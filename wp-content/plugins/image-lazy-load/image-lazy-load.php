<?php
/**
* Plugin Name: Image Lazy Load (Unveil.js)
* Plugin URI: http://www.wpcube.co.uk/plugins/image-lazy-load
* Version: 1.0.7
* Author: WP Cube
* Author URI: http://www.wpcube.co.uk
* Description: Lazy load content images using the unveil.js jQuery plugin
* License: GPL2
*/

/*  Copyright 2013 WP Cube (email : support@wpcube.co.uk)

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

/**
* Image Lazy Load Class
* 
* @package WP Cube
* @subpackage Image Lazy Load
* @author Tim Carr
* @version 1.0.7
* @copyright WP Cube
*/
class imageUnveil {
    /**
    * Constructor.
    */
    function __construct() {

        // Plugin Details
        $this->plugin               = new stdClass;
        $this->plugin->name         = 'image-lazy-load'; // Plugin Folder
        $this->plugin->displayName  = 'Image Lazy Load'; // Plugin Name
        $this->plugin->version      = '1.0.7';
        $this->plugin->folder       = plugin_dir_path( __FILE__ );
        $this->plugin->url          = plugin_dir_url( __FILE__ );

        // Dashboard Submodule
        if (!class_exists('WPCubeDashboardWidget')) {
			require_once($this->plugin->folder.'/_modules/dashboard/dashboard.php');
		}
		$dashboard = new WPCubeDashboardWidget($this->plugin); 
		
		// Hooks
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
        add_action('plugins_loaded', array(&$this, 'loadLanguageFiles'));
        add_action('wp_enqueue_scripts', array(&$this, 'scriptsAndCSS'));
        add_action('wp_footer', array(&$this, 'frontendSettings'));
        
        // Filters
        add_filter('the_content', array(&$this, 'replaceImg'));

        // Activation
        register_activation_hook( __FILE__, array(&$this, 'pluginActivate'));
    }

    /**
    * Checks if the device viewing a page is a mobile
    *
    * @return bool Is Mobile
    */
    function checkMobile(){
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
            return true;
        }
        return false; 
    }

    function pluginActivate() {
        $this->settings = get_option($this->plugin->name);
        if($this->settings == '' OR empty($this->settings)){
            // No settings, configure the default
            update_option($this->plugin->name, array('load' => 0, 'mobile' => 0));
        }
    }
    
    /**
    * Register the plugin settings panel
    */
    function adminPanelsAndMetaBoxes() {
        add_menu_page($this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array(&$this, 'adminPanel'), 'dashicons-images-alt');
    }
    
	/**
    * Output the Administration Panel
    * Save POSTed data from the Administration Panel into a WordPress option
    */
    function adminPanel() {
        // Save Settings
        if (isset($_POST['submit'])) {
        	if (isset($_POST[$this->plugin->name])) {
        		update_option($this->plugin->name, $_POST[$this->plugin->name]);
				$this->message = __('Settings Updated.', $this->plugin->name);
			}
        }
        
        // Get latest settings
        $this->settings = get_option($this->plugin->name);
        
		// Load Settings Form
        include_once(WP_PLUGIN_DIR.'/'.$this->plugin->name.'/views/settings.php');  
    }
    
    /**
	* Loads plugin textdomain
	*/
	function loadLanguageFiles() {
		load_plugin_textdomain($this->plugin->name, false, $this->plugin->name.'/languages/');
	}

    /**
    * Register and enqueue and JS and CSS for the WordPress Frontend
    */
    function scriptsAndCSS() {
        // JS
        wp_enqueue_script($this->plugin->name.'-unveil-ui', $this->plugin->url.'js/unveil-ui.min.js', array('jquery'), $this->plugin->version, true);
        
        // CSS
        wp_enqueue_style($this->plugin->name.'-frontend', $this->plugin->url.'css/frontend.css', array(), $this->plugin->version); 
    }

    /**
    * Adds a <script> tag to the footer containing the necessary settings
    */
    function frontendSettings() {
        // Get latest settings
        $this->settings = get_option($this->plugin->name);

        // First check if we're on mobile, if not, also check if mobile lazy load is enabled in the settings
        if($this->checkMobile() == false || (isset($this->settings['mobile']) && $this->settings['mobile'] == 1)){
            echo '<script type="text/javascript">' . 
            'var imageUnveilload = ' . $this->settings['load'] . ';' .
            '</script>';
        }
    }

    /**
    * Replaces all <img> tags in the content with appropriately formatted tags that unveil.js can handle
    * This also runs a check for the wp-retina-2x plugin. If found, the appropriate retina data attributes will also be added.
    */
    function replaceImg($content) {
        // Get latest settings
        $this->settings = get_option($this->plugin->name);

        // First check if we're on mobile, if not, also check if mobile lazy load is enabled in the settings
        if($this->checkMobile() == false || (isset($this->settings['mobile']) && $this->settings['mobile'] == 1)){
            // Smallest, most stable, 1 by 1 pixel transparent image
            $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
            
            // Replace all image tags with the appropriately formatted HTML
            // We add a data-unveil attribute so we can target a CSS transition (fade-in)
            $content = preg_replace('#<img([^>]+?)src=[\'"]?([^\'"\s>]+)[\'"]?([^>]*)>#', sprintf('<img${1}src="%s" data-src="${2}"${3} data-unveil="true"><noscript><img${1}src="${2}"${3}></noscript>', $placeholder), $content);
        }

        // Return
        return $content;
    }
}
$imageUnveil = new imageUnveil();
?>
