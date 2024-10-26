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
            <h1>Listado de Reservas</h1>
            <div class="redirigir">
                <a href="listadoCaracteristicas.php">Características</a>
                <a href="listadoEquipamiento.php">Equipamiento</a>
                <a href="#" class="elegido">Reserva</a>
                <a href="listadoSala.php">Salas</a>
                <a href="listadoUsuario.php">Usuarios</a>
            </div>
        </div>
        <div class="search-container">
            <img src="img/lupa.png" alt="Buscar" class="lupaP" id="search-button">
            <form class="buscador" id="search-form" method="GET" action="listadoReserva.php">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden>
            </form>
        </div>
        <div class="contenedorCRUD">
            <table width='100%'>
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
                </tr>
                <tbody id="reserva-list">
                    <!-- Aquí se insertarán las filas de reservas -->
                </tbody>
            </table>

            <form id="form-agregar" class="formulario" method="POST">
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

            <form id="form-modificar" class="formulario" method="POST" style="display:none;">
                <h2>Modificar Reserva</h2>
                <label for="id">ID</label>
                <input type="number" id="id" name="id" readonly>
                <label for="dia">Día</label>
                <input type="date" id="dia" name="dia" required>
                <div class="horas">
                    <div class="hora">
                        <label for="horainicio">Hora de inicio</label>
                        <input type="time" id="horainicio" name="horainicio" required>
                    </div>
                    <div class="hora">
                        <label for="horafin">Hora de fin</label>
                        <input type="time" id="horafin" name="horafin" required>
                    </div>
                </div>
                <label for="cantidadpersonas">Cantidad de personas</label>
                <input type="number" id="cantidadpersonas" name="cantidadpersonas" required>
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="10" cols="50" required></textarea>
                <label for="docente">Docente</label>
                <select id="docente" name="docente" required>
                    <option value='' hidden>Seleccione un docente</option>
                    <?php
                    foreach ($docentes['data'] as $doc) {
                        echo "<option value='".$doc['documento']."'>".$doc['nombre']."</option>";
                    }
                    ?>
                </select>
                <label for="sala">Sala</label>
                <select id="sala" name="sala" required>
                    <option value='' hidden>Seleccione una sala</option>
                    <?php
                    foreach ($salas['data'] as $salaItem) {
                        echo "<option value='".$salaItem['nombre']."'>".$salaItem['nombre']."</option>";
                    }
                    ?>
                </select>
                <input type="hidden" value="modificarReserva" name="accion">
                <input type="submit" class="botonFormulario" value="Guardar">
            </form>
        </div>
    </section>
    <script src="js/verificar_sesion.js"></script>
    <script src="js/menu.js"></script>
    <script>
        function mostrarAlerta(status, message) {
            if (status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: message,
                    showConfirmButton: false,
                    timer: 1500
                });
            } else if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }

        $(document).ready(function() {
            function cargarReservas(busqueda = '') {
                $.ajax({
                    url: '../controlador/ReservaController.php',
                    type: 'POST',
                    data: { accion: 'buscarReservas', busqueda: busqueda },
                    dataType: 'json',
                    success: function(data) {
                        var reservaList = $('#reserva-list');
                        reservaList.empty();
                        let contador = "uno";
                        data.reservas.forEach(function(reserva) {
                            reservaList.append(`
                                <tr class="${contador}">
                                    <td>${reserva.dia}</td>
                                    <td class='centrado'>${reserva.horainicio}</td>
                                    <td class='centrado'>${reserva.horafin}</td>
                                    <td class='centrado'>${reserva.cantidadpersonas}</td>
                                    <td class='descripcion'>${reserva.descripcion}</td>
                                    <td class='centrado'>${reserva.docente}</td>
                                    <td class='centrado'>${reserva.sala}</td>
                                    <td>
                                        <button class="btn-modificar" data-id="${reserva.id}" data-dia="${reserva.dia}" data-horainicio="${reserva.horainicio}" data-horafin="${reserva.horafin}" data-cantidadpersonas="${reserva.cantidadpersonas}" data-descripcion="${reserva.descripcion}" data-docente="${reserva.docente}" data-sala="${reserva.sala}">Modificar</button>
                                    </td>
                                    <td>
                                        <button class="btn-eliminar" data-id="${reserva.id}">Eliminar</button>
                                    </td>
                                </tr>
                            `);
                            contador = (contador === "uno") ? "dos" : "uno";
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            }

            cargarReservas();

            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                var busqueda = $('#search-input').val();
                cargarReservas(busqueda);
            });

            $('#form-agregar').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../controlador/ReservaController.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        mostrarAlerta(response.status, response.message);
                        cargarReservas();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            });

            $('#form-modificar').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../controlador/ReservaController.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        mostrarAlerta(response.status, response.message);
                        cargarReservas();
                        $('#form-modificar').hide();
                        $('#form-agregar').show();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            });

            $(document).on('click', '.btn-modificar', function() {
                var id = $(this).data('id');
                var dia = $(this).data('dia');
                var horainicio = $(this).data('horainicio');
                var horafin = $(this).data('horafin');
                var cantidadpersonas = $(this).data('cantidadpersonas');
                var descripcion = $(this).data('descripcion');
                var docente = $(this).data('docente');
                var sala = $(this).data('sala');
                $('#form-modificar input[name="id"]').val(id);
                $('#form-modificar input[name="dia"]').val(dia);
                $('#form-modificar input[name="horainicio"]').val(horainicio);
                $('#form-modificar input[name="horafin"]').val(horafin);
                $('#form-modificar input[name="cantidadpersonas"]').val(cantidadpersonas);
                $('#form-modificar textarea[name="descripcion"]').val(descripcion);
                $('#form-modificar select[name="docente"]').val(docente);
                $('#form-modificar select[name="sala"]').val(sala);
                $('#form-modificar').show();
                $('#form-agregar').hide();
            });

            $(document).on('click', '.btn-eliminar', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: '¿Deseas eliminar este registro?',
                    text: 'No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '../controlador/ReservaController.php',
                            type: 'POST',
                            data: { id: id, accion: 'eliminarReserva' },
                            dataType: 'json',
                            success: function(response) {
                                mostrarAlerta(response.status, response.message);
                                cargarReservas();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error:', textStatus, errorThrown);
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>