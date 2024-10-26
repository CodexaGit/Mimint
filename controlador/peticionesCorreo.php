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
    $id = $_POST['id'];
    $accion = $_POST['accion'];
    $mensaje = $_POST['mensaje'];
    $cedula = $_POST['cedula'];
    $sala = $_POST['salaReserva'];
    $dia = $_POST['dia'];
    // Validar los datos recibidos
    if (empty($id) || empty($accion) || empty($cedula)) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    $usuarioModel = new UsuarioModel($conexion);

    // Verificar si el usuario existe
    $usuario = $usuarioModel->obtenerUsuarioPorDocumento($cedula);
    if (!$usuario) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        exit;
    }

    // Enviar el correo electrónico con la información de la reserva usando PHPMailer
    $emailDestino = $usuario['email'];
    $email = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP
        $email->isSMTP();
        $email->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
        $email->SMTPAuth = true;
        $email->Username = $emailUsername; // Tu dirección de correo de Gmail
        $email->Password = $emailPassword; // Tu contraseña de aplicación de Gmail
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $email->Port = 587;

        if ($accion == "denegar") {
            $accion = "Reserva rechazada";
        } else {
            $accion = "Reserva aprobada";
        }

        // Configuración del correo
        $email->CharSet = 'UTF-8'; // Establecer la codificación de caracteres a UTF-8
        $email->setFrom($emailUsername, 'Codexa');
        $email->addAddress($emailDestino); // Añadir el destinatario
        $email->Subject = $accion . " en " . $sala; // Asunto del correo
        $email->isHTML(true); // Establecer el formato del correo a HTML
        

        if($mensaje == "") {
            $mensaje = "Sin mensaje";
        }
        $email->Body = "
            <div style='text-align: center; font-family: Arial, sans-serif; background: linear-gradient(145deg, #64ccc5 0%, #04364a 100%);'>
                <h1 style='font-size: 50px; margin-bottom: 20px; color: #fff; padding: 25px;'>$accion en $sala</h1>
                <p style='font-size: 18px; color: #fff; letter-spacing: 2px;'>Sala de reserva: $sala</p>
                <br>
                <p style='font-size: 18px; color: #fff; letter-spacing: 2px;'>Dia de la reserva: $dia</p>
                <br>
                <p style='font-size: 18px; color: #fff; letter-spacing: 2px;'>Razón: $mensaje</p>
                <br>
            </div>
        ";

        $email->send();
        echo json_encode(['status' => 'success', 'message' => 'Se ha enviado un correo con el estado de la reserva']);
    } catch (Exception $e) {
        error_log("Error al enviar el correo: " . $email->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => 'Hubo un problema al enviar el correo: ' . $email->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
}
?>