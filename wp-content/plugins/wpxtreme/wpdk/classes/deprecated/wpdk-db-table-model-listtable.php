<?php

/**
 * Use this class when your database model is shows in a list table view controller
 *
 * @class           WPDKDBTableModelListTable
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-03-02
 * @version         1.0.0
 * @deprecated      since 1.5.2 - use WPDKDBListTableModel instead
 *
 */
class WPDKDBTableModelListTable extends WPDKDBTableModel {

  // Common Actions
  const ACTION_NEW     = 'new';
  const ACTION_INSERT  = 'insert';
  const ACTION_UPDATE  = 'update';
  const ACTION_EDIT    = 'action_edit';
  const ACTION_DELETE  = 'action_delete';
  const ACTION_DRAFT   = 'action_draft';
  const ACTION_TRASH   = 'action_trash';
  const ACTION_RESTORE = 'action_restore';

  /**
   * Used for check the action and bulk action results
   *
   * @brief Action result
   *
   * @var bool $action_result
   */
  public $action_result = false;

  /**
   * Create an instance of WPDKDBTableModelListTable class
   *
   * @brief Construct
   *
   * @param string $table_name The name of the database table without WordPress prefix
   * @param string $sql_file   Optional. The filename of SQL file with the database table structure and init data.
   *
   * @return WPDKDBTableModelListTable
   */
  public function __construct( $table_name, $sql_file = '' )
  {
    // Init the database table model
    parent::__construct( $table_name, $sql_file );

    // Add action to get the post data
    $action = get_class( $this ) . '-listtable-viewcontroller';

    // This action is documented in classes/ui/wpdk-listtable-viewcontroller.php
    add_action( $action, array( $this, 'process_bulk_action' ) );
  }


  /**
   * Return a key value pairs array with the list of columns
   *
   * @brief Return the list of columns
   *
   * @return array
   */
  public function get_columns()
  {
    return array();
  }

  /**
   * Return the sortable columns
   *
   * @brief Sortable columns
   *
   * @return array
   */
  public function get_sortable_columns()
  {
    return array();
  }

  /**
   * Return a key value pairs array with statuses supported.
   * You can override this method to return your own statuses.
   *
   * @brief Statuses
   *
   * @return array
   */
  public function get_statuses()
  {
    // Default return the common statuses
    return WPDKDBTableRowStatuses::statuses();
  }

  /**
   * Return a key value pairs array with statuses icons glyph
   *
   * @brief Icons
   *
   * @return array
   */
  public function get_icon_statuses()
  {
    // Default return the common statuses
    return WPDKDBTableRowStatuses::icon_statuses();
  }

  /**
   * Return the count of specific status
   *
   * @brief Count status
   *
   * @param string $status
   *
   * @return int
   */
  public function get_status( $status )
  {
    return;
  }

  /**
   * Return tha array with the action for the current status
   *
   * @brief Action with status
   *
   * @param mixed  $item   The item
   * @param string $status Current status
   *
   * @return array
   */
  public function get_actions_with_status( $item, $status )
  {
    return array();
  }

  /**
   * Return the array with the buk action for the combo menu for a status of view
   *
   * @brief Bulk actions
   *
   * @param string $status Current status
   *
   * @return array
   */
  public function get_bulk_actions_with_status( $status )
  {
    return array();
  }

  /**
   * Get the current action selected from the bulk actions dropdown.
   *
   * @brief Current action
   *
   * @param string $nonce Optional. Force nonce verify
   *
   * @return string|bool The action name or False if no action was selected
   */
  public function current_action( $nonce = '' )
  {
    // Ajax
    if ( wpdk_is_ajax() ) {
      return false;
    }

    // Action
    $action = false;

    if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
      $action = $_REQUEST['action'];
    }
    elseif ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
      $action = $_REQUEST['action2'];
    }

    // Nonce
    if ( !empty( $nonce ) && !empty( $action ) && isset( $_REQUEST['_wpnonce'] ) ) {
      if ( wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $nonce ) ) {
        return $action;
      }
    }

    return $action;
  }

  /**
   * Set the action result.
   *
   * @since 1.6.0
   *
   * @param bool|WP_Error $result A result from an "action".
   */
  public function action_result( $result )
  {
    if ( is_wp_error( $result ) ) {
      $error               = array(
        'message' => $result->get_error_message(),
        'data'    => $result->get_error_data()
      );
      $this->action_result = urlencode( json_encode( $error ) );

    }
    else {
      $this->action_result = 1;
    }
  }

  /**
   * Process actions
   *
   * @brief Process actions
   * @since 1.4.21
   *
   */
  public function process_bulk_action()
  {
    // Get current action.
    $action = $this->current_action();

    // Avoid redirect for these actions
    $actions = array( WPDKDBListTableModel::ACTION_NEW, WPDKDBListTableModel::ACTION_EDIT );

    // TODO think to filter

    if( $action && in_array( $action, $actions ) ) {
      return;
    }

    /**
     * Filter the query args for redirect after an actions.
     *
     * @since 1.5.17
     *
     * @param array $args Optional. Default query args to remove. Default `array()`
     */
    $args = apply_filters( 'wpdk_list_table_remove_query_args_redirect', array() );

    // Set the action result
    $args['_action_result'] = $this->action_result;

    $reditect = add_query_arg( $args, $_SERVER['REQUEST_URI'] );

    if ( $action ) {
      wp_safe_redirect( $reditect );
      exit();
    }

  }

  // -------------------------------------------------------------------------------------------------------------------
  // CRUD
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Insert a record by values. Return FALSE if error or id of record if successfully.
   *
   * @brief Insert
   *
   * @internal string $prefix A prefix used for filter/action hook, eg: carrier, stat, ...
   * @internal array  $values Array keys values
   * @internal array  $format Optional. Array keys values for format null values
   *
   * @return int|WP_Error
   */
  //public function insert( $prefix, $values, $format = array() )
  public function insert()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    /*
     * since 1.5.1
     * try to avoid 'PHP Strict Standards:  Declaration of ::insert() should be compatible with WPDKDBTableModelListTable::insert'
     *
     * Remeber that if a params is missing it is NULL
     */
    $args = func_get_args();
    list( $prefix, $values ) = $args;
    $format = isset( $args[2] ) ? $args[2] : array();

    /**
     * Filter the values array for insert.
     *
     * @param array $values Array values for insert.
     */
    $values = apply_filters( $prefix . '_insert_values', $values );

    // Insert
    $result = $wpdb->insert( $this->table_name, $values, $format );

    if ( false === $result ) {
      return new WP_Error( $prefix . '-insert', __( 'Error while insert' ), array( $this->table_name, $values, $format ) );
    }

    // Get the id
    $id = $wpdb->insert_id;

    /**
     * Fires when a record is inserted
     *
     * @param bool  $result Result of insert
     * @param array $values Array with values of insert
     */
    do_action( $prefix . '_inserted', $result, $values );

    // Return the id
    return $id;
  }

  /**
   * Return the items array. This is an array of key value pairs array
   *
   * @brief Items
   *
   * @return array
   */
  public function select()
  {
    die( __METHOD__ . ' must be override in your subclass' );
  }

  /**
   * Update a record by values. Return FALSE if error or the $where condiction if successfully.
   * You can use the $where condiction returned to get again the record ID.
   *
   * @internal string $prefix A prefix used for filter/action hook, eg: carrier, stat, ...
   * @internal array  $values Array keys values
   * @internal array  $where  Array keys values for where update
   * @internal array  $format Optional. Array keys values for format null values
   *
   * @return int|WP_Error
   */
  //public function update( $prefix, $values, $where, $format = array() )
  public function update()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    /*
     * since 1.5.1
     * try to avoid 'PHP Strict Standards:  Declaration of ::update() should be compatible with WPDKDBTableModelListTable::update'
     *
     * Remeber that if a params is missing it is NULL
     */
    $args = func_get_args();
    list( $prefix, $values, $where ) = $args;
    $format = isset( $args[3] ) ? $args[3] : array();

    /**
     * Filter the values array for update
     *
     * @param array $values Array values for update.
     */
    $values = apply_filters( $prefix . '_update_values', $values );

    // Update
    $result = $wpdb->update( $this->table_name, $values, $where, $format );

    if ( false === $result ) {
      return new WP_Error( $prefix . '-update', __( 'Error while update' ), array( $values, $where, $format ) );
    }

    /**
     * Fires when a record is updated
     *
     * @param bool|int $result Returns the number of rows updated, or false if there is an error.
     * @param array    $values Array with values of update.
     * @param array    $where  Array with values of where condiction.
     */
    do_action( $prefix . '_updated', $result, $values, $where );

    // Get the id
    return $where;
  }

  /**
   * Return the integer count of all rows when $distinct param is emmpty or an array of distinct count for $distinct column.
   *
   * @brief    Count
   *
   * @internal string       $distinct Optional. Name of field to distinct group by
   * @internal array|string $status   Optional. Key value paier for where condiction on field: key = fields, vallue = value
   *
   * @return int|array
   */
  //public function count( $distinct = '', $status = '' )
  public function count()
  {
    global $wpdb;

    /*
     * since 1.5.1
     * try to avoid 'PHP Strict Standards:  Declaration of [...] should be compatible with [...]
     *
     * Remeber that if a params is missing it is NULL
     */
    $args     = func_get_args();
    $distinct = isset( $args[0] ) ? $args[0] : '';
    $status   = isset( $args[1] ) ? $args[1] : '';

    $where = '';
    if ( !empty( $status ) && is_array( $status ) ) {
      if ( is_numeric( $status[ key( $status ) ] ) ) {
        $where = sprintf( 'WHERE %s = %s', key( $status ), $status[ key( $status ) ] );
      }
      else {
        $where = sprintf( "WHERE %s = '%s'", key( $status ), $status[ key( $status ) ] );
      }
    }

    if ( empty( $distinct ) ) {
      $sql = <<< SQL
SELECT COUNT(*) AS count
  FROM {$this->table_name}
  {$where}
SQL;

      return absint( $wpdb->get_var( $sql ) );
    }
    else {
      $sql = <<< SQL
SELECT DISTINCT( {$distinct} ),
  COUNT(*) AS count
  FROM {$this->table_name}

  {$where}

  GROUP BY {$distinct}
SQL;

      $results = $wpdb->get_results( $sql, ARRAY_A );

      // Prepare result array
      $result = array();

      // Prepare all
      $result[ WPDKDBTableRowStatuses::ALL ] = 0;

      // Loop into results
      foreach ( $results as $res ) {
        $result[ $res[ $distinct ] ] = $res['count'];
        $result[ WPDKDBTableRowStatuses::ALL ] += $res['count'];
      }

      return $result;
    }
  }

  // -------------------------------------------------------------------------------------------------------------------
  // UTILITIES
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Set one or more record wit a status
   *
   * @brief Set a status
   *
   * @param int    $id     Record ID
   * @param string $status Optional. The status, default WPDKDBTableRowStatuses::PUBLISH
   *
   * @return mixed
   */
  public function status( $id, $status = WPDKDBTableRowStatuses::PUBLISH )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;


    // Stability
    if ( !empty( $id ) && !empty( $status ) ) {

      // Get the ID
      $id = implode( ',', (array)$id );

      $sql = <<< SQL
UPDATE {$this->table_name}
SET status = '{$status}'
WHERE id IN( {$id} )
SQL;

      $num_rows = $wpdb->query( $sql );

      if( false === $num_rows ) {
        return new WP_Error( 'deprecated-status', __( 'Error while update status' ), $sql );
      }

      return ( $num_rows > 0 );
    }
    return new WP_Error( 'deprecated-status', __( 'Wrong params in change status' ), array( $id, $status ) );
  }

}