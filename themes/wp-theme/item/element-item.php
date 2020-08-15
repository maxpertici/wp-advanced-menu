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
<a <?php echo $attributes ;?>>
<?php

    echo $args->link_before ;

    // wpam element
    $post_id = $args->wpam->wp_item->ID;
    $block_id = get_field( 'wpam_nav_item_element_selector', $post_id ) ;
    
    $content_post = get_post( $block_id );
    $block_content = $content_post->post_content;
    echo apply_filters( 'the_content',  $block_content );

    echo  $args->link_after ;

?>
</a>