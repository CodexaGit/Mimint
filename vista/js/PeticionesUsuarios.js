$(document).ready(function() {
    function cargarPeticiones(filtro = 'desc', busqueda = '') {
        $.ajax({
            url: '../controlador/PeticionesUsuariosController.php',
            type: 'POST',
            data: { accion: 'obtenerPeticiones', filtro: filtro, busqueda: busqueda },
            dataType: 'json',
            success: function(data) {
                $('#results').empty();
                if (data.length > 0) {
                    data.forEach(function(usuario) {
                        const usuarioHtml = `
                            <div class="tablaP">
                                <div class="tituloTablaP">
                                    <h1>${usuario.nombre}</h1>
                                </div>
                                <div class="datosP">
                                    <div class="filaP">
                                        <p class="label P">ROL:</p>
                                        <select name="rol" class="valor P">
                                            <option class="valor P" value="docente">DOCENTE</option>
                                            <option class="valor P" value="estudiante">ESTUDIANTE</option>
                                        </select>
                                        <input type="hidden" name="documento" value="${usuario.documento}">
                                    </div>
                                    <div class="filaP">
                                        <p class="label P">E-MAIL:</p>
                                        <p class="valor P">${usuario.email}</p>
                                    </div>
                                    <div class="filaP d">
                                        <p class="label P">DOCUMENTO:</p>
                                        <p class="valor P">${usuario.documento}</p>
                                    </div>
                                    <div class="filaP o">
                                        <div class="op">
                                            <p>ACEPTAR</p>
                                            <img class="elegir" src="img/tick.png" alt="" data-accion="aceptarUsuario" data-documento="${usuario.documento}" data-rol="docente">
                                        </div>
                                        <div class="op">
                                            <p>DENEGAR</p>
                                            <img class="mal" src="img/mal.png" alt="" data-accion="denegarUsuario" data-documento="${usuario.documento}" data-rol="estudiante">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#results').append(usuarioHtml);
                    });
                } else {
                    $('#results').append('<p>No hay peticiones de usuarios.</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    }

    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        const busqueda = $('#search-input').val();
        cargarPeticiones('desc', busqueda);
    });

    $('#filtro-form').on('submit', function(e) {
        e.preventDefault();
        const filtro = $('#filtro').val() === 'Descendente' ? 'asc' : 'desc';
        $('#filtro').val(filtro === 'desc' ? 'Descendente' : 'Ascendente');
        cargarPeticiones(filtro);
    });

    $('#results').on('click', 'img', function(e) {
        e.preventDefault();
        const accion = $(this).data('accion');
        const documento = $(this).data('documento');
        const rol = $(this).data('rol');
        $.ajax({
            url: '../controlador/PeticionesUsuariosController.php',
            type: 'POST',
            data: { accion: accion, documento: documento, rol: rol },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    cargarPeticiones();
                } else {
                    console.error('Error:', response.message);
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