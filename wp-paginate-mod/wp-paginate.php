<?php
/*
Plugin Name: NME Paginate (based on WP-Paginate )
Plugin URI: http://www.beapi.fr
Description: A simple and flexible pagination plugin for WordPress posts and comments. DO NOT UPDATE
Author: Beapi + AO + JL
Version: 1.3
Author URI: http://www.beapi.fr
*/

/**
 * Set the wp-content and plugin urls/paths
 */
if (!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_PLUGIN_URL') )
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
if (!defined('WP_PLUGIN_DIR') )
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

if (!class_exists('WPPaginateNme')) {
	class WPPaginateNme {
		/**
		 * @var string The plugin version
		 */
		var $version = '1.3';

		/**
		 * @var string The options string name for this plugin
		 */
		var $optionsName = 'wp_paginate_nme_options';

		/**
		 * @var string $localizationDomain Domain used for localization
		 */
		var $localizationDomain = 'wp-paginate';

		/**
		 * @var string $pluginurl The url to this plugin
		 */
		var $pluginurl = '';
		/**
		 * @var string $pluginpath The path to this plugin
		 */
		var $pluginpath = '';

		/**
		 * @var array $options Stores the options for this plugin
		 */
		var $options = array();

		var $type = 'posts';

		/**
		 * PHP 4 Compatible Constructor
		 */
		function WPPaginateNme() {$this->__construct();}

		/**
		 * PHP 5 Constructor
		 */
		function __construct() {
			$name = dirname(plugin_basename(__FILE__));

			//Language Setup
			load_plugin_textdomain($this->localizationDomain, false, "$name/I18n/");

			//"Constants" setup
			$this->pluginurl = WP_PLUGIN_URL . "/$name/";
			$this->pluginpath = WP_PLUGIN_DIR . "/$name/";

			
			//if ($this->options['css'])
				add_action('wp_print_styles', array(&$this, 'wp_paginate_css'));
		}

		/**
		 * Pagination based on options/args
		 */
		function paginate($args = false) {
			if ($this->type === 'comments' && !get_option('page_comments'))
				return;

			$r = wp_parse_args($args, $this->options);
			extract($r, EXTR_SKIP);

			if (!isset($page) && !isset($pages)) {
				global $wp_query;

				if ($this->type === 'posts') {
					$page = get_query_var('paged');
					$posts_per_page = intval(get_query_var('posts_per_page'));
					$pages = intval(ceil($wp_query->found_posts / $posts_per_page));
				}
				else {
					$page = get_query_var('cpage');
					$comments_per_page = get_option('comments_per_page');
					$pages = get_comment_pages_count();
				}
				$page = !empty($page) ? intval($page) : 1;
			}

			$prevlink = ($this->type === 'posts')
				? esc_url(get_pagenum_link($page - 1)) 
				: get_comments_pagenum_link($page - 1);
			$nextlink = ($this->type === 'posts')
				? esc_url(get_pagenum_link($page + 1)) 
				: get_comments_pagenum_link($page + 1);

			$output = stripslashes($before);
			if ($pages > 1) {
				//$output .= sprintf('<p class="indic">%s %d/%d</p>', stripslashes($title), $page, $pages);	
				$output .= sprintf('<ul class="wp-paginate%s">', ($this->type === 'posts') ? '' : ' wp-paginate-comments');
				$ellipsis = "<li><span class='gap'>...</span></li>";

				if ($page > 1) {
				//AO-20111212
					$output .= sprintf('<li class="prev"><a href="%s"><img src="'.get_bloginfo('template_directory').'/img/ico-prev.png" style="margin-bottom: -2px;" ></a></li>', $prevlink, stripslashes($previouspage));
				} else {
					$output .= sprintf('<li class="prev" ></li>', stripslashes($previouspage));
				}
				
				/* Var set to do Pagination*/
				$indTenPage = (int)($page/10);
				$indHunPage = (int)($page/100);
				$indThoPage = (int)($page/1000);
				
				if($page >= 10) { 
					$p = ($this->type === 'posts') ? esc_url(get_pagenum_link(1)) : get_comments_pagenum_link(1);
					$output .= '<li><a href="'.$p.'" class="page" title="1">1</a></li>'; 
					$i=0; 
				} 
				else { 
					$i=1; 
				} // Display First page link if it isn't first ten otherwise it's automatically display with next step

				/* Display first ten link one by one 
				And set current page style */
				while($i<10 AND ($indTenPage*10)+$i < $pages) {
							
							if(($indTenPage*10)+$i == $page) {
								$output .= '<li><span class="page current">'.$page.'</span></li>';
							}
							else if($indTenPage != 0) {
								$p = ($this->type === 'posts') ? esc_url(get_pagenum_link($indTenPage.$i)) : get_comments_pagenum_link($indTenPage.$i);
								$output .= '<li><a href="'.$p.'" class="page" title="'.$indTenPage.$i.'">'.$indTenPage.$i.'</a></li>';
							}
							else {
								$p = ($this->type === 'posts') ? esc_url(get_pagenum_link($i)) : get_comments_pagenum_link($i);
								$output .= '<li><a href="'.$p.'" class="page" title="'.$i.'">'.$i.'</a></li>';
							}
						$i++;
					
				}

				/* Display links ten by ten to the next hundred */
				$output .= $this->pagination_loop($indTenPage, $indHunPage, $pages, 10);

				/* Display links hundred by hundred to the next thousand */
				$output .= $this->pagination_loop($indHunPage, $indThoPage, $pages, 100);

				/* Display links thousand by thousand to the max page */
				$output .= $this->pagination_loop($indHunPage, 0, $pages, 1000);


				/* Display next page link if it isn't the last page or set current style if it's the last */
				if($page != $pages) { 
					$p = ($this->type === 'posts') ? esc_url(get_pagenum_link($pages)) : get_comments_pagenum_link($pages);
					$output .= '<li><a href="'.$p.'" class="page" title="'.$pages.'">'.$pages.'</a></li>'; 
				} 
				else { 
					$output .= '<li><span id="page current">'.$pages.'</span></li>'; 
				}


				if ($page < $pages) {
					//AO-20111212
					$output .= sprintf('<li class="next"><a href="%s"><img src="'.get_bloginfo('template_directory').'/img/ico-next.png" style="margin-bottom: -2px;"></a></li>', $nextlink, stripslashes($nextpage));
				} else {
					$output .= sprintf('<li class="next" ></li>', stripslashes($nextpage));
				}
				$output .= "</ul>";
			}
			$output .= stripslashes($after);

			if ($pages > 1 || $empty) { 
			if(is_category() OR is_tag()){
				echo $output;
			}
			else{
				//AO20130329 add javascript load ?> 
					<div id="nme_pagination"><div class="pagination clearfix"></div></div>
					<script>
						var data = '<?php echo  urlencode ($output) ?>';
						$("#nme_pagination .pagination").load("<?php echo WP_PLUGIN_URL ?>/wp-paginate-nme/nme_pagination-ajax.php",{info:data},
							function(response, status, xhr) {
								if (status == "error") {
									var msg = "Sorry but there was an error: ";
									$("#nme_pagination").html(msg + xhr.status + " " + xhr.statusText);
								}
							}
						);
					</script><?php
				}
			}
		}

		/**
		 * Helper function for pagination which builds the page links.
		 */
		function pagination_loop($indPage, $nextRound, $pages, $stepRange) {
			$j = $indPage+ 1;
			$outputPagin = '';
			while($j < (($nextRound+1)*10) AND $j < $pages/$stepRange) {
				$jPage = $j*$stepRange;
				$p = ($this->type === 'posts') ? esc_url(get_pagenum_link($jPage)) : get_comments_pagenum_link($jPage);
				$outputPagin .= '<li><a href="'.$p.'" class="page" title="'.$jPage.'">'.$jPage.'</a></li>';
				$j++;
			}
			return $outputPagin;
		}

		function wp_paginate_css() {
			$name = "wp-paginate.css";
			if (false !== @file_exists(TEMPLATEPATH . "/$name")) {
				$css = get_template_directory_uri() . "/$name";
			}
			else {
				$css = $this->pluginurl . $name;
			}
			wp_enqueue_style('wp-paginate', $css, false, $this->version, 'screen');

			if (function_exists('is_rtl') && is_rtl()) {
				$name = "wp-paginate-rtl.css";
				if (false !== @file_exists(TEMPLATEPATH . "/$name")) {
					$css = get_template_directory_uri() . "/$name";
				}
				else {
					$css = $this->pluginurl . $name;
				}
				wp_enqueue_style('wp-paginate-rtl', $css, false, $this->version, 'screen');
			}
		}
		
	}
}

//instantiate the class
if (class_exists('WPPaginateNme')) {
	$wp_paginate = new WPPaginateNme();
}

/**
 * Pagination function to use for posts
 */
function wp_paginate_nme($args = false) {
	global $wp_paginate;
	return $wp_paginate->paginate($args);
}

/**
 * Pagination function to use for post comments
 */
function wp_paginate_nme_comments($args = false) {
	global $wp_paginate;
	$wp_paginate->type = 'comments';
	return $wp_paginate->paginate($args);
}

/*
 * The format of this plugin is based on the following plugin template: 
 * http://pressography.com/plugins/wordpress-plugin-template/
 */
?>