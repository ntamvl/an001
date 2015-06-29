<?php

/**
 * This file is the kickstart used by any WPX plugin in order to check environment and boot itself.
 *
 * This is a special version for wordpress.org repository.
 *
 * @author     wpXtreme
 * @copyright  Copyright (C) 2013-2014 wpXtreme Inc. All Rights Reserved.
 * @date       2014-12-23
 * @version    1.0.0
 * @note       You can found the latest version of this file on https://gist.github.com/gfazioli/e4c4dafdc83768098781
 *
 */

// ---------------------------------------------------------------------------------------------------------------------
// Function wpxtreme_wp_kickstart
// ---------------------------------------------------------------------------------------------------------------------

if ( !function_exists( 'wpxtreme_wp_kickstart' ) ) {

  /**
   * Check environment and boot WPX plugin
   *
   * @brief Check environment and boot WPX plugin
   *
   * @param string $sMainFile           The main plugin file; usually `__FILE__`
   * @param string $wpxtreme_store_slug The original wpXtreme store slug. Eg. 'wpx-server_00006a'
   * @param string $sMainClassName      The main plugin class, eg: `WPXBannerize`
   * @param string $sMainClassFile      The main plugin filename, eg: `wpx-bannerize.php`
   * @param string $sClassParent        Optional. The name of parent plugin. Used in extension.
   */
  function wpxtreme_wp_kickstart( $sMainFile, $wpxtreme_store_slug, $sMainClassName, $sMainClassFile, $sClassParent = 'WPXtreme' )
  {
    // Check if get_plugins() function exists. This is required on the front end of the site, since it is in a file that is normally only loaded in the admin.
    if( !function_exists( 'get_plugin_data' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    $plugins = get_plugins();

    // Loop into the active plugins
    foreach( $plugins as $main_file => $plugin ) {
      if( $main_file == $wpxtreme_store_slug . '/' . 'main.php' && false !== strpos( $plugin[ 'PluginURI' ], '/wpxtre.me' ) ) {

        $fCheckWpxtremeGlobalInstance = create_function( '',
          '$GLOBALS["wpxtreme_notice_refers_to"] = "' . $sMainFile . '";' .
          'add_action( "admin_notices", "wpxtreme_wp_kickstart_light_version" );'
        );

        add_action( 'init', $fCheckWpxtremeGlobalInstance );

        return false;
      }
    }

    // Create function to boot plugin
    $fBoot = create_function( '', // Check all versions, and DO NOT PROCEED if some version is invalid
      'if( TRUE == wpxtreme_wp_kickstart_check_environment( \'' . $sMainFile . '\' )) {' .
      'require_once( trailingslashit( dirname( \'' . $sMainFile . '\' )) . basename( \'' . $sMainClassFile . '\' ));' .
      'if( !isset( $GLOBALS[\'' . $sMainClassName . '\'] ) ) {'.
      '$GLOBALS[\'' . $sMainClassName . '\'] = ' . $sMainClassName . '::boot( \'' . $sMainFile . '\' );' .
      'do_action( \'' . $sMainClassName . '\' ); } }' );

    // If I'm starting wpXtreme plugin, I immediately execute plugin boot
    // or, is it already booted the WPX plugin I belong to ?
    if ( isset( $GLOBALS[$sClassParent] ) || ( 'WPXtreme' == $sMainClassName ) ) {

      // In this case I can directly boot plugin
      $fBoot();
    }
    else {
      // I need to boot this plugin after the boot of WPX plugin father
      add_action( $sClassParent, $fBoot );
    }

    // Hook the 'wpxtreme_loaded' action ONLY if I'm not starting wpXtreme plugin.
    // For ALL other plugins: detect if WPXtreme is loaded in 'init' WP action
    if ( 'WPXtreme' != $sMainClassName ) {

      // Create function to check wpXtreme global instance
      // Must be dynamic because I need to store the main file of plugin
      // See internal comment on wpxtreme_wp_kickstart_not_loaded function
      $fCheckWpxtremeGlobalInstance = create_function( '',
        'if ( !isset( $GLOBALS[\'WPXtreme\'] ) ) {' . '$GLOBALS[\'wpxtreme_notice_refers_to\'] = \'' . $sMainFile .
        '\';' . 'add_action( \'admin_notices\', \'wpxtreme_wp_kickstart_not_loaded\' );' . '}' );

      add_action( 'init', $fCheckWpxtremeGlobalInstance );
    }
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Function wpxtreme_wp_kickstart_light_version
// ---------------------------------------------------------------------------------------------------------------------

if( !function_exists( 'wpxtreme_wp_kickstart_light_version' ) ) {

  /**
   * Display the warning plugin disable.
   *
   */
  function wpxtreme_wp_kickstart_light_version()
  {
    if( !function_exists( 'get_plugin_data' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    // Prepare slug
    $plugin_slug = trailingslashit( dirname( $GLOBALS[ 'wpxtreme_notice_refers_to' ] ) ) . 'main.php';

    // Here I can't use __FILE__, because it refers to the first require_once path of kickstart.php
    $result = get_plugin_data( $plugin_slug, false );

    // Used for jQuery scrollTo below
    $id = sanitize_title( $result['Title'] );

    // Apply some stye
    wpxtreme_wp_kickstart_styles( 'tr#' . $id . ' td,tr#' . $id . ' th {background-color:rgba(255,0,0,0.25)}');
    ?>
    <div class="wpxm-wp-kickstart-box">
      <h3>The <strong><?php echo $result[ 'Name' ] ?></strong> plugin has been <strong>disabled</strong> because you have installed the complete wpXtreme version. </h3>

      <h3>Thank you!</h3>

      <p>You can now <button class="button button-primary" onclick="jQuery('html, body').animate({ scrollTop: parseInt( jQuery('#<?php echo $id ?>').offset().top) - 64 }, 2000);">delete the light version</button> from your WordPress website</p>
    </div>
    <?php

    // Auto deactive plugin
    deactivate_plugins( $plugin_slug, true );
  }
}


// ---------------------------------------------------------------------------------------------------------------------
// Function wpxtreme_wp_kickstart_styles
// ---------------------------------------------------------------------------------------------------------------------

if ( !function_exists( 'wpxtreme_wp_kickstart_styles' ) ) {

  /**
   * Simple style for layouting.
   *
   * @param string $custom_style Optional. Additional styles. Used for disabled.
   */
  function wpxtreme_wp_kickstart_styles( $custom_style = '' )
  {
    ?><style type="text/css">.wpxm-wp-kickstart-box #icon-plugins{display:none}.wpxm-wp-kickstart-box{background-color:#7fa8cb;color:#fff;border:1px solid #7795b5;margin:40px 16px 16px 0;text-align:center;line-height:32px;-moz-border-radius:3px;-webkit-border-radius:3px;border-radius:3px}.wpxm-wp-kickstart-box p{font-size:16px;font-weight:lighter;margin:0 32px 16px}.wpxm-wp-kickstart-box h3,.wpxm-wp-kickstart-box h2{color:#fff;text-shadow:0 1px rgba(0,0,0,0.2);font-weight:lighter;font-size:20px;margin:16px}.wpxm-wp-kickstart-box h2{font-weight:bold;font-size:22px}.wpxm-wp-kickstart-box strong{font-weight:bold}.wpxm-wp-kickstart-box a,.wpxm-wp-kickstart-box a:link,.wpxm-wp-kickstart-box a:visited{color:#fff;text-decoration:none;text-shadow:none;border-bottom:2px solid #fff}.wpxm-wp-kickstart-box a:hover,.wpxm-wp-kickstart-box a:focus,.wpxm-wp-kickstart-box a:active{color:rgba(0,0,0,0.5);border-bottom-color:rgba(0,0,0,0.5)}.wpxm-wp-kickstart-box a.wpxm-kickstart-button,.wpxm-wp-kickstart-box a.wpxm-kickstart-button:link,.wpxm-wp-kickstart-box a.wpxm-kickstart-button:visited{background-color:rgba(0,50,100,0.3);border-radius:3px 3px 3px 3px;border:0;color:#fff;display:inline-block;font-size:14px;font-weight:bold;padding:12px;text-decoration:none;text-transform:uppercase}.wpxm-wp-kickstart-box a.wpxm-kickstart-button:hover,.wpxm-wp-kickstart-box a.wpxm-kickstart-button:focus,.wpxm-wp-kickstart-box a.wpxm-kickstart-button:active{background-color:rgba(0,50,100,0.5)}.wpxm-wp-kickstart-box small{font-size:12px;color:rgba(0,0,0,0.5)}<?php echo $custom_style ?></style><?php
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Function wpxtreme_wp_kickstart_environment_notices
// ---------------------------------------------------------------------------------------------------------------------

if ( !function_exists( 'wpxtreme_wp_kickstart_environment_notices' ) ) {

  /**
   * Internal function used to show a custom message in admin area. Engaged in case of error
   * in checking environment.
   *
   * @brief Show a custom message in admin area
   *
   */
  function wpxtreme_wp_kickstart_environment_notices()
  {

    global $wp_version, $wpdb;

    $plugin_name = $GLOBALS['wpxtreme_notification_data']['name'];

    $aNotice = array(
      'WPX_INVALID_WORDPRESS_VERSION' => sprintf( __( '<strong>%s</strong> cannot be used because is not compatible with your current <strong>WordPress version %s</strong> (<strong>minimum requirement: %s</strong>)' ), $plugin_name, $wp_version, $GLOBALS['wpxtreme_notification_data']['required_version'] ),
      'WPX_INVALID_PHP_VERSION'       => sprintf( __( '<strong>%s</strong> cannot be used because is not compatible with your current <strong>PHP language version %s</strong> (<strong>minimum requirement: %s</strong>)' ), $plugin_name, PHP_VERSION, $GLOBALS['wpxtreme_notification_data']['required_version'] ),
      'WPX_INVALID_MYSQL_VERSION'     => sprintf( __( '<strong>%s</strong> cannot be used because is not compatible with your current <strong>MySQL database version %s</strong> (<strong>minimum requirement: %s</strong>)' ), $plugin_name, $wpdb->db_version(), $GLOBALS['wpxtreme_notification_data']['required_version'] )
    );

    $aResolve = array(
      'WPX_INVALID_WORDPRESS_VERSION' => sprintf( __( 'To continue using <strong>%s</strong>, please upgrade the WordPress version to the minimum required version.' ), $plugin_name ),
      'WPX_INVALID_PHP_VERSION'       => sprintf( __( 'To continue using <strong>%s</strong>, please upgrade the PHP language version to the minimum required version.' ), $plugin_name ),
      'WPX_INVALID_MYSQL_VERSION'     => sprintf( __( 'To continue using <strong>%s</strong>, please upgrade the MySQL version to the minimum required version.' ), $plugin_name ),
    );

    if ( defined( 'WPXTREME_VERSION' ) ) {
      $aNotice['WPX_INVALID_WPXTREME_VERSION'] = sprintf( __( '<strong>%s</strong> cannot be used because is not compatible with your current <strong>wpXtreme framework version %s</strong> (<strong>minimum requirement: %s</strong>)' ), $plugin_name, WPXTREME_VERSION, $GLOBALS['wpxtreme_notification_data']['required_version'] );
      $aResolve['WPX_INVALID_WPXTREME_VERSION'] = sprintf( __( 'To continue using <strong>%s</strong>, please upgrade the wpXtreme framework version to the minimum required version.' ), $plugin_name );
    }

    wpxtreme_wp_kickstart_styles();
    ?>
    <div class="wpxm-wp-kickstart-box">
      <h3><?php echo $plugin_name; ?> - WARNING!</h3>
      <p><?php echo $aNotice[$GLOBALS['wpxtreme_notification_data']['type']]; ?></p>
      <p><?php echo $aResolve[$GLOBALS['wpxtreme_notification_data']['type']]; ?></p>
    </div>
  <?php
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Function wpxtreme_wp_kickstart_check_environment
// ---------------------------------------------------------------------------------------------------------------------

if ( !function_exists( 'wpxtreme_wp_kickstart_check_environment' ) ) {

  /**
   * Do all checks for minimum WP, PHP, MySQL and wpXtreme version needed by this plugin
   *
   * @brief Check environment
   *
   * @param string $sMainFile Main path file
   *
   * @return bool
   */
  function wpxtreme_wp_kickstart_check_environment( $sMainFile )
  {

    global $wp_version, $wpdb;

    // If I already have a version notice pending, don't reset array of data!
    if ( !isset( $GLOBALS['wpxtreme_notification_flag'] ) ) {
      $GLOBALS['wpxtreme_notification_data'] = array();
    }

    $aWPXMetadata = array(
      'Name'             => 'Plugin Name',
      'wpx_wpxtreme_min' => 'WPX wpXtreme Min',
      'wpx_wp_min'       => 'WPX WP Min',
      'wpx_php_min'      => 'WPX PHP Min',
      'wpx_mysql_min'    => 'WPX MySQL Min',
      'wpx_parent'       => 'WPX Parent Min',
    );

    $aWPXMetadata  = get_file_data( $sMainFile, $aWPXMetadata );

    // If wpXtreme framework needed for this plugin is greater than current wpXtreme in this system
    if ( !empty( $aWPXMetadata['wpx_wpxtreme_min'] ) && defined( 'WPXTREME_VERSION' ) ) {

      if ( true == version_compare( $aWPXMetadata['wpx_wpxtreme_min'], WPXTREME_VERSION, '>' ) ) {

        // this plugin can't be activated because wpXtreme is not updated to the minimum required version
        $GLOBALS['wpxtreme_notification_data'] = array(
          'name'             => $aWPXMetadata['Name'],
          'type'             => 'WPX_INVALID_WPXTREME_VERSION',
          'required_version' => $aWPXMetadata['wpx_wpxtreme_min']
        );

        // Notice on WP environment
        if ( !isset( $GLOBALS['wpxtreme_notification_flag'] ) ) {
          add_action( 'admin_notices', 'wpxtreme_wp_kickstart_environment_notices' );
          $GLOBALS['wpxtreme_notification_flag'] = true;
        }

        return false;
      }
    }

    // If WordPress needed for this plugin is greater than current WordPress in this system
    if ( !empty( $aWPXMetadata['wpx_wp_min'] ) ) {

      // if WordPress needed for this plugin is greater than actual WordPress in this system
      if ( true == version_compare( $aWPXMetadata['wpx_wp_min'], $wp_version, '>' ) ) {

        // this plugin can't be activated because WordPress is not updated to the minimum required version
        $GLOBALS['wpxtreme_notification_data'] = array(
          'name'             => $aWPXMetadata['Name'],
          'type'             => 'WPX_INVALID_WORDPRESS_VERSION',
          'required_version' => $aWPXMetadata['wpx_wp_min']
        );

        // Notice on WP environment
        if ( !isset( $GLOBALS['wpxtreme_notification_flag'] ) ) {
          add_action( 'admin_notices', 'wpxtreme_wp_kickstart_environment_notices' );
          $GLOBALS['wpxtreme_notification_flag'] = true;
        }

        return false;
      }
    }

    // If PHP needed for this plugin is greater than current PHP in this system
    if ( !empty( $aWPXMetadata['wpx_php_min'] ) ) {

      if ( true == version_compare( $aWPXMetadata['wpx_php_min'], PHP_VERSION, '>' ) ) {

        // this plugin can't be activated because PHP is not updated to the minimum required version
        $GLOBALS['wpxtreme_notification_data'] = array(
          'name'             => $aWPXMetadata['Name'],
          'type'             => 'WPX_INVALID_PHP_VERSION',
          'required_version' => $aWPXMetadata['wpx_php_min']
        );

        // Notice on WP environment
        if ( !isset( $GLOBALS['wpxtreme_notification_flag'] ) ) {
          add_action( 'admin_notices', 'wpxtreme_wp_kickstart_environment_notices' );
          $GLOBALS['wpxtreme_notification_flag'] = true;
        }

        return false;
      }
    }

    // If MySQL needed for this plugin is greater than current MySQL in this system
    if ( !empty( $aWPXMetadata['wpx_mysql_min'] ) ) {

      $mysql_version = $wpdb->db_version();
      if ( true == version_compare( $aWPXMetadata['wpx_mysql_min'], $mysql_version, '>' ) ) {

        // This plugin can't be activated because MySQL is not updated to the minimum required version
        $GLOBALS['wpxtreme_notification_data'] = array(
          'name'             => $aWPXMetadata['Name'],
          'type'             => 'WPX_INVALID_MYSQL_VERSION',
          'required_version' => $aWPXMetadata['wpx_mysql_min']
        );

        // Notice on WP environment
        if ( !isset( $GLOBALS['wpxtreme_notification_flag'] ) ) {
          add_action( 'admin_notices', 'wpxtreme_wp_kickstart_environment_notices' );
          $GLOBALS['wpxtreme_notification_flag'] = true;
        }

        return false;
      }
    }
    return true;
  }
}

// ---------------------------------------------------------------------------------------------------------------------
// Functions wpxtreme_wp_kickstart_not_loaded
// ---------------------------------------------------------------------------------------------------------------------

if ( !function_exists( 'wpxtreme_wp_kickstart_not_loaded' ) ) {

  /**
   * Display the admin notices when WPXtreme is NOT loaded
   *
   * @brief Admin notice
   */
  function wpxtreme_wp_kickstart_not_loaded()
  {
    global $status, $page, $s;

    // Subject
    $plugin_file = 'wpxtreme/main.php';

    // Action install
    if ( isset( $_GET['action'] ) && 'install_wpxtreme' == $_GET['action'] ) {
      if ( wp_verify_nonce( $_GET['_wpnonce'], 'wpxtreme' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
        require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
        $args         = array(
          'title'  => 'Installing wpXtreme Plugin',
          'nonce'  => '',
          'url'    => '',
          'type'   => 'web',
          'api'    => array(),
          'plugin' => $plugin_file
        );

        if( !class_exists( 'WPXTREME_KICKSTART_INSTALLER_SKIN', false ) ) {

          /**
           * @class WPXTREME_KICKSTART_INSTALLER_SKIN
           */
          final class WPXTREME_KICKSTART_INSTALLER_SKIN extends Plugin_Installer_Skin {

            /**
             * @brief Header
             */
            public function header()
            {
              wpxtreme_wp_kickstart_styles();
              echo '<div class="wpxm-wp-kickstart-box">';
              parent::header();
            }

            /**
             * @brief Close the footer
             */
            public function footer()
            {
              parent::footer();
              $foo = $this->upgrader->plugin_info();
              wp_cache_delete( 'plugins', 'plugins' );
              wp_cache_flush();
              printf( '<p><a href="%s" class="wpxm-kickstart-button">Continue</a></p>', self_admin_url() );
              $result = activate_plugin( $foo );
              wp_ob_end_flush_all();
              flush();
              ?>

              </div><?php
            }

            /**
             * @brief Override to avoid display
             */
            public function after() {}
          }
        }

        if( !class_exists( 'WPXTREME_KICKSTART_PLUGIN_UPGRADER', false ) ) {

          /**
           * @class WPXTREME_KICKSTART_PLUGIN_UPGRADER
           */
          final class WPXTREME_KICKSTART_PLUGIN_UPGRADER extends Plugin_Upgrader {

            /**
             * @brief Install strings
             */
            public function install_strings()
            {
              parent::install_strings();
              $this->strings['downloading_package'] = 'Downloading install package from WPX Server';
            }
          }
        }

        $install_skin = new WPXTREME_KICKSTART_INSTALLER_SKIN( $args );
        $installing   = new WPXTREME_KICKSTART_PLUGIN_UPGRADER( $install_skin );
        $installing->install( 'https://wpxtre.me/download' );
        return;
      }
    }

    if ( !function_exists( 'get_plugin_data' ) ) {
      require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    // Here I can't use __FILE__, because it refers to the first require_once path of kickstart.php
    $result = get_plugin_data( trailingslashit( dirname( $GLOBALS['wpxtreme_notice_refers_to'] ) ) . 'main.php', false );

    // wpXtreme could be installed but not active
    $wpxtreme_path   = trailingslashit( WP_PLUGIN_DIR ) . $plugin_file;
    $wpxtreme_exists = file_exists( $wpxtreme_path );
    $install_url     = wp_nonce_url( 'plugins.php?action=install_wpxtreme', 'wpxtreme' );
    $activate_url    = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_file . '&amp;plugin_status=' . $status . '&amp;paged=' . $page . '&amp;s=' . $s, 'activate-plugin_' . $plugin_file );

    wpxtreme_wp_kickstart_styles();
    ?>
    <div class="wpxm-wp-kickstart-box">
    <h3>The <strong><?php echo $result['Name'] ?></strong> plugin requires the <a href="https://wpxtre.me">wpXtreme Framework plugin</a> to run properly. </h3>

      <?php if ( $wpxtreme_exists ) : ?>
        <div><a href="<?php echo $activate_url ?>" class="wpxm-kickstart-button">Activate wpXtreme</a></div>
      <?php else: ?>
        <div><a href="<?php echo $install_url ?>" class="wpxm-kickstart-button">Download & Install wpXtreme</a></div>
        <p>wpXtreme plugin will be automatically activated after installation</p>
      <?php endif; ?>

      <small>Note: all plugins available in the WPX Store require the <strong>wpXtreme framework plugin</strong>. This is a one-off procedure.</small>
  </div>
  <?php
  }
}