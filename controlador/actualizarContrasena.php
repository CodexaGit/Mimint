<?php
require_once('../modelo/UsuarioModel.php'); // Asegúrate de que la ruta sea correcta
require_once('bd.php'); // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'] ?? null;
    $nuevaContrasena = $_POST['nuevaContrasena'] ?? null;

    // Validar los datos recibidos
    if (empty($cedula) || empty($nuevaContrasena)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios', 'data' => $_POST]);
        exit;
    }

    // Crear una instancia del modelo de usuario
    $usuarioModel = new UsuarioModel($conexion);

    // Actualizar la contraseña del usuario
    $resultado = $usuarioModel->actualizarContrasena($cedula, $nuevaContrasena);
    if ($resultado) {
        echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada correctamente']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hubo un problema al actualizar la contraseña']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>