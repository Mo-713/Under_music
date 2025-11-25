// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */


import './styles/app.css';
import './styles/utils.css';

const burgerIcon = document.querySelector('.burger-icon');
const navbarList = document.querySelector('.navbar-list');

// Gestion du clic sur l'icône burger
burgerIcon.addEventListener('click', () => {
    // Toggle la classe 'open' sur la liste de navigation
    navbarList.classList.toggle('open');
    
    // Toggle la classe 'open' sur l'icône burger pour l'animation
    burgerIcon.classList.toggle('open');
});

// Fermer le menu quand on clique sur un lien
const navbarLinks = document.querySelectorAll('.navbar-link');
navbarLinks.forEach(link => {
    link.addEventListener('click', () => {
        navbarList.classList.remove('open');
        burgerIcon.classList.remove('open');
    });
});

// Fermer le menu si on clique en dehors
document.addEventListener('click', (e) => {
    if (!burgerIcon.contains(e.target) && !navbarList.contains(e.target)) {
        navbarList.classList.remove('open');
        burgerIcon.classList.remove('open');
    }
});