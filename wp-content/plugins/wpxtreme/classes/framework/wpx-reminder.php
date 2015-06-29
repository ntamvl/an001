<?php

/**
 * Interface definition for reminder dialog
 *
 * @interface       IWPXReminderDialog
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-03-21
 * @version         1.0.0
 * @since           1.2.2
 */
interface IWPXReminderDialog {

  /*
   * Use a input type hidden with value this constant to trigger the post_data() method.
   * In your form (must use POST verb) you have to insert the NONCE fields too as shown below
   *
   *     <input type="hidden" name="<?php echo self::ACTION_POST_DATA ?>" value="..." />
   *
   * In your method `post_data()` you will handle the actions
   */
  const ACTION_POST_DATA = 'wpx_reminder_action';

  // If you like support a form, use `wp_nonce_field( self::NONCE_FIELD )`
  const NONCE_FIELD = 'wpx_reminder';

  /**
   * Return a singleton instance of IWPXReminderDialog class
   *
   * @param WPXReminder $reminder Reminder instance
   *
   * @return IWPXReminderDialog
   */
  public static function init( $reminder );

  /**
   * Create an instance of IWPXReminderDialog class
   *
   * @param WPXReminder $reminder Reminder instance
   *
   * @return IWPXReminderDialog
   */
  public function __construct( $reminder );

  /**
   * This method is called when the reminder is trigged. If your class extends a WPDKUIModalDialog you can open
   * (display) the dialog. However, you can do whatever you want in the implementation of this method.
   */
  public function remind();

  /**
   * Process the post data
   */
  public function post_data();

}

/**
 * Useful class with predefinited interval
 *
 * @class           WPXReminderInterval
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-01-16
 * @version         1.0.0
 *
 */
class WPXReminderInterval {

  // Remap useful WordPress defines
  const MINUTE_IN_SECONDS = MINUTE_IN_SECONDS;
  const HOUR_IN_SECONDS = HOUR_IN_SECONDS;
  const DAY_IN_SECONDS = DAY_IN_SECONDS;
  const WEEK_IN_SECONDS = WEEK_IN_SECONDS;

  // Useful constants
  const THREE_DAYS_IN_SECONDS = 259200;
  const FIFTY_DAYS_IN_SECONDS = 1296000;
}

/**
 * This class manage the rating/comment or beta for a product
 *
 * @class           WPXReminder
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-04-04
 * @version         1.0.1
 * @since           1.1.12
 *
 */
class WPXReminder extends WPDKObject {

  // Prefix for option
  const CHECK = 'wpx_reminder';

  /**
   * Reminder ID
   *
   * @var string $id
   */
  public $id = '';

  /**
   * Modal Dialog class
   *
   * @var string $type
   */
  public $dialog = 'WPXReminderDialogBeta';

  /**
   * Instance of WPXPlugin class
   *
   * @var WPXPlugin $plugin
   */
  public $plugin;

  /**
   * Timestamp first activation
   *
   * @var int $timestamp
   */
  public $timestamp = 0;
  /**
   * Timeout
   *
   * @var int $timeout
   */
  public $timeout = 0;

  /**
   * Option key
   *
   * @var string $_option_key
   */
  private $_option_key = '';

  /**
   * Create an instance of WPXReminder class
   *
   * @param WPXPlugin $plugin
   * @param string    $id      Reminder ID
   * @param string    $dialog  Optional. Reminder class name dialog
   * @param int       $timeout Optional. Timeout between reminders
   *
   * @return WPXReminder
   */
  public static function init( $plugin, $id, $dialog = 'WPXReminderDialogBeta', $timeout = 0 )
  {
    return new self( $plugin, $id, $dialog, $timeout );
  }

  /**
   * Create an instance of WPXReminder class
   *
   * @param WPXPlugin $plugin
   * @param string    $id      Reminder ID
   * @param string    $dialog  Optional. Reminder class name dialog
   * @param int       $timeout Optional. Timeout between reminders
   *
   * @return WPXReminder
   */
  public function __construct( $plugin, $id, $dialog = 'WPXReminderDialogBeta', $timeout = 0 )
  {
    $this->plugin      = $plugin;
    $this->id          = sprintf( '%s-%s', sanitize_title( $id ), $plugin->slug );
    $this->_option_key = sprintf( '%s_%s', self::CHECK, $this->id );
    $this->dialog      = $dialog;
    $this->timeout     = $timeout;

    // Check for post data
    if( isset( $_POST['wpx_reminder_action'] ) ) {
      check_admin_referer( 'wpx_reminder' );

      // Ask to dialog to process post data
      if ( isset( $this->dialog ) && is_string( $this->dialog ) && class_exists( $this->dialog ) && method_exists( $this->dialog, 'post_data' ) ) {
        $class_name = $this->dialog;
        // Fixed Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM
        $func = create_function( '$a', $class_name . '::init( $a )->post_data();' );
        $func( $this );
        //$class_name::init( $this )->post_data();
      }
    }

    $this->timestamp = get_site_option( $this->_option_key );
    if ( empty( $this->timestamp ) ) {
      update_site_option( $this->_option_key, time() );
    }
    else {
      add_action( 'admin_footer', array( $this, 'admin_footer' ) );
    }
  }

  /**
   * Check for reminder
   */
  public function admin_footer()
  {

    if ( empty( $this->timeout ) ) {
      return;
    }

    // Elapse time?
    if ( time() - $this->timestamp > $this->timeout ) {

      if ( isset( $this->dialog ) && is_string( $this->dialog ) && class_exists( $this->dialog ) && method_exists( $this->dialog, 'remind' ) ) {

        /**
         * Fires before a reminder is draw in footer
         *
         * @param WPXReminder $reminder An instance of WPXReminder class
         */
        do_action( 'wpx_reminder_before_' . $this->id, $this );

        // Updated time
        update_site_option( $this->_option_key, time() );
        $class_name = $this->dialog;
        // Fixed Parse error: syntax error, unexpected T_PAAMAYIM_NEKUDOTAYIM
        $func = create_function( '$a', $class_name . '::init( $a )->remind();' );
        $func( $this );
        //$class_name::init( $this )->remind();
      }
    }
  }
}
