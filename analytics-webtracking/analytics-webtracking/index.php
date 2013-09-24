<?php
/*
Plugin Name: NME Analytics WebTracking
Plugin URI: http://www.netmediaeurope.com
Description: Push various data from the site on analytics to improve tracking
Version: 1.2.2
Author: JL Marzio (NME)
Author URI: http://www.netmediaeurope.com
*/

if(is_admin()){
	$dir = plugin_dir_path( __FILE__ );
	include $dir.'/admin.php';
}

add_action( 'wp_footer', 'nme_analytics_webtracking', 100 );
//Main function wich manages all the tracking scripts
function nme_analytics_webtracking(){
	?>
	<script type="text/javascript">
	<?php
	/*  This function send datas to analytics if the test mode is disabled otherwise, datas are display in the console of the browser.
		All the data are in the events section in analytics (Contents -> Events -> Top Events)
	*/
	?>
	function analyticswebtracking_update(section, type, value) { 
		<?php 
		if (get_option('analyticswebtracking_includeTestMode') == 'on')
		{ 
			?>
			console.log('Section : '+section+'; Type : '+type+'; Value : '+value);
			<?php
		} 
		else
		{
			?>
			_gaq.push(['_trackEvent', section, type, value]);
			<?php
		}
		?>
	}
	jQuery(document).ready(function() {
	<?php

	//Block Tracking : this track all the click in the content and order it by sections in analytics
	if (get_option('analyticswebtracking_includeBlockTracking') == 'on') {
		if(is_home() OR is_single()) {
			if(is_home()) {  ?> var blockSection='Home'; <?php } else { ?> var blockSection = 'Single'; <?php } ?>

		var blockTitle = '';

		analyticswebtracking_update('BlockTracking', blockSection, 'Display');
		jQuery("<?php echo get_option('analyticswebtracking_BTcontentID'); ?>").on("click", function(){ 
			
			blockTitle = jQuery(this).parents().children("<?php echo get_option('analyticswebtracking_BTtitleClass'); ?>").text();
			if(blockTitle == '') {
				/* user defined  */
				<?php echo get_option('analyticswebtracking_BTcustomCode'); ?>
			}


			if(blockTitle != '') {
				analyticswebtracking_update('BlockTracking', blockSection, blockTitle);
			}
		 });
			<?php
		}
	}

	//Search Tracking : this track search request and clicks on search results
	if (get_option('analyticswebtracking_includeSearchTracking') == 'on') {
		if(is_search()) {
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;  ?>

			analyticswebtracking_update('SearchTracking', 'Requests', '<?php echo $_GET['s']; ?>');

			jQuery("<?php echo get_option('analyticswebtracking_STpostClass'); ?>").each(function(i){
				jQuery(this).find("a").click(function(){
					var positionClick = ((<?php echo $paged; ?>-1)*10)+i+1;
					analyticswebtracking_update('SearchTracking', 'Position', positionClick);
				});
			});
			<?php
		}
		else if (isset($_GET['s']) AND $_GET['s'] != '') {
			?>
			analyticswebtracking_update('SearchTracking', 'notFound', '<?php echo $_GET['s']; ?>');
			<?php
		}
	}
	
	// Category Tracking : Each time a news is display, this script add +1 on the category of the news in analytics
	if (get_option('analyticswebtracking_includeCategoryTracking') == 'on') {
		if(is_single()) {
			?>
			<?php
			$analyticswebtracking_categories = get_the_category();
			if($analyticswebtracking_categories){
				foreach($analyticswebtracking_categories as $analyticswebtracking_category) {
					?>
		analyticswebtracking_update('CategoryTagTracking', 'Category', '<?php echo $analyticswebtracking_category->slug; ?>');
					<?php
				}
			}
		}
	}

	// Tag Tracking : Each time a news is display, this script add +1 for each tag of the news in analytics
	if (get_option('analyticswebtracking_includeTagTracking') == 'on') {
		if(is_single()) {
			?>
			analyticswebtracking_update('CategoryTagTracking', 'Tag', 'Display');
			<?php
			$analyticswebtracking_tags = get_the_tags();
			if($analyticswebtracking_tags){
				foreach($analyticswebtracking_tags as $analyticswebtracking_tag) {
					?>
		analyticswebtracking_update('CategoryTagTracking', 'Tag', '<?php echo $analyticswebtracking_tag->slug; ?>');
					<?php
				}
			}
		}
	}
	
	// Adblock Tracking : If there is div with id begining by div-gpt-ad- with height != 0 in the page so the user don't use adblock, this data is upload to analytics
	if (get_option('analyticswebtracking_includeAdblock') == 'on') {
		?>
		var detect = 1;
		jQuery("div[id$='_ad_container']").each(function() {
			if(jQuery(this).length != 0){
				detect=0;
			}
		});
		if (detect == 1) {
			analyticswebtracking_update('AdblockTracking', 'Use', 'Yes');
		}
		else {
			analyticswebtracking_update('AdblockTracking', 'Use', 'No');
		}
		<?php
	}
	?>
	});
	</script>
	

	<?php
}




// This function include the analytics script
function nme_analytics_webtracking_include_analytics() { 
	?>
	<script type="text/javascript" pagespeed_no_defer="">
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo get_option("analyticswebtracking_analyticsID"); ?>', '<?php echo get_option("analyticswebtracking_analyticsDomainName"); ?>');
  ga('send', 'pageview');
  </script>
	<?php
}
if (get_option('analyticswebtracking_includeAnalytics') == 'on') {
	add_action( 'wp_head', 'nme_analytics_webtracking_include_analytics', 100 );
}