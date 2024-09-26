<?php
require_once('../modelo/UsuarioModel.php');

class UsuarioController {
    private $usuarioModel;

    public function __construct($conexion) {
        $this->usuarioModel = new UsuarioModel($conexion);
    }

    public function listarUsuarios($estado = 'aprobado', $filtro = 'asc', $busqueda = null) {
        try {
            return $this->usuarioModel->obtenerUsuarios($estado, $filtro, $busqueda);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function aceptarUsuario($documento, $rol) {
        return $this->usuarioModel->actualizarEstado($documento, $rol, 'Aprobado');
    }

    public function denegarUsuario($documento, $rol) {
        return $this->usuarioModel->actualizarEstado($documento, $rol, 'Rechazado');
    }

    public function verificarSesion() {
        session_start();
        if (isset($_SESSION['documentoUsuario'])) {
            $documentoUsuario = $_SESSION['documentoUsuario'];
            return $this->usuarioModel->obtenerUsuarioPorDocumento($documentoUsuario);
        } else {
            return null;
        }
    }

    // Método para manejar respuestas AJAX
    public function responderJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>