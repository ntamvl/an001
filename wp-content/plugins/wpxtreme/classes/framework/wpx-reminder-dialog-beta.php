<?php

/**
 * A predefined class for reminder dialog
 *
 * @class           WPXReminderDialogBeta
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-01-15
 * @version         1.0.0
 * @since           1.1.12
 *
 */
final class WPXReminderDialogBeta extends WPDKUIModalDialog implements IWPXReminderDialog {

  /**
   * Reminder instance
   *
   * @var WPXReminder $reminder
   */
  private $reminder;

  /**
   * WPX Store id
   *
   * @var string $wpxstore_id
   */
  private $wpxstore_id;

  /**
   * Alert content used in the footer feedback
   *
   * @var string $_alert_message
   */
  private $_alert_message = '';

  /**
   * Return a singleton instance of WPXReminderDialogBeta class
   *
   * @param WPXReminder $reminder Reminder instance
   *
   * @return WPXReminderDialogBeta
   */
  public static function init( $reminder )
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self( $reminder );
    }

    return $instance;
  }

  /**
   * Create an instance of WPXReminderDialogBeta class
   *
   * @param WPXReminder $reminder Reminder instance
   *
   * @return WPXReminderDialogBeta
   */
  public function __construct( $reminder )
  {
    $this->reminder = $reminder;

    /**
     * Filter the title of dialog.
     *
     * @param string $title Dialog title. Default 'Reminder'
     */
    $title = apply_filters( 'wpx_reminder_dialog_beta_title', __( 'Reminder', WPXTREME_TEXTDOMAIN ) );
    $id    = 'wpx_reminder_beta';

    parent::__construct( $id, $title );

    $this->dismissButton = false;
    $this->backdrop      = false;
  }

  /**
   * Fires on reminder
   */
  public function remind()
  {
    // Get the WPX Store ID
    $this->wpxstore_id = WPXtremePreferences::init()->wpxstore->user_id;

    // Witout the wpx store if the feedback can not link to a user
    if ( empty( $this->wpxstore_id ) ) {
      return;
    }

    add_action( 'wpdk_ui_modal_dialog_javascript_show-' . $this->id, array( $this, 'wpdk_ui_modal_dialog_javascript_show' ) );

    $this->open();
  }

  /**
   * Override buttons
   *
   * @return array
   */
  public function buttons()
  {
    $buttons = array(
      'issue-report' => array(
        'label' => __( 'Issue Report', WPXTREME_TEXTDOMAIN ),
        'class' => 'button-primary alignleft',
        'title' => __( 'Remember that the Issue Report can be started from wpXtreme menu or in footer admin area', WPXTREME_TEXTDOMAIN )
      ),
      'button_close' => array(
        'label'   => __( 'No, Thanks', WPXTREME_TEXTDOMAIN ),
        'dismiss' => true,
      ),
      'button_send'  => array(
        'label' => __( 'Yes, Send', WPXTREME_TEXTDOMAIN ),
        'class' => 'button-primary'
      )
    );

    return $buttons;
  }

  /**
   * Standard beta program content
   */
  public function content()
  {
    WPDKHTML::startCompress(); ?>
    <style type="text/css">
      #wpx_reminder_form_<?php echo $this->id ?> textarea
      {
        width  : 99%;
        height : 100px;
      }
    </style>
    <?php
    echo WPDKHTML::endCSSCompress();
    WPDKHTML::startCompress(); ?>
    <h3><?php printf( __( 'Hi, you have installed %s v%s as beta product %s ago', WPXTREME_TEXTDOMAIN ), $this->reminder->plugin->name, $this->reminder->plugin->version, human_time_diff( $this->reminder->timestamp ) ) ?></h3>
    <p><?php _e( 'Please, feel free to give us any your comments, suggestions or feedback?', WPXTREME_TEXTDOMAIN ) ?></p>
    <p><?php _e( 'If you get any issue run the Issue Report Tool by clicking the button below in order to send to us all information debug', WPXTREME_TEXTDOMAIN ) ?></p>
    <form id="wpx_reminder_form_<?php echo $this->id ?>" method="post" action="">
      <?php wp_nonce_field( 'wpx_reminder' ) ?>
      <input type="hidden" name="wpx_reminder_action" value="send_feedback_beta" />
      <input type="hidden" name="wpx_reminder_wpx_store_id" value="<?php echo $this->wpxstore_id ?>" />
      <div>
        <textarea name="wpx_reminder_beta_feedback" placeholder="<?php _e( 'You feedback...', WPXTREME_TEXTDOMAIN ) ?>"></textarea>
      </div>
    </form>
    <?php

    /**
     * Filter the dialog content.
     *
     * @param string $content The HTML markup for dialog content.
     */
    $content = apply_filters( 'wpx_reminder_beta_dialog_content', WPDKHTML::endHTMLCompress() );

    return $content;
  }

  /**
   * Fires when a modal dialog is show.
   *
   * The dynamic portion of the hook name, $id, refers to the modal dialod id.
   */
  public function wpdk_ui_modal_dialog_javascript_show()
  {
    ?>
    $( '#button_send' ).on( 'click', function() {
    var form = $( '#wpx_reminder_form_<?php echo $this->id ?>' );
    var text = form.find( 'textarea' ).val();
    if( empty( text ) ) {
    alert( '<?php _e( 'Please, write something...', WPXTREME_TEXTDOMAIN ) ?>' );
    return false;
    }
    form.submit();
    });
  <?php
  }

  /**
   * Process the post data
   */
  public function post_data()
  {
    // Send data
    $args = array(
      'issue_report[name]'        => 'Feedback',
      'issue_report[email]'       => sanitize_email( $_POST['wpx_reminder_wpx_store_id'] ),
      'issue_report[title]'       => sprintf( '%s %s v%s', __( 'Feedback for', WPXTREME_TEXTDOMAIN ), $this->reminder->plugin->name, $this->reminder->plugin->version ),
      'issue_report[description]' => ' ',
      'issue_report[report]'      => esc_attr( $_POST['wpx_reminder_beta_feedback'] ),
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

    $request  = wp_remote_request( WPXtremeIssueReport::DEVELOPER_CENTER_API_END_POINT, $params );
    $response = wp_remote_retrieve_response_code( $request );

    add_action( 'admin_footer', array( $this, 'admin_footer_alert' ) );

    if ( WPXtremeIssueReport::RESPONSE_SUCCESS == $response ) {
      // OK
      $this->_alert_message = __( 'Thank you very much! Your feedback has been successfully sent to wpXtreme Team!', WPXTREME_TEXTDOMAIN );
    }
    else {
      // Something wrong
      $this->_alert_message = __( 'Warning: Can\'t send your feedback to server! Retry later...', WPXTREME_TEXTDOMAIN );
    }
  }

  /**
   * Fires a simple alert to display a feedback
   */
  public function admin_footer_alert()
  {
    WPDKHTML::startCompress();
    ?>
    <script type="text/javascript">
 alert( '<?php echo $this->_alert_message ?>' );
</script>
    <?php echo WPDKHTML::endJavascriptCompress();
  }

}