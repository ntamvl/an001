<?php

/**
 * Manage the static status of wpXtreme issue report. This class replace the old configuration model.
 * The issue report information are stored in a site transient for EXPIRY constant time.
 *
 * @class           WPXtremeIssueReportState
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-02-01
 * @version         1.2.0
 * @since           1.0.3
 *
 */
final class WPXtremeIssueReportState {

  // Storage key for users
  const KEY = 'wpxtreme-issue-report';

  /**
   * The issue report infotmation are stored in a transient
   *
   * @since 1.4.16
   */
  const EXPIRY = HOUR_IN_SECONDS;

  /**
   * int - Issue Report Mode is enabled.
   *
   * @since 1.0.0
   */
  const ENABLED = 1;

  /**
   * int - Issue Report Mode is just disabled. It's time to send issue report to wpXtreme
   *
   * @since 1.0.0
   */
  const READY_TO_SEND = 2;

  /**
   * int - Issue Report Mode is disabled: normal WordPress behaviour.
   *
   * @since 1.0.0
   */
  const DISABLED = 3;

  /**
   * Issue Report state: see constant
   *
   * @var int $status
   */
  public $state;

  /**
   * Return a singleton instance of WPXtremeIssueReportState class
   *
   * @return WPXtremeIssueReportState
   */
  public static function init()
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = get_site_transient( self::KEY );
      if ( empty( $instance ) ) {
        $instance = new self;
        set_site_transient( self::KEY, $instance, self::EXPIRY );
      }
    }

    return $instance;
  }


  /**
   * Create an instance of WPXtremeIssueReportState class
   *
   * @return WPXtremeIssueReportState
   */
  public function __construct()
  {
    $this->defaults();
  }

  /**
   * Set defaults
   */
  public function defaults()
  {
    $this->state = self::DISABLED;
  }

  /**
   * Utility ethod for enable
   */
  public function enable()
  {
    $this->state = self::ENABLED;
    $this->update();
  }

  /**
   * Utility ethod for disable
   */
  public function disable()
  {
    $this->state = self::DISABLED;
    $this->update();
  }

  /**
   * Utility ethod for ready to send
   */
  public function readyToSend()
  {
    $this->state = self::READY_TO_SEND;
    $this->update();
  }

  /**
   * Update
   */
  public function update()
  {
    set_site_transient( self::KEY, $this, self::EXPIRY );
  }

  /**
   * Delete
   */
  public function delete()
  {
    delete_site_transient( self::KEY );
  }

}


/**
 * Manage model of wpXtreme Issue Report
 *
 * @class              WPXtremeIssueReport
 * @author             yuma <info@wpxtre.me>
 * @copyright          Copyright (C) 2013- wpXtreme Inc. All Rights Reserved.
 * @date               2014-01-16
 * @version            1.0.3
 * @since              1.0.0.b3
 *
 */
class WPXtremeIssueReport {

  /**
   * int - Maximum length of description field, in bytes
   *
   * @since 1.0.0
   */
  const MAX_LENGTH_DESCRIPTION = 784;

  /**
   * int - Response status error: server validation problems
   *
   * @since 1.0.0
   */
  const RESPONSE_UNPROCESSABLE_ENTITY = 422;

  /**
   * int - Response status OK from REST URL
   *
   * @since 1.0.0
   */
  const RESPONSE_SUCCESS = 201;

  /**
   * int - Response timeout in seconds
   *
   * @since 1.0.0
   */
  const RESPONSE_TIMEOUT = 48;

  /**
   * string - User agent used in API request
   *
   * @since 1.0.0
   */
  const USER_AGENT = 'wpXtremeIssueReport/1.0';

  /**
   * Developer Center API end point
   *
   * @since 1.1.12
   */
  const DEVELOPER_CENTER_API_END_POINT = 'https://developer.wpxtre.me/api/v1/issue_reports';

  /**
   * REST server URL to send report to.
   *
   * @var string $restServerURL
   *
   * @since 1.0.0
   */
  public $restServerURL;

  /**
   * The issue report state
   *
   * @var WPXtremeIssueReportState $configuration
   *
   * @since 1.0.0
   */
  private $configuration;

  /**
   * File name of PHP log file created after enabling issue report mode.
   *
   * @var string $_phpLogFileName
   *
   * @since 1.0.0
   */
  private $_phpLogFileName;

  /**
   * Return a singleton instance of WPXtremeIssueReport class
   *
   * @return WPXtremeIssueReport
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
   * Create an instance of WPXtremeIssueReport class
   *
   * @return WPXtremeIssueReport
   */
  private function __construct()
  {

    // Get configuration instance to determinate which view to display
    $this->configuration = WPXtremeIssueReportState::init();

    // the log file with PHP E_ALL messages
    $this->_phpLogFileName = WP_CONTENT_DIR . '/wpxtreme.log';

    // the REST server URL
    $this->restServerURL = self::DEVELOPER_CENTER_API_END_POINT;

  }

  /**
   * Return the state stored in the configuration: DISABLED, ENABLED or READY_TO_SEND
   *
   * @return int
   */
  public function state()
  {
    return $this->configuration->state;
  }

  /**
   * Enable full PHP error reporting on a specific log file. This function enable full PHP error reporting on a
   * specific file, regardless to WP_DEBUG settings, EXACTLY FROM THE PLACE WHERE IT IS EXECUTED ON.
   *
   * @since 1.0.0
   *
   */
  public function enablePHPErrorLog()
  {
    error_reporting( E_ALL & ~E_STRICT );
    @ini_set( 'display_errors', 0 );
    @ini_set( 'log_errors', 1 );
    @ini_set( 'error_log', $this->_phpLogFileName );
  }

  /**
   * This function delete log files, thus cleaning all issue report environment.
   *
   * @return boolean TRUE if operation has been successfully executed, FALSE otherwise
   *
   * @since 1.0.0
   *
   */
  public function deleteLogFiles()
  {

    if ( file_exists( $this->_phpLogFileName ) ) {
      return @unlink( $this->_phpLogFileName );
    }

    return false;

  }

  /**
   * Build detailed log to send to wpXtreme. This log contains all stuffs that can help us to reproduce and fix the
   * issue.
   *
   * @return array An array of arrays of key=>values related to every single info collected. The main entry keys of
   *               this
   *               array is 'system', 'plugins', 'php_error_log'.
   *
   * @since        1.0.0
   *
   */
  public function buildDetailedIssueLog()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wp_version, $wp_scripts, $wp_styles;

    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $aOutput = array();

    //-----------------------------------------------------------------------------------------
    // System info
    //-----------------------------------------------------------------------------------------

    // Get Javascript library from cookie.
    //$aOutput['system'] = (array)json_decode( stripslashes( $_COOKIE['wpdk_javascript_library_versions'] ) );

    // Avoid disabled for security reason
    try {
      $php_uname = @php_uname();
    }
    catch ( Exception $e ) {
      $php_uname = $e->getMessage();
    }

    $aOutput['system']['OS']         = $php_uname;
    $aOutput['system']['OS basic']   = PHP_OS;
    $aOutput['system']['Web Server'] = ( isset( $_SERVER['SERVER_SOFTWARE'] ) ) ? $_SERVER['SERVER_SOFTWARE'] : __( '[unable to get Web Server info]', WPXTREME_TEXTDOMAIN );
    $aOutput['system']['PHP']        = PHP_VERSION;
    $aOutput['system']['MySQL']      = $wpdb->db_version();
    $aOutput['system']['WordPress']  = $wp_version;
    $aOutput['system']['Site URL']   = home_url();

    //-----------------------------------------------------------------------------------------
    // Javascript info (via cookie)
    //-----------------------------------------------------------------------------------------

    // Get Javascript library from cookie.
    $aOutput['javascript'] = (array) json_decode( stripslashes( $_COOKIE['wpdk_javascript_library_versions'] ) );

    //-----------------------------------------------------------------------------------------
    // Registered scripts info
    //-----------------------------------------------------------------------------------------

    // Stability - since 1.4.2
    if( is_object( $wp_scripts ) && isset( $wp_scripts->registered ) ) {
      $scripts_keys = array_keys( $wp_scripts->registered );
      if( !empty( $scripts_keys ) ) {
        sort( $scripts_keys );
        foreach( $scripts_keys as $handle ) {
          $src                                        = $wp_scripts->registered[ $handle ]->src;
          $ver                                        = empty( $wp_scripts->registered[ $handle ]->ver ) ? '' : sprintf( 'v%s - ', $wp_scripts->registered[ $handle ]->ver );
          $deps                                       = empty( $wp_scripts->registered[ $handle ]->deps ) ? '' : sprintf( ' - DEP (%s)', implode( ', ', $wp_scripts->registered[ $handle ]->deps ) );
          $aOutput[ 'registered_scripts' ][ $handle ] = sprintf( '%s%s%s', $ver, $src, $deps );
        }
      }
    }

    //-----------------------------------------------------------------------------------------
    // Registered styles info
    //-----------------------------------------------------------------------------------------

    // Stability - since 1.4.2
    if( is_object( $wp_styles ) && isset( $wp_styles->registered ) ) {
      $styles_keys = array_keys( $wp_styles->registered );
      if( !empty( $styles_keys ) ) {
        sort( $styles_keys );
        foreach( $styles_keys as $handle ) {
          $src                                       = $wp_styles->registered[ $handle ]->src;
          $ver                                       = empty( $wp_styles->registered[ $handle ]->ver ) ? '' : sprintf( 'v%s - ', $wp_styles->registered[ $handle ]->ver );
          $deps                                      = empty( $wp_styles->registered[ $handle ]->deps ) ? '' : sprintf( ' - DEP (%s)', implode( ', ', $wp_styles->registered[ $handle ]->deps ) );
          $aOutput[ 'registered_styles' ][ $handle ] = sprintf( '%s%s%s', $ver, $src, $deps );
        }
      }
    }

    //-----------------------------------------------------------------------------------------
    // Plugins info
    //-----------------------------------------------------------------------------------------

    // Include the needed WordPress code
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    $aPluginsData = get_plugins();
    foreach ( $aPluginsData as $aPlugin ) {
      $sName                        = $aPlugin['Name'];
      $aOutput['plugins'][ $sName ] = $aPlugin['Version'];
    }

    //-----------------------------------------------------------------------------------------
    // PHP info about installation - commented by now
    //-----------------------------------------------------------------------------------------

    //        ob_start();
    //        phpinfo(-1);
    //        $sPHPInfo = ob_get_contents();
    //        ob_end_clean();
    //        $aValues = array();
    //        if( preg_match_all( '/<tr><td class="e">(.*?)<\/td><td class="v">(.*?)<\/td>(:?<td class="v">(.*?)<\/td>)?<\/tr>/',
    //                            $sPHPInfo, $aValues, PREG_SET_ORDER )) {
    //          foreach( $aValues as $aValue) {
    //            if( '<i>no value</i>' == $aValue[2] ) continue;
    //            $aOutput['php_info_log'][$aValue[1]] = $aValue[2];
    //          }
    //        }

    //-----------------------------------------------------------------------------------------
    // PHP Error log info
    //-----------------------------------------------------------------------------------------

    $aOutput['php_error_log']['log file'] = $this->_phpLogFileName;
    $sLogContent                          = '';
    if ( file_exists( $this->_phpLogFileName ) ) {
      $sLogContent = file_get_contents( $this->_phpLogFileName );
    }
    $aOutput['php_error_log']['log content'] = $sLogContent;

    return $aOutput;

  }

  /**
   * Return the text od issue Report
   *
   * @return string
   */
  public function issueReport()
  {

    $aDetailedLog = $this->buildDetailedIssueLog();
    $sLog         = '';
    foreach ( $aDetailedLog as $sArea => $aType ) {
      $sLog .= "------------------------------------" . PHP_EOL;
      $sLog .= "-  " . str_replace( '_', ' ', strtoupper( $sArea ) ) . PHP_EOL;
      $sLog .= "------------------------------------" . PHP_EOL;
      foreach ( $aType as $sWhat => $sValue ) {
        $sLog .= $sWhat . ' === ';
        if ( ! empty( $sValue ) ) {
          $sLog .= $sValue . PHP_EOL;
        }
        else {
          $sLog .= '[[VALUE NOT AVAILABLE]]' . PHP_EOL;
        }
      }
    }

    return $sLog;

  }

}


/**
 * The wpXtreme Issue Report View
 *
 * @class              WPXtremeIssueReportView
 * @author             yuma <info@wpxtre.me>
 * @copyright          Copyright (C) 2013- wpXtreme Inc. All Rights Reserved.
 * @date               2013-01-16
 * @version            1.0.0
 *
 */
class WPXtremeIssueReportView extends WPDKView {

  /**
   * Return an instance of WPXtremeIssueReportView class
   *
   * @return WPXtremeIssueReportView
   */
  public function __construct()
  {
    parent::__construct( 'wpxtreme-issue-report' );
  }

  /**
   * Display a view for state
   */
  public function draw()
  {

    switch ( WPXtremeIssueReport::init()->state() ) {
      case WPXtremeIssueReportState::DISABLED:
        $this->_drawForStateDisable();
        break;
      case WPXtremeIssueReportState::ENABLED:
        $this->_drawForStateEnabled();
        break;
      case WPXtremeIssueReportState::READY_TO_SEND:
        $this->_drawForStateReadyToSend();
        break;
    }
  }

  /**
   * Display
   */
  private function _drawForStateDisable()
  {
    ?>
    <h2><?php _e( 'How the wpXtreme Issue Report works', WPXTREME_TEXTDOMAIN ) ?></h2>
    <p><?php _e( 'If you find any bug or if you want to notice an issue about your wpXtreme experience to our team, here is the right place.', WPXTREME_TEXTDOMAIN ) ?></p>
    <ol>
        <li><?php _e( 'First of all, <strong>start the Issue Report Mode</strong> by clicking the button below. By enabling Issue Report Mode, you will start to collect and record ALL EVENTUAL debug messages in a single log file. This log file will be used by our team in order to properly reproduce and fix the issue you have detected', WPXTREME_TEXTDOMAIN ) ?></li>
        <li><?php _e( 'Then, <strong>properly reproduce</strong> your environment behavior that causes the issue you want to signal to our team. To properly reproduce means to navigate your environment in order to replay all the stuffs that cause the issue: entering pages, filling text fields, clicking buttons or links, etc.', WPXTREME_TEXTDOMAIN ) ?></li>
        <li><?php _e( 'When the behavior that causes the issue has been exactly reproduced, return to this page, disable Issue Report Mode and follow the instructions', WPXTREME_TEXTDOMAIN ) ?></li>
    </ol>
    <p style="text-align:center">
      <a href="#"
         id="wpx-issue-report-start-recording"
         class="button-primary button-hero button"><?php _e( 'Start Recording', WPXTREME_TEXTDOMAIN ) ?></a></p>
  <?php
  }

  /**
   * Display
   */
  private function _drawForStateEnabled()
  {
    ?>
    <h2><?php _e( 'Ready to stop recording of debug messages!', WPXTREME_TEXTDOMAIN ) ?></h2>
    <p><?php _e( 'Your WordPress environment has now enabled the wpXtreme Issue Report Mode. It means that you are now collecting and recording ALL EVENTUAL debug messages in a log file, that will be used by our team in order to properly reproduce and fix the issue you indicated.', WPXTREME_TEXTDOMAIN ) ?></p>
    <p><?php _e( 'If you have just reproduced the behaviour that causes the issue, click the Stop Recording button below, and follow the instructions.', WPXTREME_TEXTDOMAIN ) ?></p>
    <p><?php _e( 'If you did not, please close this window with Close button below, and come back to <strong>properly reproduce</strong> your environment behavior that causes the issue you want to signal to our team.', WPXTREME_TEXTDOMAIN ) ?></p>
    <p style="text-align:center">
      <a href="#"
         id="wpx-issue-report-stop-recording"
         class="button-primary button-hero button"><?php _e( 'Stop Recording', WPXTREME_TEXTDOMAIN ) ?></a>
    </p>
  <?php
  }

  /**
   * Display
   */
  private function _drawForStateReadyToSend()
  {

    $user_id = get_current_user_id();
    $user    = new WPDKUser( $user_id );

    $fields = array(
      __( 'Ready to send report! Please, fill out the form below.', WPXTREME_TEXTDOMAIN ) => array(
        __( 'Your issue information has been successfully collected. Your WordPress environment is back now to your default reporting mode.', WPXTREME_TEXTDOMAIN ),
        array(
          array(
            'type'  => WPDKUIControlType::TEXT,
            'name'  => 'issue-report-name',
            'size'  => 20,
            'attrs' => array( 'maxlength' => 24 ),
            'value' => $user->display_name,
            'label' => array(
              'value' => __( 'Name', WPXTREME_TEXTDOMAIN ),
              'data'  => array( 'placement' => 'right' )
            ),
            'title' => __( 'wpXtreme team will use this name in all interactions with you related to this issue.', WPXTREME_TEXTDOMAIN ),
          ),
          array(
            'type'  => WPDKUIControlType::TEXT,
            'name'  => 'issue-report-email',
            'size'  => 26,
            'attrs' => array( 'maxlength' => 48 ),
            'value' => $user->email,
            'label' => __( 'Email', WPXTREME_TEXTDOMAIN ),
            'title' => __( 'Our team will use this email address in all interactions with you about this issue. PLEASE ENTER HERE A VALID EMAIL ADDRESS.', WPXTREME_TEXTDOMAIN ),
          ),
        ),
        array(
          array(
            'type'  => WPDKUIControlType::TEXT,
            'name'  => 'issue-report-title',
            'attrs' => array( 'maxlength' => 84 ),
            'label' => array(
              'value' => __( 'Issue title', WPXTREME_TEXTDOMAIN ),
              'data'  => array( 'placement' => 'right' )
            ),
            'title' => __( 'Enter a short text that describes the issue you want to signal.', WPXTREME_TEXTDOMAIN ),
          )
        ),
        array(
          array(
            'type'  => WPDKUIControlType::TEXTAREA,
            'name'  => 'issue-report-own-description',
            'attrs' => array( 'maxlength' => WPXtremeIssueReport::MAX_LENGTH_DESCRIPTION ),
            'label' => __( 'Your own description', WPXTREME_TEXTDOMAIN ),
            'title' => __( 'Enter a detailed description of the issue you want to signal. This field is not mandatory; however, we strongly recommend you to add detailed information to your report.', WPXTREME_TEXTDOMAIN ),
          )
        ),
        array(
          array(
            'type'  => WPDKUIControlType::TEXTAREA,
            'name'  => 'issue-report-description',
            'label' => __( 'Report (for your privacy, you can cut any info you want)', WPXTREME_TEXTDOMAIN ),
            'title' => __( 'We collect here the smallest and safe information we need about your WordPress environment, in order to reproduce and fix your issue. You can cut any private information you don\'t want to share with us; simply remember that the more info you cut from this report, the harder the issue will be analyzed, reproduced and fixed.', WPXTREME_TEXTDOMAIN ),
            'class' => 'wpx-monitor',
            'value' => WPXtremeIssueReport::init()->issueReport()
          )
        ),
      )
    );

    $layout = new WPDKUIControlsLayout( $fields );

    ?>
    <form name="issue-report" method="POST" action="">
      <?php $layout->display() ?>
    </form>
  <?php
  }

  /**
   * Return the HTML markup for twitter bootstrap modal
   *
   * @return string
   */
  public function modal()
  {
    $content = $this->html();

    $modal_issue_report = new WPDKUIModalDialog( 'issue-report', __( 'Please read very carefully the instructions below', WPXTREME_TEXTDOMAIN ), $content );

    if ( WPXtremeIssueReportState::READY_TO_SEND == WPXtremeIssueReport::init()->state() ) {
      $modal_issue_report->addButton( 'wpx-issue-report-clear', __( 'Clear logs and cancel sending', WPXTREME_TEXTDOMAIN ), false, 'button-primary alignleft' );
      $modal_issue_report->addButton( 'wpx-issue-report-send', __( 'Send report!', WPXTREME_TEXTDOMAIN ), false, 'button-primary alignright' );
    }
    else {
      $modal_issue_report->addButton( 'close', __( 'Close', WPXTREME_TEXTDOMAIN ) );
    }

    return $modal_issue_report->html();
  }

  /**
   * Return the HTML markup for updated footer status of issue report
   *
   * @return string
   */
  public function footer()
  {
    $status_id = WPXtremeIssueReport::init()->state();
    $status    = array(
      WPXtremeIssueReportState::DISABLED      => __( 'Ready', WPXTREME_TEXTDOMAIN ),
      WPXtremeIssueReportState::ENABLED       => __( 'Recording...' .
                                                     WPDKGlyphIcons::html( WPDKGlyphIcons::SPIN1 ), WPXTREME_TEXTDOMAIN ),
      WPXtremeIssueReportState::READY_TO_SEND => __( 'Ready to send', WPXTREME_TEXTDOMAIN )
    );

    return sprintf( '<span id="wpxm-issue-report-status-indicator" data-status="%s" class="wpxm-issue-report-status-%s">(%s)</span>', $status_id, $status_id, $status[ $status_id ] );
  }

  /**
   * Return an array in sdf format for form fileds
   *
   * @return array
   */
  public function fields()
  {
    $aIssueReport = array( __( 'Report Console', WPXTREME_TEXTDOMAIN ) => array() );
    $sKey         = key( $aIssueReport );

    switch ( WPXtremeIssueReport::init()->state() ) {

      case WPXtremeIssueReportState::DISABLED:

        $aIssueReport[ $sKey ][] =
          __( 'If you found a bug, a notice, or if you want to send an issue to wpXtreme team about your wpXtreme experience, here is the right place.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'Before continuing, please <strong>read very carefully</strong> the instructions below:', WPXTREME_TEXTDOMAIN ) .
          '<br/>' . '<ol>' . '<li>' .
          __( 'First of all, enable the Issue Report Mode and click the Update button. By enabling Issue Report Mode, you start to collect ALL EVENTUAL PHP debug messages in a single log file. This log file will be used from wpXtreme Team in order to properly reproduce and fix the issue you signal.', WPXTREME_TEXTDOMAIN ) .
          '</li>' . '<li>' .
          __( 'After enabling Issue Report Mode, please <strong>reproduce</strong> your environment behaviour that causes the issue you want to signal to our team. To properly reproduce means to navigate your environment in order to replay all the stuffs that cause the issue: entering pages, filling text fields, clicking buttons or links, etc...', WPXTREME_TEXTDOMAIN ) .
          '</li>' . '<li>' .
          __( 'When the behaviour that causes the issue has been exactly reproduced, return to this page, disable Issue Report Mode, and click the Update button. This operation returns your WordPress environment to your default standard behaviour.', WPXTREME_TEXTDOMAIN ) .
          '</li>' . '<li>' .
          __( 'Once the Issue Report Mode is disabled and updated, the console will show you a global report of your issue. This report will be sent to our team in order to examine the issue and eventually fix it; it will contain all basic information about your WordPress environment like software versions, PHP log messages, the list of your plugins, etc... Please read very carefully this report; we collect all the information we need related to your WordPress environment, and moreover you can modify or cut any private information you do not want to share with us. Simply remember that if you cut too much info from the report, it will be harder to fix the issue.', WPXTREME_TEXTDOMAIN ) .
          '</li>' . '</ol>';

        $sChecked = '';
        $sLabel   = __( 'Enable Issue Report Mode.', WPXTREME_TEXTDOMAIN );

        $aIssueReport[ $sKey ][] = array(
          array(
            'type'    => WPDKUIControlType::CHECKBOX,
            'name'    => 'enable_issue_report_mode',
            'label'   => $sLabel,
            'value'   => WPXtremeIssueReportState::ENABLED,
            'checked' => $sChecked
          )
        );

        break;

      case WPXtremeIssueReportState::ENABLED:

        $aIssueReport[ $sKey ][] =
          __( 'Your WordPress environment has now enabled the wpXtreme Issue Report Mode. It means that you are now collecting and recording ALL EVENTUAL debug messages in a log file, that will be used by our team in order to properly reproduce and fix the issue you indicated.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'Now it\'s time to <strong>properly reproduce</strong> your environment behavior that causes the issue you want to signal to our team. To properly reproduce means to navigate your environment in order to replay all the stuffs that cause the issue: entering pages, filling text fields, clicking buttons or links, etc...', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'When the behaviour that causes the issue has been exactly reproduced, return to this page, disable Issue Report Mode, and click the Update button. This operation returns your WordPress environment to your default standard behaviour.', WPXTREME_TEXTDOMAIN );

        $sChecked = 'TRUE';
        $sLabel   = __( 'Disable Issue Report Mode and return to default WordPress behaviour.', WPXTREME_TEXTDOMAIN );

        $aIssueReport[ $sKey ][] = array(
          array(
            'type'    => WPDKUIControlType::CHECKBOX,
            'name'    => 'enable_issue_report_mode',
            'label'   => $sLabel,
            'value'   => WPXtremeIssueReportState::READY_TO_SEND,
            'checked' => $sChecked
          )
        );

        break;

      case WPXtremeIssueReportState::READY_TO_SEND:

        $aIssueReport[ $sKey ][] =
          __( 'The information about your issue has been successfully collected. Now your Wordpress environment is working with your default error reporting mode.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'You can read below all information related to your issue. These information will be sent to or team, in order to open an internal issue and start to resolve your problem.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'Please <strong>read very carefully</strong> this report; we collect all the information we need related to your WordPress environment, and moreover you can modify or cut any private information you do not want to share with us. Simply remember that if you cut too much info from the report, it will be harder to fix the issue.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'Please <strong>insert an issue title and a detailed description</strong> in order to complete this report.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>' .
          __( 'The log file and any debug messages will be deleted from your environment after the delivery of this report.', WPXTREME_TEXTDOMAIN ) .
          '<br/><br/>';

        $aIssueReport[ $sKey ][] = array(
          array(
            'type'  => WPDKUIControlType::TEXT,
            'name'  => 'issue_title',
            'size'  => '64',
            'label' => __( 'Issue Title', WPXTREME_TEXTDOMAIN ),
            'title' => __( 'Issue Title', WPXTREME_TEXTDOMAIN ),
            'value' => ''
          )
        );

        $aIssueReport[ $sKey ][] = array(
          array(
            'type'  => WPDKUIControlType::TEXTAREA,
            'name'  => 'issue_description',
            'cols'  => '64',
            'rows'  => '12',
            'label' => __( 'Issue Description', WPXTREME_TEXTDOMAIN ),
            'title' => __( 'Issue Description', WPXTREME_TEXTDOMAIN ),
            'value' => ''
          )
        );

        //-----------------------------------------------------------------------------------------
        // Build detailed log to be shown in Issue Log TEXTAREA
        //-----------------------------------------------------------------------------------------

        $cIssue       = WPXtremeIssueReport::init();
        $aDetailedLog = $cIssue->buildDetailedIssueLog();
        $sLog         = '';
        foreach ( $aDetailedLog as $sArea => $aType ) {
          $sLog .= "------------------------------------" . PHP_EOL;
          $sLog .= "-  " . strtoupper( $sArea ) . PHP_EOL;
          $sLog .= "------------------------------------" . PHP_EOL;
          foreach ( $aType as $sWhat => $sValue ) {
            $sLog .= $sWhat . ' === ';
            if ( ! empty( $sValue ) ) {
              $sLog .= $sValue . PHP_EOL;
            }
            else {
              $sLog .= '[[VALUE NOT SET]]' . PHP_EOL;
            }
          }
        }

        $aIssueReport[ $sKey ][] = array(
          array(
            'type'  => WPDKUIControlType::TEXTAREA,
            'name'  => 'issue_log',
            'cols'  => '64',
            'rows'  => '64',
            'label' => __( 'Issue Log', WPXTREME_TEXTDOMAIN ),
            'title' => __( 'Issue Log', WPXTREME_TEXTDOMAIN ),
            'value' => $sLog
          )
        );

        break;

    }

    return $aIssueReport;

  }

}