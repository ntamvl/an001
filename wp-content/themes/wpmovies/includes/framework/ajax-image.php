<?php

if (!function_exists('acera_ajax_image_upload') || !function_exists('acera_ajax_image_remove')) {
//Save image via AJAX
    add_action('wp_ajax_acera_ajax_upload', 'acera_ajax_image_upload'); //Add support for AJAX save

    function acera_ajax_image_upload() {
        global $wpdb; //Now WP database can be accessed


        $image_id = $_POST['data'];
        $image_filename = $_FILES[$image_id];
        $override['test_form'] = false; //see http://wordpress.org/support/topic/269518?replies=6
        $override['action'] = 'wp_handle_upload';

        $uploaded_image = wp_handle_upload($image_filename, $override);

        if (!empty($uploaded_image['error'])) {
            echo 'Error: ' . $uploaded_image['error'];
        } else {
            update_option($image_id, $uploaded_image['url']);
            echo $uploaded_image['url'];
        }

        die();
    }

//Remove image via AJAX
    add_action('wp_ajax_acera_ajax_remove', 'acera_ajax_image_remove'); //Add support for AJAX save

    function acera_ajax_image_remove() {
        global $wpdb; //Now WP database can be accessed


        $image_id = $_POST['data'];

        $query = "DELETE FROM $wpdb->options WHERE option_name LIKE '$image_id'";
        $wpdb->query($query);

        die();
    }

}
?>