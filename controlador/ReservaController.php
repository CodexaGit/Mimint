<?php
require_once('../modelo/ReservaModel.php');
require_once('../modelo/UsuarioModel.php');
require_once('bd.php');

class ReservaController {
    private $reservaModel;
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
        $this->reservaModel = new ReservaModel($conexion);
    }

    public function listarReservas() {
        try {
            $reservasArray = $this->reservaModel->obtenerReservas();
            return ['status' => 'success', 'data' => $reservasArray];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function crearReserva($dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala, $equipamiento) {
        try {
            $reserva_id = $this->reservaModel->crearReserva($dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala, $equipamiento);
            return ['status' => 'success', 'message' => 'Reserva creada exitosamente', 'reserva_id' => $reserva_id];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function modificarReserva($id, $dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala) {
        try {
            $this->reservaModel->modificarReserva($id, $dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala);
            return ['status' => 'success', 'message' => 'Reserva modificada exitosamente'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function eliminarReserva($id) {
        try {
            $this->reservaModel->eliminarReserva($id);
            return ['status' => 'success', 'message' => 'Reserva eliminada exitosamente'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function procesarReserva($data) {
        session_start();

        if (!isset($_SESSION['documentoUsuario']) || !isset($_SESSION['contrasenaUsuario'])) {
            header('Location: login.php');
            exit;
        }

        $documentoUsuario = $_SESSION['documentoUsuario'];
        $contrasenaUsuario = $_SESSION['contrasenaUsuario'];
        $usuarioModel = new UsuarioModel($this->conexion);
        $resultado = $usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documentoUsuario, $contrasenaUsuario);
        $usuario = $resultado->fetch_assoc();

        if (!$usuario) {
            die('Usuario no encontrado.');
        }

        $sala = $data['sala'];
        $docente = (int)$usuario['documento'];
        $hora = $data['hora'];
        $dia = $data['dia'];
        $mes = $data['mes'];
        $duracion = $data['duracion'];
        $cantidadpersonas = $data['cantidadpersonas'];
        $descripcion = $data['descripcion'];
        $equipamiento = $data['equipamiento'];
        $cantidad = $data['cantidad'];

        $anio = date('Y');
        $fecha = "$anio-$mes-$dia";
        $horainicio = "$hora:00";
        $horafin = date('H:i:s', strtotime($horainicio) + $duracion * 3600);

        $equipamiento_data = [];
        foreach ($equipamiento as $index => $equipo) {
            $equipamiento_data[] = [
                'nombre' => $equipo,
                'cantidad' => $cantidad[$index]
            ];
        }

        try {
            $reserva_id = $this->crearReserva($fecha, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala, $equipamiento_data);
            header('Location: ../vista/reservas.php?reserva=exitosa');
            exit;
        } catch (Exception $e) {
            echo "Error al realizar la reserva: " . $e->getMessage();
        }
    }

    public function obtenerReserva($id) {
        $respuesta = new ReservaModel($this->conexion);
        return $respuesta->obtenerReserva($id);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] == "obtenerReserva") {
    $respuesta = new ReservaController($conexion);
    $resultado = $respuesta->obtenerReserva($_POST['id']);
    
    echo json_encode(['status' => 'success','cedulaEnviar' => $resultado["docente"], 'salaReserva' => $resultado['sala'], 'dia' => $resultado['dia'], 'id' => $_POST['id'] , 'accion' => $_POST['accion'], 'mensaje' => $_POST['mensaje']]);
}
?>