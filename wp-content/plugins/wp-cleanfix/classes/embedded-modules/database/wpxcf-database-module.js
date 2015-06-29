/**
 * Database Module Model
 *
 * @class           WPXCFDatabaseModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-11-21
 * @version         1.0.1
 *
 */
jQuery( function ( $ )
{
  "use strict";

  window.WPXCFDatabaseModule = (function ()
  {
    /**
     * This object
     *
     * @type {{version: string, init: _init}}
     * @private
     */
    var _WPXCFDatabaseModule = {
      version : '1.0.1',
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
      // Fires when a fix has be done.
      wpdk_add_action( 'wpxcf_after_fix', _afterFix );

      return _WPXCFDatabaseModule;
    }

    /**
     * Fires when a fix is done.
     *
     * @param {string} module Class Module name.
     * @param {string} slot Class slot name.
     * @param {object} result Ajax results.
     * @private
     */
    function _afterFix( module, slot, result )
    {
      if ( 'WPXCFDatabaseModule' !== module && 'WPXCFDatabaseModuleOptimizationSlot' !== slot ) {
        $( 'button.wpxcf-button-action-refresh[data-slot="WPXCFDatabaseModuleOptimizationSlot"]' ).click();
      }
    }

    return _init();

  })();

} );