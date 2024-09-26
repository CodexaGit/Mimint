$(document).ready(function() {
    $('#form').on('submit', function(event) {
        event.preventDefault();

        var documento = $('#documento').val();
        var contrasena = $('#contrasena').val();

        $.ajax({
            url: '../controlador/AuthController.php',
            type: 'POST',
            data: {
                documento: documento,
                contrasena: contrasena
            },
            success: function(response) {
                console.log('Response:', response);
                if (!response) {
                    alert('La respuesta está vacía.');
                    return;
                }
                try {
                    // No necesitamos JSON.parse aquí
                    const data = response;
                    console.log('Data:', data);
                    if (data.autenticado) {
                        localStorage.setItem('usuario', JSON.stringify(data.usuario));
                        window.location.href = '../vista/inicio.php';
                    } else if (data.error === 1) {
                        window.location.href = '../vista/login.php?error=1';
                    } else if (data.error === 2) {
                        window.location.href = '../vista/login.php?error=2';
                    } else {
                        alert('Lo sentimos, no se pudo realizar la consulta.');
                    }
                } catch (e) {
                    console.error('Error processing response:', e);
                    alert('Ocurrió un error al procesar la respuesta del servidor.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                alert('Ocurrió un error: ' + textStatus);
            }
        });
    });
});