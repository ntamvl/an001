<?php get_header(); ?>
<?php include_once 'includes/header.php'; ?>
<?php include_once 'sidebar_left.php'; ?>
<div class="items ptts">
<div id="movie">
<?php include_once 'includes/aviso.php'; ?>
<div class="post page">
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="datos">
<h1><?php the_title(); ?></h1>
<div class="contenido_n">
<?php the_content(); ?>
</div>
<div class="pie">
<div class="date"><?php echo get_the_date(); ?></div>
<div class="tags"><?php the_tags('', ''); ?></div>
</div>
</div>
<?php social_botones(); ?>
<?php $active = get_option('activar-com-single'); if ($active == "true") { include_once 'includes/single/comentarios.php'; } ?>
<?php endwhile; ?>						
<?php else : ?>
<?php _e('No content available', 'mundothemes'); ?>
<?php endif; ?>
<?php drss_plus(); ?>
</div>
</div>
</div>
<?php include_once 'sidebar_right.php'; ?>
<?php get_footer(); ?>