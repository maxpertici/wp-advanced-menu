<?php

defined( 'ABSPATH' ) or	die();

/**
 * 
 * 
 * @since 1.0
 * 
 */
function wpam_create_content_post_type() {
 
    $labels = array(
        'name'                  => _x( 'Contents', 'Post type general name', 'wp-advanced-menu' ),
        'singular_name'         => _x( 'Content', 'Post type singular name', 'wp-advanced-menu' ),
        'menu_name'             => _x( 'WP:AM', 'Admin Menu text', 'wp-advanced-menu' ),
        'name_admin_bar'        => _x( 'Content', 'Add New on Toolbar', 'wp-advanced-menu' ),
        'add_new'               => __( 'Add New', 'wp-advanced-menu' ),
        'add_new_item'          => __( 'Add New content', 'wp-advanced-menu' ),
        'new_item'              => __( 'New content', 'wp-advanced-menu' ),
        'edit_item'             => __( 'Edit Content', 'wp-advanced-menu' ),
        'view_item'             => __( 'View Content', 'wp-advanced-menu' ),
        'all_items'             => __( 'All Contents', 'wp-advanced-menu' ),
        'search_items'          => __( 'Search Contents', 'wp-advanced-menu' ),
        'parent_item_colon'     => __( 'Parent Contents:', 'wp-advanced-menu' ),
        'not_found'             => __( 'No contents found.', 'wp-advanced-menu' ),
        'not_found_in_trash'    => __( 'No contents found in Trash.', 'wp-advanced-menu' ),
        'featured_image'        => _x( 'Content Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'wp-advanced-menu' ),
        'archives'              => _x( 'Content archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'wp-advanced-menu' ),
        'insert_into_item'      => _x( 'Insert into content', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'wp-advanced-menu' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this content', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'wp-advanced-menu' ),
        'filter_items_list'     => _x( 'Filter contents list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'wp-advanced-menu' ),
        'items_list_navigation' => _x( 'Contents list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'wp-advanced-menu' ),
        'items_list'            => _x( 'Contents list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'wp-advanced-menu' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'query_var'          => true,
        'show_in_rest'       => true,
        'show_in_menu'       => false,
        'rewrite'            => array( 'slug' => 'wpam-content' ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor' ),
    );
 
    register_post_type( 'wpam-content', $args );
}

add_action( 'init', 'wpam_create_content_post_type' );

