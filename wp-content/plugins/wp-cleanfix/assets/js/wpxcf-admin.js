jQuery( function ( $ )
{
  "use strict";

  /**
   * CleanFix Javascript
   *
   * @class           WPXCleanFix
   * @author          =undo= <info@wpxtre.me>
   * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
   * @date            2014-07-17
   * @version         1.0.3
   *
   */
  window.WPXCleanFix = (function ()
  {

    /**
     * This object
     *
     * @type {{version: string, init: _init}}
     * @private
     */
    var _WPXCleanFix = {
      version : '1.0.3',
      init    : _init
    };

    /**
     * Init
     *
     * @returns {{version: string, init: _init}}
     * @private
     */
    function _init()
    {
      return _WPXCleanFix;
    }

    return _init();

  })();

  /**
   * @since 1.2.90
   * @type {{REFRESH: string}}
   */
  window.WPXCleanFixModuleEvents = {
    REFRESH      : 'refresh' + 'wpxcf.module',
    UPDATE_BADGE : 'update_badge' + 'wpxcf'
  };

  /**
   * CleanFix Module
   *
   * @class           WPXCleanFixModule
   * @author          =undo= <info@wpxtre.me>
   * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
   * @date            2014-07-17
   * @version         1.0.4
   *
   */
  window.WPXCleanFixModule = (function ()
  {
    /**
     * This object
     *
     * @type {{version: string, refresh_count: number, init: _init}}
     * @private
     */
    var _WPXCleanFixModule = {
      version       : '1.0.4',
      refresh_count : 0,
      init          : _init
    };

    /**
     * Init
     *
     * @returns {{version: string, refresh_count: number, init: _init}}
     * @private
     */
    function _init()
    {
      // Replace span with button
      $( 'h3 .wpxcf-button-action-refresh-all' ).each( function ( i, e )
      {
        var module = $( e ).data( 'module' );
        var title = $( e ).attr( 'title' );
        var classes = $( e ).attr( 'class' );
        var button = sprintf( '<button data-module="%s" title="%s" class="%s"></button>', module, title, classes );
        $( e ).replaceWith( button );
      } );

      // Listener for refresh
      $( document ).on( 'click ' + WPXCleanFixModuleEvents.REFRESH, 'button.wpxcf-button-action-refresh', 'refresh', _send );

      // Remove WordPress title collapese
      $( 'h3.hndle' ).off( 'click' );

      // Listener for refresh all
      $( document ).on( 'click', 'button.wpxcf-button-action-refresh-all', false, _refreshAll );

      // Listener for clean & fix
      $( document ).on( 'click', 'button.wpxcf-button-action-fix, button.wpxcf-button-action-clean', 'fix', _send );

      // Update badge by event
      $( document ).on( WPXCleanFixModuleEvents.UPDATE_BADGE, _refreshBadge );

      // Refresh and Update for first time
      _refreshBadge();
      _updateBadge();

      return _WPXCleanFixModule;
    }

    /**
     * Refresh all
     *
     * @param {object} e Event object.
     * @private
     */
    function _refreshAll( e )
    {
      var event = e;
      var module = $( this ).data( 'module' );

      // Used to check the finish of refresh module slots
      _WPXCleanFixModule.refresh_count += $( '#' + module + ' .inside .wpxcf-button-action-refresh' ).length;

      $( '#' + module + ' .inside .wpxcf-button-action-refresh' ).each( function ()
      {
        $( this ).trigger( WPXCleanFixModuleEvents.REFRESH, event )
      } );

      return false;

    }

    /**
     * Sending
     *
     * @param object} e Event object.
     * @returns {boolean}
     * @private
     */
    function _send( e )
    {
      var $control = $( this );
      var slot = $( this ).data( 'slot' );

      /**
       * Filter the continue flag before clean/fix.
       *
       * The dynamic portion of the hook name, e.data, refers to the action type.
       *
       * @param {boolean} conitnue Default TRUE.
       * @param {object} $control The control object.
       * @param {string} slot The class slot name.
       *
       * @return {boolean}
       */
      var result = wpdk_apply_filters( 'wpxcf_before_' + e.data, true, $control, slot );

      if ( false === result ) {
        return false;
      }

      // Check for confirm
      var confim_message = $( this ).data( 'confirm' );
      if ( 'undefined' !== typeof( confim_message ) && !empty( confim_message ) ) {
        if ( !confirm( confim_message ) ) {
          return false;
        }
      }

      // Get parent tr and set its opacity
      var tr = $( this ).parents( 'tr' ).addClass( 'wpx-cleanfix-busy' );

      // Hide ant displayed tooltip
      $( this ).wpdkTooltip( 'hide' );

      // Get module id
      var module = $( this ).data( 'module' );

      // Get slot id
      var slot = $( this ).data( 'slot' );

      // Ajax
      $.post( wpdk_i18n.ajaxURL, {
          _ajax_nonce : wpxcf_i18n.ajax_nonce,
          action      : 'wpxcf_action',
          method      : e.data,
          slot        : slot,
          module      : module
        }, function ( data )
        {
          var result = $.parseJSON( data );

          if ( empty( result.error ) ) {
            tr.data( 'warning', result.warning );
            tr.find( '.wpxcf-column-status' ).html( result.status );
            tr.find( '.wpxcf-column-content' ).html( result.content );
            tr.find( '.wpxcf-column-actions' ).html( result.actions );
            tr.removeClass( 'wpx-cleanfix-busy' );

            /**
             * Fires when a fix has be done.
             *
             * @param string module The class module name.
             * @param string slot The class slot name.
             * @param object result The object result from JSON response.
             */
            wpdk_do_action( 'wpxcf_after_' + e.data, module, slot, result );

            // Update badge if user click
            if ( 'click' === e.type ) {
              _refreshBadge();
              _updateBadge();
            }
            else {

              // Decrement refresh count
              _WPXCleanFixModule.refresh_count--;

              // If zero this is the last
              if ( _WPXCleanFixModule.refresh_count == 0 ) {
                _refreshBadge();
                _updateBadge();
              }
            }

            /**
             * Fires to request a tooltip refresh.
             */
            wpdk_do_action( WPDKUIComponents.REFRESH_TOOLTIP );

          }
          // An error return
          else {
            alert( result.errorDescription );
          }
        }
      );

      return false;
    }

    /**
     * Update the badge in the menu via data tr.
     *
     * @since 1.2.90
     * @private
     */
    function _refreshBadge()
    {
      var warnings = 0;
      var $badge = $( 'span.wpxcf-badge' ).length ? $( 'span.wpxcf-badge' ) : false;

      if ( $badge ) {
        $( 'tr[data-warning]' ).each( function ()
        {
          warnings += parseInt( $( this ).data( 'warning' ) );
        } ).promise().done( function ()
        {

          if ( warnings > 0 ) {
            $badge.html( '<span class="plugin-count">' + warnings + '</span>' );
          }
          else {
            $badge.removeClass( 'update-plugins' )
              .removeClass( 'wpdk-badge' )
              .html( '' );
          }
        } );
      }
    }

    /**
     * Updated badge transient
     *
     * @private
     */
    function _updateBadge()
    {
      var warnings = 0;

      $( 'tr[data-warning]' ).each( function ()
      {
        warnings += parseInt( $( this ).data( 'warning' ) );
      } ).promise().done( function ()
      {
        $.post( wpdk_i18n.ajaxURL, {
            action   : 'wpxcf_action_update_badge',
            warnings : warnings
          }
        );
      } );
    }

    return _init();

  })();

  /**
   * CleanFix Module Response
   *
   * @class           WPXCleanFixModuleResponse
   * @author          =undo= <info@wpxtre.me>
   * @copyright       Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
   * @date            2014-07-18
   * @version         1.0.2
   */
  window.WPXCleanFixModuleResponse = function ()
  {
    this.error = 0;
    this.errorDescription = '';
    this.warning = '';
    this.status = '';
    this.content = '';
    this.action = '';
  };

} );