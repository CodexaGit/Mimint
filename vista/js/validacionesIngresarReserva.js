document.addEventListener('DOMContentLoaded', function() {
    // Validación adicional con JavaScript para limitar la entrada a 2 dígitos
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.length > 2) {
                input.value = input.value.slice(0, 2);
            }
        });
    });

    // Agregar nuevos campos de equipamiento
    const addEquipamientoBtn = document.getElementById('add-equipamiento');
    if (addEquipamientoBtn) {
        addEquipamientoBtn.addEventListener('click', function() {
            const container = document.getElementById('equipamiento-container');
            const newEquipamiento = document.createElement('div');
            newEquipamiento.classList.add('equipamiento-item');
            newEquipamiento.innerHTML = `
                <input type="text" name="equipamiento[]" class="equipamiento-input" placeholder="Nombre del equipamiento">
                <input type="number" name="cantidad[]" class="cantidad-input" placeholder="Cantidad" min="1">
            `;
            container.appendChild(newEquipamiento);
        });
    } else {
        console.error('Elemento #add-equipamiento no encontrado');
    }

    // Eliminar el último campo de equipamiento
    const removeEquipamientoBtn = document.getElementById('remove-equipamiento');
    if (removeEquipamientoBtn) {
        removeEquipamientoBtn.addEventListener('click', function() {
            const container = document.getElementById('equipamiento-container');
            if (container.children.length > 1) {
                container.removeChild(container.lastChild);
            }
        });
    } else {
        console.error('Elemento #remove-equipamiento no encontrado');
    }

    // Autocompletar para el campo de equipamiento
    $(document).on('input', '.equipamiento-input', function() {
        const input = $(this);
        $.ajax({
            url: '../vista/buscar-equipamiento.php', // Ruta corregida
            method: 'GET',
            data: { query: input.val() },
            dataType: 'text', // Forzar el tipo de datos de la respuesta a texto
            success: function(data) {
                console.log('Respuesta del servidor:', data); // Agrega este log
                try {
                    // Asegúrate de que data sea una cadena antes de intentar parsearla
                    if (typeof data === 'string') {
                        data = data.trim(); // Elimina espacios en blanco al inicio y al final
                    }
                    const suggestions = JSON.parse(data);
                    console.log('Sugerencias parseadas:', suggestions); // Agrega este log
                    input.autocomplete({
                        source: suggestions
                    });
                } catch (e) {
                    console.error('Error al parsear el JSON:', e);
                    console.error('Respuesta del servidor:', data);
                }
            },
            error: function() {
                console.error('Error al cargar los datos de autocompletar');
            }
        });
    });
});