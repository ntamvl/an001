<?php
/**
 * Uninstall procedure
 *
 * ## Overview
 * If this file exists WordPress executes its contents when the plugin is deleted from the WordPress.
 *
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-03-12
 * @version            1.0.1
 *
 */

// If uninstall not called from WordPress exit
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit ();
}