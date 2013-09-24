NME SEO Functions
===========================

## GIT

*see [nme_plugin_seo-functions](http://git.maggie.netmediaeurope.com/nme_plugin_seo-functions)*

 - **master:** the live plugin



-----

## Description

This plugin gather together all the functions (initially in function.php) which are dedicated to SEO


### Options of the plugin
#### Miscellaneous
##### Format title in head of the document
This add the site name for all pages, add the description of the site for homepage and add the page number if necessary

##### Add meta keywords tags on single pages
This add meta tags "keywords" and "news_keywords" on single pages which contains the tags of the post.

#### YOAST Hack (only if WP SEO is install)
With this you can remove the canonical tags and meta robots generate by YOAST and add rel link next on homepage (YOAST delete it)

### Note
You can enable opengraph and opentweet in Yoast, go to "Social Network" submenu in Wordpress SEO and enable it.

## Deployment

1. Upload `nme-seo-functions` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Activate the options you need in the admin page of the plugin (NME SEO Functions)