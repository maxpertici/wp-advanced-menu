<?php

defined( 'ABSPATH' ) or	die();

if( function_exists('acf_add_local_field_group') ){

    acf_add_local_field_group(array(

        
        'key'      => $wpam_custom_menu_item_spec['menu']['acf_group']['key'],
        'title'    => $wpam_custom_menu_item_spec['menu']['acf_group']['title'],
        'fields'   => $wpam_custom_menu_item_spec['menu']['acf_fields'],

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
    
}