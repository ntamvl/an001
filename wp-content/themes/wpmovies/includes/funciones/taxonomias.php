<?php
#######################################################
# Precausion: NO EDITAR ESTOS CAMPOS, Riesgo absoluto.#
# Puedes editar los valores SLUGS, desde el Framework.#
#######################################################
$mundothemes = wp_get_theme();
define('mt_name', 'Mundothemes');
define('mt_autor', $mundothemes->Author);
define('mt_version', trim($mundothemes->Version));
define('mt_cms', 'WordPress');
define('mt_cms_url', 'wordpress.org');
define('mt_repositorio', 'mundothemes.com');
#######################################################
	register_taxonomy 
	( get_option('director'), 'post', array(
	/* ============================================= */
	'hierarchical' => false,  'label' => __( 'Directors', 'mundothemes' ),
	'query_var' => true, 'rewrite' => true)
	);
	register_taxonomy 
	( get_option('escritor'), 'post', array(
	/* ============================================= */
	'hierarchical' => false,  'label' => __( 'Writers', 'mundothemes' ),
	'query_var' => true, 'rewrite' => true)
	);
	register_taxonomy 
	( get_option('actor'), 'post', array(
	/* ============================================= */
	'hierarchical' => false,  'label' => __( 'Cast', 'mundothemes' ),
	'query_var' => true, 'rewrite' => true)
	);

	register_taxonomy 
	( get_option('year'), 'post', array(
	/* ============================================= */
	'hierarchical' => false,  'label' => __( 'Year', 'mundothemes' ),
	'query_var' => true, 'rewrite' => true)
	);

	register_taxonomy 
	( get_option('calidad'), 'post', array(
	/* ============================================= */
	'hierarchical' => false,  'label' => __( 'Quality', 'mundothemes' ),
	'query_var' => true, 'rewrite' => true)
	);

	##########################################################
function home() {
	echo "Requires license";
}