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

if ($resultado->num_rows > 0){ //Verifica si existe algun usuario con dicha contraseña y cedula 
    $arrayResultado = $resultado->fetch_all(MYSQLI_ASSOC); //Se guardan los datos en un array
    $estado = $arrayResultado[0]['estado']; //Se toma el estado del primer elemento (el cual deberia de ser el unico)
    $estado = strtolower($estado); //Se pone el string en minuscula
    echo $estado;
    if ($estado == "denegado" || $estado == "pendiente"){ //Se verifica si estado es denegado o pendiente
        header ("Location: ../vista/login.html?error=2"); //Se vuelve a login.html con el valor de error 2 que significa que el usuario aun no fue aceptado por el administrador
    }else if ($estado == "aprobado"){ //Se verifica si el estado del usuario es aprobado
        header ("Location: ../vista/index.html"); //Se lleva a la pagina de inicio de la aplicacion
    }
} else{
    header ("Location: ../vista/login.html?error=1"); //Se vuelve al login.html con el valor de error 1 que significa que no se encontro a un usuario con el documento y la contraseña ingresada
}

$conexion->close(); // Cierre de conexión

?>
