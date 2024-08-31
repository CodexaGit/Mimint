<?php
require_once('../modelo/bd.php');
require_once('../modelo/CalendarioModel.php');

class CalendarioController {
    private $calendarioModel;

    public function __construct($conexion) {
        $this->calendarioModel = new CalendarioModel($conexion);
    }

    public function mostrarCalendario() {
        try {
            $reuniones = $this->calendarioModel->obtenerReuniones();
            include('../vista/calendario.php');
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

$controller = new CalendarioController($conexion);
$controller->mostrarCalendario();
?>