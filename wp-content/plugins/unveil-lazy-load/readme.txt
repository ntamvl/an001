=== Unveil Lazy Load ===
Contributors: marubon
Donate link: 
Tags: performance, images, lazy load
Requires at least: 3.2
Tested up to: 4.0
Stable tag: 0.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Unveil Lazy Load is a WordPress Plugin whitch makes lazy-image-load possible to decrease number of requests and improve page loading time.

== Description ==

Unveil Lazy Load is a WordPress Plugin whitch makes lazy-image-load possible 
to decrease number of requests and improve page loading time.

This plugin has the following features compared to another lazy-load plugin:

= Decrease data size loaded from web server by adopting a lightweight lazy-load library = 

  This plugin uses a lightweight jQuery plugin created by optimizing <a href="https://github.com/luis-almeida/unveil">Unveil.js</a> Unveil.js (https://github.com/luis-almeida/unveil) 
  less than about 0.6KB in size in order to only load an image when it's visible in the viewport.

= Decrease number of HTTP requests using data URI scheme =

  This plugin needs not to load an external dummy image required for lazy-load
  because the image is embedded in HTML using data URI scheme technique.

== Installation ==

1. Download zip archive file from this repository.

2. Login as an administrator to your WordPress admin page. 
   Using the "Add New" menu option under the "Plugins" section of the navigation, 
   Click the "Upload" link, find the .zip file you download and then click "Install Now". 
   You can also unzip and upload the plugin to your plugins directory (i.e. wp-content/plugins/) through FTP/SFTP. 

3. Finally, activate the plugin on the "Plugins" page.

== Frequently Asked Questions ==
There are no questions.

== Screenshots ==
No applicable screenshots

== Changelog ==

= 0.1.0 =
* Initial working version.

= 0.1.3 =
* Bug fix: entry content is not displayed.

= 0.2.0 =
* Logic optimization of jQuery plugin
* Performance improvement

= 0.3.0 =
* Added: function to cancel lazy-load for specified image to which attribute (data-lazy="false") is appended 

= 0.3.1 =
* Update for WordPress 4.0

== Upgrade Notice ==
There is no upgrade notice.

== Arbitrary section ==


