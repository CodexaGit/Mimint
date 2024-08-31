<?php
require_once('../modelo/bd.php');
require_once('../modelo/UsuarioModel.php');

class LoginController {
    private $usuarioModel;

    public function __construct($conexion) {
        $this->usuarioModel = new UsuarioModel($conexion);
    }

    public function iniciarSesion($documento, $contrasena) {
        try {
            $usuario = $this->usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documento, $contrasena);
            if ($usuario->num_rows > 0) {
                session_start();
                $_SESSION['idUsuario'] = $usuario->fetch_assoc()['id'];
                header('Location: ../vista/inicio.php');
            } else {
                header('Location: ../vista/login.php?error=1');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $documento = $_POST['documento'];
    $contrasena = $_POST['contrasena'];
    $controller = new LoginController($conexion);
    $controller->iniciarSesion($documento, $contrasena);
}
?>