<?php

/**
 * Preferences View controller
 *
 * @class           WPXtremePreferencesViewController
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2014-01-20
 * @version         0.1.2
 */
class WPXtremePreferencesViewController extends WPDKPreferencesViewController {

  /**
   * Return a singleton instance of WPXtremePreferencesViewController class
   *
   * @return WPXtremePreferencesViewController
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
   * Create an instance of WPXtremePreferencesViewController class
   *
   * @return WPXtremePreferencesViewController
   */
  public function __construct()
  {
    // Single instances of tab content
    $wpxstore   = new WPXtremePreferencesWPXStoreBranchView();
    $appearance = new WPXtremePreferencesAppearanceBranchView();
    $list_table = new WPXtremePreferencesListTableBranchView();
    $core       = new WPXtremePreferencesCoreBranchView();

    // Create each single tab
    $tabs = array(
      new WPDKjQueryTab( $wpxstore->id, __( 'WPX Store', WPXTREME_TEXTDOMAIN ), $wpxstore->html() ),
      new WPDKjQueryTab( $appearance->id, __( 'Appearance', WPXTREME_TEXTDOMAIN ), $appearance->html() ),
      new WPDKjQueryTab( $list_table->id, __( 'List Table', WPXTREME_TEXTDOMAIN ), $list_table->html() ),
      new WPDKjQueryTab( $core->id, __( 'Core', WPXTREME_TEXTDOMAIN ), $core->html() ),
    );

    parent::__construct( WPXtremePreferences::init(), __( 'wpXtreme Preferences', WPXTREME_TEXTDOMAIN ), $tabs );

  }

  /**
   * Fires when styles are printed for a specific admin page based on $hook_suffix.
   *
   * @since WP 2.6.0
   */
  public function admin_print_styles()
  {
    wp_enqueue_style( 'wpxm-preferences', WPXTREME_URL_CSS . 'wpxm-preferences.css', array(), WPXTREME_VERSION );
  }

  /**
   * Enqueue scripts and styles
   */
  public function admin_head()
  {
    // Dependences - Popover (and Tooltip)
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::PREFERENCES, WPDKUIComponents::POPOVER );

    // Enqueue Preferences components
    wp_enqueue_script( 'wpxm-preferences', WPXTREME_URL_JAVASCRIPT . 'wpxm-preferences.js', array(), WPXTREME_VERSION );

  }
}

/**
 * Display the WPX Store preferences.
 *
 * @class           WPXtremePreferencesWPXStoreBranchView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-05-21
 * @version         1.0.0
 *
 */
class WPXtremePreferencesWPXStoreBranchView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXtremePreferencesWPXStoreBranchView class
   *
   * @return WPXtremePreferencesWPXStoreBranchView
   */
  public function __construct()
  {
    $preferences = WPXtremePreferences::init();
    parent::__construct( $preferences, 'wpxstore' );
  }

  /**
   * Return the array fields
   *
   * @param WPXtremePreferencesWPXStoreBranch $branch
   *
   * @return array|void
   */
  public function fields( $branch )
  {

    $fields = array(

      __( 'WordPress Integration' ) => array(
        __( 'From here, you can manage wpXtreme products integration with WordPress repository' ),
        array(
          array(
            'type'  => WPDKUIControlType::SWITCH_BUTTON,
            'name'  => WPXtremePreferencesWPXStoreBranch::DISPLAY_PROFILE,
            'label' => __( 'Display Profile' ),
            'value' => $branch->display_profile,
          )
        ),
        array(
          array(
            'type'  => WPDKUIControlType::SWITCH_BUTTON,
            'name'  => WPXtremePreferencesWPXStoreBranch::PLUGINS_INTEGRATION,
            'label' => __( 'Plugins Integration' ),
            'value' => $branch->plugins_integration,
          )
        ),
      ),
      __( 'WPX Store ID' )          => array(

        empty( $branch->token ) ? array(
          array(
            'id'             => 'wpxm-preferences-wpxstore-branch',
            'type'           => WPDKUIControlType::ALERT,
            'alert_type'     => WPDKUIAlertType::WARNING,
            'dismiss_button' => false,
            'title'          => WPDKGlyphIcons::html( WPDKGlyphIcons::ATTENTION ) . __( 'Warning!' ),
            'value'          => __( 'Sign in with your wpXtreme credentials in order to access and display your wpXtreme plugins. Use the <strong>API SECRET KEY</strong> we sent you by email. You can recover it from your profile.' )
          )
        ) : WPDKGlyphIcons::html( WPDKGlyphIcons::OK ) .
            __( 'Congratulations! You have logged in the WPX Store. You can change your <strong>API SECRET KEY</strong> any time from your wpXtreme profile.' ),
        array(
          array(
            'type'         => WPDKUIControlType::TEXT,
            'name'         => WPXtremePreferencesWPXStoreBranch::SECRET_KEY,
            'label'        => __( 'API SECRET KEY' ),
            'placeholder'  => __( 'Your account API secret key' ),
            'value'        => $branch->secret_key,
            'autocomplete' => 'off',
          )
        ),
        array(
          array(
            'type'        => WPDKUIControlType::EMAIL,
            'name'        => WPXtremePreferencesWPXStoreBranch::USER_ID,
            'label'       => __( 'Username' ),
            'placeholder' => __( 'Your WPX Store ID' ),
            'value'       => $branch->user_id,
          )
        ),
        array(
          array(
            'type'  => WPDKUIControlType::PASSWORD,
            'name'  => WPXtremePreferencesWPXStoreBranch::PASSWORD,
            'label' => __( 'Password' ),
            'value' => '',
          )
        ),
      ),
    );

    return $fields;
  }
}


/**
 * Appearance View
 *
 * @class               WPXtremePreferencesAppearanceBranchView
 * @author              =undo= <info@wpxtre.me>
 * @copyright           Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date                2013-08-16
 * @version             0.1.0
 *
 */
class WPXtremePreferencesAppearanceBranchView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXtremePreferencesAppearanceBranchView class
   *
   * @return WPXtremePreferencesAppearanceBranchView
   */
  public function __construct()
  {
    $preferences = WPXtremePreferences::init();
    parent::__construct( $preferences, 'appearance' );

    // Filter the HTML markup for update button.
    add_filter( 'wpdk_preferences_button_update-appearance', '__return_false' );
  }

  /**
   * Return the sdf array for the form fields
   *
   * @param WPXtremePreferencesAppearanceBranch $appearance An instance of preferences branch
   *
   * @return array
   */
  public function fields( $appearance )
  {

    $fields = array(
      __( 'Improvements', WPXTREME_TEXTDOMAIN ) => array(

        __( 'These settings apply only to your current user', WPXTREME_TEXTDOMAIN ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'display_ajax',
            'label_right' => $title = __( 'Display Ajax Loader', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->display_ajax,
            'data'        => array( 'body_class' => 'wpxm-body-display-ajax-loader' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'display_ajax', $title ),
          ),
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'toolbars',
            'label_right' => $title = __( 'Toolbars', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->toolbars,
            'data'        => array( 'body_class' => 'wpxm-body-toolbars' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'toolbars', $title ),
          ),
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'inputs',
            'label_right' => $title = __( 'Input Fields', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->inputs,
            'data'        => array( 'body_class' => 'wpxm-body-inputs' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'inputs', $title ),
          ),
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'tables',
            'label_right' => $title = __( 'Tables & List Tables', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->tables,
            'data'        => array( 'body_class' => 'wpxm-body-tables' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'tables', $title ),

          ),
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'table_actions',
            'label_right' => $title = __( 'Table action links', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->table_actions,
            'data'        => array( 'body_class' => '' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'table_actions', $title ),
          ),
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'inline_edit',
            'label_right' => $title = __( 'Quick Edit Layout', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->inline_edit,
            'data'        => array( 'body_class' => 'wpxm-body-inline_edit' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'quick_edit', $title ),
          ),
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'remove_revisions',
            'label_right' => $title = __( '"Remove All Revisions" Button', WPXTREME_TEXTDOMAIN ),
            'value'       => $appearance->remove_revisions,
            'data'        => array( 'body_class' => 'wpxm-body-remove_revisions' ),
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'remove_revisions', $title ),
          ),
        ),
      ),
    );

    return $fields;
  }
}


/**
 * Lst Table View
 *
 * @class               WPXtremePreferencesListTableBranchView
 * @author              =undo= <info@wpxtre.me>
 * @copyright           Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date                2013-08-16
 * @version             0.1.0
 *
 */
class WPXtremePreferencesListTableBranchView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXtremePreferencesListTableBranchView class
   *
   * @return WPXtremePreferencesListTableBranchView
   */
  public function __construct()
  {
    $preferences = WPXtremePreferences::init();
    parent::__construct( $preferences, 'list_table' );

    // Filter the HTML markup for update button.
    add_filter( 'wpdk_preferences_button_update-list_table', '__return_false' );
  }

  /**
   * Return the sdf array for the form fields
   *
   * @param WPXtremePreferencesListTableBranch $list_table An instance of preferences branch
   *
   * @return array
   */
  public function fields( $list_table )
  {
    $fields = array(

      __( 'Media', WPXTREME_TEXTDOMAIN ) => array(
        __( 'These settings apply only to your current user', WPXTREME_TEXTDOMAIN ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'media_thickbox_icon',
            'label_right' => $title = __( 'Enable preview on the Media thumbnails', WPXTREME_TEXTDOMAIN ),
            'value'       => $list_table->media_thickbox_icon,
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'media_thickbox_icon', $title ),
          )
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'media_thumbnail_author',
            'label_right' => $title = __( 'Enable Author gravatar thumbnails', WPXTREME_TEXTDOMAIN ),
            'value'       => $list_table->media_thumbnail_author,
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'thumbnail_author', $title ),
          )
        ),
      ),
      __( 'Pages', WPXTREME_TEXTDOMAIN ) => array(
        __( 'These settings are applied to your user only', WPXTREME_TEXTDOMAIN ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'pages_thumbnail_author',
            'label_right' => $title = __( 'Enable Author gravatar thumbnails', WPXTREME_TEXTDOMAIN ),
            'value'       => $list_table->pages_thumbnail_author,
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'thumbnail_author', $title ),
          )
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'pages_swipe_publish',
            'label_right' => $title = __( 'Enable Publish/Draft Swipe button', WPXTREME_TEXTDOMAIN ),
            'value'       => $list_table->pages_swipe_publish,
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'swipe_publish', $title ),
          )
        ),
      ),
      __( 'Posts', WPXTREME_TEXTDOMAIN ) => array(
        __( 'These settings are applied to your user only', WPXTREME_TEXTDOMAIN ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'posts_thumbnail_author',
            'label_right' => $title = __( 'Enable Author gravatar thumbnails', WPXTREME_TEXTDOMAIN ),
            'value'       => $list_table->posts_thumbnail_author,
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'thumbnail_author', $title ),
          )
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => 'posts_swipe_publish',
            'label_right' => $title = __( 'Enable Publish/Draft Swipe button', WPXTREME_TEXTDOMAIN ),
            'value'       => $list_table->posts_swipe_publish,
            'popover'     => WPXtremePreferencesAppearanceBranchPopover::init( 'swipe_publish', $title ),
          )
        ),
      ),

    );

    return $fields;
  }
}


/**
 * Import/Export view
 *
 * @class           WPXtremePreferencesImportExportView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-08-16
 * @version         1.0.0
 *
 */
class WPXtremePreferencesImportExportView extends WPDKView {

  const ID = 'import-export';

  /**
   * Create an instance of WPXtremePreferencesImportExportView class
   *
   * @return WPXtremePreferencesImportExportView
   */
  public function __construct()
  {
    parent::__construct( self::ID );
  }

  /**
   * Display
   */
  public function draw()
  {
    // Create a nonce key
    $nonce                     = md5( $this->id );
    $input_hidden_nonce        = new WPDKHTMLTagInput( '', $nonce, $nonce );
    $input_hidden_nonce->type  = WPDKHTMLTagInputType::HIDDEN;
    $input_hidden_nonce->value = wp_create_nonce( $this->id );

    // Layout fields
    $layout = new WPDKUIControlsLayout( $this->_fields() );

    // Form
    $form          = new WPDKHTMLTagForm( $input_hidden_nonce->html() . $layout->html() );
    $form->name    = 'wpxtreme-import-export';
    $form->id      = $form->name;
    $form->method  = 'post';
    $form->action  = '';
    $form->enctype = 'multipart/form-data';
    $form->display();
  }

  /**
   * Return the form fields array
   *
   * @return array
   */
  private function _fields()
  {
    $fields = array(
      __( 'Import', WPXTREME_TEXTDOMAIN ) => array(

        /**
         * Filter the header of form for feedback.
         *
         * @param string $content A content to display. Default empty.
         */
        apply_filters( 'wpxtreme_import_feedback', '' ),
        array(
          array(
            'type'  => WPDKUIControlType::FILE,
            'name'  => 'file',
            'label' => __( 'Select a <code>.wpx</code> export file', WPXTREME_TEXTDOMAIN ),
          ),
          array(
            'type'  => WPDKUIControlType::SUBMIT,
            'name'  => 'import_wpxtreme',
            'class' => 'button button-primary button-large',
            'value' => __( 'Import', WPXTREME_TEXTDOMAIN ),
          ),
        ),
      ),
      __( 'Export', WPXTREME_TEXTDOMAIN ) => array(

        array(
          array(
            'type'  => WPDKUIControlType::SUBMIT,
            'name'  => 'export_wpxtreme',
            'class' => 'button button-primary button-large',
            'value' => __( 'Export', WPXTREME_TEXTDOMAIN ),
            'label' => __( 'Export all wpXtreme settings', WPXTREME_TEXTDOMAIN ),
          )
        ),
      ),
    );

    return $fields;
  }

}


/**
 * Popover for Preferences
 *
 * @class           WPXtremePreferencesAppearanceBranchPopover
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date            2014-07-12
 * @version         1.0.1
 *
 */
class WPXtremePreferencesAppearanceBranchPopover extends WPDKUIPopover {

  /**
   * Handle
   *
   * @var string $handle
   */
  private $handle = '';

  /**
   * Return a singleton instance of WPXtremePreferencesAppearanceBranchPopover class
   *
   * @param string $handle An hanle key
   * @param string $title  Optional. A title
   *
   * @return WPXtremePreferencesAppearanceBranchPopover
   */
  public static function init( $handle, $title = '' )
  {
    return new self( $handle, $title );
  }

  /**
   * Create an instance of WPXtremePreferencesAppearanceBranchPopover class
   *
   * @param string $handle An hanle key
   * @param string $title  Optional. A title
   *
   * @return WPXtremePreferencesAppearanceBranchPopover
   */
  public function __construct( $handle, $title = '' )
  {
    // Store the handle
    $this->handle = $handle;

    // Custom
    $this->title     = $title;
    $this->trigger   = 'hover';
    $this->html      = true;
    $this->animation = false;

  }

  //
  public function display_ajax()
  {
    ?>
    <p>WordPress core and other WP related stuff often do <strong>Ajax calls</strong>. WordPress core, for example,
       does Ajax calls approximately <strong>every 15 (fifteen) seconds</strong> while you are editing a post.
       Enabling this feature, let you display an animation loader (centered on the screen) when Ajax requests occur.
       Our advise is to disable this feature if your WordPress website has many Ajax requests.</p>
    <div style="display:block;position:relative;margin:8px auto;left:auto" class="wpdk-loader"></div>

  <?php
  }

  //
  public function toolbars()
  {
    ?>
    <p>As you may know, WordPress displays the top filter bar without a box. We think this is not so clear because other
       component views <strong>have boxes and backgrounds</strong>. Enabling this feature, let you improve the
      <strong>look & feel of every toolbar </strong> in the admin backend area.</p>
    <img width="256"
         height="215"
         alt="Sample"
         src="<?php echo WPXTREME_URL_IMAGES ?>wpxtreme-preferences-toolbars.gif" />
  <?php
  }

  //
  public function inputs()
  {
    ?>
    <p>This feature is strongly recommended if you want to <strong>improve the look & feel of every input control</strong>
       (text and text areas) in the admin backend area.</p>
    <img width="256" height="215" alt="Sample" src="<?php echo WPXTREME_URL_IMAGES ?>wpxtreme-preferences-inputs.gif" />
  <?php
  }


  //
  public function tables()
  {
    ?>
    <p>Enabling this feature, let you improve the look & feel of every table list and table form in admin backend area.
       For instance, this enhancer emphasizes the displayed feedback when no item is found..</p>
    <img width="256" height="215" alt="Sample" src="<?php echo WPXTREME_URL_IMAGES ?>wpxtreme-preferences-tables.gif" />
  <?php
  }

  //
  public function table_actions()
  {
    ?>
    <p>Several table lists (eg. Posts, Pages, Comments, Plugins etc) are provided with inline actions in order to perform
       some functions as edit and delete. WordPress usually displays these actions under a label, when the mouse is over.
       wpXtreme enhances this behavior by adding a mobile look & feel as shown below.</p>
    <img width="256"
         height="215"
         alt="Sample"
         src="<?php echo WPXTREME_URL_IMAGES ?>wpxtreme-preferences-actions.gif" />
  <?php
  }

  //
  public function quick_edit()
  {
    ?>
    <p>Enabling this feature let you improve the quick inline editor layout with more readable field blocks.
       If WordPress usually shows every quick edit fieldset next to each other, with this enhancement you will display
       every fieldset one above the other.</p>
    <img width="256"
         height="215"
         alt="Sample"
         src="<?php echo WPXTREME_URL_IMAGES ?>wpxtreme-preferences-quick-edit.gif" />
  <?php
  }

  //
  public function remove_revisions()
  {
    ?>
    <p>Enhance your WordPress by enabling a useful button that allows you to remove all post revisions in one shot in
       post edit view.</p>
    <img width="256"
         height="215"
         alt="Sample"
         src="<?php echo WPXTREME_URL_IMAGES ?>wpxtreme-preferences-remove-revisions.gif" />
  <?php
  }

  // -------------------------------------------------------------------------------------------------------------------
  // LIST TABLE
  // -------------------------------------------------------------------------------------------------------------------

  //
  public function media_thickbox_icon()
  {
    ?>
    <p>Enabling this feature, a light box effect will be displayed on Media thumbnails</p>
    <img width="300"
         height="297"
         alt="Sample"
         src="<?php echo WPXTREME_URL_IMAGES ?>wpxm-preferences-media-thickbox.jpg" />
  <?php
  }

  //
  public function thumbnail_author()
  {
    ?>
    <p>Enabling this feature a Gravatar image will be displayed in the author column.</p>
    <img width="256" height="76" alt="Sample" src="<?php echo WPXTREME_URL_IMAGES ?>wpxm-preferences-author.jpg" />
  <?php
  }

  //
  public function swipe_publish()
  {
    ?>
    <p>Keep this button green if you want to manage in real time the status (draft/published) of your Pages.
       More, if a Page is private you see it marked by a lock icon image.</p>
    <img width="96" height="96" alt="Sample" src="<?php echo WPXTREME_URL_IMAGES ?>wpxm-preferences-swipe.gif" />
  <?php
  }

  /**
   * Display the popover button
   *
   * @return string
   */
  public function content()
  {
    WPDKHTML::startCompress();
    call_user_func( array( $this, $this->handle ) );

    return WPDKHTML::endCompress();
  }

}

/**
 * Core branch view.
 *
 * @class           WPXtremePreferencesCoreBranchView
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2015 wpXtreme Inc. All Rights Reserved.
 * @date            2015-01-24
 * @version         1.0.1
 *
 * @history         1.0.1 - Enabled the debug console.
 *
 */
class WPXtremePreferencesCoreBranchView extends WPDKPreferencesView {

  /**
   * Create an instance of WPXtremePreferencesCoreBranchView class
   *
   * @return WPXtremePreferencesCoreBranchView
   */
  public function __construct()
  {
    $preferences = WPXtremePreferences::init();
    parent::__construct( $preferences, 'core' );
  }

  /**
   * Return the array fields
   *
   * @param WPXtremePreferencesCoreBranch $branch
   *
   * @return array|void
   */
  public function fields( $branch )
  {
    $fields = array(
      __( 'Debug' ) => array(
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => WPXtremePreferencesCoreBranch::DEBUG_CONSOLE,
            'label_right' => __( 'Enbale Debug Console - see admin bar menu after enabling' ),
            'value'       => $branch->debug_console
          )
        ),
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => WPXtremePreferencesCoreBranch::IMPROVE_DEBUG,
            'label_right' => __( 'Improve debug with more information as backtrace' ),
            'value'       => $branch->improve_debug
          )
        ),
      ),
    __( 'WPDK Framework' ) => array(
        array(
          array(
            'type'        => WPDKUIControlType::SWITCH_BUTTON,
            'name'        => WPXtremePreferencesCoreBranch::WPDK_WATCHDOG_DOG,
            'label_right' => __( 'Watchdog Log' ),
            'value'       => $branch->wpdk_watchdog_log
          )
        ),
      )
    );

    return $fields;
  }
}