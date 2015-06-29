<?php

/**
 * WPX Store model class. Manage remote API, info and local storage for the WPX Store.
 * Check user expiry license.
 *
 * @class           WPXStore
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-09-03
 * @version         1.0.0
 * @since           1.0.5
 *
 */
final class WPXStore {

  // This is the site transient used to store the wpx plugin to update
  const UPDATE_PLUGINS = 'wpx_update_plugins';

  // This is the site transient used to store the wpx themes to update
  const UPDATE_THEMES = 'wpx_update_themes';

  // Expiration time for the transient update list
  const TRANSIENT_EXPIRATION = 900; // 60 * 15 - 15 minutes

  // Check license
  const META_USER_KEY_LICENSE = '_wpxtreme_license';

  /**
   * Instance of base class WPXAPI
   *
   * @var WPXtremeAPI $api
   */
  private $api;

  /**
   * A static instance of WPXStore class
   *
   * @var WPXStore $instance
   */
  private static $instance = null;

  /**
   * List of plugins to update.
   *
   * @since 1.4.0
   *
   * @var array $plugin_to_update
   */
  public $plugin_to_update = null;


  /**
   * Return a singleton instance of WPXStore class
   *
   * @return WPXStore
   */
  public static function init()
  {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * Create an instance of WPXStore class
   *
   * @return WPXStore
   */
  public function __construct()
  {
    // Instance of own API
    $this->api = WPXtremeAPI::init();

    // Reminder
    //$this->reminder();

    // Alternate checking repository for Plugins
    add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'pre_set_site_transient_update_plugins' ) );

    // Alternate checking repository for Themes
    //add_filter( 'pre_set_site_transient_update_themes', array( $this, 'pre_set_site_transient_update_themes' ) );

    // Allows a plugin to override the WordPress.org Plugin Install API entirely.
    add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );

    // Exclude theme from WordPress updated
    // TODO Experimental - see filter below
    //add_filter( 'http_request_args', array( $this, 'slf_prevent_wp_update' ), 5, 2 );
  }

  /**
   * This filter hook is called by `http_request_args` and could be used to avoid standard WordPress repo checking.
   *
   * @todo  Not used at this moment
   *
   * @param $r
   * @param $url
   *
   * @return mixed
   */
  public function slf_prevent_wp_update( $r, $url )
  {
    if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) ) {
      return $r;
    }
    $themes = unserialize( $r['body']['themes'] );
    unset( $themes['your-theme-slug'] );
    $r['body']['themes'] = serialize( $themes );

    return $r;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // USER LICENSE
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Check WPX Store ID and user license; open right dialog
   */
  public function reminder()
  {
    // If this value is empty the user never signin into the store or he is logout
    $user_email = WPXtremePreferences::init()->wpxstore->user_id;

    // logout?
    if( empty( $user_email ) ) {

      // Display a reminder
      //add_action( 'all_admin_notices', array( WPXStoreSigninAlert::init(), 'display' ) );

    }
    // if user email (WPX Store ID) is valid, check the license
    else {

      // TODO check user license - http request for get license type (manual | auto_renew)
      $auto_renew = false;

      // Open reminder only if auto-renew is false
      if( empty( $auto_renew ) ) {

        // TODO get expiry date for this user
        $expiry_time = time() + ( 2 * DAY_IN_SECONDS );

        // Delta
        $time_delta = $expiry_time - time();

        // if the license is expired or 1 WEEK to expiry
        if ( $time_delta < 0 || $time_delta < ( ( time() + WEEK_IN_SECONDS ) - time() ) ) {

          // Warning every day
          WPXReminder::init( $GLOBALS[ 'WPXtreme' ], 'wpx-store-license', 'WPXStoreLicenseDialog', WPXReminderInterval::DAY_IN_SECONDS );
        }
      }
    }
  }

  // -------------------------------------------------------------------------------------------------------------------
  // PLUGINS
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * This hook is used by WordPress to check plugins update.
   *
   * @param object $transient Standard WordPress transient with check results
   *
   *    object(stdClass)#536 (3) {
   *      ["last_checked"]=> int(1378219663)
   *      ["checked"]=> array(26) {
   *        ["akismet/akismet.php"]=> string(5) "2.5.9"
   *        ["wpx-bannerize/main.php"]=> string(5) "1.0.5"
   *        ["bannerize_0x00/main.php"]=> string(5) "1.0.0"
   *        ["wpx-cleanfix_00002e/main.php"]=> string(5) "1.1.0"
   *        ["codecolorer/codecolorer.php"]=> string(5) "0.9.9"
   *        ["wpx-developer-tools/main.php"]=> string(8) "1.0.0.b1"
   *        ["wpx-faq/main.php"]=> string(8) "1.0.0.b1"
   *        ["wpx-followgram_000031/main.php"]=> string(5) "1.1.2"
   *        ["google-sitemap-generator/sitemap.php"]=> string(5) "3.2.9"
   *        ["wpx-mail-manager/main.php"]=> string(5) "1.0.0"
   *        ["wpx-maintenance-pro/main.php"]=> string(5) "1.1.0"
   *        ["members/members.php"]=> string(5) "0.2.2"
   *        ["wpx-rest-api-server/main.php"]=> string(5) "0.6.0"
   *        ["wpxras-smartshop/main.php"]=> string(5) "1.0.0"
   *        ["wpxss-wordpress/main.php"]=> string(7) "0.1.0.b"
   *        ["wpxras-wpxstore/main.php"]=> string(5) "0.7.0"
   *        ["wpx-smartshop/main.php"]=> string(7) "0.1.0.b"
   *        ["wpxss-stripe/main.php"]=> string(7) "0.4.1.b"
   *        ["wpxss-wpxstore/main.php"]=> string(8) "0.9.13.b"
   *        ["wpx-users-manager/main.php"]=> string(5) "1.0.6"
   *        ["wpxum-stats/main.php"]=> string(5) "0.2.1"
   *        ["wordpress-importer/wordpress-importer.php"]=> string(5) "0.6.1"
   *        ["wp-fb-autoconnect/Main.php"]=> string(5) "3.1.0"
   *        ["wp-ses/wp-ses.php"]=> string(5) "0.2.2"
   *        ["wpxtreme/main.php"]=> string(5) "1.1.0"
   *        ["wpxtreme-server/main.php"]=> string(5) "2.0.0"
   *      }
   *      ["response"]=> array(2) {
   *        ["wp-fb-autoconnect/Main.php"]=> object(stdClass)#534 (5) {
   *          ["id"]=> string(5) "13613"
   *          ["slug"]=> string(17) "wp-fb-autoconnect"
   *          ["new_version"]=> string(5) "3.1.2"
   *          ["url"]=> string(47) "http://wordpress.org/plugins/wp-fb-autoconnect/"
   *          ["package"]=> string(65) "http://downloads.wordpress.org/plugin/wp-fb-autoconnect.3.1.2.zip"
   *        }
   *        ["wp-ses/wp-ses.php"]=> object(stdClass)#533 (5) {
   *          ["id"]=> string(5) "20253"
   *          ["slug"]=> string(6) "wp-ses"
   *          ["new_version"]=> string(5) "0.3.2"
   *          ["url"]=> string(36) "http://wordpress.org/plugins/wp-ses/"
   *          ["package"]=> string(48) "http://downloads.wordpress.org/plugin/wp-ses.zip"
   *        }
   *      }
   *    }
   *
   * @return object
   *
   */
  public function pre_set_site_transient_update_plugins( $transient )
  {

    // Get the plugins list
    $plugins = get_plugins();

    // Get the wpXtreme plugins slugs
    $wpx_plugin_slugs = array_keys( WPXStorePlugins::wpxtreme_plugins() );

    // Remove wpXtreme plugins from `checked`
    if ( isset( $transient->checked ) && is_array( $transient->checked ) ) {
      foreach ( $transient->checked as $plugin_slug => $version ) {

        // Is it a wpXtreme plugin ?
        if ( isset( $plugins[ $plugin_slug ] ) && in_array( $plugin_slug, $wpx_plugin_slugs ) ) {
          unset( $transient->checked[ $plugin_slug ] );
        }
      }
    }

    // Avoid wpXtreme plugin from response - avoid duplicate name occurences in WordPress repository
    if ( isset( $transient->response ) && is_array( $transient->response ) ) {

      foreach ( $transient->response as $plugin_slug => $value ) {

        // Is it a wpXtreme plugin ?
        if ( isset( $plugins[ $plugin_slug ] ) && in_array( $plugin_slug, $wpx_plugin_slugs ) ) {
          unset( $transient->response[ $plugin_slug ] );
        }
      }

      // Avoid multiple call to wpXtreme server
      if( is_null( $this->plugin_to_update ) ) {

        // Ask to the wpXtreme Store Server if there are plugins to update
        $this->plugin_to_update = $this->api->plugins_check_updates( WPXStorePlugins::wpxtreme_plugins() );
      }

      // Stability
      if( empty( $this->plugin_to_update ) ) {
        return $transient;
      }

      // TODO remember separation extensions and product - perephs deprecated
//      if ( empty( $plugin_to_update ) || ( !isset( $plugin_to_update['extensions'] ) && !isset( $plugin_to_update['plugins'] ) ) ) {
//        return $value;
//      }

      // WPXtreme::log( $transient->response, 'before merge $transient->response' );

      /*
       * eg:
       *
       *     array(2) {
       *      ["google-sitemap-generator/sitemap.php"]=> object(stdClass)#2572 (6) {
       *        ["id"]=> string(3) "132"
       *        ["slug"]=> string(24) "google-sitemap-generator"
       *        ["plugin"]=> string(36) "google-sitemap-generator/sitemap.php"
       *        ["new_version"]=> string(5) "4.0.5"
       *        ["url"]=> string(55) "https://wordpress.org/plugins/google-sitemap-generator/"
       *        ["package"]=> string(73) "https://downloads.wordpress.org/plugin/google-sitemap-generator.4.0.5.zip"
       *      }
       *      ["members/members.php"]=>
       *      object(stdClass)#2573 (6) {
       *        ["id"]=> string(5) "10325"
       *        ["slug"]=> string(7) "members"
       *        ["plugin"]=> string(19) "members/members.php"
       *        ["new_version"]=> string(5) "0.2.4"
       *        ["url"]=> string(38) "https://wordpress.org/plugins/members/"
       *        ["package"]=> string(56) "https://downloads.wordpress.org/plugin/members.0.2.4.zip"
       *      }
       *    }
       *
       */

      $transient->response = array_merge( $transient->response, (array)$this->plugin_to_update );

    }

    //WPXtreme::log( $transient->response, 'after merge $transient->response' );

    return $transient;
  }

  /**
   * Allows a plugin to override the WordPress.org Plugin Install API entirely.
   *
   * Please ensure that an object is returned.
   *
   * @param bool|object $false  The result object. Default false.
   * @param string      $action The type of information being requested from the Plugin Install API.
   * @param object      $args   Plugin API arguments.
   *
   * @return bool|object
   */
  public function plugins_api( $false, $action, $args )
  {
//    WPXtreme::log( $false, '$false ' . __METHOD__ );
//    WPXtreme::log( $action, '$action ' . __METHOD__ );
//    WPXtreme::log( $args, '$args ' . __METHOD__ );

    switch( $action ) {

      // Plugin information. This is the single plugin information. These info are used in update-core.php too.
      case 'plugin_information':

        // Check for slug
        if( isset( $args->slug ) && ! empty( $args->slug ) ) {

          // Get information - ask ever to WPX Store, if return null the plugins is not wpXtreme
          $false = $this->api->product_information( array( 'category' => 'plugins', 'slug' => $args->slug ) );

          //WPXtreme::log( $false );

          /*
           * eg:
           *     object(stdClass)#2621 (19) {
           *      ["name"]=> string(15) "Maintenance Pro"
           *      ["slug"]=> string(26) "wpx-maintenance-pro_000038"
           *      ["version"]=> string(5) "1.2.6"
           *      ["author"]=> string(30) "<a href="#">wpXtreme, Inc.</a>"
           *      ["author_profile"]=> string(17) "https://wpxtre.me"
           *      ["contributors"]=> array(0) { }
           *      ["requires"]=> string(3) "3.7"
           *      ["tested"]=> string(3) "3.7"
           *      ["compatibility"]=> array(1) {
           *        [0]=> string(3) "3.7"
           *      }
           *      ["rating"]=> int(1)
           *      ["num_ratings"]=> int(1)
           *      ["downloaded"]=> int(12)
           *      ["last_updated"]=> string(19) "2014-05-05 14:15:30"
           *      ["added"]=> string(19) "2013-04-22 13:23:13"
           *      ["homepage"]=> string(17) "https://wpxtre.me"
           *      ["download_link"]=> string(73) "http://beta.wpxtre.me/api/v1/wpxstore/plugin_download?token=token&id=1461"
           *      ["tags"]=> string(0) ""
           *      ["donate_link"]=> string(0) ""
           *      ["sections"]=> array(3) {
           *        ["description"]=> string(41) "The new version of the Auto-Update plugin"
           *        ["another_section"]=> string(23) "This is another section"
           *        ["changelog"]=> string(17) "Some new features"
           *      }
           *    }
           *
           */
        }

        break;
    }

    return $false;
  }







  // -------------------------------------------------------------------------------------------------------------------
  // THEMES
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * This hook is called before WordPress is storing the update theme list in transient
   *
   * @param object $transient The transient object before store in database
   *
   *    object(stdClass)#527 (3) {
   *      ["last_checked"]=> int(1377789184)
   *      ["checked"]=> array(6) {
   *        ["graphene"]=> string(5) "1.9.1"
   *        ["twentyeleven"]=> string(3) "1.0"
   *        ["twentyten"]=> string(3) "1.0"
   *        ["twentythirteen"]=> string(3) "1.0"
   *        ["twentytwelve"]=> string(3) "1.1"
   *        ["xtreme"]=> string(5) "2.0.2"
   *      }
   *      ["response"]=> array(3) {
   *        ["twentyeleven"]=> array(3) {
   *          ["new_version"]=> string(3) "1.6"
   *          ["url"]=> string(40) "http://wordpress.org/themes/twentyeleven"
   *          ["package"]=> string(57) "http://wordpress.org/themes/download/twentyeleven.1.6.zip"
   *        }
   *        ["twentyten"]=> array(3) {
   *          ["new_version"]=> string(3) "1.6"
   *          ["url"]=> string(37) "http://wordpress.org/themes/twentyten"
   *          ["package"]=> string(54) "http://wordpress.org/themes/download/twentyten.1.6.zip"
   *        }
   *        ["twentytwelve"]=> array(3) {
   *          ["new_version"]=> string(3) "1.2"
   *          ["url"]=> string(40) "http://wordpress.org/themes/twentytwelve"
   *          ["package"]=> string(57) "http://wordpress.org/themes/download/twentytwelve.1.2.zip"
   *        }
   *      }
   *    }
   *
   * @return object
   */
  public function pre_set_site_transient_update_themes( $transient )
  {
    // TODO Get themes list - it is an array of object WP_Theme
    //$themes = wp_get_themes();

    return $transient;
  }

}

/**
 * Signin Dialog for the WPX Store
 *
 * @class           WPXStoreSigninAlert
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-04
 * @version         1.0.0
 *
 */
class WPXStoreSigninAlert extends WPDKUIAlert {

  const ID = 'wpxm-wpxstore-reminder-signin';

  /**
   * Return a singleton instance of WPXStoreSigninAlert class
   *
   * @return WPXStoreSigninAlert
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
   * Create an instance of WPXStoreSigninAlert class
   *
   * @return WPXStoreSigninAlert
   */
  public function __construct()
  {
    parent::__construct( self::ID, false, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
  }

  /**
   * Display
   */
  public function content()
  {
    ?>
    <p><?php _e( 'You are signout from WPX Store. Please checkout your wpXtreme Preferences.' ) ?></p>
  <?php
  }

}


/**
 * License Warning Dialog for the WPX Store. Used reminder
 *
 * @class           WPXStoreLicenseDialog
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-04
 * @version         1.0.0
 *
 */
final class WPXStoreLicenseDialog extends WPDKUIModalDialog implements IWPXReminderDialog {

  /**
   * Days to expiry in seconds
   *
   * @var int $time_delta
   */
  public $time_delta = 0;

  /**
   * Return a singleton instance of WPXStoreLicenseDialog class
   *
   * @param WPXReminder $reminder Reminder instance
   *
   * @return WPXStoreLicenseDialog
   */
  public static function init( $reminder )
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self( $reminder );
    }

    return $instance;
  }

  /**
   * Create an instance of WPXStoreLicenseDialog class
   *
   * @param WPXReminder $reminder Reminder instance
   *
   * @return WPXStoreLicenseDialog
   */
  public function __construct( $reminder )
  {
    parent::__construct( 'wpx-store-license-dialog', __( 'Warning!' ) );

    // TODO get expiry date for this user
    $expiry_time = time() + ( 2 * DAY_IN_SECONDS );

    // Delta
    $this->time_delta = $expiry_time - time();

    $this->dismissButton = false;
    $this->backdrop      = false;
  }

  /**
   * Fires the reminder
   */
  public function remind()
  {
    $this->open();
  }

  /**
   * Override buttons
   *
   * @return array
   */
  public function buttons()
  {
    $buttons = array(
      'button_close' => array(
        'label'   => __( 'No, Thanks', WPXTREME_TEXTDOMAIN ),
        'class'   => 'alignleft',
        'dismiss' => true,
      ),
      'button_renew' => array(
        'label' => __( 'Renew', WPXTREME_TEXTDOMAIN ),
        'class' => 'button-primary alignright',
        'href'  => 'https://wpxtre.me/'
      )
    );

    return $buttons;
  }

  /**
   * Content of dialog
   *
   * @return string|void
   */
  public function content()
  {

    WPDKHTML::startCompress();

    // Expired
    if ( empty( $this->time_delta ) || $this->time_delta < 0 ) : ?>

      <h3>Sorry, your license is expired...</h3>

    <?php else : ?>

      <h3>Your license will expired in <?php echo date( 'd', $this->time_delta ) ?> days</h3>

    <?php endif;

    return WPDKHTML::endHTMLCompress();
  }

  /**
   * Process the post data
   */
  public function post_data()
  {
  }

}