$(document).ready(function() {
    function cargarPeticiones(busqueda = '') {
        $.ajax({
            url: '../controlador/PeticionesReservaController.php',
            type: 'POST',
            data: { accion: 'obtenerPeticiones', busqueda: busqueda },
            dataType: 'json',
            success: function(data) {
                $('#results').empty();
                if (data.length > 0) {
                    data.forEach(function(peticion) {
                        const duracionHoras = Math.floor((convertirHorasAMinutos(peticion.horafin) - convertirHorasAMinutos(peticion.horainicio)) / 60);
                        const peticionHtml = `
                            <div class="tablaP2">
                                <div class="tituloTablaP2">
                                    <h1>${peticion.nombre_completo}</h1>
                                    <p class="P">ID: ${peticion.id}</p>
                                </div>
                                <div class="datos2">
                                    <div class="datoNum">
                                        <p>INICIO DE RESERVA:</p>
                                        <p class="num">${peticion.horainicio}</p>
                                        <p class="txt">CANT. DE PERSONAS:</p>
                                        <p class="num">${peticion.cantidadpersonas}</p>
                                        <p class="txt">DURACION:</p>
                                        <p class="num">${duracionHoras} horas</p>
                                    </div>
                                    <div class="datoTxt">
                                        <p>DESCRIPCION:</p>
                                        <p class="p2 txtp2">${peticion.descripcion.replace(/\n/g, '<br>')}</p>
                                        <p>SALA:</p>
                                        <p class="txt2 p2">${peticion.sala}</p>
                                        <div class="sino">
                                        ACEPTAR
                                            <button class="elegir" data-id="${peticion.id}" data-accion="aceptar"></button>
                                        DENEGAR
                                            <button class="mal" data-id="${peticion.id}" data-accion="denegar"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#results').append(peticionHtml);
                    });
                } else {
                    $('#results').append('<p>No hay peticiones de reservas.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    }

    function convertirHorasAMinutos(hora) {
        const [horas, minutos] = hora.split(':');
        return parseInt(horas) * 60 + parseInt(minutos);
    }

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        const busqueda = $('#search-input').val();
        cargarPeticiones(busqueda);
    });

    $('#results').on('click', 'button', function() {
        const id = $(this).data('id');
        const accion = $(this).data('accion');
        $.ajax({
            url: '../controlador/PeticionesReservaController.php',
            type: 'POST',
            data: { accion: accion === 'aceptar' ? 'aceptarReserva' : 'denegarReserva', id: id },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    cargarPeticiones();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    });

    // Cargar peticiones al cargar la página
    cargarPeticiones();
});