<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="css/menu.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js'></script>
    <link rel="stylesheet" href="css/calendario.css">
    <title>Calendario</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include("alerta.php")?>
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
<div class="contenedorCalendario">
<div class="titulo pantallaChica">
        <h1>CALENDARIO</h1>
    </div>
    <div id="Calendario">
        <div id='calendar'></div>
    </div>
    <div class="containerA">
        <div class="titulo pantallaGrande">
            <h1>CALENDARIO</h1>
        </div>
        <div id="agregarReunion">
            <p>AGREGAR REUNION</p>
            <a href="reservas.php"><img src="img/Boton de mas.png" alt=""></a>
        </div>
        <div id="modoYo">
            <p>MODO YO</p>
            <div >
                <input type="checkbox" id="modoYoCheckbox">
            </div>
            <div class="tooltip">
                <img id="ejemplo" src="img/Group 17.png" alt="">
                <span class="tooltiptext">
                    <p style="padding-left: 0;">ESTA OPCION HABLITARA VER SOLAMENTE LAS SALAS QUE SE REGISTRARON PARA USTED</p>
                </span>
            </div>
        </div>
        <div id="salas">

    </div>
</div>
</div>

<script src="js/verificar_sesion.js"></script>
<script src="js/menu.js"></script>
<script src="js/calendario.js"></script>
</body>
</html>