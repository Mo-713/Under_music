import '../styles/utils.css'

const flashMessage = document.querySelector('.flash-message');

if (flashMessage) {
    // Ajouter une transition CSS
    flashMessage.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
    
    setTimeout(function() {
        flashMessage.style.opacity = '0';
        flashMessage.style.transform = 'translateY(-20px)';
    }, 3000);
    
    setTimeout(function() {
        flashMessage.remove();
    }, 3000);
}