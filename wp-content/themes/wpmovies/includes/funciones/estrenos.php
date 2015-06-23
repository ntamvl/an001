<div id="owl-demo2" class="owl-carousel owl-theme">
<?php $estrenos = get_option('estrenos_cat');  $rand_posts = get_posts('numberposts=20&cat='.$estrenos.'&orderby=rand'); foreach( $rand_posts as $post ) : ?>
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
  <img src="<?php echo $imgsrc; $imgsrc = ''; ?>" alt="<?php the_title(); ?>" width="120" height="170" />
  <?php if($values = get_post_custom_values("imdbRating")) { ?><div class="imdb"><span class="icon-grade"></span> <?php echo $values[0]; ?></div><?php } ?>
  </div>
  </a>

 <span class="ttps"><?php the_title(); ?></span>
 <?php if($mostrar = $terms = strip_tags( $terms = get_the_term_list( $post->ID, ''.$year_estreno.'' ))) {  ?><span class="ytps"><?php echo $mostrar; ?></span><?php } ?>
</div>
<?php endforeach; ?>
</div>