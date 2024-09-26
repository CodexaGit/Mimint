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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <form class="buscadorReservas" id="search-form">
            <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
            <input type="submit" value="" hidden>
        </form>
        <img src="img/usuarioBlue.png" alt="" class="imgBlue">
        <a href="peticionesReserva.php">
            <img src="img/switchBlue.png" alt="" class="switch">
        </a>
        <img src="img/pizarraBlue.png" alt="" class="imgBlue">
    </div>
    <form id="filtro-form">
        <input type="submit" name="desc" id="filtro" class="filtro" value="Descendente">
    </form>
    <div class="results" id="results">
        <!-- los resultados de la búsqueda -->
    </div>
</section>

<script src="js/menu.js"></script>
<script src="js/verificar_sesion.js"></script>
<script src="js/peticionesUsuarios.js"></script>
</body>
</html>