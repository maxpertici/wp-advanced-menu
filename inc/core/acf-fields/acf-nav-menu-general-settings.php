<?php

defined( 'ABSPATH' ) or	die();



if( function_exists('acf_add_local_field_group') ){
	
	/**
	 * Garbage
	 *
	 * @since 0.1.0
	 */
	
	update_option( WPAM_GARBAGE_SLUG, array() );
	/*

	$wpam_garbage = get_option( WPAM_GARBAGE_SLUG );
	
	if( $wpam_garbage == false ){
		add_option( WPAM_GARBAGE_SLUG, array() );
	}else{
		update_option( WPAM_GARBAGE_SLUG, array() );
	}
	
	*/



	$wpam_fields_garbage = array(
		'wpam_menu_options_theme_selector',
		'wpam_menu_options_theme_overriding',
	);

	apply_filters( 'wpam_fields_garbage_collector', $wpam_fields_garbage );

	/**
	 * Fields
	 *
	 * @since 0.1.0
	 */
	// acf_remove_local_field_group( WPAM_ACF_PREFIX_GROUP.'wpam_group_advanced_settings' );
	acf_add_local_field_group(array(
		'key' => WPAM_ACF_PREFIX_GROUP.'wpam_group_advanced_settings',
		'title' => __( 'Advanced settings', 'wp-advanced-menu' ),
		'fields' => array(
			array(
				'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_tab_general',
				'label' => __( 'General', 'wp-advanced-menu' ),
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_menu_options_theme_selector',
				'label' => __( 'Theme', 'wp-advanced-menu' ),
				'name' => 'wpam_menu_options_theme_selector',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array(),
				'default_value' => array(),
				'allow_null' => 1,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_menu_options_theme_overriding',
				'label' => __( 'Override', 'wp-advanced-menu' ),
				'name' => 'wpam_menu_options_theme_overriding',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => 'Disable theme override feature',
				'default_value' => 0,
				'ui' => 0,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'nav_menu',
					'operator' => '==',
					'value' => 'all',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));
	
}
