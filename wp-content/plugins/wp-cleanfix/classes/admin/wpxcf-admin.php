<?php

/**
 * Class for Manage Admin (back-end)
 *
 * @class              WPXCleanFixAdmin
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2013-07-01
 * @version            1.1.0
 */
final class WPXCleanFixAdmin extends WPDKWordPressAdmin {

  // The minumun capability to manage the CleanFix menu
  const MENU_CAPABILITY = 'manage_options';

  // Menu position in admin backend
  const MENU_POSITION = null;

  /**
   * Array of menu from WPDKMenu::renderByArray();
   *
   * @var array $menus
   */
  public $menus;

  /**
   * Return a singleton instance of WPXCleanFixAdmin class
   *
   * @return WPXCleanFixAdmin
   */
  public static function init()
  {
    static $instance = null;
    if ( is_null ( $instance ) ) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * Create an instance of WPXCleanFix class
   */
  public function __construct()
  {
    /**
     * @var WPXCleanFix $plugin
     */
    $plugin = $GLOBALS[ 'WPXCleanFix' ];
    parent::__construct ( $plugin );

    // Plugin List
    add_action ( 'plugin_action_links_' . $this->plugin->pluginBasename, array(
      $this,
      'plugin_action_links'
    ), 10, 4 );

    // Meta box management
    add_action ( 'admin_post_save_wpx_cleanfix', array( $this, 'admin_post_save_wpx_cleanfix' ) );

    // This line below is not used because ClenFix has 1 column mode ever
    //add_filter( 'screen_layout_columns', array( $this, 'on_screen_layout_columns' ), 10, 2 );
  }

  /**
   * Meta box
   */
  public function admin_post_save_wpx_cleanfix()
  {
    // user permission check
    if ( !current_user_can ( 'manage_options' ) ) {
      wp_die ( __ ( 'Cheatin&#8217; uh?' ) );
    }

    // cross check the given referer
    check_admin_referer ( 'wp-cleanfix-general' );

    // process here your on $_POST validation and / or option saving

    // lets redirect the post request into get request (you may add additional params at the url, if you need to show save results
    wp_safe_redirect ( $_POST[ '_wp_http_referer' ] );
    exit();
  }

  /**
   * Meta box screen layout
   */
  public function on_screen_layout_columns( $columns, $screen )
  {
    if ( isset( $this->menus[ 'wpxcf_menu_main' ] ) ) {

      /**
       * @var WPDKMenu $menu
       */
      $menu = $this->menus[ 'wpxcf_menu_main' ];
      if ( $screen == $menu->hookName ) {
        $columns[ $menu->hookName ] = 1;
      }
    }

    return $columns;
  }

  /**
   * Aggiunge un link nella riga che identifica questo Plugin nella schermata con l'elenco dei Plugin nel backend di
   * WordPrsss.
   *
   * @param array $links
   *
   * @return array
   */
  public function plugin_action_links( $links )
  {
    $url    = WPDKMenu::url ( 'WPXCleanFixMainViewController' );
    $result = '<a href="' . $url . '">' . __ ( 'Clean & Fix', WPXCLEANFIX_TEXTDOMAIN ) . '</a>';
    array_unshift ( $links, $result );

    return $links;
  }

  /**
   * Admin menu
   */
  public function admin_menu()
  {
    // Icon
    $icon_menu = $this->plugin->imagesURL . 'logo-16x16.png';

    // Numero di elementi da sistemare
    $count = WPXCleanFixModulesController::init ()->issues ();

    // Creo un badge da mettere nel menu store in caso ci siano dei plugin da aggiornare
    $badge = WPDKUI::badge ( $count, 'wpxcf-badge' );

    $menus = array(
      'wpxcf_menu_main' => array(
        'menuTitle'  => __ ( 'CleanFix', WPXCLEANFIX_TEXTDOMAIN ) . $badge,
        'pageTitle'  => __ ( 'CleanFix', WPXCLEANFIX_TEXTDOMAIN ),
        'capability' => self::MENU_CAPABILITY,
        'icon'       => $icon_menu,
        'subMenus'   => array(

          array(
            'menuTitle'      => __ ( 'Clean & Fix', WPXCLEANFIX_TEXTDOMAIN ) . $badge,
            'pageTitle'      => __ ( 'Clean & Fix', WPXCLEANFIX_TEXTDOMAIN ),
            'capability'     => self::MENU_CAPABILITY,
            'viewController' => 'WPXCleanFixMainViewController'
          ),
          WPDKSubMenuDivider::DIVIDER,
          array(
            'menuTitle'      => __ ( 'Preferences', WPXCLEANFIX_TEXTDOMAIN ),
            'capability'     => self::MENU_CAPABILITY,
            'viewController' => 'WPXCleanFixPreferencesViewController',
          ),
        )
      )
    );

    $this->menus = WPXMenu::init ( $menus, $this->plugin );
  }
}