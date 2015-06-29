<?php

/**
 * Database clean & fix module
 *
 * @class              WPXCFDatabaseModule
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2013-08-23
 * @version            1.1.0
 *
 */

class WPXCFDatabaseModule extends WPXCleanFixModule {

  /**
   * Return a singleton instance of WPXCFDatabaseModule class
   *
   * @return WPXCFDatabaseModule
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
   * Create an instance of WPXCFDatabaseModule class
   *
   * @return WPXCFDatabaseModule
   */
  public function __construct()
  {
    parent::__construct( __( 'Database', WPXCLEANFIX_TEXTDOMAIN ) );

    // Register a custom preferences model
    add_action( 'wpxcf_preferences_init', array( $this, 'wpxcf_preferences_init' ) );

    // Register a custom preferences tab view
    add_filter( 'wpxcf_preferences_tabs', array( $this, 'wpxcf_preferences_tabs' ) );

    // Reset to default
    add_action( 'wpxcf_preferences_reset_to_defaults', array( $this, 'wpxcf_preferences_reset_to_defaults' ) );

    // Loading custom own script & styles
    add_action( 'wpxcf_admin_print_styles', array( $this, 'wpxcf_admin_print_styles' ) );
    add_action( 'wpxcf_admin_head', array( $this, 'wpxcf_admin_head' ) );

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
      'WPXCFDatabaseModuleOptimizationSlot'
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
    if ( !isset( $preferences->database ) ) {
      // Could be ...
      // $preferences = $this->wpxcf_preferences_reset_to_defaults( $preferences );
      $preferences->database = new WPXCleanFixPreferencesDatabase();
      $preferences->update();
    }
    return $preferences;
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
//    $view   = new WPXCleanFixPreferencesDatabaseView();
//    $tabs[] = new WPDKjQueryTab( $view->id, __( 'Database', WPXCLEANFIX_TEXTDOMAIN ), $view->html() );

    $tabs['WPXCleanFixPreferencesDatabaseView'] = __( 'Database', WPXCLEANFIX_TEXTDOMAIN );

    return $tabs;
  }

  /**
   * Register a custom preferences branch.
   *
   * @param WPXCleanFixPreferences $preferences Default preferences
   */
  public function wpxcf_preferences_reset_to_defaults( $preferences )
  {
    $preferences->database = new WPXCleanFixPreferencesDatabase();
    $preferences->update();
    // return $preferences;
  }

  /**
   * Add custom script and styles in admin
   */
  public function wpxcf_admin_print_styles()
  {
    wp_enqueue_style( 'wpxcf-module-database', WPXCLEANFIX_URL_EMBEDDED_MODULES . 'database/wpxcf-database-module.css', array(), WPXCLEANFIX_VERSION );
  }
  /**
   * Add custom script and styles in admin
   */
  public function wpxcf_admin_head()
  {
    wp_enqueue_script( 'wpxcf-module-database', WPXCLEANFIX_URL_EMBEDDED_MODULES . 'database/wpxcf-database-module.js', array(), WPXCLEANFIX_VERSION, true );
  }

}

/**
 * Single slot/row for database module
 *
 * @class           WPXCFDatabaseModuleOptimizationSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-01
 * @version         1.0.0
 *
 */
class WPXCFDatabaseModuleOptimizationSlot extends WPXCleanFixSlot  {

  /**
   * List of all table with some useful information per row.
   * array(
   *   'engine'         => '',
   *   'gain'           => '',
   *   'optimize'       => '',
   *   'auto_increment' => '',
   * )
   *
   * @var array $_table
   * @access private
   */
  public $tables = array();

  /**
   * Total gain.
   *
   * @var int $totalGain
   * @access private
   */
  public $totalGain = 0;

  /**
   * List of tables to optimize.
   *
   * @var array $_tableToOptimize
   * @access private
   */
  private $_tableToOptimize = array();

  /**
   * Return a singleton instance of WPXCFDatabaseModuleOptimizationSlot class
   *
   * @param WPXCleanFixModule $module Module instance.
   *
   * @return WPXCFDatabaseModuleOptimizationSlot
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
   * Return an instance of WPXCFDatabaseModuleOptimizationSlot class
   *
   * @param WPXCleanFixModule $module Module instance.
   *
   * @return WPXCFDatabaseModuleOptimizationSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Tables', WPXCLEANFIX_TEXTDOMAIN ), __( 'Database table status', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process.
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    // Check for database table optiomization
    $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $this->_tableToOptimize ) );

    // If any issues found
    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s table to optimize. This action makes you gain %s.', 'You have %s tables to optimize. This action makes you gain %s.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues, sprintf( '%6.2f Kb', $this->totalGain ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this, WPXCleanFixButtonFixControlType::FIX );
      $this->response->cleanFix->title = __( 'Fix: click here in order to optimize your table. This action is safe.', WPXCLEANFIX_TEXTDOMAIN );
    }
    $this->response->detail = new WPXCFDatabaseModuleOptimizeSlotDetailView( $this );

    return $this->response;

  }

  /**
   * Clean or Fix process.
   *
   * @return WPXCleanFixModuleResponse
   */
  public function cleanFix()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Check before fix
    $this->_check();

    /**
     * Get preferences
     *
     * @var WPXCleanFixPreferencesDatabase $database_preference
     */
    $database_preference = WPXCleanFixPreferences::init()->database;

    // Get engine list (no innodb)
    $engine = $this->_engine();

    // Prepare separate list
    $table_to_optimize = array();
    $innodb_tables     = array();

    // Loop into the to optimize table list
    foreach ( $this->_tableToOptimize as $name => $info ) {

      // Separate InniDB
      if ( in_array( $info['engine'], $engine ) ) {
        $table_to_optimize[] = $name;
      }
      else {
        $innodb_tables[] = $name;
      }
    }

    // MyISAM and other...
    if ( ! empty( $table_to_optimize ) ) {
      $list_name = join( ', ', $table_to_optimize );
      $result    = $wpdb->query( 'OPTIMIZE TABLE ' . $list_name );
      if ( is_wp_error( $result ) ) {
        return $result;
      }
    }

    if ( wpdk_is_bool( $database_preference->resetAutoIncrement ) && ! empty( $table_to_optimize ) ) {
      foreach ( $table_to_optimize as $table_name ) {
        $result = $wpdb->query( 'ALTER TABLE ' . $table_name . ' AUTO_INCREMENT = 1' );
        if ( is_wp_error( $result ) ) {
          return $result;
        }
      }
    }

    // InnoDB
    if ( ! empty( $innodb_tables ) && ! wpdk_is_bool( $database_preference->ignoreInnoDB ) ) {
      foreach ( $innodb_tables as $inno_name ) {
        $result = $wpdb->query( 'ALTER TABLE ' . $inno_name . ' ENGINE=InnoDB' );
        if ( is_wp_error( $result ) ) {
          return $result;
        }
        if ( wpdk_is_bool( $database_preference->resetAutoIncrement ) ) {
          $result = $wpdb->query( 'ALTER TABLE ' . $inno_name . ' AUTO_INCREMENT = 1' );
          if ( is_wp_error( $result ) ) {
            return $result;
          }
        }
      }
    }

    // Update data
    return $this->check();
  }

  /**
   * Return the total gain for optimization database table and set the tables property with the list of table to
   * optimize. Return false if no table foound.
   *
   * @return int
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Clear properties
    $this->tables           = array();
    $this->_tableToOptimize = array();
    $this->totalGain        = 0;

    // List of all database tables
    $sql = sprintf( 'SHOW TABLE STATUS FROM %s', DB_NAME );

    // Select
    $result = $wpdb->get_results( $sql );

    /**
     * Get preferences branch
     *
     * @var WPXCleanFixPreferencesDatabase $database_preference
     */
    $database_preference = WPXCleanFixPreferences::init()->database;

    if ( ! empty( $result ) ) {

      // Loop into the table list
      foreach ( $result as $table ) {

        // Exclude innodb by preferences
        if ( wpdk_is_bool( $database_preference->ignoreInnoDB ) && 'InnoDB' == $table->Engine ) {
          continue;
        }

        // Calculate gain
        $gain = round( floatval( $table->Data_free ) / 1024, 2 );

        // If a gain exist increment the total
        if ( $gain > 0 ) {
          $this->totalGain += $gain;
        }

        // Add this table to complete database table list information
        $this->tables[ $table->Name ] = array(
          'engine'         => $table->Engine,
          'gain'           => sprintf( '%6.2f Kb', $gain ),
          'optimize'       => ( $gain > 0 ),
          'auto_increment' => $table->Auto_increment
        );

        // Insert this table and its information in the to optimize list
        if ( $gain > 0 ) {
          $this->_tableToOptimize[ $table->Name ] = $this->tables[ $table->Name ];
        }
      }
    }

    return $this->totalGain;
  }

  /**
   * Return an array with the supported database table type.
   *
   * @return array
   */
  private function _engine()
  {
    $engine = array(
      'MyISAM',
      'ISAM',
      'HEAP',
      'MEMORY',
      'ARCHIVE'
    );

    /**
     * Filter the list of recognize databse table engine.
     *
     * @param array $engine A list of database engine.
     */
    return apply_filters( 'wpxcf_module_database_engine', $engine );
  }
}


/**
 * The database slot has a custom view to display a table with list of database table.
 *
 * @class           WPXCFDatabaseModuleOptimizeSlotDetailView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-13
 * @version         1.0.1
 *
 */
class WPXCFDatabaseModuleOptimizeSlotDetailView extends WPDKView {

  /**
   * An instance of WPXCFDatabaseModuleOptimizationSlot class. In `$slot->tables`:
   *
   *     array(4) {
   *      ["counter"]=> array(2) {
   *        ["engine"]=> string(6) "InnoDB"
   *        ["gain"]=> string(11) "10240.00 Kb"
   *      }
   *      ["wp_options"]=> array(2) {
   *        ["engine"]=> string(6) "MyISAM"
   *        ["gain"]=> string(9) "  0.60 Kb"
   *      }
   *      ["wp_postmeta"]=> array(2) {
   *        ["engine"]=> string(6) "MyISAM"
   *        ["gain"]=> string(9) "  0.11 Kb"
   *      }
   *      ["wp_posts"]=> array(2) {
   *        ["engine"]=> string(6) "MyISAM"
   *        ["gain"]=> string(9) "357.67 Kb"
   *      }
   *    }
   *
   *
   * @var WPXCFDatabaseModuleOptimizationSlot $slot
   */
  public $slot;

  /**
   * Create an instance of WPXCFDatabaseModuleOptimizeSlotDetailView class
   *
   * @param WPXCFDatabaseModuleOptimizationSlot $slot
   *
   * @return WPXCFDatabaseModuleOptimizeSlotDetailView
   */
  public function __construct( $slot )
  {
    parent::__construct( $slot->id );
    $this->slot = $slot;
  }

  /**
   * Display
   */
  public function draw()
  {
    ?>
    <div class="wpxcf-table-more-info">

      <div class="wpxcf-table-more-display-scroll">
        <table class="wpxcf-table-more-info-header" border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="wpxcf-table-more-info-column-engine"><?php _e( 'Engine', WPXCLEANFIX_TEXTDOMAIN ) ?></th>
            <th class="wpxcf-table-more-info-column-name"><?php _e( 'Name', WPXCLEANFIX_TEXTDOMAIN ) ?></th>
            <th class="wpxcf-table-more-info-column-auto-increment"><?php _e( 'Auto Increment', WPXCLEANFIX_TEXTDOMAIN ) ?></th>
            <th class="wpxcf-table-more-info-column-gain"><?php _e( 'Gain', WPXCLEANFIX_TEXTDOMAIN ) ?></th>
          </tr>
        </thead>
        </table>
      </div>

      <div class="wpxcf-table-more-info-content">
        <table class="wpxcf-table-more-info-body" border="0" cellspacing="0" cellpadding="0">

          <tbody>
        <?php
        foreach ( $this->slot->tables as $table_name => $info ) : ?>
          <tr class="<?php echo $info['optimize'] ? 'optimize' : '' ?>">
            <td class="wpxcf-table-more-info-column-engine"><?php echo $info['engine'] ?></td>
            <td class="wpxcf-table-more-info-column-name"><?php echo $table_name ?></td>
            <td class="wpxcf-table-more-info-column-auto-increment"><?php echo $info['auto_increment'] ?></td>
            <td class="wpxcf-table-more-info-column-gain"><?php echo $info['gain'] ?></td>
          </tr>
        <?php endforeach; ?>
          </tbody>

        </table>
      </div>

    <div class="wpxcf-table-more-display-scroll">
      <table class="wpxcf-table-more-info-footer" border="0" cellspacing="0" cellpadding="0">
        <tfoot>
          <tr>
          <td><?php _e( 'Total Gain', WPXCLEANFIX_TEXTDOMAIN ) ?></td>
          <td><?php printf( '%6.2f Kb', $this->slot->totalGain ); ?></td>
          </tr>
        </tfoot>
      </table>
    </div>

    </div>
  <?php
  }

}