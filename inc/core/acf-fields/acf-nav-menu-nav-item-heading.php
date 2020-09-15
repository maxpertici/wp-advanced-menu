<?php

defined( 'ABSPATH' ) or	die();

if( function_exists('acf_add_local_field_group') ){
    
    // Field data
    // include( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-types-config.php' ) ;
    // include( WPAM_CORE_PATH . 'item-types.php' ) ;

    acf_add_local_field_group(array(
        
        'key'      => $wpam_custom_menu_item_spec['heading']['acf_group']['key'],
        'title'    => $wpam_custom_menu_item_spec['heading']['acf_group']['title'],
        'fields'   => $wpam_custom_menu_item_spec['heading']['acf_fields'],

        'location' => array(
            array(
                array(
                    'param' => 'nav_menu_item',
                    'operator' => '==',
                    'value' => 'all',
                ),
            ),
        ),
        'menu_order' => 90,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));



    /* Filters */
    /*
    add_filter( 'acf/load_field_group', 'wpam_nav_item_field_group_loader' );

    function wpam_nav_item_field_group_loader( $field_group ){

        var_dump($field_group);
        
        return $field_group ;
    }
    */

    /*
    add_filter('acf/prepare_field/key='.WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_menu_nav_item_image_selector', 'wpam_nav_item_image_prepare_field' );
    
    function wpam_nav_item_image_prepare_field( $field ) {
        return $field;
    }
    */
    
}