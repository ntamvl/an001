<?php

/**
 * wpXtreme database logs
 *
 * @class           WPXLogs
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-04-30
 * @version         1.0.0
 * @since           1.2.7
 *
 */
class WPXLogs {

  // database table name - without prefix
  const TABLE_NAME = 'wpx_logs';

  // Table columns
  const COLUMN_ID       = 'id';
  const COLUMN_DATE     = 'date';
  const COLUMN_SEVERITY = 'severity';
  const COLUMN_OWNER    = 'owner';
  const COLUMN_LOG      = 'log';

  // Severity enum constants
  const SEVERITY_LOW    = 'low';
  const SEVERITY_MEDIUM = 'medium';
  const SEVERITY_HIGH   = 'high';

  /**
   * Databse table name with WordPress prefix
   *
   * @var string $table_name
   */
  public $table_name = '';

  /**
   * Return a singleton instance of WPXLogs class
   *
   * @return WPXLogs
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
   * Create an instance of WPXLogs class
   *
   * @return WPXLogs
   */
  public function __construct()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Build the table name
    $this->table_name = sprintf( '%s%s', $wpdb->prefix, self::TABLE_NAME );

    // Fires when a wpXtreme logs is required
    // TODO Enabled by defines or preferences?
    add_action( 'wpxm_logs', array( $this, 'log' ), 10, 3 );
  }

  /**
   * This method is called on wpXtreme activation and create the database log table
   */
  public function create_table()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Hide database warning and error
    $wpdb->hide_errors();
    $wpdb->suppress_errors();

    if( !function_exists( 'dbDelta' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    }

    $sql = <<<SQL
CREATE TABLE {$this->table_name} (
  id bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  date timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  owner varchar(255) NOT NULL DEFAULT '',
  log text,
  severity enum('low','medium','high') DEFAULT 'low',
  PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
SQL;

    // Buffering
    ob_start();

    // Execute delta/creation
    @dbDelta( $sql );

    // Clear error
    global $EZSQL_ERROR;
    $EZSQL_ERROR = array();

  }

  /**
   * Write a row log
   *
   * @param string $owner    Owner, usually the class name of Plugin
   * @param string $log      Free text
   * @param string $severity Optional. Severity, default 'low'
   */
  public function log( $owner, $log, $severity = self::SEVERITY_LOW )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Stability
    if( empty( $owner ) && empty( $log ) ) {

      // Error
      $wpdb->insert( $this->table_name, array(
        self::COLUMN_OWNER    => 'Unknown',
        self::COLUMN_LOG      => __( 'Somebody write an empty log!' ),
        self::COLUMN_SEVERITY => self::SEVERITY_HIGH
      ) );

      /**
       * Fires when attempt an empty log.
       */
      do_action( 'wpx_empty_log' );

      return;
    }

    // Args
    $args = array(
      self::COLUMN_OWNER    => $owner,
      self::COLUMN_LOG      => $log,
      self::COLUMN_SEVERITY => $severity
    );

    // Logs
    $wpdb->insert( $this->table_name, $args );

    /**
     * Fires when a log is insert.
     *
     * @param array $args The logs args
     */
    do_action( 'wpx_logs', $args );

    /**
     * Fires when a log severity is insert. Useful to catch specific log.
     *
     * @param array $args The logs args
     */
    do_action( 'wpx_logs-' . $severity, $args );
  }

}