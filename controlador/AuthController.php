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
                error_log("Autenticando usuario: $documento"); // Registro de depuración
                $resultado = $this->usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documento, $contrasena);
                if ($resultado->num_rows > 0) {
                    $arrayResultado = $resultado->fetch_all(MYSQLI_ASSOC);
                    $estado = strtolower($arrayResultado[0]['estado']);
                    error_log("Estado del usuario: $estado"); // Registro de depuración

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
                error_log("Error en autenticarUsuario: " . $e->getMessage()); // Registro de depuración
                return ["error" => 0, "message" => $e->getMessage()];
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $documento = $_POST['documento'];
        $contrasena = $_POST['contrasena'];

        error_log("Datos recibidos: documento=$documento, contrasena=$contrasena"); // Registro de depuración

        $conexion = new mysqli('localhost', 'root', '', 'CODEXA_MIMINT');
        if ($conexion->connect_error) {
            error_log("Error de conexión: " . $conexion->connect_error); // Registro de depuración
            echo json_encode(["error" => "Error de conexión: " . $conexion->connect_error]);
            exit();
        }

        $controller = new AuthController($conexion);
        $response = $controller->autenticarUsuario($documento, $contrasena);

        error_log("Respuesta del controlador: " . json_encode($response)); // Registro de depuración

        // Asegurarse de que la respuesta no sea null
        if ($response === null) {
            $response = ["error" => "Respuesta del controlador es null"];
            error_log("Error: Respuesta del controlador es null"); // Registro de depuración
        }

        echo json_encode($response);

        $conexion->close();
    }
} catch (Exception $e) {
    http_response_code(500);
    $response['error'] = 'Error del servidor: ' . $e->getMessage();
    error_log("Error del servidor: " . $e->getMessage()); // Registro de depuración
    echo json_encode($response);
}
?>