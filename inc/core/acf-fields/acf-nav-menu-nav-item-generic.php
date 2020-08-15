<?php

defined( 'ABSPATH' ) or	die();

if( function_exists('acf_add_local_field_group') ){
    
    // Field data
    // include( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-types-config.php' ) ;
    // include( WPAM_CORE_PATH . 'item-types.php' ) ;

    acf_add_local_field_group(array(
        
        'key'      => $wpam_custom_menu_item_spec['item']['acf_group']['key'],
        'title'    => $wpam_custom_menu_item_spec['item']['acf_group']['title'],
        'fields'   => $wpam_custom_menu_item_spec['item']['acf_fields'],

        'location' => array(
            array(
                array(
                    'param' => 'nav_menu_item',
                    'operator' => '==',
                    'value' => 'all',
                ),
            ),
        ),
        'menu_order' => 100,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));



}