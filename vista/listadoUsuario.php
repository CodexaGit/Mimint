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
            <h1>Listado de Usuarios</h1>
            <div class="redirigir">
                <a href="listadoCaracteristicas.php">Características</a>
                <a href="listadoEquipamiento.php">Equipamiento</a>
                <a href="listadoReserva.php">Reserva</a>
                <a href="listadoSala.php">Salas</a>
                <a href="#" class="elegido">Usuarios</a>
            </div>
        </div>
        <div class="search-container">
            <img src="img/lupa.png" alt="Buscar" class="lupaP" id="search-button">
            <form class="buscador" id="search-form" method="GET" action="listadoUsuario.php">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden>
            </form>
        </div>
        <div class="contenedorCRUD">
        <?php
        require_once('../controlador/bd.php');
        require_once('../controlador/UsuarioController.php');

        $usuarioController = new UsuarioController($conexion);
        $message = '';
        $status = '';

        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'];
            if ($accion == 'modificarUsuario') {
                $usuarioController->modificarUsuario($_POST['documento'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['rol'], $_POST['estado']);
            } elseif ($accion == 'eliminarUsuario') {
                $resultado = $usuarioController->eliminarUsuario($_POST['documento']);
                if ($resultado) {
                    $status = isset($resultado['status']) ? $resultado['status'] : 'error';
                    $message = isset($resultado['message']) ? $resultado['message'] : 'Error desconocido al eliminar el usuario.';
                } else {
                    $status = 'error';
                    $message = 'Error desconocido al eliminar el usuario.';
                }
            } elseif ($accion == 'agregarUsuario') {
                $usuarioController->agregarUsuario($_POST['documento'], $_POST['nombre'], $_POST['apellido'], $_POST['email'], $_POST['rol'], $_POST['estado']);
            }
        }

        $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
        $resultado = $usuarioController->buscarUsuarios($busqueda);

        if ($resultado && is_array($resultado) && isset($resultado['status']) && $resultado['status'] == 'success') {
            $usuarios = isset($resultado['data']) ? $resultado['data'] : [];
        } else {
            $usuarios = [];
            $status = 'error';
            $message = $resultado ? (isset($resultado['message']) ? $resultado['message'] : 'Error al obtener los usuarios.') : 'Error al obtener los usuarios.';
        }

        if ($message) {
            mostrarAlerta($status, $message);
        }

        echo "<table width='500'><tr><th>Documento</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Rol</th><th>Estado</th><th class='ocultar'>Modificar</th><th class='ocultar'>Eliminar</th></tr>";
        $numero = "uno";
        foreach ($usuarios as $usuario) {
            $documento = isset($usuario['documento']) ? $usuario['documento'] : '';
            $nombre = isset($usuario['nombre']) ? $usuario['nombre'] : '';
            $apellido = isset($usuario['apellido']) ? $usuario['apellido'] : '';
            $email = isset($usuario['email']) ? $usuario['email'] : '';
            $rol = isset($usuario['rol']) ? $usuario['rol'] : '';
            $estado = isset($usuario['estado']) ? $usuario['estado'] : '';

            echo "<tr class='$numero'><td>$documento</td><td>$nombre</td><td>$apellido</td><td>$email</td><td>$rol</td><td>$estado</td>
            <td>
            <form method='POST'>
            <input type='hidden' value='$documento' name='documento'>
            <input type='hidden' value='$nombre' name='nombre'>
            <input type='hidden' value='$apellido' name='apellido'>
            <input type='hidden' value='$email' name='email'>
            <input type='hidden' value='$rol' name='rol'>
            <input type='hidden' value='$estado' name='estado'>
            <input type='hidden' value='mostrarFormularioModificar' name='accion'>
            <input type='submit' value='Modificar'>
            </form>
            </td>
            <td>
            <form method='POST' onsubmit='return confirmarEliminacion(this);'>
            <input type='hidden' value='$documento' name='documento'>
            <input type='hidden' value='eliminarUsuario' name='accion'>
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
                $documento = $_POST['documento'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $email = $_POST['email'];
                $rol = $_POST['rol'];
                $estado = $_POST['estado'];
                echo "<form class='formulario' action='' method='POST'>
                <h2>Modificar Usuario</h2>
                <input type='text' name='documento' value='$documento' readonly>
                <input type='text' name='nombre' value='$nombre' required>
                <input type='text' name='apellido' value='$apellido' required>
                <input type='email' name='email' value='$email' required>
                <select name='rol' required>
                    <option value='Estudiante' " . ($rol == 'Estudiante' ? 'selected' : '') . ">Estudiante</option>
                    <option value='Docente' " . ($rol == 'Docente' ? 'selected' : '') . ">Docente</option>
                    <option value='Admin' " . ($rol == 'Admin' ? 'selected' : '') . ">Admin</option>
                </select>
                <select name='estado' required>
                    <option value='Aprobado' " . ($estado == 'Aprobado' ? 'selected' : '') . ">Aprobado</option>
                    <option value='Pendiente' " . ($estado == 'Pendiente' ? 'selected' : '') . ">Pendiente</option>
                    <option value='Rechazado' " . ($estado == 'Rechazado' ? 'selected' : '') . ">Rechazado</option>
                </select>
                <input type='hidden' value='modificarUsuario' name='accion'>
                <input type='submit' class='botonFormulario' value='Guardar'>
                </form>";
            } else {
                ?>
                <form action="" class="formulario" method="POST">
                    <h2>Agregar Usuario</h2>
                    <input type="text" name="documento" placeholder="Documento" required>
                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="text" name="apellido" placeholder="Apellido" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <select name="rol" required>
                        <option value="" hidden>Selecciona rol</option>
                        <option value="Estudiante">Estudiante</option>
                        <option value="Docente">Docente</option>
                        <option value="Admin">Admin</option>
                    </select>
                    <select name="estado" required>
                        <option value="" hidden>Selecciona estado</option>
                        <option value="Aprobado">Aprobado</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                    <input type="hidden" value="agregarUsuario" name="accion">
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