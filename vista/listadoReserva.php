

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
<h1>Listado de Reservas</h1>
<div class="redirigir">
    <a href="listadoCaracteristicas.php">Características</a>
    <a href="listadoEquipamiento.php">Equipamiento</a>
    <a href="#" class="elegido">Reserva</a>
    <a href="listadoSala.php">Salas</a>
    <a href="listadoUsuario.php">Usuarios</a>
</div>

</div>
<div class="contenedorCRUD">
<?php
// Incluir controladores y conexión
require_once('../controlador/bd.php');
require_once('../controlador/ReservaController.php');
require_once('../controlador/UsuarioController.php');
require_once('../controlador/SalaController.php');

$reservaController = new ReservaController($conexion);
$usuarioController = new UsuarioController($conexion);
$salaController = new SalaController($conexion);

$message = '';
$status = '';

// Manejo de solicitudes AJAX
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    switch ($accion) {
        case 'modificarReserva':
            // Validar y sanitizar los datos aquí
            $response = $reservaController->modificarReserva(
                $_POST['id'], 
                $_POST['dia'], 
                $_POST['horainicio'], 
                $_POST['horafin'], 
                $_POST['cantidadpersonas'], 
                $_POST['descripcion'], 
                $_POST['docente'], 
                $_POST['sala']
            );
            break;
        case 'eliminarReserva':
            $response = $reservaController->eliminarReserva($_POST['id']);
            $status = $response['status'];
            $message = $response['message'];
            break;
        case 'agregarReserva':
            $response = $reservaController->crearReserva(
                $_POST['dia'], 
                $_POST['horainicio'], 
                $_POST['horafin'], 
                $_POST['cantidadpersonas'], 
                $_POST['descripcion'], 
                $_POST['docente'], 
                $_POST['sala'],
                [] // Asumiendo que no hay equipamiento en este caso
            );
            break;
        default:
            $response = ['status' => 'error', 'message' => 'Acción no válida'];
            break;
    }
}

if ($message) {
    mostrarAlerta($status, $message);
}

// Manejo de solicitudes normales (No AJAX)
$reservas = $reservaController->listarReservas();
$docentes = $usuarioController->listarDocentesyAdmins();
$salas = $salaController->listarSalasArray();

echo "<table width='100%'>
    <tr>
        <th>Día</th>
        <th>Hora Inicio</th>
        <th>Hora Fin</th>
        <th>Personas</th>
        <th class='descripcion'>Descripción</th>
        <th>Docente</th>
        <th>Sala</th>
        <th class='ocultar'>Modificar</th>
        <th class='ocultar'>Eliminar</th>
    </tr>";
    $numero = "uno";
    foreach ($reservas["data"] as $reserva) {
        echo "<tr id='reserva-".$reserva['id']."' class='$numero'>
            
            <td>".$reserva['dia']."</td>
            <td class='centrado'>".$reserva['horainicio']."</td>
            <td class='centrado'>".$reserva['horafin']."</td>
            <td class='centrado'>".$reserva['cantidadpersonas']."</td>
            <td class='descripcion'>".$reserva['descripcion']."</td>
            <td class='centrado'>";
            $encontrado = false;
            foreach ($docentes['data'] as $doc) {
                if ($doc['documento'] == $reserva['docente']) {
                    echo $doc['nombre'];
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                echo "No encontrado";
            }
            echo "</td>
            <td class='centrado'>".$reserva['sala']."</td>
            <td>
                <form method='POST'>
                    <input type='hidden' value='".$reserva['id']."' name='id'>
                    <input type='hidden' value='".$reserva['dia']."' name='dia'>
                    <input type='hidden' value='".$reserva['horainicio']."' name='horainicio'>
                    <input type='hidden' value='".$reserva['horafin']."' name='horafin'>
                    <input type='hidden' value='".$reserva['cantidadpersonas']."' name='cantidadpersonas'>
                    <input type='hidden' value='".$reserva['descripcion']."' name='descripcion'>
                    <input type='hidden' value='".$reserva['docente']."' name='docente'>
                    <input type='hidden' value='".$reserva['sala']."' name='sala'>
                    <input type='hidden' value='mostrarFormularioModificar' name='accion'>
                    <input type='submit' value='Modificar'>
                </form>
            </td>
            <td>
                <form method='POST' onsubmit='return confirmarEliminacion(this);'>
                    <input type='hidden' value='".$reserva['id']."' name='id'>
                    <input type='hidden' value='eliminarReserva' name='accion'>
                    <input type='submit' value='Eliminar'>
                </form>
            </td>
        </tr>";    
        $numero = ($numero == "uno") ? "dos" : "uno";
    }
    echo "</table>"
    ?>


<?php
if (isset($_POST['accion']) && $_POST['accion'] == 'mostrarFormularioModificar') {
    $id = $_POST['id'];
    $dia = $_POST['dia'];
    $horainicio = $_POST['horainicio'];
    $horafin = $_POST['horafin'];
    $cantidadpersonas = $_POST['cantidadpersonas'];
    $descripcion = $_POST['descripcion'];
    $docente = $_POST['docente'];
    $sala = $_POST['sala'];

    echo "<form id='formModificarReserva' class='formulario' action='' method='POST'>
    <h2>Modificar reserva</h2>
    <label for='id'>ID</label>
    <input type='number' id='id' name='id' value='$id' readonly>
    <label for='dia'>Día</label>
    <input type='date' id='dia' name='dia' value='$dia' required>
    <div class='horas'>
        <div class='hora'>
            <label for='horainicio'>Hora de inicio</label>
            <input type='time' id='horainicio' name='horainicio' value='$horainicio' required>
        </div>
        <div class='hora'>
            <label for='horafin'>Hora de fin</label>
            <input type='time' id='horafin' name='horafin' value='$horafin' required>
        </div>
    </div>
    <label for='cantidadpersonas'>Cantidad de personas</label>
    <input type='number' id='cantidadpersonas' name='cantidadpersonas' value='$cantidadpersonas' required>
    <label for='descripcion'>Descripción</label>
    <textarea id='descripcion' name='descripcion' rows='10' cols='50' required>$descripcion</textarea>
    <label for='docente'>Docente</label>
    <select id='docente' name='docente' required>
        <option value='' hidden>Seleccione un docente</option>";
        foreach ($docentes['data'] as $doc) {
            $selected = ($doc['documento'] == $docente) ? 'selected' : '';
            echo "<option value='".$doc['documento']."' $selected>".$doc['nombre']."</option>";
        }
    echo "</select>
    <label for='sala'>Sala</label>
    <select id='sala' name='sala' required>
        <option value='' hidden>Seleccione una sala</option>";
        foreach ($salas['data'] as $salaItem) {
            $selected = ($salaItem['nombre'] == $sala) ? 'selected' : '';
            echo "<option value='".$salaItem['nombre']."' $selected>".$salaItem['nombre']."</option>";
        }
    echo "</select>
    <input type='hidden' value='modificarReserva' name='accion'>
    <input type='submit' class='botonFormulario value='Guardar'>
    </form>";
} else {
    ?>
    <form id="formAgregarReserva" class="formulario" method="POST">
    <h2>Agregar Reserva</h2>
    <label for="dia">Día</label>
    <input type="date" id="dia" name="dia" placeholder="Día" required>
    <div class="horas">
        <div class="hora">
            <label for="horainicio">Hora de inicio</label>
            <input type="time" id="horainicio" name="horainicio" placeholder="Hora de inicio" required>
        </div>
        <div class="hora">
        <label for="horafin">Hora de fin</label>
        <input type="time" id="horafin" name="horafin" placeholder="Hora de fin" required>
        </div>
    </div>
    <label for="cantidadpersonas">Cantidad de personas</label>
    <input type="number" id="cantidadpersonas" name="cantidadpersonas" placeholder="Cantidad de personas" required>
    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" placeholder="Descripción" required></textarea>
    <label for="docente">Docente</label>
    <select id="docente" name="docente" required>
        <option value="" hidden>Seleccione un docente</option>
        <?php
        foreach ($docentes['data'] as $doc) {
            echo "<option value='".$doc['documento']."'>".$doc['nombre']."</option>";
        }
        ?>
    </select>
    <label for="sala">Sala</label>
    <select id="sala" name="sala" required>
        <option value="" hidden>Seleccione una sala</option>
        <?php
        foreach ($salas['data'] as $salaItem) {
            echo "<option value='".$salaItem['nombre']."'>".$salaItem['nombre']."</option>";
        }
        ?>
    </select>
    <input type="hidden" value="agregarReserva" name="accion">
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
