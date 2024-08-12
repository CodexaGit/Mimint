document.getElementById('form').addEventListener('submit', function(event) {
    event.preventDefault();

    var documento = document.getElementById('documento').value;
    var contrasena = document.getElementById('contrasena').value;
    /*var nombre = document.getElementById('nombre').value;
    var contrasena = document.getElementById('contrasena').value;
    var contrasena = document.getElementById('contrasena').value;*/

    var formData = new FormData();
    formData.append('documento', documento);
    formData.append('contrasena', contrasena);

    fetch('../control/login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('usuario', JSON.stringify(data.usuario));
            window.location.href = '../vista/index.html';
        } else if (data.error === 1) {
            window.location.href = '../vista/login.html?error=1';
        } else if (data.error === 2) {
            window.location.href = '../vista/login.html?error=2';
        } else {
            alert('Lo sentimos, no se pudo realizar la consulta.');

        }
        console.log(data.error);
    })
    .catch(error => console.error('Error:', error));
});