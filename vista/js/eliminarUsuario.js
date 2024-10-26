$(document).ready(function() {
    console.log('Document ready'); // Verificar que el documento está listo

    function cargarUsuarios(filtro = 'asc', busqueda = '') {
        console.log('Cargar usuarios:', filtro, busqueda); // Verificar que se llama a cargarUsuarios
        $.ajax({
            url: '../controlador/eliminarUsuario.php',
            type: 'POST',
            data: { accion: 'listarUsuarios', estado: 'Aprobado', filtro: filtro, busqueda: busqueda },
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response); // Verificar la respuesta completa
                $('#usuarios-container').empty();
                if (response.status === 'success' && Array.isArray(response.data)) {
                    response.data.forEach(function(usuario) {
                        const usuarioHtml = `
                            <div class="tabla">
                                <div class="tituloTabla">
                                    <h1>${usuario.nombre} ${usuario.apellido}</h1>
                                </div>
                                <div class="datos">
                                    <div class="fila">
                                        <p class="label">ROL:</p>
                                        <p class="valor">${usuario.rol}</p>
                                    </div>
                                    <div class="fila">
                                        <p class="label">E-MAIL:</p>
                                        <p class="valor">${usuario.email}</p>
                                    </div>
                                    <div class="fila">
                                        <p class="label">DOCUMENTO:</p>
                                        <p class="valor">${usuario.documento}</p>
                                    </div>
                                    <div class="fila">
                                        <div class="sino">
                                            <p style="display:inline-block;">ELIMINAR USUARIO</p>
                                            <button id="botonEliminarUsuario" class="rechazar-boton" data-documento="${usuario.documento}"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        `;
                        $('#usuarios-container').append(usuarioHtml);
                    });
                } else {
                    console.log('No data available:', response.data); // Verificar la data cuando no hay usuarios
                    $('#usuarios-container').append('<p>No hay usuarios disponibles.</p>');
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
        console.log('Search form submit:', busqueda); // Verificar el valor de búsqueda
        cargarUsuarios('asc', busqueda);
    });

    $('#filtro').on('click', function() {
        const filtro = $(this).text() === 'Ascendente' ? 'desc' : 'asc';
        console.log('Filtro click:', filtro); // Verificar el valor del filtro
        $(this).text(filtro === 'asc' ? 'Ascendente' : 'Descendente');
        cargarUsuarios(filtro);
    });

    $('#usuarios-container').on('click', '.rechazar-boton', function() {
        const documento = $(this).data('documento');
        console.log('Rechazar botón click:', documento); // Verificar el documento del usuario a rechazar
        $.ajax({
            url: '../controlador/eliminarUsuario.php',
            type: 'POST',
            data: { accion: 'rechazarUsuario', documento: documento },
            success: function(response) {
                console.log('Rechazar Response:', response); // Verificar la respuesta de rechazarUsuario
                try {
                    const result = typeof response === 'object' ? response : JSON.parse(response);
                    if (result.status === 'success') {
                        cargarUsuarios();
                    } else {
                        alert('Error al rechazar el usuario.');
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    alert('Error inesperado. Por favor, inténtelo de nuevo.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    });

    // Cargar usuarios al cargar la página
    cargarUsuarios();
});