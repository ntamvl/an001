<?php

/**
 * @file               wpdk-functions.php
 *
 * Very useful functions for common cases. All that is missing in WordPress.
 *
 * ## Overview
 * This file contains the pure inline function without class wrapper. You can use these function directly from code.
 *
 * @brief              Very useful functions for common cases.
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2015 wpXtreme Inc. All Rights Reserved.
 * @date               2015-01-22
 * @version            1.0.1
 *
 * @history            1.0.1 - Added `wpdk_is_lock()` function.
 */

// ---------------------------------------------------------------------------------------------------------------------
// has/is zone
// ---------------------------------------------------------------------------------------------------------------------


if( !function_exists( 'wpdk_is_bool' ) ) {

  /**
   * Return TRUE if the string NOT contains '', 'false', '0', 'no', 'n', 'off', null.
   *
   * @brief      Check for generic boolean
   *
   * @param string $str String to check
   *
   * @return bool
   *
   * @file       wpdk-functions.php
   *
   */
  function wpdk_is_bool( $str )
  {
    return !in_array( strtolower( $str ), array( '', 'false', '0', 'no', 'n', 'off', null ) );
  }
}

if( !function_exists( 'wpdk_is_url' ) ) {

  /**
   * Return TRUE if a url is a URI
   *
   * @brief      Check URI
   *
   * @since      1.0.0.b2
   *
   * @param string $url
   *
   * @return bool
   *
   * @file       wpdk-functions.php
   */
  function wpdk_is_url( $url )
  {
    if( !empty( $url ) && is_string( $url ) ) {
      return ( '#' === substr( $url, 0, 1 ) || '/' === substr( $url, 0, 1 ) || 'http' === substr( $url, 0, 4 ) ||
        false !== strpos( $url, '?' ) || false !== strpos( $url, '&' ) );
    }

    return false;
  }
}

if( !function_exists( 'wpdk_is_infinity' ) ) {

  /**
   * Check if infinity
   *
   * @brief      Infinity
   *
   * @param float|string $value Check value
   *
   * @return bool TRUE if $value is equal to INF (php) or WPDKMath::INFINITY
   *
   * @file       wpdk-functions.php
   * @deprecated Since 1.2.0 Use WPDKMath::isInfinity() instead
   *
   */
  function wpdk_is_infinity( $value )
  {
    _deprecated_function( __FUNCTION__, '1.2.0', 'WPDKMath::isInfinity()' );

    return WPDKMath::isInfinity( $value );
  }
}

if( !function_exists( 'wpdk_is_ajax' ) ) {

  /**
   * Return TRUE if we are called by Ajax. Used to be sure that we are responding to an HTTPRequest request and that
   * the WordPress define DOING_AJAX is defined.
   *
   * @brief Ajax validation
   *
   * @return bool TRUE if Ajax trusted
   */
  function wpdk_is_ajax()
  {
    if( defined( 'DOING_AJAX' ) ) {
      return true;
    }
    if( isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) == 'xmlhttprequest'
    ) {
      return true;
    }
    else {
      return false;
    }
  }
}

if( !function_exists( 'wpdk_is_child' ) ) {

  /**
   * Returns TRUE if the current page is a child of another.
   *
   * @param array|int|string $parent Mixed format for parent page
   *
   * @return bool TRUE if the current page is a child of another
   */
  function wpdk_is_child( $parent = '' )
  {
    global $post;

    $parent_obj   = get_post( $post->post_parent, ARRAY_A );
    $parent       = (string)$parent;
    $parent_array = (array)$parent;

    if( $parent_obj && isset( $parent_obj[ 'ID' ] ) ) {
      if( in_array( (string)$parent_obj[ 'ID' ], $parent_array ) ) {
        return true;
      }
      elseif( in_array( (string)$parent_obj[ 'post_title' ], $parent_array ) ) {
        return true;
      }
      elseif( in_array( (string)$parent_obj[ 'post_name' ], $parent_array ) ) {
        return true;
      }
      else {
        return false;
      }
    }

    return false;
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Sanitize
// ---------------------------------------------------------------------------------------------------------------------

if( !function_exists( 'wpdk_sanitize_function' ) ) {

  /**
   * Return a possibile function name
   *
   * @brief Sanitize for function name
   * @since 1.0.0.b3
   *
   * @param string $key String key
   *
   * @return mixed
   */
  function wpdk_sanitize_function( $key )
  {
    return str_replace( '-', '_', sanitize_key( $key ) );
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Posts
// ---------------------------------------------------------------------------------------------------------------------

if( !function_exists( 'wpdk_get_image_size' ) ) {

  /**
   * Return registered image size information
   *
   * @param string $name Image size ID
   *
   * @return bool|array FALSE if not found Image size ID
   */
  function wpdk_get_image_size( $name )
  {
    global $_wp_additional_image_sizes;
    if( isset( $_wp_additional_image_sizes[ $name ] ) ) {
      return $_wp_additional_image_sizes[ $name ];
    }

    return false;
  }
}

if( !function_exists( 'wpdk_checked' ) ) {

  /**
   * Commodity to extends checked() WordPress function with array check
   *
   * @param string|array $haystack Single value or array
   * @param mixed        $current  (true) The other value to compare if not just true
   * @param bool         $echo     Whether to echo or just return the string
   *
   * @return string html attribute or empty string
   */
  function wpdk_checked( $haystack, $current, $echo = true )
  {
    if( is_array( $haystack ) ) {
      if( in_array( $current, $haystack ) ) {
        $current = $haystack = 1;

        return checked( $haystack, $current, $echo );

      }

      return false;
    }

    return checked( $haystack, $current, $echo );
  }
}

if( !function_exists( 'wpdk_selected' ) ) {

  /**
   * Commodity to extends selected() WordPress function with array check
   *
   * @param string|array $haystack Single value or array
   * @param mixed        $current  (true) The other value to compare if not just true
   * @param bool         $echo     Whether to echo or just return the string
   *
   * @return string html attribute or empty string
   * @deprecated Since 1.2.0 Use WPDKHTMLTagSelect::selected() instead
   */
  function wpdk_selected( $haystack, $current, $echo = true )
  {
    _deprecated_function( __FUNCTION__, '1.2.0', 'WPDKHTMLTagSelect::selected()' );

    return WPDKHTMLTagSelect::selected( $haystack, $current );
  }
}

if( !function_exists( 'wpdk_page_with_slug' ) ) {

  /**
   * Return a post object by slug or alternative slug.
   *
   * @since  1.8.0
   *
   * @param string $slug             Post slug.
   * @param string $post_type        Optional. Post type. Default 'page'.
   * @param string $alternative_slug Optional. Alternative slug whether the post is not found. Default empty.
   *
   * @uses   get_page_by_path()
   * @note   WPML compatible
   *
   * @return null|WP_Post
   */
  function wpdk_page_with_slug( $slug, $post_type = 'page', $alternative_slug = '' )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Stability
    if( empty( $slug ) ) {
      return null;
    }

    // Get object
    $page = get_page_by_path( $slug, OBJECT, $post_type );

    if( is_null( $page ) ) {
      $page = get_page_by_path( $alternative_slug, OBJECT, $post_type );

      if( is_null( $page ) ) {

        // Check WPML
        if( function_exists( 'icl_object_id' ) ) {
          $sql = <<<SQL
SELECT ID FROM {$wpdb->posts}
WHERE post_name = '{$slug}'
AND post_type = '{$post_type}'
AND post_status = 'publish'
SQL;
          $id  = $wpdb->get_var( $sql );
          $id  = icl_object_id( $id, $post_type, true );
        }
        else {
          return null;
        }
      }
      else {
        $id = $page->ID;
      }

      return get_post( $id );
    }

    return $page;
  }
}

if( !function_exists( 'wpdk_content_page_with_slug' ) ) {

  /**
   * Return the post content by slug or alternative slug.
   *
   * @param string $slug             Post slug.
   * @param string $post_type        Optional. Post type. Default 'page'.
   * @param string $alternative_slug Optional. Alternative slug whether the post is not found. Default empty.
   *
   * @note   WPML compatible
   * @uses   wpdk_page_with_slug()
   *
   * @return null|string
   */
  function wpdk_content_page_with_slug( $slug, $post_type = 'page', $alternative_slug = '' )
  {
    $page = wpdk_page_with_slug( $slug, $post_type, $alternative_slug );

    if( is_null( $page ) ) {
      return $page;
    }

    return apply_filters( 'the_content', $page->post_content );
  }
}

if( !function_exists( 'wpdk_permalink_page_with_slug' ) ) {

  /**
   * Return the post permalink content by slug or alternative slug. FALSE otherwise.
   *
   * @param string $slug             Post slug.
   * @param string $post_type        Optional. Post type. Default 'page'.
   * @param string $alternative_slug Optional. Alternative slug whether the post is not found. Default empty.
   *
   * @note   WPML compatible
   * @uses   wpdk_page_with_slug()
   *
   * @return bool|string
   */
  function wpdk_permalink_page_with_slug( $slug, $post_type = 'page', $alternative_slug = '' )
  {
    $page = wpdk_page_with_slug( $slug, $post_type, $alternative_slug );

    if( is_null( $page ) ) {
      return false;
    }

    $permalink = get_permalink( $page->ID );

    // Append slash
    return trailingslashit( $permalink );
  }
}

if( !function_exists( 'wpdk_delta_object' ) ) {

  /**
   * Do a merge/combine between two object tree.
   * If the old version not contains an object or property, that is added.
   * If the old version contains an object or property less in last version, that is deleted.
   *
   * @brief      Object delta compare for combine
   *
   * @param mixed $last_version Object tree with new or delete object/value
   * @param mixed $old_version  Current Object tree, loaded from serialize or database for example
   *
   * @return Object the delta Object tree
   *
   * @deprecated Since 1.2.0 Use WPDKObject::__delta() instead
   */
  function wpdk_delta_object( $last_version, $old_version )
  {
    _deprecated_function( __FUNCTION__, '1.2.0', 'WPDKObject::__delta()' );

    return WPDKObject::__delta( $last_version, $old_version );
  }
}

if( !function_exists( 'wpdk_get_image_in_post_content' ) ) {

  /**
   * Get the img src value fron content of a post or page.
   *
   * @brief Get an img tag from the content
   *
   * @param int $id_post ID post
   *
   * @return mixed
   */
  function wpdk_get_image_in_post_content( $id_post )
  {
    _deprecated_function( __FUNCTION__, '1.3.1', '_WPDKPost::imageContent() or _WPDKPost::imageContentWithID()' );

    ob_start();
    ob_end_clean();
    $output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field( 'post_content', $id_post ), $matches );
    if( !empty( $matches ) && is_array( $matches ) && isset( $matches[ 1 ][ 0 ] ) ) {
      $display_img = $matches[ 1 ][ 0 ];

      return $display_img;
    }

    return null;
  }
}

if( !function_exists( 'wpdk_get_image_from_post_thumbnail' ) ) {

  /**
   * Function to find image using WP available function get_the_post_thumbnail().
   *
   * @brief      Get thumbnail image
   * @deprecated Since 1.3.1 Use _WPDKPost::thumbnail() or _WPDKPost::thumbnailWithID()
   *
   * @param int $id_post ID post
   *
   * @return mixed|null
   */
  function wpdk_get_image_from_post_thumbnail( $id_post )
  {
    _deprecated_function( __FUNCTION__, '1.3.1', '_WPDKPost::thumbnail() or _WPDKPost::thumbnailWithID()' );
    if( function_exists( 'has_post_thumbnail' ) ) {
      if( has_post_thumbnail( $id_post ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $id_post ), 'full' );

        return $image[ 0 ];
      }
    }

    return null;
  }
}

if( !function_exists( 'wpdk_get_image_from_attachments' ) ) {

  /**
   * Get src url image from first image attachment post
   *
   * @brief Get image from post attachment
   *
   * @param int $id_post ID post
   *
   * @return array|bool
   */
  function wpdk_get_image_from_attachments( $id_post )
  {
    _deprecated_function( __FUNCTION__, '1.3.1', '_WPDKPost::imageAttachmentsWithID() or _WPDKPost::imageAttachments()' );
    if( function_exists( 'wp_get_attachment_image' ) ) {
      $children = get_children( array(
                                  'post_parent'    => $id_post,
                                  'post_type'      => 'attachment',
                                  'numberposts'    => 1,
                                  'post_status'    => 'inherit',
                                  'post_mime_type' => 'image',
                                  'order'          => 'ASC',
                                  'orderby'        => 'menu_order ASC'
                                ) );

      if( empty( $children ) || !is_array( $children ) ) {
        return false;
      }

      $item = current( $children );

      if( is_object( $item ) && isset( $item->ID ) ) {
        $image = wp_get_attachment_image_src( $item->ID, 'full' );

        return $image[ 0 ];
      }
    }

    return false;
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// WPDKResult check
// ---------------------------------------------------------------------------------------------------------------------

if( !function_exists( 'is_wpdk_error' ) ) {

  /**
   * Looks at the object and if a WPDKError class. Does not check to see if the parent is also WPDKError or a WPDKResult,
   * so can't inherit both the classes and still use this function.
   *
   * @brief      Check whether variable is a WPDK result error.
   *
   * @param mixed $thing Check if unknown variable is WPDKError object.
   *
   * @return bool TRUE, if WPDKError. FALSE, if not WPDKError.
   *
   * @deprecated Since 1.2.0 Use WPDKResult::isError() instead
   *
   */
  function is_wpdk_error( $thing )
  {
    _deprecated_function( __FUNCTION__, '1.2.0', 'WPDKResult::isError()' );

    return WPDKResult::isError( $thing );
  }
}

if( !function_exists( 'is_wpdk_warning' ) ) {

  /**
   * Looks at the object and if a WPDKWarning class. Does not check to see if the parent is also WPDKWarning or a
   * WPDKResult, so can't inherit both the classes and still use this function.
   *
   * @brief      Check whether variable is a WPDK result warning.
   *
   * @param mixed $thing Check if unknown variable is WPDKWarning object.
   *
   * @return bool TRUE, if WPDKWarning. FALSE, if not WPDKWarning.
   *
   * @deprecated Since 1.2.0 Use WPDKResult::isWarning() instead
   */
  function is_wpdk_warning( $thing )
  {
    _deprecated_function( __FUNCTION__, '1.2.0', 'WPDKResult::isWarning()' );

    return WPDKResult::isWarning( $thing );
  }
}

if( !function_exists( 'is_wpdk_status' ) ) {

  /**
   * Looks at the object and if a WPDKStatus class. Does not check to see if the parent is also WPDKStatus or a
   * WPDKResult, so can't inherit both the classes and still use this function.
   *
   * @brief      Check whether variable is a WPDK result status.
   *
   * @param mixed $thing Check if unknown variable is WPDKStatus object.
   *
   * @return bool TRUE, if WPDKStatus. FALSE, if not WPDKStatus.
   *
   * @deprecated Since 1.2.0 Use WPDKResult::isStatus() instead
   */
  function is_wpdk_status( $thing )
  {
    _deprecated_function( __FUNCTION__, '1.2.0', 'WPDKResult::isStatus()' );

    return WPDKResult::isWarning( $thing );
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Miscellanea
// ---------------------------------------------------------------------------------------------------------------------

if( !function_exists( 'wpdk_add_page' ) ) {

  /**
   * Add a custom hidden (without menu) page in the admin backend area and return the page's hook_suffix.
   *
   * @brief Add a page
   *
   * @param string          $page_slug  The slug name to refer to this hidden pahe by (should be unique)
   * @param string          $page_title The text to be displayed in the title tags of the page when the page is selected
   * @param string          $capability The capability required for this page to be displayed to the user.
   * @param callback|string $function   Optional. The function to be called to output the content for this page.
   * @param string          $hook_head  Optional. Callback when head is loaded
   * @param string          $hook_load  Optional. Callback when loaded
   *
   * @return string
   */
  function wpdk_add_page( $page_slug, $page_title, $capability, $function = '', $hook_head = '', $hook_load = '' )
  {
    global $admin_page_hooks, $_registered_pages, $_parent_pages;

    $hook_name = '';

    if( !empty( $function ) && current_user_can( $capability ) ) {
      $page_slug                      = plugin_basename( $page_slug );
      $admin_page_hooks[ $page_slug ] = $page_title;
      $hook_name                      = get_plugin_page_hookname( $page_slug, '' );
      if( !empty( $hook_name ) ) {
        add_action( $hook_name, $function );
        $_registered_pages[ $hook_name ] = true;
        $_parent_pages[ $page_slug ]     = false;

        if( !empty( $hook_head ) ) {
          add_action( 'admin_head-' . $hook_name, $hook_head );
        }

        if( !empty( $hook_load ) ) {
          add_action( 'load-' . $hook_name, $hook_load );
        }
      }
    }

    return $hook_name;
  }
}

if( !function_exists( 'wpdk_enqueue_script_page' ) ) {
  /**
   * Enqueue script for list of page template
   *
   * @brief      Enqueue script
   * @deprecated since 1.4.21 Use WPDKScripts::enqueue_scripts_page_template() instead
   *
   * @param array  $pages     Array of page slug
   * @param string $handle    The script /unique) handle
   * @param bool   $src       Optional. Source URI
   * @param array  $deps      Optional. Array of other handle
   * @param bool   $ver       Optional. Version to avoid cache
   * @param bool   $in_footer Optional. Load in footer
   */
  function wpdk_enqueue_script_page( $pages, $handle, $src = false, $deps = array(), $ver = false, $in_footer = false )
  {
    foreach( $pages as $slug ) {
      if( is_page_template( $slug ) ) {
        wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        break;
      }
    }
  }
}

if( !function_exists( 'wpdk_enqueue_script_page_template' ) ) {

  /**
   * Enqueue script for list of page template. Return FALSE if $page_templates is empty
   *
   * @brief      Enqueue script
   * @deprecated since 1.4.21 Use WPDKScripts::enqueue_scripts_page_template() instead
   *
   * @param array  $page_templates Array of page slug template
   * @param string $handle         The script /unique) handle
   * @param bool   $src            Optional. Source URI
   * @param array  $deps           Optional. Array of other handle
   * @param bool   $ver            Optional. Version to avoid cache
   * @param bool   $in_footer      Optional. Load in footer
   *
   * @return bool
   */
  function wpdk_enqueue_script_page_template( $page_templates, $handle, $src = false, $deps = array(), $ver = false, $in_footer = false )
  {
    if( empty( $page_templates ) ) {
      return false;
    }
    if( is_string( $page_templates ) ) {
      $page_templates = array( $page_templates );
    }
    foreach( $page_templates as $slug ) {
      if( is_page_template( $slug ) ) {
        wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        break;
      }
    }

    return true;
  }
}

if( !function_exists( 'wpdk_set_user_transient' ) ) {

  /**
   * Set/update the value of a user transient.
   *
   * You do not need to serialize values. If the value needs to be serialized, then it will be serialized before it is
   * set.
   *
   * @brief      Set
   * @since      1.0.0
   * @deprecated since 1.3.0 - Use WPDKUser::setTransientWithUser() instead
   *
   * @uses       apply_filters() Calls 'pre_set_user_transient_$transient' hook to allow overwriting the transient value
   *             to be stored.
   * @uses       do_action() Calls 'set_user_transient_$transient' and 'setted_transient' hooks on success.
   *
   * @param string $transient  Transient name. Expected to not be SQL-escaped.
   * @param mixed  $value      Transient value. Expected to not be SQL-escaped.
   * @param int    $expiration Time until expiration in seconds, default 0
   * @param int    $user_id    Optional. User ID. If null the current user id is used instead
   *
   * @return bool False if value was not set and true if value was set.
   */
  function wpdk_set_user_transient( $transient, $value, $expiration = 0, $user_id = null )
  {
    _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '1.3.0', 'WPDKUser::setTransientWithUser()' );

    return WPDKUser::setTransientWithUser( $transient, $value, $expiration, $user_id );
  }
}

if( !function_exists( 'wpdk_get_user_transient' ) ) {

  /**
   * Get the value of a user transient.
   * If the transient does not exist or does not have a value, then the return value will be false.
   *
   * @brief      Get
   * @since      1.0.0
   * @deprecated since 1.4.8 - Use WPDKUser::getTransientWithUser() instead
   *
   * @uses       apply_filters() Calls 'pre_user_transient_$transient' hook before checking the transient. Any value
   *             other than false will "short-circuit" the retrieval of the transient and return the returned value.
   * @uses       apply_filters() Calls 'user_transient_$transient' hook, after checking the transient, with the transient
   *             value.
   *
   * @param string $transient Transient name. Expected to not be SQL-escaped
   * @param int    $user_id   Optional. User ID. If null the current user id is used instead
   *
   * @return mixed Value of transient
   */
  function wpdk_get_user_transient( $transient, $user_id = null )
  {
    _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '1.4.8', 'WPDKUser::getTransientWithUser()' );

    return WPDKUser::getTransientWithUser( $transient, $user_id );
  }
}

if( !function_exists( 'wpdk_delete_user_transient' ) ) {

  /**
   * Delete a user transient.
   *
   * @brief      Delete
   * @since      1.1.0
   * @deprecated since 1.5.1 - Use WPDKUser::deleteTransientWithUser() instead
   *
   * @uses       do_action() Calls 'delete_user_transient_$transient' hook before transient is deleted.
   * @uses       do_action() Calls 'deleted_user_transient' hook on success.
   *
   * @param string $transient Transient name. Expected to not be SQL-escaped.
   * @param int    $user_id   Optional. User ID. If null the current user id is used instead
   *
   * @return bool true if successful, false otherwise
   */
  function wpdk_delete_user_transient( $transient, $user_id = null )
  {

    _deprecated_function( __CLASS__ . '::' . __FUNCTION__, '1.5.1', 'WPDKUser::deleteTransientWithUser()' );

    return WPDKUser::deleteTransientWithUser( $transient, $user_id );
  }
}

if( !function_exists( 'wpdk_is_request' ) ) {

  /**
   * Return true if the $verb param in input match with REQUEST METHOD
   *
   * @brief Check request
   * @since 1.1.0
   *
   * @param string $verb The verb, for instance; GET, post, delete, etc...
   *
   * @return bool
   */
  function wpdk_is_request( $verb )
  {
    $verb = strtolower( $verb );

    return ( $verb == strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) );
  }
}

if( !function_exists( 'wpdk_is_request_get' ) ) {

  /**
   * Return true if the REQUEST METHOD is GET
   *
   * @brief Check if request is get
   *
   * @return bool
   */
  function wpdk_is_request_get()
  {
    return wpdk_is_request( 'get' );
  }
}

if( !function_exists( 'wpdk_is_request_post' ) ) {

  /**
   * Return true if the REQUEST METHOD is POST
   *
   * @brief Check if request is post
   *
   * @return bool
   */
  function wpdk_is_request_post()
  {
    return wpdk_is_request( 'post' );
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Lock down
// ---------------------------------------------------------------------------------------------------------------------

if( !function_exists( 'wpdk_is_lock' ) ) {

  /**
   * Return TRUE (or the calculate hash) if the hash has been locked.
   *
   * @since 1.11.0
   *
   * @param array|string $hash       Optional. Any string or array used to generate an unique hash. If no hash is set, then
   *                                 this function provides to generate one for you and return the first time as string.
   * @param int          $expiration Optional. Expiratin in seconds. Default 5.
   *
   * @return array|bool
   */
  function wpdk_is_lock( $hash = array(), $expiration = 5 )
  {
    // Prepare result
    $result = true;

    // If no hash we create onfly for you
    if( empty( $hash ) ) {
      $hash = $result = array( time() );
    }

    // Hash
    $hash_name = '_wpdk_lock-' . md5( serialize( $hash ) );

    // Check exists
    if( 'lock' === get_transient( $hash_name ) ) {
      return $result;
    }

    // Set transient
    set_transient( $hash_name, 'lock', $expiration );

    return false;

  }
}