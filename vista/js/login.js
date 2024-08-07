//login.js

//funcion que va a servir para verificar el login :v
function verificar(){

    //creamos la variable cedula que va a guardar el valor de cedula
    let cedula = document.getElementById("cedula").value;

    //creamos la variable contrasena que va a guardar el valor de contrasena
    let contrasena = document.getElementById("contrasena").value;

    //comprueba si los campos estan vacios. en caso de que si este vacio, manda un mensaje de error
    if (cedula=="" || contrasena==""){
        error(1);
    } else {
        //si no estan vacios comprueba si el usuario existe, por ahora solo existe un usuario de prueba
        if (cedula == "12345678" && contrasena == "administrador"){
            //si esta todo bien se redirige a la pagina principal
            location.href = "paginaPrincipal.html";
        } else {
            //llamamos a la funcion error con el codigo correspondiente.
            error(2);
        }
    }

    //Hacemos return ya que queremos que cada vez que llamemos a la funcion error nos devuelva false ya que asi la pagina no se reinicia y muestre los errores sin problemas. Ademas queremos que la redireccion con location.href funcione correctamente.
    return false;
}

//funcion para administrar y mostrar los errores :v
function error(codigoError){
    /*  

    CODIGOS DE ERRORES:

    1 - CAMPOS VACIOS

    2 - USUARIO INEXISTENTE

    */

    //creamos el array que obtendra todos los inputs
    let inputs=document.querySelectorAll("input");  

    //creamos la variable que nos servira para cambiar las propiedades del body
    let fondo = document.querySelector("body"); 

    //obtenemos el lugar donde se va a mostrar el mensaje de error
    let mensajeError = document.getElementById("mensajeError");

    //mostramos el mensaje de error que anteriormente estaba en display:"hidden";
    mensajeError.style.display="inline-block";

    //para cada caso aplicamos distintos mensajes de error y cambiamos el borde de color a los respectivos inputs erroneos
    switch (codigoError) {
        case 1:
            mensajeError.innerHTML = "Datos incompletos, por favor rellene todos los campos.";
            break;
    
        case 2:
            mensajeError.innerHTML = "El usuario no existe: documento y/o contraseña erroneos.";
            break;

        default:
            //mensaje por si erroneamente se le redirigie con un codigo de error inexistente
            mensajeError.innerHTML = "Error inesperado, contactar con un administrador. Codigo de error: "+codigoError;
            break;
    }

    //marcamos ambos campos con rojo para marcar que tiene un problema
    inputs[0].style.borderColor="red";
    inputs[1].style.borderColor="red";

    //se cambia el fondo a uno con tonalidades rojas para apoyar la idea que tuvo un dato erroneo
    fondo.style.backgroundImage= "url(img/loginFallo.png)";

}

