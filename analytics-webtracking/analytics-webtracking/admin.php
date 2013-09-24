<?php
add_action('admin_menu', 'nme_analytics_webtracking_loadMenu');

function nme_analytics_webtracking_loadMenu() {
    /** ==============================================================
     *  Check if main NME menu exists
     *  ============================================================*/           
    global $menu;
    $menuExist = false;
    $nme_menu_slug = "options-general.php"; // default value: under Settings
    $nme_menu_title = "NME Analytics WebTracking";
    foreach($menu as $item) {
        if(strtolower($item[2]) == strtolower('nme-plugins-menu')) {
            $nme_menu_slug = "nme-plugins-menu";
            $nme_menu_title = "Analytics WebTracking";
        }
    }
    
    /** ============================================================*/
    // Add a page to manage this plugin's settings
    
    add_submenu_page(
        $nme_menu_slug,
        'NME Analytics WebTracking Settings', 
        $nme_menu_title, 
        'manage_options', 
        'nme-analytics-webtracking', 
        'nme_analytics_webtracking_settings_page'
    );
    add_action( 'admin_init', 'register_nme_analytics_webtracking_mysettings' );

	//Add default options for plugin
	add_option('analyticswebtracking_includeTestMode', 'on');
	add_option('analyticswebtracking_BTtitleClass', 'section-title');

	
}

function register_nme_analytics_webtracking_mysettings() {
    //register our settings
    register_setting( 'nme_analytics_webtracking_settings_general', 'analyticswebtracking_includeTestMode' );
    register_setting( 'nme_analytics_webtracking_settings_general', 'analyticswebtracking_includeAnalytics' );
    register_setting( 'nme_analytics_webtracking_settings_general', 'analyticswebtracking_analyticsID' );
    register_setting( 'nme_analytics_webtracking_settings_general', 'analyticswebtracking_analyticsDomainName' );

    register_setting( 'nme_analytics_webtracking_settings_various_tracking', 'analyticswebtracking_includeAdblock' );
    register_setting( 'nme_analytics_webtracking_settings_various_tracking', 'analyticswebtracking_includeSearchTracking' );
    register_setting( 'nme_analytics_webtracking_settings_various_tracking', 'analyticswebtracking_STpostClass' );
    register_setting( 'nme_analytics_webtracking_settings_various_tracking', 'analyticswebtracking_includeCategoryTracking' );
    register_setting( 'nme_analytics_webtracking_settings_various_tracking', 'analyticswebtracking_includeTagTracking' );

    register_setting( 'nme_analytics_webtracking_settings_block_tracking', 'analyticswebtracking_includeBlockTracking' );
    register_setting( 'nme_analytics_webtracking_settings_block_tracking', 'analyticswebtracking_BTcontentID' );
    register_setting( 'nme_analytics_webtracking_settings_block_tracking', 'analyticswebtracking_BTtitleClass' );
    register_setting( 'nme_analytics_webtracking_settings_block_tracking', 'analyticswebtracking_BTcustomCode' );
}

function nme_analytics_webtracking_settings_page() { 
?>
<div class="wrap">
    <h2><?php _e('Analytics WebTracking','springfield'); ?></h2>
    <?php settings_errors(); ?>  
          
    <?php  if( isset( $_GET[ 'tab' ] ) ) {  $active_tab = $_GET[ 'tab' ];  } 
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';?>  
          
    <h2 class="nav-tab-wrapper">  
        <a href="?page=nme-analytics-webtracking&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General','springfield'); ?></a>  
        <a href="?page=nme-analytics-webtracking&tab=various_tracking" class="nav-tab <?php echo $active_tab == 'various_tracking' ? 'nav-tab-active' : ''; ?>"><?php _e('Various Tracking','springfield'); ?></a>  
        <a href="?page=nme-analytics-webtracking&tab=block_tracking" class="nav-tab <?php echo $active_tab == 'block_tracking' ? 'nav-tab-active' : ''; ?>"><?php _e('Block Tracking','springfield'); ?></a>  
    </h2>   

    <form method="post" action="options.php" id="options">


        <?php if( $active_tab == 'general' ) {  ?>
            <?php settings_fields('nme_analytics_webtracking_settings_general'); ?>
            <?php do_settings_sections('nme_analytics_webtracking_settings_general'); ?>
            <h3><?php _e('Test Mode', 'springfield'); ?></h3>
            <table id="general" class="form-table">
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeTestMode" <?php echo (get_option('analyticswebtracking_includeTestMode') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Test Mode ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <small><?php _e('Test Mode display data in console instead of sending them to analytics, it\'s usefull to test custom code in Block Tracking. Otherwise all the datas are send to analytics in the Event Section (Content -> Events -> Top Events)', 'springfield'); ?></small>
                    </td>
                </tr>
            </table>
            <h3><?php _e('Include Analytics', 'springfield'); ?></h3>
            <table class="form-table">
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeAnalytics" <?php echo (get_option('analyticswebtracking_includeAnalytics') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Analytics ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <input type="text" name="analyticswebtracking_analyticsID" value="<?php echo get_option('analyticswebtracking_analyticsID'); ?>" /> <?php _e('Profil ID of the analytics to include', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <input type="text" name="analyticswebtracking_analyticsDomainName" value="<?php echo get_option('analyticswebtracking_analyticsDomainName'); ?>" /> <?php _e('Domain name of the analytics to include', 'springfield'); ?>
                    </td>
                </tr>
            </table>
            <?php 

        } else if( $active_tab == 'various_tracking' ) { ?>
             <?php settings_fields('nme_analytics_webtracking_settings_various_tracking'); ?>
            <?php do_settings_sections('nme_analytics_webtracking_settings_various_tracking'); ?>
            <h3><?php _e('AdBlock Tracking', 'springfield'); ?></h3>
            <table id="various_tracking" class="form-table">
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeAdblock" <?php echo (get_option('analyticswebtracking_includeAdblock') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Adblock Tracking ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <small><?php _e('This detect if adblock is use or not and push data in events section of analytics (AdblockTracking)', 'springfield'); ?></small>
                    </td>
                </tr>
            </table>
            <h3><?php _e('Category & Tags Tracking', 'springfield'); ?></h3>
            <table class="form-table">
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeCategoryTracking" <?php echo (get_option('analyticswebtracking_includeCategoryTracking') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Category Tracking ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeTagTracking" <?php echo (get_option('analyticswebtracking_includeTagTracking') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Tag Tracking ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <small><?php _e('Each time a news is display, this add +1 in events section of analytics for each tags and category (CategoryTagTracking)', 'springfield'); ?></small>
                    </td>
                </tr>
            </table>
            <h3><?php _e('Search Tracking', 'springfield'); ?></h3>
            <table class="form-table">
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeSearchTracking" <?php echo (get_option('analyticswebtracking_includeSearchTracking') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Search Tracking ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <input type="text" name="analyticswebtracking_STpostClass" value="<?php echo get_option('analyticswebtracking_STpostClass'); ?>" /> <?php _e('Class of the post results in search page (ex : ".post" for Gizmodo.fr)', 'springfield'); ?>
                    </td>
                </tr>
            </table> <?php 
        } else { ?>
         <?php settings_fields('nme_analytics_webtracking_settings_block_tracking'); ?>
            <?php do_settings_sections('nme_analytics_webtracking_settings_block_tracking'); ?>
            <h3><?php _e('Block Tracking', 'springfield'); ?></h3>
            <table id="block_tracking" class="form-table">
                <tr valign="top"> 
                    <td>
                        <input type="checkbox" name="analyticswebtracking_includeBlockTracking" <?php echo (get_option('analyticswebtracking_includeBlockTracking') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable Block Tracking ?', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <input type="text" name="analyticswebtracking_BTcontentID" value="<?php echo get_option('analyticswebtracking_BTcontentID'); ?>" /> <?php _e('ID or class of the content(s) to track (ex : "#middle-wrap a" for ITespresso.fr)', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <input type="text" name="analyticswebtracking_BTtitleClass" value="<?php echo get_option('analyticswebtracking_BTtitleClass'); ?>" /> <?php _e('Class of the titles of blocks (ex : ".section-title" for ITespresso.fr) in a comma separated list if you want to set multiple cases of class', 'springfield'); ?>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <label><?php _e('Add custom tracking code', 'springfield'); ?></label>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <textarea rows="6" cols="150" name="analyticswebtracking_BTcustomCode"><?php echo get_option('analyticswebtracking_BTcustomCode'); ?></textarea>
                    </td>
                </tr>
                <tr valign="top"> 
                    <td>
                        <small><?php _e('This detect all clicks in content, organize it by block and push data in events section of analytics (BlockTracking).<br />
                            It captures all clicks in the content class or ID you set above, by default, the script try to catch the title (with the class you set above) of the block (if it exists) and update this title on analytics. Otherwise you must filter clicks with a Jquery selector and insert a value in the var "blockTitle" to categorize the click in analytics. 
                            <br />
                            Exemple (on ITespresso) : <br />', 'springfield'); ?>
                            <pre>
                                if(jQuery(this).parents().hasClass('widget-listing-posts')) {
                                    blockTitle = 'Listing Post';
                                }
                                else if(jQuery(this).parents().hasClass('more_post')) { 
                                    blockTitle = jQuery(this).parents().children(".last_title").text();
                                }
                                else if(jQuery(this).parents().hasClass('yarpp-related')) { 
                                    blockTitle = jQuery(this).parents().children("h3").text();
                                }
                                else if($(this).parents().hasClass('tags')) { 
                                    blockTitle = 'Tags';
                                }
                                else if($(this).parents().hasClass('entry-content')) { 
                                    blockTitle = 'Content links';
                                }
                            </pre>
                        </small>
                    </td>
                </tr>
            </table><?php
        } 

        submit_button(); ?>  
              
     </form>  
          
</div><!-- /.wrap -->  
 

<?php }?> 