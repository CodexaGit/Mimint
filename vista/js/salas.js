document.getElementById('mostrarPopup').addEventListener('click', function() {
    var popupHECHO = document.getElementById('popupHECHO');
    popupHECHO.classList.add('mostrarHECHO');
    
    setTimeout(function() {
        popupHECHO.classList.remove('mostrarHECHO');
        popupHECHO.classList.add('ocultoHECHO');
    }, 5000);
});
