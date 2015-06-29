<?php

/**
 * Add a several enhancer to users
 *
 * @class              WPXtremeEnhancerUser
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-09-30
 * @version            1.0.3
 *
 */
final class WPXtremeEnhancerUser {

  /**
   * The original role filter
   *
   * @var array $_views
   */
  private $_views;

  /**
   * Return a singleton instance of WPXtremeEnhancerUser class
   *
   * @return WPXtremeEnhancerUser
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
   * Create an instance of WPXtremeEnhancerUser class
   *
   * @return WPXtremeEnhancerUser
   */
  private function __construct()
  {
    // Fires in <head> for a specific admin page based on $hook_suffix.
    add_action( 'admin_head-users.php', array( $this, 'admin_head_users_php' ) );

    // Filter the list of available list table views.
    add_filter( 'views_users', array( $this, 'views_users' ) );

    // Extends Users List Table
    add_filter( 'manage_users_columns', array( $this, 'manage_users_columns' ) );

    // Filter the display output of custom columns in the Users list table.
    add_action( 'manage_users_custom_column', array( $this, 'manage_users_custom_column' ), 10, 3 );

    // Filter the list table sortable columns for a specific screen.
    add_filter( 'manage_users_sortable_columns', array( $this, 'manage_users_sortable_columns' ) );

    // Fires after the WP_User_Query has been parsed, and before the query is executed.
    add_action( 'pre_user_query', array( $this, 'pre_user_query' ) );

    /**
     * Fires when wpXtreme enhancer users is done.
     *
     * @since 1.4.10
     */
    do_action( 'wpxtreme_enhancer_users' );

  }

  /**
   * Fires in <head> for a specific admin page based on $hook_suffix.
   *
   * @since WP 2.1.0
   */
  public function admin_head_users_php()
  {
    WPDKUIComponents::init()->enqueue( WPDKUIComponents::TOOLTIP );
    wp_enqueue_script( 'wpxm-enhancer-user', WPXTREME_URL_JAVASCRIPT . 'wpxm-enhancer-user.js', array( 'jquery' ), WPXTREME_VERSION, true );
  }

  /**
   * Return the list table columns
   *
   * @param array $columns A key value pairs
   *
   * @return array
   */
  public function manage_users_columns( $columns )
  {
    $columns[ WPDKUserMeta::LAST_TIME_SUCCESS_LOGIN ] = __( 'Last login', WPXTREME_TEXTDOMAIN );
    $columns[ WPDKUserMeta::LAST_TIME_LOGOUT ]        = __( 'Last logout', WPXTREME_TEXTDOMAIN );
    $columns[ WPDKUserMeta::COUNT_SUCCESS_LOGIN ]     = __( '# Login', WPXTREME_TEXTDOMAIN );
    $columns[ WPDKUserMeta::COUNT_WRONG_LOGIN ]       = __( '# Wrong', WPXTREME_TEXTDOMAIN );
    $columns[ WPDKUserMeta::STATUS ]                  = __( 'Enabled', WPXTREME_TEXTDOMAIN );

    return $columns;
  }

  /**
   * Filter the display output of custom columns in the Users list table.
   *
   * @param string $output      Custom column output. Default empty.
   * @param string $column_name Column name.
   * @param int    $user_id     ID of the currently-listed user.
   */
  public function manage_users_custom_column( $output, $column_name, $user_id )
  {

    // Get user for retrive user meta
    $user = new WPDKUser( $user_id );

    switch ( $column_name ) {

      // Dates
      case WPDKUserMeta::LAST_TIME_SUCCESS_LOGIN:
      case WPDKUserMeta::LAST_TIME_LOGOUT:
        $date = $user->get( $column_name );
        if ( ! empty( $date ) ) {
          $output = WPDKDateTime::timeNewLine( date( 'j M, Y H:i:s', $date ) );
        }
        break;

      // Status
      case WPDKUserMeta::STATUS:
        // Get status
        $status = $user->get( $column_name );

        // Get description
        $status_description = $user->get( WPDKUserMeta::STATUS_DESCRIPTION );

        // Display enabled for unknown status
        if( empty( $status ) && empty( $status_description ) ) {
          $status_description = __( 'Enabled' );
        }
        elseif( empty( $status_description ) ) {
          $status_description = ucfirst( $status );
        }

        // Prepare switch ui button
        $item               = array(
          'type'     => WPDKUIControlType::SWITCH_BUTTON,
          'name'     => 'wpxm-user-enabled',
          'id'       => 'switch-' . $user_id,
          'data'     => array( 'user_id' => $user_id ),
          'title'    => $status_description,
          'value'    => ! in_array( $status, array( WPDKUserStatus::DISABLED, WPDKUserStatus::CANCELED) )
        );

        // Create the switch ui button
        $control = new WPDKUIControlSwitch( $item );

        $output = $control->html();

        break;

      // Success & wrong signin
      case WPDKUserMeta::COUNT_SUCCESS_LOGIN:
      case WPDKUserMeta::COUNT_WRONG_LOGIN:
        $count = $user->get( $column_name );
        $output = empty( $count ) ? '0' : $count;
        break;
    }

    // For lucky ignore switch ui button
    if ( isset( $_REQUEST['orderby'] ) && $column_name == $_REQUEST['orderby'] ) {
      $output = sprintf( '<strong>%s</strong>', $output );
    }

    return $output;
  }

  /**
   * Filter the list table sortable columns for a specific screen.
   *
   * The dynamic portion of the hook name, $this->screen->id, refers to the ID of the current screen, usually a string.
   *
   * @param array $sortable_columns An array of sortable columns.
   */
  public function manage_users_sortable_columns( $sortable_columns )
  {
    $sortable_columns[ WPDKUserMeta::STATUS ]                  = WPDKUserMeta::STATUS;
    $sortable_columns[ WPDKUserMeta::LAST_TIME_SUCCESS_LOGIN ] = WPDKUserMeta::LAST_TIME_SUCCESS_LOGIN;

    return $sortable_columns;
  }

  /**
   * Fires after the WP_User_Query has been parsed, and before the query is executed.
   *
   * The passed WP_User_Query object contains SQL parts formed from parsing the given query.
   *
   * @param WP_User_Query $this The current WP_User_Query instance, passed by reference.
   */
  public function pre_user_query( $query )
  {
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    /**
     * @var WP_Screen $current_screen
     */
    global $current_screen;

    if ( ! is_object( $current_screen ) ) {
      return;
    }

    if ( 'users' != $current_screen->id ) {
      return;
    }

    if ( ! empty( $_REQUEST['cap'] ) ) {
      $users = WPDKUsers::usersWithCaps( $_REQUEST['cap'] );
      //$query->query_from .= " JOIN {$wpdb->usermeta} cap_usermeta ON {$wpdb->users}.ID = cap_usermeta.user_id AND (cap_usermeta.meta_key = 'wp_capabilities' AND cap_usermeta.meta_value RLIKE '[[:<:]]" . $_REQUEST['cap'] . "[[:>:]]' )";
      $query->query_where .= ' AND ID IN( ' . join( ',', $users ) . ')';
    }

    if ( WPDKUserMeta::STATUS == $query->query_vars['orderby'] ) {
      $query->query_from .=
        " LEFT JOIN {$wpdb->usermeta} usermeta ON {$wpdb->users}.ID = usermeta.user_id AND (usermeta.meta_key = '" .
        WPDKUserMeta::STATUS . "')";
      $query->query_orderby = ' ORDER BY usermeta.meta_value ' . $query->query_vars['order'];
    }
    elseif ( WPDKUserMeta::LAST_TIME_SUCCESS_LOGIN == $query->query_vars['orderby'] ) {
      $query->query_from .=
        " LEFT JOIN {$wpdb->usermeta} usermeta ON {$wpdb->users}.ID = usermeta.user_id AND (usermeta.meta_key = '" .
        WPDKUserMeta::LAST_TIME_SUCCESS_LOGIN . "')";
      $query->query_orderby = ' ORDER BY usermeta.meta_value ' . $query->query_vars['order'];
    }
  }

  /**
   * Filter the list of available list table views.
   *
   * The dynamic portion of the hook name, $this->screen->id, refers to the ID of the current screen, usually a string.
   *
   * @param array $views An array of available list table views.
   */
  public function views_users( $views )
  {
    // Save original role filter list for catch link later.
    $this->_views = $views;

    // Get selected roles
    $role = isset( $_REQUEST['role'] ) ? $_REQUEST['role'] : '';

    WPDKHTML::startCompress();
    ?>

      <select class="wpdk-form-select wpdk-ui-control"
              onchange="document.location=jQuery(this).val()"
              name='wpxm-enhancer-users-roles-filter'>

        <option
          selected="selected"
          disabled="disabled"
          class="wpdk-form-option"><?php _e( 'Filter by Role', WPXTREME_TEXTDOMAIN ) ?>
        </option>

        <?php
        foreach ( $this->_views as $key => $value ) :
          $url = preg_match( "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/", $value, $match ); ?>

          <option
            class="wpdk-form-option" <?php selected( $key, $role ) ?>
            value="<?php echo $url ? $match[0] : '' ?>"><?php echo strip_tags( $value ) ?>
        </option>

        <?php endforeach; ?>
      </select>

      <?php
      $wpxtreme_role_filter = WPDKHTML::endHTMLCompress();

      // Capabilities filter
      $cap = isset( $_REQUEST['cap'] ) ? $_REQUEST['cap'] : '';

      WPDKHTML::startCompress();
      ?>
      <select class="wpdk-form-select wpdk-ui-control"
              onchange="document.location=jQuery(this).val()"
              name='wpxm-enhancer-users-capabilities-filter'>
        <option
          selected="selected"
          disabled="disabled"
          class="wpdk-form-option"><?php _e( 'Filter by Capability', WPXTREME_TEXTDOMAIN ) ?>
        </option>

        <option
          class="wpdk-form-option" <?php selected( $value, $cap ) ?>
          value="<?php echo remove_query_arg( 'cap' ) ?>"><?php _e( 'None', WPXTREME_TEXTDOMAIN ) ?>
        </option>

        <?php
        foreach ( WPDKUserCapabilities::init()->userCapabilities as $value ) : ?>

          <option
            class="wpdk-form-option" <?php selected( $value, $cap ) ?>
            value="?cap=<?php echo $value ?>"><?php echo $value ?>
          </option>

        <?php endforeach; ?>
      </select>

    <?php
    $wpxtreme_capability_filter = WPDKHTML::endHTMLCompress();

    // Filters
    $views = array(
      'wpxtreme_role_filter'       => $wpxtreme_role_filter,
      'wpxtreme_capability_filter' => $wpxtreme_capability_filter
    );

    /**
     * Filter the views filters for a user.
     *
     * @param array $views An array with HTML markup for filter UI.
     */
    $views = apply_filters( 'wpxm_users_views', $views );

    return $views;
  }

}