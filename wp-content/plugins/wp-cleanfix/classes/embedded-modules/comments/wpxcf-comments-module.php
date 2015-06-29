<?php

/**
 * Comments module model
 *
 * @class           WPXCFCommentsModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-09-13
 * @version         1.0.0
 *
 */
class WPXCFCommentsModule extends WPXCleanFixModule {

  /**
   * Return a singleton instance of WPXCFCommentsModule class
   *
   * @return WPXCFCommentsModule
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
   * Create an instance of WPXCFCommentsModule class
   *
   * @return WPXCFCommentsModule
   */
  public function __construct()
  {
    parent::__construct( __( 'Comments', WPXCLEANFIX_TEXTDOMAIN ) );
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
      'WPXCFCommentsModuleUnapprovedSlot',
      'WPXCFCommentsModuleTrashSlot',
      'WPXCFCommentsModuleSpamSlot'
    );

    return $slots;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // SHARED METHODS - this method are use by several slot
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Select all comments by `approved` table field.
   *
   * @param string $approved Optional. The `approved` field: '0', '1', 'trash', 'spam'
   *
   * @return mixed
   */
  public function commentsWithApproved( $approved = '0' )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT

IF( LENGTH( comment_author ) > 24,
    CONCAT( SUBSTRING( TRIM( comment_author ), 1, 24 ), '...' ),
    TRIM( comment_author )
) AS comment_author,

IF( LENGTH( comment_content ) > 50,
    CONCAT( SUBSTRING( TRIM( comment_content ), 1, 40 ), '...' ),
    TRIM( comment_content )
) AS comment_content

FROM {$wpdb->comments}
WHERE comment_approved = '{$approved}'
ORDER BY comment_ID
SQL;

    $result = $wpdb->get_results( $sql );
    foreach ( $result as $row ) {
      $row->comment_content = esc_attr( preg_replace( '/[^[:print:]]/', '', strip_shortcodes( strip_tags( $row->comment_content ) ) ) );
      if ( strlen( $row->comment_content ) > 40 ) {
        $row->comment_content = substr( $row->comment_content, 0, 40 ) . '...';
      }
      elseif ( empty( $row->content ) ) {
        $row->comment_content = '...';
      }
    }

    return $result;

  }

  /**
   * Delete (permanently) all comments with a specific `comment_approved`.
   *
   * @param string $approved Optional. The `approved` field: '0', '1', 'trash', 'spam'
   *
   * @return mixed
   */
  public function deleteCommentsWithApproved( $approved = '0' )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
DELETE FROM {$wpdb->comments}
WHERE comment_approved = '{$approved}'
SQL;

    return $wpdb->query( $sql );
  }

}

/**
 * Unapproved commnets slot
 *
 * @class           WPXCFCommentsModuleUnapprovedSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-09-13
 * @version         1.0.0
 *
 */
class WPXCFCommentsModuleUnapprovedSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFCommentsModuleUnapprovedSlot class
   *
   * @param WPXCFCommentsModule $module
   *
   * @return WPXCFCommentsModuleUnapprovedSlot
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
   * Create an instance of WPXCFCommentsModuleUnapprovedSlot class
   *
   * @param WPXCFCommentsModule $module
   *
   * @return WPXCFCommentsModuleUnapprovedSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Unapproved', WPXCLEANFIX_TEXTDOMAIN ), __( 'Comments unapproved', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    // Use utility method
    $unapproved = $this->module->commentsWithApproved( '0' );

    // Get/Set issues
    $issues = $this->issues( count( $unapproved ) );

    if ( ! empty( $issues ) ) {
      $this->response->status      = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description = sprintf( _n( 'You have %s comment unapproved.', 'You have %s comments unapproved.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail      = new WPXCleanFixSelectControl( $unapproved, array(
        'comment_author'  => '(%s)',
        'comment_content' => '%s'
      ) );
      $this->response->cleanFix    = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->confirm();
      $this->response->cleanFix->title = __( 'Fix: click here to delete your unapproved comments.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deleteCommentsWithApproved( '0' );

    return $this->check();
  }


}


/**
 * Trash commnets slot
 *
 * @class           WPXCFCommentsModuleTrashSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-09-13
 * @version         1.0.0
 *
 */
class WPXCFCommentsModuleTrashSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFCommentsModuleTrashSlot class
   *
   * @param WPXCFCommentsModule $module
   *
   * @return WPXCFCommentsModuleTrashSlot
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
   * Create an instance of WPXCFCommentsModuleTrashSlot class
   *
   * @param WPXCFCommentsModule $module
   *
   * @return WPXCFCommentsModuleTrashSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Trash', WPXCLEANFIX_TEXTDOMAIN ), __( 'Comments trash', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {

    $trash = $this->module->commentsWithApproved( 'trash' );

    // Get/Set issues
    $issues = $this->issues( count( $trash ) );

    if ( ! empty( $issues ) ) {
      $this->response->status      = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description = sprintf( _n( 'You have %s comment in your trash.', 'You have %s comments in your trash.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail      = new WPXCleanFixSelectControl( $trash, array( 'comment_author'  => '(%s)',
                                                                                  'comment_content' => '%s'
        ) );
      $this->response->cleanFix    = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->confirm();
      $this->response->cleanFix->title = __( 'Fix: click here to delete all comments in your trash.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deleteCommentsWithApproved( 'trash' );

    return $this->check();
  }

}


/**
 * Single slot
 *
 * @class           WPXCFCommentsModuleSpamSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-09-13
 * @version         1.0.0
 *
 */
class WPXCFCommentsModuleSpamSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFCommentsModuleSpamSlot class
   *
   * @param WPXCFCommentsModule $module
   *
   * @return WPXCFCommentsModuleSpamSlot
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
   * Create an instance of WPXCFCommentsModuleSpamSlot class
   *
   * @param WPXCFCommentsModule $module
   *
   * @return WPXCFCommentsModuleSpamSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Spam', WPXCLEANFIX_TEXTDOMAIN ), __( 'These comments are marked as spam.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $spam = $this->module->commentsWithApproved( 'spam' );

    // Get/Set issues
    $issues = $this->issues( count( $spam ) );

    // If any issues found
    if ( ! empty( $issues ) ) {
      $this->response->status      = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description = sprintf( _n( 'You have %s comment marked as spam.', 'You have %s comments marked as spam.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail      = new WPXCleanFixSelectControl( $spam, array( 'comment_author'  => '(%s)',
                                                                                 'comment_content' => '%s'
        ) );
      $this->response->cleanFix    = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->confirm();
      $this->response->cleanFix->title = __( 'Fix: click here to delete your SPAM comments.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deleteCommentsWithApproved( 'spam' );

    return $this->check();
  }

}
