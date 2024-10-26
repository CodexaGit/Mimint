<?php
require_once('../modelo/ReservaModel.php');
require_once('../controlador/bd.php');

class PeticionesReservaController {
    private $reservaModel;

    public function __construct($conexion) {
        $this->reservaModel = new ReservaModel($conexion);
    }

    public function obtenerPeticiones($busqueda = null) {
        return $this->reservaModel->obtenerPeticionesPendientes($busqueda);
    }

    public function aceptarReserva($id) {
        return $this->reservaModel->actualizarEstadoReserva($id, 'Aprobado');
    }

    public function denegarReserva($id) {
        return $this->reservaModel->actualizarEstadoReserva($id, 'Rechazado');
    }
}

// Manejo de la solicitud AJAX para obtener las peticiones de reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PeticionesReservaController($conexion);

    if ($accion === 'obtenerPeticiones') {
        $busqueda = $_POST['busqueda'] ?? null;
        $peticiones = $controller->obtenerPeticiones($busqueda);
        echo json_encode($peticiones);
    } elseif ($accion === 'aceptarReserva') {
        $id = $_POST['id'];
        $controller->aceptarReserva($id);
        echo json_encode(['status' => 'success']);
    } elseif ($accion === 'denegarReserva') {
        $id = $_POST['id'];
        $controller->denegarReserva($id);
        echo json_encode(['status' => 'success']);
    }
}
?>