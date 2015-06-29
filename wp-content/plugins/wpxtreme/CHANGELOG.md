# wpXtreme

---

## Versioning

For transparency and insight into our release cycle, and for striving to maintain backward compatibility, this code will be maintained under the Semantic Versioning guidelines as much as possible.

Releases will be numbered with the following format:

`<major>.<minor>.<patch>`

And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bumps the patch

For more information on SemVer, please visit http://semver.org/.

---

## Version 1.5.2
### 2015-04-08

* Minor fixes

## Version 1.5.1
### 2015-03-06

* Updated WPDK framework

## Version 1.5.0
### 2015-01-24

* Added debug console
* Added enable debug console in prefereces
* Added enable improve debug in prefereces
* Minor stable fixes
* Updated WPDK framework
  * Added `wpdk_is_lock()` function
  * Added useful static `init` method in `WPDKUIControlSwitch`
  * Extends data property and minor fixes in `WPDKAjaxResponse`
  * Fixed preferences button edit for custom post types
  * Fixed potential double login count in `WPDKUsers`
  * Fixed potential property access in function `wpdk_page_with_slug`

## Version 1.4.12
### 2015-01-21

* Updated WPDK framework
  * Improved the handler of clear input text field
  * Minor fixes in css
  * Minor fixes in docs

## Version 1.4.11
### 2015-01-14

* Fixed sortable list table
* Updated WPDK framework
  * Added `COLOR_PICKER` constant and `WPDKUIControlColorPicker` class for file media ui control
  * Added `FILE_MEDIA` constant and `WPDKUIControlFileMedia` class for file media ui control
  * Minor improvements to style sheet

## Version 1.4.10
### 2015-01-08

* Fixed user profile in order to no display the WPDK user info
* Updated WPDK framework
  * Added `SWITCH_BUTTON` constant and `WPDKUIControlSwitch` class
  * Added Javascript extends `wpdkSwitch()` and `wpdkSwitches()` for `WPDKUIControlSwitch`
  * Fixed potential wrong check on undefined in `wpdkModal` Javascript
  * Minor fixes and deprecated `wpdkSwipe()` method

## Version 1.4.9
### 2014-12-31

* Fixed clone URL on quick edit

* Updated WPDK framework v1.8.0
  * Added collapsable filedset in Controls Layout
  * Added "fonts" component
  * Added `wpdk_page_with_slug()` function
  * Improved and fixed `WPDKWidget` css inline for "customize.php"
  * Improved compatibility with "costomize.php" page
  * Improved and cosmetic on file `classes/core/wpdk-functions.php`
  * Fixed several Javascript events - add "off" where needed 
  * Minor fixes

## Version 1.4.8
### 2014-12-18

* Fixed enhancer menu order (column order with drag & drop) with `post_type_supports( $post_type, 'page-attributes' )` instead `capability_type`

## Version 1.4.7
### 2014-12-10

#### Improvements

* Improved `WPXCountries`
* Performance improves
* Updated WPDK framework v1.7.4
  * Added 4 news font icons
  * Added IP address core placeholder
  * Minor fixes stability
  * Performance improves

#### Bugs

* Fixed enhancer post quick edit

## Version 1.4.6
### 2014-11-28

* Updated WPDK framework v1.7.3
  * Added 14 news font icons
  * Improved WPDK Preferences with `wpdk_preferences_reset_to_default_branch-{$branch}`.
  * Improved WPDK Preferences with `wpdk_preferences_update_branch-{$branch}`.
  * Added `wpdk_flush_cache_third_parties_plugins` action in order to flush third parties plugins.
  * Added `WPDKUIControlType::inputTypeWithClass()` helper static method in order to retrive the HTML control type string.
  * Fixed dynamic table css styles.
  * Fixed potential Javascript warning in localize script on jQuery
  * Minor stability fixes


## Version 1.4.5
### 2014-11-19

* Updated WPDK framework v1.7.2
  * Improved WPDK user profile UI
  * Minor CSS styles fixes and improvements
  * Improved style sheets
  * Renamed `wpdk_post_placeholders_array` filter in `wpdk_post_placeholders_replace`
  * Added `WPDKUIControlURL`
  * Added `composer.json`
  * Added jQuery tabs vertical support
  * Fixed potential Javascript warning in localize script when no user logged in
  * Fixed potential wrong json type in some autocomplete engine

## Version 1.4.4
### 2014-11-05

* Updated WPDK framework v1.7.1
  * Fixed wrong enqueue components for preferences
  * Improved `WPDKUIControlButton` class with toggle feature (see https://github.com/wpXtreme/wpdk/wiki/Button-Toggle-UI-Control)
  * Added `wpdkProgressBars()` methods to manage the progress bar (see https://github.com/wpXtreme/wpdk/wiki/WPDKUIProgress)
  * Added `WPDKUIProgress`
  * Update WPDK UI Progress styles with Bootstrap v3.3.0

## Version 1.4.3
### 2014-10-29

#### Bugs

* Fixed error when deactive wpXtreme plugin from plugins list

* Updated WPDK framework v1.7.0
  * Fixed potential error when open a modal dialog
  * Added `WPDKGeo::reverseGeocoding()` method
  * Added `wpdk_geo` shortcode
  * Added `WPDKDB` as extension of WordPress wpdb class
  * Added `add_clear` property for all input WPDK UI Control in order to display the icon clear
  * Added `WPDKFilesystem::append()` method
  * Added `wpdk_ui_modal_dialog_javascript_show-{$id}` action
  * Added `wpdk_ui_modal_dialog_javascript_toogle-{$id}` action

## Version 1.4.2
### 2014-10-24

#### Bugs

* Fixed "clone" action
* Fixed potential error on issue report when try to access to global scripts and styles

## Version 1.4.1
### 2014-10-22

* Updated WPDK framework v1.6.1
  * Added Javascript `wpdk_add_filter()` and `wpdk_apply_filter()`
  * Improved Javascript `wpdk_add_action()` and `wpdk_do_action()` in order to support "priority" features and avoid document trigger handler events.
  * Added `WPDKMath::bytes()`
  * Fixed potential 'division by zero' in `WPDKFilesystem::fileSize()` method
  * Minor fixes

## Version 1.4.0
### 2014-10-14

* Improved scripts and styles loader
* Improved hook for plugins page
* Added countries class and database table
* Improved log and performance

* Updated WPDK framework v1.6.0
  * Added `wpdk-load-scripts.php` and `wpdk-load-styles.php` in order to concatenate WPDK components
  * Added `WPDKGeo` class
  * Added `WPDKUserAgents` class
  * Improved Javascript with `wpdk_add_action()` & `wpdk_do_action()`
  * Improved & fix Javascript docs
  * Improved performance
  * Removed deprecated
  * Improvements users class/filename organization
  * Fixed potential `__PHP_Incomplete_Class` in object delta

## Version 1.3.13
### 2014-09-24

#### Improvements

* New admin bar menu for wpXtreme and plugins
* Minor fixes for performance and stability

#### Under the hood

* Updated WPDK Framework ( start reporting WPDK changelog )
  * Several improved and fixed on list table view controller
  * Improved action filter and post data processing in list table model and view controller

## Version 1.3.12
### 2014-09-19

#### Improvements

* Added button `Clone` feature in posts, pages and custom post type
* Added row action `Clone` feature in posts, pages and custom post type
* Added new Core Preferences

#### Under the hood

* Updated WPDK framework
  * Updated jQuery timepicker addon
  * Added `WPDKPost::publish()`
  * Added `WPDKPost::insert()`
  * Added `WPDKPost::duplicate()`
  * Added site option `wpdk_watchdog_log` to enable/disable watchdog log
  * Added `WPDK_WATCHDOG_LOG` constant to enable/disable watchdog log
  * Added `WPDKUsers::deleteUsersMetaWithKey()`
  * Refresh `WPDKDBListTableModel` class
  * Improved CSS style for alert and form rows
  * Added W3 total cache plugin flush
  * Fixed date format
  * Fixed 'Add New' url in `WPDKListTableViewController`

## Version 1.3.11
### 2014-09-05

* Alignments for WordPress 4.0
* Updated WPDK framework v1.5.15

## Version 1.3.10
### 2014-09-04

* Minor alignments for WordPress 4.0
* Added new Community forums links

## Version 1.3.9
### 2014-09-02

* Important updated and alignment to last version of WPDK framework

## Version 1.3.8
### 2014-08-29

* Alignments for WordPress 4.0.RC1
* Minor fixes on display date format
* Updated WPDK Framework

## Version 1.3.7
### 2014-08-23

* Updated localization
* Updated WPDK framework
* Several minor fixes

## Version 1.3.6
### 2014-08-20

* Update WPDK framework
* Added delete update plugins site transient when active/deactive wpXtreme plugin

## Version 1.3.5
### 2014-07-31

#### Improvements

* Display the tour
* Updated WPDK framework

#### Bugs

* Fixed wrong Preferences link url in plugins list
* Fixed potential fatal error on `T_PAAMAYIM_NEKUDOTAYIM`

## Version 1.3.4
### 2014-07-28

* Optimized images sizes
* Removed unused styles
* Updated WPDK

## Version 1.3.3
### 2014-07-22

* Updated WPDK Framework
* Minor stability fixes

## Version 1.3.2
### 2014-07-17

* Fixed missing wpxtreme installed plugins list in WordPress "search" plugins
* Improved and fix user toolbar

## Version 1.3.1
### 2014-07-12

#### Bugs

* Removed old preferences
* Fixed potential fatal error on `T_PAAMAYIM_NEKUDOTAYIM`

## Version 1.3.0
### 2014-07-10

* Introducing New WPX Store
* Introducing WPX Store API key
* Removed old unsed featured
* Several improves
* Several fixes

## Version 1.2.7
### 2014-04-30

#### Enhancemnets

* Added logs database table
* Added Dashboard widget for logs

#### Improvements

* Updated WPDK framework

#### Bugs

* Minor stability fixes

## Version 1.2.6
### 2014-04-28

#### Bugs

* Updated `kickstart.php` file to fix potential error when get the mysql version
* Fixed potential wrong plugins/themes updated feedback
* Fixed issue report when get the database version
* Minor stable fixes

#### Improvements

* Updated WPDK framework

## Version 1.2.5
### 2014-04-10

* Improved dashboard init
* Fixed potential error when update

## Version 1.2.4
### 2014-04-08

* Updated WPDK
* Fixes and stability

## Version 1.2.3
### 2014-03-31

#### Bugs

* Fixed update from the store
* Fixed potential error on issue report
* Fixed table actions when is mobile

## Version 1.2.2
### 2014-03-28

#### Improvements

* Added some Javascript constants
* Removed deprecated
* Updated WPDK framework

#### Under the hood

* Added `IWPXReminderDialog` interface for reminder system
* Improves reminder

## Version 1.2.1
### 2014-03-18

#### Enhancements

* Minor fixes stability
* Avoid PHP Strict message
* Updated WPDK
* Updated `kickstart.php`

## Version 1.2.0
### 2014-02-26

#### Enhancements

* Added "Remove All Revisions" button in Preferences
* Removed obsolete preferences/enhancers

#### Improvements

* Added popover helper in the preference view
* Improves Media List Table icon preview style
* Added Adobe Flash support in Media List Table icon preview
* Updated WPDK
* Fixes for stability
* Clean code
* Updated localization

#### Bugs

* Fixed issue #11 FTP bugs while install/update

## Version 1.1.33
### 2014-02-19

* Minor stability fixes and WPDK updated

## Version 1.1.32
### 2014-02-18

* Minor stability fixes and WPDK updated

## Version 1.1.31
### 2014-02-17

#### Bugs

* Fixed potential plugins/themes duplicate in WordPress repository

#### Improvements

* Updated WPDK
* Improved plugins/themes update process

## Version 1.1.30

### 2014-02-06

#### Improvements

* Updated WPDK with several fixes and improvements
* Refactor view controler
* Speed and stability

## Version 1.1.20
### 2014-02-03

#### Improvements

* Updated WPDK with several fixes and improvements
* Added site transient in issue report
* Improved Control Panel icon - avoid over tooltip

## Version 1.1.19
### 2014-01-31

#### Improvements

* Updated WPDK

#### Bugs

* Minor Stability fixes
* Fixed warning on Issue Report

## Version 1.1.18
### 2014-01-29

#### Improvements

* Improved new Window debug
* Stability fixes
* Updated WPDK

## Version 1.1.16
### 2014-01-29

* Minor stability fixes

#### Improvements

* Improved Control Panel plugin actions
* Improved Issue Report Information
* Global performace Improvements
* The overloading call to the store

## Version 1.1.15
### 2014-01-24

#### Bugs

* Updated WPDK v1.4.12
* Stability

## Version 1.1.14
### 2014-01-23

#### Bugs

* Fixed WPDK

## Version 1.1.13
### 2014-01-23

#### Improvements

* Updated to WPDK v1.4.10

#### Bugs

* Fixed wrong text domain
* Removed enhancing (color and font-weight) for tag label for potential conflict with WordPress 3.8+

## Version 1.1.12
### 2014-01-17

#### Enhancements

* Added a new sub item menu 'Any Issues?' to run the Issue Report tool
* Updated WPDK 1.4.9

#### Experimental

* Introducing framework class `WPXReminder`

## Version 1.1.11
### 2014-01-09

#### Enhancements

* Removed deprecated
* Updated latest WPDK version
* Improved styles for author
* Added Dutch localization by Frans Pronk beheer@ifra.nl

#### Bugs

* Minor fixes for WordPress 3.8 compatibily
* Fixed admin footer support link

## Version 1.1.10
### 2013-12-17

#### Enhancements

* Alignments for WordPress 3.8
* Updated WPDK 1.4.7

#### Bugs

* Fixed plugin list css styles
* Minor stability fixes

## Version 1.1.9
### 2013-12-02

#### Enhancements

* Updated WPDK 1.4.6
* Display current plugin version on bottom admin menu

#### Improvements

* Optimized Javascript
* Improved issue report recording feedback with animated spin glyph icon

## Version 1.1.8
### 2013-11-28

#### Enhancements

* Updated WPDK v1.4.5
* Minor stable fixes

## Version 1.1.7
### 2013-11-22

#### Bugs

* Updated WPDK
* Fixed potential Javascript conflict

## Version 1.1.6
### 2013-11-21

#### Improvements

* Improved Javascript

#### Bugs

* Fixed potential wrong preferences set

## Version 1.1.5
### 2013-11-19

#### Enhancements

* Updated WPDK v1.4.2

## Version 1.1.4
### 2013-11-18

#### Enhancements

* Updated kickstart.php to v2.0.1
* Minor performance improvements

## Version 1.1.3
### 2013-11-14

#### Enhancements

* Purge and cleanup old Javascript and CSS
* Updated WPDK v1.4.0
* Improved inline plugins styles

## Version 1.1.2
### 2013-11-07

#### Enhancements

* Added body class version for all wpx active plugins
* Added automatic icon-restyling in plugin list view
* Added `pluginsViewWillLoad` hook when plugin list view is loaded

#### Bugs

* Fixed missing update notice in WordPress plugins list
* Fixed some wrong filename
* Minor fixes

## Version 1.1.1
### 2013-11-04

#### Enhancements

* Added `WPXMenu` class for upcoming extensions
* Updated WPDK v1.3.1
* Added constant WPXTREME_MENU to display/hide wpXtreme menu

#### Bugs

* Fixed broken layout after quick edit on posts/page ( closed #194 )

## Version 1.1.0
### 2013-10-05

#### Enhancements

* Introducing WPX Store theme
* Added Control Panel Controller to activate/deactivate plugins from Control Panel

#### Improvements

* Improved row actions enhancer in list table
* Improved stability and performance

#### Experimental

* Added Remove All Revisions button

#### Bugs

* Minor stability fixes

## Version 1.0.5
### 2013-09-12

#### Bugs

* Several minor fixes

#### Experimental

* Introducing responsive styles for admin backend
* Introducing activation/deactivation, delete and open actions in Control Panel
* Prepare for WPX Store Theme

#### Enhancements

* Updated WPDK framework to 1.2.0
* Updated header style and preferences layout
* Refactor preference architecture
* Added wpXtreme menu in WordPress admin toolbar
* Rewritten checking and updating process

#### Improvements

* Restyling several css
* Added Javascript info in Issue Report log
* Improved issue report with `WPXtremeIssueReportState` class
* Added import/export of preferences
* Added display ajax request in preferences
* Added top menu position in preferences
* Improved Dashboard widget with important notification and plugins/themes update notice
* Improved Control Panael
* Several layout fixes

## Version 1.0.2
### 2013-07-09

#### Bugs

* Fixed Potentially incorrect cache clear while upgrade plugin
* Fixed back to the store from the single product card
* Minor fixes

#### Enhancements

* Updated WPDK framework v1.1.2 (see https://github.com/wpXtreme/wpdk/blob/master/CHANGELOG.md )

#### Improvements

* Minor several improvements and stable

## Version 1.0.1
### 2013-06-14

#### Bugs

* Fixed/Improved the row action enhancer
* Remove debug-bug icon from control panel
* Minor fixes

## Version 1.0.0
### 2013-06-11

#### Enhancements

* Added WPDK URL and version in admin footer
* Makes WPDK as Open Source (removed from this private repo)
* Several improvements to UI
* New API engine

#### Bugs

* Fixed wrong ajax post send in enhancer preferences when swipe changed
* Minor fixes

## Version 1.0.0 beta 4
### 2013-04-19

#### Enhancements

* Added amazing autoload PHP class with over 50% performance increment on loading
* Added WPDKMail core class to make easy compose and send mail to an user
* Added WPDKMenu engine with WPDKSubMenu and new divider to make easy compose you backend area menu
* Added WPDKMetaBoxView to manage the WordPress meta box
* Added recursive scan in WPDKFilesystem
* Improved WPDKUI
* Improved enhancer users by adding a new combo menu filters for roles and capabilities
* Improved Appearance general and posts
* Improved CSS rules
* Improved internal documentation
* Cosmetic code
* Added deprecated filder
* Cleanup code
* Removed `bootstrap.php` and `config.php` and added new `kickstart.php` file
* Removed Tools menu and Maintenance. Checkout for Maintenance Pro plugin
* Removed embed Custom Post Mail. Checkout for Mail Manager plugin
* Removed User signin/signup/profile, etc... Checkout for Users Manager plugin
* Cleanup deprecated shortcode for user
* Removed embed security. Checkout for Deflector plugin
* Removed WPDKWidget and move into wpXtreme framework as WPXtremeWidget

#### Experimental

* Added WPDK progress bar like Twitter Bootstrap

#### Bugs

* Added CA certs handling for SSL transactions towards WPX Store. Following a signal from [Walter Franchetti](http://walterfranchetti.it/)
* Fixed bug in saving user extra-fields checkbox value
* Fixed CSS enhance rules
* Minor fixes in WPDK

## Version 1.0.0 beta 3
### 2013-01-21

#### Enhancements

* Added a new online Amazing Issue Report accesible from Control Panel or from new admin backend area footer
* Removed Breaking News from menu item and Control Panel
* Added a new WordPress dashboard Widgets with more information, breaking news and system updates
* Added a new admin backend area footer with some useful links and... see issue report above
* Added a back to Control Panel in the main navigation bar of WPX Store

#### Bugs

* Fixed WPDK shortcodes registration - moved registration from `wp_head()` hook to `template_redirect()`
* Fixed and complete map for WPDKHTMLTagTextarea
* Several Fixes on new CSS box model for WordPress 3.5
* Fixed configuration delta
* Fixed empty date in formatFromFormat() method
* Fixed wrong WordPress re-inclusion Plugin_Upgrader classes
* Minor labels fixes

## Version 1.0.0 beta 2
### 2013-01-14

#### Enhancements

* Added sorting by 'Enabled' columns in backend users list table
* Added new shortcode `wpdk_user_resend_unlock` to display a form to resend the unlock code
* Added new shortcode `wpdk_is_user_logged_in` to display a content only for logged in user
* Added new shortcode `wpdk_is_user_not_logged`_in to display a content only for NOT logged in user
* Added new Drag & Drop sorter for Custom Post Type of type page

#### Experimental

* Added WPDK Cron Jobs method prototype in WPDKWordPressPlugin class

#### Bugs

* Fixed #75 Fatal error in DateTime::createFromFormat()
* Fixed and improved update plugin procedure from standard WordPress plugin list
* Several improves and fixes in enhancer user list like add column for signup and sorting
* Improved install/update plugin layout from WPX Store
* Fixed enable/disable enhanced wpXtreme theme
* Improved and fixes for Custom Post Type Mail
* Minor fixes for WordPress.org submit

## Version 1.0.0 beta 1
### 2012-12-21

* First public release

## Version 0.9.12
### 2012-12-20

#### Enhancements

* Updated icons and header view icons

#### Bugs

* Minor fixes on maintenance mode date range check
* Fixed WPDKListTableViewController checkbox column name
* Align internal log
* Fixed: #57 - Hidden security badge

## Version 0.9.1
### 2012-12-12

#### Enhancements

* Added new WPDKShortcode class to make easy to write a shortcode own class
* Improved class WPDKWatchDog - added automatic removal for old zero log file
* Several improvement and fixes to WPDKListTableViewController class

#### Experimental

* Starting refactoring of WPDKDBTable
* Introducing WPDKTwitterBootstrapButton, WPDKTwitterBootstrapButtonType, WPDKTwitterBootstrapButtonSize

#### Bugs
* Fixed registration process
* Several fixes on WPX Store, API communications

## Version 0.8.0
### 2012-11-30

#### Enhancements

* Rewrite API services - introducing WPDKAPI, WPDKAPIErrorCode, WPDKAPIMethod, WPDKAPIResource, WPDKAPIResponse
* Added WPDKAjax a new class to make easy to write Ajax gateway class
* Updated logo plugin

#### Bugs

* Minor bugs fixes
* Minor Improvements
* Revision version and date in documentation

## Version 0.7.5
### 2012-11-27

#### Bugs

* Fixed textarea controls on set value
* Several fixes to prepare WordPress 3.5 compatibility
* Minor improvments
* Minor fixes

## Version 0.7.0
### 2012-11-20

#### Enhancements

* Updated bootstrap.php to fix invalid index
* Improved WPDK main class and removed manual WPDK version setting
* Improved Plugin loading and info
* Major updated in WPDKWordPressPlugin class
* Added nonce chceck in saving configuration view

#### Experimental

* Introducing new WPDKPlugin

#### Bugs

* Minor improvments in Control Panel
* Minor fixes in Control Panel
* Fixed wrong saving configuration in the queue

## Version 0.6.2
### 2012-11-16

#### Enhancements

* Updated bootstrap.php to v2.0.1 to avoid wrong WPDK path load

#### Bugs

* Fixed WPDKConfiguration wrong singleton init
* Minor docs updated
* Fixed minor bugs

## Version 0.6.1
### 2012-11-14

#### Bugs

* Fixed anonymous function (notice by Glenn Tate) in wpdk-sdf.php and WPDKForm.php

## Version 0.6.0
### 2012-11-13

#### Enhancements

* Updated WPDK Swipe control
* Updated Icons

#### Experimental
* Enabled beta of Control Panel

#### Bugs

* Fixed foreground color in form fields
* Minor fixes