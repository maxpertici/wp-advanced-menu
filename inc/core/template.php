<?php

defined( 'ABSPATH' ) or	die();



/**
 * Build array path in function of core context, overrindg value, theme and child theme.
 *
 * @since 0.1.0
 * 
 * @param array $args (see in walker)
 * @param array $directories array of directory's path
 * @param string $tempalte element to find (ul, li, item...)
 * 
 * @return array of theme path folder
 */
function wpam_get_template_path( $args , $directories, $template  ){

    // Path dirs order
    $theme_dirs = array();

    // construct dirs list
    if( $directories['overrides'] !== null ) {
        foreach( $directories['overrides'] as $dir ){
            array_push( $theme_dirs, $dir );
        }
    }

    if( $directories['core'] !== null ) {
        array_push( $theme_dirs, $directories['core'] );
    }

    if( $directories['template'] !== null ){
        array_push( $theme_dirs, $directories['template'] );
    }

    if( $directories['origin'] !== null ){
        array_push( $theme_dirs, $directories['origin'] );
    }

    $theme_paths = array();

    foreach ( $theme_dirs as $dir ){
        
        $subtree_paths = wpam_get_template_subtree_path( $args, $template );

        // loop in subtree and buitl array of path
        foreach( $subtree_paths as $subtree_path ){
            $path = $dir . $subtree_path ;
            array_push( $theme_paths, $path );    
        }
    }
    
    return $theme_paths ;
}



/**
 * Build array of subtree theme path
 * in function of element asked, depth and child, override and origin context
 *
 * @since 0.1.0
 * 
 * @param array $args (from walker)
 * @param string template to find (ul, li, item...) 
 * 
 * @return array of path
 */
function wpam_get_template_subtree_path( $args, $template ){
    
    $deph = $args->walker->get_item_deph() ;

    if( 'ul-start' ===  $template || 'ul-stop' ===  $template  ){

        $ul_paths = array();
        if( $template === 'ul-start' ){ $deph += 1; }

        while( $deph >= 0 ){
            $number = sprintf("%02d", $deph );
            array_push( $ul_paths, 'ul/' .$number .'/' );
            $deph -= 1;
        }
        array_push( $ul_paths, 'ul/' );
        
        return $ul_paths ;
    }

    if( 'li-start' ===  $template || 'li-stop' ===  $template  ){
        $li_paths = array();

        while( $deph >= 0 ){
            $number = sprintf("%02d", $deph );
            array_push( $li_paths, 'li/' . $number .'/' );
            $deph -= 1;
        }
        array_push( $li_paths, 'li/' );
        
        return $li_paths ;
    }
    
    if( 'item' ===  $template ){
        $item_paths = array();

        while( $deph >= 0 ){
            $number = sprintf("%02d", $deph );
            array_push( $item_paths, 'item/' . $number .'/' );
            $deph -= 1;
        }
        array_push( $item_paths, 'item/' );
        
        return $item_paths ;
    }
}



/**
 * Build array of path for loading file
 * in function of element
 *
 * @since 0.1.0
 * 
 * @param array $args (from walker)
 * @param string $template to find (ul, li , item...)
 * 
 * @return array of paths
 */
function wpam_get_template_target( $args, $template ){
   

    if( 'ul-start' ===  $template ){
        $ul_start = array();
        array_push( $ul_start, 'ul-start' );
        return $ul_start;
    }

    if( 'ul-stop' ===  $template  ){
        $ul_stop = array();
        array_push( $ul_stop, 'ul-stop' );
        return $ul_stop;
    }

    if( 'li-start' ===  $template ){
        $li_start = array();
        array_push( $li_start, 'li-start' );
        return $li_start;
    }

    if( 'li-stop' ===  $template  ){
        $li_stop = array();
        array_push( $li_stop, 'li-stop' );
        return $li_stop;
    }

    if( 'item' ===  $template ){
        $item = wpam_get_template_target_item_array( 'item', $args );
        return $item;
    }

}



/**
 * Build array of paht for including item
 * un funciton of item's nature (tax, archive, etc.)
 *
 * @since 0.1.0
 * 
 * @param string $file_base : fallback file to find, ex: item
 * @param array $args (from walker)
 * 
 * @return array of file's names
 */
function wpam_get_template_target_item_array( $file_base, $args ){

    $files_array = array();
    
    // add in fct of type type
    // $item   = $args->wpam_item ;
    $item   = $args->wpam->wp_item ;

    $type   = $item->type ;
    $object = $item->object ;
    
    $id     = $item->object_id ;
    $id     = $item->db_id ;

    // Taxonomy
    if( $type === 'taxonomy' ){

        // slug
        $slug = get_term_by( 'id', $id, $object)->slug;
        
        array_push(
            $files_array,

            'taxonomy-' . $file_base . '-' . $object . '-' . $slug ,
            'taxonomy-' . $file_base . '-' . $object ,
            'taxonomy-' . $file_base . '-' . $slug ,
            'taxonomy-' . $file_base . '-' . $id ,
            'taxonomy-' . $file_base
        );

    }elseif( $type === 'post_type_archive' ){

        array_push(
            $files_array,

            'archive-' . $file_base . '-' . $object ,
            'archive-' . $file_base
        );
    }
    
    
    if( $type === 'post_type'){

        // Slug
        $post_obj = get_post($id);
        $slug = $post_obj->post_name ;
                
        array_push(
            $files_array,
            
            $object . '-' . $file_base . '-' . $slug ,
            $object . '-' . $file_base . '-' . $id ,
            $object . '-' . $file_base ,
            
            $file_base . '-' . $slug ,
            $file_base . '-' . $id
        );
    }

    if( $type === 'custom'){

        
        $wpam_custom_item_type = get_post_meta( $id , '_wpam_custom_item_type' , true );

        // include( WPAM_LEGACY_MENU_METABOX . 'item-types/wpam-item-types-config.php' ) ;
        include( WPAM_CORE_PATH . 'item-types.php' ) ;
        
        if( $wpam_custom_item_type != '' && array_key_exists( $wpam_custom_item_type, $wpam_custom_menu_item_spec) ) {

            array_push(
                $files_array,

                $wpam_custom_item_type . '-' . $file_base . '-' . $id ,
                $wpam_custom_item_type . '-' . $file_base ,
                /*
                $type . '-' . $file_base . '-' . $id ,
                $type . '-' . $file_base ,
                */
                $file_base . '-' . $id
            );

        }
    }

    /**
     * Filters $files_array
     */
    $files_array = apply_filters( 'wpam_filter_template_include', $files_array , $args );
    
    // Item
    array_push( $files_array, $file_base );

    return $files_array ;
}



/**
 * Get template file
 * fetch of combinaison from $aths and $template
 *
 * @since 0.1.0
 * 
 * @param array $ars (from walker)
 * @param array $paths theme paths from wpam_get_template_path()
 * @param string template name ot find (ex: item)
 * 
 * @return string fielpath of the forst element find
 */
function wpam_get_template_file( $args, $paths, $template ){

    $files_targets = wpam_get_template_target( $args, $template );
    
    foreach( $files_targets as $file_name ){

        foreach( $paths as $path ){
            $file_path = $path . $file_name . '.php' ;

            if( file_exists( $file_path ) ){
                return $file_path ;
            }
        } 
    }
}