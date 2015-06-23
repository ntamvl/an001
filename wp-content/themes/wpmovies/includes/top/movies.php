<div id="owl-demo2" class="owl-carousel owl-theme">
<?php $numerado = 1; { query_posts(
array('meta_key' => 'end_time','meta_compare' =>'>=','meta_value'=>time(),'meta_key' => 'imdbRating',
'post__not_in' => get_option( 'sticky_posts' ),'orderby' => 'meta_value_num','showposts' => '50','order' => 'DESC'));
while ( have_posts() ) : the_post(); 
$imdbRating = get_post_meta($post->ID, "imdbRating", $single = true); 
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
} 
?>
<div class="item">
  <a href="<?php the_permalink() ?>">
  <div class="imgss">
  <div class="ui label clip orange"><?php echo $numerado; ?></div>
  <img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" />
  <?php if($values = get_post_custom_values("imdbRating")) { ?><div class="imdbtop"><span class="icon-grade"></span> <?php echo $values[0]; ?></div><?php } ?>
  </div>
  </a>
  <span class="ttps"><?php the_title(); ?></span>
 <?php if($mostrar = $terms = strip_tags( $terms = get_the_term_list( $post->ID, ''.$year_estreno.'' ))) {  ?><span class="ytps"><?php echo $mostrar; ?></span><?php } ?>
  </div>
<?php $numerado++; ?>
<?php endwhile; wp_reset_query(); ?>
<?php } ?>
</div>