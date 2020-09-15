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
<span class="wpam-item-image__content wpam-item-image-<?php echo esc_attr( sanitize_title( $args->wpam->wp_item->post_title ) ); ?>">
<?php

$wpam_image_id = get_field( 'wpam_nav_item_image_media', $args->wpam->wp_item->ID );
$image_size    = get_field( 'wpam_nav_item_image_size',  $args->wpam->wp_item->ID );

echo wp_get_attachment_image( $wpam_image_id, $image_size );

?>
</span>