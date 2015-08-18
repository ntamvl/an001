<div class="header_slider">
<span class="titulo_2"><?php
_e('Related animes & cartoons', 'mundothemes'); ?></span>
<div class="customNavigation">
<a class="btn prev"><b class="icon-chevron-left2"></b></a>
<a class="btn next"><b class="icon-chevron-right2"></b></a>
</div>
</div>
<div class="random" style="border-bottom: 0;">
<?php
$cat = get_the_category();
$cat = $cat[0];
$cat = $cat->cat_ID;
$post = get_the_ID();
$args = array('cat' => $cat, 'orderby' => 'rand', 'showposts' => 14, 'post__not_in' => array($post));
$related = new WP_Query($args);
if ($related->have_posts()) {
    echo '<div id="owl-demo2" class="owl-carousel owl-theme">';
    while ($related->have_posts()):
        $related->the_post();
        if (has_post_thumbnail()) {
            $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thome');
            $imgsrc = $imgsrc[0];
        }
        elseif ($postimages = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=0")) {
            foreach ($postimages as $postimage) {
                $imgsrc = wp_get_attachment_image_src($postimage->ID, 'home');
                $imgsrc = $imgsrc[0];
            }
        }
        elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
            $imgsrc = $match[1];
        }

        else {
          $img = get_post_custom_values("poster_url");
          if ( isset($img) ) {
            $imgsrc = $img[0];
          } else {
            $imgsrc = get_template_directory_uri() . '/images/noimagen_single.png';
          }
        }
?>
  <div class="item">
  <a href="<?php
        the_permalink() ?>">
  <div class="imgss">
  <img src="<?php
        echo $imgsrc;
        $imgsrc = ''; ?>" alt="<?php
        the_title(); ?>" />
  <?php
        if ($values = get_post_custom_values("imdbRating")) { ?><div class="imdb"><span class="icon-grade"></span> <?php
            echo $values[0]; ?></div><?php
        } ?>
  </div>
  </a>
  <span class="ttps"><?php
        the_title(); ?></span>
 <?php
        if ($mostrar = $terms = strip_tags($terms = get_the_term_list($post->ID, '' . $year_estreno . ''))) { ?><span class="ytps"><?php
            echo $mostrar; ?></span><?php
        } ?>
  </div>
  <?php
    endwhile; ?>
</div>
<?php
}
wp_reset_query(); ?>
</div>
