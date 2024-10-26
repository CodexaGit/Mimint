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
            <h1>Listado de Equipamiento</h1>
            <div class="redirigir">
                <a href="listadoCaracteristicas.php">Características</a>
                <a href="#" class="elegido">Equipamiento</a>
                <a href="listadoReserva.php">Reserva</a>
                <a href="listadoSala.php">Salas</a>
                <a href="listadoUsuario.php">Usuarios</a>
            </div>
        </div>
        <div class="search-container">
            <img src="img/lupa.png" alt="Buscar" class="lupaP" id="search-button">
            <form class="buscador" id="search-form" method="GET" action="listadoEquipamiento.php">
                <input type="text" placeholder="Buscar..." name="busqueda" id="search-input">
                <input type="submit" value="" hidden>
            </form>
        </div>
        <div class="contenedorCRUD">
            <table width='500'>
                <tr>
                    <th class='izq'>Nombre</th>
                    <th>Cantidad</th>
                    <th>Modificar</th>
                    <th>Eliminar</th>
                </tr>
                <tbody id="equipamiento-list">
                    <!-- Aquí se insertarán las filas de equipamiento -->
                </tbody>
            </table>

            <form id="form-agregar" class="formulario" method="POST">
                <h2>Agregar Equipamiento</h2>
                <input type="text" name="nombre" placeholder="Nombre" required>
                <input type="number" name="cantidad" placeholder="Cantidad" required>
                <input type="hidden" name="accion" value="agregarEquipamiento">
                <input type="submit" class="botonFormulario" value="Agregar">
            </form>

            <form id="form-modificar" class="formulario" method="POST" style="display:none;">
                <h2>Modificar Equipamiento</h2>
                <input type="text" name="nombre" readonly>
                <input type="number" name="cantidad" required>
                <input type="hidden" name="accion" value="modificarEquipamiento">
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
            function cargarEquipamiento(busqueda = '') {
                $.ajax({
                    url: '../controlador/EquipamientoController.php',
                    type: 'POST',
                    data: { accion: 'buscarEquipamientoTodos', busqueda: busqueda },
                    dataType: 'json',
                    success: function(data) {
                        var equipamientoList = $('#equipamiento-list');
                        equipamientoList.empty();
                        let contador = "uno";
                        data.equipamiento.forEach(function(equipamiento) {
                            equipamientoList.append(`
                                <tr class="${contador}">
                                    <td>${equipamiento.nombre}</td>
                                    <td class='centrado'>${equipamiento.cantidad}</td>
                                    <td>
                                        <button class="btn-modificar" data-nombre="${equipamiento.nombre}" data-cantidad="${equipamiento.cantidad}">Modificar</button>
                                    </td>
                                    <td>
                                        <button class="btn-eliminar" data-nombre="${equipamiento.nombre}">Eliminar</button>
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

            cargarEquipamiento();

            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                var busqueda = $('#search-input').val();
                cargarEquipamiento(busqueda);
            });

            $('#form-agregar').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../controlador/EquipamientoController.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        mostrarAlerta(response.status, response.message);
                        cargarEquipamiento();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            });

            $('#form-modificar').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: '../controlador/EquipamientoController.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        mostrarAlerta(response.status, response.message);
                        cargarEquipamiento();
                        $('#form-modificar').hide();
                        $('#form-agregar').show();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            });

            $(document).on('click', '.btn-modificar', function() {
                var nombre = $(this).data('nombre');
                var cantidad = $(this).data('cantidad');
                $('#form-modificar input[name="nombre"]').val(nombre);
                $('#form-modificar input[name="cantidad"]').val(cantidad);
                $('#form-modificar').show();
                $('#form-agregar').hide();
            });

            $(document).on('click', '.btn-eliminar', function() {
                var nombre = $(this).data('nombre');
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
                            url: '../controlador/EquipamientoController.php',
                            type: 'POST',
                            data: { nombre: nombre, accion: 'eliminarEquipamiento' },
                            dataType: 'json',
                            success: function(response) {
                                mostrarAlerta(response.status, response.message);
                                cargarEquipamiento();
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