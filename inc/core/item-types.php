<?php

defined( 'ABSPATH' ) or	die();


/**
 * 
 * 
 * 
 * 
 * 
 * 
 */


// garbage for field
$wpam_nav_item_fields_garbage = array(

    // block

    // image
    'wpam_nav_item_image_selector',

    // item (generic)
    'wpam_nav_item_generic_submenu_type',
    'wpam_nav_item_generic_submenu_colmuns_number',

);
apply_filters( 'wpam_fields_garbage_collector', $wpam_nav_item_fields_garbage );


// Custom nav item specifications

$wpam_prefix = '#wpam' . '_';
$wpam_custom_menu_item_spec = array(

 

    /**
     *  Image type
     */
    'image' => array(
        'label'      => __( 'Image' , 'wp-advanced-menu'),
        
        'slug' => 'image',
        'prefix'     => $wpam_prefix . 'image',

        'acf_group'  => array(
            'key' => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_image',
            'title' => __( 'Image settings group', 'wp-advanced-menu' ),
        ),
        
        'acf_fields' => array(
            
            array(
                'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_nav_item_image_selector',
                'label' => __( 'Image settings', 'wp-advanced-menu' ),
                'name' => 'wpam_nav_item_image_selector',

                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-image-settings-field__selector',
                    'id' => '',
                ),

                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'library' => 'all',

                'min_width' => '',
                'min_height' => '',
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            
        ),



    ),



    /**
     *  Element
     */
    
    'element' => array(
        
        'label'  => __( 'Element' , 'wp-advanced-menu'),
        
        'slug' => 'element',
        'prefix' => $wpam_prefix . 'element',
        
        'acf_group'  => array(
            'key' => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_element',
            'title' => __( 'Element settings group', 'wp-advanced-menu' ),
        ),

        'acf_fields' => array(
            
            array(

                'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_nav_item_element_selector',
                'label' => __( 'Elements', 'wp-advanced-menu' ),
                'name' => 'wpam_nav_item_element_selector',

                'type' => 'post_object',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-element-settings-field__selector',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'wpam-element',
                ),
                'taxonomy' => '',
                'allow_null' => 0,
                'multiple' => 0,
                'return_format' => 'object',
                'ui' => 1,
            ),
        ),




    ),



    /**
     *  WP Block
     */
    
    'wpblock' => array(
        'label'      => __( 'Block' , 'wp-advanced-menu'),
        
        'slug' => 'wpblock',
        'prefix'     => $wpam_prefix . 'wpblock',

        'acf_group'  => array(
            'key' => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_wpblock',
            'title' => __( 'Block settings group', 'wp-advanced-menu' ),
        ),
        
        'acf_fields' => array(
            
            array(
                'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_nav_item_wpblock_selector',
                'label' => __( 'Reusable Blocks', 'wp-advanced-menu' ),
                'name' => 'wpam_nav_item_wpblock_selector',
                'type' => 'select',
                
                'instructions' => 
                    sprintf(
                        __( 'Choose your <a href="edit.php?post_type=wp_block">%s</a> or <a href="post-new.php?post_type=wp_block">%s</a> if empty.', 'wp-advanced-menu' ) ,
                        __( 'reusable block', 'wp-advanced-menu' ),
                        __( 'create one', 'wp-advanced-menu' )
                    ),

                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-wpblock-settings-field__selector',
                    'id' => '',
                ),

                'choices' => array(),
                'default_value' => array(),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'array',
                'ajax' => 0,
                'placeholder' => '',
            ),
        ),



    ),
    
    

    /**
     *  Post type archive
     */
    
    'post_type_archive' => array(
        'label'  => __( 'Post Type Archive' , 'wp-advanced-menu'),
        'slug' => 'post_type_archive',

        'prefix' => $wpam_prefix . 'post_type_archive',

        'acf_group'  => '',
        'acf_fields' => array(),

    ),




    /**
     *  Menu
     */
    
    'menu' => array(
        'label'  => __( 'Menu' , 'wp-advanced-menu'),
        'slug' => 'menu',

        'prefix' => $wpam_prefix . 'menu',

        'acf_group'  => '',
        'acf_fields' => array(),

    ),



    /**
     *  Generic item type (all item)
     */
    'item' => array(
        'label'  => __( 'Item' , 'wp-advanced-menu'),
        'slug' => 'item',

        'prefix' => $wpam_prefix . 'item',

        'acf_group'  => array(
            'key' => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_generic',
            'title' => __( 'Item settings group', 'wp-advanced-menu' ),
        ),

        'acf_fields' => array(
            
            array(
                'key' => WPAM_ACF_PREFIX_FIELD . 'wpam_field_wpam_nav_item_generic_submenu_type',
                'label' => __( 'Submenu type' , 'wp-advanced-menu'),
                'name' => 'wpam_nav_item_generic_submenu_type',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-submenu-type-settings-field__type',
                    'id' => '',
                ),
                'choices' => array(
                    'default' => __( 'Default' , 'wp-advanced-menu'),
                    'column' => __( 'Columns' , 'wp-advanced-menu'),
                ),
                'default_value' => array(
                    0 => 'default',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => WPAM_ACF_PREFIX_FIELD . 'wpam_field_wpam_nav_item_generic_submenu_colmuns_number',
                'label' => __( 'Number of columns' , 'wp-advanced-menu') ,
                'name' => 'wpam_nav_item_generic_submenu_colmuns_number',
                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => WPAM_ACF_PREFIX_FIELD . 'wpam_field_wpam_nav_item_generic_submenu_type',
                            'operator' => '==',
                            'value' => 'column',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-submenu-type-settings-field__colmuns-number',
                    'id' => '',
                ),
                'choices' => array(
                    'auto' => 'Auto',
                    2 =>  __( 'Two'   , 'wp-advanced-menu') ,
                    3 =>  __( 'Three' , 'wp-advanced-menu') ,
                    4 =>  __( 'Four'  , 'wp-advanced-menu') ,
                    5 =>  __( 'Five'  , 'wp-advanced-menu') ,
                    6 =>  __( 'Six'   , 'wp-advanced-menu') ,
                    7 =>  __( 'Seven' , 'wp-advanced-menu') ,
                    8 =>  __( 'Eight' , 'wp-advanced-menu') ,
                    9 =>  __( 'Nine'  , 'wp-advanced-menu') ,
                    10 => __( 'Ten'   , 'wp-advanced-menu') ,
                ),
                'default_value' => array(
                    0 => 'auto',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            
        ),
        
    ),

    /**
     * 
     * 
     */
);


/**
 * 
 * 
 * 
 * 
 * 
 */

$wpam_nav_item_fields_keys = array();
 
foreach( $wpam_custom_menu_item_spec as $item_type => $spec ){
  
    $acf_fields = $spec['acf_fields'] ;
    if(  is_array( $acf_fields ) && count( $acf_fields ) > 0 ){

        foreach(  $acf_fields as $acf_field ){

            array_push(
                $wpam_nav_item_fields_keys,
                array(
                    'type' => $item_type ,
                    'key' => $acf_field['key']
                )
            );

        }

    }
}

