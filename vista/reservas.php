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
    <title>Página Mimint</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/alertas.css">
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

<section class="todos">
    <div class="todaSalas">
        <div id="burgerTitulo">
            <h1>SALAS</h1>
        </div>
        <hr class="pisoSala">
        <!-- Aquí se mostrarán las salas dinámicamente -->
        <div id="salas-container"></div>
    </div>
    <div class="info">
        <h1 id="sala-nombre">Seleccione una sala para ver la información y reservar</h1>
        <div id="sala-info"></div>
    </div>
</section>

<script src="js/menu.js"></script>
<script src="js/verificar_sesion.js"></script>
<script src="js/reservas.js"></script>
<script>
    function toggleBurgerMenu() {
        var salasContainer = document.getElementById('salas-container');
        if (salasContainer) {
            salasContainer.classList.toggle('active');
        } else {
            console.error('El elemento #salas-container no se encontró en el DOM.');
        }
    }

    // Función para verificar el ancho de la pantalla y añadir el atributo onclick
    function verificarAnchoPantalla() {
        var burgerTitulo = document.getElementById('burgerTitulo');
        if (window.innerWidth < 1000) {
            burgerTitulo.setAttribute('onclick', 'toggleBurgerMenu()');
        } else {
            burgerTitulo.removeAttribute('onclick');
        }
    }

    // Llamar a la función al cargar la página y al redimensionar la ventana
    document.addEventListener('DOMContentLoaded', verificarAnchoPantalla);
    window.addEventListener('resize', verificarAnchoPantalla);
</script>
</body>
</html>