<?php



defined( 'ABSPATH' ) or	die();

// Plugin names
define( 'WPAM_PLUGIN_NAME'                 , 'WP Advanced Menu' );
define( 'WPAM_PLUGIN_SLUG'                 , sanitize_key( WPAM_PLUGIN_NAME ) );

// Plugin folder
define( 'WPAM_PLUGIN_FOLDER_INSTALL'       , plugin_dir_path( __DIR__ ) );

// Path base
define( 'WPAM_FILE'                        , __FILE__ );
define( 'WPAM_PATH'                        , realpath( plugin_dir_path( WPAM_FILE ) ) . '/' );

// Folders
define( 'WPAM_INC_PATH'                    , realpath( WPAM_PATH . 'inc' )    . '/' );
define( 'WPAM_THEMES_PATH'                 , realpath( WPAM_PATH . 'themes' ) . '/' );

// Plugin version
if( ! function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
$wpam_plugin_data = get_plugin_data( WPAM_PATH . 'wp-advanced-menu.php', false, false ) ;
define( 'WPAM_VERSION'                     , $wpam_plugin_data['Version'] );

// Inc
define( 'WPAM_ADMIN_PATH'                  , realpath( WPAM_INC_PATH . 'admin' )               . '/' );
define( 'WPAM_CORE_PATH'                   , realpath( WPAM_INC_PATH . 'core' )                . '/' );
// define( 'WPAM_LIB_PATH'                    , realpath( WPAM_INC_PATH . 'lib' )                 . '/' );
define( 'WPAM_WALKER_PATH'                 , realpath( WPAM_INC_PATH . 'walkers' )             . '/' );
define( 'WPAM_LEGACY_MENU_METABOX'         , realpath( WPAM_INC_PATH . 'legacy-menu-metabox' ) . '/' );

// Composr & npm
define( 'WPAM_VENDOR_PATH'                 , realpath( WPAM_PATH . 'vendor' )       . '/' );
define( 'WPAM_NODE_MODULES'                , realpath( WPAM_PATH . 'node_modules' ) . '/' );

// ACF Build-in
/*
define( 'WPAM_WPACKAGIST'                  , realpath( WPAM_VENDOR_PATH . 'wpackagist-plugin' ) . '/' );
define( 'WPAM_ACF_BUILDIN'                 , realpath( WPAM_WPACKAGIST  . 'advanced-custom-fields' ) . '/' );
define( 'WPAM_ACF_BUILDIN_INC'             , realpath( WPAM_ACF_BUILDIN . 'includes' )    . '/' );

define( 'WPAM_ACF_BUILDIN_NAV_WALKERS'     , realpath( WPAM_ACF_BUILDIN_INC . 'walkers' )    . '/' );
define( 'WPAM_ACF_BUILDIN_FORMS'           , realpath( WPAM_ACF_BUILDIN_INC . 'forms' )    . '/' );
*/

// Options
define( 'WPAM_GENERAL_SETTINGS_SLUG'       , 'wpam_general_settings' );
define( 'WPAM_THEMES_LIST_SLUG'            , 'wpam_themes_list' );
define( 'WPAM_GARBAGE_SLUG'                , 'wpam_garbage' );

// ACF
define( 'WPAM_ACF_PREFIX_GROUP'            , 'group_acf_key_' );
define( 'WPAM_ACF_PREFIX_FIELD'            , 'field_acf_key_' );

// Transients
define( 'WPAM_TRANSIENTS_SLUG'             , 'wpam_transients' );

// Dev mode
if( ! defined('WPAM_DEV_MODE') ) { define( 'WPAM_DEV_MODE' , false ); }

// Feature
define( 'WPAM_SUPPORT_CACHE'               , false );

// Theme
define( 'WPAM_THEME_ID_FALLBACK'           , 'wpam-wp-theme' );

if( ! defined('WPAM_THEME_OVERIDING_FOLDER') ) { define( 'WPAM_THEME_OVERIDING_FOLDER' , 'wpam' ); }

define( 'WPAM_THEME_OVERIDING_FOLDER_PATH' , WPAM_THEME_OVERIDING_FOLDER . '/' . 'overrides' . '/' );