<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['documentoUsuario']) || !isset($_SESSION['contrasenaUsuario'])) {
    header('Location: login.php'); // Redirigir al login si no está autenticado
    exit;
}

require_once('../controlador/bd.php'); // Incluir bd.php para obtener la conexión
require_once('../controlador/SalaController.php');
require_once('../modelo/UsuarioModel.php'); // Incluir UsuarioModel.php para obtener la clase UsuarioModel

// Obtener la información del usuario autenticado
$documentoUsuario = $_SESSION['documentoUsuario'];
$contrasenaUsuario = $_SESSION['contrasenaUsuario'];
$usuarioModel = new UsuarioModel($conexion);
$usuario = $usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documentoUsuario, $contrasenaUsuario)->fetch_assoc();

if (!$usuario) {
    die('Usuario no encontrado.');
}

// Procesar la reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sala = $_POST['sala'];
    $docente = $_POST['docente'];
    $hora = $_POST['hora'];
    $dia = $_POST['dia'];
    $mes = $_POST['mes'];
    $duracion = $_POST['duracion'];
    $cantidadpersonas = $_POST['cantidadpersonas'];
    $descripcion = $_POST['descripcion'];
    $equipamiento = $_POST['equipamiento'];
    $cantidad = $_POST['cantidad'];

    // Obtener el año actual
    $anio = date('Y');

    // Formatear la fecha y hora
    $fecha = "$anio-$mes-$dia";
    $horainicio = "$hora:00";

    // Calcular la hora de fin de la reserva
    $horafin = date('H:i:s', strtotime($horainicio) + $duracion * 3600);

    // Insertar la reserva en la base de datos
    $sql = "INSERT INTO reserva (dia, horainicio, horafin, cantidadpersonas, descripcion, docente, sala) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssssss', $fecha, $horainicio, $horafin, $cantidadpersonas, $descripcion, $docente, $sala);

    if ($stmt->execute()) {
        $reserva_id = $stmt->insert_id; // Obtener el ID de la reserva insertada

        // Insertar el equipamiento requerido en la tabla 'requiere'
        foreach ($equipamiento as $index => $equipo) {
            $cantidad_equipo = $cantidad[$index];
            $sql_requiere = "INSERT INTO requiere (reserva, equipamiento, cantidad) VALUES (?, ?, ?)";
            $stmt_requiere = $conexion->prepare($sql_requiere);
            $stmt_requiere->bind_param('isi', $reserva_id, $equipo, $cantidad_equipo);
            $stmt_requiere->execute();
            $stmt_requiere->close();
        }

        // Redirigir a reservas.php después de una reserva exitosa
        header('Location: reservas.php?reserva=exitosa');
        exit;
    } else {
        echo "Error al realizar la reserva: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close();
}
?>