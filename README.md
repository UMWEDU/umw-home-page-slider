# UMW Home Page Slider #
**Contributors:** [cgrymala](https://wordpress.org/support/profile/cgrymala)

**Donate link:** http://giving.umw.edu/

**Tags:** carousel, photo, slideshow, flexslider

**Requires at least:** 3.9.1

**Tested up to:** 4.1.1

**Stable tag:** 1.0

**License:** GPLv2 or later

**License URI:** http://www.gnu.org/licenses/gpl-2.0.html


Implements the photo carousel used on the home page of the [UMW website](http://www.umw.edu/).

## Description ##

This plugin uses FlexSlider to implement the photo slideshow used on the home page of the [UMW Website](http://www.umw.edu/).

The plugin pulls information from a remote RSS feed, processes that feed, and uses it to output slides with photos, titles, captions and links to the original source.

The feed information is cached, by default, for 30 minutes. Each time the plugin attempts to retrieve the feed, it first tests the retrieval process. If it fails, the plugin falls back on cached information stored in the database. If the retrieval processes succeeds, the old cache information is dumped, and the new information is added.

In order to properly pull the photos used in the slideshow, the source feed must include images (large enough to serve as the slideshow photos) as enclosures. The plugin will use the first "image" enclosure as the photo in the slideshow.

The plugin is also configured to use the first 10 items in the feed.

## Installation ##

1. Upload the `umw-home-page-slider` directory into the `wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the new "Home Page Slider" widget to place it where it should appear

## Frequently Asked Questions ##

### Why is the slideshow showing fewer than 10 items? ###

There are a few possibilities:

1. Your source feed might include fewer than 10 items
1. One or more of the items in the source feed may not have a usable image set as an enclosure

### Can I modify the source feed? ###

Within the widget settings, you can set the URL of the source feed.

### Can I modify the number of items shown in the slideshow? ###

At this time, you can't modify the number of slides shown in the slideshow.

### Can I modify the JavaScript parameters sent to FlexSlider? ###

Most of those attributes are available as options when setting up the widget. However, if you would like to modify them through code, you can use the `umw-slider-defaults` filter to do so.

## Changelog ##

### 1.0 ###
* Update version of FlexSlider from v2 to v2.4.0
* Modify styles to more closely match new UMW design

### 0.1a ###
* Initial version
