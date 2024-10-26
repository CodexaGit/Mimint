$(document).ready(function() {
    $.ajax({
        url: '../controlador/verificar_sesion.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            console.log(data);
            if (data.error) {
                alert(data.error);
                window.location.href = 'login.php';
            } else {
                $('#nombreUsuario').text(data.nombreUsuario.toUpperCase());
                $('#nombreUsuarioTexto').text(data.nombreUsuario);
                $('input[name="docente"]').val(data.documento);
                $('.nombre').append(data.nombreUsuario);
                $('.nombreMenu').append(data.nombreUsuario + '<img src="img/usuario.png" alt="" class="usuarioLogo">');
                if(data.rol == 'Admin'){
                    $('.nav-links').append(`
                        <li><a href="#">MENU DE OPCIONES</a></li>
                        <hr>
                        <li><a href="inicio.php">INICIO</a></li>
                        <hr>
                        <li><a href="reservas.php">REALIZAR RESERVAS</a></li>
                        <hr>
                        <li><a href="calendario.php">CALENDARIO</a></li>
                        <hr>
                        <li><a href="peticionesUsuarios.php">PETICIONES</a></li>
                        <hr>
                        <li><a href="usuariosAgregados.php">USUARIOS AGREGADOS</a></li>
                        <hr>
                        <li><a href="areaDeReportes.php">AREA DE REPORTES</a></li>
                        <hr>
                        <li><a href="listado.php">LISTADO</a></li>
                        <hr>
                        <li><a href="../controlador/cerrarSesion.php" >CERRAR SESION</a></li>
                        <hr></hr>
                    `);
                }else if(data.rol == 'Docente'){
                    $('.nav-links').append(`
                        <li><a href="#">MENU DE OPCIONES</a></li>
                        <hr>
                        <li><a href="inicio.php">INICIO</a></li>
                        <hr>
                        <li><a href="reservas.php">REALIZAR RESERVAS</a></li>
                        <hr>
                        <li><a href="calendario.php">CALENDARIO</a></li>
                        <hr>
                        <li><a href="../controlador/cerrarSesion.php" >CERRAR SESION</a></li>
                        <hr></hr>
                    `);
                }else if(data.rol == 'Estudiante'){
                    $('.nav-links').append(`
                        <li><a href="#">MENU DE OPCIONES</a></li>
                        <hr>
                        <li><a href="inicio.php">INICIO</a></li>
                        <hr>
                        <li><a href="calendario.php">CALENDARIO</a></li>
                        <hr>
                        <li><a href="../controlador/cerrarSesion.php" >CERRAR SESION</a></li>
                        <hr></hr>
                    `);
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    });
});

