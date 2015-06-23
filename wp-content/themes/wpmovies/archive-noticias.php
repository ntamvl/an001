<?php get_header(); ?>
<?php include_once 'includes/header.php'; ?>
<?php include_once 'sidebar_left.php'; ?>
<div class="items">
<div id="directorio">
<?php include_once 'includes/aviso.php'; ?>
      <div class="it_header">
        <h1><?php if($tex = get_option('text-5')) { echo $tex; } else { _e('News','mundothemes'); } ?></h1>
        <div class="buscador">
          <?php echo buscador_form(); ?>
        </div>
      </div>

    
<?php function_exists('wp_nav_menu') && has_nav_menu('menu1' ); wp_nav_menu( array( 'theme_location' => 'menu1', 'container' => '',  'menu_class' => 'home_links') ); ?>


<div id="noticias">
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<?php   if (has_post_thumbnail()) {
$imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
$imgsrc = $imgsrc[0];
} elseif ($postimages = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=0")) {
foreach($postimages as $postimage) {
$imgsrc = wp_get_attachment_image_src($postimage->ID, 'full');
$imgsrc = $imgsrc[0];
}
} elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
$imgsrc = $match[1];
} else {
$imgsrc = get_template_directory_uri() . '/images/index_noimagen.png';
} ?>
<div class="item_new">
<div class="imgen_n"><a href="<?php the_permalink() ?>"><img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" /></a></div>
<div class="detalles">
<h2><?php the_title(); ?></h2>
<span><?php echo get_the_date(); ?></span>
</div>
</div>
<?php endwhile; ?>						
<?php else : ?>
<div class="no_contenido_home"><?php if($tex = get_option('text-13')) { echo $tex; } else { _e('No content available', 'mundothemes'); } ?></div>
<?php endif; ?>
</div>



<div id="paginador">
<div class="pages_respo">
<div class="anterior"><?php previous_posts_link( '<span class="icon-caret-left"></span> '. _e("Anterior", "mundothemes") .' ' ); ?></div>
<div class="siguiente"><?php next_posts_link( ''. _e("Siguiente", "mundothemes") .' <span class="icon-caret-right"></span>' ); ?></div>
</div>
<?php $activar = get_option('activar-is'); if ($activar== "true") { } else { pagination($additional_loop->max_num_pages); } ?>
</div>

<?php drss_plus(); ?>
</div>
</div>
<?php include_once 'sidebar_right.php'; ?>
<?php get_footer(); ?>