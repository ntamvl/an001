<?php

/**
 * The wpXtreme Dashboard
 *
 * ## Overview
 * Manage the wpXtreme Dashboard in WordPress dashboard screen
 *
 * @class           WPXtremeDashboard
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-22
 * @version         0.5.0
 * @since           1.0.0.b3
 *
 */
class WPXtremeDashboard {

  // Name for store the user options
  const OPTION_NAME = 'wpxtreme-dashoard';

  /**
   * Dashboard widget options.
   *
   * @var array $options
   */
  public $options = array();

  /**
   * Return a singleton instance of WPXtremeDashboard class
   *
   * @return WPXtremeDashboard
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
   * Create an instance of WPXtremeDashboard class
   *
   * @return WPXtremeDashboard
   */
  private function __construct()
  {
    // Prepare image
    $image_url = sprintf( '%s%s', WPXTREME_URL_IMAGES, 'logo-16x16.png' );
    $image     = sprintf( '<img style="vertical-align: top" src="%s" alt="%s" /> ', $image_url, 'wpXtreme' );

    // Add dashboard widget
    wp_add_dashboard_widget( 'wpxtreme', $image . __( 'wpXtreme', WPXTREME_TEXTDOMAIN ), array(
        $this,
        'display'
      ), array( $this, 'options' ) );

    // Load options
    $this->options = get_user_meta( get_current_user_id(), self::OPTION_NAME, true );

    // Stability
    if ( empty( $this->options ) ) {
      $this->options = array();
    }

    // Fires when styles are printed for a specific admin page based on $hook_suffix.
    add_action( 'admin_print_styles-index.php', array( $this, 'admin_print_styles_index' ) );
  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @since WP 2.6.0
   */
  public function admin_print_styles_index()
  {
    //wp_enqueue_script( 'wpxm-dashboard', WPXTREME_URL_JAVASCRIPT . 'wpxm-dashboard.js', array( 'jquery' ), WPXTREME_VERSION, true );
    wp_enqueue_style( 'wpxm-dashboard', WPXTREME_URL_CSS . 'wpxm-dashboard.css', array(), WPXTREME_VERSION );

  }

  /**
   * Display the content of wpXtreme dashboard view
   */
  public function display()
  {
    $view = new WPXtremeDashboardView( $this );
    $view->display();
  }

  /**
   * Display the dashboard widget options
   */
  public function options()
  {
    // Check for updated
    if ( wpdk_is_request_post() ) {

      // Number of feeds
      $value                                   = esc_attr( $_POST['dashboard_number_feeds'] );
      $value                                   = max( min( $value, 21 ), 3 );
      $this->options['dashboard_number_feeds'] = $value;

      // Enable log
      $this->options['dashboard_enable_log'] = isset( $_POST['dashboard_enable_log'] );

      // Numbers of rows
      $value                                    = esc_attr( $_POST['dashboard_number_of_log'] );
      $value                                    = max( min( $value, 21 ), 3 );
      $this->options['dashboard_number_of_log'] = $value;

      // Update
      update_user_meta( get_current_user_id(), self::OPTION_NAME, $this->options );

      return;
    }

    // Display options
    $fields = array(

      __( 'Breaking News', WPXTREME_TEXTDOMAIN ) => array(
        array(
          array(
            'type'    => WPDKUIControlType::SELECT,
            'name'    => 'dashboard_number_feeds',
            'label'   => __( 'Number of feed', WPXTREME_TEXTDOMAIN ),
            'value'   => $this->options['dashboard_number_feeds'],
            'options' => array(
              '3'  => '3',
              '5'  => '5',
              '10' => '10',
              '15' => '15',
              '20' => '20'
            )
          )
        ),
      ),
      __( 'Logs', WPXTREME_TEXTDOMAIN )          => array(
        array(
          array(
            'type'  => WPDKUIControlType::SWITCH_BUTTON,
            'name'  => 'dashboard_enable_log',
            'label' => __( 'Enable Log', WPXTREME_TEXTDOMAIN ),
            'value' => $this->options['dashboard_enable_log'],
          )
        ),
        array(
          array(
            'type'    => WPDKUIControlType::SELECT,
            'name'    => 'dashboard_number_of_log',
            'label'   => __( 'Number of log', WPXTREME_TEXTDOMAIN ),
            'value'   => $this->options['dashboard_number_of_log'],
            'options' => array(
              '3'  => '3',
              '5'  => '5',
              '10' => '10',
              '15' => '15',
              '20' => '20'
            )
          )
        ),
      ),

    );


    $layout = new WPDKUIControlsLayout( $fields );
    $layout->display();
  }
}

/**
 * The wpXtreme Dashboard View
 *
 * ## Overview
 * Display the content of the wpXtreme Dashboard
 *
 * @class           WPXtremeDashboardView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-01-18
 * @version         0.1.0
 * @since           1.0.0.b3
 *
 */
class WPXtremeDashboardView extends WPDKView {

  /**
   * Dashboard model
   *
   * @var WPXtremeDashboard $model
   */
  private $model;

  /**
   * Create an instance of WPXtremeDashboardView class
   *
   * @param WPXtremeDashboard $model
   *
   * @return WPXtremeDashboardView
   */
  public function __construct( $model )
  {
    parent::__construct( 'wpxtreme-dashboard' );
    $this->model = $model;
  }

  /**
   * Drawing
   */
  public function draw()
  {
    // Latest feeds
    $this->displayLatestFeeds();

    // Check if display
    if ( wpdk_is_bool( $this->model->options['dashboard_enable_log'] ) ) {

      // Display logs
      $this->displayLogs();
    }
  }

  /**
   * Display the last 10 logs
   */
  private function displayLogs()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Logs table name
    $table_name = WPXLogs::init()->table_name;

    // Limit
    $limit = $this->model->options['dashboard_number_of_log'];

    $sql = <<<SQL
SELECT *

FROM ( {$table_name} AS logs )

ORDER BY date DESC
LIMIT 0,{$limit}
SQL;

    $results = $wpdb->get_results( $sql, ARRAY_A );

    if ( empty( $results ) ) {
      return;
    }

    ?>
    <h4><?php _e( 'Important Log' ) ?></h4>
    <table id="wpxm-logs" width="100%" border="0" cellspacing="0" cellpadding="0">
      <thead>
      <tr>
        <th><?php _e( 'Date' ) ?></th>
        <th><?php _e( 'Owner' ) ?></th>
        <th><?php _e( 'Log' ) ?></th>
      </tr>
      </thead>

      <tbody>
        <?php foreach ( $results as $log ) : ?>
          <tr class="<?php echo $log[ WPXLogs::COLUMN_SEVERITY ] ?>">
            <td><?php echo WPDKDateTime::format( $log[ WPXLogs::COLUMN_DATE ], WPDKDateTime::MYSQL_DATE_TIME ) ?></td>
            <td><?php echo $log[ WPXLogs::COLUMN_OWNER ] ?></td>
            <td><?php echo $log[ WPXLogs::COLUMN_LOG ] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  <?php

  }

  /**
   * Display the latest 5 feed from main web site
   */
  private function displayLatestFeeds()
  {
    // Include SimplePie
    if ( ! function_exists( 'fetch_feed' ) ) {
      require_once( ABSPATH . WPINC . '/class-simplepie.php' );
    }

    // wpXtreme feed
    $feed = 'https://wpxtre.me/category/blog/feed/';

    // Get RSS
    $rss  = fetch_feed( $feed );

    // Stability
    if ( ! is_wp_error( $rss ) ) {
      $maxitems  = $rss->get_item_quantity( $this->model->options['dashboard_number_feeds'] );
      $rss_items = $rss->get_items( 0, $maxitems );
      if ( $rss_items ) {

        printf( '<h4>%s</h4><ul>', __( 'Breaking News from blog', WPXTREME_TEXTDOMAIN ) );

        foreach ( $rss_items as $item ) {
          printf( '<li>%s <a href="%s">%s</a></li>', $item->get_date( 'Y, j F' ), $item->get_permalink(), $item->get_title() );
        }
        echo '</ul>';
      }
    }
    else {
      /**
       * @var WP_Error $error
       */
      $error = $rss;
      echo $error->get_error_message();
    }
  }
}