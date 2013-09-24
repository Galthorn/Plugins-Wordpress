NME Analytics WebTracking
===========================

## GIT

*see [nme_plugin_analytics-webtracking](http://git.maggie.netmediaeurope.com/nme_plugin_analytics-webtracking)*

 - **master:** the live plugin



-----

## Description

This plugin track various data on the site and send it to analytics. It contains option to active differents tracking script.
All the datas are send to analytics in the Event Section (Content -> Events -> Top Events).


### Options of the plugin
#### Test Mode
Test Mode display data in console instead of sending them to analytics, it's usefull to test custom code in Block Tracking.

#### Adblock Tracking
This detect if adblock is use or not and push data in events section of analytics (AdblockTracking)

#### Block Tracking (This Isn't Even My Final Form)
This detect all the click in content, organize it by block and push data in events section of analytics (BlockTracking).

#### Search Tracking
Each time a search request is sent this push its value on analytics (SearchTracking -> Request or SearchTracking -> notFound if the request doesn't have any results)
If user click on a result this sent the position of the result to analytics (SearchTracking -> Position)

#### Category & Tags Tracking
Each time a news is display, this add +1 in events section of analytics for each tags and category (CategoryTagTracking)

#### Include Analytics
Enable it and set ID and Domain Name of the analytics to integrate the analytics script in the head 

## Changelog

### 1.2.0

- Add Search Tracking

### 1.1.0

- Normalize layout of the plugin (Functions of the plugin and admin are in two differents pages)
- Improve Adblock tracking

## Deployment

1. Upload `nme-analytics-webtracking` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Activate the tracking script you need in the admin page of the plugin (Analytics WebTracking)