<?php

/**
 * Manage view actions and filters all patch for WordPress Theme Install.
 * This class init is called on 'init' action.
 *
 * TODO Remove unused action,filter/method
 *
 * @class           WPXStoreThemeInstall
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-02
 * @version         1.0.0
 * @since           1.3.0
 *
 */
final class WPXStoreThemeInstall {

  // Custom tab
  const TAB_WPXTREME = 'wpxtreme';

  /**
   * wpXtreme API
   *
   * @var WPXtremeAPI $api
   */
  public $api;

  /**
   * Return a singleton instance of WPXStoreThemeInstall class
   *
   * @return WPXStoreThemeInstall
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
   * Create an instance of WPXStoreThemeInstall class
   *
   * @note  This class is init on 'init' action
   *
   * @return WPXStoreThemeInstall
   */
  public function __construct()
  {
    // Init the wpXtreme API
    $this->api = WPXtremeAPI::init();

    // Load scripts and styles
    add_action( 'admin_head-theme-install.php', array( $this, 'admin_head' ) );

    // Init the screen help
    add_action( 'admin_head-theme-install.php', array( 'WPXStoreThemeInstallScreenHelp', 'init' ) );

    // Filter the Plugin Install API response results.
    add_filter( 'themes_api_result', array( $this, 'themes_api_result' ), 10, 3 );

  }

/**
 * Fires in <head> for all admin pages.
 */
  public function admin_head()
  {
    wp_enqueue_style( 'wpxm-store-theme-install', WPXTREME_URL_CSS . 'wpxm-store-theme-install.css', array(), WPXTREME_VERSION );
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
   *     object(stdClass)#2650 (3) {
   *       ["page"]=> int(1)
   *       ["per_page"]=> int(30)
   *       ["browse"]=> string(8) "featured"   // or "popular", "new"
   *     }
   *
   * @return \stdClass
   */
  public function plugins_api( $false, $action, $args )
  {

//    WPXtreme::log( $false, __METHOD__ );
//    WPXtreme::log( $action, __METHOD__ );
//    WPXtreme::log( $args, __METHOD__ );

    // This check is redundant
    if( self::TAB_WPXTREME == $args->browse ) {

      // Switch for action
      switch( $action ) {

        // Query on WPX Store
        case 'query_plugins':

          // TODO Replace with HTTP REQUEST to WPX Store - use 'page' and 'per_page'
          $false = new stdClass();
          $false->info = array( 'page' => 1, 'per_page' => $args->per_page, 'pages' => 1, 'results' => 1 );

          $plugin                    = new stdClass();
          $plugin->name              = 'wpXtreme';
          $plugin->slug              = 'wpxtreme';
          $plugin->version           = '1.1.5';
          $plugin->author            = '<a href="#">wpXtreme, Inc.</a>';
          $plugin->author_profile    = 'https://wpxtre.me';
          $plugin->contributors      = array();
          $plugin->requires          = '3.6';
          $plugin->tested            = '3.7.1';
          $plugin->rating            = 100;
          $plugin->num_ratings       = 1000;
          $plugin->compatibility     = array( '3.7.1' => array( '1.1.5' => array( 100, 1, 1) ) );
          $plugin->homepage          = 'https://wpxtre.me';
          $plugin->description       = 'description plugin in html';
          $plugin->short_description = 'description plugin in html';

          $false->plugins = array( $plugin );

          break;
      }
    }

    return $false;
  }

  /**
   * Filter the returned WordPress.org Themes API response.
   *
   * @param array|object $res    WordPress.org Themes API response.
   *
   *     object(stdClass)#2760 (2) {
   *      ["info"]=> array(3) {
   *        ["page"]=> int(1)
   *        ["pages"]=> int(0)
   *        ["results"]=> bool(false)
   *      }
   *      ["themes"]=> array(6) {
   *        [0]=> object(stdClass)#2759 (12) {
   *          ["name"]=> string(9) "Sparkling"
   *          ["slug"]=> string(9) "sparkling"
   *          ["version"]=> string(5) "1.1.0"
   *          ["author"]=> string(8) "silkalns"
   *          ["preview_url"]=> string(30) "http://wp-themes.com/sparkling"
   *          ["screenshot_url"]=> string(63) "http://wp-themes.com/wp-content/themes/sparkling/screenshot.png"
   *          ["rating"]=> float(100)
   *          ["num_ratings"]=> int(3)
   *          ["downloaded"]=> int(4933)
   *          ["last_updated"]=> string(10) "2014-04-25"
   *          ["homepage"]=> string(37) "http://wordpress.org/themes/sparkling"
   *          ["description"]=> string(728) "Sparkling is a clean minimal and responsive WordPress theme well suited for travel, health, business, finance, design, art, personal and any other creative websites and blogs. Developed using Bootstrap 3 that makes it mobile and tablets friendly. Theme comes with full-screen slider, social icon integration, author bio, popular posts widget and improved category widget. Sparkling incorporates latest web standards such as HTML5 and CSS3 and is SEO friendly thanks to its clean structure and codebase. It has dozens of Theme Options to change theme layout, colors, fonts, slider settings and much more. Theme is also translation and multilingual ready. Sparkling is a free WordPress theme with premium functionality and design."
   *        }
   *        [1]=> object(stdClass)#2658 (12) {
   *          ["name"]=> string(21) "Independent Publisher"
   *          ["slug"]=> string(21) "independent-publisher"
   *          ["version"]=> string(3) "1.5"
   *          ["author"]=> string(7) "raamdev"
   *          ["preview_url"]=> string(42) "http://wp-themes.com/independent-publisher"
   *          ["screenshot_url"]=> string(75) "http://wp-themes.com/wp-content/themes/independent-publisher/screenshot.png"
   *          ["rating"]=> float(100)
   *          ["num_ratings"]=> int(9)
   *          ["downloaded"]=> int(5329)
   *          ["last_updated"]=> string(10) "2014-04-24"
   *          ["homepage"]=> string(49) "http://wordpress.org/themes/independent-publisher"
   *          ["description"]=> string(321) "Independent Publisher is a beautiful reader-focused WordPress theme, for you. Clean, responsive, and mobile-ready, it gets out of your way and lets you share what you create. Full support for all Post Formats, HTML5-ready, and includes Schema.org markup. This theme is ideal for both single-author and multi-author blogs."
   *        }
   *        ...
   *      }
   *    }
   *
   * @param string       $action Requested action. Likely values are 'theme_information', 'feature_list', or 'query_themes'.
   *
   *     query_themes
   *
   * @param object       $args   Arguments used to query for installer pages from the WordPress.org Themes API.
   *
   *     array(1) {
   *      ["body"]=>
   *      array(2) {
   *        ["action"]=>
   *        string(12) "query_themes"
   *        ["request"]=>
   *        string(342) "O:8:"stdClass":3:{s:8:"per_page";s:3:"100";s:6:"fields";a:9:{s:11:"description";s:4:"true";s:6:"tested";s:4:"true";s:8:"requires";s:4:"true";s:6:"rating";s:4:"true";s:10:"downloaded";s:4:"true";s:12:"downloadLink";s:4:"true";s:12:"last_updated";s:4:"true";s:8:"homepage";s:4:"true";s:11:"num_ratings";s:4:"true";}s:6:"browse";s:8:"featured";}"
   *      }
   *    }
   *
   * @return array|object
   */
  public function themes_api_result( $res, $action, $args )
  {

//    WPXtreme::log( $res, __METHOD__ );
//    WPXtreme::log( $action, __METHOD__ );
//    WPXtreme::log( $args, __METHOD__ );

    // Cating in array
    $args = (array)$args;

    if ( isset( $args['body'] ) && isset( $args['body']['request'] ) ) {

      /*
       *     object(stdClass)#2652 (3) {
       *      ["per_page"]=> string(3) "100"
       *      ["fields"]=> array(9) {
       *        ["description"]=> string(4) "true"
       *        ["tested"]=> string(4) "true"
       *        ["requires"]=> string(4) "true"
       *        ["rating"]=> string(4) "true"
       *        ["downloaded"]=> string(4) "true"
       *        ["downloadLink"]=> string(4) "true"
       *        ["last_updated"]=> string(4) "true"
       *        ["homepage"]=> string(4) "true"
       *        ["num_ratings"]=> string(4) "true"
       *      }
       *      ["browse"]=> string(8) "featured" | "popular" | "new"
       *    }
       *
       *    // IF SEARCH
       *
       *    object(stdClass)#2754 (3) {
       *      ["per_page"]=> string(3) "100"
       *      ["fields"]=> array(9) {
       *        ["description"]=> string(4) "true"
       *        ["tested"]=> string(4) "true"
       *        ["requires"]=> string(4) "true"
       *        ["rating"]=> string(4) "true"
       *        ["downloaded"]=> string(4) "true"
       *        ["downloadLink"]=> string(4) "true"
       *        ["last_updated"]=> string(4) "true"
       *        ["homepage"]=> string(4) "true"
       *        ["num_ratings"]=> string(4) "true"
       *      }
       *      ["search"]=> string(4) "ciao"
       *    }
       *
       */
      $request = unserialize( $args['body']['request'] );
      //WPXtreme::log( $request, 'request' );

      // "featured" | "popular" | "new"
      if ( isset( $request->browse ) || isset( $request->search ) ) {

        // TODO add switch for featured, new, popular

        // TODO Replace with HTTP REQUEST to WPX Store - use 'page' and 'per_page' and request info
        //$res->info['results']++;

        $theme                 = new stdClass();
        $theme->name           = 'wpXtreme';
        $theme->slug           = 'wpxtreme';
        $theme->version        = '1.1.5';
        $theme->author         = 'wpXtreme';
        $theme->preview_url    = 'https://wpxtre.me';
        $theme->install_url    = 'https://wpxtre.me';
        $theme->screenshot_url = array();
        $theme->rating         = 100;
        $theme->num_ratings    = 3;
        $theme->downloaded     = 123;
        $theme->last_updated   = '2014-05-01';
        $theme->homepage       = 'https://wpxtre.me';
        $theme->description    = 'description plugin in html';

        array_unshift( $res->themes, $theme );
      }

    }

    return $res;
  }
}

/**
 * Description
 *
 * @class           WPXStoreThemeInstallScreenHelp
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-02
 * @version         1.0.0
 *
 */
class WPXStoreThemeInstallScreenHelp extends WPDKScreenHelp {

  /**
   * Return an instance of WPXStoreThemeInstallScreenHelp class
   *
   * @return WPXStoreThemeInstallScreenHelp
   */
  public static function init()
  {
    $instance = new self;
    $instance->display();
  }

  /**
   * Return a key value pairs array with the list of tabs
   *
   * @param array $tabs List of tabs
   *
   * @return array
   */
  public function tabs( $tabs = array() )
  {
    $tabs = array(
      __( 'Adding wpXtreme Themes', WPXTREME_TEXTDOMAIN ) => array( $this, 'introducing' ),
    );

    return $tabs;
  }

  /**
   * Introducing
   */
  public function introducing()
  {
    WPDKHTML::startCompress(); ?>
    <p><?php _e( 'Mail Custom Post Type can be used for various purposes.', WPXTREME_TEXTDOMAIN ) ?></p>
    <p><?php _e( 'For example, when a user is locked for some reason, a mail may be sent to the web master or any other email address.', WPXTREME_TEXTDOMAIN ) ?></p>
    <?php
    echo WPDKHTML::endHTMLCompress();
  }

  /**
   * Return the HTML markup for sidebar
   *
   * @return string
   */
  public function sidebar()
  {
    WPDKHTML::startCompress(); ?>
    <h4><?php _e( 'wpXtreme information:', WPXTREME_TEXTDOMAIN ) ?></h4>
    <ul>
      <li><a href="https://wpxtre.me/" target="_blank"><?php _e( 'wpXtreme Web Site', WPXTREME_TEXTDOMAIN )?></a></li>
      <li><a href="http://wpdk.io/" target="_blank"><?php _e( 'WPDK Docs', WPXTREME_TEXTDOMAIN )?></a></li>
      <li><a href="https://wpxtre.me/forums" target="_blank"><?php _e( 'Community', WPXTREME_TEXTDOMAIN )?></a></li>
      <li><a href="https://wpxtre.me/faq" target="_blank"><?php _e( 'FAQ', WPXTREME_TEXTDOMAIN )?></a></li>
    </ul>
    <?php return WPDKHTML::endHTMLCompress();
  }

}