<?php
if (function_exists("add_theme_support")) {
    add_theme_support("post-thumbnails");
    add_image_size('home', 120, 170, true);
}
add_filter('show_admin_bar', '__return_false'); // Admin bar deshabilitado
// Habilitar Traduccion
load_theme_textdomain('mundothemes', get_template_directory() . '/languages');
$locale = get_locale();
$locale_file = get_template_directory() . "/languages/$locale.php";
if (is_readable($locale_file)) require_once ($locale_file);
// Registrar Menu
function register_my_menu() {
    register_nav_menu('menu1', __('Menu home', 'mundothemes')); // Menu de la cabezera.
    register_nav_menu('menusidebar', __('Menu sidebar left', 'mundothemes')); // Menu del Sidebar.
    //register_nav_menu('menu3',__( 'Menu Footer', 'mundothemes' )); # Menu del pie de pagina.

}
add_action('init', 'register_my_menu'); // Registrar menu al theme.
// Registro traxonomico clave.
$escritor = get_option('escritor');
$year_estreno = get_option('year');
$calidad = get_option('calidad');
$director = get_option('director');
$actor = get_option('actor');
define('EDD_SL_STORE_URL', 'https://mundothemes.com');
define('EDD_SL_THEME_NAME', 'WPMovies');
if (!class_exists('EDD_SL_Theme_Updater')) {
    include (dirname(__FILE__) . '/mundothemes.php');
}
function edd_sl_sample_theme_updater() {
    $test_license = trim(get_option('edd_sample_theme_license_key'));
    $edd_updater = new EDD_SL_Theme_Updater(array('remote_api_url' => EDD_SL_STORE_URL, 'version' => '1.0.2', 'license' => $test_license, 'item_name' => EDD_SL_THEME_NAME, 'author' => 'Mundothemes'));
}
add_action('admin_init', 'edd_sl_sample_theme_updater');
function edd_sample_theme_license_menu() {
    add_menu_page('Mundothemes License', 'mundothemes', 'manage_options', 'mundothemes', 'edd_sample_theme_license_page', 'dashicons-admin-network');
}
add_action('admin_menu', 'edd_sample_theme_license_menu');
function edd_sample_theme_license_page() {
    $license = get_option('edd_sample_theme_license_key');
    $status = get_option('edd_sample_theme_license_key_status');
?>
<div id="acera-content" class="wrap">
<div class="acera-settings-headline">
<h2><?php
    _e('Mundothemes License', 'mundothemes'); ?></h2>
<a href="https://mundothemes.com/" target="_blank">
<img class="mundothemes" src="<?php
    echo get_stylesheet_directory_uri() . "/includes/framework/"; ?>images/logo.png">
</a>
</div>
<form method="post" action="options.php">
<?php
    settings_fields('edd_sample_theme_license'); ?>
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row" valign="top">
<?php
    _e('License Key', 'mundothemes'); ?>
</th>
<td>
<input id="edd_sample_theme_license_key"  name="edd_sample_theme_license_key" type="text" class="regular-text mundotxt" value="<?php
    echo esc_attr($license); ?>" />
<label class="description" for="edd_sample_theme_license_key"><?php
    _e('Enter your license key', 'mundothemes'); ?></label>
</td>
</tr>
<?php
    if (false !== $license) { ?>
<tr valign="top">
<th scope="row" valign="top"><?php
        _e('Activate License', 'mundothemes'); ?></th>
<td>
<?php
        if ($status !== false && $status == 'valid') { ?>
<span class="mundo"><span class="dashicons dashicons-admin-network"></span> <?php
            _e('active', 'mundothemes'); ?><br></span>
<i class="cmsxx"><?php
            echo $_SERVER['HTTP_HOST']; ?></i>
<?php
            wp_nonce_field('edd_sample_nonce', 'edd_sample_nonce'); ?>
<input type="submit" class="button-secondary mundobt" name="edd_theme_license_deactivate" value="<?php
            _e('Deactivate License', 'mundothemes'); ?>"/>
<?php
        }
        else {
            wp_nonce_field('edd_sample_nonce', 'edd_sample_nonce'); ?>
<span class="error"><span class="dashicons dashicons-admin-generic"></span> <?php
            _e('Activate License', 'mundothemes'); ?></span>
<i class="cmsxx"><?php
            echo $_SERVER['HTTP_HOST']; ?></i>
<input type="submit" class="button-secondary mundobt" name="edd_theme_license_activate" value="<?php
            _e('Activate License', 'mundothemes'); ?>"/>
<?php
        } ?>
</td>
</tr>
<?php
    } ?>
</tbody>
</table>
<?php
    submit_button(); ?>
</form>
<?php
}
function edd_sample_theme_register_option() {
    register_setting('edd_sample_theme_license', 'edd_sample_theme_license_key', 'edd_theme_sanitize_license');
}
add_action('admin_init', 'edd_sample_theme_register_option');
function edd_theme_sanitize_license($new) {
    $old = get_option('edd_sample_theme_license_key');
    if ($old && $old != $new) {
        delete_option('edd_sample_theme_license_key_status');
    }
    return $new;
}
function edd_sample_theme_activate_license() {
    if (isset($_POST['edd_theme_license_activate'])) {
        if (!check_admin_referer('edd_sample_nonce', 'edd_sample_nonce')) return;
        global $wp_version;
        $license = trim(get_option('edd_sample_theme_license_key'));
        $api_params = array('edd_action' => 'activate_license', 'license' => $license, 'item_name' => urlencode(EDD_SL_THEME_NAME));
        $response = wp_remote_get(add_query_arg($api_params, EDD_SL_STORE_URL), array('timeout' => 15, 'sslverify' => false));

        if (is_wp_error($response)) return false;
        $license_data = json_decode(wp_remote_retrieve_body($response));
        update_option('edd_sample_theme_license_key_status', $license_data->license);
    }
}
add_action('admin_init', 'edd_sample_theme_activate_license');
function edd_sample_theme_deactivate_license() {
    if (isset($_POST['edd_theme_license_deactivate'])) {
        if (!check_admin_referer('edd_sample_nonce', 'edd_sample_nonce')) return;
        $license = trim(get_option('edd_sample_theme_license_key'));
        $api_params = array('edd_action' => 'deactivate_license', 'license' => $license, 'item_name' => urlencode(EDD_SL_THEME_NAME)
         // the name of our product in EDD
        );
        $response = wp_remote_get(add_query_arg($api_params, EDD_SL_STORE_URL), array('timeout' => 15, 'sslverify' => false));
        if (is_wp_error($response)) return false;
        $license_data = json_decode(wp_remote_retrieve_body($response));
        if ($license_data->license == 'deactivated') delete_option('edd_sample_theme_license_key_status');
    }
}
add_action('admin_init', 'edd_sample_theme_deactivate_license');
// Registrar incluciones.
include_once 'includes/framework/options-init.php';
include_once 'includes/funciones/taxonomias.php';
include_once 'includes/funciones/funciones.php';
include_once 'includes/funciones/custom_fields.php';
include_once 'includes/funciones/metadatos.php';
function cg_content($more_link_text = '(more...)', $stripteaser = 0, $more_file = '', $cut = 0, $encode_html = 0) {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = strip_shortcodes(apply_filters('the_content_rss', $content));
    if ($cut && !$encode_html) $encode_html = 2;
    if (1 == $encode_html) {
        $content = wp_specialchars($content);
        $cut = 0;
    }
    elseif (0 == $encode_html) {
        $content = make_url_footnote($content);
    }
    elseif (2 == $encode_html) {
        $content = strip_tags($content);
    }
    if ($cut) {
        $blah = explode(' ', $content);
        if (count($blah) > $cut) {
            $k = $cut;
            $use_dotdotdot = 1;
        }
        else {
            $k = count($blah);
            $use_dotdotdot = 0;
        }
        for ($i = 0; $i < $k; $i++) $excerpt.= $blah[$i] . ' ';
        $excerpt.= ($use_dotdotdot) ? '' : '';
        $content = $excerpt;
    }
    $content = str_replace(']]>', ']]&gt;', $content);
    echo $content;
}
function tvshows_taxonomy() {
    register_taxonomy('tvshows_categories', array('tvshows',), array('show_admin_column' => true, 'hierarchical' => true, 'rewrite' => array('slug' => get_option('tvshows-category')),));
}
add_action('init', 'tvshows_taxonomy', 0);
function theme_prefix_rewrite_flush2() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'theme_prefix_rewrite_flush2');
// Registrar TVShows Post_type
function tvshows() {
    $labels = array('name' => _x('TVShows', 'Post Type General Name', 'mundothemes'), 'singular_name' => _x('TVShows', 'Post Type Singular Name', 'mundothemes'), 'menu_name' => __('TVShows', 'mundothemes'), 'add_new_item' => __('Add TVShow', 'mundothemes'),);
    $rewrite = array('slug' => get_option('tvshows'), 'with_front' => true, 'pages' => true, 'feeds' => true,);
    $args = array('label' => __('tvshows', 'mundothemes'), 'description' => __('Post Type Description', 'mundothemes'), 'labels' => $labels, 'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'), 'taxonomies' => array('tvshows_categories', get_option('year'), get_option('actor'), get_option('director'),), 'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true, 'show_in_admin_bar' => true, 'menu_position' => 5, 'menu_icon' => 'dashicons-welcome-view-site', 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'rewrite' => $rewrite, 'capability_type' => 'page',);
    register_post_type('tvshows', $args);
}

// Hook into the 'init' action
add_action('init', 'tvshows', 0);
// Registrar TVShows Post_type
function episodios() {
    $labels = array('name' => _x('Episodes', 'Post Type General Name', 'mundothemes'), 'singular_name' => _x('Episodes', 'Post Type Singular Name', 'mundothemes'), 'menu_name' => __('Episodes', 'mundothemes'), 'add_new_item' => __('Add Episode', 'mundothemes'),);
    $rewrite = array('slug' => get_option('episode'), 'with_front' => true, 'pages' => true, 'feeds' => true,);
    $args = array('label' => __('episodes', 'mundothemes'), 'description' => __('Post Type Description', 'mundothemes'), 'labels' => $labels, 'supports' => array('title', 'editor', 'thumbnail', 'comments', 'custom-fields'), 'taxonomies' => array(), 'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true, 'show_in_admin_bar' => true, 'menu_position' => 5, 'menu_icon' => 'dashicons-controls-play', 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'rewrite' => $rewrite, 'capability_type' => 'page',);
    register_post_type('episodios', $args);
}

// Hook into the 'init' action
add_action('init', 'episodios', 0);
//## Noticias
// Registrar taxonomia
function news_taxonomy() {
    register_taxonomy('news_categories', array('news',), array('show_admin_column' => true, 'hierarchical' => true, 'rewrite' => array('slug' => get_option('news-category')),));
}
add_action('init', 'news_taxonomy', 0);
function theme_prefix_rewrite_flush() {
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'theme_prefix_rewrite_flush');
// Registrar Noticias Post_type
function noticias() {
    $labels = array('name' => _x('News', 'Post Type General Name', 'mundothemes'), 'singular_name' => _x('News', 'Post Type Singular Name', 'mundothemes'), 'menu_name' => __('News', 'mundothemes'), 'add_new_item' => __('Add News', 'mundothemes'),);
    $rewrite = array('slug' => get_option('news'), 'with_front' => true, 'pages' => true, 'feeds' => true,);
    $args = array('label' => __('news', 'mundothemes'), 'description' => __('Post Type Description', 'mundothemes'), 'labels' => $labels, 'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'trackbacks', 'revisions', 'custom-fields', 'page-attributes', 'post-formats'), 'taxonomies' => array('news_categories', 'post_tag'), 'hierarchical' => false, 'public' => true, 'show_ui' => true, 'show_in_menu' => true, 'show_in_nav_menus' => true, 'show_in_admin_bar' => true, 'menu_position' => 5, 'menu_icon' => 'dashicons-admin-site', 'can_export' => true, 'has_archive' => true, 'exclude_from_search' => false, 'publicly_queryable' => true, 'rewrite' => $rewrite, 'capability_type' => 'page',);
    register_post_type('noticias', $args);
}

// Hook into the 'init' action
add_action('init', 'noticias', 0);
// Hook Labels
function change_post_menu_label() {
    global $menu;
    global $submenu;
    $menu[5][0] = __('Movies', 'mundothemes');
    $submenu['edit.php'][5][0] = __('All Movies', 'mundothemes');
    $submenu['edit.php'][10][0] = __('Add Movie', 'mundothemes');
    echo '';
}
function change_post_object_label() {
    global $wp_post_types;
    $labels = & $wp_post_types['post']->labels;
    $labels->name = __('Movies', 'mundothemes');
    $labels->singular_name = __('Movie', 'mundothemes');
    $labels->add_new = __('Add Movie', 'mundothemes');
    $labels->add_new_item = __('Add New movie', 'mundothemes');
    $labels->edit_item = __('Edit Movie', 'mundothemes');
    $labels->new_item = __('Movie', 'mundothemes');
}
add_action('init', 'change_post_object_label');
add_action('admin_menu', 'change_post_menu_label');
function replace_admin_menu_icons_css() { ?>
<style>
.dashicons-admin-post:before,.dashicons-format-standard:before{content:"\f219"}span.mundo{color:green;width:70%;float:left;margin-bottom:5px;font-size:17px;padding:16px
15%;background:#C4E4C4;text-align:center}span.error{color:#DB5252;width:70%;float:left;margin-bottom:5px;font-size:17px;padding:16px
15%;background:#E4C4C4;text-align:center}i.cmsxx{float:left;width:100%;font-style:normal;font-size:12px;margin-bottom:20px;text-align:right;color:#C0C0C0}.mundobt{width:100%}.mundotxt{width:100%!important;padding:5%;font-size:28px;color:#2EA2CC!important}
</style>
<?php
}
add_action('admin_head', 'replace_admin_menu_icons_css');
function wpshed_get_custom_field($value) {
    global $post;
    $custom_field = get_post_meta($post->ID, $value, true);
    if (!empty($custom_field)) return is_array($custom_field) ? stripslashes_deep($custom_field) : stripslashes(wp_kses_decode_entities($custom_field));
    return false;
}
function wpshed_add_custom_meta_box() {
    add_meta_box('wpshed-meta-box', __('Serie Data', 'mundothemes'), 'wpshed_meta_box_output', 'episodios', 'normal', 'high');
}
add_action('add_meta_boxes', 'wpshed_add_custom_meta_box');
function wpshed_meta_box_output($post) {
    wp_nonce_field('my_wpshed_meta_box_nonce', 'wpshed_meta_box_nonce');
    include_once 'includes/funciones/mas.php';
}
function wpshed_meta_box_save($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!isset($_POST['wpshed_meta_box_nonce']) || !wp_verify_nonce($_POST['wpshed_meta_box_nonce'], 'my_wpshed_meta_box_nonce')) return;
    if (!current_user_can('edit_post', get_the_id())) return;
    if (isset($_POST['titulo_serie'])) update_post_meta($post_id, 'titulo_serie', esc_attr($_POST['titulo_serie']));
    if (isset($_POST['url_serie'])) update_post_meta($post_id, 'url_serie', esc_attr($_POST['url_serie']));
    if (isset($_POST['fecha_serie'])) update_post_meta($post_id, 'fecha_serie', esc_attr($_POST['fecha_serie']));
    if (isset($_POST['temporada_serie'])) update_post_meta($post_id, 'temporada_serie', esc_attr($_POST['temporada_serie']));
    if (isset($_POST['episodio_serie'])) update_post_meta($post_id, 'episodio_serie', esc_attr($_POST['episodio_serie']));
    if (isset($_POST['cover_url'])) update_post_meta($post_id, 'cover_url', esc_attr($_POST['cover_url']));
}
add_action('save_post', 'wpshed_meta_box_save');
// Totales
function total_peliculas() {
    $s = '';
    $totalj = wp_count_posts('post')->publish;
    if ($totalj != 1) {
        $s = 's';
    }
    return sprintf(__("%s", "mundothemes"), $totalj, $s);
}

function total_series() {
    $s = '';
    $totalj = wp_count_posts('tvshows')->publish;
    if ($totalj != 1) {
        $s = 's';
    }
    return sprintf(__("%s", "mundothemes"), $totalj, $s);
}
function total_episodios() {
    $s = '';
    $totalj = wp_count_posts('episodios')->publish;
    if ($totalj != 1) {
        $s = 's';
    }
    return sprintf(__("%s", "mundothemes"), $totalj, $s);
}

// function remove_post_custom_fields() {
//   remove_meta_box( 'postcustom' , 'episodios' , 'normal' );
// }
// add_action( 'admin_menu' , 'remove_post_custom_fields' );



/**
 * Replace the default "_" (underscore) with "-" (hyphen) in protected custom fields for debugging purposes
 *
 * @param bool $protected The default value
 * @param string $meta_key The meta key
 * @return bool True for meta keys starting with "-" (hyphen), false otherwise
 */
function unprotected_meta($protected, $meta_key) {

    $protected = ('-' == $meta_key[0]);

    return $protected;
}
add_filter('is_protected_meta', 'unprotected_meta', 10, 2);

// fixed seo
add_action('admin_init', function () {
    global $wp_filter, $yoast_woo_seo;

    if (!empty($wp_filter['admin_notices'][10])) {
        foreach ($wp_filter['admin_notices'][10] as $hook_key => $hook) {
            if (is_array($hook['function']) && $hook['function'][0] instanceof \Yoast_Plugin_License_Manager && $hook['function'][1] == 'display_admin_notices') {
                unset($wp_filter['admin_notices'][10][$hook_key]);
            }
        }
    }
});

// remove_filter('wp_title', array($wpseo_front, 'title'), 10, 3);
