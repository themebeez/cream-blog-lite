(function($) {

    "use strict";

    jQuery(document).ready(function() {


        // Initialization - Carousel
        
        jQuery('#cb-banner-style-9').owlCarousel({
            items: 1,
            loop: true,
            lazyLoad: false,
            margin: 30,
            smartSpeed: 800,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 8000,
            autoplayHoverPause: true,
            navText: ["<i class='cb cb-chevron-left'></i>", "<i class='cb cb-chevron-right'></i>"],
            responsive: {
                0: {
                    items: 1
                },
                767: {
                    items: 2
                },
                991: {
                    items: 2
                },
                1199: {
                    items: 3
                }
            }
        });

        jQuery( '.woocommerce-sidebar' ).addClass( 'sidebar-layout-two' );

    });

})(jQuery);