<?php
/**
 * Plugin Name: Unveil Lazy Load
 * Description: This plugin makes lazy-image-load possible to decrease number of requests and improve page loading time, and uses a lightweight jQuery plugin created by optimizing <a href="https://github.com/luis-almeida/unveil">Unveil.js</a> in order to only load an image when it's visible in the viewport.
 * Version: 0.3.1
 * Author: Daisuke Maruyama
 * Author URI: http://marubon.info/
 * Plugin URI: http://wordpress.org/plugins/unveil-lazy-load/
 * License: GPLv2 or later
 * 
 */

/*

Copyright (C) 2014 Daisuke Maruyama

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

if (!class_exists('UnveilLazyLoad')) {

class UnveilLazyLoad {

	const version = '0.3.1';

	function __construct() {
		if (is_admin()) return;

	  	add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
	  	add_filter('the_content', array($this, 'add_dummy_image'), 99); 
	  	add_filter('post_thumbnail_html', array($this, 'add_dummy_image'), 11);
	  	add_filter('get_avatar', array($this, 'add_dummy_image' ), 11);
	}

	function load_scripts() {
		wp_enqueue_script( 'unveil',  $this->get_url( 'js/jquery.optimum-lazy-load.min.js' ), array( 'jquery' ), self::version, true );
	}
  
    function add_dummy_image( $content ) {
		
	  	if(is_feed() || is_preview() || $this->is_smartphone()) return $content;

	  	if (strpos( $content, 'data-src' ) !== false) return $content;

	  	$content = preg_replace_callback('#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', array($this, 'replace_callback'), $content);
	  
		return $content;
	}

  	private function replace_callback($matches){
	  	$dummy_image = 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==';
	  
	  	if (preg_match('/ data-lazy *= *"false" */', $matches[0])){
		  	return '<img' . $matches[1] . 'src="' . $matches[2] . '"' . $matches[3] . '>';
		} else {
		  	return '<img' . $matches[1] . 'src="' . $dummy_image . '" data-src="' . $matches[2] . '"' . $matches[3] . '><noscript><img' . $matches[1] . 'src="' . $matches[2] . '"' . $matches[3] . '></noscript>';
		}
	}
  
	private function get_url( $path = '' ) {
		return plugins_url( ltrim( $path, '/' ), __FILE__ );
	}
  
	private function is_smartphone(){
    	$useragents = array(
        	'iPhone',
        	'iPod',
        	'Android.*Mobile',
        	'Windows.*Phone',
        	'dream',
        	'CUPCAKE',
        	'blackberry9500',
        	'blackberry9530',
        	'blackberry9520',
        	'blackberry9550',
        	'blackberry9800',
        	'webOS',
        	'incognito',
        	'webmate'
    	);
	  
    	$search_pattern = '/' . implode('|', $useragents) . '/i';
	  
	  	$useragent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';

	  	return preg_match($search_pattern, $useragent);
	}

  	/**
	 * Output log message according to WP_DEBUG setting
	 *
	 */	    
	private function log($message) {
    	if (WP_DEBUG === true) {
      		if (is_array($message) || is_object($message)) {
        		error_log(print_r($message, true));
      		} else {
        		error_log($message);
      		}
    	}
  	}  
  
}

$unveil_lazy_load = new UnveilLazyLoad();

}
