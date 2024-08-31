<?php
require_once('../controlador/bd.php'); // Incluir bd.php para obtener la conexión
require_once('../controlador/UsuarioController.php');

$controller = new UsuarioController($conexion);

$filtro = isset($_POST['desc']) ? 'asc' : 'desc';
$busqueda = isset($_POST['buscar']) ? $_POST['busqueda'] : null;
$estado = 'pendiente'; // Filtrar por estado pendiente
$peticiones = $controller->listarUsuarios($estado, $filtro, $busqueda);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aceptar'])) {
        $documento = $_POST['documento'];
        $rol = $_POST['rol'];
        if ($controller->aceptarUsuario($documento, $rol)) {
            header("Location: peticionesUsuarios.php");
            exit;
        } else {
            echo "Error al aceptar el usuario.";
        }
    } elseif (isset($_POST['denegar'])) {
        $documento = $_POST['documento'];
        $rol = $_POST['rol'];
        if ($controller->denegarUsuario($documento, $rol)) {
            header("Location: peticionesUsuarios.php");
            exit;
        } else {
            echo "Error al denegar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <title>Pagina Mimit</title>
</head>
<body>

<nav>
    <div class="menu-btn">
        <img src="img/menu.png" class="menu-icon">
    </div>
    <ul class="nav-links">
        <li><a href="#">MENU DE OPCIONES</a></li>
        <hr>
        <li><a href="inicio.php">INICIO</a></li>
        <hr>
        <li><a href="reuniones.php">REUNIONES</a></li>
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
        <li><a href="#">NOMBRE DE USUARIO<img src="img/usuario.png" alt="" class="usuario"></a></li>
        <hr>
    </ul>
</nav>

<section>
    <p class="tituloUsu">PETICIONES USUARIOS</p>
    
    <div class="search-container">
        <img src="img/lupa.png" alt="" class="lupaP" id="search-button">
        <form class="buscadorReservas" method="post">
            <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
            <input type="submit" value="" hidden name="buscar">
        </form>
        <img src="img/usuarioBlue.png" alt="" class="imgBlue">
        <a href="peticionesReserva.php">
            <img src="img/switchBlue.png" alt="" class="switch">
        </a>
        <img src="img/pizarraBlue.png" alt="" class="imgBlue">
    </div>
    <form method="post">
        <input type="submit" name="desc" id="filtro" class="filtro" value="Descendente">
    </form>
    <div class="results">
        <!-- los resultados de la búsqueda -->
    </div>

    <?php foreach ($peticiones as $usuario): ?>
    <div class="tablaP">
        <div class="tituloTablaP">
            <h1><?php echo htmlspecialchars($usuario['nombre']); ?></h1>
        </div>
        <div class="datosP">
            <div class="filaP">
                <p class="label P">ROL:</p>
                <form method="post">
                <select name="rol" class="valor P">
                    <option class="valor P" value="docente">DOCENTE</option>
                    <option class="valor P" value="estudiante">ESTUDIANTE</option>
                </select>
                <input type="hidden" name="documento" value="<?php echo htmlspecialchars($usuario['documento']); ?>">
                <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                <input type="hidden" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>">
                <input type="hidden" name="estado" value="<?php echo htmlspecialchars($usuario['estado']); ?>">
                <input type="hidden" name="contrasena" value="<?php echo htmlspecialchars($usuario['contrasena']); ?>">
            </div>
            <div class="filaP">
                <p class="label P">E-MAIL:</p>
                <p class="valor P"><?php echo htmlspecialchars($usuario['email']); ?></p>
            </div>
            <div class="filaP d">
                <p class="label P">DOCUMENTO:</p>
                <p class="valor P"><?php echo htmlspecialchars($usuario['documento']); ?></p>
            </div>
            <div class="filaP o">
                <div class="op">
                    <p>ACEPTAR</p>
                    <button type="submit" name="aceptar" class="elegir"></button>
                </div>
                <div class="op">
                    <p>DENEGAR</p>
                    <button type="submit" name="denegar" class="mal"></button>
                </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<script src="js/menu.js"></script>
<script src="js/filtro.js"></script>
</body>
</html>