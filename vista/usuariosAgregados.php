<?php
require_once('../controlador/UsuarioController.php');
require_once('../controlador/bd.php');

// Crear una instancia del controlador de usuario
$usuarioController = new UsuarioController($conexion);

$filtro = isset($_POST['filtro']) && $_POST['filtro'] === 'asc' ? 'asc' : 'desc';
$busqueda = isset($_POST['buscar']) ? $_POST['busqueda'] : null;

// Obtener la lista de usuarios con estado aprobado, filtro y búsqueda
$usuarios = $usuarioController->listarUsuarios('Aprobado', $filtro, $busqueda);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $documento = $_POST['documento'];
    $query = "DELETE FROM usuario WHERE documento = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('s', $documento);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <title>Pagina Mimit</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <li><a href="usuariosAgregados.php">USUARIOS AGREGADOS</a></li>
            <hr>
            <li><a href="areaDeReportes.php">AREA DE REPORTES</a></li>
            <hr>
            <li><a href="#">NOMBRE DE USUARIO<img src="img/usuario.png" alt="" class="usuario"></a></li>
            <hr>
        </ul>
    </nav>
 
    <section>
        <p class="tituloUsu">USUARIOS AGREGADOS</p>
        <div class="search-container">
            <img src="img/lupa.png" alt="" class="lupaP" id="search-button">
            <form class="buscadorReservas" method="post">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden name="buscar">
            </form>
            <div class="disponibilidades">
                <select id="disponibilidad">
                    <option value="" disabled selected>Disponibilidad ▼</option>
                    <option value="12:00">12:00</option>
                    <option value="13:00">13:00</option>
                    <option value="14:00">14:00</option>
                    <option value="15:00">15:00</option>
                    <option value="16:00">16:00</option>
                </select>
            </div>
        </div>
        <form method="post">
            <input type="hidden" name="filtro" value="<?php echo $filtro === 'asc' ? 'desc' : 'asc'; ?>">
            <input type="submit" id="filtro" class="filtro" value="<?php echo $filtro === 'asc' ? 'Ascendente' : 'Descendente'; ?>">
        </form>
        <?php if ($usuarios && $usuarios->num_rows > 0): ?>
            <?php while ($usuario = $usuarios->fetch_assoc()): ?>
                <div class="tabla">
                    <div class="tituloTabla">
                        <h1><?php echo htmlspecialchars($usuario['nombre']); ?></h1>
                    </div>
                    <div class="datos">
                        <div class="fila">
                            <p class="label">ROL:</p>
                            <p class="valor"><?php echo htmlspecialchars($usuario['rol']); ?></p>
                        </div>
                        <div class="fila">
                            <p class="label">E-MAIL:</p>
                            <p class="valor"><?php echo htmlspecialchars($usuario['email']); ?></p>
                        </div>
                        <div class="fila">
                            <p class="label">DOCUMENTO:</p>
                            <p class="valor"><?php echo htmlspecialchars($usuario['documento']); ?></p>
                        </div>
                        <div class="fila">
                            <div class="sino">
                                <form action="usuariosAgregados.php" id="formularioEliminarUsuario" method="POST">
                                    <input type="hidden" name="documento" value="<?php echo htmlspecialchars($usuario['documento']); ?>">
                                    <input type="hidden" name="eliminar" value="1">
                                    <p style="display:inline-block;">ELIMINAR USUARIO</p>
                                    <button type="submit" id="botonEliminarUsuario" class="eliminar-boton"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay usuarios disponibles.</p>
        <?php endif; ?>
    </section>

    <script src="js/verificar_sesion.js"></script>
    <script src="js/menu.js"></script>
</body>
</html>

<?php
$conexion->close();
?>