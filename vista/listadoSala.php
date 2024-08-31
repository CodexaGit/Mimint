<html>
<head>
<title>Listado de Salas</title>
</head>

<body>

<?php
require_once('../controlador/SalaController.php');

echo "<table width='500' border='1'><tr><th>nombre</th><th>capacidad</th><th>localidad</th></tr>";
while ($array_registro = $resultado->fetch_assoc()) {
    echo "<tr><td>".$array_registro['nombre']."</td><td>".$array_registro['capacidad']."</td><td>".$array_registro['localidad']."</td></tr>";    
}
echo "</table>";

$resultado->free(); // Liberación de los resultados de la consulta
$conexion->close(); // Cierre de conexión
?>

</body>
</html>