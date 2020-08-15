<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
 
/**
 * Delete all options of WPAM plugin, and ACF fileds
 *
 */

// load defines & functions
require_once('defines.php');
require( WPAM_CORE_PATH . 'options.php' );
require( WPAM_CORE_PATH . 'transients.php' );

// Delete ACF fields registred in garbage collector

// get fields from garbage
$wpam_fields_registred_in_garbage = get_option( WPAM_GARBAGE_SLUG );

// Prepare fields with and without undescores (_)
$duplicate_field = true ;
if( $duplicate_field === true ){
    $wpam_garbage_of_field_to_find = array() ;
    foreach ( $wpam_fields_registred_in_garbage as $key => $value){
        array_push($wpam_garbage_of_field_to_find, $value );
        array_push($wpam_garbage_of_field_to_find, '_' . $value );
    }
}

// Build SQL request for fields deletation
global $wpdb;

// in postmeta table
$sql_request  = 'DELETE FROM '. $wpdb->prefix .'postmeta WHERE '.$wpdb->prefix.'postmeta.meta_key = ';

foreach ( $wpam_garbage_of_field_to_find as $key => $value){
    $sql_request .= '"'. $value .'"';
    if( array_key_exists( $key+1 , $wpam_garbage_of_field_to_find) ){
        $sql_request .= ' OR ' . $wpdb->prefix . 'postmeta.meta_key = ';
    }
}

$results = $wpdb->get_results( $sql_request , OBJECT );

// in termmeta table
$sql_request  = 'DELETE FROM '. $wpdb->prefix .'termmeta WHERE '.$wpdb->prefix.'termmeta.meta_key = ';

foreach ( $wpam_garbage_of_field_to_find as $key => $value){
    $sql_request .= '"'. $value .'"';
    if( array_key_exists( $key+1 , $wpam_garbage_of_field_to_find) ){
        $sql_request .= ' OR ' . $wpdb->prefix . 'termmeta.meta_key = ';
    }
}

$results = $wpdb->get_results( $sql_request , OBJECT );

// delete plugin options
wpam_delete_all_options();

// delete plugin transients
wpam_delete_all_transients();