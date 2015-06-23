<?php
/*
	wpmm_container_selector
	-----------------------
	Mega Menu element can be attached to any parent element of the menu item with this filter.
	If filter is set, first matching parent element of menu item link (<a> element) is chosen.
	If filter is set, position, z-index, and perspective CSS properties 
	aren't applied automatically to the selected element

	By default (or if matching element is not found), mega menu is appended to the menu <ul> element,
	ie. UL > DIV#wpmm-megamenu (which is not valid but solves possible conflict issues).
	Position, z-index, and perspective CSS properties are set for the element.
*/

/* Example 1: custom parent element */
function megamenu_parent_element( $selector ) {
	return '#header';
}
add_filter( 'wpmm_container_selector', 'megamenu_parent_element' );

/* Example 2: use parent LI as container element */
function megamenu_in_item( $selector ) {
	return 'li.menu-item';
}
add_filter( 'wpmm_container_selector', 'megamenu_parent_element' );


/*
	wpmm_color_output
	-----------------
	Apply the user-defined color for different elements.
	By default, if this filter is not set, selected color is applied as font color for the menu item
*/

/* Example 1: colorize background instead of font color */
function menu_item_color( $item_output, $item_color, $item, $depth, $args ) {
	if (!empty($item_color))
		return $item_output.'<style>#menu-item-'. $item->ID . ' a:hover, #menu-item-'. $item->ID . '.current-menu-item a, #menu-item-'. $item->ID . '.wpmm-megamenu-showing a, #menu-item-'. $item->ID . '-megamenu, #menu-item-'. $item->ID . ' .sub-menu { background-color: ' . $item_color . ' !important; } </style>';
	else
		return $item_output; // user did not set a color for this item
}
add_filter( 'wpmm_color_output', 'menu_item_color', 10, 5 );

/* Example 2: colorize icon only */
function menu_icon_color( $item_output, $item_color, $item, $depth, $args ) {
	return ($item_color ? $item_output.'<style>#menu-item-'. $item->ID . ' i.wpmm-menu-icon { color: ' . $item_color . ' !important; } </style>' : $item_output);
}
add_filter( 'wpmm_color_output', 'menu_icon_color', 10, 5 );

/* Example 3: add style attribute / a different approach */
function menu_link_bg_color( $item_output, $item_color, $item, $depth, $args ) {
	return ($item_color ? str_replace('<a ', '<a style="background-color: '.$item_color.'" ', $item_output) : $item_output);
}
add_filter( 'wpmm_color_output', 'menu_link_bg_color', 10, 5 );

/*
	wpmm_color_schemes
	---------------------
	Add or remove color mega menu schemes. 
	Array key is added as class for the mega menu. Use the class in the CSS.
*/
/* Example 1: Add a new color scheme */
function megamenu_special_color_scheme( $schemes_array ) {
    $schemes_array['megamenu-special'] = __('Special Color Scheme', 'mythemeshop');
    return $schemes_array;
}
add_filter( 'wpmm_color_schemes', 'wpmm_color_schemes' );

/*
	wpmm_exclude_menu
	---------------------
	Mega menu walker function (including icon & color) is applied to all menus on the frontend.
	With this filter, it's possible to exclude selected menus.
*/

/* Example 1: exclude if theme location is 'primary' */
function megamenu_exclude( $exclude, $args ) {
	if ( $args['theme_location'] == 'primary' )
		$exclude = true;

	return $exclude;
}
add_filter( 'wpmm_exclude_menu', 'megamenu_exclude', 10, 2 );


/*
	wpmm_posts_per_page
	-----------------
	Posts per page in Taxonomy (Category, Product Category, etc.) Mega Menus,
	when they are set to "Show Posts" or "Show Posts & Subcategories"
	Default: 4, or 3 if subcategories list is present
*/

/* Example 1: show 8 / 6 posts */
function megamenu_posts_per_page( $posts_per_page, $megamenu_type, $megamenu_args ) {
	return $posts_per_page * 2;
}
add_filter( 'wpmm_posts_per_page', 'megamenu_posts_per_page', 10, 3 );


/*
	wpmm_subcategores_per_page
	--------------------------
	Number of subcategories per page (including their children) 
	in Taxonomy (Category, Product Category, etc.) Mega Menus,
	when they are set to "Show Subcategories"
	Default: 4
*/

/* Example 1: show all subcategories on one page */
function megamenu_subcategories_per_page( $categories_per_page, $megamenu_type, $megamenu_args ) {
	return -1;
}
add_filter( 'wpmm_subcategories_per_page', 'megamenu_subcategories_per_page', 10, 3 );

/*
	wpmm_thumbnail_html
	-------------------
	Filter for the thumbnail html.
*/

/* Example 1: change default "no preview" image */
function megamenu_default_thumb( $thumbnail_html, $post_id ) {
	if ( ! has_post_thumbnail( $post_id )) {
		$thumbnail_html = '<div class="wpmm-thumbnail">';
		$thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_the_permalink( $post_id ).'">';
		
		// change src attribute:
		$thumbnail_html .= '<img src="'.get_template_directory_uri().'/images/mythumb.png'.'" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
		
		$thumbnail_html .= '</a>';
	
		// WP Review
		$thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
	
		$thumbnail_html .= '</div>';
	}
	return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_default_thumb', 10, 2 );

/* Example 2: change image size */
function megamenu_thumbnails( $thumbnail_html, $post_id ) {
	$thumbnail_html = '<div class="wpmm-thumbnail">';
	$thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_permalink( $post_id ).'">';
	if(has_post_thumbnail($post_id)):
		$thumbnail_html .= get_the_post_thumbnail($post_id, 'mythumbsize', array('title' => ''));
	else:
		$thumbnail_html .= '<img src="'.plugins_url().'/wp-mega-menu/images/thumb.png" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
	endif;
	$thumbnail_html .= '</a>';
	
	// WP Review
	$thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');
	
	$thumbnail_html .= '</div>';

	return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_thumbnails', 10, 2 );

/* Example 3: take WP Review rating out of the thumbnail container DIV */
function megamenu_wp_review( $thumbnail_html, $post_id ) {
	$thumbnail_html = '<div class="wpmm-thumbnail">';
	$thumbnail_html .= '<a title="'.get_the_title( $post_id ).'" href="'.get_permalink( $post_id ).'">';
	if(has_post_thumbnail($post_id)):
		$thumbnail_html .= get_the_post_thumbnail($post_id, 'wpmm_thumb', array('title' => ''));
	else:
		$thumbnail_html .= '<img src="'.plugins_url().'/wp-mega-menu/images/thumb.png" alt="'.__('No Preview', 'wpmm').'"  class="wp-post-image" />';
	endif;
	$thumbnail_html .= '</a>';
	
	$thumbnail_html .= '</div>';

	// WP Review
	$thumbnail_html .= (function_exists('wp_review_show_total') ? wp_review_show_total(false) : '');

	return $thumbnail_html;
}
add_filter( 'wpmm_thumbnail_html', 'megamenu_wp_review', 10, 2 );

/* 

Additional nav menu related filters available in WP: 

- 'nav_menu_item_id' ($item_id_attr, $item, $args)
- 'nav_menu_link_attributes' ($atts, $item, $args)
- 'nav_menu_css_class' ($classes, $item, $args)
- 'walker_nav_menu_start_el' ($item_output, $item, $depth, $args)

*/

?>