<?php

/**
 * Manage enhancer Post
 *
 * ## Overview
 * Add several post enhancer.
 *
 * @class              WPXtremeEnhancerPost
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-12-11
 * @version            1.0.2
 *
 * @history            1.0.1 - Added 'duplicate' row action
 * @history            1.0.2 - Fixed enhancer menu order (column order with drag & drop) with `post_type_supports( $post_type, 'page-attributes' )` instead `capability_type`
 *
 */
final class WPXtremeEnhancerPost extends WPDKObject {

  /**
   * Override version
   *
   * @var string $__version
   */
  public $__version = '1.0.1';

  /**
   * Useful pointer to preferences
   *
   * @var WPXtremePreferencesListTableBranch $preferences
   */
  public $preferences;

  /**
   * Return a singleton instance of WPXtremeEnhancerPost class
   *
   * @return WPXtremeEnhancerPost
   */
  public static function init()
  {
    static $instance = null;
    if( is_null( $instance ) ) {
      $instance = new self();
    }

    return $instance;
  }

  /**
   * Create an instance of WPXtremeEnhancerPost class
   *
   * @return WPXtremeEnhancerPost
   */
  private function __construct()
  {
    // Extends Posts List Table
    add_filter( 'manage_posts_columns', array( $this, 'manage_posts_columns' ), 10, 2 );
    add_filter( 'manage_edit-post_sortable_columns', array( $this, 'manage_edit_sortable_columns' ) );
    add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );

    // Extends Pages List Table
    add_filter( 'manage_pages_columns', array( $this, 'manage_pages_columns' ) );
    add_filter( 'manage_edit-page_sortable_columns', array( $this, 'manage_edit_sortable_columns' ) );
    add_action( 'manage_pages_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );

    // Fires before the Filter button on the Posts and Pages list tables.
    add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );

    // Extends Media (upload) List Table
    add_filter( 'manage_media_columns', array( $this, 'manage_media_columns' ) );
    add_filter( 'manage_upload_sortable_columns', array( $this, 'manage_upload_sortable_columns' ) );
    add_action( 'manage_media_custom_column', array( $this, 'manage_posts_custom_column' ), 10, 2 );

    // Enqueue scripts and styles
    add_action( 'admin_print_styles-upload.php', array( $this, 'admin_print_style_upload_php' ) );

    // Fires in <head> for a specific admin page based on $hook_suffix.
    add_action( 'admin_head-edit.php', array( $this, 'admin_head_edit_php' ) );

    // Enhancer other custom posts of type page
    $post_types = get_post_types( array( '_builtin' => false ) );
    foreach( $post_types as $id ) {
      add_filter( 'manage_edit-' . $id . '_sortable_columns', array( $this, 'manage_edit_sortable_columns' ) );
    }

    // Remove all revisions
    add_action( 'save_post', array( $this, 'save_post' ), 50, 2 );

    // Duplicate Posts and Pages
    add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

    add_filter( 'page_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

    // Fires when an 'action' request variable is sent.
    add_action( 'admin_action_wpxtreme_clone', array( $this, 'admin_action_wpxtreme_clone' ) );

    // Fires at the beginning of the publishing actions section of the Publish meta box.
    add_action( 'post_submitbox_start', array( $this, 'post_submitbox_start' ) );

    // Get instance
    $this->preferences = WPXtremePreferences::init()->list_table;

  }

  /**
   * Fires at the beginning of the publishing actions section of the Publish meta box.
   *
   * @since WP 2.7.0
   */
  public function post_submitbox_start()
  {
    global $post;

    if( false == $this->current_user_can_duplicate( $post ) ) {
      return;
    }

    // Build the href
    $args = array(
      'action'         => 'wpxtreme_clone',
      'post_id'        => $post->ID,
      'back_to_editor' => ''
    );
    $href = add_query_arg( $args );

    ?>
    <div id="duplicate-action" class="clearfix" style="text-align:right;margin-bottom:12px">
      <a class="button button-large"
         href="<?php echo $href ?>"><?php _e( 'Clone' ); ?>
      </a>
    </div>
  <?php
  }

  /**
   * Fires when an 'action' request variable is sent.
   *
   * The dynamic portion of the hook name, $_REQUEST['action'],
   * refers to the action derived from the GET or POST request.
   *
   * @since WP 2.6.0
   */
  public function admin_action_wpxtreme_clone()
  {
    // Get post id
    $post_id = isset( $_REQUEST[ 'post_id' ] ) ? absint( $_REQUEST[ 'post_id' ] ) : false;

    // Stability
    if( empty( $post_id ) ) {
      return;
    }

    // TODO Get original post to get the post type

    // TODO Add check capabilities


    // Duplicate
    $duplicate = WPDKPost::duplicate( $post_id );

    if( isset( $_REQUEST[ 'back_to_editor' ] ) ) {
      wp_safe_redirect( admin_url( 'post.php?action=edit&post=' . $duplicate->ID ) );
      exit();
    }
    else {
      wp_safe_redirect( admin_url( 'edit.php?post_type=' . $duplicate->post_type ) );
      exit();
    }
  }

  /**
   * Filter the array of row action links on the Posts list table.
   *
   * The filter is evaluated only for non-hierarchical post types.
   *
   * @since 2.8.0
   *
   * @param array   $actions An array of row action links. Defaults are
   *                         'Edit', 'Quick Edit', 'Restore, 'Trash',
   *                         'Delete Permanently', 'Preview', and 'View'.
   * @param WP_Post $post    The post object.
   */
  public function post_row_actions( $actions, $post )
  {
    // Check capabilities for post type and user
    if( false == $this->current_user_can_duplicate( $post ) ) {
      return $actions;
    }

    // Build the href
    $args = array(
      'action'  => 'wpxtreme_clone',
      'post_id' => $post->ID
    );

    // Get referer for Ajax action
    $referer = wpdk_is_ajax() ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'];

    // Create URI
    $href = add_query_arg( $args, $referer );

    // Row action
    $actions[ 'clone' ] = sprintf( '<a href="%s" title="%s">%s</a>', $href, __( 'Create a Draft copy' ), __( 'Clone' ) );

    return $actions;
  }

  /**
   * Return TRUE if the user can duplicate a post.
   *
   * @param object $post The post object.
   *
   * @return bool
   */
  private function current_user_can_duplicate( $post )
  {

    if( 'post' == $post->post_type && !current_user_can( 'edit_posts' ) ) {
      return false;
    }

    if( 'post' != $post->post_type && !current_user_can( 'edit_pages' ) ) {
      return false;
    }

    if( $post->post_author != get_current_user_id() ) {
      if( 'post' == $post->post_type && !current_user_can( 'edit_others_posts' ) ) {
        return false;
      }
      if( 'post' != $post->post_type && !current_user_can( 'edit_others_pages' ) ) {
        return false;
      }
    }

    return true;
  }

  /**
   * Fires once a post has been saved.
   *
   * @since WP 1.5.0
   *
   * @param int     $post_ID Post ID.
   * @param WP_Post $post    Post object.
   * @param bool    $update  Whether this is an existing post being updated or not.
   */
  public function save_post( $ID, $post )
  {
    // Do not save...
    if( ( defined( 'DOING_AUTOSAVE' ) && true === DOING_AUTOSAVE ) ||
      ( defined( 'DOING_AJAX' ) && true === DOING_AJAX ) || ( defined( 'DOING_CRON' ) && true === DOING_CRON )
    ) {
      return;
    }

    // Get post type information
    $post_type        = get_post_type();
    $post_type_object = get_post_type_object( $post_type );

    // Stability
    if( false == $post_type || is_null( $post_type_object ) ) {
      return;
    }

    // Find correct capability from post_type arguments
    if( isset( $post_type_object->cap->edit_posts ) ) {
      $capability = $post_type_object->cap->edit_posts;

      // Return if current user cannot edit this post
      if( !current_user_can( $capability ) ) {
        return;
      }
    }

    // Enhancer Remove All Revisions
    if( isset( $_POST[ 'wpxm-button-remove-all-revisions' ] ) ) {
      $revisions = wp_get_post_revisions( $post );
      if( !empty( $revisions ) ) {
        foreach( $revisions as $revision ) {
          $result = wp_delete_post_revision( $revision->ID );
          if( is_wp_error( $result ) ) {
            break;
          }
        }
      }
    }
  }

  // -------------------------------------------------------------------------------------------------------------------
  // List of enhancer post type
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return TRUE if a post type have to enhance
   *
   * @note  Return always TRUE
   *
   * @return bool
   */
  private function isEnhance()
  {
    return true;

    // TODO This method return always TRUE - complete the code below

    /*
    global $typenow;

    $dopable = array(
      'post',
      'page',
      ''
    );
    return in_array( $typenow, $dopable );
    */
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Enhancer Post
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Fires in <head> for a specific admin page based on $hook_suffix.
   */
  public function admin_head_edit_php()
  {
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::TOOLTIP );
  }

  /**
   * Filter the columns displayed in the Posts list table.
   *
   * @since WP 1.5.0
   *
   * @param array  $posts_columns An array of column names.
   * @param string $post_type     The post type slug.
   */
  public function manage_posts_columns( $columns, $post_type )
  {
    global $typenow;

    if( !$this->isEnhance() || ( is_null( $typenow ) && is_null( $post_type ) ) ) {
      return $columns;
    }

    if( is_null( $post_type ) ) {
      $post_type = $typenow;
    }

    // Get the post type
    $cpt = get_post_type_object( $post_type );

    // Bool flag for swipe
    $post_switch = ( WPDKPostType::POST == $cpt->capability_type ) && wpdk_is_bool( $this->preferences->posts_swipe_publish );
    $page_switch = ( WPDKPostType::PAGE == $cpt->capability_type ) && wpdk_is_bool( $this->preferences->pages_swipe_publish );

    // Added quick publish/draft column with swipe control
    if( $page_switch || $post_switch ) {
      $columns[ 'wpdk_post_internal-publish' ] = __( 'Published', WPXTREME_TEXTDOMAIN );
    }

    $post_thumbnail_author = ( WPDKPostType::POST == $cpt->capability_type ) && wpdk_is_bool( $this->preferences->posts_thumbnail_author );
    $page_thumbnail_author = ( WPDKPostType::PAGE == $cpt->capability_type ) && wpdk_is_bool( $this->preferences->pages_thumbnail_author );

    // Replace author column with author thumbnail image
    if( $page_thumbnail_author || $post_thumbnail_author ) {
      unset( $columns[ 'author' ] );
      $columns = WPDKArray::insertKeyValuePairs( $columns, 'wpdk_post_internal-author', __( 'Author', WPXTREME_TEXTDOMAIN ), 2 );
    }

    if( post_type_supports( $post_type, 'page-attributes' ) ) {
      $columns = array_merge( array( 'menu_order' => __( 'Order', WPXTREME_TEXTDOMAIN ) ), $columns );
    }

    return $columns;
  }

  /**
   * List of sortable columns
   *
   * @param array $columns Array Default sortable columns
   *
   * @return array
   */
  public function manage_edit_sortable_columns( $columns )
  {
    global $typenow;

    if( is_null( $typenow ) ) {
      return $columns;
    }

    $post_thumbnail_author = ( WPDKPostType::POST == $typenow ) &&
      wpdk_is_bool( $this->preferences->posts_thumbnail_author );
    $page_thumbnail_author = ( WPDKPostType::PAGE == $typenow ) &&
      wpdk_is_bool( $this->preferences->pages_thumbnail_author );

    if( $page_thumbnail_author || $post_thumbnail_author ) {

      if( !$this->isEnhance() ) {
        return $columns;
      }

      // Improves
      $columns[ 'title' ]              = array( 'date', false );
      $columns[ 'wpdk_post_internal' ] = array( 'author', false );
      $columns[ 'parent' ]             = array( 'parent', false );
      $columns[ 'comments' ]           = array( 'comments', false );
      $columns[ 'date' ]               = array( 'comments', true );

      //      $columns = array(
      //        'title'                     => 'title',
      //        'wpdk_post_internal-author' => 'author',
      //        'parent'                    => 'parent',
      //        'comments'                  => 'comment_count',
      //        'date'                      => array( 'date', true )
      //      );

    }

    if( post_type_supports( $typenow, 'page-attributes' ) ) {
      $columns[ 'date' ]       = array( 'date', false );
      $columns[ 'menu_order' ] = array( 'menu_order', true );
    }

    return $columns;
  }

  /**
   * Fires in each custom column in the Posts list table.
   *
   * This hook only fires if the current post type is non-hierarchical,
   * such as posts.
   *
   * @since WP 1.5.0
   *
   * @param string $column_name The name of the column to display.
   * @param int    $post_id     The current post ID.
   */
  public function manage_posts_custom_column( $column_name, $post_id )
  {
    global $post;

    // Check Preferences
    if( !$this->isEnhance() ) {
      return;
    }

    // Content for Publish
    if( 'wpdk_post_internal-publish' == $column_name ) {

      // Get the post status
      $post_status = get_post_status( $post_id );

      // Get all readable statuses
      $statuses = WPDKPostStatus::statuses();

      if( WPDKPostStatus::PUBLISH == $post_status || WPDKPostStatus::DRAFT == $post_status ) {
        $item    = array(
          'name'       => 'wpdk-post-publish',
          'id'         => 'switch-' . $post_id,
          'data'       => array( 'post_id' => $post_id ),
          'title'      => $statuses[ $post_status ],
          'value'      => ( WPDKPostStatus::PUBLISH == $post_status )
        );
        $control = new WPDKUIControlSwitch( $item );
        $control->display();
      }
      else {
        $icons = array(
          WPDKPostStatus::FUTURE   => WPDKGlyphIcons::html( WPDKGlyphIcons::CLOCK ),
          WPDKPostStatus::PRIVATE_ => WPDKGlyphIcons::html( WPDKGlyphIcons::LOCK ),
          WPDKPostStatus::TRASH    => WPDKGlyphIcons::html( WPDKGlyphIcons::TRASH ),
          WPDKPostStatus::PENDING  => WPDKGlyphIcons::html( WPDKGlyphIcons::SPIN2 ),
        );
        printf( '<span title="%s" class="wpdk-has-tooltip post-status-%s %s">%s</span>', ucfirst( $post_status ), $statuses[ $post_status ], $post_status, $icons[ $post_status ] );
      }
    }

    // Author
    elseif( 'wpdk_post_internal-author' == $column_name ) {
      echo WPDKUsers::init()->gravatar( get_the_author_meta( 'ID' ), 48 );
      printf( '<br/><a href="%s">%s</a>', esc_url( add_query_arg( array(
                                                                    'post_type' => $post->post_type,
                                                                    'author'    => get_the_author_meta( 'ID' )
                                                                  ), 'edit.php' ) ), get_the_author() );
    }

    // Icon
    elseif( 'wpdk_post_internal-icon' == $column_name ) {
      if( ( $thumb = wp_get_attachment_image( $post->ID, array( 80, 60 ), true ) ) ) {
        printf( '<a rel="gallery" class="thickbox" title="%s" href="%s?TB_iframe">%s</a>', _draft_or_post_title( $post->ID ), $post->guid, $thumb );
      }
    }

    // Drag & drop sorter
    elseif( 'menu_order' == $column_name ) {
      printf( '<i data-order="%s" class="%s"></i>', $post->menu_order, WPDKGlyphIcons::MENU );
    }
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Enhancer Page
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Filter the columns displayed in the Pages list table.
   *
   * @since WP 2.5.0
   *
   * @param array $post_columns An array of column names.
   */
  public function manage_pages_columns( $columns )
  {

    if( !$this->isEnhance() ) {
      return $columns;
    }

    $page_switch = wpdk_is_bool( $this->preferences->pages_swipe_publish );

    // Added quick publish/draft column with swipe control
    if( $page_switch ) {
      $columns[ 'wpdk_post_internal-publish' ] = __( 'Published', WPXTREME_TEXTDOMAIN );
    }

    $page_thumbnail_author = wpdk_is_bool( $this->preferences->pages_thumbnail_author );

    // Replace author column with author thumbnail image
    if( $page_thumbnail_author ) {
      unset( $columns[ 'author' ] );
      $columns = WPDKArray::insertKeyValuePairs( $columns, 'wpdk_post_internal-author', __( 'Author', WPXTREME_TEXTDOMAIN ), 2 );
    }

    $columns = array_merge( array( 'menu_order' => __( 'Order', WPXTREME_TEXTDOMAIN ) ), $columns );

    return $columns;
  }

  /**
   * Fires before the Filter button on the Posts and Pages list tables.
   *
   * The Filter button allows sorting by date and/or category on the
   * Posts list table, and sorting by date on the Pages list table.
   *
   * @since WP 2.1.0
   */
  public function restrict_manage_posts()
  {
    global $typenow, $per_page;

    // Get the post type
    $cpt = get_post_type_object( $typenow );

    // Enabled drag & drop menu order only for post type page
    if( !empty( $cpt ) && is_object( $cpt ) && post_type_supports( $typenow, 'page-attributes' ) ) {
      // Build info on pagination. Useful for sorter
      $paged = isset( $_REQUEST[ 'paged' ] ) ? $_REQUEST[ 'paged' ] : '1';
      ?>
      <input rel="<?php echo $typenow ?>"
             type="hidden"
             name="wpx-per-page"
             id="wpx-per-page"
             value="<?php echo $per_page ?>"/>
      <input type="hidden" name="wpx-paged" id="wpx-paged" value="<?php echo $paged ?>"/>
    <?php
    }

    /*
     * If you only want this to work for your specific post type, check for that $type here and then return.
     * This function, if unmodified, will add the dropdown for each post type / taxonomy combination.
     *
     * // Return the registered custom post types; exclude the builtin
     * $post_types = get_post_types( array( '_builtin' => false ) );
     *
     */

  }

  // -------------------------------------------------------------------------------------------------------------------
  // Enhancer media
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * Tickbox enhancer styles
   *
   * @since WP 2.6.0
   *
   */
  public function admin_print_style_upload_php()
  {
    wp_enqueue_style( 'wpxm-thickbox', WPXTREME_URL_CSS . 'wpxm-thickbox.css', array(), WPXTREME_VERSION );
  }

  /**
   * Filter the Media list table columns.
   *
   * @since WP 2.5.0
   *
   * @param array $posts_columns An array of columns displayed in the Media list table.
   * @param bool  $detached      Whether the list table contains media not attached
   *                             to any posts. Default true.
   */
  public function manage_media_columns( $columns )
  {
    if( !$this->isEnhance() ) {
      return $columns;
    }

    if( wpdk_is_bool( $this->preferences->media_thumbnail_author ) ) {
      unset( $columns[ 'author' ] );
      $columns = WPDKArray::insertKeyValuePairs( $columns, 'wpdk_post_internal-author', __( 'Author', WPXTREME_TEXTDOMAIN ), 3 );
    }

    if( wpdk_is_bool( $this->preferences->media_thickbox_icon ) ) {
      unset( $columns[ 'icon' ] );
      $columns = WPDKArray::insertKeyValuePairs( $columns, 'wpdk_post_internal-icon', __( 'Icon', WPXTREME_TEXTDOMAIN ), 1 );
    }

    return $columns;
  }

  /**
   * Filter the upload
   */
  public function manage_upload_sortable_columns( $columns )
  {
    if( wpdk_is_bool( $this->preferences->media_thumbnail_author ) ) {
      if( !$this->isEnhance() ) {
        return $columns;
      }

      // Improves
      $columns[ 'title' ]                     = array( 'date', false );
      $columns[ 'wpdk_post_internal-author' ] = array( 'author', false );
      $columns[ 'parent' ]                    = array( 'parent', false );
      $columns[ 'comments' ]                  = array( 'comments', false );
      $columns[ 'date' ]                      = array( 'comments', true );

      //      $columns = array(
      //        'title'                     => 'title',
      //        'wpdk_post_internal-author' => 'author',
      //        'parent'                    => 'parent',
      //        'comments'                  => 'comment_count',
      //        'date'                      => array( 'date', true )
      //      );
    }

    return $columns;
  }
}