$(document).ready(function() {

    var $container = $('#contenuti');
    
    // Call imagesLoaded and position images
    $container.imagesLoaded( function(){ 
        $container.isotope({
            itemSelector: '.element',
            animationEngine: 'best-available',
            resizesContainer: true
        });
    });
    

    
});