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
<span class="wpam-item-content__content wpam-item-content-<?php echo esc_attr( sanitize_title( $args->wpam->wp_item->post_title ) ); ?>">
<?php

    echo esc_html( $args->link_before );

    // wpam element
    $post_id  = $args->wpam->wp_item->ID;
    $block_id = get_field( 'wpam_nav_item_content_selector', $post_id ) ;
    
    $content_post  = get_post( $block_id );
    $block_content = $content_post->post_content;
    echo apply_filters( 'the_content',  $block_content );

    echo esc_html( $args->link_after );

?>
</span>