<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/alertas.css">
</head>
<body>      
    <p class="titulo">INICIO DE SESIÓN</p>
    <div class="containerLogin">
        <div class="logo-container">
            <img src="img/logo.png" id="logoLogin" class="logo" alt="Logo de la empresa">
        </div>
        <div class="form-container">
            <form id="form" method="POST">            
                <div class="formCampoLogin">
                    <label for="documento">DOCUMENTO</label>
                    <input type="number" id="documento" name="documento" class="inputCampo">    
                </div>
                <div class="formCampoLogin">
                    <label for="contrasena">CONTRASEÑA</label>
                    <div class="ojo-contra" >
                    <div class="password-container">
                        <input type="password" id="contrasena" name="contrasena" class="inputCampo" style="width: 135%">
                    </div>
                    <button type="button" id="togglePassword"   class="toggle-password">
                            <img src="img/ojo.png" style="width: 40px; margin-left: 280%;" alt="Mostrar/Ocultar contraseña">
                    </button>
                    </div>
                </div>
                <p class="ayuda"><a href="#" id="forgotPassword">¿Olvidaste tú contraseña?</a></p>
                <div class="formCampoLogin">
                    <input type="submit" value="INICIAR SESIÓN" class="inputCampo">
                </div>
            </form>
            <p class="ayuda"><a href="registro.html">¿Aún no tienes una cuenta?</a></p>
        </div>
    </div>
    <script src="js/validacion.js"></script>
    <script src="js/recuperarContrasena.js"></script>
    <script>
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');

            if (error) {
                let errorMessage = '';
                switch (error) {
                    case '1':
                        errorMessage = 'Usuario o contraseña incorrectos.';
                        break;
                    case '2':
                        errorMessage = 'Tu cuenta no fue aprobada aún.';
                        break;
                    case '3':
                        errorMessage = 'Error desconocido. Por favor, intenta nuevamente.';
                        break;
                    default:
                        errorMessage = 'Error desconocido. Por favor, intenta nuevamente.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage,
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            $('#togglePassword').on('click', function() {
                const passwordField = $('#contrasena');
                const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
                passwordField.attr('type', type);
                const icon = type === 'password' ? 'img/ojo.png' : 'img/ojoCerrado.png';
                $(this).find('img').attr('src', icon);
            });
        });
    </script>
</body>
</html>