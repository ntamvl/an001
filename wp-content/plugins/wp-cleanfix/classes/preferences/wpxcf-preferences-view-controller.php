<?php

/**
 * CleanFix preferences view controller
 *
 * @class           WPXCleanFixPreferencesViewController
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-23
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferencesViewController extends WPDKPreferencesViewController {

  /**
   * Return a singleton instance of WPXCleanFixPreferencesViewController class
   *
   * @brief Singleton
   *
   * @return WPXCleanFixPreferencesViewController
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
   * Create an instance of WPXCleanFixPreferencesViewController class
   *
   * @brief Construct
   *
   * @return WPXCleanFixPreferencesViewController
   */
  public function __construct()
  {
    $general = new WPXCleanFixPreferencesGeneralView();

    // Create each single tab
    $tabs = array(
      new WPDKjQueryTab( $general->id, __( 'General', WPXCLEANFIX_TEXTDOMAIN ), $general->html() ),
    );

    /**
     * Filter the additional tabs
     *
     * @param array $tabs Additiona array tabs.
     */
    $extends_tabs = apply_filters( 'wpxcf_preferences_tabs', array() );

    // Add the extensions preferences tabs
    foreach ( $extends_tabs as $view_class_name => $title ) {
      $view   = new $view_class_name;
      $tabs[] = new WPDKjQueryTab( $view->id, $title, $view->html() );
    }

    parent::__construct( WPXCleanFixPreferences::init(), __( 'CleanFix preferences', WPXCLEANFIX_TEXTDOMAIN ), $tabs );
  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @since WP 2.6.0
   */
  public function admin_print_styles()
  {
    wp_enqueue_style( 'wpxcf-admin', WPXCLEANFIX_URL_CSS . 'wpxcf-admin.css', array(), WPXCLEANFIX_VERSION );
  }

}

/**
 * CleanFix general view preferences
 *
 * @class           WPXCleanFixPreferencesGeneralView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-20
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferencesGeneralView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXCleanFixPreferencesGeneralView class
   *
   * @brief Construct
   *
   * @return WPXCleanFixPreferencesGeneralView
   */
  public function __construct()
  {
    $preferences = WPXCleanFixPreferences::init();
    parent::__construct( $preferences, 'general' );
  }

  /**
   * Return the array fields
   *
   * @brief Fields
   *
   * @param WPDKPreferencesBranch $branch
   *
   * @return array|void
   */
  public function fields( $branch )
  {
    $fields = array(
      __( 'Information', WPXCLEANFIX_TEXTDOMAIN ) => array(
        array(
          array(
            'type'           => WPDKUIControlType::ALERT,
            'alert_type'     => WPDKUIAlertType::INFORMATION,
            'dismiss_button' => false,
            'value'          => __( 'This preferences view can be extended by other CleanFix plugin extensions.', WPXCLEANFIX_TEXTDOMAIN )
          )
        ),
      )
    );

    return $fields;
  }

  /**
   * Override standard method to hidden buttons for reset and update.
   * Used to Hidden buttons.
   */
  public function buttonsUpdateReset() { }

}