<?php
session_start(); // Iniciar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['documentoUsuario']) || !isset($_SESSION['contrasenaUsuario'])) {
    header('Location: login.php'); // Redirigir al login si no está autenticado
    exit;
}

require_once('../controlador/bd.php'); // Incluir bd.php para obtener la conexión
require_once('../controlador/SalaController.php');
require_once('../modelo/UsuarioModel.php'); // Incluir UsuarioModel.php para obtener la clase UsuarioModel

// Obtener la información del usuario autenticado
$documentoUsuario = $_SESSION['documentoUsuario'];
$contrasenaUsuario = $_SESSION['contrasenaUsuario'];
$usuarioModel = new UsuarioModel($conexion);
$usuario = $usuarioModel->obtenerUsuarioPorDocumentoYContrasena($documentoUsuario, $contrasenaUsuario)->fetch_assoc();

if (!$usuario) {
    die('Usuario no encontrado.');
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
    <nav>
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

        <div class="menu-btn">
            <div class="separador"></div>
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
            <li><a href="#"><?php echo strtoupper(htmlspecialchars($usuario['nombre'])); ?><img src="img/usuario.png" alt="" class="usuario"></a></li>
            <hr>
        </ul>
    </nav>
    
    <section class="todos">
        <div class="todaSalas">
            <h1>SALAS</h1>
            <hr class="pisoSala">
            <!-- Aquí se mostrarán las salas dinámicamente -->
            <?php
            // Utilizar la conexión establecida en bd.php
            $controller = new SalaController($conexion);
            $salas = $controller->listarSalas();
            if ($salas->num_rows > 0) {
                while ($sala = $salas->fetch_assoc()) {
                    echo "<p><a href='reservas.php?sala=" . urlencode($sala['nombre']) . "'>" . htmlspecialchars($sala['nombre']) . "</a></p>";
                }
            } else {
                echo "<p>No hay salas disponibles.</p>";
            }
            ?>
        </div>
        <div class="info">
            <?php
            if (isset($_GET['sala'])) {
                $sala_nombre = htmlspecialchars($_GET['sala']);
                // Obtener información de la sala seleccionada
                $sala_info = $controller->obtenerSala($sala_nombre);
                if ($sala_info) {
                    echo "<h1>" . htmlspecialchars($sala_info['nombre']) . "</h1>";
                    echo "<h4>Localidad: " . htmlspecialchars($sala_info['localidad']) . "</h4>";
                    echo "<p>Capacidad: " . htmlspecialchars($sala_info['capacidad']) . "</p>";
                    ?>
                    <form action="procesar_reserva.php" id="formularioReservas" method="POST">
                        <input type="hidden" name="sala" value="<?php echo $sala_nombre; ?>">
                        <input type="hidden" name="docente" value="<?php echo htmlspecialchars($usuario['documento']); ?>">
                        <div class="horarios sp">
                            <div class="textosP">
                                <div class="datoReunion">
                                    <p>Hora</p>
                                </div>
                                <div class="datoReunion">
                                    <p>Día</p>
                                </div>
                                <div class="datoReunion">
                                    <p>Mes</p>
                                </div>
                                <div class="datoReunion">
                                    <p>Duración</p>
                                </div>
                                <div class="datoReunion">
                                    <p>Capacidad</p>
                                </div>
                            </div>
                            <div class="numerosP">
                                <div class="datoReunion">
                                    <input type="time" name="hora" class="horas" required>
                                </div>
                                <div class="datoReunion">
                                    <input type="number" name="dia" placeholder="21" class="horas" min="1" max="31" required>
                                </div>
                                <div class="datoReunion">
                                    <select name="mes" class="horas" required>
                                        <option value="01">Ene</option>
                                        <option value="02">Feb</option>
                                        <option value="03">Mar</option>
                                        <option value="04">Abr</option>
                                        <option value="05">May</option>
                                        <option value="06">Jun</option>
                                        <option value="07">Jul</option>
                                        <option value="08">Ago</option>
                                        <option value="09">Set</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dic</option>
                                    </select>
                                </div>
                                <div class="datoReunion">
                                    <input type="number" name="duracion" class="horas" min="1" max="99" maxlength="2" placeholder="2" required>
                                    <span>hs</span>
                                </div>
                                <div class="datoReunion">
                                    <input type="number" name="cantidadpersonas" placeholder="25" class="horas" min="1" max="99" maxlength="2" required>
                                </div>
                            </div>
                        </div>
                        <div class="descrip sp">
                            <p>Descripcion</p>
                            <textarea name="descripcion" placeholder="Esta reunion se realiza con el motivo de....." class="horasP"></textarea>
                        </div>
                        <div class="equipo sp">
                            <p>Equipamiento</p>
                            <div class="componentes">
                                <div class="tablaEquipo">
                                    <div id="equipamiento-container">
                                        <div class="equipamiento-item">
                                            <input type="text" name="equipamiento[]" class="equipamiento-input" placeholder="Nombre del equipamiento">
                                            <input type="number" name="cantidad[]" class="cantidad-input" placeholder="Cantidad" min="1" max="999">
                                        </div>
                                    </div>
                                </div>
                                <div class="botones">
                                    <img src="img/Menos.png" alt="Quitar" id="remove-equipamiento">
                                    <img src="img/Mas.png" alt="Agregar" id="add-equipamiento">
                                </div>
                            </div>
                        </div>
                        <div class="formCampoRegistro">
                            <input type="submit" value="Reservar" class="inputCampo">
                        </div>
                    </form>
                    <?php
                } else {
                    echo "<p>Información de la sala no disponible.</p>";
                }
            } else {
                echo "<h1>Seleccione una sala para ver la información y reservar</h1>";
            }
            ?>
        </div>
    </section>
    
    <script src="js/menu.js"></script>
    <script src="js/validacionesIngresarReserva.js"></script>
    <script>
        // Función para obtener el valor de un parámetro de la URL
        function getParameterByName(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }

        // Mostrar popup si la reserva fue exitosa
        document.addEventListener('DOMContentLoaded', function() {
            const reservaStatus = getParameterByName('reserva');
            if (reservaStatus === 'exitosa') {
                alert('Reserva realizada con éxito.');
            }
        });
    </script>
</body>
</html>