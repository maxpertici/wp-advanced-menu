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

    // heading
    'wpam_nav_item_heading_selector',

    // paragraph
    'wpam_nav_item_paragraph_text',

    // image
    'wpam_nav_item_image_media',
    'wpam_nav_item_image_size',

    // content
    'wpam_nav_item_content_selector',

    // block
    'wpam_nav_item_wpblock_selector',

    // item (generic)
    'wpam_nav_item_generic_submenu_type',
    'wpam_nav_item_generic_submenu_colmuns_number',

);

apply_filters( 'wpam_fields_garbage_collector', $wpam_nav_item_fields_garbage );


// Custom nav item specifications

$wpam_prefix = '#wpam' . '_';
$wpam_custom_menu_item_spec = array(


    
    /**
     *  Heading
     */
    
    'heading' => array(
        'label' => __( 'Heading' , 'wp-advanced-menu'),

        'slug'  => 'heading',
        'prefix' => $wpam_prefix . 'heading',

        'acf_group' => array(
            'key'   => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_heading',
            'title' => __( 'Heading settings group', 'wp-advanced-menu' ),
        ),

        'acf_fields' => array(
            
            array(
                'key'   => WPAM_ACF_PREFIX_FIELD . 'wpam_field_wpam_nav_item_heading_selector',
                'label' => __( 'Heading level' , 'wp-advanced-menu'),
                'name'  => 'wpam_nav_item_heading_selector',
                'type'  => 'select',

                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-heading-settings-field__selector',
                    'id' => '',
                ),

                'choices' => array(
                    'h2' => __( 'Heading 2', 'wp-advanced-menu' ),
                    'h3' => __( 'Heading 3', 'wp-advanced-menu' ),
                    'h4' => __( 'Heading 4', 'wp-advanced-menu' ),
                    'h5' => __( 'Heading 5', 'wp-advanced-menu' ),
                    'h6' => __( 'Heading 6', 'wp-advanced-menu' ),
                ),

                'default_value' => false,
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
     *  Paragraph
     */
    
    'paragraph' => array(
        'label'  => __( 'Paragraph' , 'wp-advanced-menu'),
        'slug' => 'paragraph',

        'prefix' => $wpam_prefix . 'paragraph',

        'acf_group' => array(
            'key'   => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_paragraph',
            'title' => __( 'Paragraph settings group', 'wp-advanced-menu' ),
        ),

        'acf_fields' => array(

            array(
                'key'   => WPAM_ACF_PREFIX_FIELD . 'wpam_field_wpam_nav_item_paragraph_text',
                'label' => __( 'Text' , 'wp-advanced-menu'),
                'name'  => 'wpam_nav_item_paragraph_text',
                'type'  => 'textarea',

                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-paragraph-settings-field__text',
                    'id' => '',
                ),

                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '',
                'rows' => '',
                'new_lines' => 'br',
            ),

        ),

    ),


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
                'label' => __( 'Image', 'wp-advanced-menu' ),
                'name' => 'wpam_nav_item_image_media',

                'type' => 'image',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '50',
                    'class' => 'wpam-image-settings-field__media',
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

            array(
                'key'   => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_nav_item_image_size',
                'label' => __( 'Size', 'wp-advanced-menu' ),
                'name'  => 'wpam_nav_item_image_size',

                'type' => 'select',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '50',
                    'class' => 'wpam-image-settings-field__size',
                    'id'    => '',
                ),

                'choices' => array(),

                'default_value' => false,
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
     *  Content
     */
    
    'content' => array(
        
        'label'  => __( 'Content' , 'wp-advanced-menu'),
        
        'slug' => 'content',
        'prefix' => $wpam_prefix . 'content',
        
        'acf_group'  => array(
            'key' => WPAM_ACF_PREFIX_GROUP.'wpam_group_nav_item_content',
            'title' => __( 'Content settings group', 'wp-advanced-menu' ),
        ),

        'acf_fields' => array(
            
            array(

                'key' => WPAM_ACF_PREFIX_FIELD.'wpam_field_wpam_nav_item_content_selector',
                'label' => __( 'Contents', 'wp-advanced-menu' ),
                'name' => 'wpam_nav_item_content_selector',

                'type' => 'post_object',
                
                'instructions' => 
                    sprintf(
                        __( 'Choose your <a href="edit.php?post_type=wpam-content">%s</a> or <a href="post-new.php?post_type=wpam-content">%s</a> if empty.', 'wp-advanced-menu' ) ,
                        __( 'content', 'wp-advanced-menu' ),
                        __( 'create one', 'wp-advanced-menu' )
                    ),

                'required' => 0,
                'conditional_logic' => 0,

                'wrapper' => array(
                    'width' => '',
                    'class' => 'wpam-content-settings-field__selector',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'wpam-content',
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
     *  No link
     */
    
    'nolink' => array(
        'label'  => __( 'No link' , 'wp-advanced-menu'),
        'slug' => 'nolink',

        'prefix' => $wpam_prefix . 'nolink',

        'acf_group'  => '',
        'acf_fields' => array(),

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

