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
