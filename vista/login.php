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
                    <input type="password" id="contrasena" name="contrasena" class="inputCampo">
                </div>
                <p class="ayuda"><a href="#" id="forgotPassword">¿Olvidaste tú contraseña?</a></p>
                <div class="formCampoLogin">
                    <input type="submit" value="INICIAR SESION" class="inputCampo">
                </div>
            </form>
            <p class="ayuda"><a href="registro.html">¿Aún no tienes una cuenta?</a></p>
        </div>
    </div>
    <script src="js/validacion.js"></script>
    <script src="js/recuperarContrasena.js"></script>
</body>
</html>