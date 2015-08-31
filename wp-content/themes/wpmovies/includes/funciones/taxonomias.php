<?php

$mundothemes = wp_get_theme();
define('mt_name', 'Mundothemes');
define('mt_autor', $mundothemes->Author);
define('mt_version', trim($mundothemes->Version));
define('mt_cms', 'WordPress');
define('mt_cms_url', 'wordpress.org');
define('mt_repositorio', 'mundothemes.com');

$status_labels = array(
      'name'              => _x( 'Status', 'Status general name' ),
      'singular_name'     => _x( 'Status', 'Status singular name' ),
      'search_items'      => __( 'Search status' ),
      'all_items'         => __( 'All Status' ),
      'parent_item'       => __( 'Parent Status' ),
      'parent_item_colon' => __( 'Parent Status:' ),
      'edit_item'         => __( 'Edit Status' ),
      'update_item'       => __( 'Update Status' ),
      'add_new_item'      => __( 'Add New Status' ),
      'new_item_name'     => __( 'New Status Name' ),
      'menu_name'         => __( 'Status' ),
    );
register_taxonomy('movie_status', array('post'), array('hierarchical' => true, 'labels' => $status_labels, 'query_var' => true, 'rewrite' => true));

register_taxonomy(get_option('director'), 'post', array('hierarchical' => false, 'label' => __('Directors', 'mundothemes'), 'query_var' => true, 'rewrite' => true));

register_taxonomy(get_option('escritor'), 'post', array('hierarchical' => false, 'label' => __('Writers', 'mundothemes'), 'query_var' => true, 'rewrite' => true));

register_taxonomy(get_option('actor'), 'post', array('hierarchical' => false, 'label' => __('Cast', 'mundothemes'), 'query_var' => true, 'rewrite' => true));

register_taxonomy(get_option('year'), 'post', array('hierarchical' => false, 'label' => __('Year', 'mundothemes'), 'query_var' => true, 'rewrite' => true));

register_taxonomy(get_option('calidad'), 'post', array('hierarchical' => false, 'label' => __('Quality', 'mundothemes'), 'query_var' => true, 'rewrite' => true));

function home() {
    echo "Requires license";
}

add_action( 'init', 'create_genres_taxonomies', 0 );

// create two taxonomies, genres and writers for the post type "book"
function create_genres_taxonomies() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => _x( 'Genres', 'taxonomy general name' ),
    'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
    'search_items'      => __( 'Search Genres' ),
    'all_items'         => __( 'All Genres' ),
    'parent_item'       => __( 'Parent Genre' ),
    'parent_item_colon' => __( 'Parent Genre:' ),
    'edit_item'         => __( 'Edit Genre' ),
    'update_item'       => __( 'Update Genre' ),
    'add_new_item'      => __( 'Add New Genre' ),
    'new_item_name'     => __( 'New Genre Name' ),
    'menu_name'         => __( 'Genre' ),
  );

  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'genre' ),
    'show_admin_column' => true,
  );

  register_taxonomy( 'genre', array( 'post', 'episodios' ), $args );
}
