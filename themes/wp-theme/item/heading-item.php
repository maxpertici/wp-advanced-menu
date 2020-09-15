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

$wpam_heading_level = get_field( 'wpam_nav_item_heading_selector', $args->wpam->wp_item->ID );

?>
<<?php echo esc_attr( $wpam_heading_level ); ?> class="wpam-item-heading__content wpam-item-heading-<?php echo esc_attr( sanitize_title( $args->wpam->wp_item->post_title ) ); ?>">
<?php echo esc_attr( $args->link_before ) . esc_attr( $title ) . esc_attr( $args->link_after ) ; ?>
</<?php echo esc_html( $wpam_heading_level ); ?>>