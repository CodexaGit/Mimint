<?php
// Conectarse a y seleccionar una base de datos de MySQL llamada ta1_conexionprueba
// La clase 'mysqli' representa una conexión entre PHP y una base de datos MySQL y tiene 4 parámetros: 
// servidor, usuario de bd, contraseña de bd, nombre de base de datos.
// Nombre de host: 127.0.0.1, nombre de usuario: root, contraseña: '', bd: CODEXA_MIMINT
$conexion = new mysqli('localhost', 'root', '', 'CODEXA_MIMINT');

// En el caso de que no permita conectar informará que existe un error 'connect_errno', fallando así el intento de conexión
// 'connect_errno': Devuelve el código de error de la última llamada
// Mediante una estructura de control y con el valor almacenado dentro de la variable $conexion
// vamos mostrando los mensajes correspondientes.
if ($conexion->connect_errno) {
    // La conexión falló.
    // No se debe revelar información delicada pero si mostrar un mensaje al usuario:
    echo "Lo sentimos, este sitio web está experimentando problemas.<br>";

    // Algo que no se debería de hacer en un sitio público, aunque este ejemplo lo mostrará
    // de todas formas, es imprimir información relacionada con errores de MySQL -- se podría registrar
    echo "Error: Fallo al conectarse a MySQL debido a: <br>";
    echo "Error Nro: " . $conexion->connect_errno . "<br>";
    echo "Error: " . $conexion->connect_error . "<br>";
	//'connect_error': Devuelve una cadena con la descripción del último error de conexión
    
    // Podría ser conveniente mostrar algo interesante, aunque nosotros simplemente saldremos
    exit;
}
?>