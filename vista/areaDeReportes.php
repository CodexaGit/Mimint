<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Gothic&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Página Mimint</title>
</head>
<body>
<nav>
        <div class="menu-btn">
            <p class="separadorUsuario nombreMenu"></p>
            <img src="img/menu.png" class="menu-icon">
        </div>
        <ul class="nav-links">
            
            <hr>
        </ul>
    </nav>
    <div class="tituloAgregar"> <h1>AREA DE REPORTES</h1> </div>

    <div class="descripcionAgregar">
        <p id="Seccion">Seccion</p>
    </div>
    <div class="inputsAgregar">
        <form id="reporteForm" class="centrar">
            <select name="seccionReporte" id="seccion" required>
                <option value="" hidden>Reporte</option>
                <option value="todoRegistros">Todos los registros</option>
                <option value="equipamientoMasUsado">Equipamiento más usados</option>
                <option value="salasMasUsadas">Salas más usadas</option>
            </select>
            
            <select name="formatoReporte" id="formato" required>
                <option value="" hidden>Formato</option>
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
            </select>
            
            <div class="botonAgregar">
                <input type="button" name="boton" value="DESCARGAR" onclick="generarReporte()">
            </div>
        </form>   
    </div>
    <script src="js/verificar_sesion.js"></script>
    <script src="js/menu.js"></script>
    <script>
        function generarReporte() {
            const seccion = document.getElementById('seccion').value;
            const formato = document.getElementById('formato').value;
            const url = `../controlador/ReporteController.php?seccionReporte=${seccion}&formatoReporte=${formato}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>