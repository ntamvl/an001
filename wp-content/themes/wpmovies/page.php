<?php get_header(); ?>
<?php include_once 'includes/header.php'; ?>
<?php include_once 'sidebar_left.php'; ?>
<div class="items ptts">
<div id="movie">
<?php include_once 'includes/aviso.php'; ?>

<div class="post page">
<?php if (current_user_can('update_core')) { ?>
<div class="menu-admin">
<ul>
<?php edit_post_link( __( 'Edit post', 'mundothemes' ), '<li>', '</li>' ); ?>
<li><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=wpmovies"><?php _e('Theme Settings','mundothemes'); ?></a></li>
<li><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=mundothemes"><?php _e('License Status','mundothemes'); ?></a></li>
<li class="right"><a href="https://mundothemes.com/forums/" target="_blank" class="right"><?php _e('Support Forums','mundothemes'); ?></a></li>
</ul>
</div> 
<?php } ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="datos">
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
</div>
<?php $active = get_option('activar-com-pages'); if ($active == "true") { include_once 'includes/single/comentarios.php'; } ?>
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