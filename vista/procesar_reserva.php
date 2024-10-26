<?php
require_once('../controlador/ReservaController.php');
require_once('../controlador/bd.php'); // Asegúrate de incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservaController = new ReservaController($conexion);
    $reservaController->procesarReserva($_POST);
}
?>