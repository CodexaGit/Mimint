<?php
header('Content-Type: application/json');

$response = array();

try {
    require_once('bd.php'); // Ajusta la ruta según la ubicación correcta
    require_once('../modelo/UsuarioModel.php'); // Ajusta la ruta según la ubicación correcta

    class AuthController {
        private $usuarioModel;

        public function __construct($conexion) {
            $this->usuarioModel = new UsuarioModel($conexion);
        }

        public function autenticarUsuario($documento, $contrasena) {
            try {
                $resultado = $this->usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documento, $contrasena);
                if ($resultado->num_rows > 0) {
                    $arrayResultado = $resultado->fetch_all(MYSQLI_ASSOC);
                    $estado = strtolower($arrayResultado[0]['estado']);

                    if ($estado == "denegado" || $estado == "pendiente") {
                        return ["error" => 2];
                    } else if ($estado == "aprobado") {
                        session_start();
                        $_SESSION['documentoUsuario'] = $arrayResultado[0]['documento'];
                        $_SESSION['contrasenaUsuario'] = $contrasena; // Establecer la contraseña en la sesión
                        return ["autenticado" => true, "usuario" => $arrayResultado[0]];
                    }
                } else {
                    return ["error" => 1];
                }
            } catch (Exception $e) {
                return ["error" => 0, "message" => $e->getMessage()];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $documento = $_POST['documento'];
        $contrasena = $_POST['contrasena'];

        $conexion = new mysqli('localhost', 'root', '', 'CODEXA_MIMINT');
        if ($conexion->connect_error) {
            echo json_encode(["error" => "Error de conexión: " . $conexion->connect_error]);
            exit();
        }

        $controller = new AuthController($conexion);
        $response = $controller->autenticarUsuario($documento, $contrasena);

        echo json_encode($response);

        $conexion->close();
    }
} catch (Exception $e) {
    http_response_code(500);
    $response['error'] = 'Error del servidor: ' . $e->getMessage();
    echo json_encode($response);
}
?>