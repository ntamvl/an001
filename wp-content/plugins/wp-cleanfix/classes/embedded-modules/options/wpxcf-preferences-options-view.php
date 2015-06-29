<?php
/**
 * CleanFix options preferences view
 *
 * @class           WPXCleanFixPreferencesOptionsView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-23
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferencesOptionsView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXCleanFixPreferencesOptionsView class
   *
   * @brief Construct
   *
   * @return WPXCleanFixPreferencesOptionsView
   */
  public function __construct()
  {
    $preferences = WPXCleanFixPreferences::init();
    parent::__construct( $preferences, 'options' );
  }

  /**
   * Return the array fields
   *
   * @brief Fields
   *
   * @param WPXCleanFixPreferencesOptions $options
   *
   * @return array|void
   */
  public function fields( $options )
  {
    $fields = array(
      __( 'Transients', WPXCLEANFIX_TEXTDOMAIN ) => array(

        array(
          array(
            'type'  => WPDKUIControlType::SWIPE,
            'name'  => WPXCleanFixPreferencesOptions::SAFE_MODE,
            'label' => __( 'Safe mode', WPXCLEANFIX_TEXTDOMAIN ),
            'value' => wpdk_is_bool( $options->safeMode ) ? 'on' : 'off'
          )
        ),

        array(
          array(
            'type'    => WPDKUIControlType::SELECT,
            'name'    => WPXCleanFixPreferencesOptions::EXPIRY_DATE,
            'label'   => __( 'Expiry date', WPXCLEANFIX_TEXTDOMAIN ),
            'options' => array(
              0               => __( 'Today', WPXCLEANFIX_TEXTDOMAIN ),
              DAY_IN_SECONDS  => __( 'Daily', WPXCLEANFIX_TEXTDOMAIN ),
              WEEK_IN_SECONDS => __( 'Weekly', WPXCLEANFIX_TEXTDOMAIN ),
            ),
            'value'   => $options->expiryDate
          )
        ),
      ),
    );

    return $fields;
  }

}