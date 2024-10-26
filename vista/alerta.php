<?php
function mostrarAlerta($status, $message) {
    // Escapar caracteres especiales en el mensaje
    $message = addslashes($message);
    $title = $status == 'success' ? 'Éxito' : 'Error';
    $icon = $status == 'success' ? 'success' : 'error';

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$title',
                text: '$message',
                icon: '$icon',
                confirmButtonText: 'OK'
            });
        });
    </script>";
}
?>

<script>
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    });

    function confirmarEliminacion(form) {
        swalWithBootstrapButtons.fire({
            title: '¿Deseas eliminar este registro?',
            text: 'No podrás revertir esto!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'No, cancelar!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: 'Cancelado',
                    text: 'Tu registro está a salvo :)',
                    icon: 'error'
                });
            }
        });

        return false; // Evita que el formulario se envíe automáticamente
    }
</script>
