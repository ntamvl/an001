<?php

/**
 * Header View for WPX Store User
 *
 * @class           WPXStoreUserBarView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-04
 * @version         1.0.0
 *
 */
class WPXStoreUserBarView extends WPDKView {

  const ID = 'wpxm-store-user-bar';

  /**
   * wpXtreme API
   *
   * @var WPXtremeAPI $api
   */
  public $api;

  /**
   * Return a singleton instance of WPXStoreUserBarView class
   *
   * @return WPXStoreUserBarView
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
   * Create an instance of WPXStoreUserBarView class
   *
   * @return WPXStoreUserBarView
   */
  public function __construct()
  {
    // Init the wpXtreme API
    $this->api = WPXtremeAPI::init();

    parent::__construct( self::ID, 'tablenav top clearfix' );

    // Include 'wpxm-store-user-bar.css'
    wp_enqueue_style( self::ID, WPXTREME_URL_CSS . self::ID . '.css', false, WPXTREME_VERSION );
  }

  /**
   * Display
   */
  public function draw()
  {
    // Get the wpx store id
    $wpxstore_id = WPXtremePreferences::init()->wpxstore->user_id;

    if ( empty( $wpxstore_id ) ) : ?>
      <img style="border:none;" src="<?php echo WPXTREME_URL_CSS ?>images/icon-store-plugins-64x64.png" class="alignleft" alt="WPX Store" />
      <h3><?php _e( 'Warning!' ) ?></h3>
      <p><?php _e( 'You are not logged in WPX Store, please' ) ?> <a class="button button-primary" href="<?php echo add_query_arg( 'page', 'wpxtreme-preferences', admin_url( 'options-general.php') ) ?>"><?php _e( 'Signin by Preferences' ) ?></a></p>

    <?php else:

      // Get the token
      $token = WPXtremePreferences::init()->wpxstore->token;

      if( empty( $token ) ) : ?>

        <h2><?php _e( 'You have to login' ) ?></h2>

      <?php else : ?>

        <?php
        // Get current user info
        $user = WPXtremeAPI::init()->user();

        // Check user, could be null
        if( empty( $user ) ) : ?>

          <img style="border:none;" src="<?php echo WPXTREME_URL_CSS ?>images/icon-store-plugins-64x64.png" class="alignleft" alt="WPX Store" />
          <h3><?php _e( 'Warning!' ) ?></h3>
          <p><?php _e( 'You are not logged in WPX Store, please' ) ?> <a class="button button-primary" href="<?php echo add_query_arg( 'page', 'wpxtreme-preferences', admin_url( 'options-general.php') ) ?>"><?php _e( 'Signin by Preferences' ) ?></a></p>

        <?php
        //WPXtreme::log( $user, '$user' );

        /*
         * eg:
         *
         *     object(stdClass)#2518 (3) {
         *      ["display_name"]=> string(22) "Giovambattista Fazioli"
         *      ["user_registered"]=> string(19) "2012-05-09 08:17:34"
         *      ["membership"]=> object(stdClass)#2517 (10) {
         *        ["id"]=> string(1) "1"
         *        ["start_date"]=> string(19) "2014-05-22 15:41:08"
         *        ["id_user"]=> string(1) "2"
         *        ["id_membership"]=> string(1) "6"
         *        ["id_product"]=> string(4) "3504"
         *        ["id_order"]=> string(1) "1"
         *        ["previous_role"]=> string(13) "administrator"
         *        ["status"]=> string(7) "current"
         *        ["post_title"]=> string(10) "Plan Month"
         *        ["expiry_date"]=> string(19) "2014-05-29 15:41:08"
         *      }
         *    }
         */

        ?>
      <?php else : ?>

      <?php echo get_avatar( $wpxstore_id, 64 ); ?>

      <h3 class="wpxm-store-wpxstore-id">
        <?php echo $user->display_name ?> <a class="button button-primary alignright" href="https://wpxtre.me/profile"><?php _e( 'Edit your Profile' ) ?></a>
      </h3>

      <p class="wpxm-store-wpxstore-id">
        <span class="wpxm-store-info wpxm-store-member"><?php _e( 'User from' ); printf( ' %s', human_time_diff( strtotime( $user->user_registered ) ) ); ?></span>

        <?php
        // Display memberships if exists
        if( empty( $user->membership ) ) : ?>
          <span class="wpxm-store-info wpxm-store-memberships-noactive"><?php _e( 'No active Subscription found' ) ?></span>
          <a class="button button-primary" href="https://wpxtre.me/pricing"><?php _e( 'Get Subscription' ) ?></a>

        <?php else : ?>
          <span class="wpxm-store-info wpxm-store-memberships-active"><?php printf( '%s <strong>%s</strong>', __( 'Membership' ), $user->membership->post_title ); ?></span>
          <span class="wpxm-store-info wpxm-store-memberships-expiry"><?php printf( '%s %s <i>( %s )</i>',  __( 'expires in' ), human_time_diff( strtotime( $user->membership->expiry_date ) ), date( 'j M, Y', strtotime( $user->membership->expiry_date ) ) ); ?></span>
        <?php endif; ?>

      </p>

        <?php endif; // if( empty( $user ) )
      endif; // if( empty( $token ) )
    endif; // if ( empty( $wpxstore_id ) )
  }

}