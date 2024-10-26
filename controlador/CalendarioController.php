<?php
session_start();

if (!isset($_SESSION['documento']) || !isset($_SESSION['rol'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
    exit;
}

require_once('bd.php');
require_once('../modelo/CalendarioModel.php');
require_once('../controlador/ReservaController.php');

class CalendarioController {
    private $calendarioModel;
    private $reservaController;

    public function __construct($conexion) {
        $this->calendarioModel = new CalendarioModel($conexion);
        $this->reservaController = new ReservaController($conexion);
    }

    public function manejarFechaSeleccionada($fecha, $modoYo) {
        try {
            // Obtener reservas para la fecha seleccionada
            $reservas = $this->calendarioModel->obtenerReservasPorFecha($fecha, $modoYo);

            // Añadir un campo de color a cada reserva
            foreach ($reservas as &$reserva) {
                $reserva['color'] = '#ff9f89'; // Puedes personalizar el color aquí
            }

            // Responder con las reservas en formato JSON
            echo json_encode(['status' => 'success', 'reservas' => $reservas]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function mostrarReservas($modoYo) {
        try {
            $reservas = $this->calendarioModel->obtenerReservas($modoYo);

            foreach ($reservas as &$reserva) {
                $reserva['color'] = '#ff9f89'; // Puedes personalizar el color aquí
            }
            echo json_encode(['status' => 'success', 'reservas' => $reservas]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function eliminarReserva($id) {
        try {
            if ($_SESSION['rol'] === 'Docente') {
                $reserva = $this->calendarioModel->obtenerReservaPorId($id);
                if ($reserva['docente'] !== $_SESSION['documento']) {
                    throw new Exception("No tienes permiso para eliminar esta reserva");
                }
            }
            if ($_SESSION['rol'] === 'Admin') {
                $reserva = $this->calendarioModel->obtenerReservaPorId($id);
            }
            if ($_SESSION['rol'] === "Estudiante"){
                throw new Exception("No tienes permisos para eliminar reservas");
            }

            $this->calendarioModel->eliminarReserva($id);
            echo json_encode(['status' => 'success', 'message' => 'Reserva eliminada correctamente']);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

$controller = new CalendarioController($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modoYo = isset($_POST['modoYo']) ? filter_var($_POST['modoYo'], FILTER_VALIDATE_BOOLEAN) : false;
    if (isset($_POST['fecha'])) {
        $controller->manejarFechaSeleccionada($_POST['fecha'], $modoYo);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'mostrarReservas') {
        $controller->mostrarReservas($modoYo);
    } elseif (isset($_POST['action']) && $_POST['action'] === 'eliminarReserva' && isset($_POST['id'])) {
        $controller->eliminarReserva($_POST['id']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no válido']);
}
?>