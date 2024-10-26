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
                        const descripcion = peticion.descripcion ? peticion.descripcion.replace(/\n/g, '<br>') : 'No hay descripción disponible';
                        
                        // Construir la plantilla HTML sin las características
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
                                    <p class="p2 txtp2">${descripcion}</p>
                                    <p>EQUIPAMIENTO:</p>
                                    <ul id="caracteristicasContainer-${peticion.id}" class="caracteristicasContainer">
                                    </ul>
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

                        // Insertar la plantilla HTML en el contenedor deseado
                        $('#results').append(peticionHtml);

                        // Realizar la llamada AJAX para obtener las características
                        $.ajax({
                            url: '../controlador/EquipamientoController.php',
                            type: 'POST',
                            data: { accion: 'obtenerEquipamiento', id: peticion.id },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 'success') {
                                    let html = '';
                                    response.equipamiento.forEach(function(equipamiento) {
                                        html += `<li class="txtp2">${equipamiento.equipamiento} en <strong>${equipamiento.cantidad}</strong> unidades</li>`;
                                    });
                                    $(`#caracteristicasContainer-${peticion.id}`).html(html);
                                } else {
                                    $(`#caracteristicasContainer-${peticion.id}`).html('<p class="txtp2">No hay equipamiento disponible</p>');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error:', textStatus, errorThrown);
                            }
                        });
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
        let cedulaUsuario = 0; // Declarar la variable fuera del bloque
    
        $.ajax({
            url: '../controlador/verificar_sesion.php',
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                    window.location.href = 'login.php';
                } else {
                    cedulaUsuario = data.documento; 
    
                    Swal.fire({
                        title: 'Enviar Mensaje',
                        input: 'textarea',
                        inputPlaceholder: 'Ingrese un mensaje (opcional)',
                        showCancelButton: true,
                        confirmButtonText: 'Enviar',
                        preConfirm: (mensaje) => {
                            return { mensaje: mensaje };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const mensaje = result.value.mensaje;
                            $.ajax({
                                url: '../controlador/verificarController.php',
                                type: 'POST',
                                data: {
                                    accion: accion === 'aceptar' ? 'Aprobado' : 'Rechazado',
                                    id: id,
                                    mensaje: mensaje,
                                    cedula: cedulaUsuario
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        cargarPeticiones();
                                        $.ajax({
                                            url: '../controlador/ReservaController.php',
                                            type: 'POST',
                                            data: {
                                                id: id,
                                                accion: accion,
                                                mensaje: mensaje,
                                                accion: "obtenerReserva"
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                if (response.status === "success") {
                                                    const cedulaEnviar = response.cedulaEnviar; 
                                                    $.ajax({
                                                        url: '../controlador/peticionesCorreo.php',
                                                        type: 'POST',
                                                        data: {
                                                            id: id,
                                                            salaReserva: response.salaReserva,
                                                            dia: response.dia,
                                                            accion: accion,
                                                            mensaje: mensaje,
                                                            cedula: cedulaEnviar
                                                        },
                                                        success: function(emailResponse) {
                                                            console.log('Email sent:', emailResponse);
                                                        },
                                                        error: function(jqXHR, textStatus, errorThrown) {
                                                            console.error('Error sending email:', textStatus, errorThrown);
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    console.error('Error:', textStatus, errorThrown);
                                }
                            });
                        }
                    });
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