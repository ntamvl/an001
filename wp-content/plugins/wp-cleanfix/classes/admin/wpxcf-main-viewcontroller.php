<?php

/**
 * Main view controller
 *
 * @class              WPXCleanFixMainViewController
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-02-08
 * @version            1.0.1
 */
class WPXCleanFixMainViewController extends WPDKViewController {

  /**
   * Return a singleton instance of WPXCleanFixMainViewController class
   *
   * @brief Singleton
   *
   * @return WPXCleanFixMainViewController
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
   * Create an instance of WPXCleanFixMainViewController class
   *
   * @brief Construct
   *
   * @return WPXCleanFixMainViewController
   */
  public function __construct()
  {
    parent::__construct( 'wpxcf-main', __( 'Clean & Fix', WPXCLEANFIX_TEXTDOMAIN ) );

    $view = new WPXCleanFixMainView();
    $this->view->addSubview( $view );
  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @since WP 2.6.0
   */
  public function admin_print_styles()
  {
    wp_enqueue_style( 'wpxcf-admin', WPXCLEANFIX_URL_CSS . 'wpxcf-admin.css', array(), WPXCLEANFIX_VERSION );

    /**
     * Fires when admin print styles is fired.
     *
     * @since 1.2.92
     */
    do_action( 'wpxcf_admin_print_styles' );
  }

  /**
   * This method is called when the head of this view controller is loaded by WordPress.
   * It is used by WPDKMenu for example, as 'admin_head-' action.
   */
  public function admin_head()
  {
    // Tooltip
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::TOOLTIP );

    // Scripts
    wp_enqueue_script( 'wpxcf-admin', WPXCLEANFIX_URL_JAVASCRIPT . 'wpxcf-admin.js', array(), WPXCLEANFIX_VERSION, true );

    $localization = array(
      'ajax_nonce' => wp_create_nonce(),
    );

    wp_localize_script( 'wpxcf-admin', 'wpxcf_i18n', $localization );

    /**
     * Fires when admin print styles is fired.
     *
     * @since 1.2.92
     */
    do_action( 'wpxcf_admin_head' );

  }

  /**
   * This static method is called when the head of this view controller is loaded by WordPress.
   */
  public function load()
  {
    // Enqueue extra components
    wp_enqueue_script( 'common' );
    wp_enqueue_script( 'wp-lists' );
    wp_enqueue_script( 'postbox' );

    // Get current screen
    $screen = get_current_screen();

    /**
     * @var WPXCleanFixModule $module
     */
    foreach ( WPXCleanFixModulesController::init()->modules as $key => $module ) {

      // Create the view here
      $view = new WPXCleanFixModuleView( $module );

      // Sanitize a title of metabox
      $title = sprintf( '<span data-module="%s" title="%s" class="wpxcf-button-action wpxcf-button-action-refresh-all"></span>%s', $module->id, __( 'Refresh All', WPXCLEANFIX_TEXTDOMAIN ), $module->name );

      // Add the metabox
      add_meta_box( $module->id, $title, array( $view, 'display' ), $screen->id, 'normal', 'core' );
    }
  }

}

/**
 * The custom view
 *
 * @class         WPXCleanFixMainView
 * @author        =undo= <info@wpxtre.me>
 * @copyright     Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date          2013-02-06
 * @version       1.0.0
 */
class WPXCleanFixMainView extends WPDKView {

  /**
   * Create an instance of WPXCleanFixMainView class
   *
   * @brief Construct
   *
   * @return WPXCleanFixMainView
   */
  public function __construct()
  {
    parent::__construct( 'wpxcf-main' );
  }

  /**
   * Drawing view
   *
   * @brief Draw
   */
  public function draw()
  {
    global $screen_layout_columns;

    $screen = get_current_screen();

    ?>

    <?php wp_nonce_field( 'wpx_cleanfix' ); ?>
    <?php wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ) ?>
    <?php wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false ) ?>
    <input type="hidden" name="action" value="save_wpx_cleanfix" />

    <div id="poststuff"
         class="metabox-holder<?php echo ( $screen_layout_columns == 2 ) ? ' has-right-sidebar' : ''; ?>">
    <div id="side-info-column" class="inner-sidebar">
      <?php echo 'static' ?>
    </div>
    <div id="post-body" class="has-sidebar">
      <div id="post-body-content" class="has-sidebar-content">
        <?php do_meta_boxes( $screen->id, 'normal', '' ) ?>
      </div>
    </div>
  </div>

    <script type="text/javascript">
    //<![CDATA[
    jQuery( document ).ready( function ()
    {
      // close postboxes that should be closed
      jQuery( '.if-js-closed' ).removeClass( 'if-js-closed' ).addClass( 'closed' );
      // postboxes setup
      postboxes.add_postbox_toggles( '<?php echo $screen->id ?>' );
    } );
    //]]>
  </script>

  <?php
  }
}
