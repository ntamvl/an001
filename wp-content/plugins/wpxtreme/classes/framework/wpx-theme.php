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
 * This class is similar to WPDKTheme. It is used to extends a main theme in wpXtreme environment.
 *
 * @class           WPXTheme
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-12-10
 * @version         1.0.3
 *
 */
class WPXTheme extends WPDKTheme {

  /**
   * Create an instance of WPXTheme class
   *
   * @param string              $file
   * @param bool|WPDKThemeSetup $setup Optional. A your custom theme setup class model
   *
   * @return WPXTheme
   */
  public function __construct( $file, $setup = false )
  {
    parent::__construct( $file, $setup );
  }

}

/// @endcond