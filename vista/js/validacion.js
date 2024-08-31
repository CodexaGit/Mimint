document.getElementById('form').addEventListener('submit', function(event) {
    event.preventDefault();

    var documento = document.getElementById('documento').value;
    var contrasena = document.getElementById('contrasena').value;

    var formData = new FormData();
    formData.append('documento', documento);
    formData.append('contrasena', contrasena);

    fetch('../controlador/AuthController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response:', response);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Cambia a text() para depurar la respuesta
    })
    .then(text => {
        console.log('Response Text:', text);
        try {
            const data = JSON.parse(text); // Intenta parsear el texto como JSON
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
            console.error('Error parsing JSON:', e);
            alert('Ocurrió un error al procesar la respuesta del servidor.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error: ' + error.message);
    });
});