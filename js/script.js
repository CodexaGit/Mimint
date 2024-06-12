let concuerda=0;
let error=1;
const cedula = document.getElementById("cedula");
function verificar(){
    const fondo = document.querySelector("body");
    const inputs =document.querySelectorAll("input");    
    const mensajeError = document.querySelector("#mensajeError");
    
    
    if(error==1){
        inputs[0].style.borderColor="red";
        inputs[1].style.borderColor="red";
        fondo.style.backgroundImage= "url(img/loginFallo.png)";
        if(cedula.nodeValue.length!=8){
            
        }
    }
}