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
    <title>Document</title>
</head>
<body>
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
            <li><a href="#">NOMBRE DE USUARIO<img src="img/usuario.png" alt="" class="usuario"></a></li>
            <hr>
        </ul>
    </nav>

    <div class="tituloAgregar"> <h1>AREA DE REPORTES</h1> </div>

    <div class="descripcionAgregar">
        <p id="Seccion">Seccion</p>
    </div>
    <div class="inputsAgregar">
        <form method="POST" action="../controlador/ReporteController.php">
            <select name="seccionReporte" id="seccion" required>
                <option value="usuario">Usuario</option>
                <option value="reserva">Reserva</option>
                <option value="sala">Sala</option>
            </select>
            
            <div class="botonAgregar">
                <input type="submit" name="boton" value="DESCARGAR PDF">
            </div>
        </form>   
    </div>

    <script src="js/menu.js"></script>
</body>
</html>