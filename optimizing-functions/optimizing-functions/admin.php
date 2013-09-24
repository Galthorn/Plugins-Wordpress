<?php
// create custom plugin settings menu
add_action('admin_menu', 'nme_optimizing_functions_loadMenu', 5);
 
function nme_optimizing_functions_loadMenu() {
    /** ============================================================*/
    // Add a page to manage this plugin's settings
     add_menu_page(
         'NME Optimizing Functions', 
         'NME Settings', 
         'manage_options', 
         'nme-plugins-menu', 
         'nme_optimizing_functions_settings_page',
         plugins_url( 'nme-optimizing-functions/nme-picto.png' )
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
 

function register_nme_optimizing_functions_mysettings() {
    //register our settings
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_enableHomeRefresh' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_homeRefreshMin' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_homeRefreshMax' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_enablePostsRefresh' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_postsRefreshMin' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_postsRefreshMax' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_excludePosts' );
    register_setting( 'nme_optimizing_functions_settings_refresh', 'nmeOptimizingFunctions_excludeIP' );

    register_setting( 'nme_optimizing_functions_settings_others', 'nmeOptimizingFunctions_addBlank' );
    register_setting( 'nme_optimizing_functions_settings_others', 'nmeOptimizingFunctions_enableShortcodeWidget' );
    register_setting( 'nme_optimizing_functions_settings_others', 'nmeOptimizingFunctions_removeWPInformations' );
    register_setting( 'nme_optimizing_functions_settings_others', 'nmeOptimizingFunctions_modifyUploadedFileNames' );
    register_setting( 'nme_optimizing_functions_settings_others', 'nmeOptimizingFunctions_keepComment' );

}
 
function nme_optimizing_functions_settings_page() {
?>

<div class="wrap">  
      
        <h2><?php _e('Optimizing Functions','springfield'); ?></h2>
        <?php settings_errors(); ?>  
          
        <?php  if( isset( $_GET[ 'tab' ] ) ) {  $active_tab = $_GET[ 'tab' ];  } 
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'refresh';?>  
          
        <h2 class="nav-tab-wrapper">  
            <a href="?page=nme-plugins-menu&tab=refresh" class="nav-tab <?php echo $active_tab == 'refresh' ? 'nav-tab-active' : ''; ?>"><?php _e('Refresh','springfield'); ?></a>  
            <a href="?page=nme-plugins-menu&tab=others" class="nav-tab <?php echo $active_tab == 'others' ? 'nav-tab-active' : ''; ?>"><?php _e('Others','springfield'); ?></a>  
        </h2>  
          
        <form method="post" action="options.php">  
  
             <?php  
      

                if( $active_tab == 'refresh' ) {  

                settings_fields('nme_optimizing_functions_settings_refresh');
                do_settings_sections('nme_optimizing_functions_settings_refresh');
                    ?><table id="refresh" class="form-table">
                        <tr valign="top">
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_enableHomeRefresh" <?php echo (get_option('nmeOptimizingFunctions_enableHomeRefresh') == 'on') ? "checked='checked'" : ""; ?>/> 
                                <?php _e('Enable Refresh on Homepage ?', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <?php _e('From', 'springfield'); ?> <input type="text" name="nmeOptimizingFunctions_homeRefreshMin" size="3" value="<?php echo get_option('nmeOptimizingFunctions_homeRefreshMin') ?>" /> 
                                <?php _e('to', 'springfield'); ?> <input type="text" name="nmeOptimizingFunctions_homeRefreshMax" size="3" value="<?php echo get_option('nmeOptimizingFunctions_homeRefreshMax') ?>" />  <?php _e('(in seconds)', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_enablePostsRefresh" <?php echo (get_option('nmeOptimizingFunctions_enablePostsRefresh') == 'on') ? "checked='checked'" : ""; ?>/> 
                                <?php _e('Enable Refresh on Post Pages ?', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <?php _e('From', 'springfield'); ?> <input type="text" name="nmeOptimizingFunctions_postsRefreshMin" size="3" value="<?php echo get_option('nmeOptimizingFunctions_postsRefreshMin') ?>" /> 
                                <?php _e('to', 'springfield'); ?> <input type="text" name="nmeOptimizingFunctions_postsRefreshMax" size="3" value="<?php echo get_option('nmeOptimizingFunctions_postsRefreshMax') ?>" /> <?php _e('(in seconds)', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <h4><?php _e('Exclude Posts ?', 'springfield'); ?></h4>
                                <input type="text" name="nmeOptimizingFunctions_excludePosts" value="<?php echo get_option('nmeOptimizingFunctions_excludePosts') ?>" /> 
                                <br /><?php _e('Insert ID of posts to exclude in a comma separated list', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <h4><?php _e('Exclude IP ?', 'springfield'); ?></h4>
                                <input type="text" name="nmeOptimizingFunctions_excludeIP" value="<?php echo get_option('nmeOptimizingFunctions_excludeIP') ?>" /> 
                                <br /><?php _e('Insert IP to exclude in a comma separated list ', 'springfield'); ?>
                                <small><?php _e('Your IP is : ', 'springfield'); echo $_SERVER['REMOTE_ADDR']; ?></small>
                            </td>
                        </tr>
                    </table><?php 

                } else {   
                    settings_fields('nme_optimizing_functions_settings_others');
                do_settings_sections('nme_optimizing_functions_settings_others');
                ?>

                    <table id="others" class="form-table">
                        <tr valign="top"> 
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_addBlank" <?php echo (get_option('nmeOptimizingFunctions_addBlank') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Add target blank on external links ?', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_enableShortcodeWidget" <?php echo (get_option('nmeOptimizingFunctions_enableShortcodeWidget') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Enable shortcode in text widget ?', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_removeWPInformations" <?php echo (get_option('nmeOptimizingFunctions_removeWPInformations') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Remove wordpress version in sourcecode ?', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_modifyUploadedFileNames" <?php echo (get_option('nmeOptimizingFunctions_modifyUploadedFileNames') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Remove accents on media url when uploading ?', 'springfield'); ?>
                            </td>
                        </tr>
                        <tr valign="top"> 
                            <td>
                                <input type="checkbox" name="nmeOptimizingFunctions_keepComment" <?php echo (get_option('nmeOptimizingFunctions_keepComment') == 'on') ? "checked='checked'" : ""; ?>/> <?php _e('Keep current comment when page refresh ?', 'springfield'); ?>
                            </td>
                        </tr>
                    </table><?php


                } // end if/else  
                  
                submit_button();  
                  
            ?>  
              
        </form>  
          
    </div><!-- /.wrap -->  
 

<?php } ?>