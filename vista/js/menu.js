//el menu
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-btn');
    const navLinks = document.querySelector('.nav-links');
    const hasSubmenu = document.querySelector('.has-submenu');
    
    menuBtn.addEventListener('click', () => {
    navLinks.classList.toggle('active');
    });
    
    hasSubmenu.addEventListener('click', () => {
    hasSubmenu.classList.toggle('active');
    });
    });
    
    
    //coso popup
    
    document.getElementById('openForm').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'flex';
    });
    
    document.getElementById('closePopup').addEventListener('click', function() {
        document.getElementById('popup').style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === document.getElementById('popup')) {
            document.getElementById('popup').style.display = 'none';
        }

        document.getElementById('mostrarPopup').addEventListener('click', function() {
            var popupHecho = document.getElementById('popupHECHO');
            popupHecho.classList.add('mostrarHECHO');
            
            // Ocultar el mensaje después de 5 segundos
            setTimeout(function() {
                popupHecho.classList.remove('mostrarHECHO');
            }, 5000);
        });
    });