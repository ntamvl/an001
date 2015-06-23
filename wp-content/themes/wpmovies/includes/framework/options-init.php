<?php
// Remove this condition if you want to show theme options for all wordpress users, not only for admins.
if (is_admin()) {
    // Theme options
    include_once 'options/acera_options.php';
    include_once 'admin-helper.php';
    include_once 'ajax-image.php';
    include_once 'generate-options.php';
    new acera_theme_options($options);

    add_action('admin_head', 'acera_admin_head');
}
?>
