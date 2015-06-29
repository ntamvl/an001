<?php

/**
 * Users clean & fix module
 *
 * @class           WPXCFUsersModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-01-08
 * @version         1.0.1
 *
 */
class WPXCFUsersModule extends WPXCleanFixModule {

  /**
   * Return a singleton instance of WPXCFUsersModule class
   *
   * @return WPXCFUsersModule
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
   * Create an instance of WPXCFUsersModule class
   *
   * @return WPXCFUsersModule
   */
  public function __construct()
  {
    parent::__construct( __( 'Users', WPXCLEANFIX_TEXTDOMAIN ) );
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
    $slots = array(
      'WPXCFUsersModuleOrphanUserMetaSlot',
      'WPXCFUsersModuleExpiredTransientSlot',
    );

    return $slots;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // SHARED METHODS - this method are use by several slot
  // -------------------------------------------------------------------------------------------------------------------


}

/**
 * Single slot
 *
 * @class           WPXCFUsersModuleOrphanUserMetaSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFUsersModuleOrphanUserMetaSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFUsersModuleOrphanUserMetaSlot class
   *
   * @param WPXCFUsersModule $module
   *
   * @return WPXCFUsersModuleOrphanUserMetaSlot
   */
  public static function init( $module )
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self( $module );
    }

    return $instance;
  }

  /**
   * Create an instance of WPXCFUsersModuleOrphanUserMetaSlot class
   *
   * @param WPXCFUsersModule $module
   *
   * @return WPXCFUsersModuleOrphanUserMetaSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Orphan user meta', WPXCLEANFIX_TEXTDOMAIN ), __( 'User Meta data not correctly assigned to a user. These are extra properties assigned to each user. It might be the case that some records are not assigned to a specific user.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $user_meta = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $user_meta ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan user meta data.', 'You have %s orphan user meta data.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $user_meta, array(
        'meta_key' => '%s',
        'number'   => ' (%s)'
      ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to safely and permanently delete them.', WPXCLEANFIX_TEXTDOMAIN );
    }
    return $this->response;
  }

  /**
   * Clean or Fix process.
   *
   * @return WPXCleanFixModuleResponse
   */
  public function cleanFix()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<< SQL
DELETE user_meta
FROM {$wpdb->usermeta} user_meta
LEFT JOIN {$wpdb->users} users ON ( users.ID = user_meta.user_id )
WHERE users.ID IS NULL
SQL;

    $wpdb->query( $sql );

    return $this->check();
  }

  /**
   * Return all user meta (id, key) without a valued user id linked.
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<< SQL
SELECT DISTINCT( COUNT(*) ) AS number, user_meta.umeta_id, user_meta.meta_key
FROM {$wpdb->usermeta} user_meta
LEFT JOIN {$wpdb->users} users ON ( users.ID = user_meta.user_id )
WHERE 1
AND users.ID IS NULL
GROUP BY user_meta.meta_key
ORDER BY user_meta.meta_key
SQL;

    return $wpdb->get_results( $sql );
  }

}

/**
 * Single slot
 *
 * @class           WPXCFUsersModuleExpiredTransientSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-15
 * @version         1.0.0
 *
 */
class WPXCFUsersModuleExpiredTransientSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFUsersModuleExpiredTransientSlot class
   *
   * @param WPXCFUsersModule $module
   *
   * @return WPXCFUsersModuleExpiredTransientSlot
   */
  public static function init( $module )
  {
    static $instance = null;
    if ( is_null( $instance ) ) {
      $instance = new self( $module );
    }

    return $instance;
  }

  /**
   * Create an instance of WPXCFUsersModuleExpiredTransientSlot class
   *
   * @param WPXCFUsersModule $module
   *
   * @return WPXCFUsersModuleExpiredTransientSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Expired Transients', WPXCLEANFIX_TEXTDOMAIN ), __( 'Users Transients data are extensions of the WPDK and they are temporary values stored in the user meta database table. When a transient expires you can safely remove it.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $transients = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $transients ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s expired transient.', 'You have %s expired transients.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $transients, array(
        'transient_name' => '%s',
        'expired'        => '(%s)'
      ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete your expired transients.', WPXCLEANFIX_TEXTDOMAIN );
    }
    return $this->response;
  }

  /**
   * Clean or Fix process.
   *
   * @return WPXCleanFixModuleResponse
   */
  public function cleanFix()
  {
    $expired = $this->_check();

    // Loop into the expired user transients in safe mode for delete them
    foreach ( $expired as $transient ) {
      WPDKUser::getTransientWithUser( $transient->transient_name, $transient->user_id );
    }

    return $this->check();
  }

  /**
   * Return (via SQL) the list of expired user transient
   *
   * @return array
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT umeta_id, user_id, meta_key,
REPLACE( meta_key, '_transient_timeout_', '' ) AS transient_name,
meta_value AS expired,
FROM_UNIXTIME( meta_value ) AS readable

FROM {$wpdb->usermeta}

WHERE meta_key LIKE '_transient\_timeout\_%'

AND meta_value < UNIX_TIMESTAMP(NOW() )

ORDER BY expired, transient_name
SQL;

    return $wpdb->get_results( $sql );

  }

}
