=== Plugin Name ===
Plugin Name:  Google News XML Sitemap Generator
Plugin URI:   http://pleer.co.uk/wordpress/plugins/google-news-xml-sitemap-generator/
Donate link:  http://pleer.co.uk/go/twitter-paypal/
Description:  Based on the plugin by Chris Jinks, this plugin generates a valid Google News Sitemap in XML format ready to submit.
Version:      1.0
Author:       Alex Moss
Author URI:   http://alex-moss.co.uk/

Contributors: Alex Moss (@alexmossSEO), Chris Jinks, David Stansbury
Link: http://pleer.co.uk/wordpress/plugins/wp-twitter-feed
Tags: Google News, Sitemap, XML
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 1.0

== Description ==

Automatically generates a Google News XML sitemap for submission online via Webmaster Tools.

Follows Google guidelines:

* 50,000 item limit
* Posts from the last 2 days

Keywords for sitemap are generated from post category and post tags.

Optional categories can be excluded from Google News sitemap.

Updated to conform with new Google News Sitemap format.



== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `google-news-sitemap-generator` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Move the file "google-news-sitemap.xml" into your blog root directory and CHMOD to 777 so it is writable
4. Save/publish/delete a post to generate the sitemap