<?php

defined( 'ABSPATH' ) or	die();



/**
 * Prepare theme datas for transients
 *
 * @since 0.1.0
 * 
 * @return void OR string 'rebuild_transients' (ction to be done) if things has changed. For exemple, new theme -> need to 'rebuild_transients'
 */
function wpam_prepare_themes_datas(){

	$action_to_be_done = '';

	// First filters
	// Array of identifiers and informations of themes in order to fecthnig folder.
	
	$builtin_themes_array = array(
        'wp-theme'        => '{"theme_core":{"theme_location":"wpam"}}',
	);

	$wpam_themes_list       = array();
    $wpam_themes_list       = wpam_add_themes_to_list($wpam_themes_list , $builtin_themes_array );
    

	$wpam_extra_themes_list = array() ;
    $wpam_extra_themes_list = apply_filters( 'wpam_add_extra_themes', $wpam_extra_themes_list );
    $wpam_extra_themes_list = wpam_add_themes_to_list($wpam_themes_list , $wpam_extra_themes_list );
    
	$wpam_themes_list       = array_merge( $wpam_themes_list, $wpam_extra_themes_list );
    $wpam_themes_list       = wpam_prepare_theme_list_for_fetching( $wpam_themes_list );

    // print_r($wpam_themes_list);
    
    $wpam_themes_datas      = wpam_themes_fetch( $wpam_themes_list );

    // print_r($wpam_themes_datas);

	// THEMES DB NO AUTOLOAD
	// Create DB or update it if values has changed
	$wpam_themes_list_option = WPAM_THEMES_LIST_SLUG ;
	$wpam_themes_list_in_db = get_option( $wpam_themes_list_option ) ;
	// var_dump($wpam_themes_datas);

	if ( $wpam_themes_list_in_db !== false ) {
		$save_themes_list = false;
		
		// already in db, so we comapre and save if different
		// test if one more
		foreach( $wpam_themes_datas as $key => $value ){

			if( ! array_key_exists( $key, $wpam_themes_list_in_db ) ) {
				$save_themes_list = true;
				break ;
			}
		}

		// test if number is diffrent
		if( count( $wpam_themes_datas ) !=  count( $wpam_themes_list_in_db ) ){
			$save_themes_list = true;
        }
        
        // Test string
        if(  $wpam_themes_datas !== $wpam_themes_list_in_db ){
            $save_themes_list = true;
        }        

		// need to rebuild transients
		if( $save_themes_list ){
			
			update_option( $wpam_themes_list_option, $wpam_themes_datas );
			$action_to_be_done = 'rebuild_transients';
		}

		

	} else {
		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		$deprecated = null;
		$autoload = 'no';
		add_option( $wpam_themes_list_option, $wpam_themes_datas, $deprecated, $autoload );
	}

	return $action_to_be_done ;
}



/**
 * Prepare theme list
 * contruct themes list with identifier, etc from result (at the end) of wpam_add_extra_themes action 
 *
 * @since 0.1.0
 * 
 * @param array $wpam_themes_list
 * 
 * @return array of themes list with unique identifier and JSON for ACF fileds
 */
function wpam_prepare_theme_list_for_fetching( $wpam_themes_list ){

	$wpam_themes  = array();

	foreach( $wpam_themes_list as $theme_slug => $theme_records ){
		
		// Built
        $theme_identifier = wpam_prepare_theme_get_identifier( $theme_slug, $theme_records );
        
		$theme_json  = '{' ;

		$theme_json .= '"theme_slug":"'. $theme_slug . '"';

		$theme_json .= ',"theme_core":' ;

			$theme_json .= '{' ;
			
			$theme_json .= '"theme_location"' ;
			$theme_json .= ':' ;
			$theme_json .= '"' . $theme_records->theme_core->theme_location . '"' ;

			if( property_exists( $theme_records->theme_core, "theme_folder_path") ){
			$theme_json .= ',"theme_loader_slug"';
			$theme_json .= ':' ;
			$theme_json .= '"' . $theme_records->folder_slug . '"' ;

			$theme_json .= ',"theme_folder_path"' ;
			$theme_json .= ':' ;
			$theme_json .= '"' . $theme_records->folder_slug . '/' . $theme_records->theme_core->theme_folder_path . '"' ;
			}

			$theme_json .= '}' ;
        
        /*
        // No here, Use File Headers in style.css instead

        if( property_exists( $theme_records, "theme_template") ){
			$theme_json .= ',"theme_template":"'. $theme_records->theme_template . '"';
        }

        if( property_exists( $theme_records, "theme_origin") ){
			$theme_json .= ',"theme_origin":"'. $theme_records->theme_origin . '"';
        }
        */
        
        if( property_exists( $theme_records, "theme_override") ){
			$theme_json .= ',"theme_override":"'. $theme_records->theme_override . '"';
		}
        
        
		$theme_json .= '}' ;
		$wpam_themes[ $theme_identifier ] = $theme_json ;
	}

	return $wpam_themes ;
}



/**
 * Get unique identifier of a given theme in funciton of context (wpam include, plugin or theme origin)
 *
 * @since 0.1.0
 * 
 * @return string
 */
function wpam_prepare_theme_get_identifier( $theme_slug, $theme_records ){
	
	// generate prefix + slug for unique identifier theme
	$wpam_theme_identifier = '' ;

	if( property_exists( $theme_records->theme_core, "theme_location") ){
		$theme_location = $theme_records->theme_core->theme_location ;
	}else{
		$theme_location = 'wpam';
	}
        
	switch ( $theme_location ) {
		
		case 'wpam':
		$wpam_theme_identifier .= 'wpam';
		break;
		
        case 'plugin':
        $wpam_theme_identifier .= 'wpam-plugin' . '-' . $theme_records->folder_slug ;
		break;

		case 'theme':
		$wpam_theme_identifier .= 'wpam-theme' . '-' . $theme_records->folder_slug ;
		break;
	}

	$wpam_theme_identifier .= '-' . $theme_slug ;

	return $wpam_theme_identifier ;
}



/**
 * Wrapper for wpam_prepare_theme_get_identifier()
 *
 * @since 0.1.0
 * 
 * @return string
 */
function wpam_get_theme_identifier( $theme_slug, $theme_records ){
    return wpam_prepare_theme_get_identifier( $theme_slug, $theme_records );
}




/**
 * Add list of themes to array of themes given
 *
 * @since 0.1.0
 * 
 * @param array $themes_list of themes to add
 * @param array $themes_array of existing themes
 * 
 * @return array 
 */
function wpam_add_themes_to_list(  $themes_list, $themes_array  ) {
	
	foreach( $themes_array as $theme_identifier => $theme_records ){
		$themes_list[ $theme_identifier ] = json_decode( $theme_records );
	}

	return $themes_list;
}



/**
 * Wrapper register on wpam_add_extra_themes action for allowing extra thems to be added
 *
 * @since 0.1.0
 */

function wpam_add_extra_themes( $wpam_extra_themes_list ){

	return $wpam_extra_themes_list ;
}







/**
 * Fetch themes for exacting datas
 *
 * @since 0.1.0
 * 
 * @param array $wpam_themes_list formatted after wpam_prepare_theme_list_for_fetching()
 * 
 * @return array identifier <-> theme refs JSON
 */
function wpam_themes_fetch( $wpam_themes_list ){
	$wpam_themes_datas = array();

	foreach ( $wpam_themes_list as $theme_identifier => $theme_records ){

		// Check theme_core for context
		$theme_records = json_decode($theme_records);
		
		// WORK WITH FILE HEADER HERE
		// @source : https://codex.wordpress.org/File_Header
		// Get others infos from style.css and add them to JSON
		$stylesheet = $theme_records->theme_slug ;

		if( $theme_records->theme_core->theme_location === 'wpam' ){

			$theme_records->theme_core->theme_folder_path = WPAM_THEMES_PATH;
			$theme_root = $theme_records->theme_core->theme_folder_path ;

		}elseif( $theme_records->theme_core->theme_location === 'plugin'){
			$theme_root = WP_PLUGIN_DIR . '/' . $theme_records->theme_core->theme_folder_path ;

		}elseif( $theme_records->theme_core->theme_location === 'theme' ){
			$theme_root =  get_theme_root() . '/' . $theme_records->theme_core->theme_folder_path ;
		}

		$theme_header = wp_get_theme( $stylesheet, $theme_root ) ;
		
        $theme_records->theme_name = $theme_header->get( 'Name' );
        $theme_records->theme_description = $theme_header->get( 'Description' );

        $theme_records->theme_template = $theme_header->get( 'Template' );
        $theme_records->theme_origin = $theme_header->get( 'Origin' );

		$wpam_themes_datas[ $theme_identifier ] = $theme_records ;
    }
    
    return $wpam_themes_datas ;
}

/**
 * 
 *  @since 0.2.0
 */
// @source : https://core.trac.wordpress.org/ticket/20897
add_filter( 'extra_theme_headers', 'wpam_tc_add_headers' );

function wpam_tc_add_headers( $extra_headers ) {
    $extra_headers = array( 'Origin' );
    return $extra_headers;
}


/**
 * Get themes directory path of a given theme
 * It's depend of context and settings of theme.
 * Directorys are return in the right order
 *
 * @since 0.1.0
 * 
 * @param string theme_slug
 * @param string theme_core
 * @param string theme_template
 * @param bool   theme_override
 * @param string theme_origin 
 * 
 * @return array
 */
function wpam_get_theme_directories( $theme_slug = 'wp-theme', $theme_core = '{"theme_location":"wpam"}', $theme_template = 'wp-theme', $theme_override = true, $theme_origin_slug = '' ){
    
    $directories = array();

    // test theme_core
    if( gettype( $theme_core ) === 'string' ){ $theme_core = json_decode($theme_core) ;}
    
    // first override
    if( $theme_override == true ){
        // check if is child theme, may be we hace 2 levels : Child theme, Theme
        $directories['overrides'] = array();

        if( get_template_directory() === get_stylesheet_directory() ) {
            $directories['overrides'][0] = get_stylesheet_directory() . '/' .  WPAM_THEME_OVERIDING_FOLDER_PATH .  $theme_slug . '/';

        } else {
            $directories['overrides'][0] = get_stylesheet_directory() . '/' .  WPAM_THEME_OVERIDING_FOLDER_PATH .  $theme_slug . '/';
            
            // if($theme_core !== 'theme'){
            if( $theme_core->theme_location !== 'theme') {
            $directories['overrides'][1] = get_template_directory() . '/' .  WPAM_THEME_OVERIDING_FOLDER_PATH .  $theme_slug . '/';
            }
        }
    }

    if( gettype($theme_core) != 'object' && gettype($theme_core) == 'string' ){
        $theme_core = json_decode($theme_core);
    }

    if( $theme_core->theme_location === 'wpam'){
        
        $directories['core'] = WPAM_THEMES_PATH .  $theme_slug . '/';
        
        if( $theme_slug != $theme_template ){
        $directories['template'] = WPAM_THEMES_PATH .  $theme_template . '/';
        }
    }

    if( $theme_core->theme_location === 'theme'){

        $directories['core'] = get_theme_root() . '/' .  $theme_core->theme_folder_path .  $theme_slug . '/';

        if( $theme_slug != $theme_template ){
        $directories['template'] = get_theme_root() . '/' .  $theme_core->theme_folder_path .  $theme_template . '/';
        }

        if( $theme_origin_slug != '' ){
        $directories['origin'] = WPAM_THEMES_PATH .  $theme_origin_slug . '/' ;
        }
    }

    if( $theme_core->theme_location === 'plugin'){
        
        $directories['core'] = WPAM_PLUGIN_FOLDER_INSTALL  . $theme_core->theme_folder_path .  $theme_slug . '/';

        if( $theme_slug != $theme_template ){
        $directories['template'] = WPAM_PLUGIN_FOLDER_INSTALL  . $theme_core->theme_folder_path .  $theme_template . '/';
        }

        if( $theme_origin_slug != '' ){
        $directories['origin'] = WPAM_THEMES_PATH .  $theme_origin_slug . '/' ;
        }
    }

    return $directories ;
}



/**
 * Get theme_core value from a given menu_slug
 *
 * @since 0.1.0
 * 
 * @param string menu slug
 * 
 * @return string return '' if not find or the value (wpam, plugin, theme)
 */
function wpam_get_theme_core( $menu_slug ){
    
    // default
    $return = '';

    // je ne sais pas ce que veux dire le message si dessous...
    // je pene que c'est obsolete ;P
    /*
    pour le moment, rien en cache a partir de l'admin, donc tout est plugin pour le core
    wpam : included natively in plugin, no add on
    */
    
    if( $menu_slug ){

        $menu = wp_get_nav_menu_object( $menu_slug );

        $menu_key = get_field( 'wpam_menu_options_theme_selector' , $menu );
        $wpam_themes_db_option = get_option( WPAM_THEMES_LIST_SLUG );
        
        // if not exist -> fallback
        if( isset($wpam_themes_db_option[$menu_key]) ){
            $menu_obj =  $wpam_themes_db_option[$menu_key];
        }else{
            $menu_obj =  $wpam_themes_db_option[ WPAM_THEME_ID_FALLBACK ];
        }

        
        if( property_exists( $menu_obj, "theme_core") ){
            $return = $menu_obj->theme_core;
        }
    }
    
    return $return ; 
}



/**
 * Get theme_tempalte value from a given menu_slug
 *
 * @since 0.1.0
 * 
 * @param string menu slug
 * 
 * @return string return '' if not find or the value (slug of existing theme)
 */
function wpam_get_theme_template( $menu_slug ){
    
    $return = '';

    if( $menu_slug ){

        // search  in transient and extract value

        $menu = wp_get_nav_menu_object( $menu_slug );

        $menu_key = get_field( 'wpam_menu_options_theme_selector' , $menu );
        $wpam_themes_db_option = get_option( WPAM_THEMES_LIST_SLUG );
       
        // test if exist, if not -> fallback
        if( isset( $wpam_themes_db_option[$menu_key] ) ){
            $menu_obj =  $wpam_themes_db_option[$menu_key];
        }else{
            $menu_obj =  $wpam_themes_db_option[ WPAM_THEME_ID_FALLBACK ];
        }
        

        if( property_exists( $menu_obj, "theme_template") ){
            $return = $menu_obj->theme_template;
        }

    }

    return $return;
}



/**
 * Get theme_origin value from a given menu_slug
 *
 * @since 0.1.0
 * 
 * @param string menu slug
 * 
 * @return string return '' if not find or the value (theme's slug)
 */
function wpam_get_theme_origin( $menu_slug ){

    $return = '';

    if( $menu_slug ){

        // search  in transient and extract value
        $menu = wp_get_nav_menu_object( $menu_slug );

        $menu_key = get_field( 'wpam_menu_options_theme_selector' , $menu );
        $wpam_themes_db_option = get_option( WPAM_THEMES_LIST_SLUG );

        // test if exist, if not -> fallback
        if( isset( $wpam_themes_db_option[$menu_key] ) ){
            $menu_obj =  $wpam_themes_db_option[$menu_key];
        }else{
            $menu_obj =  $wpam_themes_db_option[ WPAM_THEME_ID_FALLBACK ];   
        }
        
        
        if( property_exists( $menu_obj, "theme_origin") ){
            $return = $menu_obj->theme_origin;
        }

    }

    return $return;
}



/**
 * Get theme_override value from a given menu_slug
 *
 * @since 0.1.0
 * 
 * @param string menu slug
 * 
 * @return bool
 */
function wpam_get_theme_override( $menu_slug ){


    if( $menu_slug ){
        
        $menu = wp_get_nav_menu_object( $menu_slug );
        
        $theme_override = true;

        $menu_key = get_field( 'wpam_menu_options_theme_selector' , $menu );
        $wpam_themes_db_option = get_option( WPAM_THEMES_LIST_SLUG );
        
        //if exust, if not -> fallback
        if( isset( $wpam_themes_db_option[$menu_key] ) ){
            $menu_obj =  $wpam_themes_db_option[$menu_key];
        }else{
            $menu_obj =  $wpam_themes_db_option[ WPAM_THEME_ID_FALLBACK ];
        }
                
        if(
            property_exists( $menu_obj, "theme_override") &&
            $menu_obj->theme_override === 'false'
            ){ $theme_override = false; }
        

        if( $theme_override ){
            // if true, get nav_menu settings value
            $wpam_disable_override = get_field( 'wpam_menu_options_theme_overriding' , $menu );
        }

        // plus filter pour gérer ça ?
        if( isset( $wpam_disable_override ) && $wpam_disable_override === true ){
            $theme_override = false ;
        }

    }

    return $theme_override ;
}



/**
 * Load functions files of theme in the right order (origin ? template ? child ? etc)
 *
 * @since 0.1.0
 * 
 * @param array from wpam_get_theme_directories()
 * @param bool override allowed ?
 * 
 * @return void
 */
function wpam_load_theme_functions_files( $themes_directories, $override ){

    // built paths list
    $paths_list = array();
    
    // add if want override
    if( $override === true ){
        foreach( $themes_directories['overrides'] as $path ){
            array_push( $paths_list,  $path );
        }
    }

    
    // last CORE
    array_push( $paths_list,  $themes_directories['core'] );

    // Last templalte
    if( isset( $themes_directories['template']) ){
        array_push( $paths_list,  $themes_directories['template'] );
    }

    // Last origin
    if( isset( $themes_directories['origin']) ){
        array_push( $paths_list,  $themes_directories['origin'] );
    }

    // loop on + theme_id variable for mq support
    
    global $wpam_theme_id ;
    if( $wpam_theme_id == null ){
        $wpam_theme_id = 'none';
    }

    foreach( $paths_list as $index => $path ){

        // Tests and include
        if( file_exists ( $path . 'functions.php' ) ){
            include_once( $path . 'functions.php' );
        }
        
    }

    // unset($wpam_theme_id);
    
}



/**
 * Add & save array of themes in transients
 *
 * @since 0.1.0
 * 
 * @param array (array of themes)
 * 
 * @return void
 */
function wpam_save_themes_in_transient( $themes = false ){
    if( ! $themes ){ return false; }
    
    $wpam_transients   = get_transient( WPAM_TRANSIENTS_SLUG );
    $themes_merge      = array_merge( $wpam_transients['themes'], $themes );
    
    wpam_set_transient_value( 'themes', $themes_merge );
}



/**
 * Load element of theme wich need to be include
 * so, get informations, get directory and load functions
 *
 * @since 0.1.0
 * 
 * @param array of themes to be include (optionnal)
 * 
 * @return void
 */
function wpam_load_themes_includes( $wpam = false ){

    // var_dump('wpam_load_themes_includes');
    
    /*
    if( $wpam  === ''){
        $wpam = get_transient( WPAM_TRANSIENTS_SLUG );
        $wpam_themes = $wpam['themes'] ;

    }else{
        $wpam_themes = $wpam ;
        // update transient ?
    }
    */
    
    $wpam = get_transient( WPAM_TRANSIENTS_SLUG );
    $wpam_themes = $wpam['themes'] ;

    
    if( $wpam_themes == null ){ return; }

    if(  ( is_array($wpam_themes) || $wpam_themes instanceof Countable ) && count( $wpam_themes ) > 0 ){

        // remove double entries
        $loading_list = $wpam_themes;
        $loading_list = array_unique($loading_list, SORT_REGULAR);

        foreach( $loading_list as $menus ){

            if( ! property_exists( $menus, "theme_template") ){
                $menus->theme_template = $menus->theme_slug ;
            }

            if( ! property_exists( $menus, "theme_origin") ){
                $menus->theme_origin = '' ;
            }
            
            $dirs = wpam_get_theme_directories(
                $menus->theme_slug,
                $menus->theme_core,
                $menus->theme_template,
                $menus->theme_override,
                $menus->theme_origin
                );

            // includes and mark as loaded
            // var_dump( $dirs);
            wpam_load_theme_functions_files( $dirs, $menus->theme_override );
              
        }      
    }
}



/**
 * Load themes list given has param
 * load funciton files, enqueue script and save themes in transients - for custom load :=)
 *
 * @since 0.1.0
 * 
 * @param array themes list
 * 
 * @return void
 */
function wpam_load_themes( $wpam_themes = false ){
    

    if( !$wpam_themes ){ return false; }
    
    wpam_load_themes_includes( $wpam_themes );
    wp_enqueue_scripts();

    wpam_save_themes_in_transient( $wpam_themes );
}



/**
 * Get directory of a theme
 *
 * @since 0.1.0
 * 
 * @return string path of theme directory
 */
function wpam_get_themes_directory_uri(){


    return get_stylesheet_directory_uri() . '/' . WPAM_THEME_OVERIDING_FOLDER_PATH ;
}



/**
 * Get theme json from library
 *
 * @since 0.1.0
 * 
 * @param string theme slug
 * @param string theme core (wpam, plugin or theme)
 * 
 * @return object $theme json if OK or false
 */
function wpam_get_theme_library_values( $theme_slug = false, $theme_core = 'wpam' ){
    
    if( $theme_slug !== false ){

        // Transform bd into JSON
        $wpam_themes_db = get_option( WPAM_THEMES_LIST_SLUG ) ;
        
        $themes_json = array();

        foreach( $wpam_themes_db as $theme_identifier => $theme ){
            if( $theme->theme_slug === $theme_slug ){ return $theme; }
        }

    }else{
        return false ;
    }

}




/**
 * 
 * 
 *  @since 0.2.0
 */
function wpam_do_action_wpam_load_theme_options(){
    
    if( is_admin() ){
        do_action( 'wpam_load_theme_options' );
    }
}



/**
 * 
 * 
 * 
 * 
 *  @since 0.2.0
 */
// function wpam_set_theme_id( $wpam_theme_id, $theme_slug, $theme_json ){
function wpam_set_theme_id( $theme_slug, $theme_json ){

    // var_dump($wpam_theme_id);
    
    global $wpam_theme_id ;
    // var_dump( $wpam_theme_id );

    if( $wpam_theme_id === 'none' ){

        // global $wpam_theme_id ;
        $wpam_theme_id = wpam_get_theme_identifier( $theme_slug, $theme_json );
    }

    // return $wpam_theme_id ; 
}



/**
 * 
 * 
 *  @since 0.2.0
 */
function wpam_get_theme_id(){

    global $wpam_theme_id ;
    return $wpam_theme_id ;
}



/**
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_themes_styles( $file = null, $line = null ){
    
    if( function_exists('debug_backtrace') && $file === null){
        $file = debug_backtrace()[0]['file'] ;
    }

    if( function_exists('debug_backtrace') && $line === null){
        $line = debug_backtrace()[0]['line'] ;
    }

    global $wpam_styles_collector;

    if( $wpam_styles_collector == null ){
        $wpam_styles_collector = array();
    }

    $theme_name = wpam_extract_theme_name( $file );
    $loading_reference = 'wpam-loading-' . $theme_name . '-'  . $line ;
    
    return $loading_reference ;
}



/**
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_extract_theme_name( $file_path ){

    $theme_name = $file_path ;
    $theme_name = rtrim( $theme_name, "functions.php" );
    $theme_name = rtrim( $theme_name, '/');
    $split = preg_split("#/#", $theme_name); 
    $theme_name = $split[count( $split ) - 1] ;

    return $theme_name ;
}