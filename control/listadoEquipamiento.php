<html>
<head>
<title>Listado usuario</title>
</head>

<body>

<?php
require_once('bd.php');

// Ahora, vamos a mostrar los registros almacenados en la tabla 'tabla'
// Vamos a imprimir los resultados por pantalla, podria utilizar cualquier etiqueta de HTML para dicho fin.

// Guardamos en una variable la consulta para la busqueda de registros
$consulta = "SELECT * FROM equipamiento";

// Controlamos que no haya habido algún error en la consulta
// El signo ! niega valor en la variable
if (!$resultado = $conexion->query($consulta)) {
    echo "Lo sentimos, no se pudo realizar la consulta.";
    exit;
}

// 'fetch_assoc': Permite obtener una fila de resultado como un array asociativo o NULL si no hubiera filas.
// Devuelve un array de strings que representa a la fila obtenida del conjunto de resultados,
// donde cada clave del array representa el nombre de una de las columnas de éste o NULL si no hubieran más filas en dicho conjunto de resultados.
// Si dos o más columnas del resultado tienen el mismo nombre de campo, la última columna tomará precedencia. 
// Para realizarlo utilizamos un bucle que va a ir mostrando uno a uno los registros a medida que los encuentra,
// de esta menera recorremos el Array y traemos los registros encontrados en la consulta SELECT....

echo "<table width='500' border='1'><tr><th>nombre</th><th>cantidad</th></tr>";
while ($array_registro = $resultado->fetch_assoc()) {
    echo "<tr><td>".$array_registro['nombre']."</td><td>".$array_registro['cantidad']."</td></tr>";    
}
echo "</table>";

// El script automáticamente liberará el resultado y cerrará la conexión a MySQL cuando finalice.
// Por seguridad se recomienda hacer nosotros mismos la liberación y cierre
$resultado->free(); // Liberación de los resultados de la consulta
$conexion->close(); // Cierre de conexión
?>


</body>
</html>
