<?php

/**
 * Main CleanFix class
 *
 * @class              WPXCleanFix
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-10-03
 * @version            1.1.0
 */
final class WPXCleanFix extends WPXPlugin {

  /**
   * Create and return a singleton instance of WPXCleanFix class.
   *
   * @param string $file The main file of this plugin. Usually __FILE__ (main.php)
   *
   * @return WPXCleanFix
   */
  public static function boot( $file = null )
  {
    static $instance = null;
    if ( is_null( $instance ) && ( !empty( $file ) ) ) {
      $instance = new self( $file );
    }
    return $instance;
  }

  /**
   * Create an instance of WPXCleanFix class
   *
   * @param string $file The main file of this plugin. Usually __FILE__ (main.php)
   *
   * @return WPXCleanFix
   */
  public function __construct( $file = null )
  {
    parent::__construct( $file );

    // Filter the registered CleanFix modules.
    add_filter( 'wpxcf_modules', array( $this, 'wpxcf_modules' ) );

    // Init the modules controller
    add_action( 'init', array( 'WPXCleanFixModulesController', 'init' ), 50 );
  }

  /**
   * Filter the registered CleanFix modules.
   *
   * @param array $modules Registered modules.
   */
  public function wpxcf_modules( $modules )
  {
    $embed_modules = array(
      'WPXCFDatabaseModule',
			'WPXCFCommentsModule',
			'WPXCFPostsModule',
			'WPXCFTaxonomiesModule',
			'WPXCFUsersModule',
			'WPXCFOptionsModule',
    );

    return array_merge( $modules, $embed_modules );
  }

  /**
   * Register all autoload classes
   */
  public function classesAutoload()
  {
		$includes = array(
			$this->classesPath . 'admin/wpxcf-admin.php' => 'WPXCleanFixAdmin',

			$this->classesPath . 'admin/wpxcf-main-viewcontroller.php' => array(
				'WPXCleanFixMainViewController',
				'WPXCleanFixMainView'
				),

			$this->classesPath . 'core/wpxcf-ajax.php' => 'WPXCleanFixAjax',

			$this->classesPath . 'embedded-modules/comments/wpxcf-comments-module.php' => array(
				'WPXCFCommentsModule',
				'WPXCFCommentsModuleUnapprovedSlot',
				'WPXCFCommentsModuleTrashSlot',
				'WPXCFCommentsModuleSpamSlot'
				),

			$this->classesPath . 'embedded-modules/database/wpxcf-database-module.php' => array(
				'WPXCFDatabaseModule',
				'WPXCFDatabaseModuleOptimizationSlot',
				'WPXCFDatabaseModuleOptimizeSlotDetailView'
				),

			$this->classesPath . 'embedded-modules/database/wpxcf-database-preferences-view.php' => 'WPXCleanFixPreferencesDatabaseView',

			$this->classesPath . 'embedded-modules/database/wpxcf-database-preferences.php' => 'WPXCleanFixPreferencesDatabase',

			$this->classesPath . 'embedded-modules/options/wpxcf-options-module.php' => array(
				'WPXCFOptionsModule',
				'WPXCFOptionsModuleExpiredSiteTransientSlot',
				'WPXCFOptionsModuleExpiredTransientsSlot'
				),

			$this->classesPath . 'embedded-modules/options/wpxcf-preferences-options-view.php' => 'WPXCleanFixPreferencesOptionsView',

			$this->classesPath . 'embedded-modules/options/wpxcf-preferences-options.php' => 'WPXCleanFixPreferencesOptions',

			$this->classesPath . 'embedded-modules/posts/wpxcf-posts-module.php' => array(
				'WPXCFPostsModule',
				'WPXCFPostsModuleRevisionsSlot',
				'WPXCFPostsModuleAutodraftSlot',
				'WPXCFPostsModuleTrashSlot',
				'WPXCFPostsModulePostsWithoutAuthorSlot',
				'WPXCleanFixSelectControlForPostsWithoutAuthor',
				'WPXCFPostsModuleOrphanPostMetaSlot',
				'WPXCFPostsModuleTemporaryPostMetaSlot',
				'WPXCFPostsModuleOrphanAttachmentsSlot'
				),

			$this->classesPath . 'embedded-modules/taxonomies/wpxcf-taxonomies-module.php' => array(
				'WPXCFTaxonomiesModule',
				'WPXCFTaxonomiesModuleConsistentTermsSlot',
				'WPXCFTaxonomiesModuleConsistentTaxonomiesSlot',
				'WPXCFTaxonomiesModuleRelationshipsSlot',
				'WPXCFTaxonomiesModuleOrphanPostTagsSlot',
				'WPXCFTaxonomiesModuleOrphanCategoriesSlot',
				'WPXCFTaxonomiesModuleOrphanTermsSlot'
				),

			$this->classesPath . 'embedded-modules/users/wpxcf-users-module.php' => array(
				'WPXCFUsersModule',
				'WPXCFUsersModuleOrphanUserMetaSlot',
				'WPXCFUsersModuleExpiredTransientSlot'
				),

			$this->classesPath . 'modules/wpxcf-module-view.php' => array(
				'WPXCleanFixModuleView',
				'WPXCleanFixSelectControl',
				'WPXCleanFixLabelControl',
				'WPXCleanFixButtonFixControlType',
				'WPXCleanFixButtonFixControl',
				'WPXCleanFixButtonRefreshControl'
				),

			$this->classesPath . 'modules/wpxcf-module.php' => array(
				'WPXCleanFixModule',
				'WPXCleanFixSlot',
				'WPXCleanFixModuleResponseStatus',
				'WPXCleanFixModuleResponse'
				),

			$this->classesPath . 'modules/wpxcf-modules-controller.php' => 'WPXCleanFixModulesController',

			$this->classesPath . 'preferences/wpxcf-preferences-view-controller.php' => array(
				'WPXCleanFixPreferencesViewController',
				'WPXCleanFixPreferencesGeneralView'
				),

			$this->classesPath . 'preferences/wpxcf-preferences.php' => 'WPXCleanFixPreferences',

			);

		return $includes;
  }

  /**
   * Called when Ajax
   */
  public function ajax()
  {
    WPXCleanFixAjax::init();
  }

  /**
   * Called when admin
   */
  public function admin()
  {
    WPXCleanFixAdmin::init();
  }

  /**
   * Called when the plugin is activate
   */
  public function activation()
  {
    WPXCleanFixPreferences::init()->delta();
  }

  /**
   * Called when the plugin is deactivated
   */
  public function deactivation()
  {
    WPXCleanFixPreferences::init()->delta();
  }

  /**
   * Init your own configuration settings
   */
  public function preferences()
  {
    WPXCleanFixPreferences::init();
  }

}