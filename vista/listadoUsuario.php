<html>
<head>
<title>Listado de Usuarios</title>
</head>

<body>

<?php
require_once('../controlador/UsuarioController.php');

// Asumiendo que el controlador tiene un método para listar usuarios
$controller = new UsuarioController($conexion);
$resultado = $controller->listarUsuarios();

echo "<table width='500' border='1'><tr><th>Documento</th><th>Email</th><th>Contraseña</th><th>Nombre</th><th>Apellido</th><th>Rol</th><th>Estado</th></tr>";
while ($array_registro = $resultado->fetch_assoc()) {
    echo "<tr><td>".$array_registro['documento']."</td><td>".$array_registro['email']."</td><td>".$array_registro['contrasena']."</td><td>".$array_registro['nombre']."</td><td>".$array_registro['apellido']."</td><td>".$array_registro['rol']."</td><td>".$array_registro['estado']."</td></tr>";
}
echo "</table>";

$resultado->free(); // Liberación de los resultados de la consulta
$conexion->close(); // Cierre de conexión
?>

</body>
</html>