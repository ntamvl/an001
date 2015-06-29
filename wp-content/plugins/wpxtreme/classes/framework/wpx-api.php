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
 * The wpXtreme API interface
 *
 * @class              WPXAPI
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-05-21
 * @version            1.2.0
 *
 */

class WPXAPI extends WPDKObject {

  // Timeout connection request
  const CONNECTION_TIMEOUT = 45;

  // The User Agent request
  const USER_AGENT = 'wpXtreme/';

  // The wpXtreme Server
  const SERVER = 'https://wpxtre.me/';

  // The wpXtreme API endpoint
  const API_ENDPOINT = 'https://wpxtre.me/api/v1';

  /**
   * Override version
   *
   * @var string $__version
   */
  public $__version = '1.2.0';

  /**
   * Who send API.
   *
   * @note  Not used yet
   *
   * @var string $sender
   */
  public $sender;

  /**
   * Create an instance of WPXAPI class
   *
   * @param string $sender Who that send
   *
   * @return WPXAPI
   */
  public function __construct( $sender = '' )
  {
    $this->sender = $sender;
  }

  /**
   * Do a request to the wpXtreme Server.
   *
   * @param string $route    Route. Example `wpxstore/plugins/showcase`
   * @param array  $raw_body Optional. Params will be convert in jSON
   * @param string $verb     Optional. Verb of request. Default is WPDKHTTPVerbs::GET
   *
   * @return array|bool
   */
  protected function request( $route, $raw_body = array(), $verb = WPDKHTTPVerbs::GET )
  {
    global $wp_version;

    //WPXtreme::log( $route , __CLASS__ . '::' . __METHOD__ );

    /*
     *
     * The following information is mandatory
     *
     */

    // Send version of WordPress, PHP and wpXtreme to server
    $raw_body['wp_version']   = $wp_version;
    $raw_body['php_version']  = PHP_VERSION;
    $raw_body['wpx_version']  = WPXTREME_VERSION;
    $raw_body['wpdk_version'] = WPDK_VERSION;

    /*
     *
     * The following information may be empty or missing
     *
     */

    // WPX Store ID (user email/username in the store)
    $raw_body['wpxstore_id'] = isset( $raw_body['wpxstore_id'] ) ? $raw_body['wpxstore_id'] : WPXtremePreferences::init()->wpxstore->user_id;

    // Get token
    $raw_body['token'] = WPXtremePreferences::init()->wpxstore->token;

    //WPXtreme::log( $raw_body, '$raw_body' );

    // Prepare array for request
    $args = array(
      'method'      => $verb,
      'timeout'     => self::CONNECTION_TIMEOUT,
      'redirection' => 5,
      'httpversion' => '1.0',
      'user-agent'  => self::USER_AGENT . WPXTREME_VERSION,
      'blocking'    => true,
      'headers'     => array(),
      'cookies'     => array(),
      'body'        => json_encode( $raw_body ),
      'compress'    => false,
      'decompress'  => false,
      'sslverify'   => true,
    );

    if ( !empty( $route ) ) {

      // Build the endpoint API
      $endpoint = trailingslashit( sprintf( '%s%s', trailingslashit( self::API_ENDPOINT ), $route ) );

      //WPXtreme::log( $endpoint, '$endpoint' );
      //WPXtreme::log( $args, '$args' );

      // Do request
      $response = wp_remote_request( $endpoint, $args );

      /*
       * eg:
       *     array(5) {
       *      ["headers"]=> array(11) {
       *        ["server"]=> string(11) "nginx/1.4.6"
       *        ["date"]=> string(29) "Wed, 21 May 2014 12:05:09 GMT"
       *        ["content-type"]=> string(16) "application/json"
       *        ["connection"]=> string(5) "close"
       *        ["x-powered-by"]=> string(20) "PHP/5.4.6-1ubuntu1.8"
       *        ["set-cookie"]=> string(44) "PHPSESSID=it4t4qred8oe6u6c4aq0efdvt0; path=/"
       *        ["expires"]=> string(29) "Thu, 19 Nov 1981 08:52:00 GMT"
       *        ["cache-control"]=> string(62) "no-store, no-cache, must-revalidate, post-check=0, pre-check=0"
       *        ["pragma"]=> string(8) "no-cache"
       *        ["x-ratelimit-limit"]=> string(4) "5000"
       *        ["x-ratelimit-remaining"]=> string(4) "4806"
       *      }
       *      ["body"]=> string(44) "{"token":"2a14a17e25e2cf962e00e4cde43b16d9"}"
       *      ["response"]=> array(2) {
       *        ["code"]=> int(200)
       *        ["message"]=> string(2) "OK"
       *      }
       *      ["cookies"]=> array(1) {
       *        [0]=> object(WP_Http_Cookie)#2843 (5) {
       *          ["name"]=> string(9) "PHPSESSID"
       *          ["value"]=> string(26) "it4t4qred8oe6u6c4aq0efdvt0"
       *          ["expires"]=> NULL
       *          ["path"]=> string(1) "/"
       *          ["domain"]=> string(14) "beta.wpxtre.me"
       *        }
       *      }
       *      ["filename"]=> NULL
       *    }
       */

      // Dead connection
      if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
        return false;
      }

      // Get body
      $body = wp_remote_retrieve_body( $response );

      // Result
      $return = array(
        'response' => $response,
        'json'     => empty( $body ) ? '' : json_decode( $body )
      );

      return $return;
    }

    return false;
  }

}

/// @endcond