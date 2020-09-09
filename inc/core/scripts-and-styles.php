<?php

defined( 'ABSPATH' ) or	die();



/**
 * 
 *
 * @since 0.1.0
 */
function wpam_register_style(
                $handle,
                $src   = '',
                $deps  = array(),
                $ver   = false,
                $media = 'all',
                $ui    = false,
                $load_with_theme_system = false
                ){


    // var_dump('wpam_register_style');

    // ADMIN : build UI if media is a simple word and ui arrays given
    if( is_admin() ){

        $media_tag_is_basic =  wpam_is_style_media_tag_basic( $media ) ;

        if( $ui && $media_tag_is_basic ){
            // var_dump('build ui');
            wpam_acf_build_mq_ui( $handle, $ui );
        }
    }

    
    // output
    if( ! is_admin() ){

        // If UI context, override $media output with db data
        $media_tag_is_basic =  wpam_is_style_media_tag_basic( $media ) ;
        
        if( $ui && $media_tag_is_basic ){
            // rewrite
            $wpam_transients = get_transient( WPAM_TRANSIENTS_SLUG );
            $wpam_menus = $wpam_transients['menus'];
            
            $theme_id = $ui['theme_id'];
            $name     = $ui['name'];
            $ranges   = $ui['ranges'];

            // save for later
            $media_tag_for_generated_css_file = $media ;

            // var_dump( $wpam_menus[ $theme_id ] );
            $media  = $media . ' and ';
            

            foreach( $ranges as $key => $range ){

                // collect
                if( isset( $range['property'] ) ){
                    $property = $range['property'];
                }

                if( isset( $range['unit'] ) ){
                    $unit = $range['unit'];
                }
                
                if( isset( $range['step'] ) ){
                $step = $range['step'];
                }else{ $step = ''; }

                if( isset( $range['values']['min'] ) ){
                $value_min = $range['values']['min'] ;
                }else{ $value_min = '' ; }

                if( isset( $range['values']['max'] ) ){
                $value_max = $range['values']['max'] ;
                }else{ $value_max = '' ; }

                if( isset( $range['values']['default'] ) ){
                $value_default = $range['values']['default'] ;
                }else{ $value_default = '' ; }

                $unique_id = wpam_get_ui_identifier_name( $theme_id, $handle, $name, $property ) ;

                // var_dump($args['menu']);
                // var_dump($ui['menu']);
                // var_dump($menu);
                // var_dump($wpam_menus);
                // var_dump($wpam_transients);
                
                // serach menu slug
                $menu_exist = false ;
                
                foreach( $wpam_menus as $menu_item ){

                    if( $menu_item[0] === $theme_id ){
                        $menu_exist = true ;
                        $menu = $menu_item[ 1 ] ; // menu obj
                    }
                }

                if( ! $menu_exist ){
                    $menu = (object) array() ;
                    $menu->slug = 'unknown';
                }

                $range_value = get_field( $unique_id, $menu );

                if( ! $range_value ){ $range_value = $range['values']['default'] ; }

                $mq  = '(' ;
                $mq .=  $property ;
                $mq .= ':' ;
                $mq .=  $range_value ;
                $mq .=  $unit ;
                $mq .= ')' ;

                $media .= $mq ;

                if( array_key_exists( $key+1 , $ranges) ){
                    $media .= ' and ';
                }
            }

        }

        // Register

        // Check if is MQ ?
        if( $ui && $media_tag_is_basic ){

            // MQ
            // Check, generate & write IF WPAM_DEV_MODE IS FALE
            
            if( WPAM_DEV_MODE === false ){


                // var_dump( $menu );

                $folder_path = wpam_check_if_generated_css_folder_exist( $menu->slug, $ui['theme_id'] );

                $wpam_stylesheet_name = sanitize_text_field( $handle ) . '.css';
                $has_css_file = wpam_check_if_generated_css_file_exist( $folder_path, $wpam_stylesheet_name );
                
                if( $has_css_file === false ){
                    $filename   = sanitize_text_field( $wpam_stylesheet_name );
                    wpam_generate_css_file( $media, $filename, $folder_path, $src );
                }

                // construct path and register generated file
                $path_of_generated_css = $folder_path . '/' . $wpam_stylesheet_name ;
                if ( ! function_exists( '\get_home_path' ) ) {
                    include_once ABSPATH . '/wp-admin/includes/file.php';
                }
                
                $home_path = get_home_path();
                $path_of_generated_css = '/' .str_replace( $home_path, '', $path_of_generated_css );

                // register in wpam_styles_collector
                if( $load_with_theme_system ){
                    // print_r('wpam theme loading');
                }else{
                    wp_register_style( $handle, $path_of_generated_css, $deps, $ver, $media_tag_for_generated_css_file );
                }

            // DEVMODE ON : delete generated file sand $media with MQ
            }else{

                // flush generated css files
                $upload_dir = wp_upload_dir();
                $path = trailingslashit( $upload_dir['basedir'] );
                wpam_dev_mode_flush_files( $path . 'wpam/' );
                
                /*
                unlink( $path . 'wpam/.htaccess' );
                rmdir( $path . 'wpam/' );
                */

                // register style with filter for next last print action
                if( $load_with_theme_system ){
                    // print_r('wpam theme loading');
                }else{
                    wp_register_style( $handle, $src, $deps, $ver, $media );
                }
            }


        }else{

            // NO MQ
            
            // register in wpam_styles_collector
            if( $load_with_theme_system ){
                // print_r('wpam theme loading');
            }else{
                wp_register_style( $handle, $src, $deps, $ver, $media );
            }

        } // endif ( $ui && $media_tag_is_basic )

        

    }

    
}



/**
 * 
 *  php delete function that deals with directories recursively
 *  @source : https://paulund.co.uk/php-delete-directory-and-files-in-directory
 * 
 *  @since 0.2.0
 */
function wpam_dev_mode_flush_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            wpam_dev_mode_flush_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}



/**
 * 
 *
 * @since 0.1.0
 */
function wpam_enqueue_style(
                $handle,
                $src   = '',
                $deps  = array(),
                $ver   = false,
                $media = 'all',
                $ui    = false
                ){

    // Depenencies loading systm    
    // var_dump( $deps );
    $query = 'wpam-loading-';
    $load_with_theme_system = false ;

    if( ! empty( $deps ) && ( substr( $deps[0], 0, strlen($query) ) === $query ) ){
        /*
        // clear
        $deps = array();

        // dont wp_register but  add to filter for loading order
        $load_with_theme_system = true;
        */

        // disable, try with wp_style
        $load_with_theme_system = false;
        
    }

    // wpam registration & build UI
    wpam_register_style( $handle, $src, $deps, $ver, $media, $ui, $load_with_theme_system );

    if( ! is_admin() ){
        // wp enqueue
        if( $load_with_theme_system ){

        }else{
            wp_enqueue_style( $handle, $src, $deps, $ver, $media );
        }
        
    }
    
}



/**
 * 
 *
 * @since 0.1.0
 * 
 */
function wpam_register_script( $handle, $src = '', $deps = array(), $ver = false, $in_footer = false  ){

    wp_register_script( $handle, $src, $deps, $ver, $in_footer);
    
}



/**
 * 
 *
 * @since 0.1.0
 */
function wpam_enqueue_script(
                $handle,
                $src = '',
                $deps = array(),
                $ver = false,
                $in_footer = false
                ){
    
    if( ! is_admin() ){
        
        // if( has_action('wp_enqueue_scripts') ){}

            // wpam register
            wpam_register_script( $handle, $src, $deps, $ver, $in_footer );
            
            // wp enqueue
            wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        
    }

}



/**
 * 
 * 
 * @since 0.1.0
 * 
 */
function wpam_is_style_media_tag_basic( $media ){
    
    // https://developer.mozilla.org/fr/docs/Web/CSS/Requ%C3%AAtes_m%C3%A9dia/Utiliser_les_Media_queries
    if( $media === 'all' || $media === 'print' || $media === 'screen' || $media === 'speech' ){
        return true ;
    }

    return false ;
}



/**
 * 
 * 
 *  @since 0.2.0
 */

function wpam_do_action_wpam_enqueue_scripts(){

    do_action( 'wpam_enqueue_scripts' );
}



/**
 * 
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_check_if_generated_css_folder_exist( $wpam_menu_slug, $wpam_theme_id  ){

    // MQ — @source : https://wp-mix.com/wordpress-create-upload-files-directories/
    // TODO : ADD HTACCESS
    
    // uploads
    $wp_upload_dir = wp_upload_dir();

    // wpam/
    $wpam_updaload_dir = $wp_upload_dir['basedir'] . '/' . 'wpam' ;
    if( ! file_exists( $wpam_updaload_dir ) ){ wp_mkdir_p( $wpam_updaload_dir ); };
    if( chmod( $wpam_updaload_dir, 0777) ) { chmod( $wpam_updaload_dir, 0755); }

    /*
    Order deny,allow
    Deny from all

    <Files ~ "\.(xml|css|jpe?g|png|gif|js|pdf)$">
    Allow from all
    </Files>

    <Files ~ "baz\.php$">
    Allow from all
    </Files>
    */
    // wpam/.htaccess
    $path_to_htaccess = $wpam_updaload_dir . '/' . '.htaccess' ;
    if( ! file_exists( $path_to_htaccess ) ){
        file_put_contents(
            $path_to_htaccess,
            '
            Order deny,allow
            Deny from all
        
            <Files ~ "\.(js|css)$">
            Allow from all
            </Files>

            '
        );
    }

    // wpam/dist/
    $wpam_dist_updload_dir = $wp_upload_dir['basedir'] . '/' . 'wpam' . '/' . 'dist';
    if( ! file_exists( $wpam_dist_updload_dir ) ){ wp_mkdir_p( $wpam_dist_updload_dir ); };
    if( chmod( $wpam_dist_updload_dir, 0777) ) { chmod( $wpam_dist_updload_dir, 0755); }

    // wpam/dist/menu-slug/
    $wpam_menu_updload_dir = $wp_upload_dir['basedir'] . '/' . 'wpam' . '/' . 'dist' . '/' . $wpam_menu_slug ;
    if( ! file_exists( $wpam_menu_updload_dir ) ){ wp_mkdir_p( $wpam_menu_updload_dir ); };
    if( chmod( $wpam_menu_updload_dir, 0777) ) { chmod( $wpam_menu_updload_dir, 0755); }


    // wpam/dist/menu-slug/theme-id/
    $wpam_theme_updload_dir = $wp_upload_dir['basedir'] . '/' . 'wpam' . '/' . 'dist' . '/' . $wpam_menu_slug . '/' . $wpam_theme_id ;
    if( ! file_exists( $wpam_theme_updload_dir ) ){ wp_mkdir_p( $wpam_theme_updload_dir ); };
    if( chmod( $wpam_theme_updload_dir, 0777) ) { chmod( $wpam_theme_updload_dir, 0755); }


    return $wpam_theme_updload_dir ;
}



/**
 * 
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_check_if_generated_css_file_exist( $folder_path, $file_name ){

    $file = $folder_path . '/' . $file_name ;
    
    if( ! file_exists( $file ) ){

        return false ;
    };

    return true;
}



/**
 * 
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_generate_css_file( $media, $filename, $folder_path, $src ){
    
    $deprecated = null;
    $time       = current_time('mysql');

    $bits  = "\n"  . "\n" . ' @media ' . $media . ' { ' . "\n" . "\n";

    if ( ! function_exists( 'get_home_path' ) ) {
        include_once ABSPATH . '/wp-admin/includes/file.php';
    }

    // to fix : doouble folder install path base 
    $home_path = get_home_path();
    
    // need to check if folder at the end and the begining
    $home_path_explode = explode('/',$home_path);
    $home_path_explode = array_filter( $home_path_explode );
    $home_path_explode = array_values( $home_path_explode );
    $home_path_explode = array_reverse( $home_path_explode );
    
    // $home_path_last_folder_slug = $home_path_explode[ ( count( $home_path_explode ) - 1 )  ] ;

    $file_base_path    = $home_path ;
    $file_relative_uri = wp_make_link_relative( $src ) ;

    $file_path_explode = explode('/',$file_relative_uri);
    $file_path_explode = array_filter( $file_path_explode );
    $file_path_explode = array_values( $file_path_explode );
    
    // compare and cut
    $i = 0 ;
    while(  array_key_exists( $i , $home_path_explode ) || array_key_exists( $i , $file_path_explode ) ){
        
        if( $home_path_explode[ 0 ] !== $file_path_explode[ 0 ] ){
            $i = null ;
            break ;
        }

        if( $home_path_explode[ $i ] !== $file_path_explode[ $i ] ){
            $i = $i - 1 ;
            break ;
        }
        $i = $i + 1 ;
    }
    
    $common_part = '' ;

    if( ! is_null( $i ) ){
        for( $k = 0; $k <= $i; $k ++ ){
            $common_part .= '/' .  $file_path_explode[ $k ] ;
        }
    }

    // path cleaning
    $file_relative_uri = preg_replace('/^' . preg_quote( $common_part, '/') . '/', '', $file_relative_uri);
    
    // check file
    $file_path = $file_base_path . $file_relative_uri ;
        
    while( strpos( ( $file_path = str_replace( '//', '/', $file_path ) ), '//' ) !== false );
    $bits .= file_get_contents( $file_path );

    $bits .= "\n" . "\n" .' } ';
    
    // upload with WordPress
    $upload = wp_upload_bits( $filename, $deprecated, $bits, $time );
    
    // then move file to folder
    // @source : https://tommcfarlin.com/uploading-files-to-a-custom-directory/
    // @source : https://thisinterestsme.com/move-file-php/

    if( $upload['error'] ==  false ){
        $source      = $upload['file'];
        $destination = $folder_path . '/' . $filename ;
        $fileMoved   = rename( $source, $destination );
    }
    
    if( $fileMoved ){

        return true ;

    }

    return false ;

}



/**
 * 
 * 
 * 
 * 
 * 
 */
/*
function wpam_get_generated_css_file_path(){

    return $path ;
}
*/


/**
 * 
 * 
 * 
 *  @since 0.2.0
 */
function wpam_styles_reorder() {

    // Used 2 times, for head and footer

    // @source ? : https://blog.calevans.com/2015/03/04/reordering-style-sheets-in-wordpress/
    // @hook   ? : http://rachievee.com/the-wordpress-hooks-firing-sequence/
    
    global $wp_styles;

    /*
    global $wpam_styles_printed ;

    if( $wpam_styles_printed === null){
        $wpam_styles_printed = array();
    }
    */

    $loading_current_order = array();

    foreach( array_values( $wp_styles->registered ) as $index => $style ){

        // test if has wpam-loading- ?
        $query = 'wpam-loading-';
        if( ! empty( $style->deps ) && ( substr( $style->deps[0], 0, strlen($query) ) === $query ) ){
            // yes
            array_push( $loading_current_order , array( $style->handle,  $style->deps[0] ) );
        }
    }

    // REORDER DEPS
    // redefine handle order

    $wpam_right_order = array() ;

    foreach( $loading_current_order as $style ){

        $handle = $style[0];
        $position  = $style[1];

        preg_match('/\d+$/', $position, $line );
        $line = $line[0] ;

        $loading_string = 'wpam-loading-';
        $position = str_replace( 'wpam-loading-' , '', $position );
        $position = str_replace( '-' . $line , '', $position );
        
        $line = (int) $line;
        $theme = $position ;

        // 
        if( array_key_exists( $theme, $wpam_right_order) === false ){
            $wpam_right_order[ $theme ] = array( array( $handle, $line ) ) ;
        }else{
            array_push( $wpam_right_order[ $theme ], array( $handle, $line ) );
        }
    
    }

    $wpam_right_order = array_reverse ( $wpam_right_order ) ;
    
    global $wpam_right_handle_order ;
    $wpam_right_handle_order = array();

    foreach( $wpam_right_order as $theme => $styles ){

        foreach( $styles as $style ){
            array_push( $wpam_right_handle_order, $style[0] );
        }

    }
    
    // queue
    foreach( $wpam_right_handle_order as $handle ) {
        
        $keyToSplice = array_search( $handle, array_values( $wp_styles->queue ) );

        if( $keyToSplice !== false && ! is_null( $keyToSplice ) ) {

            // move at the end
            $elementToMove = array_splice($wp_styles->queue,$keyToSplice,1);
            $wp_styles->queue[] = $elementToMove[0];

            // clear dep
            $wp_styles->registered[ $handle ]->deps = array() ;

            // make as printed
            // array_push( $wpam_styles_printed,  $handle );

        }
    }

}



/**
 * 
 * 
 * 
 * 
 */
function wpam_do_action_wpam_options_handler(){
    
    do_action( 'wpam_set_theme_options' );  
    
    /*
    // start loading options in nav-menu admin page
    if( is_admin() ){

        // do_action( 'wpam_load_theme_options' );
        do_action( 'wpam_set_theme_options' );
    }

    // if front -> default value handler
    if( ! is_admin() ){
        
        // front-end - handle default value ?
        // do_action( 'wpam_set_theme_options' );

    }
    */

}



/**
 * 
 * 
 */
 function wpam_set_theme_options_fields( $theme_id, $fields ){

    // var_dump('wpam_set_theme_options_fields');

    if( is_admin() ){

        wpam_add_theme_options_fields( $theme_id, $fields );
    }

    // front
    // default value ?
    if( ! is_admin() ){

        global $wpam_theme_default_value ;
        $wpam_theme_default_value = array();

        foreach( $fields as $field ){
            /*
            var_dump( $field['key'] );
            var_dump( $field['default_value'] );
            */
            $wpam_theme_default_value[$field['key']] = $field['default_value'];
            
        }
        
    }

 }