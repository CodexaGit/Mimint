<?php

$documento=$_POST['documento'];
$contrasena=$_POST['contrasena'];


require_once('bd.php');

// Ahora, vamos a mostrar los registros almacenados en la tabla 'tabla'
// Vamos a imprimir los resultados por pantalla, podria utilizar cualquier etiqueta de HTML para dicho fin.

// Guardamos en una variable la consulta para la busqueda de registros
$consulta = "SELECT * FROM usuario WHERE documento = ".$documento." AND contrasena='".$contrasena."' ;";

// Controlamos que no haya habido algún error en la consulta
// El signo ! niega valor en la variable
if (!$resultado = $conexion->query($consulta)) {
    echo "Lo sentimos, no se pudo realizar la consulta.";
    exit;
}

/*
ERROR 1: No se encontró el usuario
ERROR 2: Usuario aun no aceptado por administrador
ERROR 3: Usuario ya existente
*/

if ($resultado->num_rows > 0){
    $arrayResultado = $resultado->fetch_all(MYSQLI_ASSOC);
    $estado = $arrayResultado[0]['estado'];
    $estado = strtolower($estado);
    echo $estado;
    if ($estado == "denegado" || $estado == "pendiente"){
        header ("Location: ../vista/login.html?error=2");
    }else if ($estado == "aprobado"){
        header ("Location: ../vista/index.html");
    }
} else{
    header ("Location: ../vista/login.html?error=1");
}

$conexion->close(); // Cierre de conexión

?>
