<?php

$conexion = new mysqli('localhost', 'root', '', 'CODEXA_MIMINT');


if ($conexion->connect_errno) {

    echo "Lo sentimos, este sitio web está experimentando problemas.<br>";


    echo "Error: Fallo al conectarse a MySQL debido a: <br>";
    echo "Error Nro: " . $conexion->connect_errno . "<br>";
    echo "Error: " . $conexion->connect_error . "<br>";

    exit;
}
?>