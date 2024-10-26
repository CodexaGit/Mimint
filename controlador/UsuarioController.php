<?php
require_once('../modelo/UsuarioModel.php');

class UsuarioController {
    private $usuarioModel;

    public function __construct($conexion) {
        $this->usuarioModel = new UsuarioModel($conexion);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function listarUsuarios($estado = 'aprobado', $filtro = 'asc', $busqueda = null) {
        try {
            $resultado = $this->usuarioModel->obtenerUsuarios($estado, $filtro, $busqueda);
            $usuariosArray = [];
            while ($usuario = $resultado->fetch_assoc()) {
                $usuariosArray[] = $usuario;
            }
            return ['status' => 'success', 'data' => $usuariosArray];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function buscarUsuarios($busqueda = ''){
        try {
            $resultado = $this->usuarioModel->obtenerUsuarios(null, 'asc', $busqueda);
            if ($resultado === false) {
                throw new Exception('Error al obtener los usuarios.');
            }
            $usuariosArray = [];
            while ($usuario = $resultado->fetch_assoc()) {
                $usuariosArray[] = $usuario;
            }
            return ['status' => 'success', 'data' => $usuariosArray];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function listarTodosUsuarios() {
        try {
            $resultado = $this->usuarioModel->obtenerUsuarios();
            if ($resultado === false) {
                throw new Exception('Error al obtener los usuarios.');
            }
            $usuariosArray = [];
            while ($usuario = $resultado->fetch_assoc()) {
                $usuariosArray[] = $usuario;
            }
            return ['status' => 'success', 'data' => $usuariosArray];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function listarDocentesyAdmins() {
        try {
            $resultado = $this->usuarioModel->obtenerDocentesyAdmins();
            $usuariosArray = [];
            while ($usuario = $resultado->fetch_assoc()) {
                $usuariosArray[] = $usuario;
            }
            return ['status' => 'success', 'data' => $usuariosArray];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function aceptarUsuario($documento, $rol) {
        return $this->usuarioModel->actualizarEstado($documento, $rol, 'Aprobado');
    }

    public function denegarUsuario($documento, $rol) {
        return $this->usuarioModel->actualizarEstado($documento, $rol, 'Rechazado');
    }

    public function modificarUsuario($documento, $nombre, $apellido, $email, $rol, $estado) {
        return $this->usuarioModel->modificarUsuario($documento, $nombre, $apellido, $email, $rol, $estado);
    }

    public function eliminarUsuario($documento) {
        return $this->usuarioModel->eliminarUsuario($documento);
    }

    public function agregarUsuario($documento, $nombre, $apellido, $email, $rol, $estado) {
        return $this->usuarioModel->añadirUsuario($documento, $nombre, $apellido, $email, $rol, $estado);
    }

    public function verificarSesion() {
        if (isset($_SESSION['documentoUsuario'])) {
            $documentoUsuario = $_SESSION['documentoUsuario'];
            return $this->usuarioModel->obtenerUsuarioPorDocumento($documentoUsuario);
        } else {
            return null;
        }
    }

    public function responderJSON($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>