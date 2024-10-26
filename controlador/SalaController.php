<?php
require_once('../modelo/SalaModel.php');
require_once('bd.php');

class SalaController {
    private $salaModel;

    public function __construct($conexion) {
        $this->salaModel = $conexion;
    }

    public function listarSalas() {
        $resultado = new SalaModel($this->salaModel);
        return $resultado->obtenerSalas();
    }

    public function listarSalasArray() {
        try {
            $resultado = new SalaModel($this->salaModel);
            $salasArray = $resultado->obtenerSalas();
            
            return ['status' => 'success', 'data' => $salasArray];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
    public function listarSalasConCaracteristicas() {
        $resultado = new SalaModel($this->salaModel);
        $salasConCaracteristicas = [];
        foreach ($resultado->obtenerSalas() as $sala) {
            if ($resultado->obtenerCaracteristicas($sala['nombre']) == null) {
                $sala['caracteristicas'] = 0;
            } else {
                $sala['caracteristicas'] = $resultado->obtenerCaracteristicas($sala['nombre']);
            }
            $salasConCaracteristicas[] = $sala;
        }
        return $salasConCaracteristicas;
    }

    public function buscarSalas($nombre) {
        $resultado = new SalaModel($this->salaModel);
        return $resultado->buscarSalas($nombre);
    }
    public function obtenerSala($nombre) {
        try {
            $resultado = new SalaModel($this->salaModel);
            $sala = $resultado->obtenerSala($nombre);

            if ($sala) {
                $sala['caracteristicas'] = $resultado->obtenerCaracteristicas($nombre);
                return ['status' => 'success', 'data' => $sala];
            } else {
                return ['status' => 'error', 'message' => 'Sala no encontrada'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function agregarSala($nombre, $capacidad, $localidad) {
        try {
            $resultado = new SalaModel($this->salaModel);
            $resultado->agregarSala($nombre, $capacidad, $localidad);
            if ($resultado === true) {
                return ['status' => 'success'];
            } else {
                return $resultado;
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function actualizarSala($nombre, $capacidad, $localidad) {
        try {
            $resultado = new SalaModel($this->salaModel);
            $resultado->actualizarSala($nombre, $capacidad, $localidad);
            if ($resultado === true) {
                return ['status' => 'success'];
            } else {
                return $resultado;
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function eliminarSala($nombre) {
        try {
            $resultado = new SalaModel($this->salaModel);
            $resultado->eliminarSala($nombre);
            
            if ($resultado) {
                return ['status' => 'success', 'message' => 'Sala eliminada exitosamente.'];
            } else {
                return ['status' => 'error', 'message' => 'No se pudo eliminar la sala.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
?>