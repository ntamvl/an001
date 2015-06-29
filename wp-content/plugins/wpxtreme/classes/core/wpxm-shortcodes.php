<?php
/// @cond private

/*
 * [DRAFT]
 *
 * THIS DOCUMENTATION IS A DRAFT. YOU CAN READ IT AND MAKE SOME EXPERIMENT BUT DO NOT USE ANY CLASS BELOW IN YOUR
 * PROJECT. ALL CLASSES AND RELATIVE METHODS BELOW ARE SUBJECT TO CHANGE.
 *
 */

/**
 * Register the wpXtreme shortcodes for WordPress
 *
 * ## Overview
 *
 * This class is not use yet.
 *
 * @class              WPXtremeShortcodes
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2014-01-20
 * @version            1.0.1
 */
final class WPXtremeShortcodes extends WPDKShortcodes {

  /**
   * Create or return a singleton instance of WPXtremeShortcodes
   *
   * @return WPXtremeShortcodes
   */
  public static function getInstance()
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new WPXtremeShortcodes();
    }
    return $instance;
  }

  /**
   * Alias of getInstance();
   *
   * @return WPXtremeShortcodes
   */
  public static function init()
  {
    return self::getInstance();
  }

  /**
   * Return a Key value pairs array with key as shortcode name and value TRUE/FALSE for turn on/off the shortcode.
   *
   * @return array Shortcode array
   */
  protected function shortcodes()
  {
    $shortcodes = array();
    return $shortcodes;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Shortcode methods
  // -------------------------------------------------------------------------------------------------------------------

} // class WPXtremeShortcodes

/// @endcond