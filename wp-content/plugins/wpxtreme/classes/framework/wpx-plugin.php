<?php
/**
 * This class is similar to WPDKWordPressPlugin. It is used to extends a main theme in wpXtreme environment.
 *
 * @class           WPXPlugin
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-11-07
 * @version         1.0.3
 *
 */
class WPXPlugin extends WPDKWordPressPlugin {

  // Standard defines constants file
  const DEFINES = 'defines.php';

  /**
   * Create an instance of WPXPlugin class
   *
   * @param string $file The main file of this plugin. Usually __FILE__ (main.php)
   *
   * @return WPXPlugin
   */
  public function __construct( $file = null )
  {
    parent::__construct( $file );

    // Body class
    add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

    // Loading constants defines
    $defines = trailingslashit( dirname( $file ) ) . self::DEFINES;
    if ( file_exists( $defines ) ) {
      require_once( $defines );
    }

    // Register autoload classes
    $includes = $this->classesAutoload();
    if ( !empty( $includes ) ) {
      $this->registerAutoloadClass( $includes );
    }
  }

  /**
   * Return the list of autoload classes
   *
   * @since 1.1.10
   *
   * @return array
   */
  public function classesAutoload()
  {
    // You can override in your subclass
    return array();
  }

  /**
   * BODY class
   *
   * @param string $classes Body classes
   *
   * @return string
   */
  public function admin_body_class( $classes )
  {
    $result = get_plugin_data( $this->file );
    $class  = '';
    if ( !empty( $result ) ) {
      $class = sprintf( ' %s-%s', sanitize_title( $result['Name'] ), sanitize_title( $result['Version'] ) );
    }

    return $classes . $class;
  }

  /**
   * Create and return a singleton instance of WPXPlugin class
   *
   * @param string $file The main file of this plugin. Usually __FILE__ (main.php)
   *
   * @return WPXtreme
   */
  public static function boot( $file = null )
  {
    die( __METHOD__ . ' must be override in your subclass' );
  }

}