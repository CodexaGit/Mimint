<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/tablas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <title>Página Mimint</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php include('alerta.php'); ?>
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
    <section>
        <div class="encabezado">
            <h1>Listado de Características</h1>
            <div class="redirigir">
                <a href="#" class="elegido">Características</a>
                <a href="listadoEquipamiento.php">Equipamiento</a>
                <a href="listadoReserva.php">Reserva</a>
                <a href="listadoSala.php">Salas</a>
                <a href="listadoUsuario.php">Usuarios</a>
            </div>
        </div>
        <div class="search-container">
            <img src="img/lupa.png" alt="Buscar" class="lupaP" id="search-button">
            <form class="buscador" id="search-form" method="GET" action="listadoCaracteristicas.php">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden>
            </form>
        </div>
        <div class="contenedorCRUD">
            <?php
            require_once('../controlador/bd.php');
            require_once('../controlador/CaracteristicasController.php');
            require_once('../controlador/SalaController.php');

            $CaracteristicasController = new CaracteristicasController($conexion);
            $SalaController = new SalaController($conexion);

            $message = '';
            $status = '';

            if (isset($_POST['accion'])) {
                $accion = $_POST['accion'];
                if ($accion == 'modificarCaracteristicas') {
                    $resultado = $CaracteristicasController->modificarCaracteristicas($_POST['sala'], $_POST['caracteristicaNueva'], $_POST['caracteristicaVieja']);
                    $status = $resultado['status'];
                    $message = $resultado['message'];
                } elseif ($accion == 'eliminarCaracteristicas') {
                    $resultado = $CaracteristicasController->eliminarCaracteristicas($_POST['sala'], $_POST['caracteristica']);
                    $status = $resultado['status'];
                    $message = $resultado['message'];
                } elseif ($accion == 'agregarCaracteristicas') {
                    $resultado = $CaracteristicasController->agregarCaracteristicas($_POST['sala'], $_POST['caracteristica']);
                    $status = $resultado['status'];
                    $message = $resultado['message'];
                }
            }
            // Obtener el término de búsqueda
            $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
            // Listar características con filtro de búsqueda
            $Caracteristicas = $CaracteristicasController->buscarCaracteristicas($busqueda);
            $salas = $SalaController->listarSalas(); // Obtener la lista de salas

            if ($message) {
                mostrarAlerta($status, $message); // Llamar a la función para mostrar la alerta
            }

            echo "<table width='500'><tr><th class='izq'>Sala</th><th>Característica</th><th class='ocultar'>Modificar</th><th class='ocultar'>Eliminar</th></tr>";
            $numero = "uno";
            foreach ($Caracteristicas as $Caracteristica) {
                echo "<tr class='$numero'><td>".$Caracteristica['sala']."</td><td class='centrado'>".$Caracteristica['caracteristica']."</td>
                <td>
                <form method='POST'>
                <input type='hidden' value='".$Caracteristica['sala']."' name='sala'>
                <input type='hidden' value='".$Caracteristica['caracteristica']."' name='caracteristica'>
                <input type='hidden' value='mostrarFormularioModificar' name='accion'>
                <input type='submit' value='Modificar'>
                </form>
                </td>
                <td>
                <form method='POST' onsubmit='return confirmarEliminacion(this);'>
                <input type='hidden' value='".$Caracteristica['sala']."' name='sala'>
                <input type='hidden' value='".$Caracteristica['caracteristica']."' name='caracteristica'>
                <input type='hidden' value='eliminarCaracteristicas' name='accion'>
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
                $sala = $_POST['sala'];
                $caracteristica = $_POST['caracteristica'];
                echo "<form class='formulario' action='' method='POST'>
                <h2>Modificar Características</h2>
                <input type='text' name='sala' value='$sala' readonly>
                <input type='hidden' name='caracteristicaVieja' value='$caracteristica'>
                <input type='text' name='caracteristicaNueva' value='$caracteristica' required>
                <input type='hidden' value='modificarCaracteristicas' name='accion'>
                <input type='submit' class='botonFormulario' value='Guardar'>
                </form>";
            } else {
                ?>
                <form action="" class="formulario" method="POST">
                    <h2>Agregar Características</h2>
                    <select name="sala" required>
                        <option value="" disabled selected>Seleccione una sala</option>
                        <?php
                        foreach ($salas as $sala) {
                            echo "<option value='".$sala['nombre']."'>".$sala['nombre']."</option>";
                        }
                        ?>
                    </select>
                    <input type="text" name="caracteristica" placeholder="Característica" required>
                    <input type="hidden" value="agregarCaracteristicas" name="accion">
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