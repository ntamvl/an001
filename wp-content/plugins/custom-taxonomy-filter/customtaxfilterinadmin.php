<?php
/*
 * Plugin Name:       CBX Custom Taxonomy Filter
 * Plugin URI:        http://wpboxr.com/product/custom-taxonomy-filter-in-wp-admin-post-listing
 * Description:       This plugin adds custom taxonomy filter in wordpress admin post listing panel.
 * Version:           1.4
 * Author:            WPBoxr
 * Author URI:        http://wpboxr.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       customtaxfilterinadmin
 * Domain Path:       /languages
*/
/*
    Copyright 2012-2015  Sabuj Kumar Kundu (email : info@wpboxr.com)


    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

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
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Use WordPress 2.6 Constants
 */
/*
if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}
*/

$wpcustomtaxfilterinadmin = get_option('wpcustomtaxfilterinadmin');

register_activation_hook( __FILE__, 'wpcustomtaxfilterinadmin_activate' );
register_deactivation_hook(__FILE__, 'wpcustomtaxfilterinadmin_deactivation');

add_action('admin_menu', 'wpcustomtaxfilterinadmin_admin');   //adding menu in admin menu settings


/**
 * Plugin activation
 */
function wpcustomtaxfilterinadmin_activate(){
    global $wpcustomtaxfilterinadmin;

	$defaults = array('post' => 1 );

    foreach($defaults  as $key => $value){
        $wpcustomtaxfilterinadmin[$key] = $value;
    }

    update_option('wpcustomtaxfilterinadmin',$wpcustomtaxfilterinadmin);

}

//plugin deactivation action
function wptoolboxfromcodeboxr_deactivation(){
    global $wpcustomtaxfilterinadmin;
    //let's keep the otpion table clean
    delete_option('wpcustomtaxfilterinadmin');

}

function wpcustomtaxfilterinadmin_admin(){
    global $wptoolboxfromcodeboxr_hook, $wpcustomtaxfilterinadmin;
    //add_options_page(page_title, menu_title, access_level/capability, file, [function]);
    if (function_exists('add_options_page')) {
          $page_hook = add_options_page('Custom Taxonomy filter in Wordpress Admin Post Listing', 'CBX Custom Tax Filter', 'manage_options', 'wpcustomtaxfilterinadmin', 'wpcustomtaxfilterinadmin_admin_option');
    }


}


//admin option page
function wpcustomtaxfilterinadmin_admin_option(){
    //global $wp_taxonomies;
    //var_dump($wp_taxonomies);
    ?>
	<h3>CBX Custom Tax Filter</h3>


	<div class="wrap">

		<div id="icon-options-general" class="icon32"></div>


		<div id="poststuff">

			<div id="post-body" class="metabox-holder columns-2">

				<!-- main content -->
				<div id="post-body-content">

					<div class="meta-box-sortables ui-sortable">

						<div class="postbox">

							<div class="handlediv" title="Click to toggle"><br></div>
							<!-- Toggle -->

							<h3 class="handle">Plugin Setting</span>
							</h3>

							<div class="inside">
							<?php
							global $wpcustomtaxfilterinadmin;
							//var_dump($wptoolboxfromcodeboxr);
							$builtinposts = array();
							$customposts  = array();
							$alltypeposts = array();

							$builtintaxs = array();
							$customtaxs  = array();
							$alltypetaxs = array();



							$builtinargs = array(
								'public'   => true,
								'show_ui'  => true,
								'_builtin' => true
								//'publicly_queryable' => true
							);

							$customargs = array(
								'public'   => true,
								'show_ui'  => true,
								'_builtin' => false
								//'publicly_queryable' => true
							);

							$output     = 'objects'; // names or objects, note names is the default
							$operator   = 'and'; // 'and' or 'or'

							//builtin post types
							$post_typesb = get_post_types($builtinargs, $output, $operator);

							foreach ($post_typesb  as $post_typeb ) {
								$label  = $post_typeb->labels->name;
								$name   = $post_typeb->name;
								$alltypeposts[$name] = $label;
								$builtinposts[$name] = $label;

								$taxonomies = get_object_taxonomies($name, 'objects');

								foreach($taxonomies as $taxonomy){
									$labeltax   = $taxonomy->labels->name;
									$nametax    = $taxonomy->name;

									if($taxonomy->_builtin == '1'){
										$builtintaxs[$nametax]  = $labeltax;
										$alltypetaxs[$nametax]  = $labeltax;
									}
									else{
										$customtaxs[$nametax]   = $labeltax;
										$alltypetaxs[$nametax]  = $labeltax;
									}
								}
							}

							//custom post types
							$post_typesc = get_post_types($customargs, $output, $operator);

							foreach ($post_typesc  as $post_typec ) {
								$label = $post_typec->labels->name;
								$name = $post_typec->name;
								$alltypeposts[$name] = $label;
								$customposts[$name]  = $label;

								$taxonomies = get_object_taxonomies($name, 'objects');
								foreach($taxonomies as $taxonomy){
									$labeltax   = $taxonomy->labels->name;
									$nametax    = $taxonomy->name;
									if($taxonomy->_builtin == '1'){
										$builtintaxs[$nametax]  = $labeltax;
										$alltypetaxs[$nametax]  = $labeltax;
									}
									else{
										$customtaxs[$nametax]   = $labeltax;
										$alltypetaxs[$nametax]  = $labeltax;
									}
								}
							}

							if(isset($_POST['uwpcustomtaxfilterinadmin'])) {
								check_admin_referer('wpcustomtaxfilterinadmin');

								foreach($alltypeposts  as $key => $value){
									//if(isset($_POST['pt'.$key])){
									$wpcustomtaxfilterinadmin[$key] = isset($_POST['pt'.$key]) ? $_POST['pt'.$key] : 0;
									//}


								}

								foreach($alltypetaxs  as $key => $value){
									//if(!isset($_POST['tx'.$key])) continue;
									$wpcustomtaxfilterinadmin[$key] = isset($_POST['tx'.$key]) ? trim($_POST['tx'.$key]) : 0;
								}

								/*echo '<pre>';
								print_r($wpcustomtaxfilterinadmin);
								echo '</pre>';*/

								update_option('wpcustomtaxfilterinadmin',$wpcustomtaxfilterinadmin);


							}//end main if

							$wpcustomtaxfilterinadmin = (array)get_option('wpcustomtaxfilterinadmin');

							/*echo '<pre>';
							print_r($wpcustomtaxfilterinadmin);
							echo '</pre>';*/

							if(isset($_POST['uwpcustomtaxfilterinadmin'])) {
								echo '<!-- Last Action --><div id="message" class="updated fade"><p>Options updated</p></div>';
							}

							?>


								<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
									<?php wp_nonce_field('wpcustomtaxfilterinadmin'); ?>

									<table cellspacing="0" class="widefat post fixed">
										<thead>
										<tr>
											<th style="" class="manage-column" scope="col">Post Types</th>
											<th style="" class="manage-column" scope="col">Selection</th>
										</tr>
										</thead>
										<tfoot>
										<tr>
											<th style="" class="manage-column" scope="col">Post Types</th>
											<th style="" class="manage-column" scope="col">Selection</th>
										</tr>
										</tfoot>
										<tbody>
										<?php
										//var_dump($alltypeposts);
										echo '<tr><td colspan="2"><h3>Built-in Posts Types</h3></td></tr>';
										foreach ($builtinposts  as $key => $value ) {



											$currentvalue = isset($wpcustomtaxfilterinadmin[$key]) ? $wpcustomtaxfilterinadmin[$key] : 0;

											//var_dump($key);
											//var_dump($currentvalue);


											echo '<tr>';
											echo '<td>'. $value.'['.$key. ']</td>';
											echo '<td><label for="pt'.$key.'"><input id="pt'.$key.'" type="checkbox" value="1" name="pt'.$key.'" '.checked('1', $currentvalue,false).' /> Enable/Disable</label></td>';
											echo '</tr>';
											echo '<tr><td colspan="2">';
											$taxonomies = get_object_taxonomies($key, 'objects');
											?>
											<table cellspacing="0" class="widefat post fixed">
												<thead>
												<tr>
													<th style="" class="manage-column" scope="col">Taxonomy Name</th>
													<th style="" class="manage-column" scope="col">Built-in</th>
													<th style="" class="manage-column" scope="col">Selection</th>
												</tr>
												</thead>
												<tfoot>
												<tr>
													<th style="" class="manage-column" scope="col">Taxonomy Name</th>
													<th style="" class="manage-column" scope="col">Built-in</th>
													<th style="" class="manage-column" scope="col">Selection</th>
												</tr>
												</tfoot>
												<tbody>

												<?php
												foreach($taxonomies as $taxonomy){
													$tkey = $taxonomy->name;

													//var_dump($wpcustomtaxfilterinadmin[$key]);

													$tcurrentvalue = isset($wpcustomtaxfilterinadmin[$tkey]) ? $wpcustomtaxfilterinadmin[$tkey] : 0 ;

													//var_dump($tkey);
													//var_dump($currentvalue);
													?>

													<tr>
														<td><?php echo $taxonomy->labels->name; ?></td>
														<td><?php echo is_cbplgtaxbuiltin_customtaxfilterinadmin($taxonomy->_builtin); ?></td>
														<?php echo '<td><label for="tx'.$tkey.'"><input id="tx'.$tkey.'" type="checkbox" value="1" name="tx'.$tkey.'" '.checked('1', $tcurrentvalue,false).' /> Enable/Disable</label></td>';  ?>
													</tr>
												<?php  }  ?>

												</tbody>
											</table>
											<?php
											echo '</tr>';
										}

										echo '<tr><td colspan="2"><h3>Custom Posts Types</h3></td></tr>';

										foreach ($customposts  as $key => $value ) {

											$currentvalue = isset($wpcustomtaxfilterinadmin[$key]) ? $wpcustomtaxfilterinadmin[$key] : 0 ;

											echo '<tr>';
											echo '<td>'. $value.'['.$key. ']</td>';
											echo '<td><label for="pt'.$key.'"><input id="pt'.$key.'" type="checkbox" value="1" name="pt'.$key.'" '.checked('1', $currentvalue, false).' /> Enable/Disable</label></td>';
											echo '</tr>';
											echo '<tr><td colspan="2">';

											$taxonomies = get_object_taxonomies($key, 'objects');
											?>
											<table cellspacing="0" class="widefat post fixed">
												<thead>
												<tr>
													<th style="" class="manage-column" scope="col">Taxonomy Name</th>
													<th style="" class="manage-column" scope="col">Built-in</th>
													<th style="" class="manage-column" scope="col">Selection</th>
												</tr>
												</thead>
												<tfoot>
												<tr>
													<th style="" class="manage-column" scope="col">Taxonomy Name</th>
													<th style="" class="manage-column" scope="col">Built-in</th>
													<th style="" class="manage-column" scope="col">Selection</th>
												</tr>
												</tfoot>
												<tbody>

												<?php
												foreach($taxonomies as $taxonomy){
													$tkey = $taxonomy->name;
													$tcurrentvalue = isset($wpcustomtaxfilterinadmin[$tkey]) ? $wpcustomtaxfilterinadmin[$tkey] : 0 ;
													?>

													<tr>
														<td><?php echo $taxonomy->labels->name; ?></td>
														<td><?php echo is_cbplgtaxbuiltin_customtaxfilterinadmin($taxonomy->_builtin); ?></td>
														<?php echo '<td><label for="tx'.$tkey.'"><input id="tx'.$tkey.'" type="checkbox" value="1" name="tx'.$tkey.'" '.checked('1', $tcurrentvalue, false).' /> Enable/Disable</label></td>';  ?>
													</tr>
												<?php  }  ?>

												</tbody>
											</table>
											<?php
											echo '</tr>';
										}
										?>

										<tr valign="top">
											<td></td>
											<td><input type="submit" name="uwpcustomtaxfilterinadmin" class="button-primary" value="Save Changes" ></td>
										</tr>
										</tbody>
									</table>
								</form>
							</div>
							<!-- .inside -->

						</div>
						<!-- .postbox -->

					</div>
					<!-- .meta-box-sortables .ui-sortable -->

				</div>
				<!-- post-body-content -->

				<!-- sidebar -->
				<div id="postbox-container-1" class="postbox-container">

					<div class="meta-box-sortables">

						<!-- <div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle">Plugin Info</h3>

							<div class="inside">
								<p><a href="http://wpboxr.com/product/custom-taxonomy-filter-in-wp-admin-post-listing" target="_blank">Name : CBX Custom Taxonomy Filter</a></p>

								<p>Author : WPBoxr Team</p>
								<p>Email : <a href="mailto:info@wpboxr.com" target="_blank">info@wpboxr.com</a></p>
								<p>Contact : <a href="http://wpboxr.com/contact-us" target="_blank">Contact Us</a></p>
							</div>
						</div> -->

						<!-- .postbox -->
						<!-- <div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="inside">WPBoxr on facebook</h3>
							<div class="inside">
								<iframe scrolling="no" frameborder="0" allowtransparency="true" style="border:none; overflow:hidden; width:260px; height:258px;" src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fwpboxr&amp;width=260&amp;height=258&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=false&amp;appId=558248797526834"></iframe>
							</div>
						</div> -->

						<!-- .postbox -->
						<!-- <div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="inside">WPBoxr on Twitter</h3>
							<div class="inside">
								<a class="twitter-timeline" href="https://twitter.com/wpboxr" data-widget-id="612564658164121602">Tweets by @wpboxr</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							</div>
						</div> -->

						<!-- <div class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><?php _e('Codeboxr Networks','wpfixedverticalfeedbackbutton') ?></h3>
							<div class="inside">

								<p><?php _e('Html, Wordpress & Joomla Themes','wpfixedverticalfeedbackbutton') ?></p>
								<a target="_blank" href="http://themeboxr.com"><img src="http://themeboxr.com/wp-content/themes/themeboxr/images/themeboxr-logo-rect.png" style="max-width: 100%;" alt="themeboxr" title="Themeboxr - useful themes"  /></a>
								<br/>
								<p><?php _e('Wordpress Plugins','wpfixedverticalfeedbackbutton') ?></p>
								<a target="_blank" href="http://wpboxr.com"><img src="http://wpboxr.com/wp-content/themes/themeboxr/images/wpboxr-logo-rect.png" style="max-width: 100%;" alt="wpboxr" title="WPBoxr - Wordpress Extracts"  /></a>
								<br/><br/>
								<p>Joomla Extensions</p>
								<a target="_blank" href="http://joomboxr.com"><img src="http://joomboxr.com/wp-content/themes/themeboxr/images/joomboxr-logo-rect.png" style="max-width: 100%;" alt="joomboxr" title="Joomboxr - Joomla Extracts"  /></a>

							</div>
						</div> -->

					</div>
					<!-- .meta-box-sortables -->

				</div>
				<!-- #postbox-container-1 .postbox-container -->

			</div>
			<!-- #post-body .metabox-holder .columns-2 -->

			<br class="clear">
		</div>
		<!-- #poststuff -->

	</div> <!-- .wrap -->

    <?php
}

//add plugin setting page link in plugin listing page
function add_wpcustomtaxfilterinadmin_settings_link( $links ) {
  $settings_link = '<a href="options-general.php?page=wpcustomtaxfilterinadmin">Settings</a>';
  array_unshift( $links, $settings_link );
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'add_wpcustomtaxfilterinadmin_settings_link' );


/**
 * Is builtin taxonomy
 *
 * @param string $flag
 * @return string
 */
function is_cbplgtaxbuiltin_customtaxfilterinadmin($flag = ''){
    if($flag == '1'){ return 'Yes';}
    else  return "No";
}


/**
 * Shows Taxnomy for post types that is allowed
 */
function todo_restrict_customtaxfilterinadmin_posts() {
    global $typenow;
    global $wpcustomtaxfilterinadmin;

    //var_dump($wptoolboxfromcodeboxr);
    //var_dump($typenow);

    $argsb   =   array( 'public' => true, '_builtin' => false );
    $argsc  =   array( 'public' => true, '_builtin' => true );
    $post_typesb        = get_post_types($argsb);
    $post_typesc        = get_post_types($argsc);
    $post_types         = array_merge($post_typesb, $post_typesc);

    if ( in_array($typenow, $post_types) && isset($wpcustomtaxfilterinadmin[$typenow]) &&  $wpcustomtaxfilterinadmin[$typenow] == '1') {
        $filter = get_object_taxonomies($typenow);
        //var_dump($filter);

        foreach ($filter as $tax_slug) {
            if($wpcustomtaxfilterinadmin[$tax_slug] == '1'){
                $tax_obj = get_taxonomy($tax_slug);
                wp_dropdown_categories(array(
                    'show_option_all'   => __('Show All '.$tax_obj->label ),
                    'taxonomy'          => $tax_slug,
                    'name'              => $tax_obj->name,
                    'orderby'           => 'name',
                    'selected'          => isset($_GET[$tax_obj->query_var]) ? $_GET[$tax_obj->query_var]: '',
                    'hierarchical'      => $tax_obj->hierarchical,
                    'show_count'        => true,
                    'hide_empty'        => false
                ));
            }//end if
        }//end foreach
    }
}


/**
 * Modify the query to get the custom taxonomy work in query
 *
 * @param $query
 */
function customtaxfilterinadmin_convert_restrict($query) {
    global $pagenow;
    global $typenow;
    global $wpcustomtaxfilterinadmin;





    if ($pagenow    == 'edit.php' && $typenow != null && isset($wpcustomtaxfilterinadmin[$typenow]) && $wpcustomtaxfilterinadmin[$typenow]== '1') {


        $filters = get_object_taxonomies($typenow);



        foreach ($filters as $tax_slug) {
            //var_dump($tax_slug);
            if($wpcustomtaxfilterinadmin[$tax_slug] == '1'){
                //var_dump($tax_slug);
                $var = &$query->query_vars[$tax_slug];
                //var_dump($var);
                if ( isset($var) && $var != 0 ) {
                    //var_dump($var);
                    $term = get_term_by('id',$var,$tax_slug);

                    $var = $term->slug;
                }
            }//end if
        }//end foreach

    }

}
function customtaxfilterinadmin_is_tax_on_post_search($query) {
    global $pagenow;
    $qv = &$query->query_vars;
    if ($pagenow == 'edit.php' && isset($qv['taxonomy']) && isset($qv['s'])) {
        $query->is_tax = true;
    }
}

add_action( 'restrict_manage_posts', 'todo_restrict_customtaxfilterinadmin_posts' );
add_filter('parse_query','customtaxfilterinadmin_convert_restrict');
add_filter('parse_query','customtaxfilterinadmin_is_tax_on_post_search');

?>