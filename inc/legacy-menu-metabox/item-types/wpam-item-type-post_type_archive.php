<?php

defined( 'ABSPATH' ) or	die();


/**
 * 
 * 
 * @link : https://wordpress.org/plugins/post-type-archive-links/
 * @link : https://stackoverflow.com/questions/20879401/how-to-add-custom-post-type-archive-to-menu
 * @link : https://kinsta.com/blog/wordpress-custom-menu/
 * 
 */


 /**
 * Add menu meta box
 *
 * @param object $object The meta box object
 * @link https://developer.wordpress.org/reference/functions/add_meta_box/
 */
function wpam_add_item_type_post_type_archive_metabox( $object ) {

	add_meta_box( 'wpam-item-type-post_type_archive-metabox', esc_html__( 'Post Type Archives', 'wp-advanced-menu' ), 'wpam_item_type_post_type_archive_metabox', 'nav-menus', 'side', 'low' );

	return $object;
}

add_filter( 'nav_menu_meta_box_object', 'wpam_add_item_type_post_type_archive_metabox', 10, 1 );


/**
 * Displays a metabox for authors menu item.
 *
 * @global int|string $nav_menu_selected_id (id, name or slug) of the currently-selected menu
 *
 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/nav-menu.php
 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/class-walker-nav-menu-edit.php
 * @link https://core.trac.wordpress.org/browser/tags/4.5/src/wp-admin/includes/class-walker-nav-menu-checklist.php
 */
function wpam_item_type_post_type_archive_metabox(){

	global $nav_menu_selected_id;

	$current_tab = 'all';

	$wpam_custom_item_prefix = '#wpam_post_type_archive_';

	$cpts = array();
	$has_archive_cps = get_post_types(
		array(
			'has_archive'	=> true,
			'_builtin' => false
		),
		'object'
	);
	foreach ( $has_archive_cps as $ptid => $pt ) {
		$to_show = $pt->show_in_nav_menus && $pt->publicly_queryable;
		if ( apply_filters( "show_{$ptid}_archive_in_nav_menus", $to_show, $pt ) ) {
			$cpts[] = $pt;
		}
	}

	$removed_args = array( 'action', 'customlink-tab', 'edit-menu-item', 'menu-item', 'page-tab', '_wpnonce' );

	// ——

	// Inform user no CPTs available to be shown.
	if ( empty( $cpts ) ) {
		echo '<p>' . esc_html__( 'No items.' ) . '</p>';
		return;
	}
	
	// run
	if ( ! empty( $cpts ) ) {

		?>
		<div id="wpam-item-type-post_type_archive" class="categorydiv">
			<ul id="wpam-item-type-post_type_archive-tabs" class="wpam-item-type-post_type_archive-tabs add-menu-item-tabs">
				<li <?php echo ( 'all' == $current_tab ? ' class="tabs"' : '' ); ?>>
					<a class="nav-tab-link" data-type="tabs-panel-wpam-item-type-post_type_archive-all" href="<?php if ( $nav_menu_selected_id ) echo esc_url( add_query_arg( 'wpam-item-type-post_type_archive-tab', 'all', remove_query_arg( $removed_args ) ) ); ?>#tabs-panel-wpam-item-type-post_type_archive-all">
						<?php echo esc_html__( 'View All' ); ?>
					</a>
				</li><!-- /.tabs -->

			</ul>
			
			<div id="tabs-panel-wpam-item-type-post_type_archive-all" class="tabs-panel tabs-panel-view-all <?php echo ( 'all' == $current_tab ? 'tabs-panel-active' : 'tabs-panel-inactive' ); ?>">
				<ul id="wpam-item-type-post_type_archive-checklist-all" class="categorychecklist form-no-clear">
				<?php

				global $_nav_menu_placeholder;

				foreach( $cpts as $item ){
					
					$url = get_post_type_archive_link(  $item->name ) ;

					// data in url
					$menu_item_data = array(
						'menu-item-title'  => esc_attr( $item->label)
					   ,'menu-item-type'   => 'post_type_archive'
					   ,'menu-item-object' => esc_attr( $item->name )
					   ,'menu-item-url'    => get_post_type_archive_link( $item->name )
					   );
					   
					   $url = $wpam_custom_item_prefix . http_build_query($menu_item_data)  ;

					?>
					<li>
						<label class="menu-item-title">
							<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-label]" value="0"> <?php echo esc_html( $item->label ); ?>
						</label>

						<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-type]" value="custom">
						<input type="hidden" class="menu-item-object" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-object]" value="<?php echo esc_attr( $item->name ); ?>">

						<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-title]" value="<?php echo esc_attr( $item->label ); ?>">
						<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-url]" value="<?php echo esc_url( $url ) ; ?>">

						<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $_nav_menu_placeholder ); ?>][menu-item-data]" value="<?php echo esc_url( $url ); ?>">

					</li>
					<?php

				} ?>
				</ul>
			</div><!-- /.tabs-panel -->

			<p class="button-controls wp-clearfix">

				<span class="add-to-menu">
					<input type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-wpam-item-type-post_type_archive-menu-item" id="submit-wpam-item-type-post_type_archive" />
					<span class="spinner"></span>
				</span>
			</p>

		</div><!-- /.categorydiv -->
		<?php
	}

}