$(document).ready(function() {
    // Función para cargar las salas
    function cargarSalas() {
        $.ajax({
            url: '../controlador/SalasReserva.php',
            type: 'POST',
            data: { accion: 'listarSalas' },
            dataType: 'json',
            success: function(response) {

                $('#salas-container').empty();
                if (response.status === 'error') {
                    $('#salas-container').append('<p>Error: ' + response.message + '</p>');
                } else if (response.length > 0) {
                    console.log("bien");
                    response.forEach(function(sala) {
                        $('#salas-container').append('<p><a href="#" class="sala-link" data-nombre="' + sala.nombre + '">' + sala.nombre + '</a></p>');
                    });
                } else {
                    $('#salas-container').append('<p>No hay salas disponibles.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#salas-container').append('<p>Error al cargar las salas.</p>');
            }
        });
    }

    // Manejar el clic en una sala
    $(document).on('click', '.sala-link', function(e) {
        e.preventDefault();
        const salaNombre = $(this).data('nombre');
        cargarDatosSala(salaNombre);
    });

    // Función para cargar los datos de una sala
    function cargarDatosSala(nombre) {
        $.ajax({
            url: '../controlador/SalasReserva.php',
            type: 'POST',
            data: { accion: 'obtenerSala', nombre: nombre },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'error') {
                    $('#sala-info').html('<p>Error: ' + response.message + '</p>');
                } else {
                    const data = response.data;
                    let localidad = data.localidad;
                    let capacidad = data.capacidad;
                    $('#sala-nombre').text(data.nombre);
                    $('#sala-info').html(`
                        <h4>Localidad: ${localidad}</h4>
                        <p>Capacidad: ${capacidad}</p>
                        <form action="procesar_reserva.php" id="formularioReservas" method="POST">
                            <input type="hidden" name="sala" value="${data.nombre}">
                            <input type="hidden" name="docente" value="${data.documento}">
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
                                        <input type="time" name="hora" class="horas" id="hora1" required>
                                    </div>
                                    <div class="datoReunion">
                                        <input type="number" name="dia" placeholder="21" class="horas" min="1" max="31" id="dia1" required>
                                    </div>
                                    <div class="datoReunion">
                                        <select name="mes" class="horas" id="mes1" required>
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
                                        <input type="number" name="duracion" id="duracion1" class="horas" min="1" max="99" maxlength="2" placeholder="2" required>
                                        <span>hs</span>
                                    </div>
                                    <div class="datoReunion">
                                        <input type="number" name="cantidadpersonas" id="cantidadpersonas1" placeholder="25" class="horas" min="1" max="99" maxlength="2" required>
                                    </div>
                                </div>
                            </div>
                            <div class="formulario-oculto">
                                <div class="datoReunion">
                                    <label for="hora">Hora</label>
                                    <input type="time" name="hora" class="horas" id="hora2" required>
                                </div>
                                <div class="datoReunion">
                                    <label for="dia">Día</label>
                                    <input type="number" name="dia" placeholder="21" id="dia2" class="horas" min="1" max="31" required>
                                </div>
                                <div class="datoReunion">
                                    <label for="mes">Mes</label>
                                    <select name="mes" class="horas" id="mes2" required>
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
                                    <label for="duracion">Duración</label>
                                    <input type="number" name="duracion" id="duracion2" class="horas" min="1" max="99" maxlength="2" placeholder="2" required>
                                    <span>hs</span>
                                </div>
                                <div class="datoReunion">
                                    <label for="cantidadpersonas">Capacidad</label>
                                    <input type="number" name="cantidadpersonas" id="cantidadpersonas2" placeholder="25" class="horas" min="1" max="99" maxlength="2" required>
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
                                        <div class='contenedorBotones'>
                                        <p class='textoBotones'>RESTAR EQUIPAMIENTO</p>
                                        <img src="img/Menos.png" alt="Quitar" id="remove-equipamiento">
                                        </div>
                                        <div class='contenedorBotones'>
                                        <p class='textoBotones'>AGREGAR EQUIPAMIENTO</p>
                                        <img src="img/Mas.png" alt="Agregar" id="add-equipamiento">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="formCampoRegistro">
                                <input type="submit" value="Reservar" style="
                                    color: #04364A;
                                    background-color: rgba(255, 255, 255, 0.5);
                                    cursor: pointer;
                                    margin-bottom: 30px;
                                    margin-top: 30px;
                                    padding:3px 20px;
                                " >
                            </div>
                        </form>
                    `);
                    let isSyncing = false;

                    function syncInputs(sourceId, targetId) {
                        document.getElementById(sourceId).addEventListener('input', function() {
                            if (!isSyncing) {
                                isSyncing = true;
                                document.getElementById(targetId).value = this.value;
                                isSyncing = false;
                            }
                        });
                    }

                    syncInputs('hora1', 'hora2');
                    syncInputs('dia1', 'dia2');
                    syncInputs('mes1', 'mes2');
                    syncInputs('duracion1', 'duracion2');
                    syncInputs('cantidadpersonas1', 'cantidadpersonas2');
                    syncInputs('hora2', 'hora1');
                    syncInputs('dia2', 'dia1');
                    syncInputs('mes2', 'mes1');
                    syncInputs('duracion2', 'duracion1');
                    syncInputs('cantidadpersonas2', 'cantidadpersonas1');

                    // Sincronizar datos antes de ocultar o mostrar formularios
                    function syncAllInputs() {
                        document.getElementById('hora2').value = document.getElementById('hora1').value;
                        document.getElementById('dia2').value = document.getElementById('dia1').value;
                        document.getElementById('mes2').value = document.getElementById('mes1').value;
                        document.getElementById('duracion2').value = document.getElementById('duracion1').value;
                        document.getElementById('cantidadpersonas2').value = document.getElementById('cantidadpersonas1').value;
                    }


                    // Inicializar autocomplete para los campos de equipamiento
                    inicializarAutocomplete();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#sala-info').html('<p>Error al cargar la información de la sala.</p>');
            }
        });
    }

    // Manejar el envío del formulario de reserva
    $(document).on('submit', '#formularioReservas', function(e) {
        e.preventDefault();

        // Mostrar todos los campos ocultos antes de la validación
        $('.formulario-oculto').css('display', 'block');

        const formData = $(this).serialize();

        // Ocultar los campos nuevamente después de la serialización
        $('.formulario-oculto').css('display', '');

        $.ajax({
            url: 'procesar_reserva.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                alert('Reserva realizada con éxito.');
                $('#sala-info').empty();
                $('#sala-nombre').text('Seleccione una sala para ver la información y reservar');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error al realizar la reserva.');
            }
        });
    });

    // Añadir una nueva característica de equipamiento
    $(document).on('click', '#add-equipamiento', function() {
        $('#equipamiento-container').append(`
            <div class="equipamiento-item">
                <input type="text" name="equipamiento[]" class="equipamiento-input" placeholder="Nombre del equipamiento">
                <input type="number" name="cantidad[]" class="cantidad-input" placeholder="Cantidad" min="1" max="999">
            </div>
        `);

        // Inicializar autocomplete para el nuevo campo de equipamiento
        inicializarAutocomplete();
    });

    // Eliminar la última característica de equipamiento
    $(document).on('click', '#remove-equipamiento', function() {
        $('#equipamiento-container .equipamiento-item:last').remove();
    });

    // Función para inicializar autocomplete en los campos de equipamiento
    function inicializarAutocomplete() {
        $('.equipamiento-input').autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: 'buscar-equipamiento.php',
                    type: 'GET',
                    data: { query: request.term },
                    dataType: 'json',
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nombre + ' (' + item.cantidad + ' disponibles)',
                                value: item.nombre
                            };
                        }));
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error al buscar equipamiento:', textStatus, errorThrown);
                    }
                });
            },
            minLength: 1
        });
    }

    // Función para ajustar los atributos required según el tamaño de la pantalla
    
    function ajustarRequired() {
        if ($(window).width() < 1000) {
            $('#formularioReservas input:not([type="submit"]), #formularioReservas select').removeAttr('required');
            $('.formulario-oculto input, .formulario-oculto select').attr('required', 'required');
        } else {
            $('#formularioReservas input:not([type="submit"]), #formularioReservas select').attr('required', 'required');
            $('.formulario-oculto input, .formulario-oculto select').removeAttr('required');
        }
    }


    // Ajustar los atributos required al cargar la página y al cambiar el tamaño de la ventana
    ajustarRequired();
    $(window).resize(ajustarRequired);

    // Cargar salas al cargar la página
    cargarSalas();
});