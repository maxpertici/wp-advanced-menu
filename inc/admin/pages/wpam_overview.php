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
        
        <div class="wpam-admin-page-section wpam-admin-page-section-general">
            <h2 class="wpam-admin-page-section-title"><?php echo esc_html__( 'General' , 'wp-advanced-menu');?></h2>
            <div class="wpam-admin-page-info-list">
            
            <div class="wpam-admin-page-section-content-col  wpam-admin-col-size-4 wpam-admin-page-section-content-user-help">
                
                <h3 class="wpam-admin-page-info-col-title"><?php echo esc_html__( 'Informations' , 'wp-advanced-menu');?></h3>
                
                <div class="wpam-admin-page-info-col-content">
                <?php

                echo '<img class="wpam-admin-page-info__brand-avatar" height="60" width="60" src="' . plugins_url( '../img/maxpertici-avatar-200x200.jpg', __FILE__ )
                . '" > ';
                
                echo '<div class="wpam-admin-page-info__brand-text">' .

                    '<p class="wpam-admin-page-info__brand-text__title">' .
                    
                    sprintf( 
                        __( 'Hi ! It’s <a href="%s" target="_blank">@maxpertici</a>, the developer of WP Advanced Menu.' , 'wp-advanced-menu' ), 
                        esc_url( 'https://twitter.com/maxpertici' ) 
                    ) .
                    
                    '</p>' .

                    '<p>' .
                    __( 'This plugin allows developers to create layouts and functionality for navigation menus. This plugin is still experimental at the moment.' , 'wp-advanced-menu') .
                    
                    '</p>' .
                    
                    '</div>' ; 

                ?>
                </div><!-- /.wpam-admin-page-info-col-content -->
            </div><!-- /. wpam-admin-page-section-content-user-help -->

            <div class="wpam-admin-page-section-content-col wpam-admin-col-size-4">
                <h3 class="wpam-admin-page-info-col-title"><?php echo esc_html__( 'Documentation' , 'wp-advanced-menu');?></h3>
                <div class="wpam-admin-page-info-col-content">
                <?php 

                echo 

                '<p>' . esc_html__( 'Draft documentation is available (and almost up to date)', 'wp-advanced-menu' ) . '</p>' . 
                    
                '<ul>' .

                '<li>' .
                sprintf( 
                    __( '<a href="%s" target="_blank">Go to documentation →</a>', 'wp-advanced-menu' ), 
                    esc_url( 'https://maxpertici.slite.com/p/note/SyHKacXjEJffPmco7QZPaN' ) 
                ) .
                '</li>' .

                '</ul>' ;

                ?>
                </div><!-- /.wpam-admin-page-info-col-content -->
            </div><!-- /.wpam-admin-page-section-content-col -->

            <div class="wpam-admin-page-section-content-col wpam-admin-col-size-4">
                <h3 class="wpam-admin-page-info-col-title"><?php echo esc_html__( 'Feedback' , 'wp-advanced-menu');?></h3>
                <div class="wpam-admin-page-info-col-content">
                <?php 

                echo 

                '<p>' . __( 'All feedback is welcome. It helps me improve this plugin. Thanks for your help :)', 'wp-advanced-menu' ) . '</p>' . 
                    
                '<ul>' .

                '<li>' .
                sprintf( 
                    __( '<a href="%s" target="_blank">Write feedback →</a>', 'wp-advanced-menu' ), 
                    esc_url( 'https://docs.google.com/forms/d/e/1FAIpQLSdPIe_CdK81Cjc-croXpUziCdnskjIK4YOERoH53LyGOw2WwQ/viewform' ) 
                ) .
                '</li>' .

                '</ul>' ;

                ?>
                </div><!-- /.wpam-admin-page-info-col-content -->
            </div><!-- /.wpam-admin-page-section-content-col -->
            
            </div><!-- /.wpam-admin-page-info-list -->
        </div><!-- /.wpam-admin-page-section -->

        <div class="wpam-admin-page-section">

            <h2 class="wpam-admin-page-section-title"><?php echo esc_html__( 'Themes' , 'wp-advanced-menu');?></h2>
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
                    <h3 class="wpam-admin-page-themes-category-title"><?php echo esc_html__( 'Included in WP:AM' , 'wp-advanced-menu');?></h3>

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
                    <h3 class="wpam-admin-page-themes-category-title"><?php echo esc_html__( 'From plugins' , 'wp-advanced-menu');?></h3>

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

                    <h3 class="wpam-admin-page-themes-category-title"><?php echo esc_html__( 'From themes' , 'wp-advanced-menu');?></h3>
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
            
            <?php } ?>

        </div><!-- /.wpam-admin-page-section -->

    
    </div><!-- /.wpam-admin-page-inner -->
    </div><!-- /.wpam-admin-page-wrapper -->

<?php 

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
