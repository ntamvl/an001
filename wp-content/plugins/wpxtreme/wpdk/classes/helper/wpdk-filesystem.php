<?php

/**
 * Filesystem helper.
 *
 * @class              WPDKFilesystem
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-10-24
 * @version            1.1.5
 *
 * @history            1.1.5 - Added `append()`
 */
class WPDKFilesystem extends WPDKObject {

  /**
   * Override version
   *
   * @brief Version
   *
   * @var string $__version
   */
  public $__version = '1.1.5';

  /**
   * Return the file size (B, KiB, MiB, GiB, TiB, PiB, EiB, ZiB, YiB) well formatted. Return FALSE if file doesn't
   * exists or `filesize()` failure.
   *
   * @brief Get the file size
   * @uses  `WPDKMath::bytes()`
   *
   * @param string $filename  File name or path to a file
   * @param int    $precision Digits to display after decimal
   *
   * @return string|bool
   */
  public static function fileSize( $filename, $precision = 2 )
  {
    if( is_file( $filename ) ) {
      if( !realpath( $filename ) ) {
        $filename = $_SERVER[ 'DOCUMENT_ROOT' ] . $filename;
      }
      $bytes = filesize( $filename );

      if( false === $bytes ) {
        return false;
      }

      return WPDKMath::bytes( $bytes, $precision );
    }

    return false;
  }

  /**
   * Return an array with all matched files from root folder. This method release the follow filters:
   *
   *     wpdk_rglob_find_dir( true, $file ) - when find a dir
   *     wpdk_rglob_find_file( true, $file ) - when find a a file
   *     wpdk_rglob_matched( $regexp_result, $file, $match ) - after preg_match() done
   *
   * @brief get all matched files
   * @since 1.0.0.b4
   *
   * @param string $path    Folder root
   * @param string $match   Optional. Regex to apply on file name. For example use '/^.*\.(php)$/i' to get only php
   *                        file. Default is empty
   *
   * @return array
   */
  public static function recursiveScan( $path, $match = '' )
  {

    /**
     * Return an array with all matched files from root folder.
     *
     * @brief get all matched files
     * @note  Internal recursive use only
     *
     * @param string $path    Folder root
     * @param string $match   Optional. Regex to apply on file name. For example use '/^.*\.(php)$/i' to get only php file
     * @param array  &$result Optional. Result array. Empty form first call
     *
     * @return array
     */
    function _rglob( $path, $match = '', &$result = array() )
    {
      $files = glob( trailingslashit( $path ) . '*', GLOB_MARK );
      if( false !== $files ) {
        foreach( $files as $file ) {
          if( is_dir( $file ) ) {
            $continue = apply_filters( 'wpdk_rglob_find_dir', true, $file );
            if( $continue ) {
              _rglob( $file, $match, $result );
            }
          }
          elseif( !empty( $match ) ) {
            $continue = apply_filters( 'wpdk_rglob_find_file', true, $file );
            if( false == $continue ) {
              break;
            }
            $regexp_result = array();
            $error         = preg_match( $match, $file, $regexp_result );
            if( 0 !== $error || false !== $error ) {
              $regexp_result = apply_filters( 'wpdk_rglob_matched', $regexp_result, $file, $match );
              if( !empty( $regexp_result ) ) {
                $result[ ] = $regexp_result[ 0 ];
              }
            }
          }
          else {
            $result[ ] = $file;
          }
        }

        return $result;
      }
    }

    $result = array();

    return _rglob( $path, $match, $result );
  }

  /**
   * Return the extension of a filename
   *
   * @brief Extension
   * @see   filename()
   *
   * @param string $filename A comoplete filename
   *
   * @return string
   */
  public static function ext( $filename )
  {
    $filename = strtolower( basename( $filename ) );
    $parts    = explode( '.', $filename );

    if( empty( $parts ) ) {
      return false;
    }

    $ext = end( $parts );

    return $ext;
  }

  /**
   * Return the only filename part. if a filename is 'test.black.jpg' will return 'test.black'
   *
   * @brief Filename
   * @since 1.4.15
   * @see   ext()
   *
   * @param string $filename A comoplete filename
   *
   * @return string
   */
  public static function filename( $filename )
  {
    $filename = strtolower( basename( $filename ) );
    $parts    = explode( '.', $filename );

    // No dot found
    if( empty( $parts ) ) {
      return $filename;
    }

    // Multiple dot found
    elseif( count( $parts ) > 2 ) {
      unset( $parts[ count( $parts ) - 1 ] );

      return implode( ',', $parts );
    }

    // Usually
    else {
      return current( $parts );
    }
  }

  /**
   * Append data to a file and return TRUE on successfully, FALSE otherwise.
   *
   * @brief Append
   * @since 1.7.0
   *
   * @param string $data     Data to append.
   * @param string $filename Complete path filename.
   *
   * @return bool
   */
  public static function append( $data, $filename )
  {

    // Check if filename is writable
    if( is_writable( $filename ) || !file_exists( $filename ) ) {

      if( !$handle = @fopen( $filename, 'a' ) ) {
        return false;
      }

      if( !fwrite( $handle, $data ) ) {
        return false;
      }

      fclose( $handle );

      return true;

    }

  }

}