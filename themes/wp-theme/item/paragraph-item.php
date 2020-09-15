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
<p class="wpam-item-paragraph__content wpam-item-paragraph-<?php echo esc_attr( sanitize_title( $args->wpam->wp_item->post_title ) ); ?>">
<?php
$wpam_paragraph_text = get_field( 'wpam_nav_item_paragraph_text', $args->wpam->wp_item->ID );
echo $wpam_paragraph_text ;
?>
</p>