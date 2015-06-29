<?php

/**
 * Posts clean & fix module
 *
 * @class              WPXCFPostsModule
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2013-07-01
 * @version            1.1.0
 *
 */

class WPXCFPostsModule extends WPXCleanFixModule {

  /**
   * Return a singleton instance of WPXCFPostsModule class
   *
   * @return WPXCFPostsModule
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
   * Create an instance of WPXCFPostsModule class
   *
   * @return WPXCFPostsModule
   */
  public function __construct()
  {
    parent::__construct( __( 'Posts, Page and Custom Post Types', WPXCLEANFIX_TEXTDOMAIN ) );

    // Fires when admin print styles is fired.
    add_action( 'wpxcf_admin_print_styles', array( $this, 'wpxcf_admin_print_styles' ) );

    // Fires when admin print styles is fired.
    add_action( 'wpxcf_admin_head', array( $this, 'wpxcf_admin_head' ) );

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
      'WPXCFPostsModuleAutodraftSlot',
      'WPXCFPostsModuleRevisionsSlot',
      'WPXCFPostsModulePostsWithoutAuthorSlot',
      'WPXCFPostsModuleOrphanPostMetaSlot',
      'WPXCFPostsModuleOrphanAttachmentsSlot',
      'WPXCFPostsModuleMissingAttachmentsSlot',
      'WPXCFPostsModuleTemporaryPostMetaSlot',
      'WPXCFPostsModuleTrashSlot',
    );

    return $slots;
  }

  /**
   * Fires when admin print styles is fired.
   */
  public function wpxcf_admin_print_styles()
  {
    wp_enqueue_style( 'wpxcf-posts-module', WPXCLEANFIX_URL_EMBEDDED_MODULES . 'posts/wpxcf-posts-module.css', array(), WPXCLEANFIX_VERSION );
  }

  /**
   * Fires when admin print styles is fired.
   */
  public function wpxcf_admin_head()
  {
    wp_enqueue_script( 'wpxcf-posts-module', WPXCLEANFIX_URL_EMBEDDED_MODULES . 'posts/wpxcf-posts-module.js', array(), WPXCLEANFIX_VERSION, true );
  }

  // -------------------------------------------------------------------------------------------------------------------
  // SHARED METHODS - this method are use by several slot
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Select all post ( post_title ) with a specific type.
   *
   * @param string $type Optional. The `post_type` field: attachment, post, page, revision, nav_menu_item, or custom post type.
   *                     Default `revision`
   *
   * @return mixed
   */
  public function postsWithType( $type = WPDKPostType::REVISION )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT DISTINCT( COUNT(*) ) AS number, post_title
FROM {$wpdb->posts}
WHERE post_type = '{$type}'
GROUP BY post_title
ORDER BY post_title
SQL;
    return $wpdb->get_results( $sql );
  }

  /**
   * Delete (permanently) all posts with a specific `post_type`
   *
   * @param string $type Optional. The `post_type` field: attachment, post, page, revision, nav_menu_item, or custom post type.
   *                     Default `revision`
   *
   * @return mixed
   */
  public function deletePostsWithType( $type = WPDKPostType::REVISION )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
DELETE FROM {$wpdb->posts}
WHERE post_type = '{$type}'
SQL;
    return $wpdb->query( $sql );
  }

  /**
   * Return the posts ( post_title ) with a specific `post_status`.
   *
   * @param string $status Optional. The `post_status`field: auto-draft, draft, inherit, publish, trash. Default `auto-draft`
   *                       Default `auto_draft`
   *
   * @return mixed
   */
  public function postsWithStatus( $status = WPDKPostStatus::AUTO_DRAFT )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT DISTINCT( COUNT(*) ) AS number, post_title
FROM {$wpdb->posts}
WHERE post_status = '{$status}'
GROUP BY post_title
ORDER BY post_title
SQL;
    return $wpdb->get_results( $sql );
  }

  /**
   * Delete (permanently) all posts with a specific `post_status`
   *
   * @param string $status The `post_status` field: auto-draft, draft, inherit, publish, trash.
   *                       Default `auto_draft`
   *
   * @return mixed
   */
  public function deletePostsWithStatus( $status = WPDKPostStatus::AUTO_DRAFT )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
DELETE FROM {$wpdb->posts}
WHERE post_status = '{$status}'
SQL;
    return $wpdb->query( $sql );
  }

  /**
   * Return the posts (id) without a valid user linked
   *
   * @return mixed
   */
  public function postsWithoutUsers()
  {
    /**
     * @var wpdn $wpdb
     */
    global $wpdb;

    $post_status = WPDKPostStatus::INHERIT;

    $sql = <<<SQL
SELECT
posts.post_title,
posts.ID AS post_id

FROM {$wpdb->posts} AS posts
LEFT JOIN {$wpdb->users} AS users ON ( users.ID = posts.post_author )

WHERE 1
AND users.ID IS NULL
AND posts.ID IS NOT NULL
AND posts.post_status <> '{$post_status}'
SQL;

    //WPXtreme::log( $sql );

    return $wpdb->get_results( $sql );
  }

  /**
   * Return all meta (id and key) without valid post linked
   *
   * @return mixed
   */
  public function postMetaWithoutPosts()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT
DISTINCT( COUNT(*) ) AS number,
post_meta.meta_id,
post_meta.meta_key

FROM {$wpdb->postmeta} AS post_meta

LEFT JOIN {$wpdb->posts} posts ON ( posts.ID = post_meta.post_id )

WHERE posts.ID IS NULL
GROUP BY post_meta.meta_key
ORDER BY post_meta.meta_key
SQL;

    //WPXtreme::log( $sql );

    return $wpdb->get_results( $sql );
  }

  /**
   * Return the know temporary post meta.
   *
   * @return mixed
   */
  public function temporaryPostMeta()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<< SQL
SELECT DISTINCT( COUNT(*) ) AS number, post_meta.meta_id, post_meta.meta_key
FROM {$wpdb->postmeta} post_meta
LEFT JOIN {$wpdb->posts} posts ON posts.ID = post_meta.post_id

WHERE posts.ID IS NOT NULL
AND (
   post_meta.meta_key = '_edit_lock'
OR post_meta.meta_key = '_edit_last'
OR post_meta.meta_key = '_wp_old_slug'
   )
GROUP BY post_meta.meta_key
ORDER BY post_meta.meta_key
SQL;

    return $wpdb->get_results( $sql );
  }

  /**
   * Return all posts of type `attachment` with `post_parent` > 0.
   *
   * @return mixed
   */
  public function attachmentsWithNullPost()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT
 posts_attachment.post_title,
 posts_attachment.ID as attachment_id

FROM {$wpdb->posts} AS posts_attachment

LEFT JOIN {$wpdb->posts} AS posts ON ( posts_attachment.post_parent = posts.ID )

WHERE 1
AND posts_attachment.post_type = 'attachment'
AND posts_attachment.post_parent > 0
AND posts.ID IS NULL
SQL;

    // Put in cache transient.
    $cache = get_transient( 'wpxcf-posts_attachments' );
    if ( empty( $cache ) ) {
      $cache = $wpdb->get_results( $sql );
      set_transient( 'wpxcf-posts_attachments', $cache, 60 * 60 );
    }
    return $cache;
  }

  /**
   * Return the post type attachments with missign file on filesystem.
   *
   * @since 1.3.2
   *
   * @return array
   */
  public function missingAttachments()
  {
    // Prepare result
    $results = array();

    // Query for attachment
    $args = array(
      'post_type'      => 'attachment',
      'posts_per_page' => -1,
      //'exclude'        => $this->exclude_attachments()
    );

    // Get
    $posts = get_posts( $args );

    // Loop into the attachment
    foreach( $posts as $post ) {

      // Sanitize post/attachment id
      $post_id = (int)$post->ID;

      // Check for file exists
      $file = get_attached_file( $post_id );

      // File exists?
      if( !file_exists( $file ) ) {
        $object          = new stdClass();
        $object->file    = $file;
        $object->post_id = $post_id;
        $results[ ]      = $object;
      }
    }

    return $results;
  }

  /**
   * Delete the post type attachments with missign file on filesystem.
   *
   * @since 1.3.2
   *
   * @return false|int
   */
  public function deleteMissingAttachments()
  {

    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    // Prepare result
    $results = array();

    // Query for attachment
    $args = array(
      'post_type'      => 'attachment',
      'posts_per_page' => -1,
      //'exclude'        => $this->exclude_attachments()
    );

    // Get
    $posts = get_posts( $args );

    // Loop into the attachment
    foreach( $posts as $post ) {

      // Sanitize post/attachment id
      $post_id = (int)$post->ID;

      // Check for file exists
      $file = get_attached_file( $post_id );

      // File exists?
      if( !file_exists( $file ) ) {
        wp_delete_attachment( $post_id, true );
      }
    }
  }

}


/**
 * Single slot
 *
 * @class           WPXCFPostsModuleRevisionsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-02
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleRevisionsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFPostsModuleRevisionsSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleRevisionsSlot
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
   * Create an instance of WPXCFPostsModuleRevisionsSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleRevisionsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Revisions', WPXCLEANFIX_TEXTDOMAIN ),
      __( 'Post in revision. WordPress auto-generates post revisions each time you save a newer version of the post. If you do not need them, you can permanently delete them to gain space in the database.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $revisions = $this->module->postsWithType();

    // Get/Set issues
    $issues = $this->issues( count( $revisions ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s post in revision.', 'You have %s posts in revision.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $revisions, array(
          'post_title' => '%s',
          'number'     => '(%s)'
        ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete your post revisions.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deletePostsWithType();
    return $this->check();
  }
}

/**
 * Single slot
 *
 * @class           WPXCFPostsModuleAutodraftSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-08
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleAutodraftSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFPostsModuleAutodraftSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleAutodraftSlot
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
   * Create an instance of WPXCFPostsModuleAutodraftSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleAutodraftSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Autodraft', WPXCLEANFIX_TEXTDOMAIN ),
      __( 'Post in Auto Draft. WordPress saves an Auto Draft in the database every n seconds. The Auto draft is different from draft, however you can safely remove it to gain more space in the database.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $auto_draft = $this->module->postsWithStatus();

    // Get/Set issues
    $issues = $this->issues( count( $auto_draft ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s post in auto draft.', 'You have %s posts in auto draft.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $auto_draft, array(
          'post_title' => '%s',
          'number'     => '(%s)'
        ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete your auto drafted posts.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deletePostsWithStatus();
    return $this->check();
  }

}

/**
 * Single slot
 *
 * @class           WPXCFPostsModuleTrashSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-08
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleTrashSlot extends WPXCleanFixSlot {

  /**
   * Override for autocomplete
   *
   * @var WPXCFPostsModule $module
   */
  public $module;

  /**
   * Return a singleton instance of WPXCFPostsModuleTrashSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleTrashSlot
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
   * Create an instance of WPXCFPostsModuleTrashSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleTrashSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Trash', WPXCLEANFIX_TEXTDOMAIN ), __( 'Posts in trash', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $trash = $this->module->postsWithStatus( WPDKPostStatus::TRASH );

    // Get/Set issues
    $issues = $this->issues( count( $trash ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s post in trash.', 'You have %s posts in trash.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $trash, array(
          'post_title' => '%s',
          'number'     => '(%s)'
        ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete your posts in trash.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deletePostsWithStatus( WPDKPostStatus::TRASH );
    return $this->check();
  }

}

/**
 * Single slot
 *
 * @class           WPXCFPostsModulePostsWithoutAuthorSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-08
 * @version         1.0.0
 *
 */
class WPXCFPostsModulePostsWithoutAuthorSlot extends WPXCleanFixSlot {

  /**
   * Override for intellisense
   *
   * @var WPXCFPostsModule $module
   */
  public $module;

  /**
   * Return a singleton instance of WPXCFPostsModulePostsWithoutAuthorSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModulePostsWithoutAuthorSlot
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
   * Create an instance of WPXCFPostsModulePostsWithoutAuthorSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModulePostsWithoutAuthorSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Posts without author', WPXCLEANFIX_TEXTDOMAIN ), __( 'Posts without author', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $posts = $this->module->postsWithoutUsers();

    // Get/Set issues
    $issues = $this->issues( count( $posts ) );

    if ( ! empty( $issues ) ) {
      $this->response->status      = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description = sprintf( _n( 'You have %s post not correctly assigned to any author', 'You have %s posts not correctly assigned to any author', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail      = new WPXCleanFixSelectControlForPostsWithoutAuthor( $posts, array( 'post_id' => '%s','post_title' => ' (%s)' ) );
      $this->response->cleanFix    = new WPXCleanFixButtonFixControl( $this, WPXCleanFixButtonFixControlType::FIX );
      $this->response->cleanFix->confirm( __( 'WARNING!! Be very careful: this action will assign all \'Posts without Author\' to your selected author. Are you sure?', WPXCLEANFIX_TEXTDOMAIN ) );
      $this->response->cleanFix->title = __( 'Fix: click here to repair posts without authors.', WPXCLEANFIX_TEXTDOMAIN );
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

    if ( isset( $_POST['params'] ) && !empty( $_POST['params'] ) && is_numeric( $_POST['params'] ) ) {
      $user_id = $_POST['params'];
      $posts   = $posts = $this->module->postsWithoutUsers();

      if ( !empty( $posts ) ) {
        $stack = array();
        foreach ( $posts as $post ) {
          $stack[] = $post->post_id;
        }
        $ids = implode( ',', $stack );

        $sql = <<<SQL
UPDATE {$wpdb->posts}
SET post_author = {$user_id}
WHERE ID IN( {$ids} )
SQL;
        $wpdb->query( $sql );
      }
    }
    return $this->check();
  }

}

/**
 * Special extra detail for posts without author
 *
 * @class           WPXCleanFixSelectControlForPostsWithoutAuthor
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-08
 * @version         1.0.0
 *
 */
class WPXCleanFixSelectControlForPostsWithoutAuthor extends WPXCleanFixSelectControl {

  /**
   * Return the HTML markup
   *
   * @return string
   */
  public function html()
  {
    $buffer = parent::html();

    $users      = array( '' => __( 'Choose a new user', WPXCLEANFIX_TEXTDOMAIN ) );
    $users_list = get_users();
    if ( $users_list ) {
      foreach ( $users_list as $user ) {
        $users[$user->ID] = sprintf( '%s (%s)', $user->display_name, $user->user_email );
      }
    }

    $item = array(
      'name'    => 'users_posts',
      'options' => $users,
      'data'    => array(
        'warning' => __( 'WARNING! Please, select an user before continuing.', WPXCLEANFIX_TEXTDOMAIN )
      )
    );

    $control = new WPDKUIControlSelect( $item );

    return $buffer . $control->html();
  }
}

/**
 * Single slot
 *
 * @class           WPXCFPostsModuleOrphanPostMetaSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-09
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleOrphanPostMetaSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFPostsModuleOrphanPostMetaSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleOrphanPostMetaSlot
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
   * Create an instance of WPXCFPostsModuleOrphanPostMetaSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleOrphanPostMetaSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Orphan Post Meta', WPXCLEANFIX_TEXTDOMAIN ),
      __( 'Post Meta data not assigned to a post. These are the extra properties for a standard post type (post, page, custom post type, etc...). Sometimes, post meta records may exist without being associated with post: in this case, post meta are orphan and can be deleted.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $post_meta = $this->module->postMetaWithoutPosts();

    // Get/Set issues
    $issues = $this->issues( count( $post_meta ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan post meta.', 'You have %s orphan post meta.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $post_meta, array(
          'meta_key' => '%s',
          'number'   => '(%s)'
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
DELETE post_meta
FROM {$wpdb->postmeta} AS post_meta
LEFT JOIN {$wpdb->posts} AS posts ON ( posts.ID = post_meta.post_id )
WHERE posts.ID IS NULL
SQL;

    $wpdb->query( $sql );
    return $this->check();
  }

}

/**
 * Single slot
 *
 * @class           WPXCFPostsModuleTemporaryPostMetaSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-09
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleTemporaryPostMetaSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFPostsModuleTemporaryPostMetaSlot class
   *
   * param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleTemporaryPostMetaSlot
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
   * Create an instance of WPXCFPostsModuleTemporaryPostMetaSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleTemporaryPostMetaSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Temporary', WPXCLEANFIX_TEXTDOMAIN ),
      __( 'These records are stored by WordPress as temporary data. If you like you can safely delete them.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $temporary = $this->module->temporaryPostMeta();

    // Get/Set issues
    $issues = $this->issues( count( $temporary ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s temporary post meta.', 'You have %s temporary post meta.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $temporary, array(
          'meta_key' => '%s',
          'number'   => '(%s)'
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

    $sql = <<<SQL
DELETE post_meta FROM {$wpdb->postmeta} AS post_meta

LEFT JOIN {$wpdb->posts} posts ON ( posts.ID = post_meta.post_id )

WHERE 1
AND posts.ID IS NOT NULL
AND (
   post_meta.meta_key = '_edit_lock'
OR post_meta.meta_key = '_edit_last'
OR post_meta.meta_key = '_wp_old_slug'
   )
SQL;

    $wpdb->query( $sql );
    return $this->check();
  }

}

/**
 * Single slot
 *
 * @class           WPXCFPostsModuleOrphanAttachmentsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-09
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleOrphanAttachmentsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFPostsModuleOrphanAttachmentsSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleOrphanAttachmentsSlot
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
   * Create an instance of WPXCFPostsModuleOrphanAttachmentsSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleOrphanAttachmentsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Orphan attachments', WPXCLEANFIX_TEXTDOMAIN ),
      __( 'An orphan attachment is a custom post type without a valid parent post ID assigned (it is missing). An attachment usually has a null parent post or a post ID', WPXCLEANFIX_TEXTDOMAIN, WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $attachments = $this->module->attachmentsWithNullPost();

    // Get/Set issues
    $issues = $this->issues( count( $attachments ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan attachment.', 'You have %s orphan attachments.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $attachments, array(
          'post_title' => '%s',
          'number'     => '(%s)'
        ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to remove all invalid post parents.', WPXCLEANFIX_TEXTDOMAIN );
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

    // Get from cache transient.
    $cache = get_transient( 'wpxcf-posts_attachments' );

    if ( empty( $cache ) ) {
      $cache = $this->module->attachmentsWithNullPost();
      set_transient( 'wpxcf-posts_attachments', $cache, 60 * 60 );
    }

    $stack = array();
    foreach ( $cache as $attachment ) {
      $stack[] = $attachment->attachment_id;
    }
    $ids = implode( ',', $stack );

    $sql = <<< SQL
UPDATE {$wpdb->posts}
SET post_parent = 0
WHERE ID IN ({$ids})
SQL;

    delete_transient( 'wpxcf-posts_attachments' );

    $wpdb->query( $sql );

    return $this->check();
  }

}

/**
 * Single slot
 *
 * @class           WPXCFPostsModuleMissingAttachmentsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-11-04
 * @version         1.0.0
 *
 */
class WPXCFPostsModuleMissingAttachmentsSlot extends WPXCleanFixSlot {

  /**
   * Parent module model
   *
   * @var WPXCFPostsModule $module
   */
  public $module;

  /**
   * Return a singleton instance of WPXCFPostsModuleMissingAttachmentsSlot class
   *
   * param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleMissingAttachmentsSlot
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
   * Create an instance of WPXCFPostsModuleMissingAttachmentsSlot class
   *
   * @param WPXCFPostsModule $module
   *
   * @return WPXCFPostsModuleMissingAttachmentsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Missing attachments file', WPXCLEANFIX_TEXTDOMAIN ), __( 'Post attachments with missing filesystem file', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $attachments = $this->module->missingAttachments();

    // Get/Set issues
    $issues = $this->issues( count( $attachments ) );

    if( !empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s missing attachment file.', 'You have %s missing attachments files.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $attachments, array( 'file' => '%s' ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->confirm();
      $this->response->cleanFix->title = __( 'Fix: click here to remove all missing post attachments.', WPXCLEANFIX_TEXTDOMAIN );
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
    $this->module->deleteMissingAttachments();
    return $this->check();
  }

}
