<?php
/// @cond private

/*
 * [DRAFT]
 *
 * THE FOLLOWING CODE IS A DRAFT. FEEL FREE TO USE IT TO MAKE SOME EXPERIMENTS, BUT DO NOT USE IT IN ANY CASE IN
 * PRODUCTION ENVIRONMENT. ALL CLASSES AND RELATIVE METHODS BELOW CAN CHNAGE IN THE FUTURE RELEASES.
 *
 */

/**
 * Custom Post Type model class
 *
 * @class           WPDKCustomPostType
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-03-03
 * @version         1.0.3
 * @since           1.4.0
 *
 */
class WPDKCustomPostType extends WPDKObject {

  /**
   * Override version
   *
   * @brief Version
   *
   * @var string $__version
   */
  public $__version = '1.0.3';

  /**
   * Custom Post type ID
   *
   * @brief CPT ID
   *
   * @var string $id
   */
  public $id = '';

  /**
   * Path images
   *
   * @brief URL images
   * @since 1.4.8
   *
   * @var string $url_images
   */
  public $url_images = '';

  /**
   * Create an instance of WPDKCustomPostType class
   *
   * Optional $args contents:
   *
   * - label - Name of the post type shown in the menu. Usually plural. If not set, labels['name'] will be used.
   * - labels - An array of labels for this post type.
   *     * If not set, post labels are inherited for non-hierarchical types and page labels for hierarchical ones.
   *     * You can see accepted values in {@link get_post_type_labels()}.
   * - description - A short descriptive summary of what the post type is. Defaults to blank.
   * - public - Whether a post type is intended for use publicly either via the admin interface or by front-end users.
   *     * Defaults to false.
   *     * While the default settings of exclude_from_search, publicly_queryable, show_ui, and show_in_nav_menus are
   *       inherited from public, each does not rely on this relationship and controls a very specific intention.
   * - hierarchical - Whether the post type is hierarchical (e.g. page). Defaults to false.
   * - exclude_from_search - Whether to exclude posts with this post type from front end search results.
   *     * If not set, the opposite of public's current value is used.
   * - publicly_queryable - Whether queries can be performed on the front end for the post type as part of parse_request().
   *     * ?post_type={post_type_key}
   *     * ?{post_type_key}={single_post_slug}
   *     * ?{post_type_query_var}={single_post_slug}
   *     * If not set, the default is inherited from public.
   * - show_ui - Whether to generate a default UI for managing this post type in the admin.
   *     * If not set, the default is inherited from public.
   * - show_in_menu - Where to show the post type in the admin menu.
   *     * If true, the post type is shown in its own top level menu.
   *     * If false, no menu is shown
   *     * If a string of an existing top level menu (eg. 'tools.php' or 'edit.php?post_type=page'), the post type will
   *       be placed as a sub menu of that.
   *     * show_ui must be true.
   *     * If not set, the default is inherited from show_ui
   * - show_in_nav_menus - Makes this post type available for selection in navigation menus.
   *     * If not set, the default is inherited from public.
   * - show_in_admin_bar - Makes this post type available via the admin bar.
   *     * If not set, the default is inherited from show_in_menu
   * - menu_position - The position in the menu order the post type should appear.
   *     * show_in_menu must be true
   *     * Defaults to null, which places it at the bottom of its area.
   * - menu_icon - The url to the icon to be used for this menu. Defaults to use the posts icon.
   *     * Pass a base64-encoded SVG using a data URI, which will be colored to match the color scheme.
   *      This should begin with 'data:image/svg+xml;base64,'.
   *     * Pass the name of a Dashicons helper class to use a font icon, e.g. 'dashicons-piechart'.
   *     * Pass 'none' to leave div.wp-menu-image empty so an icon can be added via CSS.
   * - capability_type - The string to use to build the read, edit, and delete capabilities. Defaults to 'post'.
   *     * May be passed as an array to allow for alternative plurals when using this argument as a base to construct the
   *       capabilities, e.g. array('story', 'stories').
   * - capabilities - Array of capabilities for this post type.
   *     * By default the capability_type is used as a base to construct capabilities.
   *     * You can see accepted values in {@link get_post_type_capabilities()}.
   * - map_meta_cap - Whether to use the internal default meta capability handling. Defaults to false.
   * - supports - An alias for calling add_post_type_support() directly. Defaults to title and editor.
   *     * See {@link add_post_type_support()} for documentation.
   * - register_meta_box_cb - Provide a callback function that sets up the meta boxes
   *     for the edit form. Do remove_meta_box() and add_meta_box() calls in the callback.
   * - taxonomies - An array of taxonomy identifiers that will be registered for the post type.
   *     * Default is no taxonomies.
   *     * Taxonomies can be registered later with register_taxonomy() or register_taxonomy_for_object_type().
   * - has_archive - True to enable post type archives. Default is false.
   *     * Will generate the proper rewrite rules if rewrite is enabled.
   * - rewrite - Triggers the handling of rewrites for this post type. Defaults to true, using $post_type as slug.
   *     * To prevent rewrite, set to false.
   *     * To specify rewrite rules, an array can be passed with any of these keys
   *         * 'slug' => string Customize the permastruct slug. Defaults to $post_type key
   *         * 'with_front' => bool Should the permastruct be prepended with WP_Rewrite::$front. Defaults to true.
   *         * 'feeds' => bool Should a feed permastruct be built for this post type. Inherits default from has_archive.
   *         * 'pages' => bool Should the permastruct provide for pagination. Defaults to true.
   *         * 'ep_mask' => const Assign an endpoint mask.
   *             * If not specified and permalink_epmask is set, inherits from permalink_epmask.
   *             * If not specified and permalink_epmask is not set, defaults to EP_PERMALINK
   * - query_var - Sets the query_var key for this post type. Defaults to $post_type key
   *     * If false, a post type cannot be loaded at ?{query_var}={post_slug}
   *     * If specified as a string, the query ?{query_var_string}={post_slug} will be valid.
   * - can_export - Allows this post type to be exported. Defaults to true.
   * - delete_with_user - Whether to delete posts of this type when deleting a user.
   *     * If true, posts of this type belonging to the user will be moved to trash when then user is deleted.
   *     * If false, posts of this type belonging to the user will *not* be trashed or deleted.
   *     * If not set (the default), posts are trashed if post_type_supports('author'). Otherwise posts are not trashed or deleted.
   * - _builtin - true if this post type is a native or "built-in" post_type. THIS IS FOR INTERNAL USE ONLY!
   * - _edit_link - URL segement to use for edit link of this post type. THIS IS FOR INTERNAL USE ONLY!
   *   *
   * @brief Construct
   *
   * @param string       $id   Post type key, must not exceed 20 characters.
   * @param array|string $args See optional args description above.
   *
   * @return WPDKCustomPostType
   */
  public function __construct( $id, $args )
  {
    // Save useful properties
    $this->id = $id;

    // TODO doing a several control check in the input $args array

    // Register MetaBox
    if ( !isset( $args['register_meta_box_cb'] ) ) {
      $args['register_meta_box_cb'] = array( $this, 'register_meta_box' );
    }

    // Get icons path
    if ( isset( $args['menu_icon'] ) ) {
      $this->url_images = trailingslashit( dirname( $args['menu_icon'] ) );
    }

    // Register custom post type
    register_post_type( $id, $args );

    // Init admin hook
    $this->init_admin_hooks();
  }

  /**
   * Return TRUE if the current post type is this custom post type.
   *
   * @return bool
   */
  public function is()
  {
    global $post_type;

    return ( $post_type === $this->id );
  }

  /**
   * Init useful (common) admon hook
   *
   * @brief Init admin hook
   */
  private function init_admin_hooks()
  {
    if ( is_admin() ) {

      // Fires in <head> for all admin pages.
      add_action( 'admin_head', array( $this, '_admin_head' ) );

      // Filter the admin <body> CSS classes.
      add_filter( 'admin_body_class', array( $this, '_admin_body_class' ) );

      // Fires when styles are printed for a specific admin page based on $hook_suffix.
      add_action( 'admin_print_styles-post-new.php', array( $this, '_admin_print_styles_post_new_php') );

      // Fires when styles are printed for a specific admin page based on $hook_suffix.
      add_action( 'admin_print_styles-post.php', array( $this, '_admin_print_styles_post_php') );

      // Fires when styles are printed for a specific admin page based on $hook_suffix.
      add_action( 'admin_print_styles-edit.php', array( $this, '_admin_print_styles_edit_php') );

      // Fires in <head> for a specific admin page based on $hook_suffix.
      add_action( 'admin_head-post.php', array( $this, '_admin_head_post_php' ) );

      // Fires in <head> for a specific admin page based on $hook_suffix.
      add_action( 'admin_head-edit.php', array( $this, '_admin_head_edit_php' ) );

      // Fires in <head> for a specific admin page based on $hook_suffix.
      add_action( 'admin_head-post-new.php', array( $this, '_admin_head_post_new_php' ) );

      // Manage column
      add_action( 'manage_' . $this->id . '_posts_custom_column', array( $this, 'manage_posts_custom_column' ) );
      add_filter( 'manage_edit-' . $this->id . '_columns', array( $this, 'manage_edit_columns') );
      add_filter( 'manage_edit-' . $this->id . '_sortable_columns', array( $this, 'manage_edit_sortable_columns' ) );

      // Hook save post
      add_action( 'save_post_' . $this->id, array( $this, 'save_post' ), 10, 2 );

    }
  }

/**
 * Filter the admin <body> CSS classes.
 *
 * This filter differs from the post_class or body_class filters in two important ways:
 * 1. $classes is a space-separated string of class names instead of an array.
 * 2. Not all core admin classes are filterable, notably: wp-admin, wp-core-ui, and no-js cannot be removed.
 *
 * Use to set the right icon for this custom post type.
 *
 * @param string $classes Space-separated string of CSS classes.
 */
  public function _admin_body_class( $classes )
  {

    if( ! $this->is() ) {
      return $classes;
    }

    $classes .= ' wpdk-post-type-' . $this->id;

    /**
     * Filter the admin <body> CSS classes.
     *
     * The dynamic portion of the hook name, $id, refers to the custom post type id.
     *
     * @sincw 1.6.0
     *
     * @param string $classes Space-separated string of CSS classes.
     */
    return apply_filters( 'admin_body_class-' . $this->id, $classes );
  }

  /**
   * Fires in <head> for a specific admin page based on $hook_suffix.
   *
   * @brief Head
   */
  public function _admin_head()
  {
    if ( ! $this->is() ) {
      return;
    }

    // Display right icon on the title
    if ( !empty( $this->url_images ) ) {

      WPDKHTML::startCompress();
      ?>
      <style type="text/css">
      body.wpdk-post-type-<?php echo $this->id ?> .wrap > h2
      {
        background-image  : url(<?php echo $this->url_images ?>logo-64x64.png);
        background-repeat : no-repeat;
        height            : 64px;
        line-height       : 64px;
        padding           : 0 0 0 80px;
      }
    </style>
      <?php
      echo WPDKHTML::endCSSCompress();
    }

    /**
     * Fires in <head> for all admin pages for this custom post type.
     *
     * @see "admin_head"
     */
    do_action( 'admin_head-' . $this->id );
  }

  /**
   * This action is called when a post is save or updated. Use the `save_post_{post_type}` hook
   *
   * @brief Save/update post
   * @note  You DO NOT override this method, use `update()` instead
   *
   * @param int|string $post_id Post ID
   * @param object     $post    Optional. Post object
   *
   * @return void
   */
  public function save_post( $post_id, $post = '' )
  {

    // Do not save...
    if ( ( defined( 'DOING_AUTOSAVE' ) && true === DOING_AUTOSAVE ) ||
         ( defined( 'DOING_AJAX' ) && true === DOING_AJAX ) ||
         ( defined( 'DOING_CRON' ) && true === DOING_CRON )
       ) {
      return;
    }

    // Get post type information
    $post_type        = get_post_type();
    $post_type_object = get_post_type_object( $post_type );

    // Exit
    if ( false == $post_type || is_null( $post_type_object ) ) {
      return;
    }

    // This function only applies to the following post_types
    if ( !in_array( $post_type, array( $this->id ) ) ) {
      return;
    }

    // Find correct capability from post_type arguments
    if ( isset( $post_type_object->cap->edit_posts ) ) {
      $capability = $post_type_object->cap->edit_posts;

      // Return if current user cannot edit this post
      if ( !current_user_can( $capability ) ) {
        return;
      }
    }

    // If all ok and post request then update()
    if ( wpdk_is_request_post() ) {
      $this->update( $post_id, $post );
    }

  }

  /**
   * Override this method to save/update your custom data.
   * This method is called by hook action save_post_{post_type}`
   *
   * @brief Update data
   *
   * @param int|string $post_id Post ID
   * @param object     $post    Optional. Post object
   *
   */
  public function update( $post_id, $post )
  {
    // You can override this method to save your own data
  }

  /**
   * This filter allow to display the content of column
   *
   * @brief Manage content columns
   *
   * @param array $column The column
   *
   * @return array
   */
  public function manage_posts_custom_column( $column )
  {
    // You can override this hook method
  }

  /**
   * This filter allow to change the columns of list table for this custom post type.
   *
   * @brief Manage columns
   *
   * @param array $columns The list table columns list array
   *
   * @return array
   */
  public function manage_edit_columns( $columns )
  {
    // You can override this hook method
    return $columns;
  }

  /**
   * List of sortable columns
   *
   * @brief Sortable columns
   *
   * @param array $columns Array Default sortable columns
   *
   * @return array
   */
  public function manage_edit_sortable_columns( $columns )
  {
    // You can override this hook method
    return $columns;
  }

  /**
   * Ovveride this hook to register your custom meta box
   *
   * @brief Meta box
   */
  public function register_meta_box()
  {
    // You can override this hook method
  }

  // -------------------------------------------------------------------------------------------------------------------
  // DEPRECATED
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Override this delegate method to change the pseudo-placeholder into the text input for title in edit/new post type form.
   *
   * @brief The placeholder in text input of post
   * @deprecated
   *
   * @param string $title Default placeholder
   *
   * @return string
   */
  public function shouldEnterTitleHere( $title )
  {
    // You can override this delegate method
    return $title;
  }

  /**
   * Fires in <head> for a specific admin page based on $hook_suffix.
   *
   * @brief Post Edit
   *
   * @since 1.6.0
   */
  public function _admin_head_post_php()
  {
    if ( $this->is() ) {

      /**
       * Fires when this custom post type is edit.
       *
       * The dynamic portion of the hook name, $id, refers to the custom post type id.
       */
      do_action( 'admin_head_post-' . $this->id );
    }
  }

  /**
   * Fires in <head> for a specific admin page based on $hook_suffix.
   *
   * @brief Posts list view
   *
   * @since 1.6.0
   */
  public function _admin_head_edit_php()
  {
    if ( $this->is() ) {

      /**
       * Fires when this custom post type is list in table view.
       *
       * The dynamic portion of the hook name, $id, refers to the custom post type id.
       */
      do_action( 'admin_head_edit-' . $this->id );
    }
  }

  /**
   * Fires in <head> for a specific admin page based on $hook_suffix.
   *
   * @brief Post New
   * @since 1.6.0
   */
  public function _admin_head_post_new_php()
  {
    if ( $this->is() ) {

      /**
       * Fires when this custom post type is new.
       *
       * The dynamic portion of the hook name, $id, refers to the custom post type id.
       */
      do_action( 'admin_head_post_new-' . $this->id );
    }
  }


  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @brief Post Edit
   *
   * @since 1.6.0
   */
  public function _admin_print_styles_post_php()
  {
    if ( $this->is() ) {

      /**
       * Fires when this custom post type is edit.
       *
       * The dynamic portion of the hook name, $id, refers to the custom post type id.
       */
      do_action( 'admin_print_styles_post-' . $this->id );
    }
  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @brief Posts list view
   * @since 1.6.0
   */
  public function _admin_print_styles_edit_php()
  {
    if ( $this->is() ) {

      /**
       * Fires when this custom post type is list in table view.
       *
       * The dynamic portion of the hook name, $id, refers to the custom post type id.
       */
      do_action( 'admin_print_styles_edit-' . $this->id );
    }
  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @brief Post New
   * @since 1.6.0
   */
  public function _admin_print_styles_post_new_php()
  {
    if ( $this->is() ) {

      /**
       * Fires when this custom post type is new.
       *
       * The dynamic portion of the hook name, $id, refers to the custom post type id.
       */
      do_action( 'admin_print_styles_post_new-' . $this->id );
    }
  }

}

/// @endcond