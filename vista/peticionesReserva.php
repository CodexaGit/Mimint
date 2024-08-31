<?php
require_once('../controlador/bd.php'); // Incluir bd.php para obtener la conexión

// Función para convertir horas en formato HH:MM a minutos
function convertirHorasAMinutos($hora) {
    list($horas, $minutos) = explode(':', $hora);
    return $horas * 60 + $minutos;
}

// Función para convertir minutos a horas enteras
function convertirMinutosAHorasEnteras($minutos) {
    return floor($minutos / 60);
}

// Realizar la consulta a la base de datos para obtener las peticiones de reservas junto con el nombre completo del usuario
$query = "
    SELECT reserva.*, CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombre_completo
    FROM reserva
    JOIN usuario ON reserva.docente = usuario.documento
    WHERE reserva.estado = 'pendiente'
";
if (isset($_POST['buscar'])) {
    $busqueda = $conexion->real_escape_string($_POST['busqueda']);
    $query .= " AND (usuario.nombre LIKE '%$busqueda%' OR usuario.apellido LIKE '%$busqueda%' OR reserva.descripcion LIKE '%$busqueda%')";
}
$result = $conexion->query($query);

$resultado = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $resultado[] = $row;
    }
}

// Lógica para aceptar o denegar reservas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['aceptar'])) {
        $id = $_POST['id'];
        $query = "UPDATE reserva SET estado = 'Aprobado' WHERE id = '$id'";
        $conexion->query($query);
        header("Location: peticionesReserva.php");
        exit;
    } elseif (isset($_POST['denegar'])) {
        $id = $_POST['id'];
        $query = "UPDATE reserva SET estado = 'Rechazado' WHERE id = '$id'";
        $conexion->query($query);
        header("Location: peticionesReserva.php");
        exit;
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
    <p class="tituloUsu">PETICIONES DE RESERVAS</p>
    <div class="search-container">
        <img src="img/lupa.png" alt="" class="lupaP" id="search-button">
        <form class="buscadorReservas" method="post">
            <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
            <input type="submit" value="" hidden name="buscar">
        </form>
        <img src="img/usuario.png" alt="" class="imgBlue">
        <a href="peticionesUsuarios.php">
            <img src="img/switchBlanco.png" alt="" class="switch">
        </a>
        <img src="img/pizarraBlanca.png" alt="" class="imgBlue">
    </div>
    <div class="results">
        <!-- los resultados de la búsqueda -->
    </div>

    <?php if (!empty($resultado)): ?>
        <?php foreach ($resultado as $peticion): ?>
        <div class="tablaP2">
            <div class="tituloTablaP2">
                <h1><?php echo htmlspecialchars($peticion['nombre_completo'] ?? ''); ?></h1>
                <p class="P">ID: <?php echo htmlspecialchars($peticion['id'] ?? ''); ?></p>
            </div>
            <div class="datos2">
                <div class="datoNum">
                    <p>INICIO DE RESERVA:</p>
                    <p class="num"><?php echo htmlspecialchars($peticion['horainicio'] ?? ''); ?></p>
                    <p class="txt">CANT. DE PERSONAS:</p>
                    <p class="num"><?php echo htmlspecialchars($peticion['cantidadpersonas'] ?? ''); ?></p>
                    <p class="txt">DURACION:</p>
                    <p class="num">
                        <?php 
                        if (!empty($peticion['horafin']) && !empty($peticion['horainicio'])) {
                            $inicioMinutos = convertirHorasAMinutos($peticion['horainicio']);
                            $finMinutos = convertirHorasAMinutos($peticion['horafin']);
                            $duracionMinutos = $finMinutos - $inicioMinutos;
                            $duracionHoras = convertirMinutosAHorasEnteras($duracionMinutos);
                            echo $duracionHoras . ' horas';
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </p>
                </div>

                <div class="datoTxt">
                    <p>DESCRIPCION:</p>
                    <p class="p2 txtp2"><?php echo nl2br(htmlspecialchars($peticion['descripcion'] ?? '')); ?></p>
                    <p>SALA:</p>
                    <p class="txt2 p2"><?php echo htmlspecialchars($peticion['sala'] ?? ''); ?></p>
                    <div class="sino">
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($peticion['id']); ?>">
                            <h4>ACEPTAR</h4>
                            <button type="submit" name="aceptar" class="elegir">
                            </button>
                            <h4>DENEGAR</h4>
                            <button type="submit" name="denegar" class="mal">
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay peticiones de reservas.</p>
    <?php endif; ?>
</section>

<script src="js/menu.js"></script>
</body>
</html>