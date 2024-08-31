 <?php

header('Content-Type: application/json');

/*
ERROR 0: No se pudo realizar la consulta
ERROR 1: No se encontró el usuario
ERROR 2: Usuario aun no aceptado por administrador
ERROR 3: Usuario ya existente
*/

// Verificar si los parámetros POST están presentes
if (!isset($_POST['documento']) || !isset($_POST['contrasena'])) {
    echo json_encode(["error" => "Parámetros incompletos"]);
    exit;
}

$documento = $_POST['documento'];
$contrasena = $_POST['contrasena'];

require_once('../controlador/AuthController.php');

$controller = new AuthController($conexion);
$response = $controller->autenticarUsuario($documento, $contrasena);

echo json_encode($response);

$conexion->close(); // Cierre de conexión

?>