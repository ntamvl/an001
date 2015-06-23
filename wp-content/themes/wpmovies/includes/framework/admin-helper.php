<?php
if (!function_exists('acera_admin_head')) { function acera_admin_head() { ?>
        <link href='http://fonts.googleapis.com/css?family=Rokkitt' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>css/mt_css.css" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>css/colorpicker.css" />
        <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>css/custom_style.css" />
        <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>js/colorpicker.js"></script>
        <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>js/ajaxupload.js"></script>
        <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>js/mainJs.js"></script>
        <?php
    }

}
?>