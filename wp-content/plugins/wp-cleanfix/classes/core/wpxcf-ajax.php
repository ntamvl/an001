<?php

if ( wpdk_is_ajax() ) {

  /**
   * Ajax gateway. Register all ajax mathods.
   * This class contains all Ajax method callled by back end front end.
   *
   * @class              WPXCleanFixAjax
   * @author             =undo= <info@wpxtre.me>
   * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
   * @date               2014-07-18
   * @version            1.0.3
   */
  final class WPXCleanFixAjax extends WPDKAjax {

    /**
     * Create or return a singleton instance of WPXCleanFixAjax
     *
     * @brief Create or return a singleton instance of WPXCleanFixAjax
     *
     * @return WPXCleanFixAjax
     */
    public static function getInstance()
    {
      static $instance = null;
      if ( is_null( $instance ) ) {
        $instance = new self();
      }
      return $instance;
    }

    /**
     * Alias of getInstance();
     *
     * @return WPXCleanFixAjax
     */
    public static function init()
    {
      if ( isset( $_POST['action'] ) && !empty( $_POST['action'] ) ) {

        /**
         * Fires when an Ajax action.
         *
         * The dynamic portion of the hook name, $action, refers to the 'action' post parameters.
         */
        do_action( 'wpxcf_ajax-' . $_POST['action'] );
      }

      return self::getInstance();
    }

    /**
     * Return the array with the list of ajax allowed methods
     *
     * @breief Allow ajax action
     *
     * @return array
     */
    protected function actions()
    {
      $actionsMethods = array(
        'wpxcf_action_update_badge' => false,
        'wpxcf_action'              => false,
      );
      return $actionsMethods;
    }

    /**
     * Return a standanrd response
     *
     * @param WP_Error|WPXCleanFixModuleResponse $result
     *
     * @return array
     */
    private function response( $result )
    {
      if ( is_wp_error( $result ) ) {
        $error  = sprintf( __( 'An error occourred! Code: %s Description: %s', WPXCLEANFIX_TEXTDOMAIN ), $result->get_error_code(), $result->get_error_message() );
        $result = array(
          'error'            => $result->get_error_code(),
          'errorDescription' => $error,
          'status'           => '',
          'content'          => '',
          'actions'          => '',
        );
      }
      else {
        $result = array(
          'error'            => 0,
          'errorDescription' => '',
          'warning'          => ( $result->status == WPXCleanFixModuleResponseStatus::OK ) ? 0 : 1,
          'status'           => sprintf( '<span class="wpdk-has-tooltip wpxcf-status-%s" title="%s"></span>', $result->status, $result->description ),
          'content'          => !empty( $result->detail ) ? $result->detail->html() : '',
          'actions'          => !empty( $result->cleanFix ) ? $result->cleanFix->html() : ''
        );
      }

      wp_die( json_encode( $result ) );
    }


    /**
     * Perform a refresh or fix/clean action.
     */
    public function wpxcf_action()
    {
      // Security check
      check_ajax_referer();

      // Get method, default refresh
      $method = isset( $_POST['method'] ) ? $_POST['method'] : 'refresh';

      // Error method
      if ( ! in_array( $method, array( 'refresh', 'fix' ) ) ) {
        $this->response( new WP_Error( 'guru', __( 'Method not allowed!', WPXCLEANFIX_TEXTDOMAIN ) ) );
      }

      // Get module
      $module_key = isset( $_POST['module'] ) ? sanitize_title( $_POST['module'] ) : '';

      // Error module
      if ( empty( $module_key ) ) {
        $this->response( new WP_Error( 'guru', __( 'Module Missing!', WPXCLEANFIX_TEXTDOMAIN ) ) );
      }

      // Get slot
      $slot_key = isset( $_POST['slot'] ) ? sanitize_title( $_POST['slot'] ) : '';

      // Error slot
      if ( empty( $slot_key ) ) {
        $this->response( new WP_Error( 'guru', __( 'Slot Missing!', WPXCLEANFIX_TEXTDOMAIN ) ) );
      }

      // Get module list
      $modules = WPXCleanFixModulesController::init()->modules;

      // Error
      if ( ! isset( $modules[ $module_key ] ) ) {
        $this->response( new WP_Error( 'guru', __( 'Module not allowed!', WPXCLEANFIX_TEXTDOMAIN ) ) );
      }

      /**
       * @var WPXCleanFixModule $module
       */
      $module = $modules[ $module_key ];

      /**
       * @var WPXCleanFixSlot $slot
       */
      $slot = $module->initSlot( $slot_key );

      // Error
      if ( empty( $slot ) ) {
        $this->response( new WP_Error( 'guru', __( 'Slot not allowed!', WPXCLEANFIX_TEXTDOMAIN ) ) );
      }

      switch ( $method ) {
        case 'refresh':
          $this->response( $slot->check() );
          break;
        case 'fix':
          $this->response( $slot->cleanFix() );
          break;
      }

      die();
    }

    /**
     * Updated the transient badge value.
     *
     * @since 1.2.90
     *
     * @return string jSON output
     */
    public function wpxcf_action_update_badge()
    {
      // Get badge value
      $warnings = isset( $_POST['warnings'] ) ? $_POST['warnings'] : false;

      // If false delete
      if( empty( $warnings ) ) {
        delete_site_transient( WPXCleanFixModulesController::BADGE_TRANSIENT );
        die();
      }

      set_site_transient( WPXCleanFixModulesController::BADGE_TRANSIENT, $warnings, ( 12 * HOUR_IN_SECONDS ) );
      die();
    }

    /**
     * Used to update the badge count
     *
     * @todo  Renamed from `wpxcf_action_update_badge` and not used from 1.2.90
     *
     * @return string jSON output
     */
    public function wpxcf_action_get_badge()
    {
      $count = WPXCleanFixModulesController::init()->issues();

      if ( empty( $count ) ) {
        $result = array(
          'error' => 0,
          'count' => $count
        );
      }
      else {
        $result = array(
          'error' => 0,
          'count' => $count,
          'badge' => WPDKUI::badge( $count, 'wpxcf-badge' )
        );
      }

      wp_die( json_encode( $result ) );
    }

  }
}