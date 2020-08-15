<?php
/*
Plugin Name:  WP Advanced Menu
Plugin URI:   https://wpam.io
Description:  Great menu to launch
Version:      1.0
Author:       Maxime Pertici
Author URI:   https://m.pertici.fr
Contributors:
License:      GPLv2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wp-advanced-menu
Domain Path:  /languages
Copyright 2018-2019 WP Advanced Menu
*/



defined( 'ABSPATH' ) or	die();



/**
 * Load defines
 * 
 */
require_once( 'config.php' );



/**
 * Tell WP what to do when plugin is activated.
 *
 * @since 0.1.0
 */
function wpam_activation() {
	
	// set transients wpam_settings_state  + default settings ?
	// initialisation tests ?
	// key validating ?
	// Update licence key ?
}

register_activation_hook( __FILE__, 'wpam_activation' );



/**
 * Tell WP what to do when plugin is deactivated.
 *
 * @since 0.1.0
 */
function wpam_deactivation() {

	// Update customer key & licence. ?

	// disable activation ?

	// Deletes transients
	wpam_delete_all_transients( );
}

register_deactivation_hook( __FILE__, 'wpam_deactivation' );



/**
 * First load with licence validation + hooks
 *
 * @since 0.1.0
 */

function wpam_preload(){

	// Translations
	$locale = get_locale();
	$locale = apply_filters( 'plugin_locale', $locale, 'wp-advanced-menu' );
	load_textdomain( 'wp-advanced-menu', WP_LANG_DIR . '/plugins/wp-advanced-menu-' . $locale . '.mo' );
	load_plugin_textdomain( 'wp-advanced-menu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// Very basics functions
	wpam_load_plugin_basic_functions();

	// Hooks
	wpam_load_plugin_hooks();

	// Vendors & lib
	wpam_load_plugin_vendors_and_lib();

}

add_action( 'plugins_loaded', 'wpam_preload' );



/**
 * Second load, initialisation et fire wpam_loaded action
 *
 * @since 0.1.0
 */
function wpam_init() {

	if( wpam_is_acf_loaded() ){

		// Load core
		wpam_load_plugin_core();

		
		// Admin ?
		if( is_admin() ){
			wpam_load_plugin_admin();
			require_once( WPAM_LEGACY_MENU_METABOX . 'wpam-menu-item-types.php' ) ;
		}

		// Prepare datas for next step
		$wpam_prepare_themes = wpam_prepare_themes_datas();

		// get transient, if not -> save it
		// OR preparation return a action 'rebuild_transients'
		if(
			( get_transient( WPAM_TRANSIENTS_SLUG ) === false ) ||
			( $wpam_prepare_themes === 'rebuild_transients' )
			) {
			wpam_build_transients();
		}


		/**
		 * Fires when WPAM is loaded
		 *
		 * @since 0.1.0
		*/
		do_action( 'wpam_loaded' );
	}
}

add_action( 'after_setup_theme', 'wpam_init' );



/**
 * Load includes for menu admin
 *
 * @since  ?
 */
if( ! is_admin() ){ add_action( 'after_setup_theme', 'wpam_load_themes_includes' ); }


/**
 * Load helpers below
 *
 */
function wpam_load_plugin_basic_functions(){
	require( WPAM_CORE_PATH . 'options.php' );
	require( WPAM_CORE_PATH . 'transients.php' );
}

function wpam_load_plugin_vendors_and_lib(){
	
	// ACF ?
	
	require( WPAM_CORE_PATH . 'acf.php' );

	if( ! wpam_is_acf_loaded() ){

		add_action('admin_notices', 'wpam_notice_acf_plugin_required');
	}

	if( wpam_is_acf_loaded() ){

		// ACF — Load group & fields
		if( is_admin() ){
			
			add_action( 'acf/init', 'wpam_load_acf_field', 10 );
			add_action( 'acf/init', 'wpam_load_nav_menu_screen_theme_includes', 10 );

			add_action( 'acf/init', 'wpam_do_action_wpam_options_handler', 10);
			// add_action( 'acf/init', 'wpam_do_action_wpam_load_theme_options', 10);

			add_action( 'acf/init', 'wpam_do_action_wpam_enqueue_scripts', 10);
		}
	}
}

function wpam_load_plugin_hooks(){
	add_filter( 'wpam_add_extra_themes'         , 'wpam_add_extra_themes', 10 );

	// add_action( 'wp_enqueue_scripts'			, 'wpam_do_action_wpam_options_handler', 10);
	add_action( 'wp_enqueue_scripts'            , 'wpam_do_action_wpam_enqueue_scripts', 10);
	
		
	add_action( 'wp_print_styles'				, 'wpam_styles_reorder', 9999 );
	add_action( 'get_footer'					, 'wpam_styles_reorder', 9999 );

	add_filter( 'pre_wp_nav_menu'               , 'wpam_pre_wp_nav_menu_cb' , 10, 2 ); 
	add_filter( 'wp_nav_menu_args'              , 'wpam_wp_nav_menu_args_cb', 10, 1 );

	add_filter( 'wpam_fields_garbage_collector' , 'wpam_fields_garbage_collector', 10);
	
	add_action( 'deactivated_plugin'            , 'wpam_plugin_desactivation_handler', 10, 2 );
}

function wpam_load_plugin_admin(){
	
	require( WPAM_ADMIN_PATH . '/wp_admin.php' );

	// require( WPAM_ADMIN_PATH . '/pages/wpam_settings.php' );
	require( WPAM_ADMIN_PATH . '/pages/nav_menu.php' );
	require( WPAM_ADMIN_PATH . '/pages/wpam_overview.php' );

	add_action( 'wp_update_nav_menu', 'wpam_build_transients', 10, 2 );

	// add_action( 'registered_post_type', 'wpam_reusable_block_menu_display', 10, 2 );
}

function wpam_load_plugin_core(){
	wpam_load_plugin_core_files();
	wpam_load_plugin_walkers();
}

function wpam_load_plugin_core_files(){
	require_once( WPAM_CORE_PATH . 'post-types.php' );
	require_once( WPAM_CORE_PATH . 'menu.php' );
	require_once( WPAM_CORE_PATH . 'theme.php' );
	require_once( WPAM_CORE_PATH . 'scripts-and-styles.php' );
	require_once( WPAM_CORE_PATH . 'template.php' );
	require_once( WPAM_CORE_PATH . 'walkers.php' );
}

function wpam_load_plugin_walkers(){
	require_once( WPAM_WALKER_PATH . 'WPAM_Menu.php' );
	require_once( WPAM_WALKER_PATH . 'WPAM_Theme.php' );
	require_once( WPAM_WALKER_PATH . 'WPAM_Builder.php' );
}