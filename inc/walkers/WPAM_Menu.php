<?php

defined( 'ABSPATH' ) or	die();

class WPAM_Menu extends \Walker_Nav_Menu {

	private $wpam ;
	
	private $core_dir ;
	private $template_dir ;
	private $origin_dir ;
	private $overrides_dirs ;
	private $theme_dirs ;

	private $item_deph ;

	private $current_item ;



	/**
	 * 
	 * 
	 * 
	 */
	function __construct( $wpam_theme_ref ){
		
		$this->wpam = $wpam_theme_ref ;
		
		if( ! isset( $this->wpam->theme_template ) ){
		$this->wpam->theme_template = $this->wpam->theme_slug ;
		}
		
		$dir = wpam_get_theme_directories(
			$this->wpam->theme_slug,
			$this->wpam->theme_core,
			$this->wpam->theme_template,
			$this->wpam->theme_override,
			$this->wpam->theme_origin
		);

		if( isset( $dir['overrides'] ) ){
		$this->overrides_dirs = $dir['overrides'];
		}
		
		$this->core_dir = $dir['core'];

		if( isset( $dir['template'] ) ){
		$this->template_dir = $dir['template'];
		}

		if( isset( $dir['origin'] ) ){
		$this->origin_dir = $dir['origin'];
		}

		$this->theme_dirs = array(
			'origin'    => $this->origin_dir,
			'template'  => $this->template_dir,
			'core'      => $this->core_dir,
			'overrides' => $this->overrides_dirs,
		);

		$this->current_item = false ;

	}



	/**
	 * 
	 * 
	 * 
	 */
	public function get_item_field( $key, $item ){

		$value = null ;

		$field_value = get_field( $key, $item ) ;

		// var_dump($field_value);

		if( $field_value != null ){
			$value = $field_value ;
		}

		return $value ;
		
	}




	/**
	 * 
	 * 
	 * 
	 */
	function get_item_type( $item ){

		$item_type = false ;



		return $item_type ;
	}

	

	/**
	 * 
	 * 
	 * 
	 * 
	 */
	function get_item_submenu_settings( $item ){
		
		$submenu_settings = new stdClass;
		$submenu_type = get_field(  'wpam_nav_item_generic_submenu_type', $item->ID ) ;
		$submenu_type = sanitize_text_field( $submenu_type );

		$submenu_settings->type = $submenu_type ;
		$submenu_settings->class = ' ' ;

		if( $submenu_settings->type === 'column' ){

			
			$submenu_column_number = get_field(  'wpam_nav_item_generic_submenu_colmuns_number', $item->ID ) ;
			$submenu_column_number = sanitize_text_field( $submenu_column_number );

			$submenu_settings->colmuns_number = $submenu_column_number ;

			$submenu_settings->class .= 'wpam-submenu-columns' . ' ';
			$submenu_settings->class .= 'wpam-submenu-' .  $submenu_settings->colmuns_number ;
			
		}

		return  $submenu_settings ;
	}



	/**
	 * 
	 * 
	 * 
	 * 
	 */
	public function get_theme_option( $key, $menu ){

		$value = null;

		// $args->wpam->wp_menu
		if( is_object($menu) ){
			
			if(
				property_exists( $menu, "term_id") &&
				property_exists( $menu, "name") &&
				property_exists( $menu, "slug") &&
				is_nav_menu(  $menu->slug )
				){

				// check value in db
				$value = get_field( $key, $menu );

			}

		}
		
		if( $value === null ){
			$value  = $this->wpam->options_default[ 'field_key_' . $key] ;
		}
		
		return $value ;

	}



	/**
	 * 
	 * 
	 * 
	 * 
	 */
	public function get_item_deph(){
		return $this->item_deph;
	}



	/**
	 * Starts the list before the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::start_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Default class.
		$classes = array( 'sub-menu' );

		/**
		 * Filters the CSS class(es) applied to a menu list element.
		 *
		 * @since 4.8.0
		 *
		 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
		 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
		// $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= "{$n}{$indent}";

		$this->item_deph = $depth;

		
		if( is_object( $args ) != true ){
			$args = (object) $args;
		}
		
		/*
		// WPAM : pass item values
		// $args->wpam_item = $item ;

		// WPAM : pass menu values
		$args->wpam_menu = $args->menu ;
		*/
		$args->wpam = new stdClass() ;
		$args->wpam->wpam_item = new stdClass() ;
		

		if( $this->current_item ){ $item = $this->current_item ; }
		$args->wpam->wp_item = $item ;
		$args->wpam->wp_menu = $args->menu ;
		
		$args->wpam->wp_class_names = $class_names ;

		$get_post_meta_item_type = get_post_meta( $args->wpam->wp_item->ID , '_wpam_custom_item_type' , true );
		$get_post_meta_item_type = sanitize_text_field( $get_post_meta_item_type );

		$args->wpam->wpam_item->type = $get_post_meta_item_type;

		$args->wpam->wpam_item->submenu = $this->get_item_submenu_settings( $args->wpam->wp_item ) ;

		
		$theme_paths = wpam_get_template_path( $args, $this->theme_dirs, 'ul-start' );
		$file_path   = wpam_get_template_file( $args, $theme_paths,      'ul-start' );

		ob_start();
		require( $file_path );
		$output .= ob_get_clean();
	
		$output .= "{$n}";

	}




	/**
	 * Ends the list of after the elements are added.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		$output    .= "$indent";

		
		if( is_object( $args ) != true ){
			$args = (object) $args;
		}
		
		/*
		// WPAM : pass item values
		// $args->wpam_item = $item ;

		// WPAM : pass menu values
		$args->wpam_menu = $args->menu ;
		*/
		$args->wpam = new stdClass() ;
		$args->wpam->wpam_item = new stdClass() ;

		if( $this->current_item ){ $item = $this->current_item ; }
		$args->wpam->wp_item = $item ;
		$args->wpam->wp_menu = $args->menu ;

		$get_post_meta_item_type = get_post_meta( $args->wpam->wp_item->ID , '_wpam_custom_item_type' , true );
		$get_post_meta_item_type = sanitize_text_field( $get_post_meta_item_type );

		$args->wpam->wpam_item->type = $get_post_meta_item_type;
		
		$args->wpam->wpam_item->submenu = $this->get_item_submenu_settings( $args->wpam->wp_item ) ;
		

		$theme_path = wpam_get_template_path( $args, $this->theme_dirs, 'ul-stop' );
		$file_path  = wpam_get_template_file( $args, $theme_path,       'ul-stop' );

		ob_start();
		require( $file_path );
		$output .= ob_get_clean();

		$output .= "{$n}";
	}



	/**
	 * Starts the element output.
	 *
	 * @since 3.0.0
	 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item. Used for padding.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		
		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );


		/**
		 * Filters the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		// $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';


		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
		// $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent ;

		$this->item_deph = $depth ;
		
		
		if( is_object( $args ) != true ){
			$args = (object) $args;
		}
		
		/*
		// WPAM : pass item values
		$args->wpam_item = $item ;

		// WPAM : pass menu values
		$args->wpam_menu = $args->menu ;
		*/
		
		$args->wpam = new stdClass() ;
		$args->wpam->wpam_item = new stdClass() ;

		$this->current_item  =  $item ;
		$args->wpam->wp_item = $item ;

		$args->wpam->wp_menu = $args->menu ;

		$args->wpam->wp_class_names = $class_names ;
		$args->wpam->wp_id = $id ;

		$get_post_meta_item_type = get_post_meta( $args->wpam->wp_item->ID , '_wpam_custom_item_type' , true );
		$get_post_meta_item_type = sanitize_text_field( $get_post_meta_item_type );

		$args->wpam->wpam_item->type = $get_post_meta_item_type;

		$args->wpam->wpam_item->submenu = $this->get_item_submenu_settings( $args->wpam->wp_item ) ;

		$theme_path = wpam_get_template_path( $args, $this->theme_dirs, 'li-start' );
		$file_path  = wpam_get_template_file( $args, $theme_path,       'li-start' );

		ob_start();
		require( $file_path );
		$output .= ob_get_clean();

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		
		/** This filter is documented in wp-includes/post-template.php */
		// $item->title = $item->post_title;
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		
		


		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output = $args->before;

		if( is_object( $args ) != true ){
			$args = (object) $args;
		}
		
		/*
		// WPAM : pass item values
		$args->wpam_item = $item ;

		// WPAM : pass menu values
		$args->wpam_menu = $args->menu ;
		*/

		$args->wpam = new stdClass() ;
		$args->wpam->wpam_item = new stdClass() ;

		$args->wpam->wp_item = $item ;
		$args->wpam->wp_menu = $args->menu ;

		$get_post_meta_item_type = get_post_meta( $args->wpam->wp_item->ID , '_wpam_custom_item_type' , true );
		$get_post_meta_item_type = sanitize_text_field( $get_post_meta_item_type );

		$args->wpam->wpam_item->type = $get_post_meta_item_type;

		$args->wpam->wpam_item->submenu = $this->get_item_submenu_settings( $args->wpam->wp_item ) ;

		$theme_path = wpam_get_template_path( $args, $this->theme_dirs, 'item' );
		$file_path  = wpam_get_template_file( $args, $theme_path,       'item' );

		ob_start();
		require( $file_path );
		$output .= ob_get_clean();

		$item_output .= $args->after;
		
		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}




	
	/**
	 * Ends the element output, if needed.
	 *
	 * @since 3.0.0
	 *
	 * @see Walker::end_el()
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item   Page data object. Not used.
	 * @param int      $depth  Depth of page. Not Used.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}

		
		if( is_object( $args ) != true ){
			$args = (object) $args;
		}
		
		/*
		// WPAM : pass item values
		$args->wpam_item = $item ;

		// WPAM : pass menu values
		$args->wpam_menu = $args->menu ;
		*/

		$args->wpam = new stdClass() ;
		$args->wpam->wpam_item = new stdClass() ;

		$this->current_item  =  $item ;
		$args->wpam->wp_item = $item ;
		$args->wpam->wp_menu = $args->menu ;

		$get_post_meta_item_type = get_post_meta( $args->wpam->wp_item->ID , '_wpam_custom_item_type' , true );
		$get_post_meta_item_type = sanitize_text_field( $get_post_meta_item_type );

		$args->wpam->wpam_item->type = $get_post_meta_item_type;
		
		$args->wpam->wpam_item->submenu = $this->get_item_submenu_settings( $args->wpam->wp_item ) ;

		$theme_path = wpam_get_template_path( $args , $this->theme_dirs, 'li-stop' );
		$file_path  = wpam_get_template_file( $args, $theme_path, 'li-stop' );

		ob_start();
		require( $file_path );
		$output .= ob_get_clean();
		
		$output .= "{$n}";
	}

}