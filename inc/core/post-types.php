<?php

defined( 'ABSPATH' ) or	die();

/**
 * 
 * 
 * @since 1.0
 * 
 */
function wpam_create_element_post_type() {
 
    $labels = array(
        'name'                  => _x( 'Nav. elements', 'Post type general name', 'wp-advanced-menu' ),
        'singular_name'         => _x( 'Nav. element', 'Post type singular name', 'wp-advanced-menu' ),
        'menu_name'             => _x( 'WP:AM', 'Admin Menu text', 'wp-advanced-menu' ),
        'name_admin_bar'        => _x( 'Nav. element', 'Add New on Toolbar', 'wp-advanced-menu' ),
        'add_new'               => __( 'Add New', 'wp-advanced-menu' ),
        'add_new_item'          => __( 'Add New element', 'wp-advanced-menu' ),
        'new_item'              => __( 'New element', 'wp-advanced-menu' ),
        'edit_item'             => __( 'Edit Element', 'wp-advanced-menu' ),
        'view_item'             => __( 'View Element', 'wp-advanced-menu' ),
        'all_items'             => __( 'All Elements', 'wp-advanced-menu' ),
        'search_items'          => __( 'Search Elements', 'wp-advanced-menu' ),
        'parent_item_colon'     => __( 'Parent Elements:', 'wp-advanced-menu' ),
        'not_found'             => __( 'No elements found.', 'wp-advanced-menu' ),
        'not_found_in_trash'    => __( 'No elements found in Trash.', 'wp-advanced-menu' ),
        'featured_image'        => _x( 'Element Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'archives'              => _x( 'Element archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wp-advanced-menu' ),
        'insert_into_item'      => _x( 'Insert into element', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wp-advanced-menu' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this element', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wp-advanced-menu' ),
        'filter_items_list'     => _x( 'Filter elements list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wp-advanced-menu' ),
        'items_list_navigation' => _x( 'Elements list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wp-advanced-menu' ),
        'items_list'            => _x( 'Elements list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wp-advanced-menu' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'query_var'          => true,
        'show_in_rest'       => true,
        'show_in_menu'       => false,
        'rewrite'            => array( 'slug' => 'wpam-element' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor' ),
    );
 
    register_post_type( 'wpam-element', $args );
}

add_action( 'init', 'wpam_create_element_post_type' );

