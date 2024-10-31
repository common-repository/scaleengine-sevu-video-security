=== ScaleEngine Virtual Usher ===
Contributors: stefancaunter
Tags: scaleengine, sevu, virtual usher, video, security
Requires at least: 3.0.1
Tested up to: 5.0.3
Stable tag: trunk

ScaleEngine Virtual Usher Video Security Plugin.

== Description ==

The ScaleEngine Virtual Usher (SE-VU) plugin now offers ScaleEngine customers video security on their WordPress Pages.
We are the technical choice for stream protection. Our SE-VU system secures your content, protecting you from rippers
and guarding your investment in content production. Monetize your content safely with a real authorization solution for live
streamed events and for VoD. Read more on our [Stream Security]: https://www.scaleengine.com/docs/security/sevu
page. SE-VU is available for both iOS/HLS/XBMC and RTMP/RTMPe.

The sevu_ticket shortcode will order a SE-VU ticket and return the secure token and password of the ticket as a url param string. 
This string can then be appended to your HLS url in you player, and then passed to the video servers to access the secured content. 
The shortcode issues SE-VU tickets through the ScaleEngine API using your ScaleEngine API credentials, which
are required during configuration of the application.

== Installation ==

Ordered list:

1. download the zip [sevu_wp_plugin]: http://sevu.scaleengine.net/wp-content/uploads/latest/sevu_wp_plugin.zip
2. Upload the plugin from the dashboard by going to Plugins > Add New > Upload and Follow the instructions on the screen
3. Configure the API Settings.

Configure the plugin using your ScaleEngine API credentials.

From the dashboard go to Settings > SE-VU. There are 4 settings that can be changed CDN#, Public and Secret API Keys and the API Address.
The API Address should not be changed unless directed, it is there for those who wish to use beta versions that may not be stable.
Enter your CDN # and API Keys and enjoy using the SE-VU WordPress Plugin

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==
= 1.3.0 =
- jw player no longer supported
- removed jwplayer setting
- added show error setting to display errors if they occur generating a sevu ticket
-- error is returned instead of the key and pass params

= 1.2.1 =
- changed default app_inst from 'live' to 'play'
- removed unsupported rtsp links

= 1.2.0 =
Added support for jwplayer 7 and videojs
- jwplayer 7 can be accessed by changing jw_version to 7
- videojs can be used by adding player="videojs" instead of jw_version

= 1.1.4 =
Fixed issue with jw5 url parsing of parameters

= 1.1.3 =
fixed jw6 smil files using sevu tickets

= 1.1.2 =
fixed jw6 streamer/file url not working properly for single videos

= 1.1.1 =
fixed jw6 js code

= 1.1.0 =
Added support for jwplayer 6

= 1.0.1 =
fixed sevu_embed to return the embed code properly

== Upgrade Notice ==
