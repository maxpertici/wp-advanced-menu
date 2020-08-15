<?php

defined( 'ABSPATH' ) or	die();



/**
 * Seter for WP:AM transients
 *
 * @since 0.1.0
 * 
 */
function wpam_set_transients( $value , $expiration = 0 ){
    delete_transient( WPAM_TRANSIENTS_SLUG );
    set_transient( WPAM_TRANSIENTS_SLUG, $value, $expiration );
}



/**
 * A wrapper to easily set a part of WPAM transients
 *
 * @since 0.1.0
 * 
 * @param string $key : slug of key of transient array : wpam_generate_datas_for_transients()
 * @param $value : value to store in transient
 * 
 * @return void
 *
 */
function wpam_set_transient_value( $key , $value , $expiration = 0 ){

    // get transient
    $transients   = get_transient( WPAM_TRANSIENTS_SLUG );
    
    // replace value
    $transients[$key] = $value ;

    set_transient( WPAM_TRANSIENTS_SLUG, $transients, $expiration );
}


/**
 * Test if value given is un transient
 *
 * @since 0.1.0
 * 
 * @param $value you are seaching
 * @param string $type of the $value (term_id or object)
 * @param string $key, part of transients where your value is (menus, locations or themes)
 * 
 * @return bool true or false if value is found ou not :/
 */
function wpam_is_value_in_transients( $value, $type = false, $key = false){

    if( !$key || !$type ){ return 'args error'; }

    $wpam_transients = get_transient( WPAM_TRANSIENTS_SLUG );

    if( $type === 'term_id' ){ $value = intval( $value ); }


    if( $key === 'menus' ){

        // var_dump($wpam_transients['menus']);

        foreach( $wpam_transients['menus'] as $menu_config ) {

            // 0 : slector
            // 1 : menu obj

            if( $menu_config[1]->$type === $value ){
                return true ;
            }
            

        }
        /*
        foreach( $wpam_transients['menus'] as $menu_obj ) {

            if ( $menu_obj->$type === $value ){
                return true;
            }
        }
        */
        
    }
    
    if( $key === 'menus' && $type === 'object' ){
        
        foreach( $wpam_transients['menus'] as $menu_config ) {
            
            if ( $menu_config[1] == $value ){ return true; }
        }

        /*
        foreach( $wpam_transients['menus'] as $menu_obj ) {
            if ( $menu_obj == $value ){ return true; }
        }
        */

    }
    
    if( $key === 'locations' ){
        return in_array( $value, $wpam_transients['locations'] ) ; 
    }

    if( $key === 'themes' ){
        foreach( $wpam_transients['themes'] as $theme_args ) {
            if ( $theme_args->$type === $value ){
                return true;
            }
        }
    }
    
    return false;
}



/**
 * Generate all structured datas for transients
 *
 * @since 0.1.0
 * 
 * @return array transients array with diffrentes part
 */
function wpam_generate_datas_for_transients(){
    
    $wpam_plugin             = array();
    $wpam_plugin['version']  = WPAM_VERSION ;
    $wpam_plugin['features'] = array() ;
    $wpam_plugin['theme_overriding_folder_path'] = WPAM_THEME_OVERIDING_FOLDER_PATH ;

    $wpam_themes_list_in_db = get_option( WPAM_THEMES_LIST_SLUG ) ;

    $wpam_plugin['theme_fallback'] = (Object) array(
        'theme_slug'     => $wpam_themes_list_in_db[ WPAM_THEME_ID_FALLBACK ]->theme_slug,
        'theme_core'     => $wpam_themes_list_in_db[ WPAM_THEME_ID_FALLBACK ]->theme_core,
        'theme_template' => '',
        'theme_origin' => '',
    );

    // menus part
    $wpam_menus              = array();
    $menus_using_wpam_plugin = array() ;

    $wp_menus = wp_get_nav_menus();
    foreach ( $wp_menus as $menu ){
        $wpam_theme            = get_field( 'wpam_menu_options_theme_selector'   , $menu );
        if( $wpam_theme != '' && $wpam_theme != NULL   ){
            $wpam_disable_override = get_field( 'wpam_menu_options_theme_overriding' , $menu );

            // array_push( $menus_using_wpam_plugin,  $menu);
            // $menus_using_wpam_plugin[ $wpam_theme ] = $menu ;
            
            // $menus_using_wpam_plugin[ $wpam_theme ] = $menu ;
            
            // $menus_using_wpam_plugin[ $menu ] = $wpam_theme ;
            array_push( $menus_using_wpam_plugin,  array( $wpam_theme, $menu ) );
            

        }
    }

    $wpam_menus = $menus_using_wpam_plugin ;
        
    

    // location part
    $wpam_locations = array();

    // Get all locations
    // @source : https://www.codementor.io/robbertvermeulen/get-nav-menu-items-by-location-es0n8lmtt
    $locations_using_wpam_plugin = array() ;
    $wp_locations = get_nav_menu_locations();

    // Get object id by location
    foreach ( $wp_locations as $location_slug => $location_id ){
        
        // Check if wp:am theme for this $menu
        $menu_obj      = wp_get_nav_menu_object( $location_id );
        $wpam_theme = get_field( 'wpam_menu_options_theme_selector'   , $menu_obj );

        if( $wpam_theme != '' && $wpam_theme != NULL   ){
            $wpam_disable_override = get_field( 'wpam_menu_options_theme_overriding' , $menu_obj );

            array_push( $locations_using_wpam_plugin ,  $location_slug );
        }

        $wpam_locations = $locations_using_wpam_plugin ;
    }

    // themes part
    $wpam_themes    = array();
    foreach ( $wpam_menus as $config ){

        $theme = $config[0];
        $menu  = $config[1];

        // $menu
        $wpam_association = array();
        
        $theme_slug     = wpam_get_menu_theme_by_slug(     $menu->slug );
        
        $theme_core     = wpam_get_theme_core(     $menu->slug );
        if( $theme_core === '' ){
            $theme_core = json_decode( '{"theme_location":"wpam"}' ) ;
        }
        
        $theme_override = wpam_get_theme_override( $menu->slug );

        $theme_template = wpam_get_theme_template( $menu->slug );
        if( $theme_template === '' ){
            $theme_template = $theme_slug ;
        }
        
        $theme_origin = wpam_get_theme_origin( $menu->slug );

        $wpam_association = (Object) array(
            'theme_slug'     => $theme_slug,
            'theme_core'     => $theme_core,
            'theme_override' => $theme_override,
            'theme_template' => $theme_template,
            'theme_origin'   => $theme_origin
        );

        if( $theme_slug ){ $wpam_themes[ $menu->slug ] = $wpam_association ; }
    }

    // styles part
    $wpam_styles    = array();

    // scripts part
    $wpam_scripts   = array();

    // organize and collect datas in unique array
    $wpam_transients = array(
        'plugin'         => $wpam_plugin,
        'menus'          => $wpam_menus,
        'locations'      => $wpam_locations,
        'themes'         => $wpam_themes,
        'styles'         => $wpam_styles,
        'scripts'        => $wpam_scripts,
        
    );

    return $wpam_transients;
}



/**
 * Helper for generating transients :
 * build array of data and save it
 *
 * @since 0.1.0
 * 
 * @return void
 */
function wpam_build_transients(){
    $wpam_transients = wpam_generate_datas_for_transients();	
    wpam_set_transients(  $wpam_transients );
}



/**
 * Helpers for transient delletation
 *
 * @since 0.1.0
 * 
 * @return void
 */
function wpam_delete_all_transients() {
    
    delete_transient( WPAM_TRANSIENTS_SLUG );

};



/**
 * Handle plugin desactivation, and delete transients for prevent
 * to not use theme is not longer activated
 *
 * @since 0.1.0
 * 
 * @param @see deactivated_plugin action documentation
 * 
 * @return void
 */
function wpam_plugin_desactivation_handler( $plugin, $network_deactivating ){

    wpam_delete_all_transients();

    // delete list
    // delete_option( WPAM_THEMES_LIST_SLUG );

}