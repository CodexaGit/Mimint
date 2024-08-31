<html>
<head>
<title>Listado de Reservas</title>
</head>

<body>

<?php
require_once('../controlador/ReservaController.php');

echo "<table width='500' border='1'><tr><th>id</th><th>dia</th><th>horainicio</th><th>horafin</th><th>cantidadpersonas</th><th>descripcion</th><th>docente</th><th>sala</th></tr>";
while ($array_registro = $resultado->fetch_assoc()) {
    echo "<tr><td>".$array_registro['id']."</td><td>".$array_registro['dia']."</td><td>".$array_registro['horainicio']."</td><td>".$array_registro['horafin']."</td><td>".$array_registro['cantidadpersonas']."</td><td>".$array_registro['descripcion']."</td><td>".$array_registro['docente']."</td><td>".$array_registro['sala']."</td></tr>";
}
echo "</table>";

$resultado->free(); // Liberación de los resultados de la consulta
$conexion->close(); // Cierre de conexión
?>

</body>
</html>