<?php

/**
 * Manage view actions and filters all patch for WordPress Plugin Install
 *
 * @class           WPXStorePluginInstall
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-02
 * @version         1.0.0
 * @since           1.3.0
 *
 */
final class WPXStorePluginInstall {

  // Custom tab
  const TAB_WPXTREME = 'wpxtreme';

  /**
   * wpXtreme API
   *
   * @var WPXtremeAPI $api
   */
  public $api;

  /**
   * Return a singleton instance of WPXStorePluginInstall class
   *
   * @return WPXStorePluginInstall
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
   * Create an instance of WPXStorePluginInstall class
   *
   * @return WPXStorePluginInstall
   */
  public function __construct()
  {
    // Init the wpXtreme API
    $this->api = WPXtremeAPI::init();

    // Load scripts and styles
    add_action( 'admin_head', array( $this, 'admin_head' ) );

    // Init the screen help
    add_action( 'admin_head', array( 'WPXStorePluginInstallScreenHelp', 'init' ) );

    // Fires before each tab on the Install Plugins screen is loaded.
    add_action( 'install_plugins_pre_' . self::TAB_WPXTREME, array( $this, 'install_plugins_pre_wpxtreme' ) );

    // Filter the top header tabs with list " Search | Upload | Featured | Popular | Newest | Favorites "
    add_filter( 'install_plugins_tabs', array( $this, 'install_plugins_tabs' ) );

    // Not used
    add_filter( 'install_plugins_nonmenu_tabs', array( $this, 'install_plugins_nonmenu_tabs' ) );

    // Filter API request arguments for each Plugin Install screen tab.
    add_filter( 'install_plugins_table_api_args_' . self::TAB_WPXTREME, array( $this, 'install_plugins_table_api_args_wpxtreme' ) );

    // Override the Plugin Install API arguments. See method for other filter.
    //add_filter( 'plugins_api_args', array( $this, 'plugins_api_args' ), 10, 2 );

    // Allows a plugin to override the WordPress.org Plugin Install API entirely.
    add_filter( 'plugins_api', array( $this, 'plugins_api' ), 10, 3 );

    // Filter the Plugin Install API response results.
    add_filter( 'plugins_api_result', array( $this, 'plugins_api_result' ), 10, 3 );

    /*
     * List Table view
     */

    // Add a views for WPXtreme Plugins in plugins list
    if( wpdk_is_bool( WPXtremePreferences::init()->wpxstore->display_profile ) ) {
      add_filter( 'views_plugin-install', array( $this, 'views_plugin_install' ) );
    }

    // Fires after the plugins list table in each tab of the Install Plugins screen.
    add_action( 'install_plugins_dashboard', array( $this, 'install_plugins_dashboard' ) );
    add_action( 'install_plugins_search', array( $this, 'install_plugins_search' ) );
    add_action( 'install_plugins_upload', array( $this, 'install_plugins_upload' ) );
    add_action( 'install_plugins_featured', array( $this, 'install_plugins_featured' ) );
    add_action( 'install_plugins_popular', array( $this, 'install_plugins_popular' ) );
    add_action( 'install_plugins_new', array( $this, 'install_plugins_new' ) );

    // wpXtreme custom tab
    add_action( 'install_plugins_' . self::TAB_WPXTREME, array( $this, 'install_plugins_wpxtreme' ) );

    // Fires before the Plugin Install table header pagination is displayed.
    add_action( 'install_plugins_table_header', array( $this, 'install_plugins_table_header' ) );

    // Filter the install action links for a plugin.
    add_filter( 'plugin_install_action_links', array( $this, 'plugin_install_action_links'), 10, 2 );

  }

  /**
   * Fires in <head> for all admin pages.
   */
  public function admin_head()
  {
    wp_enqueue_style( 'wpxm-store-plugin-install', WPXTREME_URL_CSS . 'wpxm-store-plugin-install.css', array(), WPXTREME_VERSION );

    // Popover (and Tooltip)
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::PREFERENCES, WPDKUIComponents::POPOVER );

  }

  /**
   * Fires before each tab on the Install Plugins screen is loaded.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_pre_plugin-information'.
   */
  public function install_plugins_pre_wpxtreme()
  {
    // TODO Load scripts and styles
  }

  /**
   * Filter the tabs shown on the Plugin Install screen.
   *
   * @param array $tabs The tabs shown on the Plugin Install screen. Defaults are 'dashboard', 'search',
   *                    'upload', 'featured', 'popular', 'new', and 'favorites'.
   *
   *     array(6) {
   *       ["dashboard"]=> string(6) "Search"
   *       ["upload"]=> string(6) "Upload"
   *       ["featured"]=> string(8) "Featured"
   *       ["popular"]=> string(7) "Popular"
   *       ["new"]=> string(6) "Newest"
   *       ["favorites"]=> string(9) "Favorites"
   *     }
   *
   * @return array
   */
  public function install_plugins_tabs( $tabs )
  {
    // Image
    $icon = new WPDKHTMLTagImg( WPXTREME_URL_IMAGES . 'logo-16x16.png',  __( 'WPX Store', WPXTREME_TEXTDOMAIN ) );

    // Add my custom Tab
    $tabs = array_merge( $tabs, array( self::TAB_WPXTREME => $icon->html() . __( 'wpXtreme' ) ) );

    return $tabs;
  }

  /**
   * Filter tabs not associated with a menu item on the Plugin Install screen.
   *
   * @param array $nonmenu_tabs The tabs that don't have a Menu item on the Plugin Install screen.
   *
   *     array(1) {
   *      [0]=> string(18) "plugin-information"
   *     }
   *
   * @return array
   */
  public function install_plugins_nonmenu_tabs( $nonmenu_tabs )
  {
    return $nonmenu_tabs;
  }

  /**
   * Filter API request arguments for each Plugin Install screen tab.
   *
   * The dynamic portion of the hook name, $tab, refers to the plugin install tabs.
   * Default tabs are 'dashboard', 'search', 'upload', 'featured', 'popular', 'new',
   * and 'favorites'.
   *
   * @param array|bool $args Plugin Install API arguments.
   *
   *     $args = false
   *
   * @return array|bool
   */
  public function install_plugins_table_api_args_wpxtreme( $args )
  {
    $args['browse'] = self::TAB_WPXTREME;

    // TODO
    $args['per_page'] = 24;

    return $args;
  }

  /**
   * Override the Plugin Install API arguments.
   *
   * @note Not used at this moment
   *
   * Please ensure that an object is returned.
   *
   * @param object $args   Plugin API arguments.
   *
   *     object(stdClass)#2650 (3) {
   *       ["page"]=> int(1)
   *       ["per_page"]=> int(30)
   *       ["browse"]=> string(8) "featured"   // or "popular", "new"
   *     }
   *
   * @param string $action The type of information being requested from the Plugin Install API.
   *
   *     "query_plugins"
   *
   * @return object
   */
  public function plugins_api_args( $args, $action )
  {
    // We can change the $args when the key "browse" === self::TAB_WPXTREME
    // See plugins_api()

//    WPXtreme::log( $args, __METHOD__ );
//    WPXtreme::log( $action, __METHOD__ );

    return $args;
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

//    WPXtreme::log( $false, '$false ' . __METHOD__ );
//    WPXtreme::log( $action, '$action ' . __METHOD__ );
//    WPXtreme::log( $args, '$args ' . __METHOD__ );

    // Switch for action
    switch( $action ) {

      // Query Plugins
      case 'query_plugins':

        // My wpXtreme TAB
        if( isset( $args->browse ) && self::TAB_WPXTREME == $args->browse ) {

          // Send to the store server only slug and version
          $wpx_installed_plugins = array();

          // Loop into the installed wpXtreme plugins
          foreach ( WPXStorePlugins::wpxtreme_plugins() as $slug => $plugin ) {
            $wpx_installed_plugins[ $slug ] = $plugin['Version'];
          }

          // Query for all plugins
          $args = array(
            'category' => 'plugins',
            'plugins'  => $wpx_installed_plugins,
            'browse'   => $args->browse
          );

          //WPXtreme::log( $args, '$args' );

          // Request products plugins
          $plugins = $this->api->products( $args );

          // Fix the PHP Fatal error: Cannot use object of type stdClass as array in /wp-admin/includes/class-wp-plugin-install-list-table.php on line 416
          // For WordPress 4.0 >
          $plugins = json_decode( json_encode( $plugins ), true );

          //WPXtreme::log( $plugins, '$plugins' );

          // Santize first element as object
          $plugins = array_map( create_function( '$c', 'return (object)$c;' ), $plugins );

          //WPXtreme::log( $plugins, '$plugins' );

          /*
           * eg:
           *
           *     array(10) {
           *      [0]=> object(stdClass)#2589 (15) {
           *        ["name"]=> string(19) "Bannerize Analytics"
           *        ["slug"]=> string(30) "wpx-bannerize-analytics_000049"
           *        ["version"]=> string(5) "1.0.3"
           *        ["author"]=> string(30) "<a href="#">wpXtreme, Inc.</a>"
           *        ["author_profile"]=> string(17) "https://wpxtre.me"
           *        ["contributors"]=> array(0) { }
           *        ["requires"]=> string(3) "3.7"
           *        ["tested"]=> string(3) "3.7"
           *        ["rating"]=> int(1)
           *        ["num_ratings"]=> int(1)
           *        ["compatibility"]=> array(1) {
           *          [0]=> string(3) "3.7"
           *        }
           *        ["homepage"]=> string(17) "https://wpxtre.me"
           *        ["description"]=> string(0) ""
           *        ["short_description"]=> string(0) ""
           *        ["action_links"]=> string(5) "hello"
           *      }
           */

          $result                 = new stdClass();
          $result->info           = array( 'results' => count( $plugins ) );

          // Since WordPress 4.0
          if( isset( $result->info['groups'] ) ) {
            $result->info['groups'] = array_merge( array( 'wpxtreme' => 'wpXtreme' ), $result->info['groups'] );
          }

          $result->plugins = $plugins;

          return $result;
        }
        break;

    }

    return $false;
  }

  /**
   * Filter the Plugin Install API response results.
   *
   * @note Used to patch and modify the stamdard WordPress list as 'feature', ...
   *
   * @param object|WP_Error $res    Response object or WP_Error.
   *
   *     object(stdClass)#2648 (2) {
   *         ["info"]=> array(3) {
   *           ["page"]=> int(1)
   *           ["pages"]=> int(1)
   *           ["results"]=> int(6)
   *         }
   *         ["plugins"]=> array(6) {
   *           [0]=> object(stdClass)#2618 (14) {
   *             ["name"]=> string(14) "WP Super Cache"
   *             ["slug"]=> string(14) "wp-super-cache"
   *             ["version"]=> string(3) "1.4"
   *             ["author"]=> string(49) "<a href="http://ocaoimh.ie/">Donncha O Caoimh</a>"
   *             ["author_profile"]=> string(32) "//profiles.wordpress.org/donncha"
   *             ["contributors"]=> array(2) {
   *               ["donncha"]=> string(32) "//profiles.wordpress.org/donncha"
   *               ["automattic"]=> string(35) "//profiles.wordpress.org/automattic"
   *             }
   *             ["requires"]=> string(3) "3.0"
   *             ["tested"]=> string(3) "3.9"
   *             ["compatibility"]=> array(1) {
   *               ["3.9"]=> array(1) {
   *                 ["1.4"]=> array(3) {
   *                   [0]=> int(78)
   *                   [1]=> int(9)
   *                   [2]=> int(7)
   *                 }
   *               }
   *             }
   *             ["rating"]=> float(83.4)
   *             ["num_ratings"]=> int(1808)
   *             ["homepage"]=> string(33) "http://ocaoimh.ie/wp-super-cache/"
   *             ["description"]=> string(5734) "<p>This plugin ... PHP scripts.</p>"
   *             ["short_description"]=> string(73) "A very fast caching engine for WordPress that produces static html files."
   *         }
   *           [1] => ...
   *       }
   *
   * @param string          $action The type of information being requested from the Plugin Install API.
   * @param object          $args   Plugin API arguments.
   *
   *     array(2) {
   *       ["timeout"]=> int(15)
   *       ["body"]=> array(2) {
   *         ["action"]=> string(13) "query_plugins"
   *         ["request"]=> string(83) "O:8:"stdClass":3:{s:4:"page";i:1;s:8:"per_page";i:30;s:6:"search";s:9:"bannerize";}"
   *       }
   *     }
   *
   *     // request for search
   *
   *     object(stdClass)#2587 (3) {
   *      ["page"]=> int(1)
   *      ["per_page"]=> int(30)
   *      ["search"]=> string(9) "bannerize"
   *    }
   *
   *    // request for featured, new,
   *
   *     object(stdClass)#2611 (3) {
   *      ["page"]=> int(1)
   *      ["per_page"]=> int(30)
   *      ["browse"]=> string(8) "featured"
   *    }
   *
   * @return object|\WP_Error
   */
  public function plugins_api_result( $res, $action, $args )
  {
//    WPXtreme::log( $res, '$res' );
//    WPXtreme::log( $action, '$action' );
//    WPXtreme::log( $args, '$args' );

    // Casting in array
    $args = (array)$args;

    if ( isset( $args['body'] ) && isset( $args['body']['request'] ) ) {
      $request = unserialize( $args['body']['request'] );

      //WPXtreme::log( $request, 'request' );
    }

    if( empty( $request ) ) {
      return $res;
    }

    // Prepare type and key
    $search_type = '';
    $search_keyword = '';

    // Get keyword
    foreach ( $request as $property => $value ) {
      if ( in_array( $property, array( 'search', 'author', 'tag' ) ) ) {
        $search_type    = $property;
        $search_keyword = $value;
      }
    }

    // SEARCH
    if( ! empty( $search_type ) ) {

      // Send to the store server only slug and version
      $wpx_installed_plugins = array();

      // Loop into the installed wpXtreme plugins
      foreach ( WPXStorePlugins::wpxtreme_plugins() as $slug => $plugin ) {
        $wpx_installed_plugins[ $slug ] = $plugin['Version'];
      }

      // Query for keyword
      $args = array(
        'category'       => 'plugins',
        'plugins'        => $wpx_installed_plugins,
        'browse'         => 'search',
        'search_keyword' => $search_keyword,
        'search_type'    => $search_type,
      );

      // Request products plugins
      $plugins = $this->api->products( $args );

      // Fix the PHP Fatal error: Cannot use object of type stdClass as array in /wp-admin/includes/class-wp-plugin-install-list-table.php on line 416
      // For WordPress 4.0 >
      $plugins = json_decode( json_encode( $plugins ), true );

      // Stability
      if( empty( $plugins ) ) {
        return $res;
      }

      // Increment count result
      $res->info['results'] += count( $plugins );

      // Merge result
      $res->plugins = array_merge( $plugins, $res->plugins );

      return $res;
    }

    // OTHER, featured, new, popular
    if ( isset( $request->browse ) && 'beta' !== $request->browse ) {

      // Send to the store server only slug and version
      $wpx_installed_plugins = array();

      // Loop into the installed wpXtreme plugins
      foreach ( WPXStorePlugins::wpxtreme_plugins() as $slug => $plugin ) {
        $wpx_installed_plugins[ $slug ] = $plugin['Version'];
      }

      // Query for all plugins
      $args = array(
        'category'  => 'plugins',
        'plugins'   => $wpx_installed_plugins,
        'browse'    => $request->browse,
      );

      // Request products plugins
      $plugins = $this->api->products( $args );

      // Stability
      if( !empty( $plugins ) ) {

        // Fix the PHP Fatal error: Cannot use object of type stdClass as array in /wp-admin/includes/class-wp-plugin-install-list-table.php on line 416
        // For WordPress 4.0 >
        $plugins = json_decode( json_encode( $plugins ), true );

        // Santize first element as object
        $plugins = array_map( create_function( '$c', 'return (object)$c;' ), $plugins );

        //      // Save this new plugins key in the options
        //      $wpxstore_plugins_slug = array();
        //      foreach ( $plugins as $plugin ) {
        //        $wpxstore_plugins_slug[] = $plugin->slug;
        //      }
        //      update_site_option( 'wpxstore_plugins_slug', $wpxstore_plugins_slug );

        //WPXtreme::log( $plugins, '$plugins' );

        /*
         * eg:
         *
         *     array(10) {
         *      [0]=> object(stdClass)#2589 (15) {
         *        ["name"]=> string(19) "Bannerize Analytics"
         *        ["slug"]=> string(30) "wpx-bannerize-analytics_000049"
         *        ["version"]=> string(5) "1.0.3"
         *        ["author"]=> string(30) "<a href="#">wpXtreme, Inc.</a>"
         *        ["author_profile"]=> string(17) "https://wpxtre.me"
         *        ["contributors"]=> array(0) { }
         *        ["requires"]=> string(3) "3.7"
         *        ["tested"]=> string(3) "3.7"
         *        ["rating"]=> int(1)
         *        ["num_ratings"]=> int(1)
         *        ["compatibility"]=> array(1) {
         *          [0]=> string(3) "3.7"
         *        }
         *        ["homepage"]=> string(17) "https://wpxtre.me"
         *        ["description"]=> string(0) ""
         *        ["short_description"]=> string(0) ""
         *        ["action_links"]=> string(5) "hello"
         *      }
         */

        // Increment count
        $res->info['results'] += count( $plugins );

        if ( isset( $res->info['groups'] ) ) {
          $res->info['groups'] = array_merge( array( 'wpxtreme' => ' wpXtreme' ), $res->info['groups'] );
        }
        elseif( 'popular' !== $request->browse ) {
          $res->info['groups'] = array( 'wpxtreme' => ' wpXtreme', 'wordpress' => 'WordPress' );
          $res->plugins = array_map( create_function( '$c', '$c->group = "wordpress";return $c;' ), $res->plugins );
        }

        // Mixed
        $res->plugins = array_merge( $plugins, $res->plugins );
      }
    }

    return $res;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // List Table View
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * The dynamic portion of the hook name, $this->screen->id, refers
   * to the ID of the current screen, usually a string..
   *
   * @param array $views An array of available list table views.
   *
   * array(5) {
   *      ["plugin-install-featured"]=> string(113) "<a href='http://release-testing.wpxtre.me/wp-admin/plugin-install.php?tab=featured' class=' current'>Featured</a>"
   *      ["plugin-install-popular"]=> string(103) "<a href='http://release-testing.wpxtre.me/wp-admin/plugin-install.php?tab=popular' class=''>Popular</a>"
   *      ["plugin-install-favorites"]=> string(107) "<a href='http://release-testing.wpxtre.me/wp-admin/plugin-install.php?tab=favorites' class=''>Favorites</a>"
   *      ["plugin-install-beta"]=> string(105) "<a href='http://release-testing.wpxtre.me/wp-admin/plugin-install.php?tab=beta' class=''>Beta Testing</a>"
   *      ["plugin-install-wpxtreme"]=> string(259) "<a href='http://release-testing.wpxtre.me/wp-admin/plugin-install.php?tab=wpxtreme' class=''>
   *                                                   <img alt="WPX Store" height="" src="http://release-testing.wpxtre.me/wp-content/plugins/wpxtreme/assets/css/images/logo-16x16.png" width="" accesskey=""/>wpXtreme</a>"
   *    }
   *
   * @return array
   */
  public function views_plugin_install( $views )
  {

    /*
     * $tab
     *
     * "dashboard", "upload", "featured", ... '"wpxtreme"
     */

    WPXStoreUserBarView::init()->display();

    return $views;

  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   */
  public function install_plugins_dashboard()
  {

    $message = __( 'This view is enhanced by wpXtreme plugin.' );
    $alert = new WPDKUIAlert( 'wpx-install-plugins-dashboard', $message, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
    $alert->dismissPermanent = true;
    $alert->display();

  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   */
  public function install_plugins_search()
  {
    $message = __( 'This view is enhanced by wpXtreme plugin.' );
    $alert = new WPDKUIAlert( 'wpx-install-plugins-search', $message, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
    $alert->dismissPermanent = true;
    $alert->display();
  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   */
  public function install_plugins_upload()
  {
    // Install a plugin by a zip
  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   */
  public function install_plugins_featured()
  {
    $message = __( 'This view is enhanced by wpXtreme plugin.' );
    $alert = new WPDKUIAlert( 'wpx-install-plugins-featured', $message, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
    $alert->dismissPermanent = true;
    $alert->display();
  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   */
  public function install_plugins_popular()
  {
    $message = __( 'This view is enhanced by wpXtreme plugin.' );
    $alert = new WPDKUIAlert( 'wpx-install-plugins-popular', $message, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
    $alert->dismissPermanent = true;
    $alert->display();
  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   */
  public function install_plugins_new()
  {
    $message = __( 'This view is enhanced by wpXtreme plugin. Here you can find the latest wpXtreme plugins.' );
    $alert = new WPDKUIAlert( 'wpx-install-plugins-new', $message, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
    $alert->dismissPermanent = true;
    $alert->display();

  }

  /**
   * Fires after the plugins list table in each tab of the Install Plugins screen.
   *
   * The dynamic portion of the action hook, $tab, allows for targeting
   * individual tabs, for instance 'install_plugins_plugin-information'.
   *
   * @param int $paged The current page number of the plugins list table.
   */
  public function install_plugins_wpxtreme( $paged )
  {
    global $wp_list_table;

    if ( current_filter() == 'install_plugins_favorites' && empty( $_GET['user'] ) && !get_user_option( 'wporg_favorites' ) ) {
      return;
    }

    $message = __( 'This is the list of wpXtreme plugins' );
    $alert = new WPDKUIAlert( 'wpx-install-plugins-wpxtreme', $message, WPDKUIAlertType::INFORMATION, __( 'Information' ) );
    $alert->dismissPermanent = true;
    $alert->display();

    // Display the standa WordPress table
    $wp_list_table->display();

  }

  /**
   * Fires before the Plugin Install table header pagination is displayed.
   *
   * @todo to implment
   */
  public function install_plugins_table_header()
  {
    return;

//    global $tab;
//
//    switch( $tab ) {
//
//      // wpXtreme TAB
//      case self::TAB_WPXTREME:
//        ?>
<!--        <select class="">-->
<!--          <option>Tools</option>-->
<!--          <option>Social</option>-->
<!--        </select>-->
<!--        --><?php
//        break;
//    }

  }

  /**
   * Filter the install action links for a plugin.
   * We use this filter to add our custom action links as 'Buy', 'Renew', ...
   *
   * @param array $action_links An array of plugin action hyperlinks. Defaults are links to Details and Install Now.
   *
   *     array(2) {
   *      [0]=> string(235) "<a href="http://beta.wpxtre.me/wp-admin/plugin-install.php?tab=plugin-information&plugin=wp-super-cache&TB_iframe=true&width=600&height=550"
   *                            class="thickbox"
   *                            title="More information about WP Super Cache 1.4">Details</a>"
   *      [1]=> string(194) "<a class="install-now"
   *                            href="http://beta.wpxtre.me/wp-admin/update.php?action=install-plugin&plugin=wp-super-cache&_wpnonce=976b4115f1"
   *                            title="Install WP Super Cache 1.4">Install Now</a>"
   *     }
   *
   * @param array $plugin       The plugin currently being listed.
   *
   *     array(14) {
   *      ["name"]=> string(8) "wpXtreme"
   *      ["slug"]=> string(8) "wpxtreme"
   *      ["version"]=> string(5) "1.1.5"
   *      ["author"]=> string(30) "wpXtreme, Inc."
   *      ["author_profile"]=> string(17) "https://wpxtre.me"
   *      ["contributors"]=> array(0) { }
   *      ["requires"]=> string(3) "3.6"
   *      ["tested"]=> string(5) "3.7.1"
   *      ["rating"]=> int(100)
   *      ["num_ratings"]=> int(1000)
   *      ["compatibility"]=> array(1) {
   *        ["3.7.1"]=> array(1) {
   *          ["1.1.5"]=> array(3) {
   *            [0]=> int(100)
   *            [1]=> int(1)
   *            [2]=> int(1)
   *          } }
   *      }
   *      ["homepage"]=> string(17) "https://wpxtre.me"
   *      ["description"]=> string(26) "description plugin in html"
   *      ["short_description"]=> string(26) "description plugin in html"
   *    }
   *
   * @return array
   * @note Remember that the array above can be modify with custom property - see self::plugins_api_result() above
   *
   */
  public function plugin_install_action_links( $action_links, $plugin )
  {

    //WPXtreme::log( $action_links, '$action_links' );
    //WPXtreme::log( $plugin, '$plugin' );

    // TODO Test #1#
    if ( isset( $plugin['action_links'] ) && !empty( $plugin['action_links'] ) ) {
      $action_links[0] = $plugin['action_links'];
    }

    return $action_links;
  }

}

/**
 * Description
 *
 * @class           WPXStorePluginInstallScreenHelp
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-02
 * @version         1.0.0
 *
 */
class WPXStorePluginInstallScreenHelp extends WPDKScreenHelp {

  /**
   * Return an instance of WPXStorePluginInstallScreenHelp class
   *
   * @return WPXStorePluginInstallScreenHelp
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
      __( 'Adding wpXtreme Plugins', WPXTREME_TEXTDOMAIN ) => array( $this, 'introducing' ),
    );

    return $tabs;
  }

  /**
   * Introducing
   */
  public function introducing()
  {
    WPDKHTML::startCompress(); ?>
    <p><?php _e( 'Please, visit the <a href="https://wpxtre.me">wpXtreme web site</a> and contact us.', WPXTREME_TEXTDOMAIN ) ?></p>
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