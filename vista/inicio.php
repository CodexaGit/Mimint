<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['documentoUsuario'])) {
    // Redirige al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.php");
    exit();
}

// Recupera la información del usuario de la sesión
$documentoUsuario = $_SESSION['documentoUsuario'];

// Conectar a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'CODEXA_MIMINT');
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener el nombre del usuario
$stmt = $conexion->prepare("SELECT nombre FROM usuario WHERE documento = ?");
$stmt->bind_param("i", $documentoUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    $nombreUsuario = $fila['nombre'];
} else {
    $nombreUsuario = "Usuario";
}

$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <title>Pagina Mimit</title>
</head>
<body style="background-image: url(img/fondo1.png); background-size: cover;">

    <nav>
        <div class="menu-btn">
            <p class="apartado">INICIO</p>
            <img src="img/menu.png" class="menu-icon">
        </div>
        <ul class="nav-links">
            <li><a href="#">MENU DE OPCIONES</a></li>
            <hr>
            <li><a href="inicio.php">INICIO</a></li>
            <hr>
            <li><a href="reservas.php">RESERVAS</a></li>
            <hr>
            <li><a href="calendario.php">CALENDARIO</a></li>
            <hr>
            <li><a href="salas.php">SALAS</a></li>
            <hr>
            <li><a href="peticionesUsuarios.php">PETICIONES</a></li>
            <hr>
            <li><a href="usuariosAgregados.php">AGREGAR USUARIOS</a></li>
            <hr>
            <li><a href="areaDeReportes.php">AREA DE REPORTES</a></li>
            <hr>
            <li><a href="#"><?php echo strtoupper(htmlspecialchars($nombreUsuario));  ?><img src="img/usuario.png" alt="" class="usuario"></a></li>
            <hr>
        </ul>
    </nav>
 
    <section>
        <div class="contenido">
            <img class="logo1" src="img/logo1.png" alt="">
            <h1 class="titulo">Te damos la bienvenida a<br>Mimit <?php echo htmlspecialchars($nombreUsuario); ?></h1>
        </div>
    </section>

    <script src="js/menu.js"></script>
</body>
</html>