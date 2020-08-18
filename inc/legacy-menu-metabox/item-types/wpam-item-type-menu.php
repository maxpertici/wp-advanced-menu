<?php

defined( 'ABSPATH' ) or	die();



 /**
 * Add menu meta box
 *
 * @param object $object The meta box object
 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function wpam_add_item_type_menu_metabox( $object ) {

	add_meta_box( 'wpam-item-type-menu-metabox', esc_html__( 'Menus', 'wp-advanced-menu' ), 'wpam_item_type_menu_metabox', 'nav-menus', 'side', 'low' );

	return $object;
}

add_filter( 'nav_menu_meta_box_object', 'wpam_add_item_type_menu_metabox', 10, 1 );


/**
 * Displays a metabox for authors menu item.
 *
 * @global int|string $nav_menu_selected_id (id, name or slug) of the currently-selected menu
 *
 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/nav-menu.php
 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/class-walker-nav-menu-edit.php
 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/class-walker-nav-menu-checklist.php
 */
function wpam_item_type_menu_metabox(){

    global $nav_menu_selected_id;
    
	$current_tab = 'all';

    $wp_nav_menus = wp_get_nav_menus( );
    /*
    var_dump($wp_nav_menus);

    array(1) {
        [0]=> object(WP_Term)#1451 (10) {
            ["term_id"]=> int(2)
            ["name"]=> string(9) "main menu"
            ["slug"]=> string(9) "main-menu"
            ["term_group"]=> int(0)
            ["term_taxonomy_id"]=> int(2)
            ["taxonomy"]=> string(8) "nav_menu"
            ["description"]=> string(0) ""
            ["parent"]=> int(0)
            ["count"]=> int(5)
            ["filter"]=> string(3) "raw" }
        } 
    */

	$wpam_menu_item_prefix = '#wpam_menu_';

	$menus = $wp_nav_menus ;

    $removed_args = array( 'action', 'customlink-tab', 'edit-menu-item', 'menu-item', 'page-tab', '_wpnonce' );

    // Inform user no CPTs available to be shown.
	if ( empty( $menus ) ) {
		echo '<p>' . esc_html__( 'No items.' ) . '</p>';
		return;
	}
	
	// run
	if ( ! empty( $menus ) ) {

	?>
	<div id="wpam-item-type-menu" class="categorydiv">
		<ul id="wpam-item-type-menu-tabs" class="wpam-item-type-menu-tabs add-menu-item-tabs">
			<li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>>
				<a class="nav-tab-link" data-type="tabs-panel-wpam-item-type-menu-all" href="<?php if ( $nav_menu_selected_id ) echo esc_url( add_query_arg( 'wpam-item-type-menu-tab', 'all', remove_query_arg( $removed_args ) ) ); ?>#tabs-panel-wpam-item-type-menu-all">
					<?php echo esc_html__( 'View All' ); ?>
				</a>
			</li><!-- /.tabs -->

		</ul>
		
		<div id="tabs-panel-wpam-item-type-menu-all" class="tabs-panel tabs-panel-view-all <?php echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ); ?>">
			<ul id="wpam-item-type-menu-checklist-all" class="categorychecklist form-no-clear">
			<?php
            global $_nav_menu_placeholder;

            foreach( $menus as $item ){
                
                $url = 'https://useless.url' ;

                // data in url
                $menu_item_data = array(
                    'menu-item-title'  => esc_attr( $item->name )
                   ,'menu-item-type'   => 'menu'
                   ,'menu-item-object' => esc_attr( $item->label )
                   ,'menu-item-url'    => 'https://useless.url'
                   );
                   
                   $url = $wpam_menu_item_prefix . http_build_query($menu_item_data)  ;

                ?>
                <li>
                    <label class="menu-item-title">
                        <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-label]" value="0"> <?php echo esc_html__( $item->name ); ?>
                    </label>

                    <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-type]" value="custom">
                    <input type="hidden" class="menu-item-object" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-object]" value="<?php echo esc_attr( $item->name ); ?>">

                    <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-title]" value="<?php echo esc_attr( $item->name ); ?>">
                    <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-url]" value="<?php echo esc_url( $url ) ; ?>">

                    <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-data]" value="<?php echo esc_url( $url ) ; ?>">

                </li>
                <?php

            } 

            ?>
			</ul>
		</div><!-- /.tabs-panel -->

		<p class="button-controls wp-clearfix">

			<span class="add-to-menu">
				<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-wpam-item-type-custom-menu-item" id="submit-wpam-item-type-menu" />
				<span class="spinner"></span>
			</span>
		</p>

	</div><!-- /.categorydiv -->
	<?php

    }

}