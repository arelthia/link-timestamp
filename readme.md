=== Link Timestamp ===
Contributors: apintop
Tags: audio, embed, html5, media, plugin, shortcode, video, vimeo, youtube
Requires at least: 4.9
Tested up to: 5.9
Stable tag: 2.3.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a link to timestamps on your website. When the link is clicked the audio or video will jump to the correct time in the media player.

== Description ==

Automatically link timestamps
* Link Timestamp can be configured to automatically link timestamps.
* Control which post type gets automatically linked from the settings page. (Settings > Link Timestamp)
* Control if timestamps are linked to audio or video. (Settings > Link Timestamp) This comes in handy if you have audio and video on the same page.
* Turn off auto linking on individual pages from the post editor.


Manually link timestamps
* You can manually add links to your timestamps using the Link Timestamp button in the visual editor

Link Timestamp will work with the following:
*   Vimeo videos
*   Youtube videos
* 	Smart Podcast Track Player
*   Soundcloud Embeded Player
*   Blubrry PowerPress Players
*   HTML5 <audio> elements
*   HTML5 <video> elements


== Installation ==

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the Plugins dashboard
1. Navigate to the 'Upload' area
1. Select 'link-timestamp.zip' from your computer
1. Click 'Install Now'
1. Activate the plugin in the Plugins dashboard

= Using FTP =

1. Download 'link-timestamp.zip'
1. Extract the 'link-timestamp' directory to your computer
1. Upload the 'link-timestamp' directory to the '/wp-content/plugins/' directory
1. Activate the plugin in the Plugins dashboard

== Frequently Asked Questions ==
= Will it work with the Soundcloud Embeded Player?=

Yes

= Will it work with the Blubrry PowerPress Player?=

Yes

= Will it work with the Smart Podcast Player?=

Yes, It will work with the Smart Podcast Track Player.

= Will it work with an embeded Vimeo video?=

Yes

= Will it work with an embeded Youtube video?=

Yes it works with a Youtube embeded video using just the url or using [embed].  It will not work with the old Youtube embed that uses the <iframe> code.

= Can I use the shortcodes and automatically linked timestamps? =

You can use the shortcode and have automatically link timestamps enabled. However, they will not both work on the same content. If you have the shortcode on a post/page links will not be linked automatically for that post/page.


== Screenshots ==

1. Link Timestamp settings (Settings > Link Timestamp)
2. Link Timestamp button in visual editor
3. Link Timestamp link settings dialogue
4. Link Timestamp disable automatic link on post checkbox

== Changelog ==
= 2.3.3 =
Fixed vimeo player doskip
Update License class name

= 2.3.2 =
Fixed license activate and deactivate for plugin update issue.

= 2.3.1 =
Fixed do_autolink to only link timestamps in the text content, not timestamps in html tag attributes 

= 2.3 =
Add support for Libsyn Embeded Player
Add support for Spreaker Embedded Player with JS API
Add support for linking to a timestamp from a different page

= 2.2 =
Updated Vimeo api 
Fix index error for iframe embeds.

= 2.1.1 =
Fix update issue

= 2.1 =
Add ability to only auto link on posts with a specific category

= 2.0 =
Add Support for SoundCloud
Fix bug that prevented Youtube embeds from working if start time provided
Updated license classto provide better messages on activation

= 1.11 =
Fix Support for the Smart Podcast Player v2

= 1.1 =
Add Support for the Smart Podcast Player
Update Youtube iFrame API

= 1.0 =
Initial release


#
# Getting Started with Link Timestamp


## Table of Contents

1.  Installation
2.  Configure Automatic Link Settings
3.  Individual Post Settings

----------

### **A) Installation**

#### Uploading in WordPress Dashboard

1.  Navigate to the ‘Add New' in the Plugins dashboard
2.  Navigate to the ‘Upload' area
3.  Select ‘link-timestamp.zip' from your computer
4.  Click ‘Install Now'
5.  Activate the plugin in the Plugins dashboard

#### Using FTP

1.  Download ‘link-timestamp.zip'
2.  Extract the ‘link-timestamp' directory to your computer
3.  Upload the ‘link-timestamp' directory to the ‘/wp-content/plugins/' directory
4.  Activate the plugin in the Plugins dashboard

----------

### **B) Configure Automatic Link Settings**

1.  From the WordPress dashboard go to Settings > Link Timestamp
2.  Link Timestamp can be configured to automatically link timestamps or control it on the post page
3.  Control which post type gets automatically linked from the settings page.
4.  Control if timestamps are linked to audio or video. This comes in handy if you have audio and video on the same page.

----------

### **C) Individual Post Settings** 

1.  Manually add links to your timestamps using the Link Timestamp button in the visual editor
2.  Control what text links to the timestamp
3.  Turn off auto linking on individual pages from the post editor.

----------

### **E) Sources and Credits**

I've used the following images, icons or other files as listed.

-   This plugin uses the Youtube Javascript API
-   This plugin uses the Vimeo Javascript API
-   This plugin uses the Soundcloud API
-   This plugin uses the Spreaker API

----------




---------
# How to link to a timestamp from a different page

## Query String

At the end of the link add a  `?ltstime=`. Then add the time stamp.

**Example**  
To link to the 0:53 timestamp in a video on a page located at  `https://exampledomain.com/pagewithvideo`  you would link to  
`https://exampledomain.com/pagewithvideo?ltstime=0:53`  


## Automatic Link

Disable Automatic links on the page that you add the link with the timestamp query string.

**Note:**  Not all media will auto play. Some media or browsers will require the user to interact with the page (ie. click play) before the media will play.