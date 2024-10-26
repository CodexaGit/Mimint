<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Página Mimint</title>
</head>
<body>

<nav>
    <div class="menu-btn">
        <p class="separadorUsuario nombreMenu"></p>
        <img src="img/menu.png" class="menu-icon">
    </div>
    <ul class="nav-links">
        <!-- Aquí puedes agregar los enlaces del menú -->
    </ul>
</nav>
<section>
    <p class="tituloUsu">PETICIONES DE USUARIOS</p>
    
    <div class="search-container">
        <div class="switchPeticiones anchoTotal">
            <img src="img/lupa.png" alt="Buscar" class="lupaP" id="search-button">
            <form class="buscadorReservas" id="search-form">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden>
            </form>
        </div>
        <div class="switchPeticiones">
            <img src="img/usuarioBlue.png" alt="Usuario" class="imgBlue">
            <a href="peticionesReserva.php">
                <img src="img/switchBlue.png" alt="Cambiar" class="switch">
            </a>
            <img src="img/pizarraBlue.png" alt="Pizarra" class="imgBlue">
        </div>
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