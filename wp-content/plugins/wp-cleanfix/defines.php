<?php
/**
 * Constants
 *
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2013 wpXtreme Inc. All Rights Reserved.
 * @date               2013-09-13
 * @version            1.2.0
 *
 */

// ---------------------------------------------------------------------------------------------------------------------
// Shorthand
// ---------------------------------------------------------------------------------------------------------------------

/**
 * @var WPXCleanFix $this
 */

define( 'WPXCLEANFIX_VERSION', $this->version );
define( 'WPXCLEANFIX_TEXTDOMAIN', $this->textDomain );
define( 'WPXCLEANFIX_TEXTDOMAIN_PATH', $this->textDomainPath );

define( 'WPXCLEANFIX_URL_ASSETS', $this->assetsURL );
define( 'WPXCLEANFIX_URL_CSS', $this->cssURL );
define( 'WPXCLEANFIX_URL_JAVASCRIPT', $this->javascriptURL );
define( 'WPXCLEANFIX_URL_IMAGES', $this->imagesURL );

define( 'WPXCLEANFIX_URL_EMBEDDED_MODULES', $this->url . 'classes/embedded-modules/' );
define( 'WPXCLEANFIX_URL_EMBEDDED_TOOLS', $this->url . 'classes/embedded-tools/' );
define( 'WPXCLEANFIX_MENU_CAPABILITY', 'manage_options' );