<div id="series" class="owl-carousel owl-theme">
<?php $args = array( 'post_type' => 'tvshows', 'showposts' => '20','orderby' => 'rand');$my_query = new WP_Query($args);
while ($my_query->have_posts()) : $my_query->the_post(); $do_not_duplicate = $post->ID; $IDPost = get_the_ID(); 
?>
<?php   if (has_post_thumbnail()) {
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
<div class="item">
  <a href="<?php the_permalink() ?>">
  <div class="imgss">
  <img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" />
  <?php if($values = get_post_custom_values("imdbRating")) { ?><div class="imdb"><span class="icon-grade"></span> <?php echo $values[0]; ?></div><?php } ?>
  </div>
  </a>
 <span class="ttps"><?php the_title(); ?></span>
 <?php if($mostrar = $terms = strip_tags( $terms = get_the_term_list( $post->ID, ''.$year_estreno.'' ))) {  ?><span class="ytps"><?php echo $mostrar; ?></span><?php } ?>
</div>
<?php endwhile; ?>
</div>