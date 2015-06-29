<?php

// Include the main plugin class
require_once( trailingslashit( dirname( __FILE__ ) ) . 'classes/framework/wpx-plugin.php' );

/**
 * wpXtreme main Plugin Class.
 * This is the main class of plugin. This class extends WPDKWordPressPlugin in order to make easy several WordPress
 * funtions.
 *
 * @class              WPXtreme
 * @author             wpXtreme, Inc. <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-09-21
 * @version            1.3.2
 *
 * @history            1.3.1 - Added delete update plugin transient when active/deactive wpxtreme plugin.
 * @history            1.3.2 - Improved admin bar settings, moved in admin.
 *
 */
final class WPXtreme extends WPXPlugin {

  /**
   * Path of CA file in PEM format used for SSL certificating in shared hosting.
   *
   * @var string $_caCertPath
   *
   * @since 0.9.0
   */
  private $_caCertPath;

  /**
   * Value of ssl_verify flag into cURL transaction sent to the WPX Store
   *
   * @var string $_flagSSLVerify;
   *
   * @since 0.9.0
   */
  private $_flagSSLVerify;

  /**
   * Create and return a singleton instance of WPXtreme class
   *
   * @param string $file The main file of this plugin. Usually __FILE__ (main.php)
   *
   * @return WPXtreme
   */
  public static function boot( $file = null )
  {
    static $instance = null;
    if ( is_null( $instance ) && ( !empty( $file ) ) ) {
      $instance = new self( $file );

      // TODO to remove in next future
      // Backward compatibility with kickstart < v2.0.5
      if ( 0 == did_action( __CLASS__ ) ) {
        $GLOBALS['WPXtreme'] = $instance;
        do_action( __CLASS__ );
      }
    }

    return $instance;
  }

  /**
   * Create an instance of WPXtreme class
   *
   * @param string $file The main file of this plugin. Usually __FILE__ (main.php)
   *
   * @return WPXtreme
   */
  public function __construct( $file = null )
  {
    parent::__construct( $file );

    // Set CA cert file path needed by some shared hosting ( i.e. Azure )
    $this->_caCertPath    = $this->path . 'cacert.crt';
    $this->_flagSSLVerify = true;

    // Alternative WPX Store. This hook must be on `init` to avoid duplicate plugins in wordpress.org repository.
    add_action( 'init', array( 'WPXStore', 'init' ) );

    // since 1.3.0 - Customize `/wp-admin/plugins.php`
    add_action( 'load-plugins.php', array( 'WPXStorePlugins', 'init' ) );

    // since 1.3.0 - Customize `/wp-admin/plugin-install.php`
    if( wpdk_is_bool( WPXtremePreferences::init()->wpxstore->plugins_integration ) ) {
      add_action( 'load-plugin-install.php', array( 'WPXStorePluginInstall', 'init' ) );
      add_action( 'load-update.php', array( 'WPXStorePluginInstall', 'init' ) );
    }

    // TODO future implmement - since 1.3.0 - Customize `/wp-admin/themes.php`
    //add_action( 'load-themes.php', array( 'WPXStoreThemes', 'init' ) );

    // TODO future implmement - since 1.3.0 - Customize `/wp-admin/theme-install.php`
    //add_action( 'init', array( 'WPXStoreThemeInstall', 'init' ) );

    // Enhancer posts, pages and custom post type. See Ajax below too.
    add_action( 'load-edit.php', array( 'WPXtremeEnhancerPost', 'init' ) );
    add_action( 'load-post.php', array( 'WPXtremeEnhancerPost', 'init' ) );
    add_action( 'load-post-new.php', array( 'WPXtremeEnhancerPost', 'init' ) );

    // Enhancer users
    add_action( 'load-users.php', array( 'WPXtremeEnhancerUser', 'init' ) );

    /*
     * cURL patch for shared hosting having 'SSL certificate problem, verify that the CA cert is OK' issue;
     * Without this patch, some shared hosting with WP and wpXtreme cannot see WPX Store, because
     * they don't have a default bundle of trusted certificates installed in their environment.
     *
     */

    add_filter( 'https_local_ssl_verify', array( $this, 'curlGetFlagSSLVerify' ) );
    add_filter( 'https_ssl_verify', array( $this, 'curlGetFlagSSLVerify' ) );
    add_action( 'http_api_curl', array( $this, 'curlEnableSSLOnSharedHosting' ), 10, 2 );

    /*
     * Activate wpXtreme issue report mode if requested;
     * WARNING: there is no need to join this activation to a WP action; simply, I start to log
     *          PHP error(s) DIRECTLY FROM HERE, that is, even before plugins activation
     */

    // Get issue report instance
    if ( WPXtremeIssueReportState::ENABLED == WPXtremeIssueReportState::init()->state ) {
      $cIssueInstance = WPXtremeIssueReport::init();
      $cIssueInstance->enablePHPErrorLog();
    }

    // Do a backtrace for deprecated notice
    if( ( defined( 'WPXTREME_IMPROVE_DEBUG_OUTPUT' ) && true === WPXTREME_IMPROVE_DEBUG_OUTPUT ) || WPXtremePreferences::init()->core->improve_debug ) {

      // Fires when a deprecated function is called.
      add_action( 'deprecated_function_run', array( $this, 'deprecated_function_run' ) );
      set_error_handler( array( $this, 'errorHandler' ) );
    }

  }

  /**
   * Register all autoload classes.
   *
   * @since 1.0.0.b4
   */
  public function classesAutoload()
  {
    $includes = array(
    	$this->classesPath . 'admin/wpxm-admin.php' => 'WPXtremeAdmin',

    	$this->classesPath . 'admin/wpxm-dashboard.php' => array(
    		'WPXtremeDashboard',
    		'WPXtremeDashboardView'
    		),

    	$this->classesPath . 'core/wpxm-ajax.php' => 'WPXtremeAjax',

    	$this->classesPath . 'core/wpxm-api.php' => 'WPXtremeAPI',

    	$this->classesPath . 'core/wpxm-debug-view.php' => 'WPXtremeDebugView',

    	$this->classesPath . 'core/wpxm-shortcodes.php' => 'WPXtremeShortcodes',

    	$this->classesPath . 'enhancers/appearance/wpxm-enhancer-post.php' => 'WPXtremeEnhancerPost',

    	$this->classesPath . 'enhancers/users/wpxm-enhancer-user.php' => 'WPXtremeEnhancerUser',

    	$this->classesPath . 'framework/wpx-api.php' => 'WPXAPI',

      $this->classesPath . 'framework/wpx-countries.php' => array( 'WPXCountry', 'WPXCountries' ),

    	$this->classesPath . 'framework/wpx-logs.php' => 'WPXLogs',

    	$this->classesPath . 'framework/wpx-menu.php' => 'WPXMenu',

    	$this->classesPath . 'framework/wpx-plugin.php' => 'WPXPlugin',

    	$this->classesPath . 'framework/wpx-reminder-dialog-beta.php' => 'WPXReminderDialogBeta',

    	$this->classesPath . 'framework/wpx-reminder.php' => array(
    		'IWPXReminderDialog',
    		'WPXReminderInterval',
    		'WPXReminder'
    		),

    	$this->classesPath . 'framework/wpx-store-plugin-install.php' => array(
    		'WPXStorePluginInstall',
    		'WPXStorePluginInstallScreenHelp'
    		),

    	$this->classesPath . 'framework/wpx-store-plugins.php' => 'WPXStorePlugins',

    	$this->classesPath . 'framework/wpx-store-theme-install.php' => array(
    		'WPXStoreThemeInstall',
    		'WPXStoreThemeInstallScreenHelp'
    		),

    	$this->classesPath . 'framework/wpx-store-themes.php' => 'WPXStoreThemes',

    	$this->classesPath . 'framework/wpx-store-user-bar-view.php' => 'WPXStoreUserBarView',

    	$this->classesPath . 'framework/wpx-store.php' => array(
    		'WPXStore',
    		'WPXStoreSigninAlert',
    		'WPXStoreLicenseDialog'
    		),

    	$this->classesPath . 'framework/wpx-theme.php' => 'WPXTheme',

    	$this->classesPath . 'issue-report/wpxm-issue-report.php' => array(
    		'WPXtremeIssueReportState',
    		'WPXtremeIssueReport',
    		'WPXtremeIssueReportView'
    		),

    	$this->classesPath . 'preferences/wpxm-preferences-view-controller.php' => array(
    		'WPXtremePreferencesViewController',
    		'WPXtremePreferencesWPXStoreBranchView',
    		'WPXtremePreferencesAppearanceBranchView',
    		'WPXtremePreferencesListTableBranchView',
    		'WPXtremePreferencesImportExportView',
    		'WPXtremePreferencesAppearanceBranchPopover',
        'WPXtremePreferencesCoreBranchView'
    		),

    	$this->classesPath . 'preferences/wpxm-preferences.php' => array(
    		'WPXtremePreferences',
    		'WPXtremePreferencesWPXStoreBranch',
    		'WPXtremePreferencesAppearanceBranch',
    		'WPXtremePreferencesListTableBranch',
    		'WPXtremePreferencesCoreBranch'
    		),

    	$this->classesPath . 'welcome_tour/wpxm-welcome-tour-modal-dialog.php' => array(
    		'WPXtremeWelcomeTourModalDialog',
    		)
    	);

    return $includes;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Standard WordPress hook methods to override
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Fires on Ajax.
   */
  public function ajax()
  {
    WPXtremeAjax::init();

    // Enhancer post must be here in order to work on quick edit post
    if( is_admin() ) {
      WPXtremeEnhancerPost::init();
    }
  }

  /**
   * Fires when admin
   */
  public function admin()
  {
    WPXtremeAdmin::init();
  }

  /**
   * Fires when ready to init preferences plugin.
   */
  public function preferences()
  {

    // Init the issue report
    WPXtremeIssueReportState::init();

    // Init preferences
    WPXtremePreferences::init();
  }

  /**
   * Add to 'http_api_curl' WordPress action a patch for shared hosting with some SSL certificate problems.
   * Thanks to Walter Franchetti: http://walterfranchetti.it/2013/01/wp_http-and-the-ssl-cert-problem/
   *
   * @param mixed &$aCurlHandler The cURL actual handler initialized by WordPress. Note that this parameter is passed
   *                             by reference, as the action needs.
   *
   * @since 0.9.0
   *
   */
  public function curlEnableSSLOnSharedHosting( &$aCurlHandler )
  {
    // Add proper handling of CA certificate only if I'm in ssl_verify mode
    if ( true == $this->_flagSSLVerify ) {
      curl_setopt( $aCurlHandler, CURLOPT_CAINFO, $this->_caCertPath );
    }

  }

  /**
   * Hook to 'https(_local)_ssl_verify' WordPress filter in order to catch if cURL transaction has ssl_verify flag
   * enabled.
   *
   * @param boolean $bSSLFlag The current value of ssl_verify flag into cURL transaction
   *
   * @return boolean Current value of ssl_verify flag, without any changing.
   *
   * @since 0.9.0
   *
   */
  public function curlGetFlagSSLVerify( $bSSLFlag )
  {
    $this->_flagSSLVerify = $bSSLFlag;
    return $bSSLFlag;
  }

  /**
   * Fires on plugin activation.
   */
  public function activation()
  {

    // Delete the transient plugins update list
    delete_site_transient( 'update_plugins' );

    // Create database logs table
    WPXLogs::init()->create_table();

    // Create database for countries (geo-localization)
    // We use a custom method `update_table()` instead `->table->update_table()` for data population
    WPXCountries::init()->update_table();

    // Do a delta (update) of preferences
    WPXtremePreferences::init()->delta();
  }

  /**
   * Fires on plugin deactivation.
   */
  public function deactivation()
  {
    // Delete the transient plugins update list
    delete_site_transient( 'update_plugins' );
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Deep debug tool
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Fires when a deprecated function is called.
   *
   * @since WP 2.5.0
   * @since 0.7.5
   *
   * @param string $function    The function that was called.
   * @param string $replacement The function that should have been called.
   * @param string $version     The version of WordPress that deprecated the function.
   */
  public function deprecated_function_run()
  {
    /**
   	 * Filter whether to show the backtrace.
   	 *
   	 * Returning false to avoif the backtrace.
   	 *
   	 * @param bool $backtrace Whether the backtrace. Default true.
   	 */
    $pass = apply_filters( 'deprecated_function_trigger_error', true );

    if( ( true === WPXTREME_IMPROVE_DEBUG_OUTPUT && $pass ) || WPXtremePreferences::init()->core->improve_debug ) {
      $functions = wp_debug_backtrace_summary( null, 0, false );
      $functions = array_slice( $functions, 3 );
      $index     = 1;
      $output    = WPDK_CRLF . '--- START BACKTRACE ------------------------------------------------' . WPDK_CRLF;
      foreach( $functions as $func ) {
        $output .= sprintf( '  %d) %s %s', $index++, $func, WPDK_CRLF );
      }
      $output .= '--- END BACKTRACE ------------------------------------------------' . WPDK_CRLF;
      trigger_error( $output );
    }
  }

  /**
   * Called when debug is on.
   *
   * @param $errno
   * @param $errstr
   * @param $errfile
   * @param $errline
   *
   * @return bool
   */
  public function errorHandler( $errno, $errstr, $errfile, $errline )
  {
    if ( !( error_reporting() & $errno ) ) {

      // This error code is not included in error_reporting
      return false;
    }

    $functions = wp_debug_backtrace_summary( null, 0, false );
    $functions = array_slice( $functions, 3 );
    $index     = 1;
    $output    = PHP_EOL . '<pre>--- START BACKTRACE ------------------------------------------------' . PHP_EOL;
    foreach ( $functions as $func ) {
      $output .= sprintf( '  %d) %s %s', $index++, $func, PHP_EOL );
    }
    $output .= '--- END BACKTRACE ------------------------------------------------</pre>';
    //            $output .= sprintf( '[%s] in %s at line %s - ERRNO [%d]', $errstr, $errfile, $errline, $errno );
    trigger_error( $output );

    /*
     * There is no need to output error line if this function returns FALSE:
     * in this case, all stuffs are normally handled by PHP core, and message is displayed anyway;
     * see http://it2.php.net/manual/en/function.set-error-handler.php.
     */
    return false;

  }

  /**
   * Do log for wpXtreme in easy way
   *
   * @param mixed  $txt
   * @param string $title Optional. Any free string text to context the log
   *
   */
  public static function log( $txt, $title = '' )
  {
    // Create instance of WPDKWatchDog
    $log = new WPDKWatchDog( trailingslashit( dirname( __FILE__ ) ) );

    // Used for var args
    call_user_func_array( array( $log, 'log' ), func_get_args() );
  }

  /**
   * Debug the caller
   *
   * @since 1.4.0
   * @access private
   */
  public static function caller()
  {
    $e = new Exception();
    self::log( $e->getTraceAsString() );
  }

}