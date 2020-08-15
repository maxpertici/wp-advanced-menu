jQuery(document).ready(function($){

    /**
     * 
     * 
     * 
     * 
     * 
     */


    /**
     * 
     * WP:AM settings page
     * ———————————————————
     * Theme Popin
     * 
     */
    function wpam_admin_theme_popin(){

        // @source : https://fancyapps.com/fancybox/
        $('.wpam-admin-theme').fancybox({
            
            maxWidth	: 800,
            maxHeight	: 600,
            
            margin      : 40,
            padding     : 40,

            closeClick: false,
            openEffect	: 'none',
            closeEffect	: 'none',
            autoScale: false,
            type: 'ajax',

            // other options
            beforeLoad: function () {
               
               var url = $(this.element).attr("data-wpam-theme-style-url");
               console.log(url);
               this.href = url ;

            }
        });

    }

    wpam_admin_theme_popin();


    /**
     * 
     * 
     * 
     * 
     * 
     */
});