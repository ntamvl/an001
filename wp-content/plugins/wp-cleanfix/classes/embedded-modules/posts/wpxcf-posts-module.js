/**
 * Posts Module model
 *
 * @class           WPXCFPostsModule
 * @author          =undo= <info@wpxtre.me>
 * @copyright       Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date            2013-11-21
 * @version         1.0.1
 *
 */
jQuery( function ( $ )
{
  "use strict";

  window.WPXCFPostsModule = (function ()
  {
    /**
     * This object
     *
     * @type {{version: string, init: null}}
     * @private
     */
    var _WPXCFPostsModule = {
      version : '1.0.1',
      init    : null
    };

    /**
     * Return an instance of WPXCFPostsModule class
     *
     * @return {}
     */
    _WPXCFPostsModule.init = function ()
    {
      wpdk_add_action( 'wpxcf_before_fix', _beforeFix );

      return _WPXCFPostsModule;
    };

    /**
     * Fires before execute a clean/fix.
     *
     * The dynamic portion of the hook name, e.data, refers to the action type.
     *
     * @param {object} $control The control object.
     * @param {string} slot The class slot name.
     *
     * @return {boolean}
     */
    function _beforeFix( control, slot )
    {
      if ( 'WPXCFPostsModulePostsWithoutAuthorSlot' == slot ) {
        var users_posts = $( '#users_posts' );
        if ( '' == users_posts.val() ) {
          alert( users_posts.data( 'warning' ) );
          return false;
        }
        else {
          return users_posts.val();
        }
      }
    }

    return _WPXCFPostsModule.init();

  })();

});