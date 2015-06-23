<?php get_header(); ?>
<?php include_once 'includes/header.php'; ?>
<?php include_once 'sidebar_left.php'; ?>
<div class="items">
<div id="directorio">
<?php include_once 'includes/aviso.php'; ?>
<div class="it_header">
<h1><?php printf( __( '%s', 'mundothemes' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?></h1>
<?php if ( tag_description() ) : ?>
<span class="desc"><?php echo tag_description(); ?></span>
<?php endif; ?>
<div class="buscador">
<?php echo buscador_form(); ?>
</div>
</div>
<div id="box_movies">
<?php  if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); 
if (has_post_thumbnail()) {
$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'home');
$imgsrc = $imgsrc[0];
} elseif ($postimages = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=0")) {
foreach($postimages as $postimage) {
$imgsrc = wp_get_attachment_image_src($postimage->ID, 'home');
$imgsrc = $imgsrc[0];
}
} elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
$imgsrc = $match[1];
} else {
$img = get_post_custom_values("poster_url");
$imgsrc = $img[0];
} ?>
<div class="movie">
<div class="imagen">
<img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" />
<a href="<?php the_permalink() ?>"><span class="player"></span></a>
<?php if($values = get_post_custom_values("imdbRating")) { ?><div class="imdb"><span class="icon-grade"></span> <?php echo $values[0]; ?></div><?php } ?>
</div>
<h2><?php the_title(); ?></h2>
<?php if($mostrar = $terms = strip_tags( $terms = get_the_term_list( $post->ID, ''.$year_estreno.'' ))) {  ?><span class="year"><?php echo $mostrar; ?></span><?php } ?>
</div>
<?php endwhile; else : ?>
<div class="no_contenido_home"><?php if($tex = get_option('text-13')) { echo $tex; } else { _e('No content available', 'mundothemes'); } ?></div>
<?php endif; ?>		
</div>
<div id="paginador">
<div class="pages_respo">
<div class="anterior"><?php previous_posts_link( '<span class="icon-caret-left"></span> Anterior ' ); ?></div>
<div class="siguiente"><?php next_posts_link( 'Siguiente <span class="icon-caret-right"></span>' ); ?></div>
</div>
<?php $activar = get_option('activar-is'); if ($activar== "true") { } else { pagination($additional_loop->max_num_pages); } ?>
</div>
<?php drss_plus(); ?>
</div>
</div>
<?php include_once 'sidebar_right.php'; ?>
<?php get_footer(); ?>