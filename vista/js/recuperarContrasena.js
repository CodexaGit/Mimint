let cedulaGlobal = '';

document.getElementById('forgotPassword').addEventListener('click', function(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Recuperar Contraseña',
        html: `
            <input type="number" id="cedula" class="swal2-input" placeholder="Cédula">
            <input type="email" id="email" class="swal2-input" placeholder="Correo Electrónico">
        `,
        confirmButtonText: 'Enviar',
        focusConfirm: false,
        preConfirm: () => {
            const cedula = Swal.getPopup().querySelector('#cedula').value;
            const email = Swal.getPopup().querySelector('#email').value;
            if (!cedula || !email) {
                Swal.showValidationMessage(`Por favor ingresa ambos campos`);
            }
            return { cedula: cedula, email: email };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            cedulaGlobal = result.value.cedula; // Guardar la cédula ingresada
            // Enviar los datos al servidor para procesar la recuperación de contraseña
            $.ajax({
                url: '../controlador/recuperarContrasena.php',
                type: 'POST',
                data: {
                    cedula: result.value.cedula,
                    email: result.value.email
                },
                success: function(response) {
                    try {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            const codigoEnviado = res.codigo; // Guardar el código enviado
                            Swal.fire({
                                title: 'Código de Verificación',
                                html: '<input type="text" id="codigo" class="swal2-input" placeholder="Código de Verificación">',
                                confirmButtonText: 'Verificar',
                                focusConfirm: false,
                                preConfirm: () => {
                                    const codigoIngresado = Swal.getPopup().querySelector('#codigo').value;
                                    if (!codigoIngresado) {
                                        Swal.showValidationMessage(`Por favor ingresa el código de verificación`);
                                    }
                                    return { codigoIngresado: codigoIngresado, codigoEnviado: codigoEnviado };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Validar el código de verificación
                                    if (result.value.codigoIngresado == result.value.codigoEnviado) {
                                        Swal.fire({
                                            title: 'Nueva Contraseña',
                                            html: '<input type="password" id="nuevaContrasena" class="swal2-input" placeholder="Nueva Contraseña">',
                                            confirmButtonText: 'Restablecer',
                                            focusConfirm: false,
                                            preConfirm: () => {
                                                const nuevaContrasena = Swal.getPopup().querySelector('#nuevaContrasena').value;
                                                if (!nuevaContrasena) {
                                                    Swal.showValidationMessage(`Por favor ingresa la nueva contraseña`);
                                                }
                                                return { nuevaContrasena: nuevaContrasena };
                                            }
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Enviar la nueva contraseña al servidor para actualizarla
                                                $.ajax({
                                                    url: '../controlador/actualizarContrasena.php',
                                                    type: 'POST',
                                                    data: {
                                                        cedula: cedulaGlobal, // Usar la cédula guardada
                                                        nuevaContrasena: result.value.nuevaContrasena
                                                    },
                                                    success: function(response) {
                                                        try {
                                                            const res = JSON.parse(response);
                                                            if (res.status === 'success') {
                                                                Swal.fire('Éxito', 'Contraseña restablecida correctamente', 'success');
                                                            } else {
                                                                Swal.fire('Error', res.message, 'error');
                                                            }
                                                        } catch (e) {
                                                            Swal.fire('Error', 'Respuesta del servidor no válida', 'error');
                                                        }
                                                    },
                                                    error: function() {
                                                        Swal.fire('Error', 'Hubo un problema al restablecer la contraseña', 'error');
                                                    }
                                                });
                                            }
                                        });
                                    } else {
                                        Swal.fire('Error', 'Código de verificación incorrecto', 'error');
                                    }
                                }
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Respuesta del servidor no válida', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Hubo un problema al enviar el correo', 'error');
                }
            });
        }
    });
});