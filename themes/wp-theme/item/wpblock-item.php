<?php
/**
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */

?>
<span class="wpam-item-element__content wpam-item-wpblock-<?php echo sanitize_title( $args->wpam->wp_item->post_title ); ?>">
<?php

    echo esc_html( $args->link_before );

    // wpam element
    $post_id  = $args->wpam->wp_item->ID;
    $block_id = get_field( 'wpam_nav_item_wpblock_selector', $post_id ) ;
    
    $content_post  = get_post( $block_id );
    $block_content = $content_post->post_content;
    echo apply_filters( 'the_content',  $block_content );

    echo esc_html( $args->link_after ) ;

?>
</span>