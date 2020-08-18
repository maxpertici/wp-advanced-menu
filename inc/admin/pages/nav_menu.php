<?php

defined( 'ABSPATH' ) or	die();

/**
 * Enqueue script for admin WP:AM setings page
 *
 * @since 0.1.0
 */
add_action( 'admin_enqueue_scripts', 'wpam_nav_menu_enqueue_scripts' );

function wpam_nav_menu_enqueue_scripts( $hook ){

    if ( 'nav-menus.php' != $hook ) {
        return;
    }

    wp_register_style( 'wpam_nav_menu_style', plugins_url( '../css/wpam-nav-menu.css', __FILE__ ) );
    wp_enqueue_style( 'wpam_nav_menu_style' );

    wp_register_script( 'wpam_nav_menu_script', plugins_url( '../js/wpam-nav-menu.js', __FILE__ ), false, true);
    
    
    
    // Get custim item config.
    // include( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-types-config.php' ) ;
    include( WPAM_CORE_PATH . 'item-types.php' ) ;

    wp_enqueue_script( 'wpam_nav_menu_script' );
    wp_localize_script('wpam_nav_menu_script' , 'wpam_nav_menu_js_vars', array(
        'theme_notice'           => esc_html__('Save menu to see options.', 'wp-advanced-menu'),
        'custom_menu_item_spec'  => $wpam_custom_menu_item_spec,
        'nav_item_fields_keys'   => $wpam_nav_item_fields_keys
        )
    );
    

}



/**
 * Load resgistred themes in ACF fields selector "wpam_menu_options_theme_selector"
 *
 * @since 0.1.0
 * 
 * @param array $field values of ACF selector
 * 
 * @return array $field to ACF selctor
 */
function wpam_load_nav_menu_options( $field ) {
    
    // Load themes JSON in ACF
    $field['choices'] = array();
    $acf_fields = array();
    $wpam_themes_db_option = get_option( WPAM_THEMES_LIST_SLUG );
    
	foreach ( $wpam_themes_db_option as $key => $json) {
		$acf_fields[ $key ] = $json->theme_name ;
	}
    asort($acf_fields);
    $field['choices'] = $acf_fields ;
    
    // return the field
    return $field;
}

add_filter('acf/load_field/name=wpam_menu_options_theme_selector', 'wpam_load_nav_menu_options');



/**
 * 
 * 
 * 
 * @since 0.2.0
 */
function wpam_nav_menu_flush_generated_css_files_and_transients( $menu_id, $menu_data ){

    // Delete transients
    wpam_build_transients();

    // delete folder
    $upload_dir = wp_upload_dir();
    $path = trailingslashit( $upload_dir['basedir'] );
    
    wpam_delete_files( $path . 'wpam/' );

}


add_action( 'wp_update_nav_menu', 'wpam_nav_menu_flush_generated_css_files_and_transients', 22, 2);
// add_action( 'save_post', 'wpam_nav_menu_flush_generated_css_files', 22 );
// add_action( 'wp_update_nav_menu_item', 'wpam_nav_menu_flush_generated_css_files_and_transients', 22 );




/**
 * 
 *  PHP delete function that deals with directories recursively
 *  @source : https://paulund.co.uk/php-delete-directory-and-files-in-directory
 *  @since 0.2.0
 */
function wpam_delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            wpam_delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}



/**
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_nav_menu_write_css_files( $menu_id, $menu_data ){
    /*
    
    wpam_check_if_generated_css_folder_exist();
    
    wpam_check_if_generated_css_file_exist();

    wpam_generate_css_file();

    */
}

add_action( 'wp_update_nav_menu', 'wpam_nav_menu_write_css_files', 25, 2 );
// add_action( 'save_post', 'wpam_nav_menu_write_css_files', 25 );
// add_action( 'wp_update_nav_menu_item', 'wpam_nav_menu_write_css_files', 25 );




/**
 * Refresh nav-menus admin page on save
 * 
 * @since 0.1.0
 */
function wpam_nav_menu_save_post_refresh( $menu_id, $menu_data ) {
    
    $admin_screen = get_current_screen();

    if( $admin_screen->id === 'nav-menus'){
        
        echo '<script type="text/javascript">';
        echo 'function wpam_refresh_the_page(){ window.location.href=window.location.href };';
        echo 'wpam_refresh_the_page();';
        echo '</script>';            
    }
}

add_action( 'wp_update_nav_menu', 'wpam_nav_menu_save_post_refresh', 99, 2 );

// add_action( 'save_post', 'wpam_nav_menu_save_post_refresh', 99, 3 );
// add_action( 'wp_update_nav_menu_item', 'wpam_nav_menu_save_post_refresh', 99, 3 );


/**
 * 
 * 
 * 
 * @since 0.4.0
 */
function wpam_admin_nav_menu_redirect(){


    if(  isset( $_GET['menu'] )  ){

        $wp_menu   = absint( $_GET['menu'] );
        if( is_int( $wp_menu ) && $wp_menu > 0 ){


            if( isset( $_GET['action'] ) ){
                $wp_action = $_GET['action'];
                if( $wp_action === 'delete' ){
                    wp_delete_nav_menu(  $wp_menu );

                }
            }
        }
    }
    
    $nav_menus  = wp_get_nav_menus();
    $menu_id = $nav_menus[0]->term_id ;
    wp_redirect( admin_url( 'nav-menus.php?menu=' . intval( $menu_id ) ) );
    exit(); 
}


/**
 * Load element of theme wich need to be include in admin nav-menu screen
 *
 * @since 0.4.0
 * 
 */
function wpam_load_nav_menu_screen_theme_includes(){


    // —— —— —— —— —— —— —— —— —— ——
    // —— Is nav-menu page url ?

    // so :
    require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' );

    // admin url given
    $wp_admin_url = admin_url();
    
    // get full url
    $_http = 'http' ;
    if( $_SERVER['HTTPS'] === 'on' ){ $_http = 'https'; }

    $_request = $_http . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ;
    
    $_wp_admin = $wp_admin_url ;
    $_query = '?' . $_SERVER['QUERY_STRING'] ;
    
    $admin_page = str_replace( $_query, '', $_request );
    $admin_page = str_replace( $_wp_admin, '', $admin_page );

    if( $admin_page !== 'nav-menus.php' ){
        return ;
    }

    
    // —— —— —— —— —— —— —— —— —— ——
    // —— Case checlking

    $case = '';

    if(  isset( $_GET['menu'] )  ){

        $wp_menu   = absint( $_GET['menu'] );
        if( is_int( $wp_menu ) && $wp_menu > 0 ){ $case = 'selected'; }
    }

    if( isset( $_GET['action'] ) ){

        $wp_action = $_GET['action'];

        // Delete : ?action=delete&menu=42&_wpnonce=993fa67603
        if( $wp_action === 'delete' ){

            if( is_int( $wp_menu ) && $wp_menu  > 0 ){
                
                $case = 'selected';
                
                // get last menu
                // add action ? nop, that cut l oding page and so, no deleting performed
                add_action( 'admin_action_delete', 'wpam_admin_nav_menu_redirect', 99, 0 );

            }
        }

        // edit     : ?action=edit&menu=24
        // creation : ?action=edit&menu=0
        if( $wp_action === 'edit' ){
                        
            if( is_int( $wp_menu ) && $wp_menu  > 0 ){ $case = 'selected'; }
            if( is_int( $wp_menu ) && $wp_menu == 0 ){ $case = 'creation'; }

        }
        
    }

    // Check recent
    if( ! isset( $_GET['menu'] ) && ! isset( $_GET['action'] ) ){
        $case = 'recent';
    }


    // —— —— —— —— —— —— —— —— —— ——
    // Cases $menu_id ?
    if( $case === 'selected' ){
        $menu_id = $wp_menu ;
    }

    if( $case === 'recent' ){
        
        // get last menu
        $nav_menus  = wp_get_nav_menus();
        $menu_id = $nav_menus[0]->term_id ;
        wp_redirect( admin_url( 'nav-menus.php?menu=' . intval( $menu_id ) ) );
        exit();
    }

    // ——
    
    if( isset( $menu_id ) ){
        if( ! is_nav_menu( $menu_id ) ){ return ; }
        // var_dump($menu_id );
    }else{
        return ;
    }
    

    // ——
    $wpam = get_transient( WPAM_TRANSIENTS_SLUG );
    $wpam_themes = $wpam['themes'] ;

    // var_dump($wpam_themes);
    // die();
    $menu_obj = wp_get_nav_menu_object( $menu_id );
    // var_dump($menu_obj);
    //die();
    
    if( array_key_exists( $menu_obj->slug, $wpam_themes ) ){
        
        $menus = $wpam_themes[ $menu_obj->slug ] ;

        if( ! property_exists( $menus, "theme_template") ){
            $menus->theme_template = $menus->theme_slug ;
        }

        if( ! property_exists( $menus, "theme_origin") ){
            $menus->theme_origin = '' ;
        }
        
        $dirs = wpam_get_theme_directories(
            $menus->theme_slug,
            $menus->theme_core,
            $menus->theme_template,
            $menus->theme_override,
            $menus->theme_origin
            );

        
        // includes and mark as loaded
        // var_dump($dirs);
        wpam_load_theme_functions_files( $dirs, $menus->theme_override );

    }
    

}