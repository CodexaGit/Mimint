document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    let calendarEl = document.getElementById('calendar');
    let modoYoCheckbox = document.getElementById('modoYoCheckbox');

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        events: function(fetchInfo, successCallback, failureCallback) {
            let modoYo = modoYoCheckbox.checked; // Obtener el valor de modoYo
            $.ajax({
                url: '../controlador/calendarioController.php',
                type: 'POST',
                data: { action: 'mostrarReservas', modoYo: modoYo },
                success: function(response) {
                    console.log('Respuesta recibida:', response);
                    try {
                        let result = JSON.parse(response);
                        if (result.status === 'success') {
                            console.log('Reservas:', result.reservas);
                            successCallback(result.reservas);
                        } else {
                            failureCallback(result.message);
                        }
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        console.error('Respuesta completa:', response);
                        failureCallback('Error al parsear JSON');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener reservas:', error);
                    failureCallback('Error al obtener reservas');
                }
            });
        },
        eventContent: function(arg) {
            let eventDiv = document.createElement('div');
            eventDiv.classList.add('fc-event-custom');
            eventDiv.style.backgroundColor = arg.event.extendedProps.color || '#3788d8';
            let titleDiv = document.createElement('div');
            titleDiv.innerText = arg.event.title;
            eventDiv.appendChild(titleDiv);
            return { domNodes: [eventDiv] };
        },
        eventDidMount: function(info) {
            let dayEl = info.el.closest('.fc-daygrid-day');
            if (dayEl) {
                dayEl.classList.add('fc-day-with-event');
            }
        },
        dateClick: function(info) {
            document.querySelectorAll('.fc-daygrid-day').forEach(day => day.classList.remove('fc-day-selected'));
            info.dayEl.classList.add('fc-day-selected');
            enviarFecha(info.dateStr);
        },
        datesSet: function(info) {
            obtenerReservasYResaltarDias();
        }
    });
    calendar.render();

    let fechaActual = new Date().toISOString().split('T')[0];
    enviarFecha(fechaActual);

    modoYoCheckbox.addEventListener('change', function() {
        calendar.refetchEvents();
        let selectedDate = document.querySelector('.fc-day-selected');
        if (selectedDate) {
            enviarFecha(selectedDate.getAttribute('data-date'));
        } else {
            enviarFecha(fechaActual);
        }
    });
});

function enviarFecha(fecha) {
    let modoYo = document.getElementById('modoYoCheckbox').checked;
    $.ajax({
        url: '../controlador/calendarioController.php',
        type: 'POST',
        data: { fecha: fecha, modoYo: modoYo },
        success: function(response) {
            try {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    console.log('Reservas para la fecha:', result.reservas);
                    actualizarReservas(result.reservas);
                } else {
                    console.error('Error:', result.message);
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                console.error('Respuesta completa:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al enviar la fecha:', error);
        }
    });
}

function obtenerReservasYResaltarDias() {
    let modoYo = document.getElementById('modoYoCheckbox').checked;
    $.ajax({
        url: '../controlador/calendarioController.php',
        type: 'POST',
        data: { action: 'mostrarReservas', modoYo: modoYo },
        success: function(response) {
            try {
                let result = JSON.parse(response);
                if (result.status === 'success') {
                    console.log('Reservas para resaltar:', result.reservas);
                    resaltarDiasConReservas(result.reservas);
                } else {
                    console.error('Error:', result.message);
                }
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                console.error('Respuesta completa:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener reservas:', error);
        }
    });
}

function resaltarDiasConReservas(reservas) {
    reservas.forEach(function(reserva) {
        let dayEl = document.querySelector(`[data-date="${reserva.dia}"]`);
        if (dayEl) {
            dayEl.classList.add('fc-day-with-event');
        }
    });
}

function actualizarReservas(reservas) {
    let salasDiv = document.getElementById('salas');
    salasDiv.innerHTML = ''; // Limpiar el contenido actual

    let fechaActual = new Date().toISOString().split('T')[0];

    if (reservas.length > 0) {
        reservas.forEach(function(reserva) {
            let salaDiv = document.createElement('div');
            salaDiv.classList.add('sala');

            let img = document.createElement('img');
            img.src = 'img/MenosC.svg';
            img.classList.add('menosC');
            img.id = reserva.id;
            img.alt = '';

            if (reserva.dia < fechaActual) {
                img.style.pointerEvents = 'none';
                img.style.opacity = '0.5';
            } else {
                img.addEventListener('click', eliminarReserva);
            }

            let textoDiv = document.createElement('div');
            textoDiv.classList.add('texto');

            let salaP = document.createElement('p');
            salaP.textContent = reserva.sala;

            let horaP = document.createElement('p');
            horaP.textContent = reserva.horainicio;

            let horaInicio = new Date('1970-01-01T' + reserva.horainicio + 'Z');
            let horaFin = new Date('1970-01-01T' + reserva.horafin + 'Z');
            let duracionHoras = (horaFin - horaInicio) / (1000 * 60 * 60);

            let duracionP = document.createElement('p');
            duracionP.textContent = duracionHoras + ' horas';

            textoDiv.appendChild(salaP);
            textoDiv.appendChild(horaP);
            textoDiv.appendChild(duracionP);

            salaDiv.appendChild(img);
            salaDiv.appendChild(textoDiv);

            salasDiv.appendChild(salaDiv);
        });
    } else {
        salasDiv.innerHTML = '<p>No hay reservas disponibles.</p>';
    }
}

function eliminarReserva() {
    let id = this.id;
    
    Swal.fire({
        title: '¿Deseas eliminar esta reserva?',
        text: 'No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar!',
        cancelButtonText: 'No, cancelar!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../controlador/calendarioController.php',
                type: 'POST',
                data: { action: 'eliminarReserva', id: id },
                success: function(response) {
                    try {
                        let result = JSON.parse(response);
                        if (result.status === 'success') {
                            Swal.fire('Eliminado!', 'La reserva ha sido eliminada.', 'success');
                            enviarFecha(new Date().toISOString().split('T')[0]);
                        } else {
                            Swal.fire('Error', result.message, 'error');
                        }
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        console.error('Respuesta completa:', response);
                        Swal.fire('Error', 'Respuesta del servidor no válida', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar la reserva:', error);
                    Swal.fire('Error', 'Hubo un problema al eliminar la reserva', 'error');
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelado', 'Tu reserva está a salvo :)', 'error');
        }
    });
}