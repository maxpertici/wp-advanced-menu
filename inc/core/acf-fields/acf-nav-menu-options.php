<?php

defined( 'ABSPATH' ) or	die();

/**
 * 
 * 
 * 
 * 
 */
$wpam_nav_menu_options_tab = false ;

/**
 * 
 * 
 * 
 * 
 * 
 */
function wpam_add_theme_options_fields( $theme_id, $fields = array() ){
    
    /*
    var_dump('wpam_add_theme_options_fields');
    die();
    */
    
    if( empty( $fields ) ){
        return ;
    }
    
    // enable option tab in nav-menu screen
    global $wpam_nav_menu_options_tab;

    if( ! $wpam_nav_menu_options_tab ){

        if( function_exists('acf_add_local_field') ){

            // Tab
            // acf_remove_local_field( WPAM_ACF_PREFIX_FIELD.'wpam_field_tab_options' );
            acf_add_local_field( array(
                'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_tab_options',
                'label' => __( 'Options', 'wp-advanced-menu' ),
                'name' => '',
                'type' => 'tab',
                'instructions' => '',
                'required' => 0,

                // 'conditional_logic' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_menu_options_theme_selector',
                            'operator' => '==',
                            'value' => $theme_id,
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'placement' => 'top',
                'endpoint' => 0,
        
                'parent' => WPAM_ACF_PREFIX_GROUP.'wpam_group_advanced_settings'
            )
            );

            global $wpam_nav_menu_options_tab;
            $wpam_nav_menu_options_tab = true ;
        }
    }

    // fetch array of acf field 
    foreach( $fields as $field ){

        if( function_exists('acf_add_local_field') ){
            // override parent parameter
            $field['parent'] = WPAM_ACF_PREFIX_GROUP.'wpam_group_advanced_settings' ;
            
            // acf_remove_local_field( $field['key'] );
            acf_add_local_field( $field );
        }
        
    }
    
 }