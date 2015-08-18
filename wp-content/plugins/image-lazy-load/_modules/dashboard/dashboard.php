<?php
/**
* Dashboard Widget
* 
* @package WP Cube
* @subpackage Dashboard
* @author Tim Carr
* @version 1.0
* @copyright WP Cube
*/
class WPCubeDashboardWidget {     
	/**
	* Constructor
	*
	* @param object $plugin Plugin Object (name, displayName, version, folder, url)
	*/
	function __construct($plugin) {
		// Plugin Details
        $this->dashboard = $plugin;
        $this->dashboardURL = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

		// Hooks
		add_action('admin_enqueue_scripts', array(&$this, 'adminScriptsAndCSS'));
		add_filter('admin_footer_text', array(&$this, 'adminFooterText'));
		add_action('wp_dashboard_setup', array(&$this, 'dashboardWidget'));
		add_action('wp_network_dashboard_setup', array(&$this, 'dashboardWidget'));	
	}     
	
	/**
    * Register and enqueue shared Admin UI CSS for WP Cube Plugins
    */
    function adminScriptsAndCSS() {    
    	// JS
    	// This will only enqueue once, despite this hook being called by up to several plugins,
    	// as we have set a single, distinct name
    	wp_enqueue_script('wpcube-admin', $this->dashboardURL.'js/admin.js', array('jquery'), $this->dashboard->version, true);
    	   
    	// CSS
    	// This will only enqueue once, despite this hook being called by up to several plugins,
    	// as we have set a single, distinct name
        wp_enqueue_style('wpcube-admin', $this->dashboardURL.'css/admin.css'); 
    }	
    
    /**
    * Replaces the footer text with the plugin name when viewing the plugin
    */
    function adminFooterText($default) {
    	if (isset($_GET['page']) AND $_GET['page'] == $this->dashboard->name) {
    		echo $this->dashboard->displayName;
    	} else {
    		echo $default;
    	}
    }
	
	/**
    * Adds a dashboard widget to list WP Cube Products + News
    *
    * Checks if another WP Cube plugin has already created this widget - if so, doesn't duplicate it
    */
    function dashboardWidget() {
    	global $wp_meta_boxes;
    	
    	if (isset($wp_meta_boxes['dashboard']['normal']['core']['wp_cube'])) return; // Another plugin has already registered this widget
    	wp_add_dashboard_widget('wp_cube', 'WP Cube', array(&$this, 'outputDashboardWidget'));
    }
    
    /**
    * Called by dashboardWidget(), includes dashboard.php to output the Dashboard Widget
    */
    function outputDashboardWidget() {
    	$result = wp_remote_get('http://www.wpcube.co.uk/feed/products');
    	if (!is_wp_error($result)) {
	    	if ($result['response']['code'] == 200) {
	    		$xml = simplexml_load_string($result['body']);
	    		$products = $xml->channel;
	    	}
	    	
	    	include_once(WP_PLUGIN_DIR.'/'.$this->dashboard->name.'/_modules/dashboard/views/dashboard.php');
    	} else {
    		include_once(WP_PLUGIN_DIR.'/'.$this->dashboard->name.'/_modules/dashboard/views/dashboard-nodata.php');
    	}
    }
}
?>