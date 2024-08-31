// el menu
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-btn');
    const navLinks = document.querySelector('.nav-links');
    const hasSubmenu = document.querySelector('.has-submenu');
    
    if (menuBtn && navLinks) {
        menuBtn.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    } else {
        console.error('Elemento .menu-btn o .nav-links no encontrado');
    }
    
    if (hasSubmenu) {
        hasSubmenu.addEventListener('click', () => {
            hasSubmenu.classList.toggle('active');
        });
    } else {
        console.error('Elemento .has-submenu no encontrado');
    }
});

// coso popup
document.addEventListener('DOMContentLoaded', () => {
    const openFormBtn = document.getElementById('openForm');
    const closePopupBtn = document.getElementById('closePopup');
    const popup = document.getElementById('popup');
    const mostrarPopupBtn = document.getElementById('mostrarPopup');
    const popupHecho = document.getElementById('popupHECHO');

    if (openFormBtn && popup) {
        openFormBtn.addEventListener('click', function() {
            popup.style.display = 'flex';
        });
    } else {
        console.error('Elemento #openForm o #popup no encontrado');
    }

    if (closePopupBtn && popup) {
        closePopupBtn.addEventListener('click', function() {
            popup.style.display = 'none';
        });
    } else {
        console.error('Elemento #closePopup o #popup no encontrado');
    }

    if (popup) {
        window.addEventListener('click', function(event) {
            if (event.target === popup) {
                popup.style.display = 'none';
            }
        });
    } else {
        console.error('Elemento #popup no encontrado');
    }

    if (mostrarPopupBtn && popupHecho) {
        mostrarPopupBtn.addEventListener('click', function() {
            popupHecho.classList.add('mostrarHECHO');
            
            // Ocultar el mensaje después de 5 segundos
            setTimeout(function() {
                popupHecho.classList.remove('mostrarHECHO');
            }, 5000);
        });
    } else {
        console.error('Elemento #mostrarPopup o #popupHECHO no encontrado');
    }
});