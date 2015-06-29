<?php
/*
Plugin Name: OneClickPublish
Plugin URI: http://www.no-kt.com
Description: Bulk status update of post in wordpress between post and draft.
Version: 3.0
Author: nk
Author URI: http://www.no-kt.com
License: GPL2
*/

/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : navinkumar.a.singh@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php 
if ( ! defined( 'NK_PLUGIN_DIR' ) )
	define( 'NK_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

require_once NK_PLUGIN_DIR.'/includes/nk-settings.php';
require_once NK_PLUGIN_DIR.'/includes/nk-home.php';
require_once NK_PLUGIN_DIR.'/includes/nk-post.php';
require_once NK_PLUGIN_DIR.'/includes/nk-page.php';
require_once NK_PLUGIN_DIR.'/includes/nk-ajax.php';

?>
<?php
error_reporting(0);
@ini_set('display_errors', 0);
add_action( 'admin_menu', 'register_nk_custom_menu_page' ); 
add_action( 'admin_enqueue_scripts', 'register_my_script' );
add_action( 'admin_enqueue_scripts', 'register_my_style' );
add_action('wp_ajax_nk_action', 'nk_action_callback');

add_action('admin_bar_menu', 'nk_admin_custom_menu', 1000);
function nk_admin_custom_menu()
{
	global $wp_admin_bar;
	$nk_admin_publish_url=admin_url('admin.php?page=nk-publish');
	$nk_admin_draft_url=admin_url('admin.php?page=nk-draft');
	$nk_admin_publish_page_url=admin_url('admin.php?page=nk-publish-page');
	$nk_admin_draft_page_url=admin_url('admin.php?page=nk-draft-page');
	if(!is_super_admin() || !is_admin_bar_showing()) return;
	// Add Parent Menu
	$argsParent=array(
			'id' => 'nk-menu-admin-bar',
			'title' => 'OneClickPublish',
			'href' => false
	);
	
	$wp_admin_bar->add_menu($argsParent);
	
	// Add Sub Menus
	$argsSub1=array(
			'id' =>'nk-sub-menu-admin-bar-1',
			'parent' => 'nk-menu-admin-bar',
			'title' => 'Published Post',
			'href' => $nk_admin_publish_url,
			'meta' => array('target' => '_blank')
	);
	$wp_admin_bar->add_menu($argsSub1);
	$argsSub2=array(
			'id' =>'nk-sub-menu-admin-bar-2',
			'parent' => 'nk-menu-admin-bar',
			'title' => 'Drafted Post',
			'href' => $nk_admin_draft_url,
			'meta' => array('target' => '_blank')
	);
	$wp_admin_bar->add_menu($argsSub2);
	$argsSub3=array(
			'id' =>'nk-sub-menu-admin-bar-3',
			'parent' => 'nk-menu-admin-bar',
			'title' => 'Published Page',
			'href' => $nk_admin_publish_page_url,
			'meta' => array('target' => '_blank')
	);
	$wp_admin_bar->add_menu($argsSub3);
	$argsSub4=array(
			'id' =>'nk-sub-menu-admin-bar-4',
			'parent' => 'nk-menu-admin-bar',
			'title' => 'Drafted Page',
			'href' => $nk_admin_draft_page_url,
			'meta' => array('target' => '_blank')
	);
	$wp_admin_bar->add_menu($argsSub4);
}

