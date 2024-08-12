<?php

header('Content-Type: application/json');

/*
ERROR 0: No se pudo realizar la consulta
ERROR 1: No se encontró el usuario
ERROR 2: Usuario aun no aceptado por administrador
ERROR 3: Usuario ya existente
*/

// Verificar si los parámetros POST están presentes
if (!isset($_POST['documento']) || !isset($_POST['contrasena'])) {
    echo json_encode(["error" => "Parámetros incompletos"]);
    exit;
}

$documento = $_POST['documento'];
$contrasena = $_POST['contrasena'];

require_once('bd.php');

// Guardamos en una variable la consulta para la búsqueda de registros
$consulta = "SELECT * FROM usuario WHERE documento = ? AND contrasena = ?";

// Preparamos la consulta para evitar inyecciones SQL
$stmt = $conexion->prepare($consulta);
$stmt->bind_param("ss", $documento, $contrasena);

// Ejecutamos la consulta
if (!$stmt->execute()) {
    echo json_encode(["error" => 0]);
    exit;
}

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {
    $arrayResultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $estado = strtolower($arrayResultado[0]['estado']);

    if ($estado == "denegado" || $estado == "pendiente") {
        echo json_encode(["error" => 2]);
    } else if ($estado == "aprobado") {
        echo json_encode(["success" => true,  "usuario" => $arrayResultado[0]]);
    }
} else {
    echo json_encode(["error" => 1]);
}

$conexion->close(); // Cierre de conexión

?>