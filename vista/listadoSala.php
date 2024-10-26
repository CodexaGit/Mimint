

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/tablas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <title>Listado</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include('alerta.php'); ?>
</head>
<body >


    <nav>
        <div class="menu-btn">
            <p class="separadorUsuario nombreMenu"></p>
            <img src="img/menu.png" class="menu-icon">
        </div>
        <ul class="nav-links">
            
        </ul>
    </nav>
    <section>

    <div class="encabezado">
<h1>Listado de Sala</h1>
<div class="redirigir">
    <a href="listadoCaracteristicas.php">Características</a>
    <a href="listadoEquipamiento.php" >Equipamiento</a>
    <a href="listadoReserva.php" >Reserva</a>
    <a href="#" class="elegido">Salas</a>
    <a href="listadoUsuario.php">Usuarios</a>
</div>

</div>
<div class="contenedorCRUD">
<?php
require_once('../controlador/bd.php');
require_once('../controlador/SalaController.php');

$salaController = new SalaController($conexion);
$message = '';
$status = '';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    if ($accion == 'modificarSala') {
        $salaController->actualizarSala($_POST['nombre'], $_POST['capacidad'], $_POST['localidad']);
    } elseif ($accion == 'eliminarSala') {
        $resultado = $salaController->eliminarSala($_POST['nombre']);
        $status = $resultado['status'];
        $message = $resultado['message'];
    } elseif ($accion == 'agregarSala') {
        $salaController->agregarSala($_POST['nombre'], $_POST['capacidad'], $_POST['localidad']);
    }
}

$salas = $salaController->listarSalasConCaracteristicas();

if ($message) {
    mostrarAlerta($status, $message);
}

echo "<table width='500'><tr><th class='izq'>nombre</th><th>capacidad</th><th>localidad</th><th>caracteristicas</th><th class='ocultar'>modificar</th><th class='ocultar'>eliminar</th></tr>";
$numero = "uno";
foreach ($salas as $sala) {
    echo "<tr class='$numero'><td>".$sala['nombre']."</td><td class='centrado'>".$sala['capacidad']."</td><td class='centrado'>".$sala['localidad']."</td><td class='centrado'>";
    if ($sala['caracteristicas'] != 0) {
        foreach ($sala['caracteristicas'] as $caracteristica) {
            echo $caracteristica."<br>";
        }
    } else {
        echo "No hay caracteristicas";
    }
    echo "</td>
    <td>
    <form method='POST'>
    <input type='hidden' value='".$sala['nombre']."' name='nombre'>
    <input type='hidden' value='".$sala['capacidad']."' name='capacidad'>
    <input type='hidden' value='".$sala['localidad']."' name='localidad'>
    <input type='hidden' value='mostrarFormularioModificar' name='accion'>
    <input type='submit' value='Modificar'>
    </form>
    </td>
    <td>
    <form method='POST' onsubmit='return confirmarEliminacion(this);'>
    <input type='hidden' value='".$sala['nombre']."' name='nombre'>
    <input type='hidden' value='eliminarSala' name='accion'>
    <input type='submit' value='Eliminar'>
    </form>
    </td>
    </tr>";    
    $numero = ($numero == "uno") ? "dos" : "uno";
}
echo "</table>";
?>

<?php
if (isset($_POST['accion']) && $_POST['accion'] == 'mostrarFormularioModificar') {
    $nombre = $_POST['nombre'];
    $capacidad = $_POST['capacidad'];
    $localidad = $_POST['localidad'];
    echo "<form class='formulario' action='' method='POST'>
    <h2>Modificar sala</h2>
    <input type='text' name='nombre' value='".$nombre."' readonly>
    <input type='number' name='capacidad' value='".$capacidad."' required>
    <input type='text' name='localidad' value='".$localidad."' required>
    <input type='hidden' value='modificarSala' name='accion'>
    <input type='submit' class='botonFormulario' value='Guardar'>
    </form>";
} else {
    ?>
    <form action="" class="formulario" method="POST">
        <h2>Agregar sala</h2>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="capacidad" placeholder="Cantidad" required>
        <input type='text' name='localidad' placeholder="Localidad" required>
        <input type="hidden" value="agregarSala" name="accion">
        <input type="submit" class="botonFormulario" value="Agregar">
    </form>
    <?php
}
$conexion->close(); // Cierre de conexión al final del script
?>
</div>

    </section>
    <script src="js/verificar_sesion.js"></script>
    <script src="js/menu.js"></script>
</body>
</html>
