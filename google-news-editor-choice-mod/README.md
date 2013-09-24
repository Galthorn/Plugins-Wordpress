NME Google News Editor Choice B2B
===========================

## GIT

*see [nme_plugin_google-news-editor-choice-b2b](http://git.maggie.netmediaeurope.com/nme_plugin_google-news-editor-choice-b2b)*

 - **master:** the live plugin



-----

## Description

This plugin generate RSS for "[Editor Picks](https://support.google.com/news/answer/1004865?hl=en)".
**Need "Une" class and global $featured_ids to work**

## 1.0.2

Change some tags the plugin generates to have a full RSS compatibility according to [Google recommendations](https://support.google.com/news/publisher/answer/1407682?hl=en)

## Deployment

1. Upload `NME Google news Editor Choice - B2B` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Move the file "google-news-rss.xml" into your blog root directory and CHMOD to 777 so it is writable
4. wait 5 minutes and refresh the front page to regenerate the file