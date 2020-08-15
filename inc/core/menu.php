<?php

defined( 'ABSPATH' ) or	die();



/**
 * Callback of pre_wp_nav_menu
 * for handle cache feature before menu generation
 *
 * @since 0.1.0
 */
function wpam_pre_wp_nav_menu_cb( $output, $args ) { 

    // var_dump($args);

    /*
    // Check cache and so :
    $cache = 'wp:am cache tmp';

    // cache support ?
    if( WPAM_SUPPORT_CACHE ){
        $output = $cache ;
        $args->echo  = true;
    }
    */
    
    // return
    return $output;
};



/**
 * Callback of wp_nav_menu_args
 * for setup and load theme walker based on context and $args values
 * and apply filters
 *
 * @since 0.1.0
 */
function wpam_wp_nav_menu_args_cb( $args ) { 

    
    
    // var_dump($args);
    
    // Has theme ? if OK, return elts for work with.
    // So get refs are for walker class
    $wpam_theme_refs = wpam_get_menu_theme_from_transients( $args ) ; 
    
    // var_dump($args['menu']);
    // var_dump( $args ) ;

    // if wpam settings 
    if( isset( $args['wpam'] ) ){

        $is_already_include = wpam_is_value_in_transients(  $args['wpam']->theme_slug, 'theme_slug', 'themes' );

        // ——
        // Check template parameter
        if( ! property_exists( $args['wpam'], "theme_template") ){
            $args['wpam']->theme_template = $args['wpam']->theme_slug ;
        }
        if( ! property_exists( $args['wpam'], "theme_origin") ){
            $args['wpam']->theme_origin = '' ;
        }

        // ——
        // Check if theme_overide is allowed
        // Compare with theme info in libraray (bd)
        $theme_lib_values = wpam_get_theme_library_values( $args['wpam']->theme_slug, $args['wpam']->theme_core );

        // if default value override is false, change demanded value
        if( isset( $theme_lib_values ) &&
            property_exists( $theme_lib_values, "theme_override") &&
            $theme_lib_values->theme_override === 'false'
        ){
            $args['wpam']->theme_override = false ;
        }


        // —— ——
        // has not location ?
        
        // ——
        // files dont already included, for exemple if menu is not in location
        if( !$is_already_include ){

            // var_dump('already included');

            $dirs = wpam_get_theme_directories(
                 $args['wpam']->theme_slug,
                 $args['wpam']->theme_core,
                 $args['wpam']->theme_template,
                 $args['wpam']->theme_override,
                 $args['wpam']->theme_origin
                );
            
            // override
            // $wpam_theme_refs = $args['wpam'] ;

            // load
            // var_dump($args['menu']);
            if( $args['menu'] =! '' ){ $ui['menu'] =  $args['menu'] ; }

            wpam_load_theme_functions_files( $dirs, $args['wpam']->theme_override );

            // —— nop
            // do_action( 'wpam_load_theme_options' );
            // wpam_do_action_wpam_options_handler();

            // ——
            // define default options
            do_action( 'wpam_set_theme_options' );

            // —— so script & style fromfor theme loaded with wpam arg in page
            // wp_enqueue_scripts();
            do_action( 'wpam_enqueue_scripts' );
        }

        // ——
        // here, in all cases, files already loaded
        // so, validate values for mthe next step
        $wpam_theme_refs = (Object) array(
            'theme_slug'     => $args['wpam']->theme_slug,
            'theme_core'     => $args['wpam']->theme_core,
            'theme_template' => $args['wpam']->theme_template,
            'theme_override' => $args['wpam']->theme_override,
            'theme_origin'   => $args['wpam']->theme_origin
        );

        global $wpam_theme_default_value ;
        if( $wpam_theme_default_value ){
            $wpam_theme_refs->options_default = $wpam_theme_default_value ;
            unset($wpam_theme_default_value);
        }
        
    }
    

    // If refrerences are set -> load Walker. \o/
    if( is_object( $wpam_theme_refs ) ){

        // load walker
        if( $wpam_theme_refs->theme_slug != 'wpam-builder' ){
            $args['walker'] = new WPAM_Theme( $wpam_theme_refs ) ;
        }else{
            $args['walker'] = new WPAM_Builder() ;
        }
    }

    
    // Treatments of other parameters
    // @source : https://developer.wordpress.org/reference/functions/wp_nav_menu/

    // —
    // Filter — by theme name
    if( is_object( $wpam_theme_refs ) ){

        // origin theme
        $theme_slug = $wpam_theme_refs->theme_origin ;
        if( $theme_slug ){ $args = apply_filters( "wpam_filter_theme_{$theme_slug}_nav_menu_args", $args ); }

        // template theme
        $theme_slug = $wpam_theme_refs->theme_template ;
        if( $theme_slug ){ $args = apply_filters( "wpam_filter_theme_{$theme_slug}_nav_menu_args", $args ); }
        
        // current theme
        $theme_slug = $wpam_theme_refs->theme_slug ;
        if( $theme_slug ){ $args = apply_filters( "wpam_filter_theme_{$theme_slug}_nav_menu_args", $args ); }
    }


    // —
    // Filter — by location
    
    if( ($args['theme_location']  != '') && (gettype($args['theme_location']) == 'string') ){
        $menu_location = $args['theme_location'];
        $args = apply_filters( "wpam_filter_location_{$menu_location}_nav_menu_args", $args );
    }


    // —
    // Filter — by slug

    if( $args['menu'] != '' ){

        // string -> slug
        if( ($args['menu'] != '') && (gettype($args['menu']) == 'string') && (gettype(intval($args['menu'])) != 'integer') ){
            $menu_slug = $args['menu'] ;
            $args = apply_filters( "wpam_filter_slug_{$menu_slug}_nav_menu_args", $args );
        }

        // id -> slug
        if(
            ($args['menu'] != '') && (gettype($args['menu']) == 'string') && (gettype(intval($args['menu'])) == 'integer') ||
            ($args['menu'] != '') && (gettype($args['menu']) == 'integer')
            ){
            
            $menu_obj =  wp_get_nav_menu_object(intval($args['menu'])) ;
            if( $menu_obj ){
                $menu_slug = $menu_obj->slug;
                $args = apply_filters( "wpam_filter_slug_{$menu_slug}_nav_menu_args", $args );
            };
        }
        
        // object -> slug
        if( ($args['menu'] != '') && (gettype($args['menu']) == 'object') ){
            $menu_slug = $args['menu']->slug ;
            $args = apply_filters( "wpam_filter_slug_{$menu_slug}_nav_menu_args", $args );
        }

    }elseif( $menu_location && $args['menu'] == '' ){
        // Find slug of menu and apply filter
        $locations = get_nav_menu_locations() ;
        if( array_key_exists($menu_location, $locations) ){

            $menu_id = $locations[$menu_location];
            $menu_slug = wpam_get_menu_slug_by_id($menu_id);
            $args = apply_filters( "wpam_filter_slug_{$menu_slug}_nav_menu_args", $args );

        }
    }

    return $args; 
}; 



/**
 * Helper : get menu slug based on the id passed
 *
 * @since 0.1.0
 * 
 * @param integer id of menu
 * 
 * @return string slug or bool false if no slug found
 */
function wpam_get_menu_slug_by_id( $id = false ) {

    if( isset( $id ) && $id !== false ){

        $menus = get_terms( 'nav_menu' ); 

        foreach ( $menus as $menu ) {
            if( $id === $menu->term_id ) {
                return $menu->slug;
            }
        }
    }
    return false;
}



/**
 * Get theme from $args values passed as param
 *
 * @since 0.1.0
 * 
 * @param $args from wp_nav_menu_args filter
 * 
 * @return theme references if find or false if not
 * 
 * @see : wpam_wp_nav_menu_args_cb()
 */
function wpam_get_menu_theme_from_transients( $args = array() ){
        
    // Get transient :
    $wpam = get_transient( WPAM_TRANSIENTS_SLUG );
    
    // test vars for searching theme
    $menu_location_is_in_global = false ;
    $menu_slug_is_in_global     = false ;
    $menu_id_is_in_global       = false ;
    $menu_object_is_in_global   = false ;

    // Check menu value (slug, id, object)
    $menu_arg = $args['menu'];
    $menu_slug_of_current_menu = '';

    // string case - slug
    if( gettype( $menu_arg ) == 'string' ){
        
        $menu_slug = $menu_arg ;
        $menu_slug_of_current_menu = $menu_slug;

        $menu_slug_is_in_global = wpam_is_value_in_transients( $menu_slug, 'slug', 'menus' ) ;
        
        
        // string + number case - id
        if( gettype( intval($menu_slug) ) == 'integer' ) {
            $menu_id = intval( $menu_arg );
            
            if( wp_get_nav_menu_object( $menu_id ) ){
                $menu_slug_of_current_menu = wp_get_nav_menu_object( $menu_id )->slug;
            };
            
            $menu_id_is_in_global = wpam_is_value_in_transients( $menu_id, 'term_id', 'menus' ) ;
        }

    }

    // object case -
    if( is_object( $menu_arg ) && get_class( $menu_arg ) == 'WP_Term' ){
        $menu_obj = $menu_arg ;
        $menu_slug_of_current_menu = $menu_obj->slug ;
        $menu_object_is_in_global = wpam_is_value_in_transients( $menu_obj ,'object', 'menus') ;
    }

    // locations validation
    $menu_location_is_in_global = wpam_is_value_in_transients( $args['theme_location'], 'location', 'locations' ) ;
    
    if( $menu_location_is_in_global ){
        $theme_locations = get_nav_menu_locations();
        $menu_obj = get_term( $theme_locations[$args['theme_location']], 'nav_menu' );
        $menu_slug_of_current_menu = $menu_obj->slug;
    }
    
    // Have we find theme ?
    if(
        $menu_location_is_in_global ||
        $menu_slug_is_in_global     ||
        $menu_id_is_in_global       ||
        $menu_object_is_in_global
        ){
            
            // return elts for walker
            // theme_slug, theme_core, override
            // get refs, use transients themes value :
            if( $menu_slug_of_current_menu ){
                
                // check themes exist, no -> fallback theme
                if( ! isset( $wpam['themes'][$menu_slug_of_current_menu] ) ){
                    $wpam_theme_refs = $wpam['plugin']['theme_fallback'];
                }else{
                    // yep, prepare value for return
                    $wpam_theme_refs = (Object) array(
                        'theme_slug'     => $wpam['themes'][$menu_slug_of_current_menu]->theme_slug,
                        'theme_core'     => $wpam['themes'][$menu_slug_of_current_menu]->theme_core,
                        'theme_template' => '',
                        'theme_origin'   => '',
                        'theme_override' => $wpam['themes'][$menu_slug_of_current_menu]->theme_override,
                    );                    
                }

                // optionnal pproperty
                if( property_exists( $wpam['themes'][$menu_slug_of_current_menu], "theme_template") ){
                    $wpam_theme_refs->theme_template = $wpam['themes'][$menu_slug_of_current_menu]->theme_template ;
                }

                // optionnal pproperty
                if( property_exists( $wpam['themes'][$menu_slug_of_current_menu], "theme_origin") ){
                    $wpam_theme_refs->theme_origin = $wpam['themes'][$menu_slug_of_current_menu]->theme_origin ;
                }

            }

            // return theme references dude
            return $wpam_theme_refs ;

    }

    // nothing find, return false :/
    return false;
}



/**
 * Get theme slug by menu slug
 *
 * @since 0.1.0
 * 
 * @param string slug of menu
 * 
 * @return string theme slug if OK, false if KO
 */
function wpam_get_menu_theme_by_slug( $menu_slug ){

    if( $menu_slug ){
        
        $menu                  = wp_get_nav_menu_object( $menu_slug );
        $wpam_theme            = get_field('wpam_menu_options_theme_selector', $menu );
        $wpam_themes_db_option = get_option( WPAM_THEMES_LIST_SLUG );
        
        if( $wpam_theme != '' && $wpam_theme != NULL ){
            
            // check themes exist, no -> fallback theme
            if( isset( $wpam_themes_db_option[$wpam_theme]->theme_slug ) ){
                $theme_slug = $wpam_themes_db_option[$wpam_theme]->theme_slug;

            }else{
                // fallback
                $theme_slug = $wpam_themes_db_option[ WPAM_THEME_ID_FALLBACK ]->theme_slug;
            }


            /*
            if( isset( $wpam_themes_db_option[$wpam_theme]->theme_slug ) ){
                $theme_slug = $wpam_themes_db_option[$wpam_theme]->theme_slug;

            }else{
                // fallback
                $theme_slug = $wpam_themes_db_option[ WPAM_THEME_ID_FALLBACK ]->theme_slug;
            }
            */

            return $theme_slug ;
        }
    }

    return false;
}