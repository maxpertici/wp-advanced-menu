<?php

defined( 'ABSPATH' ) or	die();


function wpam_delete_all_options(){
    delete_option( WPAM_GENERAL_SETTINGS_SLUG );
    delete_option( WPAM_THEMES_LIST_SLUG );
    delete_option( WPAM_GARBAGE_SLUG );
}