<?php
// Procesamiento del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once('../controlador/SalaController.php');
    require_once('../controlador/bd.php'); // Incluir bd.php para obtener la conexión
    $controller = new SalaController($conexion);

    $nombre = $_POST['name'];
    $capacidad = $_POST['capacidad'];
    $localidad = $_POST['localidad'];
    $caracteristicas = implode(", ", $_POST['caracteristicas']);

    $resultado = $controller->agregarSala($nombre, $capacidad, $localidad, $caracteristicas);

    if ($resultado) {
        echo "<script>alert('Sala agregada exitosamente');</script>";
    } else {
        echo "<script>alert('Error al agregar la sala');</script>";
    }

    $conexion->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de Mimit</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
</head>
<body>
    <div id="popupHECHO" class="ocultoHECHO">
        DATOS GUARDADOS...
    </div>
    <nav>
        <div class="menu-btn">
            <p class="apartado">SALAS</p>
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

    <section>
        <div class="agregar">
            <h1>AGREGAR SALA</h1>
            <img class="mas" src="img/Mas.png" alt="" id="openForm">
        </div>

        <!-- POP UP -->
        <div class="popup" id="popup">
            <div class="popup-content">
                <span id="closePopup" class="close-btn">&times;</span>
                <div class="formUP">
                    <div class="formUP2">
                        <form action="salas.php" method="POST">
                            <div class="formDIV t">
                                <label>NOMBRE/IDENTIFICADOR</label>
                                <input type="text" id="name" name="name" required>
                                <hr class="txtSeparado">
                                <label>CAPACIDAD</label>
                                <input type="number" id="capacidad" name="capacidad" required>
                                <hr class="txtSeparado">
                                <label>LOCALIDAD</label>
                                <input type="text" id="localidad" name="localidad" required>
                                <hr>
                            </div>
                            <div class="formDIV l">
                                <label>CARACTERISTICAS</label>
                                <div class="caracTODO">
                                    <div class="tablaCARACT">
                                        <input type="text" name="caracteristicas[]" required>
                                        <hr>
                                        <input type="text" name="caracteristicas[]" required>
                                        <hr>
                                    </div>
                                    <div class="botones q">
                                        <img src="img/Menos.png" alt="Quitar">
                                        <img src="img/Mas.png" alt="Agregar">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btnCaract" id="mostrarPopup">ACEPTAR</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="contenedor">
            <div class="salas">
                <?php
                require_once('../controlador/SalaController.php');
                require_once('../controlador/bd.php'); // Incluir bd.php para obtener la conexión
                
                $controller = new SalaController($conexion);
                $salas = $controller->listarSalas();
                
                if ($salas->num_rows > 0) {
                    while ($sala = $salas->fetch_assoc()) {
                        echo "<div class='item'>
                                <div class='fondo'>
                                    <p>" . htmlspecialchars($sala['nombre']) . "</p>
                                    <img src='img/Menos.png' alt='Eliminar Sala' class='menos'>
                                </div>
                              </div>";
                    }
                } else {
                    echo "<p>No hay salas disponibles.</p>";
                }
                
                $conexion->close();
                ?>
            </div>
        </div>
    </section>

    <script src="js/menu.js"></script>
</body>
</html>