<?php
add_action('admin_menu', 'tj_create_meta_box');
add_action('save_post', 'tj_save_meta_data');
function tj_create_meta_box() {
    add_meta_box('post-meta-boxes', __('Movie Metadata', 'mundothemes'), 'post_meta_boxes', 'post', 'normal', 'high');
    add_meta_box('post-meta-boxes', __('Movie Metadata', 'mundothemes'), 'post_meta_boxes', 'tvshows', 'normal', 'high');
}
function tj_post_meta_boxes() {
    $mundothemes_seotitle = get_option('mundothemes_seo_single_field_title');
    $mundothemes_seodescription = get_option('mundothemes_seo_single_field_description');
    $mundothemes_keywords = get_option('mundothemes_seo_single_field_keywords');
    if (get_option('mundothemes_seo_field') == 'On') {
        $meta_boxes = array();
    }
    else {
        $meta_boxes = array(

        /* meta datos */

        /***********************************************************************************************************************************
        '' => array( 'name' => '', 'title' => __('', 'mundothemes'), 'type' => 'select', 'desc' => '', 'options' => array('', '', '', '') ),
        '' => array( 'name' => '', 'title' => __('', 'mundothemes'), 'type' => 'textarea', 'desc' => ''),
        '' => array( 'name' => '', 'title' => __('', 'mundothemes'), 'type' => 'text', 'desc' => ''),
        ************************************************************************************************************************************/
        'poster_url' => array('name' => 'poster_url', 'title' => __('URL Poster (optional)', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'cover_url' => array('name' => 'cover_url', 'title' => __('Cover image URL (optional)', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Title' => array('name' => 'Title', 'title' => __('Original title', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Rated' => array('name' => 'Rated', 'title' => __('Rated', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Released' => array('name' => 'Released', 'title' => __('Release Date', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Language' => array('name' => 'Language', 'title' => __('Language', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Runtime' => array('name' => 'Runtime', 'title' => __('Runtime', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'imdbRating' => array('name' => 'imdbRating', 'title' => __('IMDB Rating', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'imdbVotes' => array('name' => 'imdbVotes', 'title' => __('IMDB votes', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Awards' => array('name' => 'Awards', 'title' => __('Awards', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')), 'Country' => array('name' => 'Country', 'title' => __('Country', 'mundothemes'), 'type' => 'text', 'desc' => __('', 'mundothemes')),);
    }
    return apply_filters('tj_post_meta_boxes', $meta_boxes);
}

/* ---------------------------------------------------------- */
function tj_page_meta_boxes() {
    $mundothemes_seotitle = get_option('mundothemes_seo_single_field_title');
    $mundothemes_seodescription = get_option('mundothemes_seo_single_field_description');
    $mundothemes_keywords = get_option('mundothemes_seo_single_field_keywords');
    if (get_option('mundothemes_seo_field') == 'On') {
        $meta_boxes = array();
    }
    else {
        $meta_boxes = array();
    }
    return apply_filters('tj_page_meta_boxes', $meta_boxes);
}
function post_meta_boxes() {
    global $post;
    $meta_boxes = tj_post_meta_boxes();
?>
<table class="form-table">
<?php
    foreach ($meta_boxes as $meta):
        $value = get_post_meta($post->ID, $meta['name'], true);
        if ($meta['type'] == 'text') get_meta_text_input($meta, $value);
        elseif ($meta['type'] == 'textarea') get_meta_textarea($meta, $value);
        elseif ($meta['type'] == 'select') get_meta_select($meta, $value);
        elseif ($meta['type'] == 'mundothemesselect') get_meta_mundothemesselect($meta, $value);
        elseif ($meta['type'] == 'selectadmin') get_meta_selectadmin($meta, $value);
        elseif ($meta['type'] == 'checkbox') get_meta_checkbox($meta, $value);
        elseif ($meta['type'] == 'selectdate') get_meta_selectgrup($meta, $value);
    endforeach; ?>
</table>
<?php
}
function page_meta_boxes() {
    global $post;
    $meta_boxes = tj_page_meta_boxes();
?>
<table class="form-table">
<?php
    foreach ($meta_boxes as $meta):
        $value = get_post_meta($post->ID, $meta['name'], true);
        if ($meta['type'] == 'text') get_meta_text_input($meta, $value);
        elseif ($meta['type'] == 'textarea') get_meta_textarea($meta, $value);
        elseif ($meta['type'] == 'select') get_meta_select($meta, $value);
        elseif ($meta['type'] == 'mundothemesselect') get_meta_mundothemesselect($meta, $value);
        elseif ($meta['type'] == 'selectadmin') get_meta_selectadmin($meta, $value);
        elseif ($meta['type'] == 'checkbox') get_meta_checkbox($meta, $value);
        elseif ($meta['type'] == 'selectdate') get_meta_selectgrup($meta, $value);
    endforeach; ?>
</table>
<?php
}
function get_meta_text_input($args = array(), $value = false) {
    extract($args); ?>
<tr>
<th style="width:40%;">
<label for="<?php
    echo $name; ?>"><?php
    echo $title; ?></label>
</th>
<td>
<input type="text" name="<?php
    echo $name; ?>" id="<?php
    echo $name; ?>" value="<?php
    echo wp_specialchars($value, 1); ?>" size="30" tabindex="30" style="width: 250px;margin-top:-3px;" />
<input type="hidden" name="<?php
    echo $name; ?>_noncename" id="<?php
    echo $name; ?>_noncename" value="<?php
    echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
<br />
<p class="description"><?php
    echo $desc; ?></p>
</td>
</tr>
<?php
}
function get_meta_select($args = array(), $value = false) {
    extract($args); ?>
<tr>
<th style="width:20%;">
<label for="<?php
    echo $name; ?>"><?php
    echo $title; ?></label>
</th>
<td>
<select name="<?php
    echo $name; ?>" id="<?php
    echo $name; ?>">
<?php
    foreach ($options as $option): ?>
<option <?php
        if (htmlentities($value, ENT_QUOTES) == $option) echo ' selected="selected"'; ?>>
<?php
        echo $option; ?>
</option>
<?php
    endforeach; ?>
</select>
<input type="hidden" name="<?php
    echo $name; ?>_noncename" id="<?php
    echo $name; ?>_noncename" value="<?php
    echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
<p class="description"><?php
    echo $desc; ?></p>
</td>
</tr>
<?php
}
function get_meta_textarea($args = array(), $value = false) {
    extract($args); ?>
<tr>
<th style="width:20%;">
<label for="<?php
    echo $name; ?>"><?php
    echo $title; ?></label>
</th>
<td>
<textarea name="<?php
    echo $name; ?>" id="<?php
    echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 97%;margin-top:-3px;"><?php
    echo wp_specialchars($value, 1); ?></textarea>
<input type="hidden" name="<?php
    echo $name; ?>_noncename" id="<?php
    echo $name; ?>_noncename" value="<?php
    echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
<p class="description"><?php
    echo $desc; ?></p>
</td>
</tr>
<?php
}
function get_meta_mundothemesselect($args = array(), $value = false) {
    extract($args); ?>
<tr>
<th style="width:20%;">
<label for="<?php
    echo $name; ?>" style="font-weight:bold;"><?php
    echo $title; ?></label>
</th>
<td>
<span class="description">----------------------------------------------------------------------------------------------</span>
<input type="hidden" name="<?php
    echo $name; ?>_noncename" id="<?php
    echo $name; ?>_noncename" value="<?php
    echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
</td>
</tr>
<?php
}
function tj_save_meta_data($post_id) {
    global $post;
    if ('page' == $_POST['post_type']) $meta_boxes = array_merge(tj_page_meta_boxes());
    else $meta_boxes = array_merge(tj_post_meta_boxes());
    foreach ($meta_boxes as $meta_box):
        if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) return $post_id;
        if ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post_id)) return $post_id;
        elseif ('post' == $_POST['post_type'] && !current_user_can('edit_post', $post_id)) return $post_id;
        $data = stripslashes($_POST[$meta_box['name']]);
        if (get_post_meta($post_id, $meta_box['name']) == '') add_post_meta($post_id, $meta_box['name'], $data, true);
        elseif ($data != get_post_meta($post_id, $meta_box['name'], true)) update_post_meta($post_id, $meta_box['name'], $data);
        elseif ($data == '') delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
    endforeach;
}
?>