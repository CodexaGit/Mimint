

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
<h1>Listado de Equipamiento</h1>
<div class="redirigir">
    <a href="listadoCaracteristicas.php">Características</a>
    <a href="#" class="elegido">Equipamiento</a>
    <a href="listadoReserva.php" >Reserva</a>
    <a href="listadoSala.php">Salas</a>
    <a href="listadoUsuario.php">Usuarios</a>
</div>
</div>
<div class="contenedorCRUD">
<?php
require_once('../controlador/bd.php');
require_once('../controlador/EquipamientoController.php');

$equipamientoController = new EquipamientoController($conexion);
$message = '';
$status = '';

if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    if ($accion == 'modificarEquipamiento') {
        $equipamientoController->modificarEquipamiento($_POST['nombre'], $_POST['cantidad']);
    } elseif ($accion == 'eliminarEquipamiento') {
        $resultado = $equipamientoController->eliminarEquipamiento($_POST['nombre']);
        if ($resultado) {
            $status = $resultado['status'];
            $message = $resultado['message'];
        } else {
            $status = 'error';
            $message = 'Error desconocido al eliminar el equipamiento.';
        }
    } elseif ($accion == 'agregarEquipamiento') {
        $equipamientoController->agregarEquipamiento($_POST['nombre'], $_POST['cantidad']);
    }
}

$equipamientos = $equipamientoController->listarEquipamiento();

if ($message) {
    mostrarAlerta($status, $message);
}

echo "<table width='500'><tr><th class='izq'>nombre</th><th>cantidad</th><th class='ocultar'>modificar</th><th class='ocultar'>eliminar</th></tr>";
$numero =  "uno";
foreach ($equipamientos as $equipamiento) {
    
    echo "<tr class='$numero'><td >".$equipamiento['nombre']."</td><td class='centrado'>".$equipamiento['cantidad']."</td>
    <td>
    <form method='POST'>
    <input type='hidden' value='".$equipamiento['nombre']."' name='nombre'>
    <input type='hidden' value='".$equipamiento['cantidad']."' name='cantidad'>
    <input type='hidden' value='mostrarFormularioModificar' name='accion'>
    <input type='submit' value='Modificar'>
    </form>
    </td>
    <td>
    <form method='POST' onsubmit='return confirmarEliminacion(this);'> <!-- Línea modificada -->
    <input type='hidden' value='".$equipamiento['nombre']."' name='nombre'>
    <input type='hidden' value='eliminarEquipamiento' name='accion'>
    <input type='submit' value='Eliminar'>
    </form>
    </td>
    </tr>";    
    if($numero == "uno"){
        $numero = "dos";
    }else{
        $numero = "uno";
    }
}
echo "</table>";
?>

<?php
if (isset($_POST['accion']) && $_POST['accion'] == 'mostrarFormularioModificar') {
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    echo "<form class='formulario' action='' method='POST'>
    <h2>Modificar Equipamiento</h2>
    <input type='text' name='nombre' value='$nombre' readonly>
    <input type='number' name='cantidad' value='$cantidad' required>
    <input type='hidden' value='modificarEquipamiento' name='accion'>
    <input type='submit' class='botonFormulario' value='Guardar'>
    </form>";
} else {
    ?>
    <form action="" class="formulario" method="POST">
        <h2>Agregar Equipamiento</h2>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <input type="hidden" value="agregarEquipamiento" name="accion">
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
