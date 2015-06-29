<?php

/**
 * Manage and send request to wpXtreme Server
 *
 * @class              WPXtremeAPI
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-05-04
 * @version            1.2.0
 *
 */
final class WPXtremeAPI extends WPXAPI {

  /**
   * Return a singleton instance of WPXtremeAPI class
   *
   * @return WPXtremeAPI
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
   * Create an instance of WPXtremeAPI class
   *
   * @return WPXtremeAPI
   */
  public function __construct()
  {
    parent::__construct( 'wpXtreme' );
  }

  // -------------------------------------------------------------------------------------------------------------------
  // SIGNIN
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Authentication on WPX Store server.
   * Return the token or FALSE if error.
   *
   * @param string $username   The email address.
   * @param string $password   User password.
   * @param string $secret_key The secret key.
   *
   * @return string|bool
   */
  public function singin( $username, $password, $secret_key )
  {
    // Stability
    if( empty( $username ) || empty( $password ) || empty( $secret_key ) ) {
      return false;
    }

    // Save wpxstore id
    $wpxstore_id = $username;

    // Encrypt
    $username = WPDKCrypt::crypt_decrypt( $username, $secret_key );
    $password = WPDKCrypt::crypt_decrypt( $password, $secret_key );

    // Request to SSL server
    $response = $this->request( 'wpxstore/signin', array( 'wpxstore_id' => $wpxstore_id, 'username' => $username, 'password' => $password ) );

    /*
     * eg:
     *     array(2) {
     *      ["response"]=> array(5) {
     *        ["headers"]=> array(11) {
     *          ["server"]=> string(11) "nginx/1.4.6"
     *          ["date"]=> string(29) "Wed, 21 May 2014 12:16:05 GMT"
     *          ["content-type"]=> string(16) "application/json"
     *          ["connection"]=> string(5) "close"
     *          ["x-powered-by"]=> string(20) "PHP/5.4.6-1ubuntu1.8"
     *          ["set-cookie"]=> string(44) "PHPSESSID=k9hd8ohh1ls159bq1aatrddfn4; path=/"
     *          ["expires"]=> string(29) "Thu, 19 Nov 1981 08:52:00 GMT"
     *          ["cache-control"]=> string(62) "no-store, no-cache, must-revalidate, post-check=0, pre-check=0"
     *          ["pragma"]=> string(8) "no-cache"
     *          ["x-ratelimit-limit"]=> string(4) "5000"
     *          ["x-ratelimit-remaining"]=> string(4) "4797"
     *        }
     *        ["body"]=> string(44) "{"token":"da5c44a5032038bba387a59562ce81dd"}"
     *        ["response"]=> array(2) {
     *          ["code"]=> int(200)
     *          ["message"]=> string(2) "OK"
     *        }
     *        ["cookies"]=> array(1) {
     *          [0]=> object(WP_Http_Cookie)#2843 (5) {
     *            ["name"]=> string(9) "PHPSESSID"
     *            ["value"]=> string(26) "k9hd8ohh1ls159bq1aatrddfn4"
     *            ["expires"]=> NULL
     *            ["path"]=> string(1) "/"
     *            ["domain"]=> string(14) "beta.wpxtre.me"
     *          }
     *        }
     *        ["filename"]=> NULL
     *      }
     *      ["json"]=> object(stdClass)#2844 (1) {
     *        ["token"]=> string(32) "da5c44a5032038bba387a59562ce81dd"
     *      }
     *    }
     *
     */


    // If false access denied
    if( false === $response ) {
      WPXtremePreferences::init()->wpxstore->token = false;
      return false;
    }

    // Check for error
    if( isset( $response['json']->error ) ) {
      // TODO
      return false;
    }

    if( !isset( $response['json']->token ) ) {
      // TODO Error
      return false;
    }

    // Get token
    $token = $response['json']->token;

    return $token;

  }

  // -------------------------------------------------------------------------------------------------------------------
  // USER
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return some useful information about current user logged-in. Otherwise return FALSE.
   *
   * @return array|bool
   */
  public function user()
  {
    // Ask for user info
    $response = $this->request( 'wpxstore/user' );

    // If no response return false
    if( empty( $response ) ) {
      return false;
    }

    // Get json(ed) data
    return $response['json']->user;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // PRODUCTS
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return the list of products (plugins or themes)
   *
   * @param array $args An array key values with params for query products.
   *
   * @return array|bool
   */
  public function products( $args )
  {
    // Ask for user info
    $response = $this->request( 'wpxstore/products', $args );

    // If no response return false
    if( empty( $response ) ) {
      return false;
    }

    // Get json(ed) data
    return $response['json']->products;
  }

  /**
   * Return the product information data. When `plugin_api()` call 'plugin_information'.
   * Used to install/update.
   *
   * @param array $args An array with the list of params to query product information.
   *
   * @return bool|object
   */
  public function product_information( $args )
  {
    // Ask for product information
    $response = $this->request( 'wpxstore/product_information', $args );

    // If no response return false
    if( empty( $response ) ) {
      return false;
    }

    // Get json(ed) data
    // NOTE we must serialize this object to keep alive the array section. Otherwise when json is decode the array is lost

    return isset( $response['json']->information ) ? unserialize( $response['json']->information ) : false;
  }


  /**
   * Return the list of products (plugins or themes) to updates
   *
   * @param array $wpx_plugins An array with the list of wpXtreme plugin installed.
   *
   * @return array|bool
   */
  public function plugins_check_updates( $wpx_plugins )
  {
    // Send to the store server only slug and version
    $wpx_installed_plugins = array();

    //WPXtreme::log( $wpx_plugins );

    /*
     * eg:
     *
     *     array(31) {
     *      ["wpx-awesome-taxonomies/main.php"]=> array(11) {
     *        ["Name"]=> string(18) "Awesome Taxonomies"
     *        ["PluginURI"]=> string(17) "https://wpxtre.me"
     *        ["Version"]=> string(5) "1.0.3"
     *        ["Description"]=> string(50) "Awesome Taxonomies with widgets, enhancer and more"
     *        ["Author"]=> string(14) "wpXtreme, Inc,"
     *        ["AuthorURI"]=> string(17) "https://wpxtre.me"
     *        ["TextDomain"]=> string(22) "wpx-awesome-taxonomies"
     *        ["DomainPath"]=> string(12) "localization"
     *        ["Network"]=> bool(false)
     *        ["Title"]=> string(18) "Awesome Taxonomies"
     *        ["AuthorName"]=> string(14) "wpXtreme, Inc,"
     *      }
     */

    // Loop into the installed wpXtreme plugins
    foreach ( $wpx_plugins as $slug => $plugin ) {
      $wpx_installed_plugins[ $slug ] = $plugin['Version'];
    }

    // Prepare args
    $args = array(
      'category' => 'plugins',
      'plugins'  => $wpx_installed_plugins
    );

    // Ask for list of plugins to updates
    $response = $this->request( 'wpxstore/products/check_updates', $args );

    //WPXtreme::log( $response, '$response' );

    // If no response return false
    if ( empty( $response ) ) {
      return false;
    }

    // Get json(ed) data
    return $response['json']->updates;
  }

}