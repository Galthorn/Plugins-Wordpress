<?php
// create custom plugin settings menu
add_action('admin_menu', 'nme_optimizing_functions_loadMenu');
 
function nme_google_news_sitemap_loadMenu() {

    /** ==============================================================
     *  Check if main NME menu exists
     *  ============================================================*/           
    global $menu;
    $menuExist = false;
    $nme_menu_slug = "options-general.php"; // default value: under Settings

    foreach($menu as $item) {
        if(strtolower($item[0]) == strtolower('NME Plugins')) {
            $menuExist = true;
        }
    }
    if($menuExist) $nme_menu_slug = "nme-plugins-menu";
    /** ============================================================*/
    // Add a page to manage this plugin's settings
     add_submenu_page(
      $nme_menu_slug,
         'NME Google News Sitemap Generator Settings', 
         'NME Google News Sitemap Generator', 
         'manage_options', 
         __FILE__, 
         'nme_google_news_sitemap_settings_page'
     );

    //add_options_page('NME Optimizing Functions', 'Optimizing Functions', 'administrator', __FILE__, 'nme_optimizing_functions_settings_page');
    add_action( 'admin_init', 'register_nme_optimizing_functions_mysettings' );

    // Set default options
    add_option('nmeOptimizingFunctions_homeRefreshMin', '172');
    add_option('nmeOptimizingFunctions_homeRefreshMax', '193');
    add_option('nmeOptimizingFunctions_postsRefreshMin', '475');
    add_option('nmeOptimizingFunctions_postsRefreshMax', '495');

    //Rewrite .htaccess according to the option nmeOptimizingFunctions_removeWPInformations
    add_action('update_option_nmeOptimizingFunctions_removeWPInformations', 'nme_optimizing_functions_block_readme');

}
 

function register_nme_google_news_sitemap_mysettings() {
    //register our settings
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_enableHomeRefresh' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_homeRefreshMin' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_homeRefreshMax' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_enablePostsRefresh' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_postsRefreshMin' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_postsRefreshMax' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_excludePosts' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_excludeIP' );

    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_addBlank' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_enableShortcodeWidget' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_removeWPInformations' );
    register_setting( 'nme_optimizing_functions_settings', 'nmeOptimizingFunctions_modifyUploadedFileNames' );

}