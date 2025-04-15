document.getElementById('hamburger').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden'); // Toggle visibility
    
    this.classList.toggle('hidden');
    const cross = document.getElementById('hamcross');
    cross.classList.toggle('hidden');
    
    
    });
    document.getElementById('hamcross').addEventListener('click', function() {
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden'); // Toggle visibility
    
    this.classList.toggle('hidden');
    const cross = document.getElementById('hamburger');
    cross.classList.toggle('hidden');
    
    });