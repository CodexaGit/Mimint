<?php
session_start(); // Asegúrate de que la sesión esté iniciada al principio

require_once('UsuarioController.php');
require_once('bd.php');

$usuarioController = new UsuarioController($conexion);
$response = array();

$usuario = $usuarioController->verificarSesion();
if ($usuario) {
    $_SESSION['nombreUsuario'] = $usuario['nombre'];
    $_SESSION['documento'] = $usuario['documento'];
    $_SESSION['rol'] = $usuario['rol'];
    $response['nombreUsuario'] = $usuario['nombre'];
    $response['documento'] = $usuario['documento'];
    $response['rol'] = $usuario['rol'];
} else {
    $response['error'] = "Usuario no ha iniciado sesión";
}

echo json_encode($response);
?>