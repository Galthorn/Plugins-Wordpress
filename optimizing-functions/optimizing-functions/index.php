<?php
/*
Plugin Name: NME Optimizing Functions
Plugin URI: http://www.netmediaeurope.com
Description: Add various functions to improve site : target blank on all external links, allow shortcode in text widget, remove wordpress version in source code and block the readme file access, remove accents on media url when uploading, add refresh on selected pages, keep comment when page refresh.
Version: 1.0.1
Author: JL Marzio (NME)
Author URI: http://www.netmediaeurope.com
*/

if(is_admin()){
	$dir = plugin_dir_path( __FILE__ );
	include $dir.'/admin.php';
}

// Add target blank on externals links
function nme_optimizing_functions_add_blank_script() {
    // get domain name
    $this_domain = get_option('siteurl');
    ?>
    <script>
        jQuery('a[href^="http"]:not([href^="<?php echo($this_domain); ?>"])') 
            .attr('target','_blank');
    </script>
    <?php
}
if (get_option('nmeOptimizingFunctions_addBlank') == 'on') {
	add_action('wp_footer', 'nme_optimizing_functions_add_blank_script');
}

// Enable shortcode in text widget
if (get_option('nmeOptimizingFunctions_enableShortcodeWidget') == 'on') {
	add_filter('widget_text', 'do_shortcode');
}

// remove wordpress version in sourcecode 
if (get_option('nmeOptimizingFunctions_removeWPInformations') == 'on') {
	remove_action('wp_head', 'wp_generator');
	add_filter( 'script_loader_src', 'nme_optimizing_functions_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', 'nme_optimizing_functions_remove_script_version', 15, 1 );
}

function nme_optimizing_functions_remove_script_version( $src ){
	$parts = explode( '?', $src );
	return $parts[0];
}

function nme_optimizing_functions_block_readme(){
	if (!defined('ABSPATH')) die('You do not have sufficient permissions to access this file.');
	if(file_exists(ABSPATH.'.htaccess')){
		$htaccess_add_line = '
<files readme.html>
order allow,deny
deny from all
</files>';
		if (get_option('nmeOptimizingFunctions_removeWPInformations') == 'on') {
			// Add line in htaccess
			@file_put_contents(ABSPATH.'.htaccess',$htaccess_add_line,FILE_APPEND);
		}
		else {
			//remove line in htaccess
			$htaccess_content_orig = @file_get_contents(ABSPATH.'.htaccess');
			$htaccess_content_orig = str_replace($htaccess_add_line, '', $htaccess_content_orig);
			@file_put_contents(ABSPATH.'.htaccess',$htaccess_content_orig);
		}
		
	}
}

// remove accents on media url when uploading 
function nme_optimizing_functions_w8_modify_uploaded_file_names($arr) {
	$arr['name'] = remove_accents($arr['name']);
	return $arr;
}
if (get_option('nmeOptimizingFunctions_modifyUploadedFileNames') == 'on' AND function_exists('remove_accents')) {
	add_filter('wp_handle_upload_prefilter', 'nme_optimizing_functions_w8_modify_uploaded_file_names', 1, 1);
}



?>
<?php 
// Add refresh
function nme_optimizing_functions_add_refresh() {
	global $bPartner;

	$listExcludeIP = ','.get_option('nmeOptimizingFunctions_excludeIP').',';
	$currentIP = $_SERVER['REMOTE_ADDR'];


	if((is_single(get_option('nmeOptimizingFunctions_excludePosts')) != true OR get_option('nmeOptimizingFunctions_excludePosts') == '') AND $bPartner != true AND strpos($listExcludeIP, $currentIP) === false) {
		if(is_home() AND get_option('nmeOptimizingFunctions_enableHomeRefresh') == 'on'){  
			srand(microtime()*1000000); 
			$seconds = rand(get_option('nmeOptimizingFunctions_homeRefreshMin'),get_option('nmeOptimizingFunctions_homeRefreshMax'));
			if($seconds == 180 or $seconds == 190) {   $seconds++;  } ?>
			<meta http-equiv="refresh" content="<?php echo($seconds); ?>">
		<?php 
		}
		else if(is_single() AND get_option('nmeOptimizingFunctions_enablePostsRefresh') == 'on'){ 
			srand(microtime()*1000000); 
			$seconds = rand(get_option('nmeOptimizingFunctions_postsRefreshMin'),get_option('nmeOptimizingFunctions_postsRefreshMax')); ?>
			<meta http-equiv="refresh" content="<?php echo($seconds); ?>">
		<?php 
		}	
	}
}
add_action('wp_head', 'nme_optimizing_functions_add_refresh');

// Keep comment when page refresh
function nme_optimizing_functions_keep_comment() {
	if(is_single()) {
	?>
<script type="text/javascript">


function nmeoptimizingfunctions_createCookie(name,value,days) {
	if (days) {
	    var date = new Date();
	    date.setTime(date.getTime()+(days*24*60*60*1000));
	    var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function nmeoptimizingfunctions_readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
	    var c = ca[i];
	    while (c.charAt(0)==' ') c = c.substring(1,c.length);
	    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function nmeoptimizingfunctions_eraseCookie(name) {
	nmeoptimizingfunctions_createCookie(name,"",-1);
}

jQuery(document).ready(function() {

	if(jQuery(location).attr('href') == nmeoptimizingfunctions_readCookie("nmeoptimizingfunctions_url")){
		jQuery("#comment-form-box textarea.textareaFieldImp").attr("value", nmeoptimizingfunctions_readCookie("nmeoptimizingfunctions_comment").replace(/\[nl2br\]/g,'\r\n'));
		//console.log(nmeoptimizingfunctions_readCookie("nmeoptimizingfunctions_comment"));
	}
	jQuery("#comment-form-box textarea.textareaFieldImp").focus(function() {
		nmeoptimizingfunctions_createCookie("nmeoptimizingfunctions_url",jQuery(location).attr('href'),1);
		jQuery(this).keyup(function() {
			nmeoptimizingfunctions_createCookie("nmeoptimizingfunctions_comment",jQuery(this).val().replace(/\r\n|\r|\n/g,'[nl2br]'),1);
		});
	});
	jQuery("#comment-form-box #submit").click(function() {
		nmeoptimizingfunctions_eraseCookie("nmeoptimizingfunctions_url");
		nmeoptimizingfunctions_eraseCookie("nmeoptimizingfunctions_comment");
	});
	
});
</script>
<?
	}
}
if (get_option('nmeOptimizingFunctions_keepComment') == 'on'){
	add_action('wp_head', 'nme_optimizing_functions_keep_comment');
}

?>