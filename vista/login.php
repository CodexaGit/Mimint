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
</head>
<body>      
    <p class="titulo">INICIO DE SESION</p>
    <div class="containerLogin">
        <div class="logo-container">
            <img src="img/logo.png" id="logoLogin" class="logo" alt="Logo de la empresa">
        </div>
        <div class="form-container">
            <form action="../controlador/AuthController.php" id="form" method="POST">            
                <div class="formCampoLogin">
                    <label for="documento">DOCUMENTO</label>
                    <input type="number" id="documento" name="documento" class="inputCampo">    
                </div>
                <div class="formCampoLogin">
                    <label for="contrasena">CONTRASEÑA</label>
                    <input type="password" id="contrasena" name="contrasena" class="inputCampo">
                </div>
                <p class="ayuda">¿Olvidaste tu contraseña?</p>
                <div class="formCampoLogin">
                    <input type="submit" value="INICIAR SESION" class="inputCampo">
                </div>
            </form>
            <p class="ayuda"><a href="registro.php">¿Aun no tienes una cuenta?</a></p>
        </div>
    </div>
    <script src="js/validacion.js"></script>
</body>
</html>