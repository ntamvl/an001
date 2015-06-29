<?php

/**
 * This class describe a single database row table
 *
 * @class           WPXCountry
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-09-25
 * @version         1.0.0
 * @since           1.4.0
 *
 */
class WPXCountry {

  // Row properties record
  public $id;
  public $code;
  public $continent;
  public $country;
  public $currency;
  public $isocode;
  public $status;
  public $symbol;
  public $symbol_html;
  public $tax;
  public $zone;

  /**
   * Table, override for IDE autocoplete
   *
   * @var WPXCountries $table
   */
  public $table;

  /**
   * Return a singleton instance of WPXCountry class
   *
   * @param int|array|object $id Optional. Any id, array or object
   *
   * @return WPXCountry
   */
  public static function init( $id = null )
  {
    // If null return a new empty instance
    if ( is_null( $id ) ) {
      return new self;
    }

    // String or ID
    $key = $id;

    // Object
    if ( is_object( $id ) ) {
      $key = get_class( $id );
    }

    // Array
    if ( is_array( $id ) ) {
      $key = $id[ WPXCountries::COLUMN_ID ];
    }

    // Sanitize key
    $key = md5( $key );

    // Instance array
    static $instance = array();

    if ( ! isset( $instance[ $key ] ) ) {
      $instance[ $key ] = new self( $id );
    }

    return $instance[ $key ];
  }

  /**
   * Create an instance of WPXCountry class
   *
   * @param int|array|object $id Optional. Any id, array or object
   *
   * @return WPXCountry
   */
  public function __construct( $id = null )
  {
    $this->table = WPXCountries::init()->table;
    $this->id    = $id;

    // Get columns and foreign data
    if ( !empty( $id ) ) {
      $this->columns();
    }
  }

  /**
   * Get the columns and foreign data
   */
  protected function columns()
  {
    // Get data
    $data = $this->table->select( array( WPXCountries::COLUMN_ID => $this->id ) );

    // Check result
    if ( empty( $data ) ) {
      return;
    }

    // Get single record
    $row = current( $data );

    // Set the properties
    foreach ( $row as $column => $value ) {
      $this->$column = $value;
    }
  }

  /**
   * Insert or Update this record by property
   *
   * @return WPDKError|int
   */
  public function commit()
  {
    // Get values by properties
    $values = array(
      WPXCountries::COLUMN_ID          => $this->id,
      WPXCountries::COLUMN_COUNTRY     => $this->country,
      WPXCountries::COLUMN_ISOCODE     => $this->isocode,
      WPXCountries::COLUMN_CURRENCY    => $this->currency,
      WPXCountries::COLUMN_SYMBOL      => $this->symbol,
      WPXCountries::COLUMN_SYMBOL_HTML => $this->symbol_html,
      WPXCountries::COLUMN_CODE        => $this->code,
      WPXCountries::COLUMN_ZONE        => $this->zone,
      WPXCountries::COLUMN_TAX         => $this->tax,
      WPXCountries::COLUMN_CONTINENT   => $this->country,
    );

    return empty( $this->id ) ? ( $this->id = $this->table->insert( $values ) ) : $this->table->update( $values );

  }

}

/**
 * Model used in list table view controller
 *
 * @class           WPXCountries
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-09-25
 * @version         1.0.0
 * @since           1.4.0
 *
 */
class WPXCountries extends WPDKDBListTableModel {

  // Table Columns
  const COLUMN_ID          = 'id';
  const COLUMN_ZONE        = 'zone';
  const COLUMN_COUNTRY     = 'country';
  const COLUMN_ISOCODE     = 'isocode';
  const COLUMN_CURRENCY    = 'currency';
  const COLUMN_SYMBOL      = 'symbol';
  const COLUMN_SYMBOL_HTML = 'symbol_html';
  const COLUMN_CODE        = 'code';
  const COLUMN_TAX         = 'tax';
  const COLUMN_CONTINENT   = 'continent';
  const COLUMN_STATUS      = 'status';

  // List table id
  const LIST_TABLE_SINGULAR = 'list_table_country_id';

  // Filter
  const FILTER_CONTINENT = 'filter_continent';
  const FILTER_ZONE      = 'filter_zone';

  // Do not used this constant for select. Use ->tableName property instead
  const TABLE_NAME = 'wpx_countries';

  // Used this file to create and insert default values into the table
  const SQL_FILENAME = 'wpx_countries.sql';

  // Default order
  const DEFAULT_ORDER = 'ASC';

  // Default order by
  const DEFAULT_ORDER_BY = 'country';

  /**
   * Create an instance of WPXCountries class
   *
   * @return WPXCountries
   */
  public function __construct()
  {
    // Build the database sql filename
    $database_filename = sprintf( '%s%s', WPXTREME_PATH_DATABASE, self::SQL_FILENAME );

    parent::__construct( self::TABLE_NAME, $database_filename );
  }

  /**
   * Return a singleton instance of WPXCountries class
   *
   * @note  This method is an alias of init()
   *
   * @return WPXCountries
   */
  public static function getInstance()
  {
    return self::init();
  }

  /**
   * Return a singleton instance of WPXCountries class
   *
   * @return WPXCountries
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
   * Create the table and insert data if empty.
   *
   */
  public function update_table()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Delta table
    $this->table->update_table();

    // Check for empty rows
    $count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $this->table->table_name );

    // If count is empty load and insert base data
    if ( empty( $count ) ) {

      // Load data
      $content = file_get_contents( WPXTREME_PATH_DATABASE . 'wpx_countries_data.sql' );

      // Stability
      if ( empty( $content ) ) {
        return false;
      }

      // Replace table name
      $sql = str_replace( '%s', $this->table->table_name, $content );

      // Remove comments
      $pattern = '/-{2,}.*/';

      $sql_sanitize = trim( preg_replace( $pattern, '', $sql ) );

      $result = $wpdb->query( 'LOCK TABLES ' . $this->table->table_name . ' WRITE;' );
      $result = $wpdb->query( $sql_sanitize );
      $result = $wpdb->query( 'UNLOCK TABLES;' );

      return $result;

    }

    return true;
  }

  /**
   * Return columns
   *
   * @return array
   */
  public function get_columns()
  {
    $columns = array(
      'cb'                   => '<input type="checkbox" />',
      self::COLUMN_COUNTRY   => __( 'Country', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_CONTINENT => __( 'Continent', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_ZONE      => __( 'Zone', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_CURRENCY  => __( 'Currency', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_SYMBOL    => __( 'Symbol', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_ISOCODE   => __( 'ISO Code', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_CODE      => __( 'Code', WPXTREME_TEXTDOMAIN ),
      self::COLUMN_TAX       => __( 'Tax', WPXTREME_TEXTDOMAIN ) . ' %',
      self::COLUMN_STATUS    => __( 'Status', WPXTREME_TEXTDOMAIN ),
    );

    return apply_filters( 'wpxss_shipping_country_columns', $columns );
  }

  /**
   * Return the sortable columns
   *
   * @return array
   */
  public function get_sortable_columns()
  {
    $sortable_columns = array(
      self::COLUMN_COUNTRY   => array( self::COLUMN_COUNTRY, true ),
      self::COLUMN_CONTINENT => array( self::COLUMN_CONTINENT, false ),
      self::COLUMN_ZONE      => array( self::COLUMN_ZONE, false ),
    );

    // TODO add filter
    return $sortable_columns;
  }

  /**
   * Return a key value pairs array with status key => count.
   *
   * @return array
   */
  public function get_count_statuses()
  {
    $counts = $this->count( self::COLUMN_STATUS );

    return $counts;
  }

  /**
   * Return the right inline action for the current status
   *
   * @param array $item   The item
   * @param array $status Describe one or more status of single item
   *
   * @return array
   */
  public function get_actions_with_status( $item, $status )
  {

    $actions = array(
      self::ACTION_EDIT    => __( 'Edit', WPXTREME_TEXTDOMAIN ),
      self::ACTION_RESTORE => __( 'Restore', WPXTREME_TEXTDOMAIN ),
      self::ACTION_DELETE  => __( 'Delete', WPXTREME_TEXTDOMAIN ),
      self::ACTION_TRASH   => __( 'Trash', WPXTREME_TEXTDOMAIN ),
    );

    switch ( $status ) {

      case WPDKDBTableRowStatuses::TRASH:
        unset( $actions[ self::ACTION_TRASH ] );
        break;

      default:
        unset( $actions[ self::ACTION_DELETE ] );
        unset( $actions[ self::ACTION_RESTORE ] );
        break;
    }

    return $actions;
  }

  /**
   * Return the right combo menu bulk actions for the current status
   *
   * @param string $status Usually this is the status in the URI, when user select 'All', 'Publish', etc...
   *
   * @return array
   */
  public function get_bulk_actions_with_status( $status )
  {
    $actions = array(
      self::ACTION_RESTORE => __( 'Restore', WPXTREME_TEXTDOMAIN ),
      self::ACTION_DELETE  => __( 'Delete', WPXTREME_TEXTDOMAIN ),
      self::ACTION_TRASH   => __( 'Move to trash', WPXTREME_TEXTDOMAIN ),
    );

    switch ( $status ) {
      case WPDKDBTableRowStatuses::TRASH:
        unset( $actions[ self::ACTION_TRASH ] );
        break;

      default:
        unset( $actions[ self::ACTION_RESTORE ] );
        unset( $actions[ self::ACTION_DELETE ] );
        break;
    }

    return $actions;
  }

  /**
   * Process actions
   */
  public function process_bulk_action()
  {
    // Get the shortocode id if exists
    $id = isset( $_REQUEST[ self::LIST_TABLE_SINGULAR ] ) ? $_REQUEST[ self::LIST_TABLE_SINGULAR ] : '';

    // Process the action
    switch ( $this->current_action() ) {

      // Insert
      case self::ACTION_INSERT:
        $this->action_result = $this->insert( $_REQUEST );
        break;

      // Update
      case self::ACTION_UPDATE:
        $this->action_result = $this->update( $_REQUEST );
        break;

      // Trash
      case self::ACTION_TRASH:
        $this->action_result = $this->status( $id, WPDKDBTableRowStatuses::TRASH );
        break;

      // Restore
      case self::ACTION_RESTORE:
        $this->action_result = $this->status( $id );
        break;

      // Delete
      case self::ACTION_DELETE:
        $this->action_result = $this->delete( $id );
        break;

    }
  }

  // -------------------------------------------------------------------------------------------------------------------
  // CRUD
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Create a new record and return the action_result property
   *
   * @param array $post_data Key value pairs array
   *
   * @return int|bool
   */
  public function insert( $post_data = null )
  {
    if ( isset( $post_data[ self::COLUMN_COUNTRY ] ) && ! empty( $post_data[ self::COLUMN_COUNTRY ] ) ) {

      // Sanitize
      $values = array(
        self::COLUMN_COUNTRY     => esc_html( $post_data[ self::COLUMN_COUNTRY ] ),
        self::COLUMN_ISOCODE     => esc_html( $post_data[ self::COLUMN_ISOCODE ] ),
        self::COLUMN_CURRENCY    => esc_html( $post_data[ self::COLUMN_CURRENCY ] ),
        self::COLUMN_SYMBOL      => esc_html( $post_data[ self::COLUMN_SYMBOL ] ),
        self::COLUMN_SYMBOL_HTML => esc_html( $post_data[ self::COLUMN_SYMBOL_HTML ] ),
        self::COLUMN_CODE        => esc_html( $post_data[ self::COLUMN_CODE ] ),
        self::COLUMN_ZONE        => esc_html( $post_data[ self::COLUMN_ZONE ] ),
        self::COLUMN_TAX         => ( $post_data[ self::COLUMN_TAX ] ),
        self::COLUMN_CONTINENT   => esc_html( $post_data[ self::COLUMN_CONTINENT ] ),
      );

      $this->action_result = parent::insert( 'wpx_countries', $values );

    }

    return $this->action_result;
  }

  /**
   * Return rows.
   *
   *    ### Single record
   *
   * @param array $args Optional.
   *
   * @return array
   */
  public function select( $args = array() )
  {
    global $wpdb;

    // Defaults values
    $defaults = array(
      'orderby' => self::DEFAULT_ORDER_BY,
      'order'   => self::DEFAULT_ORDER,
    );

    $args = wp_parse_args( $args, $defaults );

    // Prepare the where condictions
    $where = array( 'WHERE 1' );

    // Where for id
    $where[] = $this->table->where( $args, self::COLUMN_ID );

    // Where for country
    $where[] = $this->table->where( $args, self::COLUMN_COUNTRY );

    // Where for status
    $where[] = $this->table->where( $args, self::COLUMN_STATUS, '', WPDKDBTableRowStatuses::ALL );

    // Where for continent
    $where[] = $this->table->where( $args, self::COLUMN_CONTINENT );

    // Where for zone
    $where[] = $this->table->where( $args, self::COLUMN_ZONE );

    // Build the where
    $where_str = implode( ' AND ', array_filter( $where ) );

    $sql = <<< SQL
SELECT countries.*,
       countries.id AS list_table_country_id

FROM {$this->table->table_name} AS countries

{$where_str}

ORDER BY {$args['orderby']} {$args['order']}
SQL;

    //WPXtreme::log( $sql );

    $data = $wpdb->get_results( $sql, ARRAY_A );

    return $data;
  }

  /**
   * Update a record
   *
   * @param array $post_data Optional.
   *
   * @return array|bool
   */
  public function update( $post_data = array() )
  {
    if ( empty( $post_data ) ) {
      return false;
    }

    // Sanitize
    $values = array(
      self::COLUMN_COUNTRY     => esc_html( $post_data[ self::COLUMN_COUNTRY ] ),
      self::COLUMN_ISOCODE     => esc_html( $post_data[ self::COLUMN_ISOCODE ] ),
      self::COLUMN_CURRENCY    => esc_html( $post_data[ self::COLUMN_CURRENCY ] ),
      self::COLUMN_SYMBOL      => esc_html( $post_data[ self::COLUMN_SYMBOL ] ),
      self::COLUMN_SYMBOL_HTML => esc_html( $post_data[ self::COLUMN_SYMBOL_HTML ] ),
      self::COLUMN_CODE        => esc_html( $post_data[ self::COLUMN_CODE ] ),
      self::COLUMN_ZONE        => esc_html( $post_data[ self::COLUMN_ZONE ] ),
      self::COLUMN_TAX         => ( $post_data[ self::COLUMN_TAX ] ),
      self::COLUMN_CONTINENT   => esc_html( $post_data[ self::COLUMN_CONTINENT ] ),
    );

    // Where for update
    $where = array(
      self::COLUMN_ID => $post_data[ self::LIST_TABLE_SINGULAR ]
    );

    return parent::update( 'wpx_countries', $values, $where );
  }

  // -------------------------------------------------------------------------------------------------------------------
  // UTILITIES
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return the array fields for form view.
   *
   * @param array $args    {
   *                       Arguments for formatting and columns.
   *
   * @type string $format  Default '%s - (%s) VAT: %s'
   * @type array  $columns Default array( self::COLUMN_COUNTRY, self::COLUMN_CURRENCY, self::COLUMN_TAX )
   *
   * }
   *
   * @return array
   */
  public function selectCountries( $args = array() )
  {
    $countries = $this->select();
    $select    = array();

    // Defaults
    $defaults = array(
      'format'  => '%s - (%s) ' . __( 'VAT', WPXTREME_TEXTDOMAIN ) . ': %s',
      'columns' => array( self::COLUMN_COUNTRY, self::COLUMN_CURRENCY, self::COLUMN_TAX ),
    );

    // Merge
    $args = WPDKArray::fit( $args, $defaults );

    if( !empty( $countries ) ) {
      $select[ ] = __( 'Select a country', WPXTREME_TEXTDOMAIN );

      /**
       * @var WPXCountry $country
       */
      foreach( $countries as $country ) {

        // Get values for columns
        $values = array();
        foreach( $args[ 'columns' ] as $column ) {
          $values[ ] = $country[ $column ];
        }

        $select[ $country[ self::COLUMN_ID ] ] = vsprintf( $args[ 'format' ], $values );
      }
    }

    return $select;
  }


}