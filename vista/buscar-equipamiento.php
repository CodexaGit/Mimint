<?php
require_once('../controlador/EquipamientoController.php');
require_once('../controlador/bd.php'); // Asegúrate de incluir la conexión a la base de datos

header('Content-Type: application/json');

try {
    EquipamientoController::manejarSolicitudBusqueda($conexion);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>