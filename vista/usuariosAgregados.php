<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>Pagina Mimit</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<nav>
        <div class="menu-btn">
            <p class="separadorUsuario nombreMenu"></p>
            <img src="img/menu.png" class="menu-icon">
        </div> 
        <ul class="nav-links">
        
        </ul>
</nav>
<section>
    <p class="tituloUsu">USUARIOS AGREGADOS</p>
    <div class="search-container">
        <img src="img/lupa.png" alt="" class="lupaP" id="search-button">
        <form class="buscadorReservas" id="search-form">
            <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
            <input type="submit" value="" hidden>
        </form>
    </div>
    <button id="filtro" class="filtro">Ascendente</button>
    <div id="usuarios-container"></div>
</section>

<script src="js/verificar_sesion.js"></script>
<script src="js/menu.js"></script>
<script src="js/eliminarUsuario.js"></script>
</body>
</html>