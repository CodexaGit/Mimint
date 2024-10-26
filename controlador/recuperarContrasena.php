<?php
require_once('../modelo/UsuarioModel.php'); // Asegúrate de que la ruta sea correcta
require_once('bd.php'); // Incluir la conexión a la base de datos
require '../modelo/phpmailer/vendor/autoload.php'; // Incluir el autoload de Composer para PHPMailer
require '../modelo/vlucas/vendor/autoload.php'; // Incluir el autoload de Composer para dotenv

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Cargar las variables de entorno desde el archivo .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Obtener las variables de entorno
$emailUsername = $_ENV['EMAIL_USERNAME'] ?? null;
$emailPassword = $_ENV['EMAIL_PASSWORD'] ?? null;

// Eliminar comillas dobles si están presentes
$emailPassword = trim($emailPassword, '"');

if (!$emailUsername || !$emailPassword) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'No se pudieron cargar las credenciales de correo electrónico'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cedula = $_POST['cedula'];
    $email = $_POST['email'];

    // Validar los datos recibidos
    if (empty($cedula) || empty($email)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Crear una instancia del modelo de usuario
    $usuarioModel = new UsuarioModel($conexion);

    // Verificar si el usuario existe
    $usuario = $usuarioModel->obtenerUsuarioPorCedulaYEmail($cedula, $email);
    if ($usuario) {
        // Generar un código de verificación
        $codigo = rand(100000, 999999); // Generar un código aleatorio de 6 dígitos

        // Enviar el correo electrónico con el código de verificación usando PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = $emailUsername; // Tu dirección de correo de Gmail
            $mail->Password = $emailPassword; // Tu contraseña de aplicación de Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuración del correo
            $mail->CharSet = 'UTF-8'; // Establecer la codificación de caracteres a UTF-8
            $mail->setFrom($emailUsername, 'Codexa');
            $mail->addAddress($email); // Añadir el destinatario
            $mail->Subject = 'Código de Verificación';
            $mail->isHTML(true); // Establecer el formato del correo a HTML
            $mail->Body = "
                <div style='text-align: center; font-family: Arial, sans-serif; background: linear-gradient(145deg, #64ccc5 0%, #04364a 100%);'>
                    <h1 style='font-size: 50px; margin-bottom: 20px;  color: #fff; padding: 25px; '>$codigo</h1>
                    <p style='font-size: 18px; color: #fff; letter-spacing: 2px; '>Este es tu código de verificación para restablecer tu contraseña. Por favor, ingrésalo en el formulario para continuar con el proceso.</p>
                </div>
            ";

            $mail->send();
            // Devolver el código de verificación al cliente para validarlo en el lado del cliente
            echo json_encode(['status' => 'success', 'message' => 'Se ha enviado un correo con el código de verificación', 'codigo' => $codigo]);
        } catch (Exception $e) {
            error_log("Error al enviar el correo: " . $mail->ErrorInfo);
            echo json_encode(['status' => 'error', 'message' => 'Hubo un problema al enviar el correo: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>