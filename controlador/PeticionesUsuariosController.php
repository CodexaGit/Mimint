<?php
require_once('../modelo/UsuarioModel.php');
require_once('../controlador/bd.php');
require_once('../controlador/UsuarioController.php');

class PeticionesUsuariosController {
    private $usuarioController;

    public function __construct($conexion) {
        $this->usuarioController = new UsuarioController($conexion);
    }

    public function obtenerPeticiones($filtro = 'desc', $busqueda = null) {
        return $this->usuarioController->listarUsuarios('pendiente', $filtro, $busqueda);
    }

    public function aceptarUsuario($documento, $rol) {
        return $this->usuarioController->aceptarUsuario($documento, $rol);
    }

    public function denegarUsuario($documento, $rol) {
        return $this->usuarioController->denegarUsuario($documento, $rol);
    }
}

// Manejo de la solicitud AJAX para obtener las peticiones de usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $controller = new PeticionesUsuariosController($conexion);

    if ($accion === 'obtenerPeticiones') {
        $filtro = $_POST['filtro'] ?? 'desc';
        $busqueda = $_POST['busqueda'] ?? null;
        $peticiones = $controller->obtenerPeticiones($filtro, $busqueda);
        $peticionesArray = [];

        // Verifica si $peticiones es un objeto de resultado de consulta SQL
        if (is_object($peticiones) && method_exists($peticiones, 'fetch_assoc')) {
            while ($peticion = $peticiones->fetch_assoc()) {
                $peticionesArray[] = $peticion;
            }
        } elseif (is_array($peticiones)) {
            // Si $peticiones es un array, itera sobre él
            $peticionesArray = $peticiones;
        }

        echo json_encode($peticionesArray);
    } elseif ($accion === 'aceptarUsuario') {
        $documento = $_POST['documento'] ?? null;
        $rol = $_POST['rol'] ?? null;
        if ($documento && $rol) {
            $controller->aceptarUsuario($documento, $rol);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
    } elseif ($accion === 'denegarUsuario') {
        $documento = $_POST['documento'] ?? null;
        $rol = $_POST['rol'] ?? null;
        if ($documento && $rol) {
            $controller->denegarUsuario($documento, $rol);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
        }
    }
}
?>