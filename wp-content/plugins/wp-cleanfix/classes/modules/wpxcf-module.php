<?php

/**
 * This is the model of CleanFix module
 *
 * @class           WPXCleanFixModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-01
 * @version         1.0.0
 *
 */
class WPXCleanFixModule {

  /**
   * Unique module id.
   *
   * @var string $id .
   */
  public $id = '';

  /**
   * Name of module, used as title in the metabox.
   *
   * @var string $name
   */
  public $name = '';

  /**
   * Set to TRUE when the check process find one or more issues.
   *
   * @var bool $has_issues
   */
  public $has_issues = false;

  /**
   * Count of issues for this module. This value is the sum of single slot issues.
   *
   * @var int $issues
   */
  public $issues = 0;

  /**
   * Create an instance of WPXCleanFixModule class.
   *
   * @param $name Optional. Name of module, used as title in the metabox. Default empty.
   *
   * @return WPXCleanFixModule
   */
  public function __construct( $name = '' )
  {
    // Set the name/title.
    $this->name = $name;

    // Set an unique module id.
    $this->id = sanitize_title( get_class( $this ) );

    // Get the slots.
    $this->slots();
  }

  /**
   * Return the list of slots.
   *
   * $slots = array(
   *  'ClassName',
   *  ...
   * );
   *
   */
  public function slots()
  {
    // To override
    return array();
  }

  /**
   * Return an instance of single slot. Return FALSE if the slot can not be instance or not found.
   *
   * @param string $slot Any slot id of class name.
   *
   * @return WPXCleanFixSlot|bool
   */
  public function initSlot( $slot )
  {
    // Get slot list
    $slots = $this->slots();

    // Stability
    if ( empty( $slots ) ) {
      return false;
    }

    // Sanitize id
    $slot = sanitize_title( $slot );

    // Loop into the class
    foreach ( $slots as $class_name ) {

      // Sanitize class name
      $id = sanitize_title( $class_name );

      if ( $id == $slot ) {
        return call_user_func( array( $class_name, 'init' ), $this );
      }
    }

    return false;
  }

  /**
   * Ask to all slot to check and set `issues` and `has_issues` properties.
   * This method works as `check()` for single slot.
   */
  public function check()
  {
    // Get slot list
    $slots = $this->slots();

    // Stability
    if ( empty( $slots ) ) {
      return false;
    }

    // Clear counter
    $this->has_issues = false;
    $this->issues     = 0;

    // Loop into the class
    foreach ( $slots as $class_name ) {

      /**
       * @var WPXCleanFixSlot $instance
       */
      $instance = call_user_func( array( $class_name, 'init' ), $this );
      $instance->check();

      $this->issues += $instance->issues;
    }

    $this->has_issues = ! empty( $this->issues );
  }

}


/**
 * This is a single slot for a module.
 *
 * @class           WPXCleanFixSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-10-02
 * @version         1.0.0
 *
 */
class WPXCleanFixSlot {

  /**
   * Module id.
   *
   * @var string $module_id
   */
  public $module_id = '';

  /**
   * Slot id.
   *
   * @var string $id
   */
  public $id = '';

  /**
   * Title of slot.
   *
   * @var string $title
   */
  public $title = '';

  /**
   * Short description of slot.
   *
   * @var string $description
   */
  public $description = '';

  /**
   * Set to TRUE when the check process find one or more issues.
   *
   * @var bool $has_issues
   */
  public $has_issues = false;

  /**
   * Count of issues for this slot.
   *
   * @var int $issues
   */
  public $issues = 0;

  /**
   * An instance of WPXCleanFixModuleResponse class.
   *
   * @var WPXCleanFixModuleResponse $response
   */
  public $response;

  /**
   * Create an instance of WPXCleanFixSlot class
   *
   * @param WPXCleanFixModule $module      Module instance.
   * @param string            $title       Slot title.
   * @param string            $description Optional. Short description.
   *
   * @return WPXCleanFixSlot
   */
  public function __construct( $module, $title, $description = '' )
  {
    $this->module      = $module;
    $this->id          = sanitize_title( get_class( $this ) );
    $this->title       = $title;
    $this->description = $description;
    $this->response    = new WPXCleanFixModuleResponse;
  }

  /**
   * Set the total issues found and the `has_issues` bool flag and return the count of issues.
   *
   * @param int $issues Total issues.
   *
   * @return int
   */
  public function issues( $issues )
  {
    $this->issues     = $issues;
    $this->has_issues = ! empty( $issues );

    return $issues;
  }

  /**
   * Refresh/Check process
   */
  public function check()
  {
    die( __METHOD__ . ' must be override in your subclass' );;
  }

  /**
   * Clean or Fix process.
   */
  public function cleanFix()
  {
    die( __METHOD__ . ' must be override in your subclass' );
  }
}

/**
 * Status response constants
 *
 * @class              WPXCleanFixModuleResponseStatus
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-07-18
 * @version            1.0.0
 *
 */
class WPXCleanFixModuleResponseStatus {

  // Status OK
  const OK = 'ok';

  // Status warning
  const WARNING = 'warning';
}


/**
 * Standard (mandatory) class to response when a process check has been performed. All slot must be return this class.
 *
 * @class              WPXCleanFixModuleResponse
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-07-18
 * @version            1.0.0
 *
 */
class WPXCleanFixModuleResponse {

  /**
   * The status of response
   *
   * @var string $status
   */
  public $status = '';

  /**
   * Description of the status
   *
   * @var string $description
   */
  public $description = '';

  /**
   * More detail for the status response
   *
   * @var WPDKView $detail
   */
  public $detail;

  /**
   * Clean or Fix button.
   *
   * @var WPXCleanFixButtonFixControl $cleanFix
   */
  public $cleanFix;

  /**
   * Create an instance of WPXCleanFixModuleResponse class
   *
   * @param string                             $status      The status ID
   * @param string                             $description Optional. Status description
   * @param string|WPDKView                    $detail      Optional. Detail view
   * @param string|WPXCleanFixButtonFixControl $fix         Optional. Fix button control
   *
   * @return WPXCleanFixModuleResponse
   */
  public function __construct( $status = WPXCleanFixModuleResponseStatus::OK, $description = '', $detail = '', $fix = '' )
  {

    if ( empty( $description ) ) {
      if ( WPXCleanFixModuleResponseStatus::OK === $status ) {
        $description = __( 'All works great.', WPXCLEANFIX_TEXTDOMAIN );
      }
    }

    $this->status      = $status;
    $this->detail      = $detail;
    $this->cleanFix    = $fix;
    $this->description = $description;
  }

  /**
   * Return TRUE if a status is warning
   *
   * @return bool
   */
  public function isWarning()
  {
    return ( WPXCleanFixModuleResponseStatus::WARNING === $this->status );
  }

}