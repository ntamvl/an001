<?php

/**
 * Constants
 *
 * @author             =undo= <info@wpxtre.me>
 * @copyright          Copyright (C) 2012-2014 wpXtreme Inc. All Rights Reserved.
 * @date               2014-05-28
 * @version            1.2.0
 *
 */

/**
 * @var WPXtreme $this
 */

// Short hand for text domain.
define( 'WPXTREME_VERSION', $this->version );
define( 'WPXTREME_TEXTDOMAIN', $this->textDomain );

define( 'WPXTREME_URL_ASSETS', $this->assetsURL );
define( 'WPXTREME_URL_CSS', $this->cssURL );
define( 'WPXTREME_URL_JAVASCRIPT', $this->javascriptURL );
define( 'WPXTREME_URL_IMAGES', $this->imagesURL );

/*
* Path unix: /var/
*/

// Set constant path: plugin directory
define( 'WPXTREME_PATH', $this->path );
define( 'WPXTREME_PATH_CLASSES', $this->classesPath );
define( 'WPXTREME_PATH_DATABASE', $this->databasePath );

// Standard message when and error/warning occours
define( 'WPXTREME_SUPPORT', __( '<p>If the problem persist, please contact our <a href="mailto:support@wpxtre.me">support team</a></p>', WPXTREME_TEXTDOMAIN ) );

// ---------------------------------------------------------------------------------------------------------------------
// Development
// ---------------------------------------------------------------------------------------------------------------------

define( 'WPXTREME_DEBUG', false );
define( 'WPXTREME_IMPROVE_DEBUG_OUTPUT', false );