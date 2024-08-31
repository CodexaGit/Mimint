<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/calendario.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>Calendario</title>
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
    <div id="Calendario">
        <img src="img/Calendar Collapsed (Light).png" alt="">
    </div>

    <div class="containerA">
        <div id="titulo">
            <h1>CALENDARIO</h1>
        </div>
        <div id="agregarReunion">
            <p>AGREGAR REUNION</p>
            <img src="img/Boton de mas.png" alt="">
        </div>
        <div id="modoYo">
            <p>MODO YO</p>
            <img id="rectangle" src="img/Rectangle 87.png" alt="">
            <div class="tooltip">
                <img id="ejemplo" src="img/Group 17.png" alt="">
                <span class="tooltiptext">
                    <p style="padding-left: 0;">ESTA OPCION HABLITARA VER SOLAMENTE LAS SALAS QUE SE REGISTRARON PARA USTED</p>
                </span>
            </div>
        </div>
        <div id="salas">
            <?php foreach ($reuniones as $reunion): ?>
                <div class="sala">
                    <img src="img/MenosC.png" alt="">
                    <div class="texto">
                        <div><p><?php echo $reunion['sala']; ?></p></div>
                        <div><p><?php echo $reunion['hora']; ?></p></div>
                        <div><p><?php echo $reunion['duracion']; ?></p></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="js/menu.js"></script>
</body>
</html>