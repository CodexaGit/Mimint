document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar el interruptor
    const toggle = document.getElementById('toggle');
    const toggle1 = document.getElementById('toggle1');

    // Función para cambiar de página cuando el interruptor se activa
    function cambiarPagina() {
        if (toggle.checked) {
            // Cambiar la página si el interruptor está activado
            window.location.href = 'peticionesReunion.html';  // Reemplaza con la URL de la página a la que deseas redirigir
        } 
        if (toggle1.checked) {
            // Cambiar la página si el interruptor está activado
            window.location.href = 'peticionesUsuarios.html';  // Reemplaza con la URL de la página a la que deseas redirigir
        } else {
        }
    }

    // Agregar un evento al interruptor
    toggle.addEventListener('change', cambiarPagina);
    toggle1.addEventListener('change', cambiarPagina);

    });