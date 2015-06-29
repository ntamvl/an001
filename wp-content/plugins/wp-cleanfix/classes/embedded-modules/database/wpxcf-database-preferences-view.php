<?php
/**
 * CleanFix database preferences view
 *
 * @class           WPXCleanFixPreferencesDatabaseView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-20
 * @version         1.0.0
 *
 */
class WPXCleanFixPreferencesDatabaseView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXCleanFixPreferencesDatabaseView class
   *
   * @brief Construct
   *
   * @return WPXCleanFixPreferencesDatabaseView
   */
  public function __construct()
  {
    $preferences = WPXCleanFixPreferences::init();
    parent::__construct( $preferences, 'database' );
  }

  /**
   * Return the array fields
   *
   * @brief Fields
   *
   * @param WPXCleanFixPreferencesDatabase $database
   * @return array|void
   */
  public function fields( $database )
  {

    $fields = array(
      __( 'Optimization', WPXCLEANFIX_TEXTDOMAIN ) => array(

        __( 'The InnoDB tables could not be optimized on some MySQL version / Server.', WPXCLEANFIX_TEXTDOMAIN ),
        array(
          array(
            'type'  => WPDKUIControlType::SWIPE,
            'name'  => WPXCleanFixPreferencesDatabase::IGNORE_INNODB,
            'label' => __( 'Ignore INNODB', WPXCLEANFIX_TEXTDOMAIN ),
            'value' => wpdk_is_bool( $database->ignoreInnoDB ) ? 'on' : 'off'
          )
        ),

        __( 'Once the optimization process ends, you are able the reset the AUTO_INCREMENT index function of the tables.', WPXCLEANFIX_TEXTDOMAIN ),
        array(
          array(
            'type'  => WPDKUIControlType::SWIPE,
            'name'  => WPXCleanFixPreferencesDatabase::RESET_AUTO_INCREMENT,
            'label' => __( 'Reset AUTO_INCREMENT', WPXCLEANFIX_TEXTDOMAIN ),
            'value' => wpdk_is_bool( $database->resetAutoIncrement ) ? 'on' : 'off'
          )
        ),
      ),
    );

    return $fields;
  }

}