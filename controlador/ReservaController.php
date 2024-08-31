<?php
require_once('../modelo/ReservaModel.php'); // Asegúrate de que esta ruta sea correcta
require_once('bd.php'); // Ajuste de la ruta

class ReservaController {
    private $reservaModel;

    public function __construct($conexion) {
        $this->reservaModel = new ReservaModel($conexion);
    }

    public function listarReservas() {
        try {
            return $this->reservaModel->obtenerReservas();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function crearReserva($dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala) {
        return $this->reservaModel->crearReserva($dia, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala);
    }

    public function agregarEquipamiento($reserva_id, $nombre, $cantidad) {
        return $this->reservaModel->agregarEquipamiento($reserva_id, $nombre, $cantidad);
    }
}

// Crear una instancia del controlador y listar reservas
$controller = new ReservaController($conexion);
?>