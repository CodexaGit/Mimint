
//funcion que va a servir para verificar el login :v
function verificar(){

    //creamos la variable cedula que va a guardar el valor de cedula
    let cedula = document.getElementById("cedula").value;

    //creamos la variable contrasena que va a guardar el valor de contraseña
    let contrasena = document.getElementById("contrasena").value;


    //comprobamos si la cedula o la contraseña estan vacios, en respectivos casos se manda un mensaje a la funcion error
    if(cedula=="" && contrasena==""){
        error(3);
    } else if (cedula=="" || contrasena==""){
        if (cedula==""){
            error(1);
        }
        if (contrasena==""){
            error(2);
        }
    } else {
        //si ninguno de los datos estan vacios comprueba si ese usuario existe. Por ahora solo existe un usuario de prueba.
        if (cedula == "1234" && contrasena == "1234"){
            //si esta todo bien se redirige a la pagina principal
            location.href ="paginaPrincipal.html";
        } else {
            error(4);
        }
    }
}

//funcion para administrar y mostrar los errores :v
function error(codigoError){
    /*  

    CODIGOS DE ERRORES:

    1 - CEDULA VACIA

    2 - CONTRASEÑA VACIA

    3 - CEDULA Y CONTRASEÑA VACIA

    4 - USUARIO INEXISTENTE

    */

    //creamos el array que obtendra todos los inputs
    let inputs=document.querySelectorAll("input");  

    //creamos la variable que nos servira para cambiar las propiedades del body
    let fondo = document.querySelector("body"); 

    //obtenemos el lugar donde se va a mostrar el mensaje de error
    let mensajeError = document.getElementById("mensajeError");

    //le quitamos el borde de color por si acaso que haya corregido un error
    inputs[0].style.borderColor="";
    inputs[1].style.borderColor="";

    //mostramos el mensaje de error que anteriormente estaba en display:"hidden";
    mensajeError.style.display="inline-block";

    //para cada caso aplicamos distintos mensajes de error y cambiamos el borde de color a los respectivos inputs erroneos
    switch (codigoError) {
        case 1:
            mensajeError.innerHTML = "El documento no puede estar vacio.";
            inputs[0].style.borderColor="red";
            break;
    
        case 2:
            mensajeError.innerHTML = "La contraseña no puede estar vacia.";
            inputs[1].style.borderColor="red";
            break;

        case 3:
            mensajeError.innerHTML = "Por favor complete los campos.";
            inputs[0].style.borderColor="red";
            inputs[1].style.borderColor="red";
            break;
        case 4:
            mensajeError.innerHTML = "El usuario no existe: documento y/o contraseña erroneos.";
            break;
        default:
            //mensaje por si erroneamente se le redirigie con un codigo de error inexistente
            mensajeError.innerHTML = "Error inesperado, contactar con un administrador. Codigo de error: "+codigoError;
            break;
    }

    //se cambia el fondo a uno con tonalidades rojas para apoyar la idea que tuvo un dato erroneo
    fondo.style.backgroundImage= "url(img/loginFallo.png)";
}

