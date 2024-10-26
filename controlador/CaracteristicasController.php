<?php
require_once('../modelo/CaracteristicasModel.php');
require_once('bd.php');

class CaracteristicasController {
    private $caracteristicasModel;

    public function __construct($conexion) {
        $this->caracteristicasModel = new CaracteristicasModel($conexion);
    }

    public function listarCaracteristicas() {
        return $this->caracteristicasModel->obtenerCaracteristicas();
    }

    public function listarCaracteristicasPorSala($id){
        return $this->caracteristicasModel->obtenerCaracteristicasPorSala($id);

    }
    public function obtenerCaracteristicas($nombre) {
        try {
            $caracteristicas = $this->caracteristicasModel->obtenerCaracteristicas($nombre);
            if ($caracteristicas) {
                return ['status' => 'success', 'data' => $caracteristicas];
            } else {
                return ['status' => 'error', 'message' => 'Caracteristicas no encontrada'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function agregarCaracteristicas($sala, $caracteristica) {
        try {
            $resultado = $this->caracteristicasModel->agregarCaracteristicas($sala, $caracteristica);
            if ($resultado === true) {
                return ['status' => 'success', 'message' => 'Característica agregada exitosamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Error al agregar la característica.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function modificarCaracteristicas($sala, $caracteristicaNueva, $caracteristicaVieja) {
        try {
            $resultado = $this->caracteristicasModel->actualizarCaracteristicas($sala, $caracteristicaNueva, $caracteristicaVieja);
            if ($resultado === true) {
                return ['status' => 'success', 'message' => 'Característica actualizada exitosamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Error al actualizar la característica.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function eliminarCaracteristicas($sala, $caracteristica) {
        try {
            $resultado = $this->caracteristicasModel->eliminarCaracteristicas($sala, $caracteristica);
            if ($resultado === true) {
                return ['status' => 'success', 'message' => 'Característica eliminada exitosamente.'];
            } else {
                return ['status' => 'error', 'message' => 'Error al eliminar la característica.'];
            }
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}

if(isset($_POST['accion']) && $_POST['accion'] == 'obtenerCaracteristicas') {
    $controller = new CaracteristicasController($conexion);
    $id = $_POST['id'];
    $caracteristicas = $controller->listarCaracteristicas();
    echo json_encode($caracteristicas);
}
?>