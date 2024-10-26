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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <p class="tituloUsu">PETICIONES DE RESERVAS</p>
    <div class="search-container">
        <div class="switchPeticiones anchoTotal">
            <img src="img/lupa.png" alt="Buscar" class="lupaP" id="search-button">
            <form class="buscadorReservas" id="search-form" method="GET" action="peticionesReserva.php">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden>
            </form>
        </div>
        <div class="switchPeticiones">
            <img src="img/usuario.png" alt="Usuario" class="imgBlue">
            <a href="peticionesUsuarios.php">
                <img src="img/switchBlanco.png" alt="Cambiar" class="switch">
            </a>
            <img src="img/pizarraBlanca.png" alt="Pizarra" class="imgBlue">
        </div>
    </div>
    <div class="results" id="results">
        <!-- los resultados de la búsqueda -->
    </div>
</section>

<script src="js/verificar_sesion.js"></script>
<script src="js/menu.js"></script>
<script src="js/peticionesReserva.js"></script>
</body>
</html>