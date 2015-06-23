<?php 
/*
Template Name: TOP average
*/
get_header(); ?>
<?php include_once 'includes/header.php'; ?>
<?php include_once 'sidebar_left.php'; ?>
<div class="items">
<div id="directorio">
<?php include_once 'includes/aviso.php'; ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<div class="place_top">
<h1><span><?php the_title(); ?></span></h1>
<?php the_content(); ?>
</div>
<?php endwhile; ?>						
<?php else : ?>
<?php _e('No content available', 'mundothemes'); ?>
<?php endif; ?>





<div class="header_slider">
<span class="titulo_2"><b class="icon-trophy"></b> <?php echo _e('TOP movies','mundothemes'); ?></span>
<div class="customNavigation">
<a class="btn prev"><b class="icon-chevron-left2"></b></a>
<a class="btn next"><b class="icon-chevron-right2"></b></a>
</div>
</div>
<div class="random">
<?php include_once 'includes/top/movies.php'; ?>
</div>




<div class="header_slider">
<span class="titulo_2"><b class="icon-trophy"></b> <?php echo _e('TOP TVShows','mundothemes'); ?></span>
<div class="customNavigation">
<a class="btn prevs"><b class="icon-chevron-left2"></b></a>
<a class="btn nexts"><b class="icon-chevron-right2"></b></a>
</div>
</div>
<div class="random">
<?php include_once 'includes/top/tvshows.php'; ?>
</div>



<?php drss_plus(); ?>
</div>
</div>
<?php include_once 'sidebar_right.php'; ?>
<?php get_footer(); ?>