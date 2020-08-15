<?php
/**
 * 
 * 
 * 
 * 
 * 
 * 
 */

/**
 * Prepare theme style url
 * 
 */

 $wpam_theme_style_url = '';

if( $theme->theme_core->theme_location === 'wpam' ){
    $wpam_theme_style_url =  plugins_url( 'themes/' . $theme->theme_slug . '/style.css' , WPAM_THEMES_PATH ) ;
}

if( $theme->theme_core->theme_location === 'plugin' ){
    $wpam_theme_style_url = plugins_url() . '/' . $theme->theme_core->theme_folder_path . $theme->theme_slug . '/style.css' ;
}

if( $theme->theme_core->theme_location === 'theme' ){
    $wpam_theme_style_url = '' ;
}

?>
<div class="wpam-admin-theme" data-wpam-theme-style-url="<?php echo $wpam_theme_style_url ; ?>">
    <h4 class="wpam-admin-theme-title"><?php echo $theme->theme_name ; ?></h4>
    <p class="wpam-admin-theme-description"><?php echo $theme->theme_description ; ?></p>
</div>