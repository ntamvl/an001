<?php
/// @cond private
/**
 * Plugin Name: wpXtreme
 * Plugin URI: https://wpxtre.me
 * Description: Amazing WordPress Xtreme pack
 * Version: 1.5.2
 * Author: wpXtreme, Inc.
 * Author URI: https://wpxtre.me
 * Text Domain: wpxtreme
 * Domain Path: localization
 *
 * WPX PHP Min: 5.2.4
 * WPX WP Min: 3.8
 * WPX MySQL Min: 5.0
 * WPDK Min: 1.12.1
 *
 */
/// @endcond

// Avoid directly access
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

// Loading WPDK and immediately stop if something wrong happens
require_once( trailingslashit( dirname( __FILE__ ) ) . 'wpdk/wpdk.php' );
if ( is_null( $GLOBALS['WPDK'] ) ) {
  return;
}

// wpXtreme kickstart logic
require_once( trailingslashit( dirname( __FILE__ ) ) . 'kickstart.php' );

// Engage this WPX plugin
wpxtreme_kickstart( __FILE__, 'WPXtreme', 'wpxtreme.php' );