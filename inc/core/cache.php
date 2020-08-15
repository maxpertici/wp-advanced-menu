<?php

defined( 'ABSPATH' ) or	die();
// @source : https://generatewp.com/how-to-use-transients-to-speed-up-wordpress-menus/

class WPAM_menu_cache{
    /**
     * $cache_time
     * transient expiration time
     * @var int
     */
    public $cache_time = 43200; // 12 hours in seconds
    /**
     * $timer
     * simple timer to time the menu generation
     * @var time
     */
    public $timer;
     
    /**
     * __construct
     * class constructor will set the needed filter and action hooks
     *
     */
    function __construct(){
        global $wp_version;
        // only do all of this if WordPress version is 3.9+
        if ( version_compare( $wp_version, '3.9', '>=' ) ) {
 
            //show the menu from cache
            add_filter( 'pre_wp_nav_menu', array($this,'pre_wp_nav_menu'), 20, 2 );

            //store the menu in cache
            add_filter( 'wp_nav_menu', array($this,'wp_nav_menu'), 20, 2);
            
            // widget support
            add_filter('widget_nav_menu_args', array($this, 'widget_nav_menu_args'), 20, 3);

            //refresh on update
            add_action( 'wp_update_nav_menu', array($this,'wp_update_nav_menu'), 20, 1);
        }
    }
     
    /**
     * get_menu_key
     * Simple function to generate a unique id for the menu transient
     * based on the menu arguments and currently requested page.
     * @param  object $args     An object containing wp_nav_menu() arguments.
     * @return string
     */
    function get_menu_key($args){
        
        // return 'wpam-mc-' . md5( serialize( $args ).serialize(get_queried_object()) );
        $key = 'wpam-mc-' . md5( serialize( $args ).serialize(get_queried_object()) );
        
        return $key ;

    }
     
    /**
     * get_menu_transient
     * Simple function to get the menu transient based on menu arguments
     * @param  object $args     An object containing wp_nav_menu() arguments.
     * @return mixed            menu output if exists and valid else false.
     */
    function get_menu_transient($args){
        $key = $this->get_menu_key($args);
        $transient = get_transient($key);

        return $transient;
    }
  
 
  
    /**
     * pre_wp_nav_menu
     *
     * This is the magic filter that lets us short-circit the menu generation
     * if we find it in the cache so anything other then null returend will skip the menu generation.
     *
     * @param  string|null $nav_menu    Nav menu output to short-circuit with.
     * @param  object      $args        An object containing wp_nav_menu() arguments
     * @return string|null
     */
    function pre_wp_nav_menu($nav_menu, $args){
        $this->timer = microtime(true);
        $in_cache = $this->get_menu_transient($args);
        $last_updated = get_transient('wpam-mc-' . $args->theme_location . '-updated');

        if (isset($in_cache['data']) && isset($last_updated) &&  $last_updated < $in_cache['time'] ){
            return $in_cache['data'].'<!-- From menu cache in '.number_format( microtime(true) - $this->timer, 5 ).' seconds -->';
        }
        return $nav_menu;
    }
  
     
    /**
     * wp_nav_menu
     * store menu in cache
     * @param  string $nav      The HTML content for the navigation menu.
     * @param  object $args     An object containing wp_nav_menu() arguments
     * @return string           The HTML content for the navigation menu.
     */
    function wp_nav_menu( $nav, $args ) {
        $last_updated = get_transient('wpam-mc-' . $args->theme_location . '-updated');
        if( ! $last_updated ) {
            set_transient('wpam-mc-' . $args->theme_location . '-updated', time());
        }
        $key = $this->get_menu_key($args);
        $data = array('time' => time(), 'data' => $nav);
         
        set_transient( $key, $data ,$this->cache_time);
        return $nav.'<!-- Not From menu cache in '.number_format( microtime(true) - $this->timer, 5 ).' seconds -->';
    }
  
    /**
     * wp_update_nav_menu
     * refresh time on update to force refresh of cache
     * @param  int $menu_id
     * @return void
     */
    function wp_update_nav_menu($menu_id) {
        $locations = array_flip(get_nav_menu_locations());
  
        if( isset($locations[$menu_id]) ) {
            set_transient('wpam-mc-' . $locations[$menu_id] . '-updated', time());
        }
    }

    /**
    * widget_nav_menu_args
    * Filters the arguments passed to wp_nav_menu() function when fired within the
    * native WP Custom Menu Widget, by adding the theme_location parameter to them. 
    * 
    * This is a method hooked to a filter in the WP Widget Menu class.
    * Since in that wp_nav_menu() is not given theme_location in args, that results
    * in breaking this caching system for key generation based on that parameter. 
    *
    * @since 4.2.0 
    * @access public
    * @see WP_Nav_Menu_Widget in /wp-includes/default-widgets.php
    * @param array $nav_menu_args {
    *       an array of arguments passed to wp_nav_menu() to retreive a custom menu.
    *       @type callback|bool $fallback_cb Callback to fire if the menu doesn't exist. Default empty.
    *       @type mixed         $menu        Menu ID, slug, or name.
    * }
    * @param stdClass $nav_menu      Nav menu object for the current menu.
    * @param array    $args          Display arguments for the current widget. 
    */
    public function widget_nav_menu_args($nav_menu_args, $nav_menu, $args) {
        $locations = get_nav_menu_locations(); //retrieve menus theme_locations.
        $the_location = array_search($nav_menu->term_id, $locations); //get theme_location for this menu.
        if ($the_location !== false) {
            $nav_menu_args['theme_location'] = $the_location; //set theme_location in new args passed to wp_nav_menu
        }
        return $nav_menu_args;
    }
  
}//end class

function WPAM_menu_cache_init() {
    $GLOBALS['wp_menu_cache'] = new WPAM_menu_cache();
}