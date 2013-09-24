<?php
/*
Plugin Name: NME SEO Functions
Plugin URI: http://www.netmediaeurope.com
Description: Add functions to optimize SEO
Version: 1.0.1
Author: JL Marzio (NME)
Author URI: http://www.netmediaeurope.com
*/

if(is_admin()){
	$dir = plugin_dir_path( __FILE__ );
	include $dir.'/admin.php';
}

function nme_seo_functions_format_title( $title, $sep ) {
	 global $paged, $page;

	 if ( is_feed() )
	  return $title;

	 // Add the site name.
	 $title .= get_bloginfo( 'name' );

	 // Add the site description for the home/front page.
	 $site_description = get_bloginfo( 'description', 'display' );
	 if ( $site_description && ( is_home() || is_front_page() ) )
	  $title = "$title $sep $site_description";

	 // Add a page number if necessary.
	 if ( $paged >= 2 || $page >= 2 )
	  $title = "$title $sep " . sprintf( __( 'Page %s', 'springfield' ), max( $paged, $page ) );

	 return $title;
}
if (get_option('nmeSEOFunctions_formatTitle') == 'on') {
	add_filter( 'wp_title', 'nme_seo_functions_format_title', 10, 2 );
}

// Add keywords tag on single pages
function nme_seo_functions_add_keywords_tags() {
	if (is_single()) {
		$posttags = nme_seo_functions_get_tags_list();
		
		echo '<meta name="keywords" content="'.$posttags.'"/>';
		echo '<meta name="news_keywords" content="'.$posttags.'"/>';
	}
}
if (get_option('nmeSEOFunctions_addKeywordsTags') == 'on') {
	add_action('wp_head', 'nme_seo_functions_add_keywords_tags');
}

// Generate tag list for nme_seo_functions_add_keywords_tags()
function nme_seo_functions_get_tags_list() {
	$posttags = get_the_tags();
	$posttagsList = '';
	if ($posttags) {
		$nbr = 0;
		foreach($posttags as $tag) {
		if($nbr < 7 ){
			$posttagsList .= $tag->name . ', ';
		}
		$nbr++;
	  }
	  $posttagsList = substr($posttagsList, 0, -2);
	  return $posttagsList;
	}
}

// Add opengraph and opentweet meta in head
function nme_seo_functions_add_opengraph() {
	if(is_single() OR is_page()){
	//$id = get_the_ID();
	//query_posts( 'p='.$id );
	//while (have_posts()) : the_post();
		$myContent = strip_tags(get_the_excerpt());
		$myContent = str_replace("&hellip; Continuer la lecture &rarr;", "...", $myContent);
	//endwhile;

		if(is_page() && empty($myContent)) {
			//$myContent = get_post_meta( $post->ID, '', true );
			//$myContent = WPSEO_Frontend::metadesc(false);
		}

	$thumbnailUrl = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'wptouch-new-thumbnail'); 
	$thumbnailUrl_OG = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full'); 
	?> 
	<meta name="twitter:card" content="summary"/>
	<meta name="twitter:site" content="@itespressofr">
	<meta name="twitter:creator" content="@itespressofr">	
	<?php /*<meta property="fb:admins" content="135851933096649" />*/ ?>
	<?php /*<meta property="fb:app_id" content=""/>*/ ?>
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	
	<meta property="og:type" content="article" />
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<meta property="og:description" content="<?php echo $myContent; ?>" />	 
	<meta property="og:image" content="<?php echo $thumbnailUrl_OG[0]; ?>">
	<link rel="image_src" href="<?php echo $thumbnailUrl[0]; ?>" />
	
	
	<?php /*
	$thumbnailUrl = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'wptouch-new-thumbnail'); ?> 
	<meta property="og:type" content="article" />
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:url" content="<?php the_permalink(); ?>" />
	<meta property="og:description" content="<?php the_excerpt(); ?>" />		
	<meta property="og:image" content="<?php echo $thumbnailUrl[0]; ?>">*/ ?>
<?php }	else { ?>
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo esc_attr( get_bloginfo( 'name', 'display' ).' - '.get_bloginfo( 'description', 'display' ) ); ?>" />
	<meta property="og:url" content="<?php bloginfo( 'url' ); ?>" />
	<meta property="og:description" content="Découvrez l'information en direct sur les technologies de l'internet, de la high-tech et du marché IT pour les TPE-PME : quiz, livres blancs, test de produits high-tech, vidéos, logiciel à télécharger" />
	<?php }
}
if (get_option('nmeSEOFunctions_addOpengraph') == 'on') {
	add_action('wp_head', 'nme_seo_functions_add_opengraph');
}

/**
 * YOAST Hack
 */

// Remove canonical create by YOAST
function nme_seo_functions_remove_yoast_canonical(){

	add_filter('wpseo_robots','__return_false'); 
	
	if( is_admin() OR is_tag() OR is_category() OR is_date() OR is_author() OR is_paged() ){
		add_filter( 'wpseo_canonical', '__return_false' );
	}
}
if (get_option('nmeSEOFunctions_removeYoastCanonical') == 'on') {
	add_action('wp_head', 'nme_seo_functions_remove_yoast_canonical',1);
}

// Add rel link on homepage after YOAST delete it
function nme_seo_functions_add_rel_links(){
	if(is_home()) {
		$page = get_query_var( 'paged' );
		$next = $page+1;
		$prev = $page-1;
		$url_next = get_bloginfo( 'url' ).'/page/'.$next;
		$url_prev = get_bloginfo( 'url' ).'/page/'.$prev;
		$url = get_bloginfo( 'url' ).'/page/2';
		$url_home = get_bloginfo( 'url' );
		if($page == 0){
			echo '<link rel="next" href="'.$url.'" />';
		}else if ($page == 2){
			echo '<link rel="next" href="'.$url_next.'" />';
			echo '<link rel="prev" href="'.$url_home.'" />';
		}else{
			echo '<link rel="next" href="'.$url_next.'" />';
			echo '<link rel="prev" href="'.$url_prev.'" />';
		}
	}
}
if(get_option('nmeSEOFunctions_addRelLinks') == 'on'){
	add_action('wp_head', 'nme_seo_functions_add_rel_links');
} 
?>