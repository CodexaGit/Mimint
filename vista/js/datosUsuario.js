document.addEventListener('DOMContentLoaded', function() {
    let usuarioData = localStorage.getItem('usuario');
    console.log(usuarioData);
    if (usuarioData) {
        let usuario = JSON.parse(usuarioData); // Convertir la cadena JSON en un objeto
        if(document.querySelector('.nombreUsuario') != null){
            let nombre = document.querySelector('.nombreUsuario');
            nombre.innerHTML = usuario.nombre;
        }
        if(document.querySelector('.documentoUsuario') != null){
            let documento = document.querySelector('.documentoUsuario');
            documento.innerHTML = usuario.documento;
        }
        if(document.querySelector('.emailUsuario') != null){
            let email = document.querySelector('.emailUsuario');
            email.innerHTML = usuario.email;
        }
        if(document.querySelector('.rolUsuario') != null){
            let rol = document.querySelector('.rolUsuario');
            rol.innerHTML = usuario.rol;
        }
        if(document.querySelector('.estadoUsuario') != null){
            let estado = document.querySelector('.estadoUsuario');
            estado.innerHTML = usuario.estado;
        }
        if(document.querySelector('.apellidoUsuario') != null){
            let apellido = document.querySelector('.apellidoUsuario');
            apellido.innerHTML = usuario.apellido;
        }
        if(document.querySelector('.contrasenaUsuario') != null){
            let contrasena = document.querySelector('.contrasenaUsuario');
            contrasena.innerHTML = usuario.contrasena;
        }
        
    }
});