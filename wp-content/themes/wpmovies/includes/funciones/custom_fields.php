<?php
$sp_boxes = array ( 
__('Complete data imdb', 'mundothemes') => array (
/* Agregar codigo IMDb */
array( 'Checkbx2', __('Assign ID IMDb, example (http://www.imdb.com/title/tt0120338/) = <b>tt0120338</b><script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>', 'mundothemes') ),
array( 'Checkbx', __('Put the cursor in the text box', 'mundothemes'), 'button' )	
),
__('Trailer / Images', 'mundothemes') => array (
array( 'Trailer', __('Trailer', 'mundothemes'), 'textarea' ),
array( 'imagenes', __('Backdrops - Place each image url below another', 'mundothemes'), 'textarea' )
),
/* Datos extraidos de IMDb */
__('Players', 'mundothemes') => array (
array( 'embed_pelicula', __('Option # 1 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro1', __('Title Option # 1 player', 'mundothemes') ),
array( 'embed_pelicula2', __('<hr><BR><BR> Option # 2 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro2', __('Title Option # 2 player', 'mundothemes') ),
array( 'embed_pelicula3', __('<hr><BR><BR> Option # 3 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro3', __('Title Option # 3 player', 'mundothemes') ),
array( 'embed_pelicula4', __('<hr><BR><BR> Option # 4 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro4', __('Title Option # 4 player', 'mundothemes') ),
array( 'embed_pelicula5', __('<hr><BR><BR> Option # 5 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro5', __('Title Option # 5 player', 'mundothemes') ),
array( 'embed_pelicula6', __('<hr><BR><BR> Option # 6 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro6', __('Title Option # 6 player', 'mundothemes') ),
array( 'embed_pelicula7', __('<hr><BR><BR> Option # 7 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro7', __('Title Option # 7 player', 'mundothemes') ),
array( 'embed_pelicula8', __('<hr><BR><BR> Option # 8 - HTML code', 'mundothemes'), 'textarea' ),
array( 'titulo_repro8', __('Title Option # 8 player', 'mundothemes') ),
),
__('Downloads', 'mundothemes') => array (
array( 'descargas_link', __('HTML Code links downloads', 'mundothemes'), 'textarea' ),
),
);
add_action( 'admin_menu', 'sp_add_custom_box' );
add_action( 'save_post', 'sp_save_postdata', 1, 2 );
function sp_add_custom_box() {
global $sp_boxes;
if ( function_exists( 'add_meta_box' ) ) {
foreach ( array_keys( $sp_boxes ) as $box_name ) {
add_meta_box( $box_name, __( $box_name, 'sp' ), 'sp_post_custom_box', 'post', 'normal', 'high' );
add_meta_box( $box_name, __( $box_name, 'sp' ), 'sp_post_custom_box', 'tvshows', 'normal', 'high' );
} } }
function sp_post_custom_box ( $obj, $box ) {
global $sp_boxes;
static $sp_nonce_flag = false;
if ( ! $sp_nonce_flag ) {
echo_sp_nonce();
$sp_nonce_flag = true;
}
foreach ( $sp_boxes[$box['id']] as $sp_box ) {
echo field_html( $sp_box );
} }
function field_html ( $args ) {
switch ( $args[2] ) {
case 'textarea':
return text_area( $args );
case 'checkbox':
case 'radio':
case 'button':
case 'text':
return text_button( $args );
case 'submit':
default:
return text_field( $args );
} }
function text_field ( $args ) {
global $post;
$args[2] = get_post_meta($post->ID, $args[0], true);
$args[1] = __($args[1], 'sp' );
$label_format =
'<label for="%1$s">%2$s</label><br />'
. '<input style="width: 95%%;" type="text" name="%1$s" value="%3$s" /><br /><br />';
return vsprintf( $label_format, $args );
}
function text_button ( $args ) {
$label_format = '<input type="button" style="cursor:pointer;" name="Checkbx" value="'.__("Generate data from IMDb","mundothemes").'" /><br /><br />';
return vsprintf( $label_format, $args );
}
function text_area ( $args ) {
global $post;
$args[2] = get_post_meta($post->ID, $args[0], true);
$args[1] = __($args[1], 'sp' );
$label_format =
'<label for="%1$s">%2$s</label><br />'
. '<textarea style="width: 95%%;" name="%1$s">%3$s</textarea><br /><br />';
return vsprintf( $label_format, $args );
}
function sp_save_postdata($post_id, $post) {
global $sp_boxes;
if ( ! wp_verify_nonce( $_POST['sp_nonce_name'], plugin_basename(__FILE__) ) ) {
return $post->ID; }
if ( 'page' == $_POST['post_type'] ) {
if ( ! current_user_can( 'edit_page', $post->ID ))
return $post->ID;
} else {
if ( ! current_user_can( 'edit_post', $post->ID ))
return $post->ID; }
foreach ( $sp_boxes as $sp_box ) {
foreach ( $sp_box as $sp_fields ) {
$my_data[$sp_fields[0]] =  $_POST[$sp_fields[0]];
} }
foreach ($my_data as $key => $value) {
if ( 'revision' == $post->post_type  ) {
return; }
$value = implode(',', (array)$value);
if ( get_post_meta($post->ID, $key, FALSE) ) {
update_post_meta($post->ID, $key, $value);
} else {
add_post_meta($post->ID, $key, $value);
}
if (!$value) {
delete_post_meta($post->ID, $key);
} } }
function echo_sp_nonce () {
echo sprintf(
'<input type="hidden" name="%1$s" id="%1$s" value="%2$s" />',
'sp_nonce_name',
wp_create_nonce( plugin_basename(__FILE__) )
);
}
if ( !function_exists('get_custom_field') ) {
function get_custom_field($field) {
global $post;
$custom_field = get_post_meta($post->ID, $field, true);
echo $custom_field; } }
function logo_admin_wpmovies() {  ?>
<?php  } 
add_action('login_head', 'logo_admin_wpmovies');
function core_grifus() {
}
add_action('admin_footer', 'core_grifus');
function custom_admin_js() {
?>

<script>
	$('input[name=Checkbx]').click(function() {
	var coc = $('input[name=Checkbx2]').get(0).value;
    $.getJSON("http://www.omdbapi.com/?plot=full&r=json&i=" + coc, function(data) {
	    var valDir = "";
		var valWri = "";
		var valAct = "";
		$.each(data, function(key, val) {
			  $('input[name=' +key+ ']').val(val);
			  $('textarea[name=' +key+ ']').val(val); 
			  if(key == "Director"){
				valDir+= " "+val+",";
			  }
			  if(key == "Writer"){
				valWri+= " "+val+",";
			  }
			  if(key == "Actors"){
				valAct+= " "+val+",";
			  }
			  if(key == "Year"){
				$('#new-tag-<?php echo get_option('year'); ?>').val(val);
			  }
		});
		$('#new-tag-<?php echo get_option('director'); ?>').val(valDir);
		$('#new-tag-<?php echo get_option('escritor'); ?>').val(valWri);
		$('#new-tag-<?php echo get_option('actor'); ?>').val(valAct);
		alert('<?php echo __( 'Data have been generated correctly', 'mundothemes' ); ?>');
	}); 
});
</script> 
<?php
}
add_action('admin_footer', 'custom_admin_js');