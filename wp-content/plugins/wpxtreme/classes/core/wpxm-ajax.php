<?php

if ( wpdk_is_ajax() ) {

  /**
   * Ajax gateway engine
   *
   * @class              WPXtremeAjax
   * @author             =undo= <info@wpxtre.me>
   * @copyright          Copyright (C) 2012-2015 wpXtreme Inc. All Rights Reserved.
   * @date               2015-01-07
   * @version            1.0.0
   *
   */
  final class WPXtremeAjax extends WPDKAjax {

    /**
     * Instance of WPXtremeAPI class
     *
     * @var WPXtremeAPI $api
     */
    private $api;

    /**
     * Create or return a singleton instance of WPXtremeAjax
     *
     * @return WPXtremeAjax
     */
    public static function getInstance()
    {
      static $instance = null;
      if ( is_null( $instance ) ) {
        $instance      = new WPXtremeAjax();
        $instance->api = new WPXtremeAPI();
      }

      return $instance;
    }

    /**
     * Alias of getInstance();
     *
     * @return WPXtremeAjax
     */
    public static function init()
    {
      return self::getInstance();
    }

    /**
     * Return the array with the list of ajax allowed methods
     *
     * @return array
     */
    protected function actions()
    {
      $actions = array(
        'wpxtreme_action_user_set_status'               => false,
        'wpxtreme_action_post_set_publish'              => false,
        'wpxtreme_action_sorting_post_page'             => true,
        'wpxtreme_action_set_issue_report'              => false,
        'wpxtreme_action_send_issue_report'             => false,
        'wpxtreme_action_update_preferences_appearance' => false,
        'wpxtreme_action_update_preferences_list_table' => false,
      );

      return $actions;
    }

    // -----------------------------------------------------------------------------------------------------------------
    // Actions methods
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Update the user status (by switch ui button) and return the new update table row.
     *
     * @return string
     */
    public function wpxtreme_action_user_set_status()
    {
      // Prepare response
      $response = new WPDKAjaxResponse();

      // Get user id
      $user_id = absint( $_POST[ 'user_id' ] );

      // Get switch ui button state
      $state = wpdk_is_bool( $_POST[ 'state' ] );

      if( empty( $state ) ) {
        update_user_meta( $user_id, WPDKUserMeta::STATUS, WPDKUserStatus::DISABLED );
      }
      else {
        delete_user_meta( $user_id, WPDKUserMeta::STATUS );
      }

      // Init enhancer
      WPXtremeEnhancerUser::init();

      // Get the post list table
      $wp_list_table = _get_list_table( 'WP_Users_List_Table', array( 'screen' => 'users' ) );

      // Row
      WPDKHTML::startCompress();

      $wp_list_table->items = array( $user_id => get_user_by( 'id', $user_id ) );
      $wp_list_table->display_rows();

      $response->data['row'] = WPDKHTML::endHTMLCompress();

      $response->json();

    }

    /**
     * Update the post status (by switch ui button) and return the new update table row.
     *
     * @return string
     */
    public function wpxtreme_action_post_set_publish()
    {
      // Prepare response
      $response = new WPDKAjaxResponse();

      $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : false;

      // Stability
      if ( empty( $post_id ) ) {
        $response->json();
      }

      $post_status = esc_attr( $_POST['status'] );
      $screen      = isset( $_POST['screen'] ) ? $_POST['screen'] : '';

      if ( empty( $screen ) ) {
        $response->error = '#internal: No Screen right set!';
        $response->json();
      }

      $post = array(
        'ID'          => $post_id,
        'post_status' => $post_status
      );

      wp_update_post( $post );

      // Prepare response
      $response->data = array();

      // Init enhancer
      WPXtremeEnhancerPost::init();

      // Get the post list table
      $wp_list_table = _get_list_table( 'WP_Posts_List_Table', array( 'screen' => $screen ) );

      // @todo - Views: for now I disable this feature because do not work properly
      //      WPDKHTML::startCompress();
      //      $wp_list_table->views();
      //      $response->data['views'] = WPDKHTML::endHTMLCompress();

      // Row
      WPDKHTML::startCompress();
      $level        = 0;
      $request_post = array( get_post( $post_id ) );
      $parent       = $request_post[0]->post_parent;

      while ( $parent > 0 ) {
        $parent_post = get_post( $parent );
        $parent      = $parent_post->post_parent;
        $level++;
      }
      $wp_list_table->display_rows( array( get_post( $post_id ) ), $level );

      $response->data['row'] = WPDKHTML::endHTMLCompress();

      $response->json();
    }

    /**
     * Updated `menu_order` field in post table.
     *
     * @internal param $_POST['sorted'] List sequence of sorted items
     * @internal param $_POST['paged'] Pagination value
     * @internal param $_POST['per_page'] Number of items per page
     *
     * @return string
     *
     */
    public function wpxtreme_action_sorting_post_page()
    {
      /**
       * @var wpdb $wpdb
       */
      global $wpdb;

      $response = new WPDKAjaxResponse();

      $sorted   = wp_parse_args( $_POST['sorted'] );
      $paged    = absint( esc_attr( $_POST['paged'] ) );
      $per_page = absint( esc_attr( $_POST['per_page'] ) );

      if ( is_array( $sorted['post'] ) ) {
        $offset = ( $paged - 1 ) * $per_page;
        foreach ( $sorted['post'] as $key => $value ) {
          $menu_order = $key + $offset;
          $sql        = sprintf( 'UPDATE %s SET menu_order = %s WHERE ID = %s', $wpdb->posts, $menu_order, absint( $value ) );
          $wpdb->query( $sql );
        }
      }

      $response->json();
    }

    /**
     * Update the preferences appearance
     *
     * @since 1.0.0.b3
     *
     */
    public function wpxtreme_action_update_preferences_appearance()
    {
      // Prepare response
      $response = new WPDKAjaxResponse();

      // Get data
      $data = $_POST['data'];

      // Stability
      if ( is_array( $data ) && ! empty( $data ) ) {

        // Sent a set of preferences
        foreach ( $data as $property => $value ) {
          WPXtremePreferences::init()->appearance->$property = $value;
        }
        WPXtremePreferences::init()->update();
      }

      // Something wrong
      else {
        $response->error = __( 'Software Failure! Guru Meditation: 0x8000C000', WPXTREME_TEXTDOMAIN );

      }

      $response->json();

    }

    /**
     * Update the preferences list table
     *
     * @since 1.0.0.b4
     *
     */
    public function wpxtreme_action_update_preferences_list_table()
    {

      // Prepare response
      $response = new WPDKAjaxResponse();

      // Get data
      $data = $_POST['data'];

      // Stability
      if ( is_array( $data ) && ! empty( $data ) ) {

        // Sent a set of preferences
        foreach ( $data as $property => $value ) {
          WPXtremePreferences::init()->list_table->$property = $value;
        }
        WPXtremePreferences::init()->update();
      }

      // Something wrong
      else {
        $response['message'] = __( 'Software Failure! Guru Meditation: 0x8000C001', WPXTREME_TEXTDOMAIN );

      }

      $response->json();

    }

    // -----------------------------------------------------------------------------------------------------------------
    // Issue Report
    // -----------------------------------------------------------------------------------------------------------------

    /**
     * Set a new state mode for issue report
     *
     * @since 1.0.0.b3
     */
    public function wpxtreme_action_set_issue_report()
    {

      // Prepare the response
      $response = array();

      if ( isset( $_POST['mode'] ) ) {
        $mode = absint( $_POST['mode'] );
        if ( WPXtremeIssueReportState::DISABLED == $mode || WPXtremeIssueReportState::ENABLED == $mode ||
             WPXtremeIssueReportState::READY_TO_SEND == $mode
        ) {
          WPXtremeIssueReportState::init()->state = $mode;
          WPXtremeIssueReportState::init()->update();

          //-----------------------------------------------------------------------
          // If I'm disabling issue report mode, delete previous PHP log file
          //-----------------------------------------------------------------------

          if ( WPXtremeIssueReportState::DISABLED == $mode ) {
            $cIssueReport = WPXtremeIssueReport::init();
            $cIssueReport->deleteLogFiles();
          }

          /* HTML markup for update modal and footer without reload entire document page. */
          $issue_reposrt_view  = new WPXtremeIssueReportView;
          $response['content'] = $issue_reposrt_view->modal();
          $response['footer']  = $issue_reposrt_view->footer();

        }
        else {
          $response['message'] = __( 'Error: wrong parameter', WPXTREME_TEXTDOMAIN );
        }
      }
      else {
        $response['message'] = __( 'Error: no parameter', WPXTREME_TEXTDOMAIN );
      }

      wp_die( json_encode( $response ) );
    }

    /**
     * Send the issue report to Developer Center Endpoint
     *
     * @since 1.0.0.b3
     */
    public function wpxtreme_action_send_issue_report()
    {
      // Prepare the response
      $response = array();

      // Some sanitize and check on POST params
      $name = esc_attr( stripslashes( $_POST['name'] ) );

      // Stability
      if ( empty( $name ) ) {
        $response['message'] = __( 'Warning: can\'t send the report without your name!', WPXTREME_TEXTDOMAIN );
        wp_die( json_encode( $response ) );
      }

      // Sanitize email
      $email = sanitize_email( $_POST['email'] );

      // Stability
      if ( empty( $email ) ) {
        $response['message'] = __( 'Warning: your email address is invalid. Can\'t send the issue report.', WPXTREME_TEXTDOMAIN );
        wp_die( json_encode( $response ) );
      }

      // Sanitize title
      $title = esc_attr( stripslashes( $_POST['title'] ) );

      // Stability
      if ( empty( $title ) ) {
        $response['message'] = __( 'Warning: can\'t send your report without a title for the Issue!', WPXTREME_TEXTDOMAIN );
        wp_die( json_encode( $response ) );
      }

      // Get the report
      $report = stripslashes( $_POST['report'] );

      // Stability
      if ( empty( $report ) ) {
        $response['message'] = __( 'Warning: the report seems to be empty! Please, repeat the process.', WPXTREME_TEXTDOMAIN );
        wp_die( json_encode( $response ) );
      }

      // Description is optional
      $description = esc_attr( stripslashes( substr( $_POST['description'], 0, WPXtremeIssueReport::MAX_LENGTH_DESCRIPTION ) ) );

      $args = array(
        'issue_report[name]'        => $name,
        'issue_report[email]'       => $email,
        'issue_report[title]'       => $title,
        'issue_report[description]' => $description,
        'issue_report[report]'      => $report
      );

      $params = array(
        'method'      => WPDKHTTPVerbs::POST,
        'timeout'     => WPXtremeIssueReport::RESPONSE_TIMEOUT,
        'redirection' => 5,
        'httpversion' => '1.0',
        'user-agent'  => WPXtremeIssueReport::USER_AGENT,
        'blocking'    => true,
        'headers'     => array(),
        'cookies'     => array(),
        'body'        => $args,
        'compress'    => false,
        'decompress'  => true,
        'sslverify'   => true,
      );

      // Physically send report to Developer Center

      $cIssueReport = WPXtremeIssueReport::init();

      $request = wp_remote_request( $cIssueReport->restServerURL, $params );

      if ( is_array( $request ) ) {

        // Get response code
        $iResponseCode = wp_remote_retrieve_response_code( $request );

        switch ( $iResponseCode ) {

          // SUCCESS
          case WPXtremeIssueReport::RESPONSE_SUCCESS:
            $response['send_result'] =
              __( 'Thank you! Your Issue Report has been successfully sent to wpXtreme Team!', WPXTREME_TEXTDOMAIN ) .
              PHP_EOL . PHP_EOL . __( 'These are the main data sent:', WPXTREME_TEXTDOMAIN ) . PHP_EOL . PHP_EOL .
              sprintf( "%s: %s", __( 'Name', WPXTREME_TEXTDOMAIN ), $name ) . PHP_EOL .
              sprintf( "%s: %s", __( 'Email address', WPXTREME_TEXTDOMAIN ), $email ) . PHP_EOL .
              sprintf( "%s: %s", __( 'Issue Title', WPXTREME_TEXTDOMAIN ), $title ) . PHP_EOL .
              sprintf( "%s: %s", __( 'Issue Description', WPXTREME_TEXTDOMAIN ), $description );

            //-----------------------------------------------------------------------
            // If the report has been correctly sent, reset issue environment status
            //-----------------------------------------------------------------------

            // Switch status to 'disabled'
            WPXtremeIssueReportState::init()->disable();

            // Clear all log files
            $cIssueReport->deleteLogFiles();

            /* HTML markup for update modal and footer without reload entire document page. */
            $issue_reposrt_view  = new WPXtremeIssueReportView;
            $response['content'] = $issue_reposrt_view->modal();
            $response['footer']  = $issue_reposrt_view->footer();

            break;

          // UNPROCESSABLE
          case WPXtremeIssueReport::RESPONSE_UNPROCESSABLE_ENTITY:

            $cErrors = json_decode( $request['body'] );
            $sError  = '';

            foreach ( $cErrors as $cError ) {
              foreach ( $cError as $sKey => $aValue ) {
                $sError .= $sKey . ' ' . $aValue[0] . PHP_EOL;
              }
            }

            $response['message'] = __( 'Warning: Can not send the report to server!', WPXTREME_TEXTDOMAIN ) . PHP_EOL .
                                   __( 'Unprocessable request:', WPXTREME_TEXTDOMAIN ) . PHP_EOL . $sError;

            break;

          // DEFAULT
          default:
            $response['message'] = __( 'Warning: Can\'t send the report to server!', WPXTREME_TEXTDOMAIN ) . PHP_EOL .
                                   sprintf( "%s: %d", __( 'Status code', WPXTREME_TEXTDOMAIN ), $iResponseCode );

        } // end switch

      }
      // If !is_array
      else {

        if ( is_wp_error( $request ) ) {
          $response['message'] = __( 'Warning: Can\'t send the report to server! The Server response is malformat.', WPXTREME_TEXTDOMAIN );
          $response['message'] .= PHP_EOL;
          $aErrors = $request->get_error_messages();
          foreach ( $aErrors as $sMessage ) {
            $response['message'] .= $sMessage . PHP_EOL;
          }

        }
      }

      wp_die( json_encode( $response ) );
    }

  } // WPXtremeAjax
}