<?php
function server() {
global $wp_version;
$license = trim( get_option( 'edd_sample_theme_license_key' ) );
$api_params = array(
'edd_action' => 'check_license',
'license' => $license,
'item_name' => urlencode( EDD_SL_THEME_NAME ) );
$response = wp_remote_get( add_query_arg( $api_params, EDD_SL_STORE_URL ), array( 'timeout' => 5, 'sslverify' => false ) );
if ( is_wp_error( $response ) )
return false;
$license_data = json_decode( wp_remote_retrieve_body( $response ) );
if( $license_data->license == 'valid' ) { } else { ?>
<div id="message" class="off error below-h2">
<p><span class="dashicons dashicons-admin-generic" style="color: #F07E78;"></span> Es posible que esta secci&oacute;n no funcione correctamente, <strong><?php echo THEME_NAME; ?> <?php echo THEME_VERSION; ?></strong> solicita la activaci&oacute;n de su licencia, hagalo desde <a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=mundothemes">aqu&iacute;</a> 
</p>
</div>
<?php
	}
}
include 'includes/theme_info.php';
define('MENU_TITLE', 'WPMovies'); 
define('MENU_SLUG', 'wpmovies');
if (!class_exists('acera_theme_options')) {
class acera_theme_options {
private $options;
public function acera_theme_options($options) {
$this->options = $options;
add_action('admin_menu', array(&$this, 'acera_add_menu')); }
public function acera_add_menu() {
add_menu_page(__(MENU_TITLE), __(MENU_TITLE), 'administrator', '' . MENU_SLUG, array(&$this, 'acera_display_page'), 'dashicons-schedule'); } public function acera_display_page() { ?>
<?php 
global $wp_version;
$license = trim( get_option( 'edd_sample_theme_license_key' ) );
$api_params = array(
'edd_action' => 'check_license',
'license' => $license,
'item_name' => urlencode( EDD_SL_THEME_NAME ) );
$response = wp_remote_get( add_query_arg( $api_params, EDD_SL_STORE_URL ), array( 'timeout' => 5, 'sslverify' => false ) );
if ( is_wp_error( $response ) )
return false;
$license_data = json_decode( wp_remote_retrieve_body( $response ) );
if( $license_data->license == 'valid' ) {
$this->save_options();
}
?>
<form id="acera-settings" method="post">
<?php server(); ?> 
<input type="hidden" name="action" id="action" value="acera_save_options" />
<div id="acera-sidebar">
<ul id="acera-main-menu">
<?php $first = true; ?> 
<?php
foreach ($this->options as $option):
if ($option['type'] == "section") :
$section = $option['id']; ?>
<li><p><?php echo $option['title']; ?></p>
<ul<?php if ($option['expanded'] == "true") echo ' class="default-accordion"'; ?>>
<?php foreach ($this->options as $sections):
if (($sections['section'] == $section) && (($sections['type'] == "heading") || ($sections['type'] == "html"))): ?>
<li><a<?php if ($first) { echo ' class="defaulttab"'; $first = false; } ?> href="#" rel="<?php echo $sections['id']; ?>"><p><?php echo $sections['title']; ?></p></a></li>
<?php endif; endforeach; ?> 
</ul>
</li>
<?php endif; endforeach; ?>
</ul>
</div>
<?php /* Below - script that generates divs for each tab */ ?>
 <div id="acera-content">
<?php foreach ($this->options as $option): ?> 
<?php if ($option['type'] == "heading"): ?>
<?php $under_section = $option['id']; ?>
<div class="tab-content" id="<?php echo $option['id']; ?>">
<div class="acera-settings-headline">
<h2><?php echo $option['title']; ?></h2>
<a href="https://mundothemes.com/?key=<?php echo get_option('edd_sample_theme_license_key'); ?>" target="_blank"><img class="mundothemes" src="<?php echo get_stylesheet_directory_uri()."/includes/framework/"; ?>images/logo.png"></a>
</div>

<?php
foreach ($this->options as $item) {
if ($item['under_section'] == $under_section) {
switch ($item['type']) {

case "text":
$this->display_text($item);
break;

case "color":
$this->display_color($item);
break;

case "small_heading":
$this->display_small_heading($item);
break;

case "textarea":
$this->display_textarea($item);
break;

case "image":
$this->display_image($item);
break;

case "checkbox":
$this->display_checkbox($item);
break;

case "checkbox_image":
$this->display_checkbox_image($item);
break;

case "radio":
$this->display_radio($item);
break;

case "toggle_div_start":
$this->display_toggle_div_start($item);
break;

case "toggle_div_end":
$this->display_toggle_div_end();
break;

case "radio_image":
$this->display_radio_image($item);
break;

case "select":
$this->display_select($item);
break; } } }
if($key = get_option('edd_sample_theme_license_key')) { ?>
<span class="key_mt">
&copy; <b><a href="https://mundothemes.com/wpmovies/?key=<?php echo $key; ?>" target="_blank"><?php echo THEME_NAME; ?></a></b> <?php echo THEME_VERSION; ?> - <b><?php echo $key; ?></b></span>
<?php } ?>
<input name="save" class="save-button" type="submit" value="<?php _e('Save Changes', 'mundothemes'); ?>" />
</div>
<?php endif; ?>
<?php if ($option['type'] == "html"): ?>
<div class="tab-content" id="<?php echo $option['id']; ?>">
<?php echo $option['source']; ?>
</div>
<?php endif; ?>
<?php endforeach; ?>
</div> 
</form>
<?php  }
 public function display_text($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = "";
?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="acera_image_preview">
<?php if ($value['img_desc']): ?>
<img src="<?php echo $value['img_desc']; ?>" />
<?php endif; ?>
</div>
<input<?php if ($value['placeholder']) echo ' placeholder="' . $value['placeholder'] . '"'; ?> class="acera-fullwidth" id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" type="text" value="<?php if (get_option($value['id'])) echo esc_html(stripslashes(get_option($value['id'])));else echo $value['default']; ?>" />
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php }
public function display_color($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<?php
if (get_option($value['id']))
$color = ' style="background-color: #' . get_option($value['id']) . ';"';
else if ($value['default'])
$color = ' style="background-color: #' . $value['default'] . ';"'
?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="acera_image_preview">
<?php if ($value['img_desc']): ?>
<img src="<?php echo $value['img_desc']; ?>" />
<?php endif; ?>
</div>
<input<?php if ($value['placeholder']) echo ' placeholder="' . $value['placeholder'] . '"'; ?> class="acera-color-picker"<?php echo $color; ?> id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" type="text" value="<?php if (get_option($value['id'])) echo esc_html(stripslashes(get_option($value['id'])));else echo $value['default']; ?>" />
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php } /* Image Upload ("type" => "image") */
public function display_image($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<?php if (get_option($value['id']))
$def_value = stripslashes(get_option($value['id']));
else
$def_value = $value['default']; ?>
<input<?php if ($value['placeholder']) echo ' placeholder="' . $value['placeholder'] . '"'; ?> class="acera-fullwidth" type="text" value="<?php echo $def_value; ?>" name="<?php echo $value['id']; ?>" />
<span class="upload acera_upload acera-button-blue" id="<?php echo $value['id']; ?>"><?php _e('Upload Image', 'mundothemes'); ?></span>
<?php if (get_option($value['id'])): ?>
<span type="button" class="acera_remove acera-button" id="remove_<?php echo $value['id']; ?>"><?php _e('Remove image', 'mundothemes'); ?></span>
<?php endif; ?>
<div class="acera_image_preview">
<?php if (get_option($value['id'])): ?>
<img src="<?php echo get_option($value['id']); ?>" />
<?php elseif ($value['default'] != ""): ?>
<img src="<?php echo $value['default']; ?>" />
<?php endif; ?>
</div>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php } /* Textarea input ("type" => "textarea") */
public function display_textarea($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="acera_image_preview">
<?php if ($value['img_desc']): ?>
<img src="<?php echo $value['img_desc']; ?>" />
<?php endif; ?>
</div>
<textarea<?php if ($value['placeholder']) echo ' placeholder="' . $value['placeholder'] . '"'; ?> id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>" cols="70" rows="8"><?php
if (get_option($value['id']))
echo stripslashes(get_option($value['id']));
else
echo $value['default']; ?></textarea>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php } /* Select input ("type" => "select") */
public function display_select($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="acera_image_preview">
<?php if ($value['img_desc']): ?>
<img src="<?php echo $value['img_desc']; ?>" />
<?php endif; ?>
</div>
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php if (get_option($value['id']))
$default = get_option($value['id']);
else
$default = $value['default'];
foreach ($value['options'] as $option):
$selected = '';
if ($option == $default)
$selected = ' selected="selected"'; ?>
<option <?php echo $selected; ?>><?php echo $option ?></option>
<?php endforeach; ?>
</select>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php } /* Normal checkbox ("type" => "checkbox") */
public function display_checkbox($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="acera_image_preview">
<?php if ($value['img_desc']): ?>
<img src="<?php echo $value['img_desc']; ?>" />
<?php endif; ?>
</div>
<?php
$i = 0;
foreach ($value['options'] as $box):
$checked = '';
if (get_option($value['id'][$i])) {
if (get_option($value['id'][$i]) == 'true')
$checked = ' checked="checked"';
else
$checked = '';
} else {
if ($value['default'][$i] == "checked")
$checked = ' checked="checked"'; } ?>
<label for="<?php echo $value['id'][$i]; ?>">
<input type="checkbox"<?php echo $checked; ?> name="<?php echo $value['id'][$i]; ?>" id="<?php echo $value['id'][$i]; ?>" />
<?php echo $box; ?>
</label>
<?php $i++; endforeach; ?>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php } /* Image checkbox ("type" => "checkbox_image") */
 public function display_checkbox_image($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="cOf">
<?php
$i = 0;
foreach ($value['options'] as $box):
$checked = '';
$class = '';
$img_size = '';
if ($value['image_size'][$i])
$img_size = 'width="' . $value['image_size'][$i] . '"';
else if ($value['image_size'][$i] == false && $value['image_size'][0] == true)
$img_size = 'width="' . $value['image_size'][0] . '"';
else
$img_size = 'width="120"';
if (get_option($value['id'][$i])) {
if (get_option($value['id'][$i]) == 'true') {
$checked = ' checked="checked"';
$class = ' acera-img-selected';
} } elseif ($value['default'][$i] == "checked") {
$class = ' acera-img-selected';
$checked = ' checked="checked"';
} ?>
<label class="acera-image-checkbox<?php echo $class; ?>" for="<?php echo $value['id'][$i]; ?>">
<img <?php echo $img_size; ?> src="<?php echo $value['image_src'][$i]; ?>" alt="<?php echo $box ?>" />
<input class="acera-image-checkbox-b" type="checkbox"<?php echo $checked; ?> name="<?php echo $value['id'][$i]; ?>" id="<?php echo $value['id'][$i]; ?>" />
<?php if ($value['show_labels'] == "true"): ?><p><?php echo $box; ?></p><?php endif; ?></label>
<?php  $i++; endforeach; ?>
</div>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php }
/* Normal radio input ("type" => "radio") */
 public function display_radio($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="acera_image_preview">
<?php if ($value['img_desc']): ?>
<img src="<?php echo $value['img_desc']; ?>" />
<?php endif; ?>
</div>
<?php
$i = 0;
if (get_option($value['id']))
$default = get_option($value['id']);
else
$default = $value['default'];
foreach ($value['options'] as $option):
$checked = '';
if ($value['options'][$i] == $default) {
$checked = ' checked="checked"';
} ?>
<label for="<?php echo $value['id'] . $i; ?>">
<input type="radio" id="<?php echo $value['id'] . $i; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $value['options'][$i]; ?>" <?php echo $checked; ?> />
<?php echo $option; ?>
</label>
<?php $i++; endforeach; ?>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php }
/* Image radio input ("type" => "radio_image") */
public function display_radio_image($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<div class="label">
<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
</div>
<div class="settings-content">
<div class="cOf">
<?php
$i = 0;
if (get_option($value['id']))
$default = get_option($value['id']);
else
$default = $value['default'];
foreach ($value['options'] as $option):
$class = '';
$img_size = '';
$checked = '';
if ($value['image_size'][$i])
$img_size = 'width="' . $value['image_size'][$i] . '"';
else if ($value['image_size'][$i] == false && $value['image_size'][0] == true)
$img_size = 'width="' . $value['image_size'][0] . '"';
else
$img_size = 'width="120"';
if ($value['options'][$i] == $default) {
$checked = ' checked="checked"';
$class = ' acera-img-selected'; } ?>
<label class="acera-image-radio<?php echo $class; ?>" for="<?php echo $value['id'] . $i; ?>">
<img <?php echo $img_size; ?> src="<?php echo $value['image_src'][$i]; ?>" alt="<?php echo $box ?>" />
<input class="acera-image-radio-b" type="radio" id="<?php echo $value['id'] . $i; ?>" name="<?php echo $value['id']; ?>" value="<?php echo $value['options'][$i]; ?>" <?php echo $checked; ?> />
<?php if ($value['show_labels'] == "true"): ?><p><?php echo $option; ?></p><?php endif; ?>
</label>
<?php $i++; endforeach; ?>
</div>
<p class="description"><?php echo $value['desc']; ?></p>
</div>
</div>
<?php }
/* Displays small Heading in tabs */
public function display_small_heading($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else $rel = ""; ?>
<div<?php echo $rel; ?> class="separator2">
<h4><?php echo $value['title']; ?></h4>
</div>
<?php }
/* Hiding div start ("type" => "toggle_div_start" */
 public function display_toggle_div_start($value) {
$rel = "";
if ($value['display_checkbox_id'])
$rel = " rel=".$value['display_checkbox_id'];
else
$rel = ""; ?>
<div<?php echo $rel; ?>>
<?php }
/* Hiding div end ("type" => "toggle_div_end" */        
 public function display_toggle_div_end() { ?>
</div>                    
<?php }
public function save_options() {
if (isset($_POST['action']) && $_POST['action'] == "acera_save_options") {
foreach ($this->options as $value) {
$the_type = $value['type'];
if ($the_type == "heading" || $the_type == "section" || $the_type == "small_heading")
continue;
else if ($the_type != "checkbox" && $the_type != "checkbox_image") {
update_option($value['id'], $_POST[$value['id']]);
} else if ($the_type == "checkbox" || $the_type == "checkbox_image") {
$i = 0;
foreach ($value['options'] as $box) {
$curr_id = $value['id'][$i];
if (isset($_POST[$curr_id]))
update_option($curr_id, 'true');
else
update_option($curr_id, 'false');
$i++;
} } } } } } } ?>