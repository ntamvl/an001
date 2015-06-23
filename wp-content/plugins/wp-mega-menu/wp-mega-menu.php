<?php
/*
 * Plugin Name: WP Mega Menu
 * Plugin URI: http://mythemeshop.com/
 * Description: WP Mega Menu is an easy to use plugin for creating beautiful, customized menus for your blog that show categories, subcategories and posts.
 * Version: 1.1.2
 * Author: MyThemeShop
 * Author URI: http://mythemeshop.com/
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @since     1.0
 * @copyright Copyright (c) 2014, MyThemeShop
 * @author    MyThemeShop
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

define('WPMM_PLUGIN_VERSION', '1.1.2');

function wpmm_init() {
	load_plugin_textdomain('wpmm', false, dirname(plugin_basename(__FILE__)) . '/languages/' );
    add_image_size( 'wpmm_thumb', 345, 250, true ); // thumb
}
add_action('init', 'wpmm_init');

function wpmm_nav_menu_args( $args ) {	
	if ( ! apply_filters( 'wpmm_exclude_menu', false, $args ))
		$args['walker'] = new wpmm_menu_walker;

	return $args;
}
add_filter( 'wp_nav_menu_args', 'wpmm_nav_menu_args', 20 );

function wpmm_list_custom_fields() {
	return array(
		'color',
		'icon',
		'ismegamenu',
		'mmcolorscheme',
		'mmlinkcolor',
		'mmfontcolor',
		'mmbgcolor',
		'mmbgimage',
		'mmbgrepeat',
		'mmbgposition',
		'mmshow',
		'mmauthors',
		'mmthumbnails',
		'mmdates',
		'mmexcerpts',
		'mmsubcategories',
		'mmpagination',
		'mmanimationin',
		'mmanimationout',
	);
}

/**
 * Add custom fields to $item nav object
 * in order to be used in custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function wpmm_add_custom_nav_fields( $menu_item ) {
	$custom_fields = wpmm_list_custom_fields();
	foreach ($custom_fields as $field) {
		$menu_item->$field = get_post_meta( $menu_item->ID, '_menu_item_'.$field, true );
	}
    return $menu_item;
}
// add custom menu fields to menu
add_filter( 'wp_setup_nav_menu_item', 'wpmm_add_custom_nav_fields' );

/**
 * Save menu custom fields
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function wpmm_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
	$custom_fields = wpmm_list_custom_fields();
	foreach ($custom_fields as $field) {
		if (isset($_REQUEST['menu-item-'.$field][$menu_item_db_id]))
        	$value = $_REQUEST['menu-item-'.$field][$menu_item_db_id];
        else 
        	$value = '';

        update_post_meta( $menu_item_db_id, '_menu_item_'.$field, $value );
	}
}
// save menu custom fields
add_action( 'wp_update_nav_menu_item','wpmm_update_custom_nav_fields', 10, 3 );

/**
 * Define new Walker edit
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function wpmm_edit_walker($walker,$menu_id) {
    return 'wpmm_Walker_Nav_Menu_Edit';
}
// edit menu walker
add_filter( 'wp_edit_nav_menu_walker', 'wpmm_edit_walker', 99, 2 );

/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class wpmm_Walker_Nav_Menu_Edit extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth = 0, $args = array()) {	
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = array()) {
	}
	
    public $mts_icons = array(
            'Web Application Icons' => array(
				'adjust', 'anchor', 'archive', 'arrows', 'arrows-h', 'arrows-v', 'asterisk', 'ban', 
				'bar-chart-o', 'barcode', 'bars', 'beer', 'bell', 'bell-o', 'bolt', 'bomb', 'book', 
				'bookmark', 'bookmark-o', 'briefcase', 'bug', 'building', 'building-o', 'bullhorn', 
				'bullseye', 'calendar', 'calendar-o', 'camera', 'camera-retro', 'car', 'caret-square-o-down', 
				'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'certificate', 'check', 
				'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'child', 'circle', 
				'circle-o', 'circle-o-notch', 'circle-thin', 'clock-o', 'cloud', 'cloud-download', 
				'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 
				'comments', 'comments-o', 'compass', 'credit-card', 'crop', 'crosshairs', 'cube', 'cubes', 
				'cutlery', 'database', 'desktop', 'dot-circle-o', 'download', 'ellipsis-h', 'ellipsis-v', 
				'envelope', 'envelope-o', 'envelope-square', 'eraser', 'exchange', 'exclamation', 
				'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 
				'eye-slash', 'fax', 'female', 'fighter-jet', 'file-archive-o', 'file-audio-o', 'file-code-o', 
				'file-excel-o', 'file-image-o', 'file-pdf-o', 'file-powerpoint-o', 'file-video-o', 'file-word-o', 
				'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flask', 
				'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'gamepad', 'gavel', 'gift', 
				'glass', 'globe', 'graduation-cap', 'hdd-o', 'headphones', 'heart', 'heart-o', 'history', 
				'home', 'inbox', 'info', 'info-circle', 'key', 'keyboard-o', 'language', 'laptop', 
				'leaf', 'lemon-o', 'level-down', 'level-up', 'life-ring', 'lightbulb-o', 'location-arrow', 
				'lock', 'magic', 'magnet', 'male', 'map-marker', 'meh-o', 'microphone', 'microphone-slash', 
				'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'money', 'moon-o', 
				'music', 'paper-plane', 'paper-plane-o', 'paw', 'pencil', 'pencil-square', 'pencil-square-o', 
				'phone', 'phone-square', 'picture-o', 'plane', 'plus', 'plus-circle', 'plus-square', 
				'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 
				'quote-left', 'quote-right', 'random', 'recycle', 'refresh', 'reply', 'reply-all', 'retweet', 
				'road', 'rocket', 'rss', 'rss-square', 'search', 'search-minus', 'search-plus', 'share', 
				'share-alt', 'share-alt-square', 'share-square', 'share-square-o', 'shield', 'shopping-cart', 
				'sign-in', 'sign-out', 'signal', 'sitemap', 'sliders', 'smile-o', 'sort', 'sort-alpha-asc', 
				'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 
				'sort-numeric-asc', 'sort-numeric-desc', 'space-shuttle', 'spinner', 'spoon', 'square', 
				'square-o', 'star', 'star-half', 'star-half-o', 'star-o', 'suitcase', 'sun-o', 'tablet', 
				'tachometer', 'tag', 'tags', 'tasks', 'taxi', 'terminal', 'thumb-tack', 'thumbs-down', 
				'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 
				'times-circle-o', 'tint', 'trash-o', 'tree', 'trophy', 'truck', 'umbrella', 'university', 
				'unlock', 'unlock-alt', 'upload', 'user', 'users', 'video-camera', 'volume-down', 
				'volume-off', 'volume-up', 'wheelchair', 'wrench' 
			),
            'File Type Icons' => array(
				'file', 'file-archive-o', 'file-audio-o', 'file-code-o', 'file-excel-o', 'file-image-o', 
				'file-o', 'file-pdf-o', 'file-powerpoint-o', 'file-text', 'file-text-o', 'file-video-o', 
				'file-word-o' 
			),
            'Spinner Icons' => array(
				'circle-o-notch', 'cog', 'refresh', 'spinner', 
			),
            'Form Control Icons' => array(
				'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 
				'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o'
			),
            'Currency Icons' => array(
				'btc', 'eur', 'gbp', 'inr', 'jpy', 'krw', 'money', 'rub', 'try', 'usd', 
			),
            'Text Editor Icons' => array(
				'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain-broken', 
				'clipboard', 'columns', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 
				'files-o', 'floppy-o', 'font', 'header', 'indent', 'italic', 'link', 'list', 
				'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'paragraph', 'repeat', 
				'scissors', 'strikethrough', 'subscript', 'superscript', 'table', 'text-height', 
				'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo', 
			),
            'Directional Icons' => array(
				'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 
				'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 
				'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 
				'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 
				'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 
				'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 
				'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 
				'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 
				'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 
				'long-arrow-right', 'long-arrow-up', 
			),
            'Video Player Icons' => array(
				'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 
				'forward', 'pause', 'play', 'play-circle', 'play-circle-o', 'step-backward', 'step-forward', 
				'stop', 'youtube-play', 
			),
			'Brand Icons' => array(
				'adn', 'android', 'apple', 'behance', 'behance-square', 'bitbucket', 'bitbucket-square', 
				'btc', 'codepen', 'css3', 'delicious', 'deviantart', 'digg', 'dribbble', 'dropbox', 
				'drupal', 'empire', 'facebook', 'facebook-square', 'flickr', 'foursquare', 'git', 
				'git-square', 'github', 'github-alt', 'github-square', 'gittip', 'google', 'google-plus', 
				'google-plus-square', 'hacker-news', 'html5', 'instagram', 'joomla', 'jsfiddle', 'linkedin', 
				'linkedin-square', 'linux', 'maxcdn', 'openid', 'pagelines', 'pied-piper', 'pied-piper-alt', 
				'pinterest', 'pinterest-square', 'qq', 'rebel', 'reddit', 'reddit-square', 'renren', 
				'share-alt', 'share-alt-square', 'skype', 'slack', 'soundcloud', 'spotify', 'stack-exchange', 
				'stack-overflow', 'steam', 'steam-square', 'stumbleupon', 'stumbleupon-circle', 'tencent-weibo', 
				'trello', 'tumblr', 'tumblr-square', 'twitter', 'twitter-square', 'vimeo-square', 'vine', 
				'vk', 'weibo', 'weixin', 'windows', 'wordpress', 'xing', 'xing-square', 'yahoo', 
				'youtube', 'youtube-play', 'youtube-square', 
			),
			'Medical Icons' => array(
				'ambulance', 'h-square', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 
				'wheelchair'
			)
        );
    
	public $supported_taxonomies = array('category', 'product_cat');

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;

	    // Post Meta > Show Thumbnails - enabled by default
	    if (!isset($item->mmthumbnails) || $item->mmthumbnails !== '0') $item->mmthumbnails = '1';
	
	    ?>
	    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item','wpmm' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
	                        <?php _e( 'URL','wpmm' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Navigation Label','wpmm' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Title Attribute','wpmm' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab','wpmm' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
	                    <?php _e( 'CSS Classes (optional)','wpmm' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
	                    <?php _e( 'Link Relationship (XFN)','wpmm' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
	                    <?php _e( 'Description','wpmm' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.','wpmm'); ?></span>
	                </label>
	            </p>        
	            <?php
	            /* New fields insertion starts here */
	            ?>
                
                <p class="field-custom description description-thin">
	                <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
	                    <?php _e( 'Icon (optional)','wpmm' ); ?><br />
	                    <?php 
                        echo '<select id="edit-menu-item-icon-'.$item_id.'" name="menu-item-icon['.$item_id.']" style="width: 100%; max-width: 240px;">';
                		echo '<option value=""'.selected($item->icon, '', false).'>'.__('No Icon').'</option>';
                        foreach ( $this->mts_icons as $icon_category => $icons ) {
                            echo '<optgroup label="'.$icon_category.'">';
                            foreach ($icons as $icon) {
                                echo '<option value="'.$icon.'"'.selected($item->icon, $icon, false).'>'.ucwords(str_replace('-', ' ', $icon)).'</option>';
                            }
                            echo '</optgroup>';
                		}
                
                		echo '</select>';
                        ?>
	                </label>
	            </p>
                
	            <p class="field-custom description description-thin">
	                <label for="edit-menu-item-color-<?php echo $item_id; ?>" style="position:relative;">
	                    <?php _e( 'Color (optional)','wpmm' ); ?><br />
	                    <input type="text" id="edit-menu-item-color-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom edit-menu-color" name="menu-item-color[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->color ); ?>" />
	                </label>
	            </p>
	            <?php 
				if ( 'taxonomy' == $item->type ) {//&& in_array($item->object, $this->supported_taxonomies) ) {
		            ?>
		            <p class="field-custom description widefat" style="height: 32px;">
		                <label for="edit-menu-item-ismegamenu-<?php echo $item_id; ?>" style="position:relative; top: 10px;">
		                    <input type="hidden" name="menu-item-ismegamenu[<?php echo $item_id; ?>]" value="0" />
		                    <input type="checkbox" id="edit-menu-item-ismegamenu-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-ismegamenu" name="menu-item-ismegamenu[<?php echo $item_id; ?>]" value="1" <?php checked( $item->ismegamenu, '1' ); ?> />
		                    <?php _e( 'Enable Mega Menu','wpmm' ); ?><br />
		                </label>
		            </p>
		            <div id="wpmm-megamenu-options-<?php echo $item_id; ?>" class="wpmm-megamenu-options" style="display: none;">
		            	<h3><?php _e('Mega Menu Options', 'wpmm'); ?></h3>
		            	<div>
				            <p class="field-custom description mmshow widefat">
				                <label for="edit-menu-item-mmshow-posts-<?php echo $item_id; ?>">
				                	<input type="radio" id="edit-menu-item-mmshow-posts-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmshow" name="menu-item-mmshow[<?php echo $item_id; ?>]" value="posts" <?php if ( empty($item->mmshow) || $item->mmshow == 'posts' ) echo 'checked="checked"'; ?><?php checked( $item->mmshow, 'posts' ); ?> />
				                	<?php _e( 'Show posts','wpmm' ); ?><br />
				                	<img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/posts.png" />
				                </label><br />
		            			<?php if (is_taxonomy_hierarchical($item->object)) { ?>
					                <label for="edit-menu-item-mmshow-both-<?php echo $item_id; ?>">
					                	<input type="radio" id="edit-menu-item-mmshow-both-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmshow" name="menu-item-mmshow[<?php echo $item_id; ?>]" value="both" <?php checked( $item->mmshow, 'both' ); ?> />
					                	<?php _e( 'Show posts &amp; subcategories','wpmm' ); ?><br />
					                	<img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/posts-subcategories.png" />
					                </label><br />
					                <label for="edit-menu-item-mmshow-subcats-<?php echo $item_id; ?>">
					                	<input type="radio" id="edit-menu-item-mmshow-subcats-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmshow" name="menu-item-mmshow[<?php echo $item_id; ?>]" value="subcategories" <?php checked( $item->mmshow, 'subcategories' ); ?> />
					                	<?php _e( 'Show subcategories','wpmm' ); ?><br />
					                	<img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/subcategories.png" />
					                </label>
				            	<?php } ?>
				            </p>
				            <p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmpagination-<?php echo $item_id; ?>">
				                    <input type="hidden" name="menu-item-mmpagination[<?php echo $item_id; ?>]" value="0" />
				                    <input type="checkbox" id="edit-menu-item-mmpagination-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmpagination" name="menu-item-mmpagination[<?php echo $item_id; ?>]" value="1" <?php checked( $item->mmpagination, '1' ); ?> />
				                    <?php _e( 'Pagination','wpmm' ); ?><br />
			                	</label>
				            </p>
			            </div>
			            
			            <h3 id="wpmm-postmetatab-<?php echo $item_id; ?>"<?php echo ($item->mmshow == 'subcategories' ? ' style="display: none;"' : '') ?>><?php _e('Post Meta', 'wpmm'); ?></h3>
		            	<div>
				            <p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmthumbnails-<?php echo $item_id; ?>">
				                    <input type="hidden" name="menu-item-mmthumbnails[<?php echo $item_id; ?>]" value="0" />
				                    <input type="checkbox" id="edit-menu-item-mmthumbnails-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmthumbnails" name="menu-item-mmthumbnails[<?php echo $item_id; ?>]" value="1" <?php checked( $item->mmthumbnails, '1' ); ?> />
				                    <?php _e( 'Show post thumbnails','wpmm' ); ?><br />
			                	</label>
				            </p>
				            <p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmauthors-<?php echo $item_id; ?>">
				                    <input type="hidden" name="menu-item-mmauthors[<?php echo $item_id; ?>]" value="0" />
				                    <input type="checkbox" id="edit-menu-item-mmauthors-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmauthors" name="menu-item-mmauthors[<?php echo $item_id; ?>]" value="1" <?php checked( $item->mmauthors, '1' ); ?> />
				                    <?php _e( 'Show post authors','wpmm' ); ?><br />
			                	</label>
				            </p>
				            <p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmdates-<?php echo $item_id; ?>">
				                    <input type="hidden" name="menu-item-mmdates[<?php echo $item_id; ?>]" value="0" />
				                    <input type="checkbox" id="edit-menu-item-mmdates-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmdates" name="menu-item-mmdates[<?php echo $item_id; ?>]" value="1" <?php checked( $item->mmdates, '1' ); ?> />
				                    <?php _e( 'Show post dates','wpmm' ); ?><br />
			                	</label>
				            </p>
				            <p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmexcerpts-<?php echo $item_id; ?>">
				                    <input type="hidden" name="menu-item-mmexcerpts[<?php echo $item_id; ?>]" value="0" />
				                    <input type="checkbox" id="edit-menu-item-mmexcerpts-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmexcerpts" name="menu-item-mmexcerpts[<?php echo $item_id; ?>]" value="1" <?php checked( $item->mmexcerpts, '1' ); ?> />
				                    <?php _e( 'Show post excerpts','wpmm' ); ?><br />
			                	</label>
				            </p>
		            	</div>

		            	<?php if (is_taxonomy_hierarchical($item->object)) { ?>
			            	<h3 id="wpmm-subcategoriestab-<?php echo $item_id; ?>"<?php echo (empty($item->mmshow) || $item->mmshow == 'posts' ? ' style="display: none;"' : ''); ?>><?php _e('Subcategories', 'wpmm'); ?></h3>
			            	<div>
					            <p><?php _e( 'Choose displayed subcategories','wpmm' ); ?></p>
					            <?php 
								$categories = get_categories( array('parent' => $item->object_id, 'taxonomy' => $item->object, 'hide_empty' => 0 ) );
								$subcats_default = false;
								if (empty($item->mmsubcategories)) $subcats_default = true;
								if (!empty($categories)) {
									foreach ($categories as $i => $cat) {
					            ?>
					            <p class="field-custom description widefat">
					            	<label for="edit-menu-item-mmsubcategory-<?php echo $cat->term_id; ?>-<?php echo $item_id; ?>">
					                    <input type="hidden" name="menu-item-mmsubcategories[<?php echo $item_id; ?>][<?php echo $cat->term_id; ?>]" value="0" />
					                    <input type="checkbox" id="edit-menu-item-mmsubcategory-<?php echo $cat->term_id; ?>-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmsubcategories" name="menu-item-mmsubcategories[<?php echo $item_id; ?>][<?php echo $cat->term_id; ?>]" value="1" <?php checked( $subcats_default || (!empty($item->mmsubcategories) && is_array($item->mmsubcategories) && !empty($item->mmsubcategories[$cat->term_id])) ); ?> />
					                    <?php echo $cat->name; ?><br />
				                	</label>
					            </p>
					            <?php 
					        		}
					        	}
					            ?>
			            	</div>
		            	<?php } ?>

			            <h3><?php _e('Mega Menu Colors &amp; Background', 'wpmm'); ?></h3>
		            	<div>
		            		<p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmcolorscheme-<?php echo $item_id; ?>">
				            		<?php _e( 'Color Scheme','wpmm' ); ?><br />
				                    <select name="menu-item-mmcolorscheme[<?php echo $item_id; ?>]" id="edit-menu-item-mmcolorscheme-<?php echo $item_id; ?>">
				                    	<?php 
			                    		$color_schemes = apply_filters( 'wpmm_color_schemes', array(
			                    			'wpmm-light-scheme' =>  __('Light', 'wpmm'), 
			                    			'wpmm-dark-scheme' =>  __('Dark', 'wpmm'))
			                    		);
			                    		foreach ($color_schemes as $class => $label) {
			                    			?>
											<option value="<?php echo $class; ?>"<?php selected( $item->mmcolorscheme, $class, 1 );?>>
					                    		<?php echo $label; ?>
					                    	</option>
			                    			<?php
			                    		}
				                    	?>
				                    	<option value="custom"<?php selected( $item->mmcolorscheme, "custom", 1 );?>>
				                    		<?php _e( 'Custom','wpmm' ); ?>
				                    	</option>
				                    </select>
				                    <br />
			                	</label>
				            </p>
				            <div class="wpmm-megamenu-colors"<?php echo ($item->mmcolorscheme == 'custom' ? '' : ' style="display: none;"') ?>>
					            <p class="field-custom description widefat">
					                <label for="edit-menu-item-mmfontcolor-<?php echo $item_id; ?>" style="position:relative;">
					                    <?php _e( 'Text Color (excerpt, post meta)','wpmm' ); ?><br />
					                    <input type="text" id="edit-menu-item-mmfontcolor-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom edit-menu-mmfontcolor" name="menu-item-mmfontcolor[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->mmfontcolor ); ?>" />
					                </label>
					            </p>
					            <p class="field-custom description widefat">
					                <label for="edit-menu-item-mmlinkcolor-<?php echo $item_id; ?>" style="position:relative;">
					                    <?php _e( 'Link Color','wpmm' ); ?><br />
					                    <input type="text" id="edit-menu-item-mmlinkcolor-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom edit-menu-mmlinkcolor" name="menu-item-mmlinkcolor[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->mmlinkcolor ); ?>" />
					                </label>
					            </p>
					            <p class="field-custom description widefat">
					                <label for="edit-menu-item-mmbgcolor-<?php echo $item_id; ?>" style="position:relative;">
					                    <?php _e( 'Background Color','wpmm' ); ?><br />
					                    <input type="text" id="edit-menu-item-mmbgcolor-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom edit-menu-mmbgcolor" name="menu-item-mmbgcolor[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->mmbgcolor ); ?>" />
					                </label>
					            </p>
				        	</div>
				            <p class="field-custom description widefat">
				            	<?php _e( 'Background Image','wpmm' ); ?><br />
				            	<?php 
				            	$src = plugins_url('images/blank.png', __FILE__);
				            	$class="wpmm-select-background";
				            	$label = __('Select Background', 'wpmm');
				            	if (!empty($item->mmbgimage)) {
				            		$src = $item->mmbgimage;
				            		$class="wpmm-remove-background";
				            		$label = __('Remove Background', 'wpmm');
				            	}

				            	?>
				            	<a href="#" class="<?php echo $class; ?> button button-secondary"><?php echo $label; ?></a>
				                <input type="hidden" name="menu-item-mmbgimage[<?php echo $item_id; ?>]" id="edit-menu-item-mmbgimage-<?php echo $item_id; ?>" value="<?php echo $item->mmbgimage; ?>">
				            	<br /><img src="<?php echo $src; ?>" class="wpam-mmbgimage-preview">
				            </p>
				            <div class="mmbgimagesettings"<?php echo empty($item->mmbgimage) ? ' style="display: none;"' : ''; ?>>
				            	<p class="field-custom description widefat">
					            	<label for="edit-menu-item-mmbgrepeat-<?php echo $item_id; ?>">
					                    <input type="hidden" name="menu-item-mmbgrepeat[<?php echo $item_id; ?>]" value="0" />
					                    <input type="checkbox" id="edit-menu-item-mmbgrepeat-<?php echo $item_id; ?>" class="edit-menu-item-custom edit-menu-mmbgrepeat" name="menu-item-mmbgrepeat[<?php echo $item_id; ?>]" value="1" <?php checked( $item->mmbgrepeat, '1' ); ?> />
					                    <?php _e( 'Repeat Background','wpmm' ); ?><br />
				                	</label>
					            </p>
					            <p class="field-custom description widefat">
					            	<label for="edit-menu-item-mmbgposition-<?php echo $item_id; ?>">
					            		<?php _e( 'Background Position','wpmm' ); ?><br />
					                    <select name="menu-item-mmbgposition[<?php echo $item_id; ?>]" id="edit-menu-item-mmbgposition-<?php echo $item_id; ?>">
					                    	<option value="top left"<?php selected( $item->mmbgposition, "top left", 1 );?>>
					                    		<?php _e( 'Top Left','wpmm' ); ?>
					                    	</option>
					                    	<option value="top center"<?php selected( $item->mmbgposition, "top center", 1 );?>>
					                    		<?php _e( 'Top Center','wpmm' ); ?>
					                    	</option>
					                    	<option value="top right"<?php selected( $item->mmbgposition, "top right", 1 );?>>
					                    		<?php _e( 'Top Right','wpmm' ); ?>
					                    	</option>

					                    	<option value="center left"<?php selected( $item->mmbgposition, "center left", 1 );?>>
					                    		<?php _e( 'Middle Left','wpmm' ); ?>
					                    	</option>
					                    	<option value="center center"<?php selected( $item->mmbgposition, "center center", 1 );?>>
					                    		<?php _e( 'Center','wpmm' ); ?>
					                    	</option>
					                    	<option value="center right"<?php selected( $item->mmbgposition, "center right", 1 );?>>
					                    		<?php _e( 'Middle Right','wpmm' ); ?>
					                    	</option>

					                    	<option value="bottom left"<?php selected( $item->mmbgposition, "bottom left", 1 );?>>
					                    		<?php _e( 'Bottom Left','wpmm' ); ?>
					                    	</option>
					                    	<option value="bottom center"<?php selected( $item->mmbgposition, "bottom center", 1 );?>>
					                    		<?php _e( 'Bottom Center','wpmm' ); ?>
					                    	</option>
					                    	<option value="bottom right"<?php selected( $item->mmbgposition, "bottom right", 1 );?>>
					                    		<?php _e( 'Bottom Right','wpmm' ); ?>
					                    	</option>
					                    </select>
					                    <br />
				                	</label>
					            </p>
				            </div>
			        	</div>

			        	<h3><?php _e('Mega Menu Animation', 'wpmm'); ?></h3>
		            	<div>
		            		<p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmanimationin-<?php echo $item_id; ?>">
				            		<?php _e( 'Animate In','wpmm' ); ?><br />
				                    <select name="menu-item-mmanimationin[<?php echo $item_id; ?>]" id="edit-menu-item-mmanimationin-<?php echo $item_id; ?>">
				                    	<option value=""<?php selected( $item->mmanimationin, "", 1 );?>>
				                    		<?php _e( 'None','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeIn"<?php selected( $item->mmanimationin, "fadeIn", 1 );?>>
				                    		<?php _e( 'Fade In','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeInSlideUp"<?php selected( $item->mmanimationin, "fadeInSlideUp", 1 );?>>
				                    		<?php _e( 'Fade in and slide up','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeInSlideDown"<?php selected( $item->mmanimationin, "fadeInSlideDown", 1 );?>>
				                    		<?php _e( 'Fade in and slide down','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeInSlideRight"<?php selected( $item->mmanimationin, "fadeInSlideRight", 1 );?>>
				                    		<?php _e( 'Fade in and slide right','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeInSlideLeft"<?php selected( $item->mmanimationin, "fadeInSlideLeft", 1 );?>>
				                    		<?php _e( 'Fade in and slide left','wpmm' ); ?>
				                    	</option>
				                    	<option value="expand"<?php selected( $item->mmanimationin, "expand", 1 );?>>
				                    		<?php _e( 'Expand from top','wpmm' ); ?>
				                    	</option>
				                    	<option value="expandHorizontalCenter"<?php selected( $item->mmanimationin, "expandHorizontalCenter", 1 );?>>
				                    		<?php _e( 'Expand horizontally from center','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeInZoomIn"<?php selected( $item->mmanimationin, "fadeInZoomIn", 1 );?>>
				                    		<?php _e( 'Zoom in','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeInZoomOut"<?php selected( $item->mmanimationin, "fadeInZoomOut", 1 );?>>
				                    		<?php _e( 'Zoom out','wpmm' ); ?>
				                    	</option>
				                    	<option value="foldOutFront"<?php selected( $item->mmanimationin, "foldOutFront", 1 );?>>
				                    		<?php _e( 'Fold out from front','wpmm' ); ?>
				                    	</option>
				                    	<option value="foldOutBack"<?php selected( $item->mmanimationout, "foldOutBack", 1 );?>>
				                    		<?php _e( 'Fold out from back','wpmm' ); ?>
				                    	</option>
				                    </select>
				                    <br />
			                	</label>
			                </p>
			                <p class="field-custom description widefat">
				            	<label for="edit-menu-item-mmanimationout-<?php echo $item_id; ?>">
				            		<?php _e( 'Animate Out','wpmm' ); ?><br />
				                    <select name="menu-item-mmanimationout[<?php echo $item_id; ?>]" id="edit-menu-item-mmanimationout-<?php echo $item_id; ?>">
<option value=""<?php selected( $item->mmanimationin, "", 1 );?>>
				                    		<?php _e( 'None','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOut"<?php selected( $item->mmanimationout, "fadeOut", 1 );?>>
				                    		<?php _e( 'Fade out','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOutSlideUp"<?php selected( $item->mmanimationout, "fadeOutSlideUp", 1 );?>>
				                    		<?php _e( 'Fade out and slide up','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOutSlideDown"<?php selected( $item->mmanimationout, "fadeOutSlideDown", 1 );?>>
				                    		<?php _e( 'Fade out and slide down','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOutSlideRight"<?php selected( $item->mmanimationout, "fadeOutSlideRight", 1 );?>>
				                    		<?php _e( 'Fade out and slide right','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOutSlideLeft"<?php selected( $item->mmanimationout, "fadeOutSlideLeft", 1 );?>>
				                    		<?php _e( 'Fade out and slide left','wpmm' ); ?>
				                    	</option>
				                    	<option value="collapse"<?php selected( $item->mmanimationout, "collapse", 1 );?>>
				                    		<?php _e( 'Collapse from bottom','wpmm' ); ?>
				                    	</option>
				                    	<option value="collapseHorizontalCenter"<?php selected( $item->mmanimationout, "collapseHorizontalCenter", 1 );?>>
				                    		<?php _e( 'Collapse horizontally to center','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOutZoomIn"<?php selected( $item->mmanimationout, "fadeOutZoomIn", 1 );?>>
				                    		<?php _e( 'Zoom in','wpmm' ); ?>
				                    	</option>
				                    	<option value="fadeOutZoomOut"<?php selected( $item->mmanimationout, "fadeOutZoomOut", 1 );?>>
				                    		<?php _e( 'Zoom out','wpmm' ); ?>
				                    	</option>
				                    	<option value="foldInFront"<?php selected( $item->mmanimationout, "foldInFront", 1 );?>>
				                    		<?php _e( 'Fold in to front','wpmm' ); ?>
				                    	</option>
				                    	<option value="foldInBack"<?php selected( $item->mmanimationout, "foldInBack", 1 );?>>
				                    		<?php _e( 'Fold in to back','wpmm' ); ?>
				                    	</option>
				                    </select>
				                    <br />
			                	</label>
			                </p>
		            	</div>
		            </div>
                <?php } ?>
	            <?php
	            /* New fields insertion ends here */
	            ?>
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove','wpmm'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel','wpmm'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#edit-menu-item-icon-<?php echo $item_id; ?>').select2({
                        formatResult: function(state) {
                            if (!state.id) return state.text; // optgroup
                            return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
                        },
                        formatSelection: function(state) {
                            if (!state.id) return state.text; // optgroup
                            return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
                        },
                        escapeMarkup: function(m) { return m; }
                    });
                    $('#edit-menu-item-color-<?php echo $item_id; ?>, #edit-menu-item-mmbgcolor-<?php echo $item_id; ?>, #edit-menu-item-mmfontcolor-<?php echo $item_id; ?>, #edit-menu-item-mmlinkcolor-<?php echo $item_id; ?>').wpColorPicker();
                    
                    <?php if ( 'taxonomy' == $item->type ) { ?>
                    var $megamenu_options = $('#wpmm-megamenu-options-<?php echo $item_id; ?>');
                    var $megamenu_checkbox = $('#edit-menu-item-ismegamenu-<?php echo $item_id; ?>');
					$megamenu_options.toggle($megamenu_checkbox.prop('checked'));
					if ($megamenu_checkbox.prop('checked'))
						$('#menu-item-<?php echo $item_id; ?> .item-type').text($('#menu-item-<?php echo $item_id; ?> .item-type').text() + ' Mega Menu');
					$megamenu_checkbox.change(function() { 
						$megamenu_options.slideToggle($megamenu_checkbox.prop('checked'));
						if ($megamenu_checkbox.prop('checked'))
							$('#menu-item-<?php echo $item_id; ?> .item-type').text($('#menu-item-<?php echo $item_id; ?> .item-type').text() + ' Mega Menu');
						else
							$('#menu-item-<?php echo $item_id; ?> .item-type').text($('#menu-item-<?php echo $item_id; ?> .item-type').text().replace(' Mega Menu', ''));
					});
					$('#wpmm-megamenu-options-<?php echo $item_id; ?>').accordion({collapsible: true, active: false, heightStyle: "content"});
                	// show/hide Post Meta tab
                	$('#menu-item-<?php echo $item_id; ?>').find('.mmshow input').change(function() {
                		if (this.value == 'subcategories') {
                			$('#wpmm-postmetatab-<?php echo $item_id; ?>').slideUp();
                		} else {
                			$('#wpmm-postmetatab-<?php echo $item_id; ?>').slideDown();

                		}
                	});
                	// show/hide Subcategories tab
                	$('#menu-item-<?php echo $item_id; ?>').find('.mmshow input').change(function() {
                		if (this.value == 'posts') {
                			$('#wpmm-subcategoriestab-<?php echo $item_id; ?>').slideUp();
                		} else {
                			$('#wpmm-subcategoriestab-<?php echo $item_id; ?>').slideDown();

                		}
                	});
                	// Show/hide color options
                	$('#edit-menu-item-mmcolorscheme-<?php echo $item_id; ?>').change(function() {
                		//console.log(this.value);
                		if (this.value == 'custom') {
                			$('#menu-item-<?php echo $item_id; ?> .wpmm-megamenu-colors').slideDown();
                		} else {
                			$('#menu-item-<?php echo $item_id; ?> .wpmm-megamenu-colors').slideUp();
                		}
                	});
                	<?php } ?>
                });
                </script>
                
            </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	    }
}

global $wpmm_megamenus;
$wpmm_megamenus = array();
/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
class wpmm_menu_walker extends Walker_Nav_Menu
{
	public $suppress_children = false;

	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query, $wpmm_megamenus;
		$class_stem = apply_filters( 'wpmm_class_stem', 'wpmm' );
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		if ($depth == 0) 
			$this->suppress_children = false;
		else if ($this->suppress_children)
			return;

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$class_names = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// <a> attributes
		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';

		if ( ! has_filter( 'wpmm_color_output')) { // apply color only if there is no color output set by the theme
			$atts['style']   = ! empty( $item->color ) ? 'color: '.$item->color.';'        : '';
		}

		// mega menu atts
		if ( $item->ismegamenu && $depth == 0 ) {
			if ($item->type == 'taxonomy') {
				$atts['data-menu_item'] = $megamenu_args['menu_item'] = $item->ID;
				$atts['data-object'] = $megamenu_args['object'] = $item->object;
				$atts['data-object_id'] = $megamenu_args['object_id'] = $item->object_id;
				$atts['data-show'] = $megamenu_args['show'] = (empty($item->mmshow) ? 'posts' : $item->mmshow);
				$atts['data-pagination'] = $megamenu_args['pagination'] = (empty($item->mmpagination) ? '0' : '1');
				$atts['data-excerpts'] = $megamenu_args['excerpts'] = (empty($item->mmexcerpts) ? '0' : '1');
				$cats_tmp = array();
				if (!empty($item->mmsubcategories)) {
					foreach ($item->mmsubcategories as $i => $cat) {
						if ($cat)
						$cats_tmp[] = $i;
					}
				}
				$atts['data-subcategories'] = $megamenu_args['subcategories'] = (empty($cats_tmp) ? '' : implode(',', $cats_tmp));
				$atts['data-authors'] = $megamenu_args['authors'] = (empty($item->mmauthors) ? '0' : '1');
				$atts['data-thumbnails'] = $megamenu_args['thumbnails'] = (empty($item->mmthumbnails) ? '0' : '1');
				$atts['data-dates'] = $megamenu_args['dates'] = (empty($item->mmdates) ? '0' : '1');

				if ($item->mmcolorscheme != 'custom')
					$atts['data-colorscheme'] = $item->mmcolorscheme;
				else 
					$atts['data-colorscheme'] = 'wpmm-custom-colors';

				$classes[] = $atts['data-colorscheme'];
				$classes[]= 'menu-item-'.$class_stem.'-megamenu';
				$classes[]= 'menu-item-'.$class_stem.'-taxonomy';
				$classes[]= $class_stem.'-'.$item->object;
				$classes = array_diff($classes, array('menu-item-has-children')); // remove this one
				$this->suppress_children = true;

				$wpmm_megamenus[$item->ID] = array('type' => 'taxonomy', 'args' => $megamenu_args);
			//} elseif ($item->type == 'custom') {
				// widgetized mega menu
			}
		}
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			//if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			//}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= $indent . '<li ' . $id  . $class_names .'>';

		$prepend = '';
		$append = '';
		$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

		if($depth != 0) {
		   $description = $append = $prepend = "";
		}

        $item_output = @$args->before;
        $item_output .= '<a'. $attributes;
        $item_output .= '>';

        // Icon
        if (!empty($item->icon)) {
            $item_output .= '<i class="fa fa-'.$item->icon.' '.$class_stem.'-menu-icon"></i> ';
        }
		
        $item_output .= @$args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
        $item_output .= @$args->link_after;
        //  angle icon >
        if ($item->ismegamenu && $depth == 0 && $item->type == 'taxonomy') {
            $item_output .= ' <i class="fa fa-caret-down '.$class_stem.'-megamenu-indicator"></i> ';
        }
        $item_output .= '</a>';
        $item_output .= @$args->after;
        
        $item_css = '<style type="text/css">'."\n";
		if ($item->ismegamenu && $depth == 0) {
			if ($item->mmcolorscheme == 'custom') {
				if (!empty($item->mmfontcolor)){
					$item_css .= '#wpmm-megamenu.menu-item-'.$item->ID.'-megamenu p, #wpmm-megamenu.menu-item-'.$item->ID.'-megamenu span, .menu-item-'.$item->ID.'.wpmm-megamenu-showing a, #wpmm-megamenu.menu-item-'.$item->ID.'-megamenu .wpmm-entry-author a, #wpmm-megamenu.menu-item-'.$item->ID.'-megamenu .wpmm-post i, #wpmm-megamenu.menu-item-'.$item->ID.'-megamenu .wpmm-subcategories a, #wpmm-megamenu div a:hover { ';
					$item_css .= 'color: '.$item->mmfontcolor.'!important; ';
					$item_css .= " } \n";
				}
				if (!empty($item->mmlinkcolor)){
					$item_css .= '#wpmm-megamenu.menu-item-'.$item->ID.'-megamenu a { ';
					$item_css .= 'color: '.$item->mmlinkcolor.'; ';
					$item_css .= " } \n";
				}

				$item_css .= '#wpmm-megamenu.menu-item-'.$item->ID.'-megamenu, .menu-item-'.$item->ID.'.wpmm-megamenu-showing { ';
				if (!empty($item->mmbgcolor)){
					$item_css .= 'background-color: '.$item->mmbgcolor.'!important; ';
				}
			} else {
				$item_css .= '#wpmm-megamenu.menu-item-'.$item->ID.'-megamenu { ';
			}
			if (!empty($item->mmbgimage)){
				$item_css .= 'background-image: url('.$item->mmbgimage.'); ';
				$item_css .= 'background-repeat: '.($item->mmbgrepeat ? 'repeat' : 'no-repeat').'; ';
				$item_css .= 'background-position: '.$item->mmbgposition.'; ';
			}
			$item_css .= " } \n";

			if (!empty($item->mmanimationin)) {
				$item_css .= '.menu-item-'.$item->ID.'-megamenu.wpmm-visible { ';
				$item_css .= '-webkit-animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationin.'; ';
				$item_css .= '-moz-animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationin.'; ';
				$item_css .= '-o-animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationin.'; ';
				$item_css .= 'animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationin.'; ';
				$item_css .= " } \n";
			}
			if (!empty($item->mmanimationout)) {
				$item_css .= '.menu-item-'.$item->ID.'-megamenu.wpmm-hidden { ';
				$item_css .= '-webkit-animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationout.'; ';
				$item_css .= '-moz-animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationout.'; ';
				$item_css .= '-o-animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationout.'; ';
				$item_css .= 'animation: 0.5s ease 0s normal none 1 wpmm_'.$item->mmanimationout.'; ';
				$item_css .= " } \n";
			} else { // no "out" animation -- fix the "visibility: hidden" issue in Chrome
				$item_css .= '.menu-item-'.$item->ID.'-megamenu.wpmm-hidden { ';
				$item_css .= 'display: none;';
				$item_css .= " } \n";
			}
		}
        $item_css .= '</style>';
        $item_output .= $item_css;

        if (has_filter( 'wpmm_color_output')) {
        	$item_output = apply_filters( 'wpmm_color_output', $item_output, $item->color, $item, $depth, $args );
        }

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}

    function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		if ( ! $this->suppress_children ) // if it's a mega menu, we don't need the subitems
			$output .= "\n$indent<ul class=\"sub-menu\">\n";
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		if ( ! $this->suppress_children )
			$output .= "$indent</ul>\n";
	}
}

// enqueue admin scripts
add_action('admin_enqueue_scripts', 'wpmm_menu_admin_scripts');
function wpmm_menu_admin_scripts($hook) {
    if ($hook != 'nav-menus.php')
        return;
    wp_enqueue_style( 'wp-color-picker' ); 
    wp_enqueue_script('wp-color-picker');

    wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
	wp_enqueue_script( 'media-upload');

	wp_enqueue_script( 'jquery-ui-core'); 
	wp_enqueue_script( 'jquery-ui-accordion'); 
	// jQuery UI CSS from CDN
	wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/themes/smoothness/jquery-ui.css');

    $pluginurl = plugin_dir_url( __FILE__ );
    wp_enqueue_script(
		'select2', 
		$pluginurl.'js/select2.min.js', 
		array('jquery'),
		null,
		true
	);
    wp_enqueue_style(
		'select2', 
		$pluginurl.'css/select2.css', 
		array(),
		null,
		'all'
	);
    wp_enqueue_style(
		'font-awesome', 
		$pluginurl.'css/font-awesome.min.css',
		array(),
		null,
		'all'
	);
	wp_enqueue_script(
		'wpmm-admin', 
		$pluginurl.'js/wpmm-admin.js', 
		array('jquery'),
		WPMM_PLUGIN_VERSION,
		true
	);
	wp_localize_script( 'wpmm-admin', 'wpmm', array(
		'blank' => plugins_url('images/blank.png', __FILE__)
	));
    wp_enqueue_style(
		'wpmm-admin', 
		$pluginurl.'css/wpmm-admin.css', 
		array(),
		WPMM_PLUGIN_VERSION,
		'all'
	);
}

// enqueue frontend scripts
add_action('wp_enqueue_scripts', 'wpmm_enqueue_scripts');
function wpmm_enqueue_scripts() {
	$pluginurl = plugin_dir_url( __FILE__ );
	wp_enqueue_script(
		'wpmm', 
		$pluginurl.'js/wpmm.js', 
		array('jquery'),
		WPMM_PLUGIN_VERSION,
		true
	);
	wp_localize_script( 'wpmm', 'wpmm', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'container_selector' => apply_filters( 'wpmm_container_selector', '' ),
		'css_class' => apply_filters( 'wpmm_class_stem', 'wpmm' )
	));
}

// enqueue frontend css
add_action('wp_enqueue_scripts', 'wpmm_enqueue_styles');
function wpmm_enqueue_styles() {
	$pluginurl = plugin_dir_url( __FILE__ );
	wp_enqueue_style(
		'font-awesome', 
		$pluginurl.'css/font-awesome.min.css',
		array(),
		null,
		'all'
	);
	wp_enqueue_style(
		'wpmm', 
		$pluginurl.'css/wpmm.css', 
		array(),
		WPMM_PLUGIN_VERSION,
		'all'
	);
	wp_register_style( 'wpmm-animations', $pluginurl.'css/wpmm-animations.css', array(), null, 'all' );
}

add_action( 'wp_ajax_get_megamenu', 'ajax_get_megamenu' );
add_action( 'wp_ajax_nopriv_get_megamenu', 'ajax_get_megamenu' );
function ajax_get_megamenu() {
	$type = $_POST['type'];
	echo wpmm_get_megamenu($type, $_POST);
	exit;
}
function wpmm_get_megamenu($type, $args) {
	$return = '';
	$class = apply_filters( 'wpmm_class_stem', 'wpmm' );
	if (!empty($args['subcategories'])) 
		$args['subcategories'] = explode(',', $args['subcategories']);

	if ($type == 'taxonomy') {
		$args = wp_parse_args( $args, array('object' => 'category', 'object_id' => 0, 'new_object_id' => 0, 'page' => 1, 'posts_per_page' => 4, 'show' => 'posts') );
		$show = $args['show'];
		if ($show == 'posts' || $show == 'both') {
			if ($show == 'both' && is_taxonomy_hierarchical($args['object'])) {
				$categories = get_categories( array('parent' => $args['object_id'], 'taxonomy' => $args['object'], 'hide_empty' => 0 ) );
				$subcats_default = false;
				if (empty($args['subcategories'])) $subcats_default = true;

				if (!empty($categories)) {
					$return .= '<div class="'.$class.'-subcategories">';
					foreach ($categories as $cat) {
						if ($subcats_default || (!empty($args['subcategories']) && is_array($args['subcategories']) && in_array($cat->term_id, $args['subcategories']))) {
							$current = '';
							if ($cat->term_id == $args['new_object_id'])
								$current = ' '.$class.'-current-subcategory';

							$return .= '<a href="'.get_term_link((int) $cat->term_id, $args['object']).'" class="'.$class.'-subcategory'.$current.'" data-new_object_id="'.$cat->term_id.'"><i class="fa fa-angle-right"></i> '.$cat->name.'</a> ';
						}
					}
					$return .= '</div>';
					$args['posts_per_page'] = 3;
				}
			}

			$posts_per_page = apply_filters( 'wpmm_posts_per_page', $args['posts_per_page'], $type, $args);
			$return .= '<div class="'.$class.'-posts '.$class.'-'.$posts_per_page.'-posts "><div class="loaderblock"><div class="loader"><div class="dot1"></div><div class="dot2"></div></div></div>';
			$query_args = array(
				'posts_per_page' => $posts_per_page, 
				'post_status' => 'publish', 
				'paged' => $args['page'], 
				'wpmm_pagination' => $args['pagination'],
				'wpmm_subcategories' => $args['subcategories'],
				'wpmm_excerpts' => $args['excerpts'],
				'wpmm_authors' => $args['authors'],
				'wpmm_dates' => $args['dates'],
				'wpmm_thumbnails' => $args['thumbnails'],
				'tax_query' => array(
					array(
						'taxonomy' => $args['object'], 
						'terms' =>  (!empty($args['new_object_id']) ? $args['new_object_id'] : $args['object_id'])
					)
				)
			);
			$return .= wpmm_get_the_posts($query_args);
			$return .= '</div>';
		} elseif ($show == 'subcategories') {
			// posts_per_page means categories per page
			if (is_taxonomy_hierarchical($args['object'])) {
				$categories = get_categories( array('parent' => $args['object_id'], 'taxonomy' => $args['object'], 'hide_empty' => 0 ) );
				$subcats_default = false;
				if (empty($args['subcategories'])) $subcats_default = true;
				
				if (!empty($categories)) {
					// weed out excluded categories
					$cat_tmp = array();
					foreach ($categories as $i => $cat) {
						if ($subcats_default || (!empty($args['subcategories']) && is_array($args['subcategories']) && in_array($cat->term_id, $args['subcategories']))) {
							$cat_tmp[] = $cat;
						}
					}
					$categories = $cat_tmp;

					$total = count($categories);
					$posts_per_page = apply_filters( 'wpmm_subcategories_per_page', $args['posts_per_page'], $type, $args);
					if ($posts_per_page == -1) 
						$posts_per_page = 999; // large number
					$start = ($args['page'] - 1) * $posts_per_page;
					$end = $start + $posts_per_page;
					$last_page = ceil($total / $posts_per_page);

					foreach ($categories as $i => $cat) {
						if ($i < $start || $i >= $end)
							continue;

						$return .= '<div class="'.$class.'-subcategories '.$class.'-subcategory-children">';
						$return .= '<h4 class="'.$class.'-subcategory-heading"><a href="'.get_term_link((int) $cat->term_id, $args['object']).'">'.$cat->name.'</a></h4>';
						// and now the sub sub categories
						// not using ul/li to avoid conflict with theme's menu css/js
						$subcategories = get_categories( array('parent' => $cat->term_id, 'taxonomy' => $args['object'], 'hide_empty' => 0) );
						if (!empty($subcategories)) {
							foreach ($subcategories as $subcat) {
								$return .= '<a href="'.get_term_link((int) $subcat->term_id, $args['object']).'" class="'.$class.'-subcategory-child"><i class="fa fa-angle-right"></i> '.$subcat->name.'</a>';
							}
						}
						$return .= '</div>';
					}
					if ($args['pagination'] && ($args['page'] > 1 || $last_page > $args['page'])) {
						$return .= '<div class="'.$class.'-pagination '.$class.'-subcategories-pagination">';
						if ($args['page'] > 1)
							$return .= '<a href="#" class="'.$class.'-pagination-previous" data-page="'.($args['page']-1).'"><i class="fa fa-angle-left"></i></a>';
						if ($last_page > $args['page'])
							$return .= '<a href="#" class="'.$class.'-pagination-next" data-page="'.($args['page']+1).'"><i class="fa fa-angle-right"></i></a>';
						$return .= '</div>';
					}
				}
			} else {
				$return .= __('This taxonomy is not hierarchical. Set this mega menu to Posts.', 'wpmm'); 
			}
		}
	}
	return apply_filters('wpmm_get_megamenu_html', $return, $type, $args);
}
function wpmm_get_the_posts( $query_args ) {
	$class = apply_filters( 'wpmm_class_stem', 'wpmm' );
	$posts_query = new WP_Query( $query_args );  
	$return = '';     
	$last_page = $posts_query->max_num_pages;
	global $post;
	while ($posts_query->have_posts()) : $posts_query->the_post();
		//$return .= '<li>';
		$return .= '<div class="'.$class.'-post post-'.$post->ID.'">';
			if ($query_args['wpmm_thumbnails']) {
				$thumb = '<div class="'.$class.'-thumbnail">';
	                $thumb .= '<a title="'.get_the_title().'" href="'.get_the_permalink().'">';
						if(has_post_thumbnail()):
							$thumb .= get_the_post_thumbnail($post->ID, 'wpmm_thumb', array('title' => ''));
						else:
							$thumb .= '<img src="'.plugins_url('images/thumb.png', __FILE__).'" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
						endif;
	                $thumb .= '</a>';
	                // WP Review - can be taken out of this DIV using the filter below
					$thumb .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
				$thumb .= '</div>';
				$return .= apply_filters('wpmm_thumbnail_html', $thumb, $post->ID);
			}

			$return .= '<div class="'.$class.'-entry-title"><a title="'.get_the_title().'" href="'.get_the_permalink().'">'.get_the_title().'</a></div>';
			if ($query_args['wpmm_dates']){
				$return .= '<div class="'.$class.'-entry-date">';
				$return .= '<i class="fa fa-clock-o"></i><span>'.get_the_date().'</span>';
				$return .= '</div>';
			}
			if ($query_args['wpmm_authors']){
				$return .= '<div class="'.$class.'-entry-author"><i class="fa fa-user"></i>';
				$return .= '<a href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'">'.get_the_author_meta( 'display_name' ).'</a>';
				$return .= '</div>';
			}
			if ($query_args['wpmm_excerpts']){
				$return .= '<p class="'.$class.'-post-excerpt">';
				$excerpt = explode(' ', strip_tags( get_the_excerpt() ));
				if (count($excerpt) > 10) {
					$excerpt = implode(' ', array_slice($excerpt, 0, 11)).'...';
				} else {
					$excerpt = implode(' ', $excerpt);
				}
				$excerpt .= '';
				$return .= $excerpt;
				$return .= '</p>';
			}
		//$return .= '</li>';
		$return .= '</div>';
	endwhile; wp_reset_query();
	if ($query_args['wpmm_pagination'] && ($query_args['paged'] > 1 || $last_page > $query_args['paged'])) {
		$return .= '<div class="'.$class.'-pagination">';
		//if ($query_args['paged'] > 1)
			$return .= '<a href="#" class="'.$class.'-pagination-previous'.($query_args['paged'] > 1 ? '' : ' inactive').'" data-page="'.($query_args['paged']-1).'"><i class="fa fa-angle-left"></i></a>';
		//if ($last_page > $query_args['paged'])
			$return .= '<a href="#" class="'.$class.'-pagination-next'.($last_page > $query_args['paged'] ? '' : ' inactive').'" data-page="'.($query_args['paged']+1).'"><i class="fa fa-angle-right"></i></a>';
		$return .= '</div>';
	}
	return apply_filters('wpmm_get_the_posts_html', $return, $query_args);
}

// preload initial mega menu contents in hidden divs in footer
function wpmm_preload() {
 	global $wpmm_megamenus;
 	foreach ($wpmm_megamenus as $id => $megamenu) {

 		$megamenu_content = wpmm_get_megamenu($megamenu['type'], $megamenu['args']);

 		// minimize effect on page size: 
 		// remove SRC attributes from images, to add them back with JS when needed
 		$megamenu_content = preg_replace('/src="([^"]+)"/i', "src=\"".plugins_url('images/thumb.png', __FILE__)."\" data-src=\"$1\"", $megamenu_content);

 		echo '<div class="wpmm-preload wpmm-preload-megamenu-'.$id.'">'.$megamenu_content.'</div>';
 	}
}
add_action( 'wp_footer', 'wpmm_preload' );

?>