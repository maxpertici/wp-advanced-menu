<?php

defined( 'ABSPATH' ) or	die();

/**
 * 
 * 
 * 
 * 
 * @since 1.0
 */
function wpam_admin_render_overview(){

    ?>
    <div class="wpam-admin-page-wrapper">
    <div class="wpam-admin-page-inner">

    <div class="wpam-admin-page-header-banner">
    <?php
        // WP:AM logo
        echo '<img class="wpam-admin-page-header-logo" src="' . plugins_url( '../img/wpam-logo.png', __FILE__ )
        . '" title="' . esc_html( get_admin_page_title() ) . '" > ';

        ?>
    </div>

    <div class="wpam-admin-page-section" id="wpam-themes">

    <h2 class="wpam-admin-page-section-title"><?php echo __( 'Themes' , 'wp-advanced-menu');?></h2>
        <?php

        $wpam_thems_bd = get_option( WPAM_THEMES_LIST_SLUG ) ;

        // Separate thems location
        $wpam_themes_buildin = array();
        $wpam_themes_plugin  = array();
        $wpam_themes_theme   = array();
        $wpam_themes_uploads = array();
        
        foreach( $wpam_thems_bd as $key => $theme ){
                
            switch( $theme->theme_core->theme_location ){
                
                case  'wpam':
                $wpam_themes_buildin[$key]= $theme ;
                break;

                case  'plugin':
                $wpam_themes_plugin[$key]= $theme ;
                break;

                case  'theme':
                $wpam_themes_theme[$key]= $theme ;
                break;

                case  'uploads':
                $wpam_themes_uploads[$key]= $theme ;
                break;
                
            }
        }



        /**
         * 
         * 
         * WPAM
         * ————
         * Buildin themes
         * 
         */
        if( $wpam_themes_buildin ){
        ?>
            <div class="wpam-admin-page-section-content-themes">
                <h3 class="wpam-admin-page-themes-category-title"><?php echo __( 'Included in WP:AM' , 'wp-advanced-menu');?></h3>

                <div class="wpam-admin-page-themes-list">
                <?php
                foreach( $wpam_themes_buildin as $theme ){
                    
                    ob_start();
                    include( WPAM_ADMIN_PATH . 'views/theme-resume.php');
                    $buildin_theme_output = ob_get_contents();
                    ob_end_clean();
                    echo  $buildin_theme_output ;
                }
                ?>
                </div><!-- /.wpam-admin-page-themes-list -->

            </div><!-- /.wpam-admin-page-section-content-themes -->
        <?php }

        

        /**
         * 
         * 
         * WPAM
         * ————
         * From plugin
         * 
         */
        if( $wpam_themes_plugin ){
        ?>
            <div class="wpam-admin-page-section-content-themes">
                <h3 class="wpam-admin-page-themes-category-title"><?php echo __( 'From plugins' , 'wp-advanced-menu');?></h3>

                <div class="wpam-admin-page-themes-list">
                <?php
                foreach( $wpam_themes_plugin as $theme ){
                    
                    ob_start();
                    include( WPAM_ADMIN_PATH . 'views/theme-resume.php');
                    $plugin_theme_output = ob_get_contents();
                    ob_end_clean();
                    echo  $plugin_theme_output ;
                }
                ?>
                </div><!-- /.wpam-admin-page-themes-list -->

            </div><!-- /.wpam-admin-page-section-content-themes -->
        <?php }

        

        /**
         * 
         * 
         * WPAM
         * ————
         * From themes
         * 
         */
        if( $wpam_themes_theme ){
            ?>
                <div class="wpam-admin-page-section-content-themes">
                <h3 class="wpam-admin-page-themes-category-title"><?php echo __( 'From themes' , 'wp-advanced-menu');?></h3>
                <div class="wpam-admin-page-themes-list">
                <?php
                foreach( $wpam_themes_theme as $theme ){
                    
                    ob_start();
                    include( WPAM_ADMIN_PATH . 'views/theme-resume.php');
                    $themes_theme_output = ob_get_contents();
                    ob_end_clean();
                    echo  $themes_theme_output ;
                }
                ?>
                </div><!-- /.wpam-admin-page-themes-list -->
                </div><!-- /.wpam-admin-page-section-content-themes -->

    </div><!-- /.wpam-admin-page-section -->

    </div>    
    </div>
    
    <?php }
    
}



/**
 * 
 * 
 * @since 1.0
 */
function wpam_admin_overview_enqueue_scripts( $hook ){

    if ( 'toplevel_page_wpam-admin' != $hook ) {
        return;
    }

    // Fancybox
    wp_register_script( 'wpam_node_modules_fancybox_script', plugins_url( 'node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js', WPAM_NODE_MODULES ), false, true);
    wp_enqueue_script( 'wpam_node_modules_fancybox_script' );

    wp_register_style( 'wpam_node_modules_fancybox_style', plugins_url( 'node_modules/@fancyapps/fancybox/dist/jquery.fancybox.css', WPAM_NODE_MODULES ) );
    wp_enqueue_style( 'wpam_node_modules_fancybox_style' );    

    // WP:AM
    wp_register_style( 'wpam_settings_style', plugins_url( '../css/wpam-overview.css', __FILE__ ) );
    wp_enqueue_style( 'wpam_settings_style' );

    wp_register_script( 'wpam_settings_script', plugins_url( '../js/wpam-overview.js', __FILE__ ), false, true);
    wp_enqueue_script( 'wpam_settings_script' );
}

add_action( 'admin_enqueue_scripts', 'wpam_admin_overview_enqueue_scripts' );
