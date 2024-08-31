<?php
require_once('../controlador/EquipamientoController.php');
require_once('../controlador/bd.php'); // Asegúrate de incluir la conexión a la base de datos

header('Content-Type: application/json');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $controller = new EquipamientoController($conexion);
    $resultados = $controller->buscarEquipamiento($query);
    
    // Elimina los registros de depuración
    echo json_encode($resultados);
} else {
    echo json_encode([]);
}
?>