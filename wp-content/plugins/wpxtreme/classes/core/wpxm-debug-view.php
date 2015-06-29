<?php

/**
 * Debug Window View
 *
 * @class           WPXtremeDebugView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-01-29
 * @version         1.0.0
 * @since           1.1.20
 *
 */
final class WPXtremeDebugView extends WPDKView {

  const ID = 'wpxm-debug-view';

  /**
   * Return a singleton instance of WPXtremeDebugView class
   *
   * @return WPXtremeDebugView
   */
  public static function init()
  {
    static $instance = null;
    if( is_null( $instance ) ) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * Create an instance of WPXtremeDebugView class
   *
   * @return WPXtremeDebugView
   */
  public function __construct()
  {
    parent::__construct( self::ID );
  }

  /**
   * Display
   */
  public function draw()
  {
    ?>
    <style type="text/css">
      #wpxm-debug-view hr
      {
        margin : 32px 16px 0 0;
      }

      #wpxm-debug-view h2
      {
        text-align : center;
      }

      #wpxm-debug-view .wpx-issue-report
      {
        max-height : 300px;
        overflow-y : scroll;
      }

      #wpxm-logs-container
      {
        margin-right : 16px;
      }

      table#wpxm-logs th
      {
        padding          : 8px;
        background-color : #888;
        color            : #fff;
        text-align       : left;
      }

      table#wpxm-logs td
      {
        padding       : 8px;
        border-bottom : 1px solid #aaa;
      }

    </style>


    <hr/><h2>WPX Debug</h2>
    <pre class="wpdk-monitor wpx-issue-report"><?php echo WPXtremeIssueReport::init()->issueReport() ?></pre>

    <hr/><h2>WPX Logs</h2>
    <p>Use <code>WPXLogs::init()->log( 'owner', 'description', WPXLogs::SEVERITY_HIGH );</code></p>
    <?php

    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Logs table name
    $table_name = WPXLogs::init()->table_name;

    $sql = <<<SQL
SELECT *

FROM ( {$table_name} AS logs )

ORDER BY date DESC
SQL;

    $results = $wpdb->get_results( $sql, ARRAY_A );

    if( empty( $results ) ) {
      return;
    }

    ?>
    <div id="wpxm-logs-container">
      <table id="wpxm-logs" width="100%" border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
          <th><?php _e( '!' ) ?></th>
          <th><?php _e( 'Date' ) ?></th>
          <th><?php _e( 'Owner' ) ?></th>
          <th><?php _e( 'Log' ) ?></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach( $results as $log ) : ?>
          <tr class="<?php echo $log[ WPXLogs::COLUMN_SEVERITY ] ?>">
            <td><?php echo $log[ WPXLogs::COLUMN_SEVERITY ] ?></td>
            <td><?php echo WPDKDateTime::format( $log[ WPXLogs::COLUMN_DATE ], WPDKDateTime::MYSQL_DATE_TIME ) ?></td>
            <td><?php echo $log[ WPXLogs::COLUMN_OWNER ] ?></td>
            <td><?php echo $log[ WPXLogs::COLUMN_LOG ] ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>

      </table>
    </div>

    <hr/><h2>WPX SandBox (see free file wpxm-debug.php)</h2>
  <?php

    @include_once( WPXTREME_PATH_CLASSES . 'admin/wpxm-debug.php' );
  }

}