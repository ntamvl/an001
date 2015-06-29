<?php

/**
 * wpXtreme preferences model
 *
 * @class           WPXtremePreferences
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-09-16
 * @version         1.0.2
 *
 * @history         1.0.2 - Introducing Core Preferences
 *
 */
class WPXtremePreferences extends WPDKPreferences {

  /**
   * The preferences name used on database
   *
   * @var string
   */
  const PREFERENCES_NAME = 'wpxtreme-preferences';

  /**
   * Your own preferences property
   *
   * @var string $version
   */
  public $version = WPXTREME_VERSION;

  /**
   * An instance of WPXtremePreferencesWPXStoreBranch class to manage the WPX Store preferences.
   *
   * @var WPXtremePreferencesWPXStoreBranch $wpxstore
   */
  public $wpxstore;

  /**
   * Appearance
   *
   * @var WPXtremePreferencesAppearanceBranch $appearance
   */
  public $appearance;

  /**
   * List table
   *
   * @var WPXtremePreferencesListTableBranch $list_table
   */
  public $list_table;

  /**
   * Core preferences.
   *
   * @var WPXtremePreferencesCoreBranch $core
   */
  public $core;

  /**
   * Return an instance of WPXtremePreferences class from the database or onfly.
   *
   * @return WPXtremePreferences
   */
  public static function init()
  {
    $user_id = get_current_user_id();

    return parent::init( self::PREFERENCES_NAME, __CLASS__, WPXTREME_VERSION, $user_id );
  }

  /**
   * Set the default
   */
  public function defaults()
  {
    $this->wpxstore   = new WPXtremePreferencesWPXStoreBranch();
    $this->appearance = new WPXtremePreferencesAppearanceBranch();
    $this->list_table = new WPXtremePreferencesListTableBranch();
    $this->core       = new WPXtremePreferencesCoreBranch;
  }

}

/**
 * WPXStore preferences branch model
 *
 * @class           WPXtremePreferencesWPXStoreBranch
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-21
 * @version         1.0.0
 *
 */
class WPXtremePreferencesWPXStoreBranch extends WPDKPreferencesBranch {

  const DISPLAY_PROFILE     = 'wpxm-wpxstore-display-profile';
  const PLUGINS_INTEGRATION = 'wpxm-wpxstore-plugins-integration';

  const SECRET_KEY = 'wpxm-wpxstore-secret-key';
  const USER_ID    = 'wpxm-wpxstore-user-id';
  const PASSWORD   = 'wpxm-wpxstore-password';

  /**
   * A secret key used to encrypt the user credetial.
   *
   * @var string $secret_key
   */
  public $secret_key;

  /**
   * WPX Store user id
   *
   * @var string $user_id
   */
  public $user_id;

  /**
   * Session token. This token expiry.
   *
   * @var string $token
   */
  public $token;

  public $display_profile;
  public $plugins_integration;


  /**
   * Reset to defaults values
   */
  public function defaults()
  {
    $this->user_id             = '';
    $this->secret_key          = '';
    $this->token               = '';
    $this->display_profile     = true;
    $this->plugins_integration = true;
  }

  /**
   * Update this branch
   */
  public function update()
  {
    $this->user_id    = esc_attr( $_POST[ self::USER_ID ] );
    $this->secret_key = esc_attr( $_POST[ self::SECRET_KEY ] );
    $password         = esc_attr( $_POST[ self::PASSWORD ] );

    $this->display_profile     = isset( $_POST[ self::DISPLAY_PROFILE ] );
    $this->plugins_integration = isset( $_POST[ self::PLUGINS_INTEGRATION ] );

    // Avoid request
    if( empty( $this->user_id ) || empty( $password ) || empty( $this->secret_key ) ) {
      $this->token = false;

      return;
    }

    // Connect to WPX Store to get the token
    $this->token = WPXtremeAPI::init()->singin( $this->user_id, $password, $this->secret_key );

    //WPXtreme::log( $this->token );
  }
}

/**
 * Appearance preferences branch model
 *
 * @class           WPXtremePreferencesAppearanceBranch
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-07-12
 * @version         1.0.2
 *
 */
class WPXtremePreferencesAppearanceBranch extends WPDKPreferencesBranch {

  // All appearance enhancers
  public $display_ajax;
  public $toolbars;
  public $inputs;
  public $tables;
  public $table_actions;
  public $inline_edit;
  public $remove_revisions;

  /**
   * Set the default preferences
   */
  public function defaults()
  {
    $this->display_ajax     = 'off';
    $this->toolbars         = 'off';
    $this->inputs           = 'off';
    $this->tables           = 'off';
    $this->table_actions    = 'off';
    $this->inline_edit      = 'off';
    $this->remove_revisions = 'off';
  }
}

/**
 * List table preferences branch model
 *
 * @class           WPXtremePreferencesListTableBranch
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-22
 * @version         1.0.0
 *
 */
class WPXtremePreferencesListTableBranch extends WPDKPreferencesBranch {

  public $posts_thumbnail_author;
  public $posts_swipe_publish;

  public $media_thickbox_icon;
  public $media_thumbnail_author;

  public $pages_thumbnail_author;
  public $pages_swipe_publish;

  /**
   * Set the default preferences
   */
  public function defaults()
  {
    $this->posts_thumbnail_author = false;
    $this->posts_swipe_publish    = false;
    $this->media_thumbnail_author = false;
    $this->media_thickbox_icon    = false;
    $this->pages_thumbnail_author = false;
    $this->pages_swipe_publish    = false;
  }
}

/**
 * WPXtremePreferencesCoreBranch preferences branch model
 *
 * @class           WPXtremePreferencesCoreBranch
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2015 wpXtreme Inc. All Rights Reserved.
 * @date            2015-01-24
 * @version         1.0.2
 *
 * @history         1.0.1 - Minor refactor fow switch ui button
 * @history         1.0.2 - Added debug console and improve debug settings.
 *
 */
class WPXtremePreferencesCoreBranch extends WPDKPreferencesBranch {

  const WPDK_WATCHDOG_DOG = 'wpdk_watchdog_log';
  const DEBUG_CONSOLE     = 'debug_console';
  const IMPROVE_DEBUG     = 'improve_debug';

  /**
   * Enable log.
   *
   * @var bool $wpdk_watchdog_log
   */
  public $wpdk_watchdog_log = true;

  /**
   * Enable the debug console.
   *
   * @var bool $debug_console
   */
  public $debug_console = false;

  /**
   * Improve the debug with more information as backtrace.
   *
   * @var bool $improve_debug
   */
  public $improve_debug = false;

  /**
   * Reset to defaults values
   */
  public function defaults()
  {
    $this->wpdk_watchdog_log = true;
    $this->debug_console     = false;
    $this->improve_debug     = false;
  }

  /**
   * Update this branch
   */
  public function update()
  {
    $this->wpdk_watchdog_log = isset( $_POST[ self::WPDK_WATCHDOG_DOG ] );
    $this->debug_console     = isset( $_POST[ self::DEBUG_CONSOLE ] );
    $this->improve_debug     = isset( $_POST[ self::IMPROVE_DEBUG ] );

    // WPDK
    update_site_option( self::WPDK_WATCHDOG_DOG, $this->wpdk_watchdog_log );
  }
}