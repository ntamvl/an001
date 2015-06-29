<?php
/// @cond private

/*
 * [DRAFT]
 *
 * THIS DOCUMENTATION IS A DRAFT. YOU CAN READ IT AND MAKE SOME EXPERIMENT BUT DO NOT USE ANY CLASS BELOW IN YOUR
 * PROJECT. ALL CLASSES AND RELATIVE METHODS BELOW ARE SUBJECT TO CHANGE.
 *
 */

/**
 * wpXtreme standard menu. You can use this utility class to draw a standard About wpXtreme menu.
 *
 * @class           WPXMenu
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-10-21
 * @version         1.0.0
 * @since           1.1.1
 *
 */
final class WPXMenu extends WPDKMenu {

  // This is the minumun capability required to display admin menu item
  const MENU_CAPABILITY = 'manage_options';

  /**
   * Menus
   *
   * @var array $menus
   */
  private $menus;

  /**
   * An instance of WPXPlugin class
   *
   * @var WPXPlugin $plugin
   */
  private $plugin;

  /**
   * Return an instance of WPXMenu class.
   *
   * @param array     $menus  A key value pairs array with the list of menu.
   * @param WPXPlugin $plugin An instance of WPXPlugin.
   *
   * @return WPXMenu
   */
  public static function init( $menus, $plugin )
  {
    $instance = new WPXMenu( $menus, $plugin );
    $instance->display();

    return $instance;
  }

  /**
   * Create an instance of WPXMenu class
   *
   * @param array     $menus  A key value pairs array with the list of menu
   * @param WPXPlugin $plugin An instance of WPXPlugin
   */
  public function __construct( &$menus, $plugin )
  {
    $this->menus  = $menus;
    $this->plugin = $plugin;
  }

  /**
   * Display the menu
   *
   * @return array
   */
  public function display()
  {
    $submenus = array(
      array( WPDKSubMenuDivider::DIVIDER => sprintf( '<span class="wpxm-menu-current-version">v%s</span>', $this->plugin->version ) ),
    );

    WPDKMenu::addSubMenusAt( $this->menus, $submenus, -1 );

    $key = key( $this->menus );
    if( isset( $this->menus[ $key ][ 'subMenus' ] ) ) {
      WPDKMenu::renderByArray( $this->menus );
    }
    else {
      WPDKSubMenu::renderByArray( $this->menus );
    }

  }

  /**
   * Return the standard url extensions submenu item.
   *
   * @since 1.2.4
   *
   * @note  This method is used by Bannerize
   *
   * @param string $id The submenu id
   *
   * @return string
   */
  public static function url_extensions( $id )
  {
    global $submenu;

    $item = current( array_slice( $submenu[ $id ], -3, 1 ) );
    $url  = add_query_arg( array( 'page' => $item[ 2 ] ), self_admin_url( $id ) );

    return $url;
  }

}


/// @endcond