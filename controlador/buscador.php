<?php

require_once('../modelo/bd.php');
require_once('../modelo/ReservaModel.php');

$reservaModel = new ReservaModel($conexion);

$accion = $_GET['accion'] ?? $_POST['accion'] ?? '';

if ($accion === 'obtenerPeticiones') {
    $busqueda = $_GET['busqueda'] ?? '';
    $reservas = $reservaModel->obtenerPeticionesPendientes($busqueda);
    echo json_encode($reservas);
} elseif ($accion === 'aceptarReserva') {
    $id = $_POST['id'];
    // Lógica para aceptar la reserva
} elseif ($accion === 'denegarReserva') {
    $id = $_POST['id'];
    // Lógica para denegar la reserva
}
?>