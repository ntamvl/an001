<?php

/**
 * Options clean & fix module
 *
 * @class           WPXCFOptionsModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-23
 * @version         1.0.0
 *
 */
class WPXCFOptionsModule extends WPXCleanFixModule {

  const SITE_PREFIX = '_site';

  /**
   * Return a singleton instance of WPXCFOptionsModule class
   *
   * @return WPXCFOptionsModule
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
   * Create an instance of WPXCFOptionsModule class
   *
   * @return WPXCFOptionsModule
   */
  public function __construct()
  {
    parent::__construct( __( 'Options', WPXCLEANFIX_TEXTDOMAIN ) );

    // Register a custom preferences model.
    add_action( 'wpxcf_preferences_init', array( $this, 'wpxcf_preferences_init' ) );

    // Reset to default.
    add_action( 'wpxcf_preferences_reset_to_defaults', array( $this, 'wpxcf_preferences_reset_to_defaults' ) );

    // Register a custom preferences tab view.
    add_filter( 'wpxcf_preferences_tabs', array( $this, 'wpxcf_preferences_tabs' ) );

    // Added human readable date.
    add_filter( 'wpxcf_select_control_expired', array( $this, 'wpxcf_select_control_expired' ), 10, 3 );
  }

  /**
   * Return the list of slots.
   *
   * $slots = array(
   *  'ClassName',
   *  ...
   * );
   *
   */
  public function slots()
  {
    $slots = array(
      'WPXCFOptionsModuleExpiredSiteTransientSlot',
      'WPXCFOptionsModuleExpiredTransientsSlot',
    );

    return $slots;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // PREFERENCES INTEGRATION - these actions and filters are used to customize the CleanFix preferences
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Register a custom preferences branch
   *
   * @param WPXCleanFixPreferences $preferences Default preferences
   *
   * @return WPXCleanFixPreferences
   */
  public function wpxcf_preferences_init( $preferences )
  {
    if ( ! isset( $preferences->options ) ) {
      // Could be ...
      // $preferences = $this->wpxcf_preferences_reset_to_defaults( $preferences );
      $preferences->options = new WPXCleanFixPreferencesOptions();
      $preferences->update();
    }

    return $preferences;
  }

  /**
   * Register a custom preferences branch
   *
   * @param WPXCleanFixPreferences $preferences Default preferences
   */
  public function wpxcf_preferences_reset_to_defaults( $preferences )
  {
    $preferences->database = new WPXCleanFixPreferencesOptions();
    $preferences->update();
    // return $preferences;
  }

  /**
   * Register a custom preferences tabs
   *
   * @param array $tabs Default tabs
   *
   * @return array
   */
  public function wpxcf_preferences_tabs( $tabs )
  {
    //    $view   = new WPXCleanFixPreferencesOptionsView();
    //    $tabs[] = new WPDKjQueryTab( $view->id, __( 'Options', WPXCLEANFIX_TEXTDOMAIN ), $view->html() );

    $tabs['WPXCleanFixPreferencesOptionsView'] = __( 'Options', WPXCLEANFIX_TEXTDOMAIN );

    return $tabs;
  }

  /**
   * Return the date in human readable
   *
   * @param $result
   * @param $format
   * @param $item
   *
   * @return string
   */
  public function wpxcf_select_control_expired( $result, $format, $item )
  {
    $result = sprintf( __( '<strong>(%s ago)</strong>', WPXCLEANFIX_TEXTDOMAIN ), human_time_diff( $item->expired ) );

    return $result;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // SHARED METHODS - this method are use by several slot
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return the expired transient for single or multi site.
   *
   * @param string $prefix Optional. Prefix for multi `site` o single.
   *
   * @return mixed
   */
  public function expiredTransients( $prefix = '' )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    /**
     * @var WPXCleanFixPreferencesOptions $options
     */
    $options = WPXCleanFixPreferences::init()->options;
    $expiry  = isset( $options->expiryDate ) ? $options->expiryDate : 0;

    $sql = <<<SQL
SELECT
option_id,
option_name,
REPLACE(option_name, '{$prefix}_transient_timeout_', '') AS transient_name,
option_value AS expired,
from_unixtime( option_value ) AS readable

FROM {$wpdb->options}

WHERE option_name LIKE '{$prefix}_transient\_timeout\_%'

AND option_value < ( UNIX_TIMESTAMP(NOW()) - {$expiry} )

ORDER BY expired, transient_name
SQL;

    return $wpdb->get_results( $sql );

  }

  /**
   * Return the number of records delete
   *
   * @param string $prefix Optional. Prefix for multi `site` o single.
   *
   * @return array|bool
   */
  public function deleteExpiredTransients( $prefix = '' )
  {
    global $wpdb;

    $expired = $this->expiredTransients( $prefix );

    /**
     * @var WPXCleanFixPreferencesOptions $options
     */
    $options = WPXCleanFixPreferences::init()->options;

    if ( wpdk_is_bool( $options->safeMode ) ) {
      if ( self::SITE_PREFIX == $prefix ) {
        foreach ( $expired as $transient ) {
          get_site_transient( $transient->transient_name );
        }

      }
      else {
        foreach ( $expired as $transient ) {
          get_transient( $transient->transient_name );
        }
      }
    }
    else {

      $option_names = array();
      foreach ( $expired as $transient ) {
        $option_names[] = $prefix . '_transient_' . $transient->transient_name;
        $option_names[] = $prefix . '_transient_timeout_' . $transient->transient_name;
      }

      $options_names = array_map( 'esc_sql', $option_names );
      $options_names = "'" . implode( "','", $options_names ) . "'";

      $sql = <<<SQL
DELETE
FROM {$wpdb->options}
WHERE option_name IN ({$options_names})
SQL;

      return $wpdb->get_results( $sql );
    }

    return false;
  }

}

/**
 * Single slot
 *
 * @class           WPXCFOptionsModuleExpiredSiteTransientSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-15
 * @version         1.0.0
 *
 */
class WPXCFOptionsModuleExpiredSiteTransientSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFOptionsModuleExpiredSiteTransientSlot class
   *
   * @param WPXCFOptionsModule $module
   *
   * @return WPXCFOptionsModuleExpiredSiteTransientSlot
   */
  public static function init( $module )
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self( $module );
    }

    return $instance;
  }

  /**
   * Create an instance of WPXCFOptionsModuleExpiredSiteTransientSlot class
   *
   * @param WPXCFOptionsModule $module
   *
   * @return WPXCFOptionsModuleExpiredSiteTransientSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Expired Site Transients', WPXCLEANFIX_TEXTDOMAIN ), __( 'Transients data are temporary values stored in the options database tables. When a transient expires you can safely remove it.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $site_transients = $this->module->expiredTransients( WPXCFOptionsModule::SITE_PREFIX );

    // Get/Set issues
    $issues = $this->issues( count( $site_transients ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s expired site transient.', 'You have %s expired site transients.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $site_transients, array(
        'transient_name' => '%s',
        'expired'        => '(%s)'
      ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete your expired site transients.', WPXCLEANFIX_TEXTDOMAIN );
    }
    return $this->response;
  }

  /**
   * Clean or Fix process.
   *
   * @return WPXCleanFixModuleResponse
   */
  public function cleanFix()
  {
    $this->module->deleteExpiredTransients( WPXCFOptionsModule::SITE_PREFIX );

    return $this->check();
  }


}

/**
 * Single slot
 *
 * @class           WPXCFOptionsModuleExpiredTransientsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-15
 * @version         1.0.0
 *
 */
class WPXCFOptionsModuleExpiredTransientsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFOptionsModuleExpiredTransientsSlot class
   *
   * @param WPXCFOptionsModule $module
   *
   * @return WPXCFOptionsModuleExpiredTransientsSlot
   */
  public static function init( $module )
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self( $module );
    }

    return $instance;
  }

  /**
   * Create an instance of WPXCFOptionsModuleExpiredTransientsSlot class
   *
   * @param WPXCFOptionsModule $module
   *
   * @return WPXCFOptionsModuleExpiredTransientsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Expired Transients', WPXCLEANFIX_TEXTDOMAIN ), __( 'Transients data are temporary values store in the options database table. When a transient is expired you can remove it in safe.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $transients = $this->module->expiredTransients();

    // Get/Set issues
    $issues = $this->issues( count( $transients ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s expired transient.', 'You have %s expired transients.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $transients, array(
        'transient_name' => '%s',
        'expired'        => '(%s)'
      ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete your expired transients.', WPXCLEANFIX_TEXTDOMAIN );
    }
    return $this->response;
  }

  /**
   * Clean or Fix process.
   *
   * @return WPXCleanFixModuleResponse
   */
  public function cleanFix()
  {
    $this->module->deleteExpiredTransients();

    return $this->check();
  }

}
