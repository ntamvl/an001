<div style="display:none">
<?php
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "new_post") {
if (isset ($_POST['title'])) {
$title =  $_POST['title'];
} else {
echo _('Please enter the wine name','mundothemes');
}
if (isset ($_POST['description'])) {
$description = $_POST['description'];
} else {
echo _('Please enter some notes','mundothemes');
}
$tags = $_POST['post_tags'];
# Registrar campo personalizado
$imdb = $_POST['Checkbx2']; #####################
# Agregar elementos a nuevo post
$new_post = array(
    'post_title'    =>   $title,
    'post_content'  =>   $description,
    'post_category' =>   array($_POST['cat']), #activar taxonomia de categoria.
    'tags_input'    =>   array($tags),
    'post_status'   =>   'draft', # Elejir estado de publicacion: publish, preview, future, draft, etc.
    'post_type' =>   'post' # Elegir post_type
);
# Guardar Publicacion
$pid = wp_insert_post($new_post);
# Agregando custom field
add_post_meta($pid, 'Checkbx2', $imdb, true); #####################
# Insertando etiquetas
wp_set_post_tags($pid, $_POST['post_tags']);
# Redireccionar
wp_redirect(home_url());
} 
# Agregando Imagen
if ($_FILES) {
array_reverse($_FILES);
$i = 0;
foreach ($_FILES as $file => $array) {
if ($i == 0) $set_feature = 1; 
else $set_feature = 0; 
$newupload = insert_attachment($file,$pid, $set_feature);
 $i++; } } 
# Insertando publicacion a la base de datos.
do_action('wp_insert_post', 'wp_insert_post');
# Fin de funcion.
?>
</div>