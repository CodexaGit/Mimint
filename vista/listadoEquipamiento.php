<html>
<head>
<title>Listado de Equipamiento</title>
</head>

<body>

<?php
require_once('../controlador/EquipamientoController.php');

echo "<table width='500' border='1'><tr><th>nombre</th><th>cantidad</th></tr>";
while ($array_registro = $resultado->fetch_assoc()) {
    echo "<tr><td>".$array_registro['nombre']."</td><td>".$array_registro['cantidad']."</td></tr>";    
}
echo "</table>";

$resultado->free(); // Liberación de los resultados de la consulta
$conexion->close(); // Cierre de conexión
?>

</body>
</html>