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

 // rebuild without href
 $attributes = '';
 foreach ( $atts as $attr => $value ) {
     if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
        if( 'href' !== $attr ){
            $value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
     }
 }

?>
<span <?php echo $attributes ;?>>
<?php echo esc_attr( $args->link_before ) . esc_attr( $title ) . esc_attr( $args->link_after ) ; ?>
</span>