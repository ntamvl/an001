<?php

/**
 * Description
 *
 * @class           WPDKUIPageView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-29
 * @version         1.0.0
 *
 */
class WPDKUIPageView extends WPDKView
{

  // ID view
  const ID = 'wpdk-ui-page-view';

  // HTML page seprator. Used in initWithHTML() and in the your HTML markup.
  const HTML_PAGE_SEPARATOR = '<!-- WPDK Pages -->';

  /**
   * Internal static pointer for static method.
   *
   * @brief Instance
   *
   * @var WPDKUIPageView $instance
   */
  private static $instance = null;

  /**
   * List of views to paged. This can be a list of instance of WPDKView, string content or callable user functions.
   *
   * @brief Views
   *
   * @var array $views
   */
  public $views = array();

  /**
   * Return a singleton instance of WPDKUIPageView class
   *
   * @brief Singleton
   *
   * @return WPDKUIPageView
   */
  public static function init()
  {
    if ( is_null( self::$instance ) ) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Return a singleton instance of WPDKUIPageView class
   *
   * @brief Singleton
   *
   * @return WPDKUIPageView
   */
  public static function initWithPages( $views )
  {
    // Init
    self::init();

    // Save the views
    self::$instance->views = $views;

    // Return instance
    return self::$instance;
  }

  public static function initWithHTML( $html )
  {
    // Init
    self::init();

    // Explode HTML
    $views = explode( self::HTML_PAGE_SEPARATOR, $html );

    // Save the views
    self::$instance->views = $views;

    // Return instance
    return self::$instance;
  }

  /**
   * Create an instance of WPDKUIPageView class
   *
   * @brief Construct
   *
   * @return WPDKUIPageView
   */
  public function __construct()
  {
    parent::__construct( self::ID, 'wpdk-ui-page-view-main-container' );
  }

  /**
   * Return the array with list of views. You can override this method when subclass this class.
   *
   * @brief Views
   *
   * @return array
   */
  public function views()
  {
    return $this->views;
  }

  /**
   * Display
   *
   * @brief Display
   */
  public function draw()
  {
    // Get the views
    $views = $this->views();

    // If no views available exit
    if( empty( $views ) ) {
      return;
    }

    // Prepare an index
    $index = 0;

    ?><div class="wpdk-ui-page-view-mask"><?php

    /**
     * Loop into the views
     *
     * @var WPDKView $view
     */
    foreach( $views as $view ) : ?>

      <div data-view="<?php echo $index++ ?>" class="wpdk-ui-page-view-container">
        <?php
        // WPDKView
        if ( is_object( $view ) && $view instanceof WPDKView ) {
          $view->display();
        }
        // String content
        elseif ( is_string( $view ) ) {
          echo $view;
        }
        // Callable user function
        elseif ( is_callable( $view ) ) {
          call_user_func( $view );
        }
        ?>
      </div>

    <?php
    endforeach;
    ?></div><?php
  }

  /**
   * Return the HTML markup for navigator in page.
   *
   * @brief Navigator
   */
  public function navigator()
  {
    WPDKHTML::startCompress();

    // Get the views
    $views = $this->views();

    // If no views available exit
    if( empty( $views ) ) {
      return;
    }

    ?>
    <div class="wpdk-ui-navigator">
    <?php

    // Prepare an index
    $index = 0;

    /**
     * Loop into the views
     *
     * @var WPDKView $view
     */
    foreach( $views as $view ) : ?>

      <a href="#" class="wpdk-ui-navigator-bullet <?php echo empty( $index ) ? 'current' : '' ?>" data-bullet="<?php echo $index++ ?>" ></a>

    <?php endforeach; ?>

    </div>
    <?php

    return WPDKHTML::endCompress();
  }

}