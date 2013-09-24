<?php
/*
Plugin Name: NME Google news Editor Choice - B2B
Plugin URI: http://www.netmediaeurope.com
Description: Editor Choice RSS generation for B2B website. Need "Une" class and global $featured_ids to work
Version: 1.0.2
Author: Thierry Pigot
Author URI: http://www.netmediaeurope.com
*/

// JL : correction of the format of the feed to have a full RSS standard
add_action( 'wp_footer', 'nme_google_news_editor_choice_b2b', 1 );
function nme_google_news_editor_choice_b2b()
{
	$expire			= time() - 300 ; //	Cache : 5 minutes (300)
	$limit			= 5; //	limit item to 5 in rss file
	$xml_filename	= "google-news-rss.xml";
	
	//	Cache verification
	$regenerate_file = true;
	if( file_exists( $xml_filename ) && filemtime( $xml_filename ) > $expire )
		$regenerate_file = false;
	
	if( is_home() && $regenerate_file )
	{
		global $featured_ids; //	List of post call in home
		
		//	if count $featured_ids < $limit, get X last post
		if( count( $featured_ids ) < $limit )
		{
			$posts_array = get_posts( array( 'numberposts' => $limit - count( $featured_ids ), 'orderby' => 'post_date', 'order' => 'DESC', 'post_status' => 'publish', 'fields' => 'ids', 'post__not_in' => $featured_ids ) );
		}

		$output_xml[] = '<?xml version="1.0" encoding="UTF-8" ?>';
		$output_xml[] = '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">'; // JL : Made some change to have the correct header
		$output_xml[] = '<channel>';
		$output_xml[] = '<link>'. get_bloginfo( 'wpurl' ) .'</link>';
		$output_xml[] = '<description><![CDATA['. get_bloginfo( 'description' ) .']]></description>';
		$output_xml[] = '<title>'. get_bloginfo( 'name' ) .'</title>';
		$output_xml[] = '<image>';
		$output_xml[] = '<url>'. plugin_dir_url( __FILE__ ) .'images/'. strtolower( str_replace( '.', '_', get_bloginfo( 'name' ) ) ) .'.png</url>';
		$output_xml[] = '<title><![CDATA['. get_bloginfo( 'name' ) .' : '. get_bloginfo( 'description' ) .']]></title>';
		$output_xml[] = '<link>'. get_bloginfo( 'wpurl' ) .'</link>';
		$output_xml[] = '</image>';
		$output_xml[] = '<lastBuildDate>'. date('D, d M Y h:i:s O' ) .'</lastBuildDate>';
		
		$i=0;
		foreach( $featured_ids as $post_id )
		{
			if( $i == $limit )
				continue;
			
			$post = get_post( $post_id );
			
			$output_xml[] = '<item>';
			$output_xml[] = '<title><![CDATA['. $post->post_title .']]></title>';
			$output_xml[] = '<link>'. get_permalink( $post_id ) .'</link>';

			if( trim( $post->post_excerpt ) != '' )
				$text = trim( strip_tags( $post->post_excerpt ) );
			else
				$text = substr( trim( strip_tags( $post->post_content ) ), 0, 256 );
			
			$output_xml[] = '<description><![CDATA['. $text .']]></description>';
			$output_xml[] = '<pubDate>'. date('D, d M Y h:i:s O', strtotime( $post->post_date ) ) .'</pubDate>';
			$output_xml[] = '<dc:creator>'. ucfirst( strtolower( get_the_author_meta( 'user_firstname', $post->post_author ) ) ) .' '. ucfirst( strtolower( get_the_author_meta( 'user_lastname', $post->post_author ) ) ) .'</dc:creator>'; // JL : author tag became dc:creator
			$output_xml[] = '</item>';

			$i++;
		}
		if( $posts_array )
		{
			foreach( $posts_array as $post_id )
			{
				if( $i == $limit )
					continue;
				
				$post = get_post( $post_id );
				
				$output_xml[] = '<item>';
				$output_xml[] = '<title><![CDATA['. $post->post_title .']]></title>';
				$output_xml[] = '<link>'. get_permalink( $post_id ) .'</link>';

				if( trim( $post->post_excerpt ) != '' )
					$text = trim( strip_tags( $post->post_excerpt ) );
				else
					$text = substr( trim( strip_tags( $post->post_content ) ), 0, 256 );
			
				$output_xml[] = '<description><![CDATA['. $text .']]></description>';
				$output_xml[] = '<pubDate>'. date('D, d M Y h:i:s O', strtotime( $post->post_date ) ) .'</pubDate>';
				$output_xml[] = '<dc:creator>'. ucfirst( strtolower( get_the_author_meta( 'user_firstname', $post->post_author ) ) ) .' '. ucfirst( strtolower( get_the_author_meta( 'user_lastname', $post->post_author ) ) ) .'</dc:creator>'; // JL : author tag became dc:creator
				$output_xml[] = '</item>';
			}
		}
		
		$output_xml[] = '</channel>';
		$output_xml[] = '</rss>';
		
		$fp = fopen( $xml_filename, "w+" ); // open the cache file "google-news-rss.xml" for writing
		fwrite( $fp, implode( "\n", $output_xml ) ); // save the contents of output buffer to the file
		fclose( $fp ); // close the file

		echo '<!-- news Editor Choice '.date('Y-m-d H:i:s').' -->';
	}
}
?>