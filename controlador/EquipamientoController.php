<?php
require_once('../modelo/EquipamientoModel.php');

class EquipamientoController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function buscarEquipamiento($query) {
        $model = new EquipamientoModel($this->conexion);
        return $model->buscarEquipamiento($query);
    }

    public function listarEquipamiento() {
        $model = new EquipamientoModel($this->conexion);
        return $model->obtenerEquipamiento();
    }
}
?>