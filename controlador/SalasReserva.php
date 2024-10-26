<?php
require_once('bd.php');
require_once('SalaController.php');
header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Acción no definida'];

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'];
            $controller = new SalaController($conexion);

            switch ($accion) {
                case 'listarSalas':
                    $response = $controller->listarSalas();
                    break;

                case 'obtenerSala':
                    if (isset($_POST['nombre'])) {
                        $nombre = $_POST['nombre'];
                        $response = $controller->obtenerSala($nombre);
                    } else {
                        $response = ['status' => 'error', 'message' => 'Nombre de sala no proporcionado'];
                    }
                    break;

                case 'agregarSala':
                    if (isset($_POST['name']) && isset($_POST['capacidad']) && isset($_POST['localidad']) && isset($_POST['caracteristicas'])) {
                        $nombre = $_POST['name'];
                        $capacidad = $_POST['capacidad'];
                        $localidad = $_POST['localidad'];
                        $caracteristicas = $_POST['caracteristicas'];
                        $response = $controller->agregarSala($nombre, $capacidad, $localidad, $caracteristicas);
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para agregar sala'];
                    }
                    break;

                case 'actualizarSala':
                    if (isset($_POST['salaId']) && isset($_POST['capacidad']) && isset($_POST['localidad']) && isset($_POST['caracteristicas'])) {
                        $nombre = $_POST['salaId'];
                        $capacidad = $_POST['capacidad'];
                        $localidad = $_POST['localidad'];
                        $caracteristicas = $_POST['caracteristicas'];
                        $response = $controller->actualizarSala($nombre, $capacidad, $localidad, $caracteristicas);
                    } else {
                        $response = ['status' => 'error', 'message' => 'Datos incompletos para actualizar sala'];
                    }
                    break;

                case 'eliminarSala':
                    if (isset($_POST['nombre'])) {
                        $nombre = $_POST['nombre'];
                        $response = $controller->eliminarSala($nombre);
                    } else {
                        $response = ['status' => 'error', 'message' => 'Nombre de sala no proporcionado'];
                    }
                    break;

                default:
                    $response = ['status' => 'error', 'message' => 'Acción no válida'];
                    break;
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Acción no definida'];
        }
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
?>