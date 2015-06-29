<?php

/**
 * Manage view actions and filters all patch for WordPress Plugins list, install and update
 *
 * @class           WPXStorePlugins
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-02
 * @version         1.0.0
 * @since           1.3.0
 *
 */
class WPXStorePlugins {

  // Column
  const COLUMN_INFO = 'wpxm-column-info';

  // Column
  const PLUGIN_STATUS = 'wpxtreme';

  /**
   * An array with the list of wpXtreme plugins
   *
   * @var array $wpxtreme_plugins
   */
  public static $wpxtreme_plugins = array();

  /**
   * Return a singleton instance of WPXStorePlugins class
   *
   * @return WPXStorePlugins
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
   * Create an instance of WPXStorePlugins class
   *
   * @return WPXStorePlugins
   */
  public function __construct()
  {
    // Get only wpXtreme plugins
    foreach( self::wpxtreme_plugins() as $key => $plugin ) {
      // Fires at the end of the update message container in each row of the plugins list table.
      add_action( 'in_plugin_update_message-' . $key, array( $this, 'in_plugin_update_message' ), 10, 2 );
    }

    // Load scripts and styles
    add_action( 'admin_head', array( $this, 'admin_head' ) );

    /*
     * Plugins List
     */

    // Add a views for WPXtreme Plugins in plugins list
    add_filter( 'views_plugins', array( $this, 'views_plugins' ) );

    // Add custom columns - `get_columns`
    add_filter( 'manage_plugins_columns', array( $this, 'manage_plugins_columns' ) );

    // Content of custom columns
    add_action( 'manage_plugins_custom_column', array( $this, 'manage_plugins_custom_column'), 10, 3 );

    // Filter all plugins to show only wpXtreme plugins
    add_filter( 'all_plugins', array( $this, 'all_plugins' ) );

    // Filter global $plugins variable used for count the status
    add_filter( 'pre_set_transient_plugin_slugs', array( $this, 'pre_set_transient_plugin_slugs' ) );

    // Fires before the plugins list table is rendered.
    if( wpdk_is_bool( WPXtremePreferences::init()->wpxstore->display_profile ) ) {
      add_action( 'pre_current_active_plugins', array( $this, 'pre_current_active_plugins' ) );
    }
  }

  /**
   * Return the array with the wpXtreme plugins.
   *
   * @since 1.4.7
   *
   * @return array
   */
  public static function wpxtreme_plugins()
  {
    if( !empty( self::$wpxtreme_plugins ) ) {
      return self::$wpxtreme_plugins;
    }

    // Store all plugins
    $plugins = get_plugins();

    // Reset
    self::$wpxtreme_plugins = array();

    // Get only wpXtreme plugins
    foreach( $plugins as $key => $plugin ) {

      // Use the URI to get wpXtreme plugins
      if( false !== strpos( $plugin[ 'PluginURI' ], '/wpxtre.me' ) ) {
        // Memo
        self::$wpxtreme_plugins[ $key ] = $plugin;
      }
    }

    return self::$wpxtreme_plugins;

  }

  /**
   * Fires at the end of the update message container in each
   * row of the plugins list table.
   *
   * The dynamic portion of the hook name, $file, refers to the path
   * of the plugin's primary file relative to the plugins directory.
   *
   * @since 2.8.0
   *
   * @param array $plugin_data {
   *                           An array of plugin metadata.
   *
   * @type string $name        The human-readable name of the plugin.
   * @type string $plugin_uri  Plugin URI.
   * @type string $version     Plugin version.
   * @type string $description Plugin description.
   * @type string $author      Plugin author.
   * @type string $author_uri  Plugin author URI.
   * @type string $text_domain Plugin text domain.
   * @type string $domain_path Relative path to the plugin's .mo file(s).
   * @type bool   $network     Whether the plugin can only be activated network wide.
   * @type string $title       The human-readable title of the plugin.
   * @type string $author_name Plugin author's name.
   * @type bool   $update      Whether there's an available update. Default null.
   * }
   *
   * @param array $r           {
   *                           An array of metadata about the available plugin update.
   *
   * @type int    $id          Plugin ID.
   * @type string $slug        Plugin slug.
   * @type string $new_version New plugin version.
   * @type string $url         Plugin URL.
   * @type string $package     Plugin update package URL.
   * }
   */
  public function in_plugin_update_message( $plugin_data, $r )
  {
    // WPXtreme::log( $plugin_data, '$plugin_data' );

    /*
     * eg:
     *
     *     array(12) {
     *      ["Name"]=> string(9) "Bannerize"
     *      ["PluginURI"]=> string(17) "https://wpxtre.me"
     *      ["Version"]=> string(5) "1.3.4"
     *      ["Description"]=> string(32) "Easly manage ADV of your website"
     *      ["Author"]=> string(14) "wpXtreme, Inc."
     *      ["AuthorURI"]=> string(17) "https://wpxtre.me"
     *      ["TextDomain"]=> string(13) "wpx-bannerize"
     *      ["DomainPath"]=> string(12) "localization"
     *      ["Network"]=> bool(false)
     *      ["Title"]=> string(9) "Bannerize"
     *      ["AuthorName"]=> string(14) "wpXtreme, Inc."
     *      ["update"]=> bool(true)
     *    }
     */

    //WPXtreme::log( $r, '$r' );

    /*
     * eg:
     *
     *     object(stdClass)#2518 (8) {
     *      ["id"]=> string(3) "996"
     *      ["slug"]=> string(13) "wpx-bannerize"
     *      ["plugin"]=> string(22) "wpx-bannerize/main.php"
     *      ["new_version"]=> string(5) "2.0.0"
     *      ["url"]=> string(17) "https://wpxtre.me"
     *      ["package"]=> string(0) ""
     *      ["upgrade_notice"]=> string(145) "Warning! Plugin requires WordPress 4.2 ( Your current version is 3.9.1 ) - Plugin requires PHP 8.0.0 ( Your current version is 5.4.6-1ubuntu1.8 )"
     *      ["wpx_updgrade_notice"]=> array(2) {
     *        [0]=> string(63) "Plugin requires WordPress 4.2 ( Your current version is 3.9.1 )"
     *        [1]=> string(70) "Plugin requires PHP 8.0.0 ( Your current version is 5.4.6-1ubuntu1.8 )"
     *      }
     *    }
     *
     */

    // TODO Here we can addition plugin information as

    // TODO 1. if the user is not logged in
    // TODO 2. if the user is logged in but the product purchased or memberships is expire
    // TODO 3. more information on other products

    // Versions requirement
    if( isset( $r->wpx_updgrade_notice ) ) : ?>
      <h4><?php _e( 'Warning' ) ?></h4>
      <ol>
      <?php foreach( $r->wpx_updgrade_notice as $message ) : ?>
        <li><?php echo $message ?></li>
      <?php endforeach ?>
      </ol>
      <p><?php _e( 'Please, update your system and retry. If you still receive this message contact the support team.' ) ?></p>
    <?php endif;

  }

  /**
   * Fires in <head> for all admin pages.
   */
  public function admin_head()
  {
    wp_enqueue_style( 'wpxm-store-plugins', WPXTREME_URL_CSS . 'wpxm-store-plugins.css', array(), WPXTREME_VERSION );

    // Popover (and Tooltip)
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::PREFERENCES, WPDKUIComponents::POPOVER );

  }

  // -------------------------------------------------------------------------------------------------------------------
  // Plugins List
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * The dynamic portion of the hook name, $this->screen->id, refers
   * to the ID of the current screen, usually a string..
   *
   * @param array $views An array of available list table views.
   *
   * @return array
   */
  public function views_plugins( $views )
  {
    global $totals, $status;

    /*
     * $total
     *
     *     array(8) {
     *      ["all"]=> int(38)
     *      ["search"]=> int(0)
     *      ["active"]=> int(29)
     *      ["inactive"]=> int(9)
     *      ["recently_activated"]=> int(0)
     *      ["upgrade"]=> int(0)
     *      ["mustuse"]=> int(0)
     *      ["dropins"]=> int(0)
     *     }
     */

    if ( isset( $_REQUEST['plugin_status'] ) && self::PLUGIN_STATUS == $_REQUEST['plugin_status'] ) {
      $status = $_REQUEST['plugin_status'];
    }

    // Build url
    $url = add_query_arg( 'plugin_status', self::PLUGIN_STATUS, 'plugins.php' );

    // Class current
    $class = ( self::PLUGIN_STATUS == $status ) ? 'class="current"' : '';

    // Get count
    $count = (double)$totals[ self::PLUGIN_STATUS ];

    // Append my custom view
    $views['wpxtreme'] = sprintf( '<a href="%s" %s>%s <span class="count">(%s)</span></a>', $url, $class, __( 'WPXtreme' ), number_format_i18n( $count )  );

    return $views;
  }

  /**
   * Description
   *
   * @param array $columns An array of columns
   *
   * @return array
   */
  public function manage_plugins_columns( $columns )
  {

    $columns[ self::COLUMN_INFO ] = __( 'Detail' );

    return $columns;
  }

  /**
   * Fires inside each custom column of the Plugins list table.
   *
   * @param string $column_name Name of the column.
   * @param string $plugin_file Path to the plugin file. Eg: "adrotate-pro/adrotate.php"
   * @param array  $plugin_data An array of plugin data.
   *
   *     array(11) {
   *      ["Name"]=> string(21) "AdRotate Professional"
   *      ["PluginURI"]=> string(29) "http://www.adrotateplugin.com"
   *      ["Version"]=> string(5) "3.9.8"
   *      ["Description"]=> string(58) "The very best and most convenient way to publish your ads."
   *      ["Author"]=> string(31) "Arnan de Gans of AJdG Solutions"
   *      ["AuthorURI"]=> string(19) "http://www.ajdg.net"
   *      ["TextDomain"]=> string(0) ""
   *      ["DomainPath"]=> string(0) ""
   *      ["Network"]=> bool(false)
   *      ["Title"]=> string(21) "AdRotate Professional"
   *      ["AuthorName"]=> string(31) "Arnan de Gans of AJdG Solutions"
   *     }
   *
   */
  public function manage_plugins_custom_column( $column_name, $plugin_file, $plugin_data )
  {

    switch ( $column_name ) {

      // Info
      case self::COLUMN_INFO:

        // Get plugin slug
        $slug = dirname( $plugin_file );

        // Build the url
        $url = self_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $slug .
          '&amp;TB_iframe=true&amp;width=600&amp;height=550' );

        printf( '<a href="%s" class="thickbox button button-small" title="%s">%s</a>', $url, esc_attr( sprintf( __( 'More information about %s' ), $plugin_data['Name'] ) ), __( 'Open Details' ) );

        break;
    }
  }

  /**
   * Filter the full array of plugins to list in the Plugins list table.
   *
   * @param array $get_plugins An array of plugins to display in the list table.
   *
   *     array(38) {
   *      ["akismet/akismet.php"]=> array(11) {
   *        ["Name"]=> string(7) "Akismet"
   *        ["PluginURI"]=> string(19) "http://akismet.com/"
   *        ["Version"]=> string(5) "3.0.0"
   *        ["Description"]=> string(421) "Used by millions, Akismet is quite possibly the best way in the world to protect your blog from comment and trackback spam. It keeps your site protected from spam even while you sleep. To get started: 1) Click the "Activate" link to the left of this description, 2) Sign up for an Akismet API key, and 3) Go to your Akismet configuration page, and save your API key."
   *        ["Author"]=> string(10) "Automattic"
   *        ["AuthorURI"]=> string(40) "http://automattic.com/wordpress-plugins/"
   *        ["TextDomain"]=> string(7) "akismet"
   *        ["DomainPath"]=> string(0) ""
   *        ["Network"]=> bool(false)
   *        ["Title"]=> string(7) "Akismet"
   *        ["AuthorName"]=> string(10) "Automattic"
   *      }
   *      ["wpx-bannerize/main.php"]=> array(11) {
   *        ["Name"]=> string(9) "Bannerize"
   *        ["PluginURI"]=> string(17) "https://wpxtre.me"
   *        ["Version"]=> string(5) "1.3.4"
   *        ["Description"]=> string(32) "Easly manage ADV of your website"
   *        ["Author"]=> string(14) "wpXtreme, Inc."
   *        ["AuthorURI"]=> string(17) "https://wpxtre.me"
   *        ["TextDomain"]=> string(13) "wpx-bannerize"
   *        ["DomainPath"]=> string(12) "localization"
   *        ["Network"]=> bool(false)
   *        ["Title"]=> string(9) "Bannerize"
   *        ["AuthorName"]=> string(14) "wpXtreme, Inc."
   *      }
   *     ...
   *
   *
   * @return array
   */
  public function all_plugins( $get_plugins )
  {
    // Filter only wpXtreme plugins
    foreach ( $get_plugins as $plugin_slug => $plugin ) {

      // Is it a wpXtreme plugin ?
      if ( false === strpos( $plugin['PluginURI'], '/wpxtre.me' ) ) {

        // If looks for wpXtreme plugins only, remove other
        if( isset( $_REQUEST[ 'plugin_status' ] ) && self::PLUGIN_STATUS == $_REQUEST[ 'plugin_status' ] ) {

          // Removed no-wpXtreme plugin
          unset( $get_plugins[ $plugin_slug ] );
        }
      }
    }

    return $get_plugins;
  }

  /**
   * This filter works both `all_plugins` above.
   *
   * See `wp-admin/includes/class-wp-plugins-list-table.php`
   *
   * @param mixed $value New value of transient.
   *
   * @return mixed
   */
  public function pre_set_transient_plugin_slugs( $value )
  {
    global $plugins;

    $plugins[ self::PLUGIN_STATUS ] = self::$wpxtreme_plugins;

    return $value;
  }

  /**
   * Fires before the plugins list table is rendered.
   *
   * This hook also fires before the plugins list table is rendered in the Network Admin.
   *
   * Please note: The 'active' portion of the hook name does not refer to whether the current
   * view is for active plugins, but rather all plugins actively-installed.
   *
   * @param array $plugins_all An array containing all installed plugins.
   */
  public function pre_current_active_plugins( $plugins_all )
  {
    WPXStoreUserBarView::init()->display();
  }

}