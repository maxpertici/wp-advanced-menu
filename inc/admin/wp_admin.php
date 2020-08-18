<?php

/**
 * wpam_reusable_block_menu_display function.
 * 
 * @param mixed $type
 * @param mixed $args
 * @return void
 */
function wpam_reusable_block_menu_display( $type, $args ) {
	if ( 'wp_block' !== $type ) { return; }
	$args->show_in_menu = true;
	$args->_builtin = false;
	$args->labels->name = esc_html__( 'Reusable Blocks', 'wp-advanced-menu' );
	$args->labels->menu_name = esc_html__( 'Reusable Blocks', 'wp-advanced-menu' );
	$args->menu_icon = 'dashicons-screenoptions';
	$args->menu_position = 58;
}




/**
 * 
 * 
 * 
 * @since : 1.0
 * @source : https://www.zigpress.com/classicpress-cpt-admin-page-custom-menu-entry/
 */
function wpam_admin_menu() {
	
	# First, the top level menu entry
	add_menu_page(
		esc_html__('WP:AM','wp-advanced-menu'), # Page title
		esc_html__('WP:AM','wp-advanced-menu'), # Menu title

		'manage_options', # Capability
		'wpam-admin', # Menu slug
		'wpam_admin_render_overview',
		'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="#9ea3a8" d="M18.8,0L0.1,1.2L1.2,20l18.7-1.2L18.8,0z M17,16.9H9V14h8V16.9z M17,12H5V9h12V12z M17,7H3V4.1h14V7z"/></svg>')	
	);

	# Overview
	add_submenu_page(
		'wpam-admin', # Parent menu slug

		esc_html__('Overview','wp-advanced-menu'),
		esc_html__('Overview','wp-advanced-menu'),

		'manage_options',
		'wpam-admin', # Default subpage so menu slug is same as parent
		'wpam_admin_render_overview'
	);

	# CPT page
	add_submenu_page(
		'wpam-admin',
		esc_html__('Contents','wp-advanced-menu'),
		esc_html__('Contents','wp-advanced-menu'),
		'manage_options',
		'edit.php?post_type=wpam-content'
	);

}

add_action('admin_menu', 'wpam_admin_menu');




/**
 * 
 * 
 * 
 * @since : 1.0
 * @source : https://www.zigpress.com/classicpress-cpt-admin-page-custom-menu-entry/
 */
function wpam_admin_set_parent_file($parent_file) {
	global $current_screen;
	
	if ($current_screen->post_type == 'wpam-content' ) {
		if (in_array($current_screen->base, array('post', 'edit')) !== false) {
			return 'wpam-admin'; # Parent menu slug
		}
	}

	return $parent_file;
}

add_filter('parent_file', 'wpam_admin_set_parent_file');