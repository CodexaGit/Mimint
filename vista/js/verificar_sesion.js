$(document).ready(function() {
    $.ajax({
        url: '../controlador/verificar_sesion.php',
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                alert(data.error);
                window.location.href = 'login.php';
            } else {
                $('#nombreUsuario').text(data.nombreUsuario.toUpperCase());
                $('#nombreUsuarioTexto').text(data.nombreUsuario);
                $('input[name="docente"]').val(data.documento);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
        }
    });
});