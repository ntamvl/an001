<?php
# formulario de busqueda
function buscador_form_home() { ?>
<form method="get" id="searchform" action="<?php bloginfo("url"); ?>">
<input type="text" name="s" id="s" placeholder="<?php if($tex = get_option('text-6')) { echo $tex; } else { _e('Search..', 'mundothemes'); } ?>" value="<?php echo $_GET['s'] ?>">
</form>
<?php }
function buscador_form() { ?>
<form method="get" id="searchform" action="">
<input type="text" name="s" id="s" placeholder="<?php if($tex = get_option('text-6')) { echo $tex; } else { _e('Search..', 'mundothemes'); } ?>" value="<?php echo $_GET['s'] ?>">
</form>
<?php }
# Funcion de ennumerado de categorias.
function categorias() {
$args = array('hide_empty' => FALSE, 'title_li'=> __( '' ), 'show_count'=> 1, 'echo' => 0 );
$links = wp_list_categories($args);
$links = str_replace('</a> (', '</a> <i>', $links);
$links = str_replace(')', '</i>', $links);
echo $links;
}
function categorias_tv() {
$post_type		= 'tvshows';
$taxonomy		= 'tvshows_categories';
$orderby		= 'ASC';
$show_count		= 1;
$hide_empty		= false;
$pad_counts		= 0;
$hierarchical	        = 1;
$exclude			= '55';
$title				= '';
$args = array(
'post_type'		=> $post_type,
'taxonomy'		=> $taxonomy,
'orderby'			=> $orderby,
'show_count'		=> $show_count,
'hide_empty'		=> $hide_empty,
'pad_counts'		=> $pad_counts,
'hierarchical'	    => $hierarchical,
'exclude'			=> $exclude,
'title_li'			=> $title,
'echo' => 0
);
$links_tv = wp_list_categories($args);
$links_tv = str_replace('</a> (', '</a> <i>', $links_tv);
$links_tv = str_replace(')', '</i>', $links_tv);
echo $links_tv;
}

# Funcion de paginador.
function pagination($pages = '', $range = 2) {
$showitems = ($range * 2)+1;
global $paged; if(empty($paged)) $paged = 1;
if($pages == '') {
global $wp_query; $pages = $wp_query->max_num_pages;
if(!$pages){ $pages = 1; }
}
if(1 != $pages) {
echo "<div class='paginado'>";
if($paged > 2 && $paged > $range+1 && $showitems < $pages)
echo "<a class=previouspostslink' rel='nofollow' href='".get_pagenum_link(1)."'><span class='icon-angle-double-left'></span>".__( '', 'mundothemes' )."</a>";
if($paged > 1 && $showitems < $pages)
echo "<a class=previouspostslink' rel='nofollow' href='".get_pagenum_link($paged - 1)."'><span class='icon-chevron-left2'></span>".__( '', 'mundothemes' )."</a>";
for ($i=1; $i <= $pages; $i++){
if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a rel='nofollow' class='page larger' href='".get_pagenum_link($i)."'>".$i."</a>";
} }
if ($paged < $pages && $showitems < $pages)
echo "<a rel='nofollow' class=previouspostslink href='".get_pagenum_link($paged + 1)."'>".__( '', 'mundothemes' )."<span class='icon-chevron-right2'></span></a>";
if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
echo "<a rel='nofollow' class=previouspostslink2 href='".get_pagenum_link($pages)."'>".__( '', 'mundothemes' )."<span class='icon-angle-double-right'></span></a>";
echo "</div>";
} }

# Cargando las imagenes a Wordpress.
function insert_attachment($file_handler,$post_id,$setthumb='false') {
if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK){ return __return_false();
}
require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');
echo $attach_id = media_handle_upload( $file_handler, $post_id );
if ($setthumb == 1) update_post_meta($post_id,'_thumbnail_id',$attach_id);
return $attach_id;
}
# Filtrar resultados de busqueda.
function fb_search_filter($query) {
if ( !$query->is_admin && $query->is_search) {
$query->set('post_type', array('post','tvshows') );
} return $query; }
add_filter( 'pre_get_posts', 'fb_search_filter' );
# Generar lista de imagenes.
function backdrops($imagen){
	$val = str_replace(array("http","jpg","png","gif"),array('<div class="item"><img class="lazyOwl" data-src="http','jpg" alt="'.get_the_title().'" /></div>','png" /></div>','gif" /></div>'),$imagen);
	echo $val;
}
# No remover
function drss_plus() { ?>
<div class="footer_c">
<a href="#" class="top" id="arriba"><b class="icon-chevron-up"></b></a>
<span class="copy">
<B><?php bloginfo('name'); ?></B> &copy; <?php echo date ("Y"); ?> <?php _e('All rights reserved', 'mundothemes'); ?>
</span>
<?php if($copy = get_option('brand')) { ?>
<span class="brand">
<?php echo stripslashes($copy); ?>
</span>
<?php } else { ?>
<span class="brand">
<?php echo 'Powered by Cartoon2Watch'; ?>
<!-- <A HREF="https://<?php echo mt_cms_url; ?>/" target="_blank"><B><?php echo mt_cms; ?></B></A> &
<A HREF="https://<?php echo mt_repositorio; ?>/" target="_blank"><B><?php echo mt_name; ?></B></A> WPMovies<?php echo mt_version; ?> -->
</span>
<?php } ?>
</div>
<?php }
# Formulario
function agregar_pelicula() { ?>
<div class="post_nuevo">
<form id="new_post" name="new_post" method="post" action="<?php echo get_option('add_movie'); ?>?mt=posting" class="posting" enctype="multipart/form-data">
<fieldset>
<input class="caja titulo" type="text" id="title" maxlength="50" name="title" placeholder="<?php if($tex = get_option('text-20')) { echo $tex; } else { _e('Original title','mundothemes'); } ?>" required>
<span class="tip"><?php if($tex = get_option('text-21')) { echo $tex; } else { _e('Add title of the movie.','mundothemes'); } ?></span>
</fieldset>
<!-- #### - Editor Avanzado WordPress - #### -->
<?php $activar = get_option('activar-editor'); if ($activar == "true") { # activacion de editor avanzado ?>
<fieldset>
<div class="movie-editor-mt">
<?php $editor_texo = "Synopsis"; wp_editor($editor_texto,"description", array('textarea_rows'=>10, 'editor_class'=>'resumen', )); ?>
</div>
<span class="tip">
<?php if($tex = get_option('text-22')) { echo $tex; } else { _e('Add an abstract of no more than 1000 characters of the synopsis or plot.','mundothemes'); } ?>
</span>
</fieldset>
<?php } else { ?>
<fieldset>
<textarea class="resumen" id="description"  maxlength="1000" name="description"  placeholder="<?php if($tex = get_option('text-28')) { echo $tex; } else { _e('Synopsis','mundothemes'); } ?>" required></textarea>
<span  class="tip">
<?php if($tex = get_option('text-22')) { echo $tex; } else { _e('Add an abstract of no more than 1000 characters of the synopsis or plot.','mundothemes'); } ?>
</span>
</fieldset>
<?php } ?>
<!-- #### -->
<fieldset>
<input class="caja" type="text" id="Checkbx2" maxlength="9" name="Checkbx2" placeholder="<?php if($tex = get_option('text-23')) { echo $tex; } else { _e('IMDb id','mundothemes'); } ?>" required>
<span class="tip"><a href="http://imdb.com" target="_blank"><strong>IMDb</strong></a> - <?php if($tex = get_option('text-24')) { echo $tex; } else { _e('Assign ID IMDb, example URL = http://www.imdb.com/title/<i>tt0120338</i>/','mundothemes'); } ?></span>
</fieldset>
<!-- #### -->
<fieldset>
<input type="file" class="custom-file-input" name="file" id="file" accept="image/jpg, image/png, image/gif, image/jpeg" required>
<span class="tip"><?php if($tex = get_option('text-25')) { echo $tex; } else { _e('Upload poster image.','mundothemes'); } ?></span>
</fieldset>
<fieldset>
<?php $select_cats = wp_dropdown_categories( array( 'echo' => 0 ) ); $select_cats = str_replace( 'id=', 'multiple="multiple" id=', $select_cats ); echo $select_cats; ?>
<span class="tip"><?php if($tex = get_option('text-26')) { echo $tex; } else { _e('Select main genres of film.','mundothemes'); } ?></span>
</fieldset>
<!-- #### -->
<fieldset class="captcha_s">
<div class="g-recaptcha" data-sitekey="<?php echo get_option('public_key_rcth') ?>"></div>
</fieldset>
<!-- #### -->
<fieldset>
<input class="boton" type="submit" value="<?php if($tex = get_option('text-27')) { echo $tex; } else { _e('Send content','mundothemes'); } ?>" id="submit" name="submit" required>
</fieldset>
<!-- #### -->
<input type="hidden" name="action" value="new_post" />
<?php wp_nonce_field( 'new-post' ); ?>
</form>
</div>
<?php
}
function css_theme() {
$active = get_option('activar-main-color'); if ($active == "true") {
$alfa = get_option('color_alfa');
$alfa_bg = get_option('color_alfa_bg');
$focus = get_option('input_focus');
?>
<style>
#largo .contenido {
background: #<?php echo $alfa_bg; ?>;
}
#largo .contenido .bajon {
background: #<?php echo $alfa_bg; ?>;
}
#largo .contenido .header .data ul li.activo, li.most_rated, li.views {
border-left: 4px solid #<?php echo $alfa; ?>!important;
}
#largo .contenido .header .data ul li.main:hover {
border-left: 4px solid #<?php echo $alfa; ?>;
}
#largo .contenido .header .data ul.generos li.current-cat:before {
color: #<?php echo $alfa; ?>;
}
ul.leftmenu li.current-menu-item a i {
color: #<?php echo $alfa; ?>;
}
a.agregar-movie:hover {
background: #<?php echo $alfa; ?>;
}
#largo .contenido .header input[type='text']:focus {
border: 1px solid #<?php echo $focus; ?>;
}
#contenedor .items .it_header .buscador input[type='text']:focus {
border: 1px solid #<?php echo $focus; ?>;
}
.post_nuevo form.posting fieldset .caja:focus {
border: 1px solid #<?php echo $focus; ?>;
}
.post_nuevo form.posting fieldset .resumen:focus {
border: 1px solid #<?php echo $focus; ?>;
}
.post_nuevo form.posting fieldset .postform:focus {
border: 1px solid #<?php echo $focus; ?>;
}
</style>
<?php } ?>
<?php $activar = get_option('activar_css'); if ($activar == "true") {
$css = get_option('code_css');if(!empty($css)){ echo '<style>'.$css.'</style>'; }
} }
function javascript_theme() {
?>
 <script>
    $(function() {
        var btn_movil = $('#nav-mobile'),
        menu = $('#menu-resp').find('ul');
        btn_movil.on('click', function (e) {
            e.preventDefault();
            var el = $(this);
            el.toggleClass('nav-active');
            menu.toggleClass('abrir');
        })
    });
</script>
<?php }
function enlaces_descargas() {
	$activar = get_option('activar-descargas'); if ($activar== "true") {
if( have_rows('ddw') ): ?>
<div class="enlaces_box">
<ul class="enlaces">
<li class="elemento header">
<span class=""><?php if($tex = get_option('text-31')) { echo $tex; } else { _e('Download Links','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
<span class=""><?php if($tex = get_option('text-33')) { echo $tex; } else { _e('Server','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
<span class=""><?php if($tex = get_option('text-34')) { echo $tex; } else { _e('Audio / Language','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
<span class=""><?php if($tex = get_option('text-35')) { echo $tex; } else { _e('Quality','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
</li>
</ul>
<ul class="enlaces">
<?php  $numerado = 1; { while( have_rows('ddw') ): the_row(); ?>
<a href="<?php echo get_sub_field('op1'); ?>" target="_blank">
<li class="elemento">
<span class="a"><b class="icon-get-app"></b> <?php if($tex = get_option('text-36')) { echo $tex; } else { _e('Option','mundothemes'); } ?> <?php echo $numerado; ?></span>
<span class="b">
<img src="http://www.google.com/s2/favicons?domain=<?php echo get_sub_field('op2'); ?>" alt="<?php echo get_sub_field('op2'); ?>">
<?php echo get_sub_field('op2'); ?>
</span>
<span class="c"><?php echo get_sub_field('op3'); ?></span>
<span class="d"><?php echo get_sub_field('op4'); ?></span>
</li>
</a>
<?php $numerado++; ?>
<?php endwhile; } ?>
</ul>
</div>
<?php else : ?>
<div class="no_link"><b class="icon-get-app"></b> <?php if($tex = get_option('text-37')) { echo $tex; } else { _e('No links available','mundothemes'); } ?></div>
<?php endif;
	}
}
function enalces_verenlinea() {
	$activar = get_option('activar-descargas'); if ($activar== "true") {
if( have_rows('voo') ): ?>
<div class="enlaces_box">
<ul class="enlaces">
<li class="elemento header">
<span class=""><?php if($tex = get_option('text-32')) { echo $tex; } else { _e('View Online','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
<span class=""><?php if($tex = get_option('text-33')) { echo $tex; } else { _e('Server','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
<span class=""><?php if($tex = get_option('text-34')) { echo $tex; } else { _e('Audio / Language','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
<span class=""><?php if($tex = get_option('text-35')) { echo $tex; } else { _e('Quality','mundothemes'); } ?> <i class="icon-caret-down"></i></span>
</li>
</ul>
<ul class="enlaces">
<?php  $numerado = 1; { while( have_rows('voo') ): the_row(); ?>
<a href="<?php echo get_sub_field('op1'); ?>" target="_blank">
<li class="elemento">
<span class="a"><b class="icon-play-circle-outline play"></b> <?php if($tex = get_option('text-36')) { echo $tex; } else { _e('Option','mundothemes'); } ?> <?php echo $numerado; ?></span>
<span class="b">
<img src="http://www.google.com/s2/favicons?domain=<?php echo get_sub_field('op2'); ?>" alt="<?php echo get_sub_field('op2'); ?>">
<?php echo get_sub_field('op2'); ?>
</span>
<span class="c"><?php echo get_sub_field('op3'); ?></span>
<span class="d"><?php echo get_sub_field('op4'); ?></span>
</li>
</a>
<?php $numerado++; ?>
<?php endwhile; } ?>
</ul>
</div>
<?php else : ?>
<div class="no_link"><b class="icon-play-circle-outline play"></b> <?php if($tex = get_option('text-37')) { echo $tex; } else { _e('No links available','mundothemes'); } ?></div>
<?php endif;
	}
}
function social_botones() { ?>
<div class="soci">
<a class="fb" href="javascript: void(0);" onclick="window.open ('http://www.facebook.com/sharer.php?u=<?php the_permalink() ?>', 'Facebook', 'toolbar=0, status=0, width=650, height=450');"><b class="icon-facebook3"></b> <?php if($tex = get_option('text-39')) { echo $tex; } else { _e('Share','mundothemes'); } ?></a>
<a class="tw" href="javascript: void(0);" onclick="window.open ('https://twitter.com/intent/tweet?text=<?php the_title(); ?>&url=<?php the_permalink() ?>', 'Twitter', 'toolbar=0, status=0, width=650, height=450');" data-rurl="<?php the_permalink() ?>"><b class="icon-twitter3"></b> <?php if($tex = get_option('text-40')) { echo $tex; } else { _e('Tweet','mundothemes'); } ?></a>
</div>
<?php }
function tvshows_ul() { ?>
<?php if( have_rows('seasons') ): ?>
<div id='cssmenu'>
<ul>
    <?php   $numerado = 1; { while( have_rows('seasons') ): the_row(); ?>
	<li class='has-sub'><a href='#'><span><b class="icon-bars"></b> <?php if($tex = get_option('text-41')) { echo $tex; } else { _e('Season','mundothemes'); } ?> <?php echo $numerado; ?></span></a>
	     <ul>
		 <?php if( have_rows('episode') ): ?>
		 <?php $numerado2 = 1; { while( have_rows('episode') ): the_row(); ?>
		 <li>
		 <?php if($data = get_sub_field('url_tvshows')) { ?>
		 <a href="<?php bloginfo('url'); ?>/<?php echo get_option('episode'); ?>/<?php echo $data; ?>/" target="_blank">
		 <?php } else { ?>
		 <a>
		 <?php } ?>
		 <span class="datex"><?php echo $numerado; ?> - <?php echo $numerado2; ?></span>
		 <span class="datix"><b class="icon-chevron-right"></b>
		 <?php if($dato = get_sub_field('title_tvshows')) { ?>
		 <?php echo $dato; ?>
		 <?php } else { ?>
		 <?php if($tex = get_option('text-42')) { echo $tex; } else { _e('Episode','mundothemes'); } ?> <?php echo $numerado2; ?>
		 <?php } ?>
		 </span>
		 <i><b class="icon-query-builder"></b>
		 <?php if($dato = get_sub_field('runtime_tvshows')){ ?>
		 <?php echo $dato; ?>
		 <?php } else { ?>
		 <?php $values = get_post_custom_values("Runtime"); echo $values[0]; ?>
		 <?php } ?>
		 </i>
		 </a>
		 </li>
		 <?php $numerado2++; ?>
		 <?php endwhile; } ?>
		 <?php else : ?>
		 <li><a>
		 <span class="datex"><?php echo $numerado; ?> - 0</span>
		 <span class="datix"><b class="icon-chevron-right"></b> <?php if($tex = get_option('text-43')) { echo $tex; } else { _e('No episodes','mundothemes'); } ?></span>
		 </a></li>
		 <?php endif; ?>
         </ul>
     </li>
	  <?php $numerado++; ?>
    <?php endwhile; } ?>
</ul>
</div>
<?php else : ?>
<div class="datos">
<div class="no_link"><b class="icon-play-circle-outline play"></b> <?php if($tex = get_option('text-44')) { echo $tex; } else { _e('No seasons','mundothemes'); } ?></div>
</div>
<?php endif; ?>
<?php }

// BEGIN MOVIE EP
function tvshows_ul_2() { ?>
<?php if( have_rows('seasons') ): ?>
<div id='cssmenu'>
<ul>
    <?php   $numerado = 1; { while( have_rows('seasons') ): the_row(); ?>
  <li class='has-sub' id="ep_ul">
    <a href='#'><span><b id="ep_icon_single" class="icon-bars"></b> <?php if($tex = get_option('text-41')) { echo $tex; } else { _e('Episode list','mundothemes'); } ?> <?php //echo $numerado; ?></span></a>
     <ul>
     <?php if( have_rows('episode') ): ?>
     <?php $numerado2 = 1; { while( have_rows('episode') ): the_row(); ?>
     <li>
     <?php if($data = get_sub_field('url_tvshows')) { ?>
     <a href="<?php bloginfo('url'); ?>/<?php echo get_option('episode'); ?>/<?php echo $data; ?>/" target="_blank">
     <?php } else { ?>
     <a>
     <?php } ?>
     <span class="datex"><?php echo $numerado; ?> - <?php echo $numerado2; ?></span>
     <span class="datix"><b class="icon-chevron-right"></b>
     <?php if($dato = get_sub_field('title_tvshows')) { ?>
     <?php echo $dato; ?>
     <?php } else { ?>
     <?php if($tex = get_option('text-42')) { echo $tex; } else { _e('Episode','mundothemes'); } ?> <?php echo $numerado2; ?>
     <?php } ?>
     </span>
     <i><b class="icon-query-builder"></b>
     <?php if($dato = get_sub_field('runtime_tvshows')){ ?>
     <?php echo $dato; ?>
     <?php } else { ?>
     <?php $values = get_post_custom_values("Runtime"); echo $values[0]; ?>
     <?php } ?>
     </i>
     </a>
     </li>
     <?php $numerado2++; ?>
     <?php endwhile; } ?>
     <?php else : ?>
     <li><a>
     <span class="datex"><?php echo $numerado; ?> - 0</span>
     <span class="datix"><b class="icon-chevron-right"></b> <?php if($tex = get_option('text-43')) { echo $tex; } else { _e('No episodes','mundothemes'); } ?></span>
     </a></li>
     <?php endif; ?>
         </ul>
     </li>
    <?php $numerado++; ?>
    <?php endwhile; } ?>
</ul>
</div>
<?php else : ?>
<div class="datos">
<div class="no_link"><b class="icon-play-circle-outline play"></b> <?php if($tex = get_option('text-44')) { echo $tex; } else { _e('No seasons','mundothemes'); } ?></div>
</div>
<?php endif; ?>
<?php }
// END MOVIE EP

function relacionados() { ?>
<?php
 // Articulos Recomendados
$cat = get_the_category();
$cat = $cat[0];
$cat = $cat->cat_ID;
$post = get_the_ID();
$args = array('cat'=>$cat, 'orderby' => 'rand', 'showposts' => 20,'post__not_in' => array($post));
$related = new WP_Query($args);
if($related->have_posts()) {
	echo '<div class="scrolling relacionados">';
while($related->have_posts()) : $related->the_post();
if (has_post_thumbnail()) {
$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'thumbnail');
$imgsrc = $imgsrc[0];
} elseif ($postimages = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=0")) {
foreach($postimages as $postimage) {
$imgsrc = wp_get_attachment_image_src($postimage->ID, 'thumbnail');
$imgsrc = $imgsrc[0];
}
} elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
$imgsrc = $match[1];
} else {
$imgsrc = get_template_directory_uri() . '/images/no_image.png';
} ?>
<a href="<?php the_permalink() ?>">
<div class="movie-r">
<div class="image-r"><img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" /></div>
<div class="data-r">
<h4><?php the_title(); ?></h4>
<?php if($values = get_post_custom_values("imdbRating")) { ?><span class="rating"><?php echo $values[0]; ?></span><?php } ?>
<?php if($values = get_post_custom_values("Runtime")) { ?><span class="rating-b"><b class="icon-query-builder"></b> <?php echo $values[0]; ?></span><?php } ?>
</div>
</div>
</a>
<?php endwhile; ?>
</div>
<?php } wp_reset_query(); ?>
<?php }
function relacionados_tv() { ?>
<?php
 // Articulos Recomendados
$cat = get_the_category();
$cat = $cat[0];
$cat = $cat->cat_ID;
$post = get_the_ID();
$args = array('post_type' => 'tvshows', 'orderby' => 'rand', 'showposts' => 20,'post__not_in' => array($post));
$related = new WP_Query($args);
if($related->have_posts()) {
	echo '<div class="scrolling relacionados">';
while($related->have_posts()) : $related->the_post();
if (has_post_thumbnail()) {
$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'thumbnail');
$imgsrc = $imgsrc[0];
} elseif ($postimages = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=0")) {
foreach($postimages as $postimage) {
$imgsrc = wp_get_attachment_image_src($postimage->ID, 'thumbnail');
$imgsrc = $imgsrc[0];
}
} elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
$imgsrc = $match[1];
} else {
$imgsrc = get_template_directory_uri() . '/images/noimagen_single.png';
} ?>
<a href="<?php the_permalink() ?>">
<div class="movie-r">
<div class="image-r"><img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" /></div>
<div class="data-r">
<h4><?php the_title(); ?></h4>
<?php if($values = get_post_custom_values("imdbRating")) { ?><span class="rating"><?php echo $values[0]; ?></span><?php } ?>
<?php if($values = get_post_custom_values("imdbVotes")) { ?><span class="rating-b"><b class="icon-thumb-up"></b> <?php echo $values[0]; ?></span><?php } ?>
</div>
</div>
</a>
<?php endwhile; ?>
</div>
<?php } wp_reset_query(); ?>
<?php }