<?php

defined( 'ABSPATH' ) or	die();


/**
 * 
 * 
 * 
 */
load_theme_textdomain( 'wpam-wp-theme', plugin_dir_path( __FILE__ ) .'/languages' );



/**
 * 
 * 
 * 
 * 
 */
wpam_set_theme_id( 'wp-theme', json_decode('{"theme_core":{"theme_location":"wpam"}}') );



/**
 * 
 * 
 * 
 */
function wpam_wp_theme_menu_class( $args ){

    $args['menu_class'] .= ' wpam wpam-wp-theme';
    return $args;
}

$theme_slug = 'wp-theme' ;
add_filter( "wpam_filter_theme_{$theme_slug}_nav_menu_args", 'wpam_wp_theme_menu_class', 10, 1 ); 



/**
 * 
 * 
 * 
 */
add_action( 'wpam_enqueue_scripts', 'wpam_wpteme_styles', 10 );

if ( ! function_exists( 'wpam_wpteme_styles' ) ){

    function wpam_wpteme_styles( ){

        wpam_enqueue_style(
            'wpam-wp-theme-style',
            plugins_url( 'style.css', __FILE__ ),
            array( wpam_themes_styles( __FILE__, __LINE__) ),
            false,
            'screen'
        );
        
    }
}