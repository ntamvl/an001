=== JSON REST API (WP API) Categories and Tags ===
Contributors: WizADSL
Tags: api, json, REST, rest-api, patch
Requires at least: 3.9
Tested up to: 4.0
Stable tag: 1.01
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=58CC2KYQR64XW
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows tags and categories to be set when creating/editing posts with the JSON REST API (WP API)

== Description ==

The [JSON REST API (WP API)](http://wordpress.org/plugins/json-rest-api/) as of version 1.1.1 does not allow you to specify tags or categories
when createing or editing posts (it appears this will be added in a later release). This plugin addresses that problem by allowing categories and tags to be
passed in the JSON data when creating/editing posts.

An example JSON post that can be made using the [JSON REST API (WP API)](http://wordpress.org/plugins/json-rest-api/) (described in detail [here](http://wp-api.org/#posts_create-a-post)) to create a post in Wordpress would be:

`{"title":"Hello World!","content_raw":"Content","excerpt_raw":"Excerpt"}`

In order to add tags/categories you would add an array called "x-categories" and/or and array called "x-tags" to the JSON data, for example:

`{"title":"Hello World!","content_raw":"Content","excerpt_raw":"Excerpt","x-tags":["tag1","tag2"],"x-categories":["General","15"]}`

The example creates a post and assignes the tags "tag1" and "tag2" as well as placing the post in the category named "General" and category ID 15.  The tags do not have to exist prior to use. Categories must exist prior to use and may be expressed and either category names or category IDs.  The "x-" prefixes were used to avoid any conflicts in functionality with the [JSON REST API (WP API)](http://wordpress.org/plugins/json-rest-api/) plugin.  

Because this plugin relies on the JSON parsing functionallity provided by the [JSON REST API (WP API)](http://wordpress.org/plugins/json-rest-api/) plugin the alternate syntax using a multi-part-form body should also work.

By default the tags/categories specified when editing a post will replace any tags/categories already assigned to the post.  This behavior can be changed so that tags/categories are appened instead, simply open the `json-rest-api-wp-api-categories-and-tags` folder in the Wordpress plugins folder and edit `json-rest-api-patch.php`. On
line 12 of the file is a variable that should be set to `true` in order to have tags/categories appended instead of replaced.

== Installation ==

1. Upload the `json-rest-api-patch` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.0 =
* Initial release

= 1.01 =
* Fixed category id integer check; thanks to trevordevore
