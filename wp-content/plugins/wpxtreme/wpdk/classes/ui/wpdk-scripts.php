<?php
/// @cond private

/*
 * [DRAFT]
 *
 * THE FOLLOWING CODE IS A DRAFT. FEEL FREE TO USE IT TO MAKE SOME EXPERIMENTS, BUT DO NOT USE IT IN ANY CASE IN
 * PRODUCTION ENVIRONMENT. ALL CLASSES AND RELATIVE METHODS BELOW CAN CHNAGE IN THE FUTURE RELEASES.
 *
 */

/**
 * This class manage the registered scripts in WordPress.
 * Very useful for view controller.
 *
 * @class           WPDKScripts
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-01-29
 * @version         1.0.0
 * @since           1.4.13
 */
class WPDKScripts {

  // @todo List of standard WordPress scripts handles
  const JQUERY = 'jquery';

  protected $scripts = array();

  /**
   * Create an instance of WPDKScripts class
   *
   * @brief Construct
   *
   * @return WPDKScripts
   */
  public function __construct()
  {
    //global $wp_scripts;
  }

  /**
   * Return TRUE if a handle is registered
   *
   * @brief Has Handle registered
   *
   * @param string|array $handle Handle or list of handles.
   * @param string       $comp   If $handle is an array or list of handles, use 'AND' or '&' to return TRUE wheter all handle exists
   *                             use 'OR' or '|' to return TRUE if almost one handle exists
   *
   * @return bool
   */
  public function hasScripts( $handle, $comp = '&' )
  {
    global $wp_scripts;

    if ( isset( $wp_scripts->registered ) && !empty( $handle ) ) {

      // Sanitize input
      if ( is_string( $handle ) ) {
        $handles = explode( ',', $handle );
      }
      elseif ( is_array( $handle ) ) {
        $handles = $handle;
      }
      else {
        return false;
      }

      // AND
      if ( '&' == $comp || 'AND' == strtoupper( $comp ) ) {
        foreach ( $handles as $handle ) {
          if ( !isset( $wp_scripts->registered[$handle] ) ) {
            return false;
          }
        }
        return true;
      }

      // OR
      if ( '|' == $comp || 'OR' == strtoupper( $comp ) ) {
        foreach ( $handles as $handle ) {
          if ( isset( $wp_scripts->registered[$handle] ) ) {
            return true;
          }
        }
        return false;
      }
    }
    return false;
  }

  /**
   * Register one or more script following the view controller standard
   *
   * @brief Register
   *
   * @param array $scripts   List of scripts
   *
   *     $scripts = array(
   *         'wpxbz-preferences.js' => array(
   *            'handle'  => 'my-handle',                  // Optional -sanitize_titile( $key ) + remove '.js'
   *            'path'    => WPXBANNERIZE_URL_JAVASCRIPT,  // Optional if set $path params
   *            'deps'    => array( 'jquery' ),            // Optional if set $deps params
   *            'version' => '1.2.3',                      // Optional if set $version
   *            'footer'  => false,                        // Optional override the $in_footer params,
   *          )
   *     );
   *
   *     WPDKScripts::registerScripts( $scripts );
   *
   *     OR
   *
   *     $scripts = array(
   *         'wpxbz-preferences.js',
   *         'wpxbz-admin.js',
   *     );
   *
   *     WPDKScripts::registerScripts( $scripts, $path, $version );
   *
   *     OR
   *
   *     $scripts = array(
   *         'wpxbz-preferences.js' => array(
   *            'handle'  => 'my-handle',                  // Optional -sanitize_titile( $key ) + remove '.js'
   *            'path'    => WPXBANNERIZE_URL_JAVASCRIPT,  // Optional if set $path params
   *            'version' => '1.2.3',                      // Optional if set $version
   *            'footer'  => false,                        // Optional override the $in_footer params
   *          ),
   *         'wpxbz-admin.js',
   *     );
   *
   *     // $path and version will be used for 2th iten
   *     WPDKScripts::registerScripts( $scripts, $path, $version );
   *
   * @param string       $path      Optional. If set all scripts will be loaded fron this url
   * @param string|array $deps      Optional. One or more handle dependiences
   * @param bool|string  $version   Optional. If set will apply to all script
   * @param bool         $in_footer Optional. Default all scripts are loaded in footer
   *
   * @return bool
   */
  public function registerScripts( $scripts, $path = '', $deps = array(), $version = false, $in_footer = true )
  {
    if ( !empty( $scripts ) && is_array( $scripts ) ) {
      foreach ( $scripts as $filename => $info ) {

        // Case 1
        if ( is_array( $info ) ) {
          $handle = isset( $info['handle'] ) ? $info['handle'] : sanitize_title( WPDKFilesystem::filename( $filename ) );
          $_path  = isset( $info['path'] ) ? $info['path'] : $path;
          $_deps  = isset( $info['deps'] ) ? $info['deps'] : $deps;

          // Sanitize $deps
          if ( is_string( $_deps ) ) {
            $_deps = explode( ',', $_deps );
          }

          $_version   = isset( $info['version'] ) ? $info['version'] : $version;
          $_in_footer = isset( $info['footer'] ) ? $info['footer'] : $in_footer;
          $src        = sprintf( '%s%s', trailingslashit( $_path ), $filename );
        }
        elseif ( is_string( $info ) ) {
          $handle = sanitize_title( WPDKFilesystem::filename( $info ) );
          $_path  = $path;
          $_deps  = $deps;

          // Sanitize $deps
          if ( is_string( $_deps ) ) {
            $_deps = explode( ',', $_deps );
          }

          $_version   = $version;
          $_in_footer = $in_footer;
          $src        = sprintf( '%s%s', trailingslashit( $_path ), $info );
        }
        // Cha!
        else {
          return false;
        }

        // Stability
        if ( !empty( $handle ) && !empty( $src ) ) {
          wp_register_script( $handle, $src, $_deps, $_version, $_in_footer );
        }
      }
    }
    return true;
  }

  /**
   * Enqueue script for list of page. Return FALSE if $pages is empty
   *
   * @brief Enqueue scripts
   *
   * @param array        $pages     Array of page slug
   * @param string|array $handle    The script handle or an array of handles
   * @param bool         $src       Optional. Source URI
   * @param array        $deps      Optional. Array of other handle
   * @param bool         $ver       Optional. Version to avoid cache
   * @param bool         $in_footer Optional. Load in footer
   *
   * @return bool
   */
  public static function enqueue_script_pages( $pages, $handle, $src = false, $deps = array(), $ver = false,
                                                $in_footer = false )
  {
    if ( empty( $pages ) ) {
      return false;
    }
    if ( is_string( $pages ) ) {
      $pages = array( $pages );
    }

    // Any single or array
    $handles = (array)$handle;
    foreach ( $pages as $slug ) {
      if ( is_page( $slug ) ) {
        foreach ( $handles as $handle ) {
          wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        }
        break;
      }
    }
    return true;
  }

  /**
   * Enqueue style for list of page. Return FALSE if $pages is empty
   *
   * @brief Enqueue style
   *
   * @param array        $pages     Array of page slug
   * @param string|array $handle    The script handle or an array of handles
   * @param bool         $src       Optional. Source URI
   * @param array        $deps      Optional. Array of other handle
   * @param bool         $ver       Optional. Version to avoid cache
   *
   * @return bool
   */
  public static function enqueue_style_pages( $pages, $handle, $src = false, $deps = array(), $ver = false )
  {
    if ( empty( $pages ) ) {
      return false;
    }
    if ( is_string( $pages ) ) {
      $pages = array( $pages );
    }

    // Any single or array
    $handles = (array)$handle;
    foreach ( $pages as $slug ) {
      if ( is_page( $slug ) ) {
        foreach ( $handles as $handle ) {
          wp_enqueue_style( $handle, $src, $deps, $ver );
        }
        break;
      }
    }
    return true;
  }



  /**
   * Enqueue script for list of page template. Return FALSE if $page_templates is empty
   *
   * @brief Enqueue scripts
   *
   * @param array        $page_templates Array of page slug template
   * @param string|array $handle         The script handle or an array of handles
   * @param bool         $src            Optional. Source URI
   * @param array        $deps           Optional. Array of other handle
   * @param bool         $ver            Optional. Version to avoid cache
   * @param bool         $in_footer      Optional. Load in footer
   *
   * @return bool
   */
  public static function enqueue_script_page_templates( $page_templates, $handle, $src = false, $deps = array(),
                                                        $ver = false, $in_footer = false )
  {
    if ( empty( $page_templates ) ) {
      return false;
    }
    if ( is_string( $page_templates ) ) {
      $page_templates = array( $page_templates );
    }

    // Any single or array
    $handles = (array)$handle;
    foreach ( $page_templates as $slug ) {
      if ( is_page_template( $slug ) ) {
        foreach ( $handles as $handle ) {
          wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
        }
        break;
      }
    }
    return true;
  }

  /**
   * Enqueue script for list of page template. Return FALSE if $page_templates is empty
   *
   * @brief Enqueue styles
   *
   * @param array        $page_templates Array of page slug template
   * @param string|array $handle         The script handle or andarray of handles
   * @param bool         $src            Optional. Source URI
   * @param array        $deps           Optional. Array of other handle
   * @param bool         $ver            Optional. Version to avoid cache
   *
   * @return bool
   */
  public static function enqueue_style_page_templates( $page_templates, $handle, $src = false, $deps = array(), $ver = false )
  {
    if ( empty( $page_templates ) ) {
      return false;
    }
    if ( is_string( $page_templates ) ) {
      $page_templates = array( $page_templates );
    }

    // Any single or array
    $handles = (array)$handle;
    foreach ( $page_templates as $slug ) {
      if ( is_page_template( $slug ) ) {
        foreach ( $handles as $handle ) {
          wp_enqueue_style( $handle, $src, $deps, $ver );
        }
        break;
      }
    }
    return true;
  }


}

/// @endcond