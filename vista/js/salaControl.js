$(document).ready(function() {
    // Función para cargar las salas
    function cargarSalas() {
        $.ajax({
            url: '../controlador/SalaController.php',
            type: 'POST',
            data: { accion: 'listarSalas' },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Imprimir la respuesta en la consola
                $('.salas').empty();
                if (response.status === 'error') {
                    console.error('Error:', response.message);
                    $('.salas').append('<p>Error al cargar las salas.</p>');
                } else if (response.status === 'success' && response.data.length > 0) {
                    console.log('Salas obtenidas:', response.data); // Imprimir las salas obtenidas en la consola
                    response.data.forEach(function(sala) {
                        const salaHtml = `
                            <div class='item' data-nombre='${sala.nombre}'>
                                <div class='fondo'>
                                    <p>${sala.nombre}</p>
                                    <img src='img/Menos.png' alt='Eliminar Sala' class='menos' data-nombre='${sala.nombre}'>
                                </div>
                            </div>
                        `;
                        $('.salas').append(salaHtml);
                    });
                } else {
                    console.log('No hay salas disponibles.'); // Mensaje de depuración
                    $('.salas').append('<p>No hay salas disponibles.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                console.error('Respuesta del servidor:', jqXHR.responseText); // Imprimir la respuesta en la consola
                $('.salas').append('<p>Error al cargar las salas.</p>');
            }
        });
    }

    // Función para cargar los datos de una sala en el formulario
    function cargarDatosSala(nombre) {
        $.ajax({
            url: '../controlador/SalaController.php',
            type: 'POST',
            data: { accion: 'obtenerSala', nombre: nombre },
            dataType: 'json',
            success: function(data) {
                console.log('Datos de la sala:', data); // Imprimir los datos de la sala en la consola
                if (data.status === 'error') {
                    alert('Error al obtener los datos de la sala: ' + data.message);
                } else {
                    $('#salaId').val(data.data.nombre);
                    $('#name').val(data.data.nombre).prop('disabled', true); // Deshabilitar el campo de nombre
                    $('#capacidad').val(data.data.capacidad);
                    $('#localidad').val(data.data.localidad);
                    // Cargar características
                    $('.tablaCARACT').empty();
                    if (data.data.caracteristicas && data.data.caracteristicas.length > 0) {
                        data.data.caracteristicas.forEach(function(caracteristica) {
                            $('.tablaCARACT').append('<input type="text" name="caracteristicas[]" value="' + caracteristica + '" ><hr>');
                        });
                    } else {
                        $('.tablaCARACT').append('<input type="text" name="caracteristicas[]" ><hr>');
                    }
                    $('#popup').show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                console.error('Respuesta del servidor:', jqXHR.responseText); // Imprimir la respuesta en la consola
                alert('Error al obtener los datos de la sala.');
            }
        });
    }

    // Cargar salas al cargar la página
    cargarSalas();

    // Abrir el popup para agregar una nueva sala
    $('#openForm').click(function() {
        $('#formAgregarSala')[0].reset();
        $('#name').prop('disabled', false); // Habilitar el campo de nombre
        $('.tablaCARACT').empty(); // Limpiar las características
        $('.tablaCARACT').append('<input type="text" name="caracteristicas[]" ><hr>'); // Añadir un campo de característica por defecto
        $('#popup').show();
    });

    // Cerrar el popup
    $('#closePopup').click(function() {
        $('#popup').hide();
    });

    // Manejar el envío del formulario
    $('#formAgregarSala').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const accion = $('#salaId').val() ? 'actualizarSala' : 'agregarSala';
        $.ajax({
            url: '../controlador/SalaController.php',
            type: 'POST',
            data: formData + '&accion=' + accion,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Imprimir la respuesta en la consola
                if (response.status === 'success') {
                    alert('Sala guardada exitosamente');
                    cargarSalas();
                    $('#popup').hide();
                } else {
                    alert('Error al guardar la sala: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                console.error('Respuesta del servidor:', jqXHR.responseText); // Imprimir la respuesta en la consola
                alert('Error al guardar la sala.');
            }
        });
    });

    // Manejar la eliminación de una sala
    $('.salas').on('click', '.menos', function(e) {
        e.stopPropagation(); // Evitar que el evento se propague al contenedor padre
        const salaNombre = $(this).data('nombre');
        $.ajax({
            url: '../controlador/SalaController.php',
            type: 'POST',
            data: { accion: 'eliminarSala', nombre: salaNombre },
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Imprimir la respuesta en la consola
                if (response.status === 'success') {
                    alert('Sala eliminada exitosamente');
                    cargarSalas();
                } else {
                    alert('Error al eliminar la sala: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                console.error('Respuesta del servidor:', jqXHR.responseText); // Imprimir la respuesta en la consola
                alert('Error al eliminar la sala.');
            }
        });
    });

    // Manejar la edición de una sala al hacer clic en el elemento
    $('.salas').on('click', '.item', function() {
        const salaNombre = $(this).data('nombre');
        cargarDatosSala(salaNombre);
    });

    // Añadir una nueva característica
    $('.caracTODO').on('click', '.mas', function() {
        $('.tablaCARACT').append('<input type="text" name="caracteristicas[]" ><hr>');
    });

    // Eliminar la última característica
    $('.caracTODO').on('click', '.menos', function() {
        $('.tablaCARACT input:last-of-type').remove();
        $('.tablaCARACT hr:last-of-type').remove();
    });
});