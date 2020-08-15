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
$wpam_image_id = get_field( 'wpam_nav_item_image_selector', $args->wpam->wp_item->ID );
echo wp_get_attachment_image( $wpam_image_id );
?>
</a>