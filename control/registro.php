<?php
/*
ERROR 0: Error en la consulta
ERROR 1: No se encontró el usuario
ERROR 2: Usuario aun no aceptado por administrador
ERROR 3: Usuario ya existente
*/

$documento=$_POST['documento'];
$contrasena=$_POST['contrasena'];
$email=$_POST['email'];
$nombreCompleto = explode(" ", $_POST['nombre']); //Se separa el nombre completo a partir de los espacios 
$nombre=$nombreCompleto[0]; //Se le asigna el primer valor del array nombreCompleto a nombre
$apellido=$nombreCompleto[1]; //Se le asigna el segundo valor a apellido

require_once('bd.php');

// Ahora, vamos a mostrar los registros almacenados en la tabla 'tabla'
// Vamos a imprimir los resultados por pantalla, podria utilizar cualquier etiqueta de HTML para dicho fin.

// Guardamos en una variable la consulta para la busqueda de registros

$consulta = "SELECT * FROM usuario WHERE documento = ".$documento.";";

// Controlamos que no haya habido algún error en la consulta
// El signo ! niega valor en la variable
if (!$resultado = $conexion->query($consulta)) {
    echo json_encode(["error" => 0]);
    exit;
}


if ($resultado->num_rows > 0){
    echo json_encode(["error" => 3]);
} else{
    $consulta = "INSERT INTO usuario(documento, email, contrasena, nombre, apellido, rol, estado) VALUES('".$documento."', '".$email."', '".$contrasena."', '".$nombre."','".$apellido."','', 'pendiente');";
    if (!$resultado = $conexion->query($consulta)) {
        echo json_encode(["error" => 0]);
    }else{
        echo json_encode(["registrado" => true]);
        header ("Location: ../vista/login.html?registro=true");
    }
}

$conexion->close(); // Cierre de conexión

?>
