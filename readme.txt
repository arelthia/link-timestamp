=== Plugin Name ===
Contributors: apintop
Tags: audio, embed, html5, media, plugin, shortcode, video, vimeo, youtube
Requires at least: 4.9
Tested up to: 5
Stable tag: 2.1.1
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

