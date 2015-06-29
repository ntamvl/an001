<?php

/**
 * Modules model controller.
 * This is the main modules controller. Use the filter `wpxcf_modules` to add/remove modules.
 *
 * @class              WPXCleanFixModulesController
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-10-02
 * @version            1.0.0
 */
class WPXCleanFixModulesController {

  // Badge transient
  const BADGE_TRANSIENT = 'wpxcg_badge_transient';

  /**
   * CleanFix modules instance array list
   *
   * @var array $modules
   */
  public $modules = array();

  /**
   * Return a singleton instance of WPXCleanFixModulesController class.
   * This method includes all file module and count the warning.
   *
   * @return WPXCleanFixModulesController
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
   * Return an singleton instance of WPXCleanFixModulesController class. This static method is an alias of init().
   *
   * @return WPXCleanFixModulesController
   */
  public static function getInstance()
  {
    return self::init();
  }

  /**
   * Create an instance of WPXCleanFixModulesController class
   *
   * @return WPXCleanFixModulesController
   */
  private function __construct()
  {
    /**
     * Filter the registered CleanFix modules.
     *
     * @param array $modules Registered modules.
     */
    $modules = apply_filters( 'wpxcf_modules', array() );

    /*
     * $modules = array(
     *   'WPXCFDatabaseModule',
     *   ...
     * );
     */

    // Loop into the module
    foreach( $modules as $class ) {

      // Sanitize key
      $key = sanitize_title( $class );

      // Init the module
      $this->modules[ $key ] = call_user_func( array( $class, 'init' ) );

    }
  }

  /**
   * Return the number of total issues for all registered modules.
   *
   * @return int
   *
   */
  public function issues()
  {
    global $plugin_page;

    // @since 1.2.90
    $total = get_site_transient( self::BADGE_TRANSIENT );

    // Check for empty
    if ( ( false === $total || wpdk_is_ajax() ) && ( ! is_null( $plugin_page ) && $plugin_page != 'wpxcf_menu_main' ) ) {

      // Default value
      $total = 0;

      /**
       * @var WPXCleanFixModule $module
       */
      foreach ( $this->modules as $module ) {
        $module->check();
        $total += (int) $module->has_issues;
      }

      set_site_transient( self::BADGE_TRANSIENT, $total, ( 12 * HOUR_IN_SECONDS ) );
    }

    return $total;
  }


}
