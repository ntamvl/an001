<?php

/**
 * Admin Class for backend managment
 *
 * @class              WPXtremeAdmin
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-09-21
 * @version            1.0.4
 *
 * @history            1.0.4 - Added admin bar settings.
 *
 */

class WPXtremeAdmin extends WPDKWordPressAdmin {

  /**
   * This is the minimun capability required to display admin menu item
   *
   * @note This constant will be removed when the new roles and caps engine is ready
   */
  const MENU_CAPABILITY = 'manage_options';

  /**
   * Return a singleton instance of WPXtremeAdmin class
   *
   * @return WPXtremeAdmin
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
   * Create an instance of WPXtremeAdmin class
   *
   * @return WPXtremeAdmin
   */
  public function __construct()
  {
    /**
     * @var WPXtreme $plugin
     */
    $plugin = $GLOBALS['WPXtreme'];
    parent::__construct( $plugin );

    // Fires when styles are printed for all admin pages.
    add_action( 'admin_print_styles', array( $this, 'admin_print_styles' ) );

    // Fires when scripts are printed for all admin pages.
    add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ) );

    // Fires when styles are printed for a specific admin page based on $hook_suffix.
    add_action( 'admin_print_styles-plugins.php', array( $this, 'admin_print_styles_plugins' ) );

    // Plugin List
    add_action( 'plugin_action_links_' . $this->plugin->pluginBasename, array( $this, 'plugin_action_links' ), 10, 4 );

    // Fires after core widgets for the admin dashboard have been registered.
    add_action( 'wp_dashboard_setup', array( 'WPXtremeDashboard', 'init' ) );

    // Fires after the opening tag for the admin footer.
    add_action( 'in_admin_footer', array( $this, 'in_admin_footer' ) );

    // Print scripts or data before the default footer scripts.
    add_action( 'admin_footer', array( $this, 'admin_footer' ) );

    // Add the wpXtreme and WPDK version
    $this->bodyClasses['wpdk-' . str_replace( '.', '-', WPDK_VERSION )] = true;

    // Add classes for enhancer UI layout
    $appearance = WPXtremePreferences::init()->appearance;

    // Enhancements
    $this->bodyClasses['wpxm-body-display-ajax-loader'] = wpdk_is_bool( $appearance->display_ajax );
    $this->bodyClasses['wpxm-body-toolbars']            = wpdk_is_bool( $appearance->toolbars );
    $this->bodyClasses['wpxm-body-table_actions']       = wpdk_is_bool( $appearance->table_actions ) && !wp_is_mobile();
    $this->bodyClasses['wpxm-body-inputs']              = wpdk_is_bool( $appearance->inputs );
    $this->bodyClasses['wpxm-body-tables']              = wpdk_is_bool( $appearance->tables );
    $this->bodyClasses['wpxm-body-inline_edit']         = wpdk_is_bool( $appearance->inline_edit );
    $this->bodyClasses['wpxm-body-remove_revisions']    = wpdk_is_bool( $appearance->remove_revisions );

    // Added wpXtreme menu to admin bar
    //add_action( 'wp_before_admin_bar_render', array( $this, 'wp_before_admin_bar_render' ) );
    //add_action( 'in_admin_header', array( $this, 'in_admin_header' ), -1 );

    // Only for administrator
    if( current_user_can( 'manage_options' ) ) {

      // Load all necessary admin bar items.
      add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
    }

  }

  /**
   * Fires when styles are printed for all admin pages.
   *
   * @since WP 2.6.0
   */
  public function admin_print_styles()
  {
    // All styles
    wp_deregister_style( 'jquery-ui' );
    wp_enqueue_style( 'wpxm-admin', $this->plugin->cssURL . 'wpxm-admin.css', array(), $this->plugin->version );

    // Since 1.0.0.b3 this style is loading because the styles are set onfly.
    wp_enqueue_style( 'wpxm-admin-enhanced', $this->plugin->cssURL . 'wpxm-admin-enhanced.css', array(), $this->plugin->version );

    // Prepare tour
    WPXtremeWelcomeTourModalDialog::init();
  }

  /**
   * Fires when scripts are printed for all admin pages.
   *
   * @since WP 2.1.0
   */
  public function admin_print_scripts()
  {
    // Loading Modal (modal is ever loaded for issue report and store)
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::MODAL,  WPDKUIComponents::ALERT );

    // All scripts
    wp_enqueue_script( 'wpxm-admin', $this->plugin->javascriptURL . 'wpxm-admin.js', array( 'jquery' ), $this->plugin->version, true );

    // Localize Javascript
    $strings = array(
      'remove_all'                       => __( 'Remove All', WPXTREME_TEXTDOMAIN ),
      'warning_confirm_remove_revisions' => __( "WARNING!\n\nAre you sure do you want remove all your revision posts?", WPXTREME_TEXTDOMAIN ),
    );
    wp_localize_script( 'wpxm-admin', 'WPXtremeStrings', $strings );
  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * It's used when the admin plugins list table view page is loaded.
   *
   * @since WP 2.6.0
   *
   */
  public function admin_print_styles_plugins()
  {
    // Get all installed plugins
    $all_plugins = get_plugins();

    // Auto display the plugin icon in plugins list
    WPDKHTML::startCompress();
    ?>
    <style id="wpxm-plugins-list" type="text/css">
    <?php

    // Get only wpXtreme plugin
    foreach ( $all_plugins as $key => $plugin ) :

      // Use the URI to get wpXtreme plugins
      if ( false !== strpos( $plugin['PluginURI'], '/wpxtre.me' ) ) : $selector = sanitize_title( $plugin['Name'] ); ?>
  <?php echo 'tr#' . $selector ?> td.plugin-title strong
  {
    background-position : left center;
    background-repeat   : no-repeat;
    background-image    : url(<?php echo trailingslashit( WP_PLUGIN_URL ) . trailingslashit( dirname( $key ) ) ?>assets/css/images/logo-16x16.png);
    width               : auto;
    height              : 16px;
    float               : none;
    margin              : 0 0 6px;
    padding-left        : 20px;
  }

  <?php echo 'tr#' . $selector ?>.inactive td.plugin-title strong
  {
    opacity : 0.4;
    filter  : alpha(opacity=40);
  }

  <?php echo 'tr#' . $selector ?>.inactive td.plugin-title:hover strong
  {
    opacity : 1;
    filter  : alpha(opacity=100);
  }

  <?php
  endif;
  endforeach;

    ?>
    </style>
  <?php
    echo WPDKHTML::endCSSCompress();
  }

  // -------------------------------------------------------------------------------------------------------------------
  // WordPress Hooks
  // -------------------------------------------------------------------------------------------------------------------

  /**
 	 * Load all necessary admin bar items.
 	 *
 	 * This is the hook used to add, remove, or manipulate admin bar items.
 	 *
 	 * @since WP 3.1.0
 	 *
 	 * @param WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference
 	 */
  public function admin_bar_menu( $wp_admin_bar )
  {

    // About wpXtreme
    $args = array(
      'id'     => 'wpxm-admin-bar-menu-wp-logo-about',
      'parent' => 'wp-logo',
      'title'  => __( 'About wpXtreme', WPXTREME_TEXTDOMAIN ),
      'href'   => 'https://wpxtre.me',
      'meta'   => array(
        'title' => __( 'About wpXtreme', WPXTREME_TEXTDOMAIN ),
      )
    );
    $wp_admin_bar->add_node( $args );

    // Main menu
    $args = array(
      'id'    => 'wpxm-admin-bar-menu',
      'title' => __( 'wpXtreme', WPXTREME_TEXTDOMAIN ),
      'meta'  => array(
        'class' => 'wpxm-admin-bar-menu',
        'title' => __( 'About wpXtreme', WPXTREME_TEXTDOMAIN ),
      )
    );
    $wp_admin_bar->add_node( $args );

    // Community
    $args = array(
      'id'     => 'wpxm-admin-bar-menu-item-community',
      'parent' => 'wpxm-admin-bar-menu',
      'title'  => __( 'Community', WPXTREME_TEXTDOMAIN ),
      'href'   => 'https://wpxtre.me/forums',
      'meta'   => array(
        'class' => 'wpxm-admin-bar-menu-item-community',
        'title' => __( 'wpXtreme Community Forums', WPXTREME_TEXTDOMAIN ),
      )
    );
    $wp_admin_bar->add_node( $args );

    // Administrator logged in users
    if ( is_user_logged_in() && current_user_can( self::MENU_CAPABILITY ) ) {

      // Issue Report
      $args = array(
        'id'     => 'wpxm-admin-bar-menu-item-issue-report',
        'parent' => 'wpxm-admin-bar-menu',
        'title'  => __( 'Issue Report', WPXTREME_TEXTDOMAIN ),
        'href'   => '#issue-report',
        'meta'   => array(
          'class' => 'wpxm-admin-bar-menu-item-issue-report',
          'title' => __( 'Send a Issue Report to teh wpXtreme Developer team', WPXTREME_TEXTDOMAIN ),
        )
      );
      $wp_admin_bar->add_node( $args );

      // Debug?
      if ( ( defined( 'WPXTREME_DEBUG' ) && true === WPXTREME_DEBUG ) || WPXtremePreferences::init()->core->debug_console ) {

        // Debug item
        $args = array(
          'id'     => 'wpxm-admin-bar-menu-item-debug',
          'parent' => 'wpxm-admin-bar-menu',
          'title'  => __( 'Debug', WPXTREME_TEXTDOMAIN ),
          'href'   => add_query_arg( array( 'page' => 'wpxm_debug' ), admin_url( 'admin.php' ) ),
          'meta'   => array(
            'class' => 'wpxm-admin-bar-menu-item-debug',
            'title' => __( 'Open internal debug', WPXTREME_TEXTDOMAIN ),
          )
        );
        $wp_admin_bar->add_node( $args );
      }

    }

    // Separator
    $args = array(
      'id'     => 'wpxm-admin-bar-menu-separator',
      'parent' => 'wpxm-admin-bar-menu',
      'meta'   => array(
        'class' => 'wpxm-admin-bar-separator'
      )
    );
    $wp_admin_bar->add_node( $args );

    // Preferences
    $args = array(
      'id'     => 'wpxm-admin-bar-menu-item-preferences',
      'parent' => 'wpxm-admin-bar-menu',
      'title'  => __( 'Preferences', WPXTREME_TEXTDOMAIN ),
      'href'   => add_query_arg( array( 'page' => 'wpxtreme-preferences'), admin_url( 'options-general.php' ) ),
      'meta'   => array(
        'class' => 'wpxm-admin-bar-menu-item-preferences',
        'title' => __( 'wpXtreme Preferences', WPXTREME_TEXTDOMAIN ),
      )
    );
    $wp_admin_bar->add_node( $args );

    // Separator
    $args = array(
      'id'     => 'wpxm-admin-bar-menu-separator-2',
      'parent' => 'wpxm-admin-bar-menu',
      'meta'   => array(
        'class' => 'wpxm-admin-bar-separator'
      )
    );
    $wp_admin_bar->add_node( $args );

    // Version
    $args = array(
      'id'     => 'wpxm-admin-bar-menu-item-version',
      'parent' => 'wpxm-admin-bar-menu',
      'title'  => sprintf( 'wpXtreme v%s', WPXTREME_VERSION ),
      'meta'   => array(
        'class' => 'wpxm-admin-bar-menu-item-version',
        'title' => __( 'wpXtreme Version', WPXTREME_TEXTDOMAIN ),
      )
    );
    $wp_admin_bar->add_node( $args );
  }

  /**
   * Override and called when WordPress is ready to draw the menu in admin area
   */
  public function admin_menu()
  {
    // TODO Hack for wpXtreme icon
    //$icon_menu = $this->plugin->imagesURL . 'logo-16x16.png';

    // Add the main setting wpXtreme in WordPress settings
    $hook_suffix = add_options_page( __( 'wpXtreme Preferences' ), 'wpXtreme', self::MENU_CAPABILITY, 'wpxtreme-preferences', create_function( '', 'WPXtremePreferencesViewController::init()->display();' ) );

    // Add my custom hook for this page
    add_action( "admin_print_styles-{$hook_suffix}", create_function( '', 'WPXtremePreferencesViewController::init()->admin_print_styles();' ) );
    add_action( "admin_head-{$hook_suffix}", create_function( '', 'WPXtremePreferencesViewController::init()->admin_head();' ) );

    // Add a custom hidden (without menu) page in the admin backend area and return the page's hook_suffix.
    wpdk_add_page( 'wpxm_debug', 'Debug', self::MENU_CAPABILITY, create_function( '', 'WPXtremeDebugView::init()->display();' ) );

  }

  /**
   * Display something before (into) the standard WordPress admin footer.
   *
   * @since 1.0.0.b3
   */
  public function in_admin_footer() {
    $issue_reposrt_view = new WPXtremeIssueReportView;
    ?>
  <div id="wpx-admin-footer">
    <p class="alignleft wpx-logo">
      <a href="https://wpxtre.me/">wpXtreme</a> v<?php echo WPXTREME_VERSION ?> / <a href="http://www.wpdk.io/">WPDK</a> v<?php echo WPDK_VERSION ?>
      <?php
      /**
     	 * Filter the wpXtreme admin footer on the left.
     	 *
     	 * Returning a string with a content for wpXtreme footer left.
     	 *
     	 * @param string $content Content of admin footer. Default ''.
     	 */
        echo apply_filters( 'wpxm_admin_footer_left', '' )
      ?>
    </p>

    <p class="alignright">
      <?php
      /**
     	 * Filter the wpXtreme admin footer on the right.
     	 *
     	 * Returning a string with a content for wpXtreme footer right.
     	 *
     	 * @param string $content Content of admin footer. Default ''.
     	 */
      echo apply_filters( 'wpxm_admin_footer_right', '' )
      ?>
      <a href="https://wpxtre.me/forums">Community</a> <?php if( current_user_can( self::MENU_CAPABILITY ) ) : ?>| <a href="#issue-report">Issue Report</a>
      <?php echo $issue_reposrt_view->footer() ?>
      <?php endif; ?>
    </p>

    <div class="clear"></div>
  </div>
  <?php
  }

  /**
   * Display something after the standard WordPress admin footer.
   *
   * @since 1.0.0.b3
   */
  public function admin_footer()
  {
    $issue_reposrt_view = new WPXtremeIssueReportView;
    echo $issue_reposrt_view->modal();

    // Open tour
    WPXtremeWelcomeTourModalDialog::init()->open();
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Plugin page Table List integration
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return an HTML markup link to add in Plugin column in Plugin List.
   *
   * @param array $links
   *
   * @return array
   */
  public function plugin_action_links( $links )
  {
    // @since 1.4.0 - Message for disable here to avoid localize script.
    $message =  __( "WARNING!\n\nIf you disable WPXtreme plugin, you'll no longer be able to use any of the connected plugins!\n\nDo you really want to continue?", WPXTREME_TEXTDOMAIN );

    // Option url
    $url = admin_url( 'options-general.php?page=wpxtreme-preferences' );

    WPDKHTML::startCompress() ?>

    <a id="wpxtreme-preferences-link"
       data-message="<?php echo $message ?>"
       href="<?php echo $url ?>">
      <?php _e( 'Preferences', WPXTREME_TEXTDOMAIN ) ?>
    </a>
    <script type="text/javascript">
      jQuery( document ).on( 'click', 'tr#wpxtreme span.deactivate a', false, function ( e )
      {
        return confirm( jQuery( 'a#wpxtreme-preferences-link' ).data( 'message' ) );
      } );
    </script>
   <?php

    $result = WPDKHTML::endCompress();

    array_unshift( $links, $result );

    return $links;
  }

  /**
   * Return an HTML markup link to add in Plugin column in Plugin List.
   *
   * @param array  $links Links array
   * @param string $file  Plugin path/main_file.php
   *
   * @return array
   */
  public function plugin_row_meta( $links, $file )
  {
    // am I?
    if ( $file == $this->plugin->pluginBasename ) {
      $links[] = '<span class="wpxm-row-meta">' . __( 'For more info and plugins visit', WPXTREME_TEXTDOMAIN ) . ' <a href="https://wpxtre.me">wpXtre.me</a></span>';
    }
    return $links;
  }
}