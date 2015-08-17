<?php
/**
 * Plugin Name: JSON REST API Category/Tag Patch
 * Description: Patches to the <strong>JSON Wordpress API</strong> to allow categories & tags to be set/edited in posts
 * Author: Ray Rodriguez
 * Author URI: http://www.wizadsl.com
 * Version: 1.01
 */


//Set this variable to true in order to append tags and categories instead of replacing them.
$plugin_patch_json_insert_post_append = false;

function plugin_patch_json_insert_post($post, $data, $update) {
global $plugin_patch_json_insert_post_append;
	if (!empty($data["x_tags"])) {
		if (is_array($data['x_tags'])) {
            wp_set_post_tags($post["ID"], $data["x_tags"], $plugin_patch_json_insert_post_append);
        }
    }
 	if (!empty($data['x_categories'])) {
		if (is_array( $data['x_categories'])) {
            for($x = 0; $x < count($data['x_categories']); $x++) {
				if (!ctype_digit($data['x_categories'][$x])) {
                    $data['x_categories'][$x] = get_cat_ID($data['x_categories'][$x]);
                }
            }
        wp_set_post_categories($post["ID"], $data['x_categories'], $plugin_patch_json_insert_post_append);
        }
    }
}

add_filter('json_insert_post', 'plugin_patch_json_insert_post', 20, 3 );
