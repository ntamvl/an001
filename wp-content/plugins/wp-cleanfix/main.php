<?php
/// @cond private
/**
 * Plugin Name: WP CleanFix
 * Plugin URI: https://wpxtre.me
 * Description: Clean and fix tools! Repair corrupted data and clean up your database
 * Version: 4.0.0
 * Author: wpXtreme, Inc.
 * Author URI: https://wpxtre.me
 * Text Domain: wpx-cleanfix
 * Domain Path: localization
 *
 * WPX PHP Min: 5.2.4
 * WPX WP Min: 3.8
 * WPX MySQL Min: 5.0
 * WPX wpXtreme Min: 1.4.8
 *
 */
// @endcond

// Avoid directly access
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

// wpXtreme kickstart logic
require_once( trailingslashit( dirname( __FILE__ ) ) . 'wp_kickstart.php' );

wpxtreme_wp_kickstart( __FILE__, 'wpx-cleanfix_00002e', 'WPXCleanFix', 'wpx-cleanfix.php' );