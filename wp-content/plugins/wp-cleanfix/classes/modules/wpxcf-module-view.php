<?php

/**
 * Base class used to display a single module view and its slot-row.
 *
 * @class              WPXCleanFixModuleView
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-10-02
 * @version            1.0.0
 */
class WPXCleanFixModuleView extends WPDKView {

  /**
   * Current module.
   *
   * @var WPXCleanFixModule $module
   */
  public $module;

  /**
   * Create an instance of WPXCleanFixModuleView class
   *
   * @param WPXCleanFixModule $module The module instance.
   *
   */
  public function __construct( $module )
  {
    // Save module
    $this->module = $module;

    // Create the view
    parent::__construct( get_class( $this->module ) . '-module-view', 'wpxcf_module_view' );
  }

  /**
   * Drawing the main module view and its slot.
   *
   * @return string
   */
  public function draw()
  {

    ?>
    <table id="<?php $this->module->id ?>" class="wpxcf-table" cellpadding="0" cellspacing="0" width="100%">
      <tbody>
      <?php

      // Get list of slots
      $slots_list = $this->module->slots();

      // Loop into the class name list
      foreach( $slots_list as $class ) {

        /**
         * @var WPXCleanFixSlot $slot
         */
        $slot = call_user_func( array( $class, 'init' ), $this->module );

        /**
         * @var WPXCleanFixModuleResponse $response
         */
        $response = $slot->check();

        // Increments warnings count
        $warning = ( $response->status == WPXCleanFixModuleResponseStatus::OK ) ? 0 : 1;

        ?>
        <tr data-warning="<?php echo $warning ?>" class="<?php echo $slot->id ?>">

          <td class="wpxcf-column-hidle">
            <?php WPDKGlyphIcons::display( WPDKGlyphIcons::SPIN3 ) ?>
          </td>

          <td class="wpxcf-column-refresh">
            <?php
            $refresh = new WPXCleanFixButtonRefreshControl( $slot );
            $refresh->display(); ?>
          </td>

          <td class="wpxcf-column-title">
          <span data-placement="right"
                title="<?php echo $slot->description ?>"
                class="wpdk-has-tooltip"><?php echo $slot->title ?>
          </span>
          </td>

          <td class="wpxcf-column-status">
          <span class="wpdk-has-tooltip wpxcf-status-<?php echo $response->status ?>"
                title="<?php echo $response->description ?>">
          </span>
          </td>

          <td class="wpxcf-column-content">
            <?php
            if( !empty( $response->detail ) ) {
              $response->detail->display();
            } ?>
          </td>

          <td class="wpxcf-column-actions">
            <?php
            if( !empty( $response->cleanFix ) ) {
              $response->cleanFix->display();
            }
            ?>
          </td>
        </tr>

      <?php } ?>
      </tbody>
    </table>
  <?php
  }
}

/**
 * Utility control to display a combo information
 *
 * @class           WPXCleanFixSelectControl
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-02
 * @version         1.0.0
 *
 */
class WPXCleanFixSelectControl extends WPDKHTMLTagSelect {

  /**
   * Create an instance of WPXCleanFixSelectControl class
   *
   * @param array $items   Array of items to display in combo select.
   * @param array $columns Name/id f column.
   *
   * @return WPXCleanFixSelectControl
   */
  public function __construct( $items, $columns )
  {
    $options = array();
    foreach( $items as $item ) {
      $stack = array();
      foreach( $columns as $column_name => $format ) {
        if( isset( $item->$column_name ) ) {

          // TODO doc
          $stack[ ] = apply_filters( 'wpxcf_select_control_' . $column_name, sprintf( $format, $item->{$column_name} ), $format, $item );
        }
      }
      $options[ ] = implode( ' ', $stack );
    }
    parent::__construct( $options );

    $this->class = 'wpdk-form-select wpdk-ui-control';
  }

}

/**
 * Utility control to display a label information
 *
 * @class           WPXCleanFixLabelControl
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-10
 * @version         1.0.0
 *
 */
class WPXCleanFixLabelControl extends WPDKHTMLTagLabel {

  /**
   * Create an instance of WPXCleanFixLabelControl class
   *
   * @param string $label
   *
   * @return WPXCleanFixLabelControl
   */
  public function __construct( $label )
  {
    parent::__construct( $label );
  }
}


/**
 * Repair button control type
 *
 * @class              WPXCleanFixButtonFixControlType
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2013-07-01
 * @version            1.0.1
 */
class WPXCleanFixButtonFixControlType {

  // Action Button to clean unused data
  const CLEAN = 'clean';

  // Action Button to repair and fix data
  const FIX = 'fix';
}


/**
 * Button control used to repair
 *
 * @class           WPXCleanFixButtonFixControl
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-02
 * @version         1.0.0
 *
 */
class WPXCleanFixButtonFixControl extends WPDKHTMLTagButton {

  /**
   * Create an instance of WPXCleanFixButtonFixControl class
   *
   * @param WPXCleanFixSlot $slot An instance of WPXCleanFixSlot class
   * @param string          $type Optional. Type of button
   *
   * @return WPXCleanFixButtonFixControl
   */
  public function __construct( $slot, $type = WPXCleanFixButtonFixControlType::CLEAN )
  {
    parent::__construct();

    // Data atribute.
    $this->data = array(
      'module'    => get_class( $slot->module ),
      'slot'      => get_class( $slot ),
      'placement' => 'left',
      'confirm'   => ''
    );

    // Additional class.
    $classes     = array(
      'wpdk-has-tooltip',
      'wpxcf-button-action',
      'wpxcf-button-action-' . $type
    );
    $this->class = implode( ' ', $classes );
  }

  /**
   * Helper method to display a confirm message
   *
   * @param null|string $confirm Optional. Confirm message
   */
  public function confirm( $confirm = null )
  {
    $confirm                 = is_null( $confirm ) ? __( 'WARNING!! Are you sure you want to permanently delete these data? This action is not undoable.', WPXCLEANFIX_TEXTDOMAIN ) : $confirm;
    $this->data[ 'confirm' ] = $confirm;
  }
}

/**
 * Button control used to refresh/check
 *
 * @class           WPXCleanFixButtonRefreshControl
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-07-02
 * @version         1.0.0
 *
 */
class WPXCleanFixButtonRefreshControl extends WPDKHTMLTagButton {

  /**
   * Create an instance of WPXCleanFixButtonRefreshControl class
   *
   * @param WPXCleanFixSlot $slot An instance of WPXCleanFixSlot class
   *
   * @return WPXCleanFixButtonRefreshControl
   */
  public function __construct( $slot )
  {
    parent::__construct();

    // Data atribute.
    $this->data = array(
      'module' => get_class( $slot->module ),
      'slot'   => get_class( $slot ),
    );

    // Title.
    $this->title = __( 'Refresh', WPXCLEANFIX_TEXTDOMAIN );

    // Additional class.
    $classes = array(
      'wpxcf-button-action',
      'wpxcf-button-action-refresh'
    );

    $this->class = implode( ' ', $classes );
  }

}