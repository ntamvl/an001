<?php

/**
 * Terms and taxonomies clean & fix module
 *
 * @class           WPXCFTaxonomiesModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModule extends WPXCleanFixModule {

  /**
   * Total warning for each slots
   *
   * @var int $warnings_count
   */
  public $warnings_count = 0;

  /**
   * Return a singleton instance of WPXCFTaxonomiesModule class
   *
   * @return WPXCFTaxonomiesModule
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
   * Create an instance of WPXCFTaxonomiesModule class
   *
   * @return WPXCFTaxonomiesModule
   */
  public function __construct()
  {
    parent::__construct( __( 'Terms and Taxonomies', WPXCLEANFIX_TEXTDOMAIN ) );
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
      'WPXCFTaxonomiesModuleConsistentTermsSlot',
      'WPXCFTaxonomiesModuleConsistentTaxonomiesSlot',
      'WPXCFTaxonomiesModuleRelationshipsSlot',
      'WPXCFTaxonomiesModuleOrphanPostTagsSlot',
      'WPXCFTaxonomiesModuleOrphanCategoriesSlot',
      'WPXCFTaxonomiesModuleOrphanTermsSlot',
    );

    return $slots;
  }

  // -------------------------------------------------------------------------------------------------------------------
  // SHARED METHODS - this method are use by several slot
  // -------------------------------------------------------------------------------------------------------------------

}

/**
 * Single slot
 *
 * @class           WPXCFTaxonomiesModuleConsistentTermsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModuleConsistentTermsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFTaxonomiesModuleConsistentTermsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleConsistentTermsSlot
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
   * Create an instance of WPXCFTaxonomiesModuleConsistentTermsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleConsistentTermsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Consistent Terms', WPXCLEANFIX_TEXTDOMAIN ), __( 'These are orphan Terms and they don\'t exist in the taxonomy table.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $terms = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $terms ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan term. This term does not extis as taxonomy.', 'You have %s orphan terms. These terms does not exists as taxonomy.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $terms, array( 'name' => '%s' ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to repair terms', WPXCLEANFIX_TEXTDOMAIN );
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
DELETE T
FROM {$wpdb->terms} AS T
LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_id = T.term_id )
WHERE 1
AND T.term_id <> 1
AND TT.term_taxonomy_id IS NULL
SQL;
    $wpdb->query( $sql );

    return $this->check();
  }


  /**
   * Return all orphan terms
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT *
FROM {$wpdb->terms} AS T
LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_id = T.term_id )
WHERE 1
AND T.term_id <> 1
AND TT.term_taxonomy_id IS NULL
ORDER BY T.name
SQL;

    return $wpdb->get_results( $sql );
  }

}

/**
 * Single slot
 *
 * @class           WPXCFTaxonomiesModuleConsistentTaxonomiesSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModuleConsistentTaxonomiesSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFTaxonomiesModuleConsistentTaxonomiesSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleConsistentTaxonomiesSlot
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
   * Create an instance of WPXCFTaxonomiesModuleConsistentTaxonomiesSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleConsistentTaxonomiesSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Consistent Terms/Taxonomy', WPXCLEANFIX_TEXTDOMAIN ), __( 'These are orphan Taxonomies which are missing a valid linked term.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $taxonomies = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $taxonomies ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan taxonomy. This is taxonomy have a not valid term id linked.', 'You have %s orphan taxonomies. These taxonomies have not a valid term id linked.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $taxonomies, array( 'taxonomy' => '%s' ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to repair terms and taxonomies.', WPXCLEANFIX_TEXTDOMAIN );
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
DELETE TT FROM {$wpdb->term_taxonomy} AS TT
LEFT JOIN {$wpdb->terms} AS T ON ( T.term_id = TT.term_id )
WHERE 1
AND TT.term_id <> 1
AND T.term_id IS NULL
SQL;
    $wpdb->query( $sql );

    return $this->check();
  }

  /**
   * Return all orphan taxonomies. These are the taxonomies that have a not valied term id linked.
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT *
FROM {$wpdb->term_taxonomy} AS TT
LEFT JOIN {$wpdb->terms} AS T ON ( T.term_id = TT.term_id )
WHERE 1
AND TT.term_id <> 1
AND T.term_id IS NULL
ORDER BY TT.taxonomy
SQL;

    //WPXtreme::log( $sql );

    $result = $wpdb->get_results( $sql );

    //WPXtreme::log( $result );

    return $result;
  }

}

/**
 * Single slot
 *
 * @class           WPXCFTaxonomiesModuleRelationshipsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModuleRelationshipsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFTaxonomiesModuleRelationshipsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleRelationshipsSlot
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
   * Create an instance of WPXCFTaxonomiesModuleRelationshipsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleRelationshipsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Consistent Terms/Relationships', WPXCLEANFIX_TEXTDOMAIN ), __( 'Check for term_relationships table and for missing taxonomy IDs.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $relationships = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $relationships ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s row broken. This object ID or term taxonomy ID is not valid.', 'You have %s broken rows. These object ID or terms taxonomy ID are not valid.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixLabelControl( $issues );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to repair terms relationships.', WPXCLEANFIX_TEXTDOMAIN );
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
DELETE TR
FROM {$wpdb->term_relationships} AS TR
LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_taxonomy_id = TR.term_taxonomy_id )
LEFT JOIN {$wpdb->posts} AS P ON ( P.ID = TR.object_id )
WHERE 1
AND TT.term_taxonomy_id IS NULL
OR P.ID IS NULL
SQL;

    $wpdb->query( $sql );

    return $this->check();
  }


  /**
   * Return all orphan taxonomies. These are the taxonomies that have a not valied term id linked.
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL
SELECT *
FROM {$wpdb->term_relationships} AS TR
LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( TT.term_taxonomy_id = TR.term_taxonomy_id )
LEFT JOIN {$wpdb->posts} AS P ON ( P.ID = TR.object_id )
WHERE 1
AND TT.term_taxonomy_id IS NULL
OR P.ID IS NULL
SQL;

    //WPXtreme::log( $sql );

    $result = $wpdb->get_results( $sql );

    //WPXtreme::log( $result );

    return $result;
  }

}

/**
 * Single slot
 *
 * @class           WPXCFTaxonomiesModuleOrphanPostTagsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModuleOrphanPostTagsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFTaxonomiesModuleOrphanPostTagsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleOrphanPostTagsSlot
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
   * Create an instance of WPXCFTaxonomiesModuleOrphanPostTagsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleOrphanPostTagsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Orphan Post Tags', WPXCLEANFIX_TEXTDOMAIN ), __( 'Check for unused post tags.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $terms = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $terms ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan tag.', 'You have %s orphan tags.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $terms, array( 'name' => '%s' ) );
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
SELECT T.term_id

  FROM {$wpdb->terms} AS T

  INNER JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
  LEFT JOIN {$wpdb->term_relationships} AS TR ON ( TR.term_taxonomy_id = TT.term_taxonomy_id )

  WHERE 1

  AND TT.taxonomy = 'post_tag'
  AND TT.count = 0
  AND TR.object_id IS NULL
SQL;

    $results = $wpdb->get_results( $sql, OBJECT_K );

    if ( empty( $results ) ) {
      return;
    }

    $keys = implode( ',', array_keys( $results ) );

    $sql = <<<SQL
DELETE FROM {$wpdb->terms} WHERE term_id IN ( {$keys} );
SQL;

    //WPXtreme::log( $sql );

    $wpdb->query( $sql );

    $sql = <<<SQL
DELETE FROM {$wpdb->term_taxonomy} WHERE term_id IN ( {$keys} );
SQL;

    //WPXtreme::log( $sql );

    $wpdb->query( $sql );

    $sql = <<<SQL
DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id NOT IN ( SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy} );
SQL;

    //WPXtreme::log( $sql );

    $wpdb->query( $sql );

    return $this->check();
  }

  /**
   * Return all unused tags
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<<SQL

SELECT *

FROM ( {$wpdb->terms} AS T )

INNER JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
LEFT JOIN {$wpdb->term_relationships} AS TR ON ( TR.term_taxonomy_id = TT.term_taxonomy_id )

WHERE 1

AND TT.taxonomy = 'post_tag'
AND TT.count = 0
AND TR.object_id IS NULL

ORDER BY T.name

SQL;

    //WPXtreme::log( $sql );

    return $wpdb->get_results( $sql );
  }

}

/**
 * Single slot
 *
 * @class           WPXCFTaxonomiesModuleOrphanCategoriesSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModuleOrphanCategoriesSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFTaxonomiesModuleOrphanCategoriesSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleOrphanCategoriesSlot
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
   * Create an instance of WPXCFTaxonomiesModuleOrphanCategoriesSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleOrphanCategoriesSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Orphan Post Categories', WPXCLEANFIX_TEXTDOMAIN ), __( 'Check for unused post categories.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @brief Checking
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $categories = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $categories ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan post category.', 'You have %s orphan post categories.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $categories, array( 'name' => '%s' ) );
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
SELECT

 T.term_id AS t_term_id,
 TT.term_taxonomy_id AS tt_term_taxonomy_id

FROM {$wpdb->terms} AS T

LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
LEFT JOIN {$wpdb->term_relationships} AS TR ON ( TR.term_taxonomy_id = TT.term_taxonomy_id )

WHERE TT.taxonomy = 'category'

AND T.term_id <> 1
AND TT.count = 0
AND TR.term_taxonomy_id IS NULL
AND ( SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent = T.term_id ) = 0

SQL;

    $items = $wpdb->get_results( $sql );

    $t_term_id           = array();
    $tt_term_taxonomy_id = array();

    foreach ( $items as $value ) {
      $t_term_id[]           = $value->t_term_id;
      $tt_term_taxonomy_id[] = $value->tt_term_taxonomy_id;
    }

    $wpdb->query( sprintf( 'DELETE FROM %s WHERE term_id IN( %s )', $wpdb->terms, implode( ',', $t_term_id ) ) );
    $wpdb->query( sprintf( 'DELETE FROM %s WHERE term_taxonomy_id IN( %s )', $wpdb->term_taxonomy, implode( ',', $tt_term_taxonomy_id ) ) );

    return $this->check();
  }

  /**
   * Return all unused categories
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<< SQL
SELECT *

FROM {$wpdb->terms} AS T
LEFT JOIN {$wpdb->term_taxonomy} AS TT ON T.term_id = TT.term_id
LEFT JOIN {$wpdb->term_relationships} AS TR ON TR.term_taxonomy_id = TT.term_taxonomy_id

WHERE TT.taxonomy = 'category'

AND T.term_id <> 1
AND TT.count = 0
AND TR.term_taxonomy_id IS NULL
AND ( SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent = T.term_id ) = 0

ORDER BY T.name
SQL;

    //WPXtreme::log( $sql );

    return $wpdb->get_results( $sql );
  }

}

/**
 * Single slot
 *
 * @class           WPXCFTaxonomiesModuleOrphanTermsSlot
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCFTaxonomiesModuleOrphanTermsSlot extends WPXCleanFixSlot {

  /**
   * Return a singleton instance of WPXCFTaxonomiesModuleOrphanTermsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleOrphanTermsSlot
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
   * Create an instance of WPXCFTaxonomiesModuleOrphanTermsSlot class
   *
   * @param WPXCFTaxonomiesModule $module
   *
   * @return WPXCFTaxonomiesModuleOrphanTermsSlot
   */
  public function __construct( $module )
  {
    parent::__construct( $module, __( 'Orphan Terms', WPXCLEANFIX_TEXTDOMAIN ), __( 'Check for unused generic terms.', WPXCLEANFIX_TEXTDOMAIN ) );
  }

  /**
   * Refresh/Check process
   *
   * @return WPXCleanFixModuleResponse
   */
  public function check()
  {
    $terms = $this->_check();

    // Get/Set issues
    $issues = $this->issues( count( $terms ) );

    if ( ! empty( $issues ) ) {
      $this->response->status          = WPXCleanFixModuleResponseStatus::WARNING;
      $this->response->description     = sprintf( _n( 'You have %s orphan generic term', 'You have %s orphan generic terms.', $issues, WPXCLEANFIX_TEXTDOMAIN ), $issues );
      $this->response->detail          = new WPXCleanFixSelectControl( $terms, array(
        'name'     => '%s',
        'taxonomy' => ' (%s)'
      ) );
      $this->response->cleanFix        = new WPXCleanFixButtonFixControl( $this );
      $this->response->cleanFix->title = __( 'Fix: click here to delete all orphan terms.', WPXCLEANFIX_TEXTDOMAIN );
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
SELECT

 T.term_id AS t_term_id,
 TT.term_taxonomy_id AS tt_term_taxonomy_id

FROM ( {$wpdb->terms} AS T )

LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
LEFT JOIN {$wpdb->term_relationships} AS TR ON TR.term_taxonomy_id = TT.term_taxonomy_id

WHERE ( TT.taxonomy <> 'category' AND TT.taxonomy <> 'post_tag' )

AND T.term_id <> 1
AND TT.count = 0
AND TR.term_taxonomy_id IS NULL
AND ( SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent = T.term_id ) = 0

ORDER BY T.name
SQL;

    $items = $wpdb->get_results( $sql );

    $t_term_id           = array();
    $tt_term_taxonomy_id = array();

    foreach ( $items as $value ) {
      $t_term_id[]           = $value->t_term_id;
      $tt_term_taxonomy_id[] = $value->tt_term_taxonomy_id;
    }

    $wpdb->query( sprintf( 'DELETE FROM %s WHERE term_id IN( %s )', $wpdb->terms, implode( ',', $t_term_id ) ) );
    $wpdb->query( sprintf( 'DELETE FROM %s WHERE term_taxonomy_id IN( %s )', $wpdb->term_taxonomy, implode( ',', $tt_term_taxonomy_id ) ) );

    return $this->check();

  }

  /**
   * Return all unused categories
   *
   * @return mixed
   */
  private function _check()
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $sql = <<< SQL
SELECT *

FROM ( {$wpdb->terms} AS T )

LEFT JOIN {$wpdb->term_taxonomy} AS TT ON ( T.term_id = TT.term_id )
LEFT JOIN {$wpdb->term_relationships} AS TR ON TR.term_taxonomy_id = TT.term_taxonomy_id

WHERE ( TT.taxonomy <> 'category' AND TT.taxonomy <> 'post_tag' )

AND T.term_id <> 1
AND TT.count = 0
AND TR.term_taxonomy_id IS NULL
AND ( SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE parent = T.term_id ) = 0

ORDER BY T.name
SQL;

    //WPXtreme::log( $sql );

    return $wpdb->get_results( $sql );
  }

}


