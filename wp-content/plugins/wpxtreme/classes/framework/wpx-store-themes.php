<?php

/**
 * TODO
 *
 * @class           WPXStoreThemes
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-02
 * @version         1.0.0
 * @since           1.3.0
 *
 */
class WPXStoreThemes {

  /**
   * Return a singleton instance of WPXStoreThemes class
   *
   * @return WPXStoreThemes
   */
  public static function init()
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * Create an instance of WPXStoreThemes class
   *
   * @return WPXStoreThemes
   */
  public function __construct()
  {
  }


}