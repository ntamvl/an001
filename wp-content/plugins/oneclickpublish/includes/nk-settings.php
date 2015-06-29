<?php
function register_nk_custom_menu_page(){
	add_menu_page( 'nk bulk', __('OneClickPublish','domain'), 'manage_options', 'nk-bulk', 'my_custom_menu_page', plugins_url( '/img/wpmini-blue.png',dirname(__FILE__) ) );
	add_submenu_page( 'nk-bulk', 'Published Post', 'Published Post', 'manage_options', 'nk-publish', 'nk_published_post' );
	add_submenu_page( 'nk-bulk', 'Drafted Post', 'Drafted Post', 'manage_options', 'nk-draft', 'nk_drafted_post' );
	add_submenu_page( 'nk-bulk', 'Published Page', 'Published Page', 'manage_options', 'nk-publish-page', 'nk_published_page' );
	add_submenu_page( 'nk-bulk', 'Drafted Page', 'Drafted Page', 'manage_options', 'nk-draft-page', 'nk_drafted_page' );

}

function register_my_script($hook){
	if(is_admin())
	{
		$myhook= end(explode('_',$hook));
		if( 'nk-bulk' === $myhook  || 'nk-draft' === $myhook  || 'nk-publish' === $myhook || 'nk-publish-page' === $myhook || 'nk-draft-page' === $myhook )
		{
			wp_register_script('jquerynew', plugins_url('/js/jquery-1.9.js', dirname(__FILE__)));
			wp_register_script('nk_data_script', plugins_url('/js/jquery.dataTables.js', dirname(__FILE__)),array('jquery'));
			wp_register_script('nk_script', plugins_url('/js/nk_script.js', dirname(__FILE__)),array('jquery'));
				
			wp_enqueue_script('jquerynew');
			wp_enqueue_script('nk_script');
			wp_enqueue_script('nk_data_script');

			$author_nonce = wp_create_nonce( 'nk_yantrakaar' );
			
			//echo plugins_url('/js/jquery-1.9.js', dirname(__FILE__));

			wp_localize_script( 'nk_script', 'nk_object',array( 'nk_ajax_url' => admin_url( 'admin-ajax.php' ) , 'nk_plugin_url' => plugins_url() ,'nk_author' => $author_nonce) );
		} else {
			return;
				
		}
	}
} // end register_my_script

function register_my_style($hook){
	if(is_admin())
	{
		$myhook= end(explode('_',$hook));
		if( 'nk-bulk' === $myhook  || 'nk-draft' === $myhook  || 'nk-publish' === $myhook || 'nk-publish-page' === $myhook || 'nk-draft-page' === $myhook )
		{
			wp_register_style( 'nk_style_data', plugins_url('/css/jquery.dataTables.css', dirname(__FILE__)) );
			wp_register_style( 'nk_style_self', plugins_url('/css/nk_style.css', dirname(__FILE__)) );


			wp_enqueue_style( 'nk_style_data' );
			wp_enqueue_style( 'nk_style_self' );
		} else {
			return;
		}
	}
} // end register_my_style