<?php
// create custom plugin settings menu
add_action('admin_menu', 'nme_seo_functions_loadMenu');
 
function nme_seo_functions_loadMenu() {
    /** ==============================================================
     *  Check if main NME menu exists
     *  ============================================================*/           
    global $menu;
    $menuExist = false;
    $nme_menu_slug = "options-general.php"; // default value: under Settings
    $nme_menu_title = "NME SEO Functions";
    foreach($menu as $item) {
        if(strtolower($item[2]) == strtolower('nme-plugins-menu')) {
            $nme_menu_slug = "nme-plugins-menu";
            $nme_menu_title = "SEO Functions";
        }
    }
    /** ============================================================*/
    // Add a page to manage this plugin's settings
     add_submenu_page(
        $nme_menu_slug,
        'NME SEO Functions Settings', 
        $nme_menu_title, 
        'manage_options', 
        'nme-seo-functions', 
        'nme_seo_functions_settings_page'
     );
   //add_options_page('NME SEO Functions', 'SEO Functions', 'administrator', __FILE__, 'nme_seo_functions_settings_page');
    add_action( 'admin_init', 'register_nme_seo_functions_mysettings' );
}
function register_nme_seo_functions_mysettings() {
    //register our settings
    register_setting( 'nme_seo_functions_settings', 'nmeSEOFunctions_formatTitle' );
    register_setting( 'nme_seo_functions_settings', 'nmeSEOFunctions_addKeywordsTags' );
    register_setting( 'nme_seo_functions_settings', 'nmeSEOFunctions_removeYoastCanonical' );
    register_setting( 'nme_seo_functions_settings', 'nmeSEOFunctions_addRelLinks' );
}
function nme_seo_functions_settings_page() {
?>
<div class="wrap">
<h2><?php _e('SEO Functions','springfield'); ?></h2>
<form method="post" action="options.php">
    <?php settings_fields('nme_seo_functions_settings'); ?>
    <?php do_settings_sections('nme_seo_functions_settings'); ?>
    <h3><?php _e('Miscellaneous','springfield'); ?></h3>
    <table class="form-table">
        <tr valign="top"> 
            <td>
                <input type="checkbox" name="nmeSEOFunctions_formatTitle" <?php echo (get_option('nmeSEOFunctions_formatTitle') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Format title in head of the document ? (This format the title for home and listing pages). <b>Only if you cannot use WP SEO</b>', 'springfield'); ?>
            </td>
        </tr>
        <tr valign="top"> 
            <td>
                <input type="checkbox" name="nmeSEOFunctions_addKeywordsTags" <?php echo (get_option('nmeSEOFunctions_addKeywordsTags') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Add meta keywords tags on single pages ?', 'springfield'); ?>
            </td>
        </tr>
    </table>
    
    <h3><?php _e('YOAST Hack (only if WP SEO is install)','springfield'); ?></h3>
    <table class="form-table">
        <tr valign="top"> 
            <td>
                <input type="checkbox" name="nmeSEOFunctions_removeYoastCanonical" <?php echo (get_option('nmeSEOFunctions_removeYoastCanonical') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Remove canonical tags and meta robots generate by YOAST ? (on pages : Admin, Tag, Category, Date, Author, Paged)', 'springfield'); ?>
            </td>
        </tr>
        <tr valign="top"> 
            <td>
                <input type="checkbox" name="nmeSEOFunctions_addRelLinks" <?php echo (get_option('nmeSEOFunctions_addRelLinks') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Add rel link next on homepage even if YOAST delete it ?', 'springfield'); ?>
            </td>
        </tr>
         <tr valign="top"> 
            <td>
                <?php _e('Note : You can enable opengraph and opentweet in Yoast, go to "Social Network" submenu in Wordpress SEO and enable it.','springfield'); ?>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>
<?php } ?>