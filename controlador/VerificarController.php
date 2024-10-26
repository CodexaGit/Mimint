<?php
require_once("../modelo/VerificarModel.php");
require_once("bd.php");

class VerificarController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function verificarReserva($accion, $id, $mensaje, $cedula) {
        try {
            // Añadir registro de depuración para verificar el valor de $accion
            error_log("Acción recibida: " . $accion);

            $verificarModel = new VerificarModel($this->conexion);
            $resultado = $verificarModel->agregarVerificacion($accion, $id, $mensaje, $cedula);
            if ($resultado === true) {
                return ['status' => 'success', 'message' => 'Verificacion agregada exitosamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Error al agregar la verificacion.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $id = $_POST['id'];
    $mensaje = $_POST['mensaje'];
    $cedula = $_POST['cedula'];

    // Asegurarse de que la conexión a la base de datos esté disponible
    if (!isset($conexion)) {
        die("Error de conexión: No se pudo obtener la conexión a la base de datos.");
    }

    $verificarController = new VerificarController($conexion); 
    $resultado = $verificarController->verificarReserva($accion, $id, $mensaje, $cedula);

    echo json_encode($resultado); 
}
?>