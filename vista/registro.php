<?php
require_once('../controlador/RegistroController.php');
require_once('../modelo/UsuarioModel.php');
require_once('../controlador/bd.php'); // Asegúrate de que este archivo contiene la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $documento = $_POST['documento'];
    $contrasena = $_POST['contrasena'];
    $email = $_POST['email'];
    $nombreCompleto = $_POST['nombre'];

    // Crear una nueva conexión a la base de datos
    $conexion = new mysqli('localhost', 'root', '', 'CODEXA_MIMINT');
    if ($conexion->connect_error) {
        die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
    }

    // Crear una instancia del controlador de registro
    $controller = new RegistroController($conexion);
    $resultado = $controller->registrarUsuario($documento, $contrasena, $email, $nombreCompleto);

    echo json_encode($resultado);

    if (isset($resultado['registrado']) && $resultado['registrado'] === true) {
        header("Location: login.php?registro=true");
    }

    $conexion->close(); // Cierre de conexión
}
?>