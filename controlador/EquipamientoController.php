<?php
require_once('../modelo/EquipamientoModel.php');
require_once('bd.php');

class EquipamientoController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function buscarEquipamiento($query) {
        $model = new EquipamientoModel($this->conexion);
        return $model->buscarEquipamiento($query);
    }

    public function buscarEquipamientoTodos($query) {
        $model = new EquipamientoModel($this->conexion);
        return $model->buscarEquipamientoTodos($query);
    }

    public function listarEquipamiento() {
        $model = new EquipamientoModel($this->conexion);
        return $model->obtenerEquipamiento();
    }

    public function agregarEquipamiento($nombre, $cantidad) {
        $model = new EquipamientoModel($this->conexion);
        return $model->agregarEquipamiento($nombre, $cantidad);
    }

    public function modificarEquipamiento($nombre, $cantidad) {
        $model = new EquipamientoModel($this->conexion);
        return $model->modificarEquipamiento($nombre, $cantidad);
    }

    public function obtenerEquipamientoPorNombre($nombre) {
        $model = new EquipamientoModel($this->conexion);
        return $model->obtenerEquipamientoPorNombre($nombre);
    }

    public function obtenerEquipamientoPorId($id) {
        $model = new EquipamientoModel($this->conexion);
        return $model->obtenerEquipamientoPorId($id);
    }
    
    public function eliminarEquipamiento($nombre) {
        $model = new EquipamientoModel($this->conexion);
        return $model->eliminarEquipamiento($nombre);
    }

    public static function manejarSolicitudBusqueda($conexion) {
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $controller = new EquipamientoController($conexion);
            $resultados = $controller->buscarEquipamiento($query);
            echo json_encode($resultados);
        } else {
            echo json_encode([]);
        }
    }

    public static function manejarSolicitudListar($conexion) {
        $controller = new EquipamientoController($conexion);
        $resultados = $controller->listarEquipamiento();
        echo json_encode($resultados);
    }
}

// Manejo de solicitudes AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header('Content-Type: application/json');
    $response = ['status' => 'error', 'message' => 'Acción no definida'];

    try {
        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'];
            $controller = new EquipamientoController($conexion);

            switch ($accion) {
                case 'listarEquipamiento':
                    $equipamiento = $controller->listarEquipamiento();
                    $response = ['status' => 'success', 'equipamiento' => $equipamiento];
                    break;
                case 'obtenerEquipamiento':
                    if (isset($_POST['id'])) {
                        $id = $_POST['id'];
                        $equipamiento = $controller->obtenerEquipamientoPorId($id);
                        $response = ['status' => 'success', 'equipamiento' => $equipamiento];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para obtener equipamiento'];
                    }
                    break;
                case 'modificarEquipamiento':
                    if (isset($_POST['nombre']) && isset($_POST['cantidad'])) {
                        $nombre = $_POST['nombre'];
                        $cantidad = $_POST['cantidad'];
                        $resultado = $controller->modificarEquipamiento($nombre, $cantidad);
                        $response = $resultado;
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para modificar equipamiento'];
                    }
                    break;

                case 'eliminarEquipamiento':
                    if (isset($_POST['nombre'])) {
                        $nombre = $_POST['nombre'];
                        $resultado = $controller->eliminarEquipamiento($nombre);
                        $response = $resultado;
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para eliminar equipamiento'];
                    }
                    break;

                case 'agregarEquipamiento':
                    if (isset($_POST['nombre']) && isset($_POST['cantidad'])) {
                        $nombre = $_POST['nombre'];
                        $cantidad = $_POST['cantidad'];
                        $resultado = $controller->agregarEquipamiento($nombre, $cantidad);
                        $response = $resultado;
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para agregar equipamiento'];
                    }
                    break;

                case 'buscarEquipamiento':
                    if (isset($_POST['busqueda'])) {
                        $busqueda = $_POST['busqueda'];
                        $equipamiento = $controller->buscarEquipamiento($busqueda);
                        $response = ['status' => 'success', 'equipamiento' => $equipamiento];
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para buscar equipamiento'];
                    }
                    break;
                case 'buscarEquipamientoTodos':
                        if (isset($_POST['busqueda'])) {
                            $busqueda = $_POST['busqueda'];
                            $equipamiento = $controller->buscarEquipamientoTodos($busqueda);
                            $response = ['status' => 'success', 'equipamiento' => $equipamiento];
                        } else {
                            $response = ['status' => 'error', 'message' => 'Datos incompletos para buscar equipamiento'];
                        }
                        break;
                default:
                    $response = ['status' => 'error', 'message' => 'Acción no válida'];
                    break;
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Acción no definida'];
        }
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

    echo json_encode($response);
}
?>