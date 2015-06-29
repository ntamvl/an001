<?php

/**
 * CleanFix preferences model
 *
 * @class           WPXCleanFixPreferences
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-23
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferences extends WPDKPreferences {

  /**
   * The preferences name used on database
   *
   * @var string
   */
  const PREFERENCES_NAME = 'wpxcf-preferences';

  /**
   * Your own preferences property
   *
   * @var string $version
   */
  public $version = WPXCLEANFIX_VERSION;

  /**
   * Return an instance of WPXCleanFixPreferences class from the database or onfly.
   *
   * @return WPXCleanFixPreferences
   */
  public static function init()
  {
    $preferences = parent::init( self::PREFERENCES_NAME, __CLASS__, WPXCLEANFIX_VERSION );

    /**
     * Filter the CleanFix Preferences instance class. You can use this filter to extend the preferences.
     *
     * @param WPXCleanFixPreferences $preferences The instance WPXCleanFixPreferences class.
     */
    $preferences = apply_filters( 'wpxcf_preferences_init', $preferences );

    return $preferences;
  }

  /**
   * Set the default
   */
  public function defaults()
  {
    /**
     * Fires when CleanFix preferences set the default value.
     * You can use this action to extend CleanFix preferences and set your own defaul values.
     *
     * @param WPXCleanFixPreferences $preferences The instance WPXCleanFixPreferences class.
     */
    do_action( 'wpxcf_preferences_reset_to_defaults', $this );
  }

}