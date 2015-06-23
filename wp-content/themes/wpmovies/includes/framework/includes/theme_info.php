<?php
$acera_theme_data = wp_get_theme();
define('THEME_NAME', $acera_theme_data->Name);
define('THEME_AUTHOR', $acera_theme_data->Author);
define('THEME_VERSION', trim($acera_theme_data->Version));
define('FRAMEWORK_NAME', 'Acera Theme Options');
?>
