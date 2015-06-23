<div class="links">
<h3><?php if($tex = get_option('text-12')) { echo $tex; } else { _e('20 Most voted','mundothemes'); } ?> <span class="icon-sort"></span></h3>
<ul class="scrolling lista">
<?php $numerado = 1; { query_posts(
array('meta_key' => 'end_time','meta_compare' =>'>=','meta_value'=>time(),'meta_key' => 'imdbRating',
'post__not_in' => get_option( 'sticky_posts' ),'orderby' => 'meta_value_num','showposts' => '20','order' => 'DESC'));
while ( have_posts() ) : the_post(); 
$imdbRating = get_post_meta($post->ID, "imdbRating", $single = true); ?>
<li>
<b><?php echo $numerado; ?></b> 
<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
<span><?php $values = get_post_custom_values("imdbRating"); echo $values[0]; ?></span> 
<?php if($mostrar = $terms = strip_tags( $terms = get_the_term_list( $post->ID, ''.$year_estreno.'' ))) {  ?><i><?php echo $mostrar; ?></i><?php } ?>
</li>
<?php $numerado++; ?>
<?php endwhile; wp_reset_query(); ?>
<?php } ?>
</ul>
</div>