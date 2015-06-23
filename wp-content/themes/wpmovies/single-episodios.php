<?php get_header(); ?>
<?php include_once 'includes/header-tv.php'; ?>
<!-- sidebar -->
<?php include_once 'sidebar_left_tv.php'; ?>
<div class="items ptts">
<!-- single -->
<div id="movie">
<?php include_once 'includes/aviso.php'; ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); 
$tiulo  = get_post_custom_values("titulo_serie");
$url    = get_post_custom_values("url_serie");
$fecha  = get_post_custom_values("fecha_serie");
$s      = get_post_custom_values("temporada_serie");
$ep     = get_post_custom_values("episodio_serie");
?>
<div class="post">
<div class="datos episodio">
<h1><?php the_title(); ?></h1>
<?php if($values = get_post_custom_values("titulo_serie")) { ?>
<p><span>S<?php echo $s[0]; ?></span> <span>Ep<?php echo $ep[0]; ?></span> <a href="<?php bloginfo('url'); ?>/<?php echo get_option('tvshows'); ?>/<?php echo $url[0]; ?>/"><?php echo $values[0]; ?></a> <i><?php echo $fecha[0]; ?></i></p>
<?php } ?>
<?php the_content(); ?>
</div>
<?php include_once 'includes/single/player_tv.php'; ?>
<div class="datos">
<div class="responsive">
<div class="alerta"><b class="icon-screen-rotation"></b></div>
<div class="contenido">
<?php enalces_verenlinea(); ?>
<?php enlaces_descargas(); ?>
</div>
</div>
</div>
<!-- botones sociales -->
<?php social_botones(); ?>
<!-- comentarios -->
<?php $active = get_option('activar-com-single'); if ($active == "true") { include_once 'includes/single/comentarios.php'; } ?>
<?php drss_plus(); ?>
</div>
<!-- post -->
<?php endwhile; ?>						
<?php else : ?>
<div class="no_data"><?php _e('No content available', 'mundothemes'); ?></div>
<?php endif; ?>
</div>
</div>
<!-- sidebar -->
<?php include_once 'sidebar_single_tv.php'; ?>
<!-- footer -->
<?php get_footer(); ?>