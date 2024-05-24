const botones = document.querySelectorAll("button");

function eliminarBotones() {
    botones.forEach(function(boton) {
        boton.classList.add("ocultar");
    });
}

function loginPrecionado(){
    setTimeout(eliminarBotones, 500);
    setTimeout(function(){
        window.location.href = "login.html";
    }, 1000);
}

function registrarPrecionado(){
    setTimeout(eliminarBotones, 500);
    setTimeout(function(){
        window.location.href = "registro.html";
    }, 1000);
}