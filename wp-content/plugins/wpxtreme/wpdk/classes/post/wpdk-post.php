<?php

/**
 * The WPDKPost class is a WordPress Post as object.
 *
 * ## Overview
 * The WPDKPost class is a wrap of WordPress Post record. In addition this class provides a lot of methods and
 * properties.
 *
 * ### Properties naming
 * You'll see that a lot of properties class are written in lowercase and underscore mode as `$post_date`. This beacouse
 * they are a map of database record.
 *
 * ### Post onfly
 *
 * ### Create a virtual post
 *
 * @class              WPDKPost
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-08-26
 * @version            1.0.3
 *
 * @history            1.0.3 - Added post_meta property to manage the post meta
 *
 */
class WPDKPost extends WPDKObject {

  const COLUMN_COMMENT_COUNT         = 'comment_count';
  const COLUMN_COMMENT_STATUS        = 'comment_status';
  const COLUMN_GUID                  = 'guid';
  const COLUMN_ID                    = 'ID';
  const COLUMN_MENU_ORDER            = 'menu_order';
  const COLUMN_PINGED                = 'pinged';
  const COLUMN_PING_STATUS           = 'ping_status';
  const COLUMN_POST_AUTHOR           = 'post_author';
  const COLUMN_POST_CONTENT          = 'post_content';
  const COLUMN_POST_CONTENT_FILTERED = 'post_content_filtered';
  const COLUMN_POST_DATE             = 'post_date';
  const COLUMN_POST_DATE_GMT         = 'post_date_gmt';
  const COLUMN_POST_EXCERPT          = 'post_excerpt';
  const COLUMN_POST_MIME_TYPE        = 'post_mime_type';
  const COLUMN_POST_MODIFIED         = 'post_modified';
  const COLUMN_POST_MODIFIED_GMT     = 'post_modified_gmt';
  const COLUMN_POST_NAME             = 'post_name';
  const COLUMN_POST_PARENT           = 'post_parent';
  const COLUMN_POST_PASSWORD         = 'post_password';
  const COLUMN_POST_STATUS           = 'post_status';
  const COLUMN_POST_TITLE            = 'post_title';
  const COLUMN_POST_TYPE             = 'post_type';
  const COLUMN_TO_PING               = 'to_ping';

  /**
   * Override version
   *
   * @brief Version
   *
   * @var string $__version
   */
  public $__version = '1.0.3';

  /**
   * The post ID
   *
   * @brief ID Post
   *
   * @var int $ID
   */
  public $ID;

  /**
   * Number of comments, pings, and trackbacks combined
   *
   * @brief Comment count
   *
   * @var int $comment_count
   */
  public $comment_count;

  /**
   * The comment status, ie. open. Max 20 char
   *
   * @brief Comment status
   *
   * @var string $comment_status
   */
  public $comment_status;

  /**
   * The guid. Global Unique Identifier. The “real” URL to the post, not the permalink version. For pages, this is the
   * actual URL. In the case of files (attachments), this holds the URL to the file.
   *
   * @brief GUID
   *
   * @var string $guid
   */
  public $guid;

  /**
   * Holds values for display order of pages. Only works with pages, not posts.
   *
   * @brief Menu order
   *
   * @var int $menu_order
   */
  public $menu_order;

  /**
   * The ping status, ie. open. Max 20 char
   *
   * @brief Ping status
   *
   * @var string $ping_status
   */
  public $ping_status;

  /**
   * List of urls that have been pinged (for published posts)
   *
   * @brief List of pinged urls
   *
   * @var array $pinged
   */
  public $pinged;

  /**
   * The post author ID
   *
   * @brief ID author
   *
   * @var int $post_author
   */
  public $post_author;

  /**
   * Number representing post category ID#. This property is not present on databse record.
   *
   * @brief Category ID
   *
   * @var int $post_category
   */
  public $post_category;

  /**
   * The post content
   *
   * @brief Content
   *
   * @var string $post_content
   */
  public $post_content;

  /**
   * Exists to store a cached version of post content (most likely with all the the_content filters already applied).
   * If you’ve got a plugin that runs a very resource heavy filter on content, you might consider caching the results
   * with post_content_filtered, and calling that from the front end instead.
   *
   * @brief Content filtered
   *
   * @var string $post_content_filtered
   */
  public $post_content_filtered;

  /**
   * The Post date
   *
   * @brief Date Post
   *
   * @var string $post_date
   */
  public $post_date;

  /**
   * The post date in GMT
   *
   * @brief Date post in GMT
   *
   * @var string $post_date_gmt
   */
  public $post_date_gmt;

  /**
   * The post excerpt
   *
   * @brief Excerpt
   *
   * @var string $post_excerpt
   */
  public $post_excerpt;

  /**
   * The post mime type. Only used for files (attachments). Contains the MIME type of the uploaded file.
   * Typical values are: text/html, image/png, image/jpg
   *
   * @brief Mime type
   *
   * @var string $post_mime_type
   *
   */
  public $post_mime_type;

  /**
   * Modified date
   *
   * @brief Modified date
   *
   * @var string $post_modified
   */
  public $post_modified;

  /**
   * Modifed date in GMT
   *
   * @brief Modifed date in GMT
   *
   * @var string $post_modified_gmt
   */
  public $post_modified_gmt;

  /**
   * The post name. Same as post slug. Max 200 char
   *
   * @brief Name
   *
   * @var string $post_name
   */
  public $post_name;

  /**
   * Parent Post ID
   *
   * @brief Parent post
   *
   * @var int $post_parent
   */
  public $post_parent;

  /**
   * Protect post password. Will be empty if no password. Max 20 char
   *
   * @brief Password
   *
   * @var string $post_password
   */
  public $post_password;

  /**
   * Post status, ie. publish, draft. Max 20 char
   *
   * @brief Status
   *
   * @var string $post_status
   */
  public $post_status;

  /**
   * The post title
   *
   * @brief Title
   *
   * @var string $post_title
   */
  public $post_title;

  /**
   * The post type. Used by Custom Post. Default 'post'. Self-explanatory for pages and posts. Any files uploaded are
   * attachments and post revisions saved as revision
   *
   * @brief Type
   *
   * @var string $post_type
   */
  public $post_type = WPDKPostType::POST;

  /**
   * List of urls to ping when post is published (for unpublished posts)
   *
   * @brief List ping urls
   *
   * @var array $to_ping
   */
  public $to_ping;

  /**
   * A key values pair array with the list of post meta for this post.
   *
   * @brief Post meta
   * @since 1.5.13
   *
   * @var array $post_meta
   */
  public $post_meta;

  /**
   * Create an instance of WPDKPost class
   *
   * @brief Construct
   *
   * @param string|int|object|null $record    Optional. Post ID, post object, post slug or null
   * @param string                 $post_type Optional. If $record is a string (slug) then this is the post type where
   *                                          search. Default is 'page'
   *
   * @return WPDKPost
   */
  public function __construct( $record = null, $post_type = WPDKPostType::POST )
  {

    // Get post by id
    if ( ! is_null( $record ) && is_numeric( $record ) ) {
      $this->initPostByID( absint( $record ) );
    }

    // Get post from database record
    elseif ( ! is_null( $record ) && is_object( $record ) && isset( $record->ID ) ) {
      $this->initPostByPost( $record );
    }

    // Get post by name
    elseif ( ! is_null( $record ) && is_string( $record ) ) {

      // Try by path
      $object = get_page_by_path( $record, OBJECT, $post_type );

      if ( is_null( $object ) ) {

        // Try by title
        $object = get_page_by_title( $record, OBJECT, $post_type );
      }

      if ( ! is_null( $object ) ) {
        $this->initPostByPost( $object );
      }
    }

    // Create an empty post
    elseif ( is_null( $record ) ) {

      // Save post type
      $this->post_type = $post_type;

      // Create a new onfly post
      $defaults = $this->postEmpty();
      $this->initPostByArgs( $defaults );
    }

    // Get the post meta
    if ( isset( $this->ID ) && ! empty( $this->ID ) ) {
      $this->post_meta = get_post_meta( $this->ID );
    }

  }

  // -------------------------------------------------------------------------------------------------------------------
  // Clone
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Return an instance of WPDKPost as clone of input post. The new 'duplicate' post is stored on db.
   *
   * @brief Dusplicate
   * @since 1.5.16
   *
   * @param string|int|object $post       Post ID, post object, post slug.
   * @param array             $args       Optional. Additional args.
   *                                      {
   *
   * @type string             $status     The post status. Default 'draft'.
   * @type bool               $taxonomies Whether to duplicate taxonomies. Default TRUE.
   * @type bool               $post_meta  Whether to duplicate post meta. Default TRUE.
   *        }
   *
   * @return WPDKPost
   */
  public static function duplicate( $post, $args = array() )
  {
    // Defaults args
    $defaults = array(
      'status'     => WPDKPostStatus::DRAFT,
      'taxonomies' => true,
      'post_meta'  => true,
    );

    $args = wp_parse_args( $args, $defaults );

    // Get original post
    $original = new self( $post );

    // Create an empty post
    $duplicate = new self;

    // Loop in properties
    foreach ( $original as $property => $value ) {

      // Preserve some properties
      if ( ! in_array( $property, array( 'ID' ) ) ) {
        $duplicate->$property = $value;
      }
    }

    // Update the date
    $duplicate->post_date         = date( WPDKDateTime::MYSQL_DATE_TIME );
    $duplicate->post_date_gmt     = $duplicate->post_date;
    $duplicate->post_modified     = $duplicate->post_date;
    $duplicate->post_modified_gmt = $duplicate->post_date;

    // Status
    $duplicate->post_status = $args['status'];

    /**
     * Filter the WPDKPost object before insert.
     *
     * @param WPDKPost $duplicate An instance of WPDKPost class.
     */
    $duplicate = apply_filters( 'wpdk_post_before_insert_duplicate', $duplicate );

    // Insert
    $duplicate->insert();

    // Duplicate taxonomies
    if ( true === $args['taxonomies'] ) {

      // Clear default category (added by wp_insert_post)
      wp_set_object_terms( $duplicate->ID, null, 'category' );

      // Get taxonomy ID for this post
      $taxonomies = get_object_taxonomies( $original->post_type );

      //WPXtreme::log( $taxonomies );

      foreach ( $taxonomies as $taxonomy_id ) {
        // Get template categories
        $terms = wp_get_post_terms( $original->ID, $taxonomy_id );

        //WPXtreme::log( $terms );

        /*
         *    array(2) {
         *      [0]=> object(stdClass)#2746 (10) {
         *        ["term_id"]=> int(396)
         *        ["name"]=> string(8) "Template"
         *        ["slug"]=> string(8) "template"
         *        ["term_group"]=> int(0)
         *        ["term_taxonomy_id"]=> int(401)
         *        ["taxonomy"]=> string(14) "wpx_notify_tax"
         *        ["description"]=> string(17) "Used for template"
         *        ["parent"]=> int(0)
         *        ["count"]=> int(3)
         *        ["filter"]=> string(3) "raw"
         *      }
         *     [1]=>
         *      object(stdClass)#2745 (10) {
         *       ["term_id"]=> int(397)
         *        ["name"]=> string(8) "Warnings"
         *        ["slug"]=> string(8) "warnings"
         *        ["term_group"]=> int(0)
         *        ["term_taxonomy_id"]=> int(402)
         *        ["taxonomy"]=> string(14) "wpx_notify_tax"
         *        ["description"]=> string(0) ""
         *        ["parent"]=> int(0)
         *        ["count"]=> int(2)
         *        ["filter"]=> string(3) "raw"
         *      }
         *    }
         */

        $set_terms = array();
        foreach ( $terms as $term ) {
          $set_terms[] = $term->term_id;
        }

        if ( ! empty( $set_terms ) ) {
          wp_set_object_terms( $duplicate->ID, $set_terms, $taxonomy_id );
        }
      }
    }

    // Duplicate post meta
    if ( true == $args['post_meta'] ) {

      // Get post meta keys
      $post_meta_keys = get_post_custom_keys( $original->ID );

      //WPXtreme::log( $post_meta_keys, '$post_meta_keys' );

      // Loop into the meta key
      foreach ( $post_meta_keys as $meta_key ) {

        // Get the meta values - could br more that one
        $meta_values = get_post_custom_values( $meta_key, $original->ID );

        // Loop into meta values
        foreach ( $meta_values as $meta_value ) {
          $meta_value = maybe_unserialize( $meta_value );
          add_post_meta( $duplicate->ID, $meta_key, $meta_value );
        }
      }

    }

    return $duplicate;

  }


  // -------------------------------------------------------------------------------------------------------------------
  // Create/Get Post
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Init this instance of WPDKPost as post from Post ID
   *
   * @brief Init by Post ID
   *
   * @param int $id_post Post ID
   */
  private function initPostByID( $id_post )
  {
    if ( isset( $GLOBALS[ __CLASS__ ][ $id_post ] ) ) {
      $post = $GLOBALS[ __CLASS__ ][ $id_post ];
    }
    else {
      $GLOBALS[ __CLASS__ ][ $id_post ] = $post = get_post( $id_post );
    }
    $this->initPostByPost( $post );
  }

  /**
   * Init this instance of WPDKPost as post from Post database object
   *
   * @brief Init by Post object
   *
   * @param object $post The post database record
   */
  private function initPostByPost( $post )
  {
    if ( is_object( $post ) ) {

      // Get properties
      foreach ( $post as $property => $value ) {
        $this->$property = $value;
      }
    }
  }

  /**
   * Return a Key value pairs array with property and value for an empty post. The properties are set to a default
   * values.
   *
   * @brief Return an empty post key value pairs
   *
   * @return array
   */
  private function postEmpty()
  {
    $args = array(
      'ID'                    => 0,
      'post_author'           => get_current_user_id(),
      'post_date'             => '0000-00-00 00:00:00',
      'post_date_gmt'         => '0000-00-00 00:00:00',
      'post_content'          => '',
      'post_title'            => '',
      'post_excerpt'          => '',
      'post_status'           => WPDKPostStatus::PUBLISH,
      'comment_status'        => 'open',
      'ping_status'           => 'open',
      'post_password'         => '',
      'post_name'             => '',
      'to_ping'               => '',
      'pinged'                => '',
      'post_modified'         => '0000-00-00 00:00:00',
      'post_modified_gmt'     => '0000-00-00 00:00:00',
      'post_content_filtered' => '',
      'post_parent'           => 0,
      'guid'                  => '',
      'menu_order'            => 0,
      'post_type'             => $this->post_type,
      'post_mime_type'        => '',
      'comment_count'         => 0
    );

    return $args;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // Empty Post
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Init this instance of WPDKPost as a empty Post
   *
   * @brief Init by array arguments
   *
   * @param array|object $args An array with key values or and object.
   */
  private function initPostByArgs( $args )
  {
    foreach ( $args as $property => $value ) {
      $this->$property = $value;
    }
  }

  // -------------------------------------------------------------------------------------------------------------------
  // CRUD
  // -------------------------------------------------------------------------------------------------------------------

  /**
   * Publish a post.
   *
   * @since 1.5.16
   *
   * @return int|WP_Error
   */
  public function publish()
  {
    $this->post_status = WPDKPostStatus::PUBLISH;

    return $this->insert();
  }


  /**
   * Insert or update a post.
   *
   * @since 1.5.16
   *
   * @return int|WP_Error
   */
  public function insert()
  {
    $result = wp_insert_post( $this );

    $this->ID = empty( $result ) ? 0 : $result;

    return $this->ID;

  }

  /**
   * Delete permately this post from database
   *
   * @brief Delete
   *
   * @since 0.9
   * @uses  wp_delete_post() with second parameter to TRUE.
   *
   * @return mixed False on failure
   */
  public function delete()
  {
    return wp_delete_post( $this->ID, true );
  }

  /**
   * Moves a post or page to the Trash
   * If trash is disabled, the post or page is permanently deleted.
   *
   * @brief Set in trash
   *
   * @since 0.9
   * @uses  wp_trash_post()
   *
   * @return mixed False on failure
   */
  public function trash()
  {
    return wp_trash_post( $this->ID );
  }

  /**
   * Restores a post or page from the Trash
   *
   * @brief Set in trash
   *
   * @since 0.9
   * @uses  wp_untrash_post()
   *
   * @return mixed False on failure
   */
  public function untrash()
  {
    return wp_untrash_post( $this->ID );
  }

  /**
   * Update this post on database. Also this method check if you are in admin backend area for this custom post.
   * In this case the post update if turn off and save the post meta only.
   * Return value 0 or WP_Error on failure. The post ID on success.
   *
   * @brief Update
   *
   * @since 0.9
   * @uses  wp_update_post()
   *
   * @return int|WP_Error
   */
  public function update()
  {
    // Avoid update when we are in admin backend area
    global $pagenow;

    if ( 'post.php' != $pagenow ) {
      return wp_update_post( $this, true );
    }

    return false;
  }

  /**
   * Update meta
   *
   * @brief Update meta
   * @since 1.4.20
   *
   * @param array $args
   */
  public function updateMeta( $args = array() )
  {
    self::updateMetaWithID( $this->ID, $args );
  }

  /**
   * Update the post meta with post id
   *
   * @brief Brief
   * @since 1.4.20
   *
   * @param int   $post_id Post ID
   * @param array $args    Key value pairs array with meta_key => meta_value
   */
  public static function updateMetaWithID( $post_id, $args = array() )
  {
    if ( ! empty( $post_id ) && ! empty( $args ) ) {
      foreach ( $args as $meta_key => $meta_value ) {
        update_post_meta( $post_id, $meta_key, $meta_value );
      }
    }
  }

  /**
   * Return or set a single post meta value
   *
   * @brief    Meta value
   * @since    1.3.1
   *
   * @param string $meta_key Meta key
   *
   * @var  mixed   $value    Optional. If set is the value to store
   *
   * @return bool|mixed
   */
  public function metaValue( $meta_key )
  {
    if ( empty( $this->ID ) ) {
      return false;
    }
    if ( func_num_args() > 1 ) {
      $value = func_get_arg( 1 );

      return update_post_meta( $this->ID, $meta_key, $value );
    }

    return get_post_meta( $this->ID, $meta_key, true );
  }

  /**
   * Return o set post meta values
   *
   * @brief    Meta values
   * @since    1.3.1
   *
   * @param string $meta_key Meta key
   *
   * @var mixed    $value    Optional. If set is the value to store
   *
   * @return bool|mixed
   */
  public function metaValues( $meta_key )
  {
    if ( empty( $this->ID ) ) {
      return false;
    }
    if ( func_num_args() > 1 ) {
      $value = func_get_arg( 1 );

      return update_post_meta( $this->ID, $meta_key, $value );
    }

    return get_post_meta( $this->ID, $meta_key );
  }

  /**
   * Return an instance of WPDKHTMLTagImg class with thumbmail image description. If the thumbnail is not found return
   * FALSE. You can use the WPDKHTMLTagImg instance to read the property, get the HTML markup or display the image.
   *
   * @brief Get thumbnail image
   * @since 1.3.1
   *
   * @param string $size Optional. Default 'full'.
   *
   * @return bool|WPDKHTMLTagImg
   */
  public function thumbnail( $size = 'full' )
  {
    $thumbnail = self::thumbnailWithID( $this->ID, $size );

    if( empty( $thumbnail ) ) {
      trigger_error( 'Thumbnail not found for post ID = ' . $this->ID . ' and size = ' . $size );
    }

    return $thumbnail;
  }

  /**
   * Return an instance of WPDKHTMLTagImg class with thumbmail image description. If the thumbnail is not found return
   * FALSE. You can use the WPDKHTMLTagImg instance to read the property, get the HTML markup or display the image.
   *
   * @brief Get thumbnail image
   * @since 1.3.1
   *
   * @param int    $post_id Post id
   * @param string $size    Optional. Default 'full'
   *
   * @return bool|WPDKHTMLTagImg
   */
  public static function thumbnailWithID( $post_id, $size = 'full' )
  {
    if ( empty( $post_id ) || $post_id != absint( $post_id ) ) {
      return false;
    }

    if ( function_exists( 'has_post_thumbnail' ) ) {
      if ( has_post_thumbnail( $post_id ) ) {
        $thumbnail_id = get_post_thumbnail_id( $post_id );
        $image        = wp_get_attachment_image_src( $thumbnail_id, $size );

        // Get src attribute
        $src = $image[0];

        // Get the attachment alt text
        $alt = trim( strip_tags( get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) ) );

        // Get the attachment caption
        $caption = get_post_field( 'post_excerpt', $thumbnail_id );

        $img = new WPDKHTMLTagImg( $src, $alt );
        if ( ! empty( $caption ) ) {
          $img->addData( 'caption', $caption );
        }
        $img->addData( 'thumbnail_id', $thumbnail_id );
        $img->addData( 'post_id', $post_id );
        $img->addData( 'size', $size );

        return $img;
      }
    }

    return false;
  }

  /**
   * Return the nth instance of WPDKHTMLTagImg class as attachment image in this post.
   *
   *     self::imageFromAttachmentsWithID( 2294, 'thumbnail' )->display();
   *
   * @brief Attachment image
   * @since 1.3.1
   *
   * @param string $size  Optional. Size of attachment image
   * @param int    $index Optional. Index of image. Default first attach image is returned
   *
   * @return bool|WPDKHTMLTagImg
   */
  public function imageAttachments( $size = 'full', $index = 1 )
  {
    return self::imageAttachmentsWithID( $this->ID, $size, $index );
  }

  /**
   * Return the nth instance of WPDKHTMLTagImg class as attachment image in a post.
   *
   *     self::imageFromAttachmentsWithID( 2294, 'thumbnail' )->display();
   *
   * @brief Attachment image
   * @since 1.3.1
   *
   * @param int    $post_id Post id
   * @param string $size    Optional. Size of attachment image
   * @param int    $index   Optional. Index of image. Default first attach image is returned
   *
   * @return bool|WPDKHTMLTagImg
   */
  public static function imageAttachmentsWithID( $post_id, $size = 'full', $index = 1 )
  {
    // Check for support
    if ( function_exists( 'wp_get_attachment_image' ) ) {
      $args     = array(
        'post_parent'    => $post_id,
        'post_type'      => WPDKPostType::ATTACHMENT,
        'numberposts'    => -1,
        'post_status'    => WPDKPostStatus::INHERIT,
        'post_mime_type' => 'image',
        'order'          => 'ASC',
        'orderby'        => 'menu_order ASC'
      );
      $children = get_children( $args );

      if ( empty( $children ) || ! is_array( $children ) ) {
        return false;
      }

      // Get the first
      $item = current( $children );

      // Try to get the $index element
      if ( $index > 1 ) {
        $item = current( array_slice( $children, $index - 1, 1 ) );
      }

      if ( is_object( $item ) && isset( $item->ID ) ) {
        $thumbnail_id = $item->ID;

        $image = wp_get_attachment_image_src( $thumbnail_id, $size );
        $src   = $image[0];

        // Get the attachment alt text
        $alt = trim( strip_tags( get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) ) );

        // Get the attachment caption
        $caption = get_post_field( 'post_excerpt', $thumbnail_id );

        $img = new WPDKHTMLTagImg( $src, $alt );
        if ( ! empty( $caption ) ) {
          $img->addData( 'caption', $caption );
        }
        $img->addData( 'thumbnail_id', $thumbnail_id );
        $img->addData( 'post_id', $post_id );
        $img->addData( 'size', $size );

        return $img;
      }
    }

    return false;
  }

  /**
   * Return an instance of WPDKHTMLTagImg class with the first image found in this post content.
   *
   * @brief image in post content
   * @since 1.3.1
   *
   * @return bool|WPDKHTMLTagImg
   */
  public function imageContent()
  {
    return self::imageContentWithID( $this->ID );
  }

  /**
   * Return an instance of WPDKHTMLTagImg class with the first image found in the post content.
   *
   * @brief image in post content
   * @since 1.3.1
   *
   * @param int $post_id Post ID
   *
   * @return bool|WPDKHTMLTagImg
   */
  public static function imageContentWithID( $post_id )
  {
    // Search the post's content for the <img /> tag and get its URL
    preg_match_all( '|<img.*?src=[\'"](.*?)[\'"].*?>|i', get_post_field( 'post_content', $post_id ), $matches );

    // If there is a match for the image, return its URL
    if ( isset( $matches ) && is_array( $matches ) && ! empty( $matches[1][0] ) ) {
      $src = $matches[1][0];

      $img = new WPDKHTMLTagImg( $src, '' );
      $img->addData( 'post_id', $post_id );

      return $img;
    }

    return false;
  }

}

/**
 * WordPress stan dard Post Status at 3.4 release
 *
 * @class              WPDKPostStatus
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2013-05-30
 * @version            1.0.0
 *
 */
class WPDKPostStatus {

  const AUTO_DRAFT = 'auto-draft';
  const DRAFT      = 'draft';
  const FUTURE     = 'future';
  const INHERIT    = 'inherit';
  const PENDING    = 'pending';

  /**
   * @note Sorry by PRIVATE  by it is a php keyword so I have used PRIVATE_ instead
   */
  const PRIVATE_ = 'private';
  const PUBLISH  = 'publish';
  const TRASH    = 'trash';

  /**
   * Return a readable and filtered key-value array with the list of status for a post.
   *
   * @brief Readable post statuses
   * @uses  apply_filters() calls `wpdk-posts-statuses` hook to allow overwriting the statuses list.
   *
   * @return mixed|void
   */
  public static function statuses()
  {
    $statuses = array(
      self::AUTO_DRAFT => __( 'A newly created post, with no content', WPDK_TEXTDOMAIN ),
      self::DRAFT      => __( 'The post is draft', WPDK_TEXTDOMAIN ),
      self::FUTURE     => __( 'The post to publish in the future', WPDK_TEXTDOMAIN ),
      self::INHERIT    => __( 'The post is a revision', WPDK_TEXTDOMAIN ),
      self::PENDING    => __( 'The post is pending review', WPDK_TEXTDOMAIN ),
      self::PRIVATE_   => __( 'Not visible to users who are not logged in', WPDK_TEXTDOMAIN ),
      self::PUBLISH    => __( 'A published post or page', WPDK_TEXTDOMAIN ),
      self::TRASH      => __( 'The post is in trashbin', WPDK_TEXTDOMAIN ),
    );

    return apply_filters( 'wpdk-posts-statuses', $statuses );
  }
}

/// @cond private
/* Backward copatibility */

class _WPDKPost extends WPDKPost {

}

/// @endcond

/**
 * WordPress standard Post Type at 3.4 release.
 *
 * @class              WPDKPostType
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2012-11-28
 * @version            0.8.1
 *
 */
class WPDKPostType {

  const ATTACHMENT    = 'attachment';
  const NAV_MENU_ITEM = 'nav_menu_item';
  const PAGE          = 'page';
  const POST          = 'post';
  const REVISION      = 'revision';
}

/**
 * Utility class for post meta. You usually extends this class in your custom post type meta definition.
 *
 * @class              WPDKPostMeta
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-08-25
 * @version            1.0.0
 *
 * @since              1.5.13
 *
 */
class WPDKPostMeta {

  /**
   * An instance of WPDKPost class.
   *
   * @brief Post
   *
   * @var WPDKPost $post
   */
  public $post;

  /**
   * List of meta key with property and label info.
   *
   * @brief Meta keys
   *
   * @var array $meta
   */
  public $meta = array();

  /**
   * Create aninstance of WPDKPostMeta class
   *
   * @brief Construct
   *
   * @param WPDKPost $post Optional. An instance of WPDKPost class.
   *
   * @return WPDKPostMeta
   */
  public function __construct( $post = null )
  {
    $this->post = $post;
    $this->meta();
  }

  /**
   * Register meta keys. Use
   *
   *     $this->add( self::META_KEY_BANNER_EXTERNAL_URL, 'banner_external_url', '', __( 'External URL',
   *     WPXBANNERIZE_TEXTDOMAIN ) );
   *
   * @brief Register
   *
   * @return array
   */
  public function meta()
  {

    // $this->add( self::META_KEY_BANNER_EXTERNAL_URL, 'banner_external_url', '', __( 'External URL', WPXBANNERIZE_TEXTDOMAIN ) );

    die( __METHOD__ . ' must be override in your subclass' );
  }

  /**
   * Add a meta key.
   *
   * @brief Add
   *
   * @param string $meta_key Meta key.
   * @param string $property Optional. Object proprty name.
   * @param mixed  $default  Optional. Default value.
   * @param string $label    Optional. Label definition.
   *
   * @return array
   */
  public function add( $meta_key, $property = '', $default = false, $label = '' )
  {
    $this->meta[ $meta_key ] = array(
      'property' => $property,
      'label'    => $label,
      'default'  => $default,
    );

    return $this->meta;
  }

  /**
   * Map the post meta property (if defined) to post.
   *
   * @brief Map
   *
   * @param bool $create_property Optional. Default TRUE when in the target object the property doesn't exists then it
   *                              is dynamically created. Set FALSE to map only the existing properties.
   *
   * @return WPDKPost
   */
  public function mapToPost( $create_property = true )
  {
    // Get all post meta for this post id
    $meta = $this->post->post_meta;

    /*
     *    array(14) {
     *      ["_edit_lock"]=> array(1) {
     *        [0]=> string(12) "1408973442:1"
     *      }
     *      ["_edit_last"]=> array(1) {
     *        [0]=> string(1) "1"
     *      }
     *      ["wpx_bannerize_banner_type"]=> array(1) {
     *        [0]=> string(5) "local"
     *      }
     *      ["wpx_bannerize_banner_url"]=> array(1) {
     *        [0]=> string(89) "http://sp-museo-maranello.s3.amazonaws.com/wp-content/uploads/2014/07/599xx-1140x4371.jpg"
     *      }
     *      ["wpx_bannerize_banner_external_url"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_link"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_target"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_width"]=> array(1) {
     *        [0]=> string(4) "1140"
     *      }
     *      ["wpx_bannerize_banner_height"]=> array(1) {
     *        [0]=> string(3) "437"
     *      }
     *      ["wpx_bannerize_banner_no_follow"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_impressions_enabled"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_clicks_enabled"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_date_from"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *      ["wpx_bannerize_banner_date_expiry"]=> array(1) {
     *        [0]=> string(0) ""
     *      }
     *    }
     */

    if ( empty( $meta ) ) {
      return $this->post;
    }

    // Loop in the all meta
    foreach ( $meta as $meta_key => $array ) {

      // Check if meta key exists
      if ( isset( $this->meta[ $meta_key ] ) ) {

        // Get the relative property
        $property = $this->meta[ $meta_key ]['property'];

        // If property not set continue
        if ( empty( $property ) ) {
          continue;
        }

        // Stability, check if the target object has the property
        if ( property_exists( $this->post, $property ) || $create_property ) {

          // Get single value
          $this->post->$property = $array[0];
        }
      }
    }

    return $this->post;
  }

  /**
   * Update post meta by using WPDKPost object properties.
   *
   * @brief Update
   */
  public function update()
  {
    // Get post id
    $post_id = $this->post->ID;

    // Get meta
    $meta = $this->meta();

    if ( empty( $meta ) ) {
      return false;
    }

    // Loop into the registered meta
    foreach ( $meta as $meta_key => $array ) {

      // Get relative property
      $property = $array['property'];

      if ( empty( $array['property'] ) ) {
        continue;
      }

      // Stability, check if the target object has the property
      if ( property_exists( $this->post, $property ) ) {

        // Update
        return update_post_meta( $post_id, $meta_key, $this->post->$property );
      }
    }

    return false;
  }

  /**
   * Update post meta by using an array.
   *
   * @brief Update
   *
   * @param int   $post_id The post ID.
   * @param array $values  A key value pairs array with key as post meta key and value as post meta value.
   */
  public static function updateWithArray( $post_id, $values )
  {

    // Loop into the registered meta
    foreach ( $values as $meta_key => $value ) {

      // Update
      update_post_meta( $post_id, $meta_key, $value );
    }

  }


}