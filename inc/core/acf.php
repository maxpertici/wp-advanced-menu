<?php

defined( 'ABSPATH' ) or	die();
// This file is include and so execute in wp-advanced-menu.php - in wpam_load_plugin_vendors_and_lib()



/**
 * Wrapper for loading ACF build-in plugin if not ACF (free and pro) already active
 *
 * @since 0.1.0
 */
function wpam_is_acf_loaded(){

    /**
     * Load ACF & configure it
     */
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    
    if (
        ( ! is_plugin_active( 'advanced-custom-fields/acf.php'     ) ) &&
        ( ! is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) )

    ){

        return false ;

    }

    return true ;

}



/**
 * 
 * ACF notice
 * 
 * @since 1.0
 */
function wpam_notice_acf_plugin_required(){
    //print the message
    $acf_search_url = 'plugin-install.php?s=advanced-custom-fields&tab=search&type=term';
    $acf_link = get_admin_url() . $acf_search_url ;

    echo '<div id="message" class="error notice is-dismissible">
    <p>'. __( 'Please install and activate', 'wp-advanced-menu-demos') . ' ' . '<a href="'.$acf_link.'">Advanced Custom Fields</a>'. ' ' . __('for using WP:AM plugin.' , 'wp-advanced-menu-demos').'</p>
    <button type="button" class="notice-dismiss"><span class="screen-reader-text">'.__('Ignore this message.','wp-advanced-menu').'</span></button>
    </div>';
    
    //make sure to remove notice after its displayed so its only displayed when needed.
    remove_action('admin_notices', 'wpam_notice_acf_plugin_required');

    // shutdown
    deactivate_plugins( 'wp-advanced-menu/wp-advanced-menu.php' );
}


/**
 * Load ACF fields
 *
 * @since 0.1.0
 */
function wpam_load_acf_field(){
    
    // load fields
    
    if( is_admin() ){
        // menu
        include_once( WPAM_CORE_PATH . 'acf-fields/acf-nav-menu-general-settings.php' );
        include_once( WPAM_CORE_PATH . 'acf-fields/acf-nav-menu-options.php' );
        
        // items
        include_once( WPAM_CORE_PATH . 'item-types.php' ) ;

        // 80
        include_once( WPAM_CORE_PATH . 'acf-fields/acf-nav-menu-nav-item-wpblock.php' );

        // 90
        include_once( WPAM_CORE_PATH . 'acf-fields/acf-nav-menu-nav-item-image.php' );

        // 90
        include_once( WPAM_CORE_PATH . 'acf-fields/acf-nav-menu-nav-item-content.php' );

        // 100
        include_once( WPAM_CORE_PATH . 'acf-fields/acf-nav-menu-nav-item-generic.php' );
        
    }

}



/**
 * Garbage collector connected to fiter with same name
 *
 * @since 0.1.0
 * 
 * @param array with field's name (without _underscore)
 * 
 * @return array with all fields given, all collected in transient
 */
function wpam_fields_garbage_collector( $wpam_fields_garbage ){

    $wpam_fields_garbage_in_transient = get_option( WPAM_GARBAGE_SLUG );
    $wpam_fields_garbage = array_merge( $wpam_fields_garbage_in_transient, $wpam_fields_garbage );
    update_option( WPAM_GARBAGE_SLUG, $wpam_fields_garbage );
    
    return $wpam_fields_garbage;
}



/**
 * 
 * 
 * 
 * 
 */
function wpam_get_ui_identifier_key( $theme_id, $handle, $name, $property ){
    
    $theme_id = str_replace( '-', '_', $theme_id );
    $handle   = str_replace( '-', '_', $handle );
    
    $name     = str_replace( ' ', '-', $name );
    $name     = str_replace( '-', '_', $name );

    $property = str_replace( '-', '_', $property );

    $theme_id = strtolower( $theme_id );
    $handle   = strtolower( $handle );
    $name     = strtolower( $name );
    $property = strtolower( $property );

    return WPAM_ACF_PREFIX_FIELD.'wpam_field_ui_' . $theme_id . '_' . $handle . '_' . $name . '_' . $property ;
}



/**
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_get_ui_identifier_name( $theme_id, $handle, $name, $property ){

    $theme_id = str_replace( '-', '_', $theme_id );
    $handle   = str_replace( '-', '_', $handle );

    $name     = str_replace( ' ', '-', $name );
    $name     = str_replace( '-', '_', $name );

    $property = str_replace( '-', '_', $property );

    $theme_id = strtolower( $theme_id );
    $handle   = strtolower( $handle );
    $name     = strtolower( $name );
    $property = strtolower( $property );

    return 'wpam_field_ui_' . $theme_id . '_' . $handle . '_' . $name . '_' . $property . '_name';
}



/**
 * 
 * 
 * @since 0.1.0
 * 
 */
function wpam_acf_build_mq_ui( $handle, $ui  ){

    // Build ACF Range \o/


    // vars
    $theme_id = $ui['theme_id'];
    $name     = $ui['name'];
    $ranges   = $ui['ranges'];


    if( function_exists('acf_add_local_field') ){

        // add now
        // acf_remove_local_field( WPAM_ACF_PREFIX_FIELD.'wpam_field_tab_mq' );
        acf_add_local_field( array(
            'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_tab_mq',
            'label' => __( 'Media queries', 'wp-advanced-menu' ),
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

        // Message
        // acf_remove_local_field( wpam_get_ui_identifier_key( $theme_id, $handle, $name, 'ui-message') );
        acf_add_local_field(array(
            'key'   => wpam_get_ui_identifier_key( $theme_id, $handle, $name, 'ui-message'),
            'label' => $name,
            'name'  => '',
            'type'  => 'message',
            'instructions'      => '',
            'required'          => 0,
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
            

            'wrapper'   => array(
                'width' => '',
                'class' => '',
                'id'    => '',
            ),
            'message'   => '',
            'new_lines' => 'wpautop',
            'esc_html'  => 0,
            
            'parent' => WPAM_ACF_PREFIX_GROUP.'wpam_group_advanced_settings'
        ));

        // Ranges
        foreach( $ranges as $range ){
            
            // collect
            if( isset( $range['property'] ) ){
                $property = $range['property'];
            }

            if( isset( $range['unit'] ) ){
                $unit = $range['unit'];
            }
            
            if( isset( $range['step'] ) ){
            $step = $range['step'];
            }else{ $step = ''; }

            if( isset( $range['values']['min'] ) ){
            $value_min = $range['values']['min'] ;
            }else{ $value_min = '' ; }

            if( isset( $range['values']['max'] ) ){
            $value_max = $range['values']['max'] ;
            }else{ $value_max = 6000 ; }

            if( isset( $range['values']['default'] ) ){
            $value_default = $range['values']['default'] ;
            }else{ $value_default = '' ; }

            // Garbage
            $wpam_fields_garbage = array(
                wpam_get_ui_identifier_name( $theme_id, $handle, $name, $property ) 
            );
        
            $garbage = apply_filters( 'wpam_fields_garbage_collector', $wpam_fields_garbage );
            
            // ACF
            // acf_remove_local_field( wpam_get_ui_identifier_key( $theme_id, $handle, $name, $property ) );
            acf_add_local_field(array(
                'key'   => wpam_get_ui_identifier_key( $theme_id, $handle, $name, $property ),
                'label' => '',
                'name'  => wpam_get_ui_identifier_name( $theme_id, $handle, $name, $property ),
                'type'  => 'range',
                'instructions'      => '',
                'required'          => 0,
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
                
                'wrapper'   => array(
                    'width' => '',
                    'class' => '',
                    'id'    => '',
                ),
                'default_value' => $value_default,
                'min'     => $value_min,
                'max'     => $value_max,
                'step'    => $step,
                'prepend' => $property,
                'append'  => $unit,
                
                'parent'  => WPAM_ACF_PREFIX_GROUP.'wpam_group_advanced_settings'
            ));
        }

    }
}