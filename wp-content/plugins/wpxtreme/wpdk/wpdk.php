<?php
/// @cond private

// Avoid directly access
if ( !defined( 'ABSPATH' ) ) {
  exit;
}

if ( !class_exists( 'WPDK' ) ) {

  // Include config
  require_once( trailingslashit( dirname( __FILE__ ) ) . 'config.php' );

  /**
   * Static/singleton class for load WPDK Framework.
   * This class is in singleton mode to avoid double init of action, filters and includes.
   *
   * @class              WPDK
   * @author             =undo= <info@wpxtre.me>
   * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
   * @date               2014-11-11
   * @version            0.10.7
   *
   * @history            0.10.6 - Added `WPDKDB` class.
   * @history            0.10.7 - Cleanup code.
   */
  final class WPDK {

    /**
     * The array of loading path related to any WPDK class.
     *
     * @var array $_wpdkClassLoadingPath
     *
     * @since 0.10.0
     */
    private $_wpdkClassLoadingPath;

    /**
     * Init the framework in singleton mode to avoid double include, action and inits.
     *
     * @return WPDK
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
     * Create an instance of WPDK class and init the franework
     *
     * @return WPDK
     */
    private function __construct()
    {

      // First of all, load SPL autoload logic
      $this->_wpdkClassLoadingPath = array();
      spl_autoload_extensions( '.php' ); // for faster execution
      spl_autoload_register( array( $this, 'autoloadWPDKEnvironment' ) );

      // Load the framework in SPL autoload logic
      $this->defines();
      $this->registerClasses();

      // WPDK Cron schedules
      WPDKCronSchedules::init();

      // Fires to flush (clear) the third parties plugins.
      add_action( 'wpdk_flush_cache_third_parties_plugins', array( $this, 'wpdk_flush_cache_third_parties_plugins') );

      // Load the translation of WPDK
      add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

      // Register scripts and styles
      add_action( 'init', array( 'WPDKUIComponents', 'init' ) );

      // Placeholder metabox
      add_action( 'init', array( 'WPDKPostPlaceholders', 'init' ) );

      // Users enhancer
      add_action( 'set_current_user', array( 'WPDKUsers', 'init' ) );

      // Shortcodes
      add_action( 'wp_loaded', array( 'WPDKServiceShortcodes', 'init' ) );

      // Ajax
      if ( wpdk_is_ajax() ) {
        add_action( 'wp_loaded', array( 'WPDKServiceAjax', 'init' ) );
      }

      // Fires when scripts are printed for all admin pages.
      add_action( 'admin_print_scripts', array( $this, 'admin_print_scripts' ), 1 );

      // Print scripts or data in the head tag on the front end.
      add_action( 'wp_head', array( $this, 'wp_head' ), 1  );

      /**
       * Fires when WPDK is loaded.
       */
      do_action( 'WPDK' );
    }

    /**
     * Fires to flush (clear) the third parties plugins.
     *
     * @since 1.7.3
     */
    public function wpdk_flush_cache_third_parties_plugins()
    {
      // WP SuperCache patch
      if( function_exists( 'wp_cache_clear_cache' ) ) {
        wp_cache_clear_cache();
      }

      // W3 Total Cache Plugin
      if( function_exists( 'w3tc_pgcache_flush' ) ) {
        w3tc_pgcache_flush();
      }
    }

    /**
     * This function records a WPDK class into autoloading register, joined with its loading path. The function has some
     * facility in its first param, in order to allow both string and array loading of class names ( useful in case of a
     * group of classes that are defined in a single file ):
     *
     * 1. $this->registerAutoloadClass( 'file.php', 'ClassName' );
     *
     * 2. $this->registerAutoloadClass( array( 'file.php' => 'ClassName' ) );
     *
     * 3. $this->registerAutoloadClass( array( 'file.php' => array( 'ClassName', 'ClassName', ... ) ) );
     *
     * @brief Records a WPDK class into autoloading register.
     *
     * @param string|array $sLoadingPath Path of class when $mClassName is a string
     * @param string       $mClassName   Optional. The single class name or key value pairs array with path => classes
     *
     * @since 0.10.0
     *
     */
    public function registerAutoloadClass( $sLoadingPath, $mClassName = '' )
    {

      // 1.
      if ( is_string( $sLoadingPath ) && is_string( $mClassName ) && !empty( $mClassName ) ) {
        $sClassNameLowerCased                               = strtolower( $mClassName );
        $this->_wpdkClassLoadingPath[$sClassNameLowerCased] = $sLoadingPath;
      }

      // 2.
      elseif ( is_array( $sLoadingPath ) ) {
        foreach ( $sLoadingPath as $path => $classes ) {
          if ( is_string( $classes ) ) {
            $class_name                               = strtolower( $classes );
            $this->_wpdkClassLoadingPath[$class_name] = $path;
          }

          // 3.
          elseif ( is_array( $classes ) ) {
            foreach ( $classes as $class_name ) {
              $class_name                               = strtolower( $class_name );
              $this->_wpdkClassLoadingPath[$class_name] = $path;
            }
          }
        }
      }
    }

    /**
     * This function performs runtime autoloading of all WPDK classes, based on previous class registering executed
     * in includes method.
     *
     * @since 0.10.0
     *
     * @param string $sClassName - The class that has to be loaded right now
     *
     */
    public function autoloadWPDKEnvironment( $sClassName )
    {
      if( class_exists( $sClassName, false ) ) {
        return;
      }

      // For backward compatibility and for better matching
      $sClassNameLowerCased = strtolower( $sClassName );
      if ( isset( $this->_wpdkClassLoadingPath[$sClassNameLowerCased] ) ) {
        require_once( $this->_wpdkClassLoadingPath[$sClassNameLowerCased] );
      }
    }

    /**
     * Include external defines
     */
    private function defines()
    {
      // define WPDK constants
      require_once( trailingslashit( dirname( __FILE__ ) ) . 'defines.php' );
    }

    /**
     * Register all autoload classes and include all framework class files through SPL autoload logic
     */
    private function registerClasses()
    {

      $sPathPrefix = trailingslashit( dirname( __FILE__ ) );

      // Put here files that have to be directly included without autoloading
      require_once( $sPathPrefix . 'classes/core/wpdk-functions.php' );

      // Start autoloading register

      $includes = array(

        // -------------------------------------------------------------------------------------------------------------
        // CORE
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/core/wpdk-ajax.php'                        => array(
          'WPDKAjax',
          'WPDKAjaxResponse'
        ),

        $sPathPrefix . 'classes/core/wpdk-cron.php'                        => array(
          'WPDKCronSchedules',
          'WPDKCronController',
          'WPDKCron',
          'WPDKRecurringCron',
          'WPDKSingleCron',
        ),

        $sPathPrefix . 'classes/core/wpdk-mail.php'                        => array(
          'WPDKMail',
        ),

        $sPathPrefix . 'classes/core/wpdk-object.php'                      => 'WPDKObject',

        $sPathPrefix . 'classes/core/wpdk-preferences.php'                 => array(
          'WPDKPreferences',
          'WPDKPreferencesBranch',
          'WPDKPreferencesImportExport',
        ),

        $sPathPrefix . 'classes/core/wpdk-result.php'                      => array(
          'WPDKError',
          'WPDKResult',
          'WPDKResultType',
          'WPDKStatus',
          'WPDKWarning',
        ),

        $sPathPrefix . 'classes/core/wpdk-shortcodes.php'                   => array(
          'WPDKShortcode',
          'WPDKShortcodes',
        ),

        $sPathPrefix . 'classes/core/wpdk-theme-customize.php'             => array(
          'WPDKThemeCustomize',
          'WPDKThemeCustomizeControlType',
        ),

        $sPathPrefix . 'classes/core/wpdk-watchdog.php'                    => 'WPDKWatchDog',

        $sPathPrefix . 'classes/core/wpdk-wordpress-admin.php'             => 'WPDKWordPressAdmin',

        $sPathPrefix . 'classes/core/wpdk-wordpress-plugin.php'            => array(
          'WPDKPlugin',
          'WPDKPlugins',
          'WPDKWordPressPaths',
          'WPDKWordPressPlugin',
        ),

        $sPathPrefix . 'classes/core/wpdk-wordpress-theme.php'             => array(
          'WPDKTheme',
          'WPDKThemeSetup',
          'WPDKWordPressTheme',
        ),

        // -------------------------------------------------------------------------------------------------------------
        // DATABASE
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/database/wpdk-db.php'                      => array(
          'WPDKDB',
          'WPDKDBTableModel',
          'WPDKDBListTableModel',
          'WPDKDBTableRowStatuses',
        ),


        // -------------------------------------------------------------------------------------------------------------
        // HELPER
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/helper/wpdk-array.php'                     => 'WPDKArray',
        $sPathPrefix . 'classes/helper/wpdk-colors.php'                    => 'WPDKColors',
        $sPathPrefix . 'classes/helper/wpdk-crypt.php'                     => 'WPDKCrypt',
        $sPathPrefix . 'classes/helper/wpdk-datetime.php'                  => 'WPDKDateTime',
        $sPathPrefix . 'classes/helper/wpdk-filesystem.php'                => 'WPDKFilesystem',
        $sPathPrefix . 'classes/helper/wpdk-geo.php'                       => 'WPDKGeo',
        $sPathPrefix . 'classes/helper/wpdk-http.php'                      => array(
          'WPDKHTTPRequest',
          'WPDKHTTPVerbs',
          'WPDKUserAgents',
        ),
        $sPathPrefix . 'classes/helper/wpdk-math.php'                      => 'WPDKMath',
        $sPathPrefix . 'classes/helper/wpdk-screen-help.php'               => 'WPDKScreenHelp',

        // -------------------------------------------------------------------------------------------------------------
        // POST
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/post/wpdk-custom-post-type.php'            => 'WPDKCustomPostType',

        $sPathPrefix . 'classes/post/wpdk-post.php'                        => array(
          '_WPDKPost',
          'WPDKPost',
          'WPDKPostMeta',
          'WPDKPostStatus',
          'WPDKPostType',
        ),

        $sPathPrefix . 'classes/post/wpdk-post-placeholders.php'                        => array(
          'WPDKPostPlaceholders',
          'WPDKPostPlaceholdersMetaBoxView',
        ),

        // -------------------------------------------------------------------------------------------------------------
        // TAXONOMIES
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/taxonomies/wpdk-custom-taxonomy.php'       => 'WPDKCustomTaxonomy',

        $sPathPrefix . 'classes/taxonomies/wpdk-terms.php'                 => array(
          'WPDKTerm',
          'WPDKTerms',
        ),

        // -------------------------------------------------------------------------------------------------------------
        // UI
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/ui/wpdk-dynamic-table.php'            => array(
          'WPDKDynamicTable',
          'WPDKDynamicTableView',
        ),

        $sPathPrefix . 'classes/ui/wpdk-glyphicons.php'                 => 'WPDKGlyphIcons',

        $sPathPrefix . 'classes/ui/wpdk-html.php'                       => 'WPDKHTML',

        $sPathPrefix . 'classes/ui/wpdk-html-tag.php'                   => array(
          'WPDKHTMLTag',
          'WPDKHTMLTagA',
          'WPDKHTMLTagButton',
          'WPDKHTMLTagFieldset',
          'WPDKHTMLTagForm',
          'WPDKHTMLTagImg',
          'WPDKHTMLTagInput',
          'WPDKHTMLTagInputType',
          'WPDKHTMLTagLabel',
          'WPDKHTMLTagLegend',
          'WPDKHTMLTagName',
          'WPDKHTMLTagSelect',
          'WPDKHTMLTagSpan',
          'WPDKHTMLTagTextarea',
        ),

        $sPathPrefix . 'classes/ui/wpdk-jquery.php'                     => array(
          'WPDKjQuery',
          'WPDKjQueryTab',
          'WPDKjQueryTabsView',
          'WPDKjQueryTabsViewController',
        ),

        $sPathPrefix . 'classes/ui/wpdk-listtable-viewcontroller.php'   => array(
          'IWPDKListTableModel',
          'WPDKListTableModel',
          'WPDKListTableViewController',
        ),

        $sPathPrefix . 'classes/ui/wpdk-menu.php'                       => array(
          'WPDKMenu',
          'WPDKSubMenu',
          'WPDKSubMenuDivider',
        ),

        $sPathPrefix . 'classes/ui/wpdk-metabox.php'                    => array(
          'WPDKMetaBoxContext',
          'WPDKMetaBoxPriority',
          'WPDKMetaBoxView',
        ),

        $sPathPrefix . 'classes/ui/wpdk-pointer.php'                    => array(
          'WPDKPointer',
          'WPDKPointerButton',
        ),

        $sPathPrefix . 'classes/ui/wpdk-preferences-view.php'           => 'WPDKPreferencesView',

        $sPathPrefix . 'classes/ui/wpdk-preferences-viewcontroller.php' => 'WPDKPreferencesViewController',

        $sPathPrefix . 'classes/ui/wpdk-scripts.php'                    => 'WPDKScripts',

        $sPathPrefix . 'classes/ui/wpdk-ui.php'                         => 'WPDKUI',

        $sPathPrefix . 'classes/ui/wpdk-ui-alert.php'                   => array(
          'WPDKUIAlert',
          'WPDKUIAlertType',
        ),

        $sPathPrefix . 'classes/ui/wpdk-ui-components.php'              => 'WPDKUIComponents',

        $sPathPrefix . 'classes/ui/wpdk-ui-controls.php'                => array(
          'WPDKUIControl',
          'WPDKUIControlAlert',
          'WPDKUIControlButton',
          'WPDKUIControlCheckbox',
          'WPDKUIControlCheckboxes',
          'WPDKUIControlChoose',
          'WPDKUIControlColorPicker',
          'WPDKUIControlCustom',
          'WPDKUIControlDate',
          'WPDKUIControlDateTime',
          'WPDKUIControlEmail',
          'WPDKUIControlFile',
          'WPDKUIControlFileMedia',
          'WPDKUIControlHidden',
          'WPDKUIControlLabel',
          'WPDKUIControlNumber',
          'WPDKUIControlPassword',
          'WPDKUIControlPhone',
          'WPDKUIControlRadio',
          'WPDKUIControlSection',
          'WPDKUIControlSelect',
          'WPDKUIControlSelectList',
          'WPDKUIControlsLayout',
          'WPDKUIControlSubmit',
          'WPDKUIControlSwipe',
          'WPDKUIControlSwitch',
          'WPDKUIControlText',
          'WPDKUIControlTextarea',
          'WPDKUIControlType',
          'WPDKUIControlURL',
        ),

        $sPathPrefix . 'classes/ui/wpdk-ui-modal-dialog.php'            => 'WPDKUIModalDialog',
        $sPathPrefix . 'classes/ui/wpdk-ui-modal-dialog-tour.php'       => 'WPDKUIModalDialogTour',

        $sPathPrefix . 'classes/ui/wpdk-ui-page-view.php'               => 'WPDKUIPageView',

        $sPathPrefix . 'classes/ui/wpdk-ui-popover.php'                 => array(
          'WPDKUIPopover',
          'WPDKUIPopoverPlacement',
        ),

        $sPathPrefix . 'classes/ui/wpdk-ui-progress.php'                 => array(
          'WPDKUIProgress',
          'WPDKUIProgressBar',
          'WPDKUIProgressBarType',
        ),

        $sPathPrefix . 'classes/ui/wpdk-ui-table-view.php'              => 'WPDKUITableView',

        $sPathPrefix . 'classes/ui/wpdk-view.php'                       => 'WPDKView',

        $sPathPrefix . 'classes/ui/wpdk-viewcontroller.php'             => array(
          'WPDKHeaderView',
          'WPDKViewController',
        ),

        // -------------------------------------------------------------------------------------------------------------
        // USERS, ROLES & CAPABILITIES
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/users/wpdk-users.php'                    => array(
          'WPDKUser',
          'WPDKUserMeta',
          'WPDKUsers',
          'WPDKUserStatus',
        ),

        $sPathPrefix . 'classes/users/wpdk-user-capabilities.php'                    => array(
          'WPDKUserCapabilities',
          'WPDKUserCapability',
          'WPDKCapabilities',
          'WPDKCapability',
        ),

        $sPathPrefix . 'classes/users/wpdk-user-roles.php'                    => array(
          'WPDKUserRole',
          'WPDKUserRoles',
          'WPDKRole',
          'WPDKRoles',
        ),

        // -------------------------------------------------------------------------------------------------------------
        // WIDGET
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/widget/wpdk-widget.php'                    => 'WPDKWidget',

        // -------------------------------------------------------------------------------------------------------------
        // SERVICES
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'services/wpdk-service-ajax.php'                    => 'WPDKServiceAjax',
        $sPathPrefix . 'services/wpdk-service-shortcodes.php'              => 'WPDKServiceShortcodes',

        // -------------------------------------------------------------------------------------------------------------
        // DEPRECATED
        // -------------------------------------------------------------------------------------------------------------

        $sPathPrefix . 'classes/deprecated/wpdk-configuration.php'     => array(
          'WPDKConfig',
          'WPDKConfigBranch',
          'WPDKConfiguration',
          'WPDKConfigurationView',
        ),

        $sPathPrefix . 'classes/deprecated/wpdk-db-table.php'     => array(
          '__WPDKDBTable',
          '_WPDKDBTable',
          'WPDKDBTable',
          'WPDKDBTableRow',
          'WPDKDBTableStatus',
        ),

        $sPathPrefix . 'classes/deprecated/wpdk-db-table-model-listtable.php' => 'WPDKDBTableModelListTable',

        $sPathPrefix . 'classes/deprecated/wpdk-tbs-alert.php'         => array(
          'WPDKTwitterBootstrapAlert',
          'WPDKTwitterBootstrapAlertType',
        ),

        $sPathPrefix . 'classes/deprecated/wpdk-twitter-bootstrap.php' => array(
          'WPDKTwitterBoostrapPopover',
          'WPDKTwitterBootstrap',
          'WPDKTwitterBootstrapButton',
          'WPDKTwitterBootstrapButtonSize',
          'WPDKTwitterBootstrapButtonType',
          'WPDKTwitterBootstrapModal',
        ),

        // Extra libs

      );

      $this->registerAutoloadClass( $includes );

    }

    /**
     * Load a text domain for WPDK, like a plugin. In this relase WPDK has an own text domain. This feature could
     * miss in future release
     */
    public function load_plugin_textdomain()
    {
      load_plugin_textdomain( WPDK_TEXTDOMAIN, false, WPDK_TEXTDOMAIN_PATH );
    }

    /**
     * Fires when scripts are printed for all admin pages.
     */
    public function admin_print_scripts()
    {
      // Localize wpdk_i18n
      $this->wp_head();
    }

    /**
     * Print scripts or data in the head tag on the front end.
     */
    public function wp_head()
    {
      $loc   = $this->scriptLocalization();
      $stack = array();

      foreach( $loc as $key => $value ) {
        if( !is_scalar( $value ) ) {
          continue;
        }
        $stack[ $key ] = html_entity_decode( (string)$value, ENT_QUOTES, 'UTF-8' );
      }
      ?>
      <script type='text/javascript'>
        var wpdk_i18n = <?php echo json_encode( $stack ) ?>;
      </script>
    <?php
    }

    /**
     * Return a Key values pairs array to localize Javascript
     *
     * @return array
     */
    private function scriptLocalization()
    {
      $result = array(
        'ajaxURL'            => WPDKWordPressPlugin::urlAjax(),

        'messageUnLockField' => __( "Please confirm before unlock this form field.\nDo you want unlock this form field?", WPDK_TEXTDOMAIN ),

        'timeOnlyTitle'      => __( 'Choose Time', WPDK_TEXTDOMAIN ),
        'timeText'           => __( 'Time', WPDK_TEXTDOMAIN ),
        'hourText'           => __( 'Hour', WPDK_TEXTDOMAIN ),
        'minuteText'         => __( 'Minute', WPDK_TEXTDOMAIN ),
        'secondText'         => __( 'Seconds', WPDK_TEXTDOMAIN ),
        'currentText'        => __( 'Now', WPDK_TEXTDOMAIN ),
        'dayNamesMin'        => __( 'Su,Mo,Tu,We,Th,Fr,Sa', WPDK_TEXTDOMAIN ),
        'monthNames'         => __( 'January,February,March,April,May,June,July,August,September,October,November,December', WPDK_TEXTDOMAIN ),
        'monthNamesShort'    => __( 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec', WPDK_TEXTDOMAIN ),
        'closeText'          => __( 'Close', WPDK_TEXTDOMAIN ),
        //'dateFormat'         => __( 'mm/dd/yy', WPDK_TEXTDOMAIN ),
        'dateFormat'         => WPDKDateTime::DATE_FORMAT_JS,
        'timeFormat'         => WPDKDateTime::TIME_FORMAT_JS,
      );

      return $result;
    }
  }

  // Let's dance
  $GLOBALS['WPDK'] = WPDK::init();
}

/// @endcond