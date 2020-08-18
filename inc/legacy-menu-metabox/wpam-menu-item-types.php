<?php

// 
defined( 'ABSPATH' ) or	die();

/**
 * 
 * 
 * Custom Item Metabox
 * 
 */


require_once( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-type-menu.php' );
require_once( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-type-custom.php' );
require_once( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-type-post_type_archive.php' );



/**
 * 
 * 
 * handler for add menuitem in nva-menu admin screen
 * check with custom and flag and param added url of item
 */

add_filter( 'wp_setup_nav_menu_item' , 'wpam_setup_nav_menu_item' );

function wpam_setup_nav_menu_item( $menu_item ){
	
    if( $menu_item->type == 'custom' ){

        // include( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-types-config.php' ) ;
        include( WPAM_CORE_PATH . 'item-types.php' ) ;

        //Check flag FIRST, only deal with URL if flag hasn't been set
		$custom_item_type = '';
        $custom_item_data = '';
        
        $wpam_data = $menu_item->url ;
        $url = '';
        
        $is_wpam_item = false ;

        // Find WP:AM item type with prefix
        $wpam_item_types_prefix = array();

        foreach( $wpam_custom_menu_item_spec as $item_type_spec ){
            
            array_push( $wpam_item_types_prefix, $item_type_spec['prefix'] );

        }

        foreach( $wpam_item_types_prefix as $wpam_type_prefix ){
            
            if( strpos( $wpam_data , $wpam_type_prefix ) === 0 ){

                $wpam_item_type = substr( $wpam_type_prefix, strlen( $wpam_prefix ) ) ;

                $wpam_data = substr( $wpam_data , strlen( $wpam_type_prefix ) );
                $parts = parse_url( $wpam_data );
                parse_str( $parts['path'], $results );
                $wpam_item_key = $results ;
                
                $wpam_item_keys = array(
                    'item_type' => $wpam_item_type,
                    'item_data' => $wpam_item_key
                );
                $is_wpam_item = true ;
            }

        }
        
        if( $is_wpam_item ){

            
            // When item is added to menu, set flag
            if( isset( $menu_item->post_status ) && $menu_item->post_status == 'draft' ){
        
                update_post_meta( $menu_item->ID , '_wpam_custom_item_type' , $wpam_item_keys['item_type'] );
                update_post_meta( $menu_item->ID , '_wpam_custom_item_data' , $wpam_item_keys['item_data'] );

                $custom_item_type = get_post_meta( $menu_item->ID , '_wpam_custom_item_type' , true );
                $custom_item_data = get_post_meta( $menu_item->ID , '_wpam_custom_item_data' , true );
            
            }

            //Not new, check meta
            else{

                $custom_item_type = get_post_meta( $menu_item->ID , '_wpam_custom_item_type' , true );
                $custom_item_data = get_post_meta( $menu_item->ID , '_wpam_custom_item_data' , true );
                
            }

        }

        if( $is_wpam_item ){
            
            // Label
            $label =  esc_html__( 'WP:AM' , 'wp-advanced-menu') . ' — ' . $wpam_custom_menu_item_spec[ $custom_item_type ][ 'label' ];

            if( $custom_item_type === 'post_type_archive' ){
                $menu_item->object  = $custom_item_data['menu-item-object'];
                $menu_item->type    = 'post_type_archive';

                $label =  $wpam_custom_menu_item_spec[ $custom_item_type ][ 'label' ];
            }

            $menu_item->type_label = $label;
            $menu_item->url = $custom_item_data['menu-item-url'] ;
        }


	}

	return $menu_item;
}