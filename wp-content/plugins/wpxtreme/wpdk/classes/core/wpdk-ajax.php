<?php

if ( wpdk_is_ajax() ) {

  /**
   * Ajax class for extends an Ajax parent class.
   * You will use this class to extends a your own Ajax gateway class.
   *
   *     class YourClass extends WPDKAjax {
   *       public function actions()
   *       {
   *         return array();
   *       }
   *     }
   *
   * In this way you can access to `registerActions` method
   *
   * @class              WPDKAjax
   * @author             =undo= <info@wpxtre.me>
   * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
   * @date               2013-11-15
   * @version            1.0.3
   * @since              0.7.5
   */
  class WPDKAjax {

    /**
     * Create an instance of WPDKAjax class
     *
     * @brief Construct
     *
     * @return WPDKAjax
     */
    public function __construct()
    {
      $this->registerActions();
    }

    /**
     * Register the allow ajax method in WordPress environment
     *
     * @brief Register the ajax methods
     *
     */
    public function registerActions()
    {
      $actions = $this->actions();
      foreach ( $actions as $method => $nopriv ) {
        add_action( 'wp_ajax_' . $method, array( $this, $method ) );
        if ( $nopriv ) {
          add_action( 'wp_ajax_nopriv_' . $method, array( $this, $method ) );
        }
      }
    }

    /**
     * Useful static method to add an action ajax hook
     *
     * @brief Add an Ajax hook
     * @since 1.3.0
     *
     * @param string   $method   Method name, eg: wpxkk_action_replace
     * @param callback $callable A callable function/method hook
     * @param bool     $nopriv   Set to TRUE for enable no privilege
     */
    public static function add( $method, $callable, $nopriv = false )
    {
      // Action for admin (logged-in user) only
      add_action( 'wp_ajax_' . $method, $callable );

      // Action for frontend (no logged-in user)
      if ( $nopriv ) {
        add_action( 'wp_ajax_nopriv_' . $method, $callable );
      }
    }

    /**
     * Return the array list with allowed method. This is a Key value pairs array with value for not signin user ajax
     * method allowed.
     *
     * @brief Ajax actions list
     *
     * @return array
     */
    protected function actions()
    {
      // To override
      return array();
    }

  } // class WPDKAjax


  /**
   * A WPDK (utility) Ajax Response class model
   *
   * @class           WPDKAjaxResponse
   * @author          =undo= <info@wpxtre.me>
   * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
   * @date            2014-10-03
   * @version         1.0.6
   *
   * @since           1.4.0
   *
   * @history         1.0.5 - Improves json result response.
   * @history         1.0.6 - Extends data property and minor fixes.
   */
  class WPDKAjaxResponse extends WPDKObject {

    /**
     * Override version
     *
     * @brief Version
     *
     * @var string $__version
     */
    public $__version = '1.0.6';

    /**
     * User define error code or string
     *
     * @brief Error
     *
     * @var string $error
     */
    public $error = '';

    /**
     * Usually an alert message feedback
     *
     * @brief Message
     *
     * @var string $message
     */
    public $message = '';

    /**
     * Use this property to set any your own data to return.
     * This property can be a string or - usually - an array.
     *
     * @brief Back data
     *
     * @var mixed $data
     */
    public $data = '';

    /**
     * Create an instance of WPDKAjaxResponse class
     *
     * @brief Construct
     *
     * @return WPDKAjaxResponse
     */
    public function __construct() { }

    /**
     * Send a JSON response back to an Ajax request.
     *
     * @brief Send a JSON
     */
    public function json()
    {
      @header( 'Cache-Control: no-cache, must-revalidate' );
      @header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
      @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );

      wp_die( json_encode( $this ) );
    }

  }
}