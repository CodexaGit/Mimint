<?php
require_once('../controlador/UsuarioController.php');
require_once('../controlador/bd.php');

// Crear una instancia del controlador de usuario
$usuarioController = new UsuarioController($conexion);

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    switch ($accion) {
        case 'listarUsuarios':
            $estado = $_POST['estado'] ?? 'Aprobado';
            $filtro = $_POST['filtro'] ?? 'asc';
            $busqueda = $_POST['busqueda'] ?? null;
            $usuarios = $usuarioController->listarUsuarios($estado, $filtro, $busqueda);
            echo json_encode($usuarios); // Aquí devolvemos directamente el resultado de listarUsuarios
            break;

        case 'rechazarUsuario':
            $documento = $_POST['documento'];
            $query = "UPDATE usuario SET estado = 'Rechazado' WHERE documento = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param('s', $documento);
            if ($stmt->execute()) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al rechazar el usuario.']);
            }
            $stmt->close();
            break;

        default:
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
            break;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
$conexion->close();
?>