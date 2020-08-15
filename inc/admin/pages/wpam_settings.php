<?php

defined( 'ABSPATH' ) or	die();

/*

// wpam_delete_all_options();

$wpam_general_settings = get_option('wpam_general_settings'); 
var_dump( $wpam_general_settings );
*/

/**
 * Add page to admin menu and add action
 * 
 * @since 0.1.0
 */
function wpam_load_settings_page(){

    add_theme_page(
        'WP:AM',
        'WP:AM',
        'manage_options',
        'wpam',
        'wpam_load_general_settings_page'
    );

    add_action( 'admin_init', 'wpam_register_general_settings' );

    add_action( 'admin_enqueue_scripts', 'wpam_admin_settings_page_enqueue_scripts' );
}

add_action( 'admin_menu', 'wpam_load_settings_page'   );



/**
 * Enqueue script for admin WP:AM setings spage
 *
 * @since 0.1.0
 */
function wpam_admin_settings_page_enqueue_scripts( $hook ){
    

    if ( 'appearance_page_wpam' != $hook ) {
        return;
    }

    
    // Fancybox
    wp_register_script( 'wpam_node_modules_fancybox_script', plugins_url( 'node_modules/fancybox/dist/js/jquery.fancybox.pack.js', WPAM_NODE_MODULES ), false, true);
    wp_enqueue_script( 'wpam_node_modules_fancybox_script' );

    wp_register_style( 'wpam_node_modules_fancybox_style', plugins_url( 'node_modules/fancybox/dist/css/jquery.fancybox.css', WPAM_NODE_MODULES ) );
    wp_enqueue_style( 'wpam_node_modules_fancybox_style' );    

    // WP:AM
    wp_register_style( 'wpam_settings_style', plugins_url( '../css/wpam-settings.css', __FILE__ ) );
    wp_enqueue_style( 'wpam_settings_style' );

    wp_register_script( 'wpam_settings_script', plugins_url( '../js/wpam-settings.js', __FILE__ ), false, true);
    wp_enqueue_script( 'wpam_settings_script' );
}



/**
 * Function attached top settings validation, use to rebuilt transients
 * 
 * @since 0.1.0
 */
function wpam_update_wpam_settings_page( $old_value, $value, $option ) { 
    wpam_build_transients();
}; 
            
add_action( "update_option_{WPAM_GENERAL_SETTINGS_SLUG}", 'wpam_update_wpam_settings_page', 10, 3 ); 



/**
 * Resgister WP:AM general settings
 * 
 * @since 0.1.0
 */
function wpam_register_general_settings() {

    // register settings
    // licence key
    // register_setting( 'wpam_general_settings', 'wpam_licence_key' );

    $args = array(
        'type'              => 'string',
        'group'             => 'wpam_general_settings',
        'description'       => '',
        'sanitize_callback' => 'wpam_validate_settings',
        'show_in_rest'      => false,
    );

    register_setting( 'wpam_general_settings', 'wpam_general_settings', $args );
}

function wpam_validate_settings( $input ) {
    /*
    // Validate age as an integer
    $input['age'] = intval( $input['age'] );

    // Strip HTML tags from text to make it safe
    $input['text'] = wp_filter_nohtml_kses( $input['text'] );

    // Make sure isauthorized is only true or false (0 or 1)
    $input['isauthorized'] = ( $input['isauthorized'] == 1 ? 1 : 0 );
    */

    return $input;
}






/**
 * Generate WP:AM settings page layout and form
 * 
 * @since 0.1.0
 */
function wpam_load_general_settings_page() {
    

    ?>
    <div class="wrap wpam-admin-page-wrapper">
    <div class="wpam-admin-page-inner">
        <div class="wpam-admin-page-header">

            <div class="wpam-admin-page-header-banner">
                <?php
                
                    // WP:AM logo
                    echo '<img class="wpam-admin-page-header-logo" src="' . plugins_url( 'img/wpam-logo.png', __FILE__ )
                            . '" title="' . esc_html( get_admin_page_title() ) . '" > ';
                ?>
            </div>

            <h1 class="wpam-admin-page-header-title"><?php echo get_admin_page_title(); ?></h1> 
            <?php


            // check if the user have submitted the settings
            // wordpress will add the "settings-updated" $_GET parameter to the url
            if ( isset( $_GET['settings-updated'] ) ) {
                
                // add settings saved message with the class of "updated"
                add_settings_error( 'wpam_general_settings', 'wpam-settings-updated', __( 'Settings Saved', 'wp-advanced-menu' ), 'updated' );
                }
                // show error/update messages
                settings_errors( 'wpam_general_settings' );

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
        <?php } ?>
    
    </div>   
    </div><!-- /.wpam-admin-page-inner -->

    <div class="wpam-inner-sidebar">

            <div class="wpam-plugin-quote-user-help">
            <?php
            echo '<img class="wpam-plugin-quote-user-help_avatar" height="60" width="60" src="' . plugins_url( 'img/maxpertici-avatar-200x200.jpg', __FILE__ )
            . '" > ';
            echo '<div class="wpam-plugin-quote-user-help_text">' .
                '<p class="wpam-plugin-quote-user-help_text_title">' .
                sprintf( 
                    __( 'Hi ! It’s <a href="%s" target="_blank">@maxpertici</a>, the developer of WP Advanced Menu' , 'wp-advanced-menu' ), 
                    esc_url( 'https://twitter.com/maxpertici' ) 
                ) . '</p>' .

                '<p><strong>' . __( 'Links:', 'wp-advanced-menu' ) . '</strong></p>' .

                '<ul>' .

                '<li>' .
                sprintf( 
                    __( '<a href="%s" target="_blank">Feedbacks</a>', 'wp-advanced-menu' ), 
                    esc_url( 'https://docs.google.com/forms/d/e/1FAIpQLSdPIe_CdK81Cjc-croXpUziCdnskjIK4YOERoH53LyGOw2WwQ/viewform' ) 
                ) .
                
                __( ' & ', 'wp-advanced-menu' )  .

                sprintf( 
                    __( '<a href="%s" target="_blank">Documentation</a>.', 'wp-advanced-menu' ), 
                    esc_url( 'https://maxpertici.slite.com/p/note/SyHKacXjEJffPmco7QZPaN' ) 
                ) .
                '</li>' .

                '</ul>' .

                '</div>';
            ?>
            </div><!-- /.wpam-plugin-quote-user-help -->

    </div><!-- /.wpam-inner-sidebar -->
        
    <div class="" id="wpam-footer">
        <?php
        echo '<div class="wpam-plugin-colophon">' ; 

        echo '<p class="wpam-plugin-version-number">' . WPAM_PLUGIN_NAME . ' ' . WPAM_VERSION . '</p>';

        echo '</div>' ;
        ?>
    </div><!-- /#wpam-footer -->
        

    <!-- Start of maxpertici Zendesk Widget script -->
    <!--
    <script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=c88d4c78-279d-439d-8350-4f7b2e5d524a"> </script>
    -->
    <!-- End of maxpertici Zendesk Widget script -->

    </div><!-- /.wpam-admin-page-wrapper -->
<?php }