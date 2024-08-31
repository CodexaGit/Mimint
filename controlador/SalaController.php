<?php
require_once('../modelo/SalaModel.php'); // Asegúrate de que esta ruta sea correcta
require_once('bd.php'); // Ajuste de la ruta

class SalaController {
    private $salaModel;

    public function __construct($conexion) {
        $this->salaModel = new SalaModel($conexion);
    }

    public function listarSalas() {
        try {
            return $this->salaModel->obtenerSalas();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function obtenerSala($nombre) {
        try {
            return $this->salaModel->obtenerSala($nombre);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
?>