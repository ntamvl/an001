<?php
if (current_user_can('update_core')) { ?>
<div class="menu-admin">
<ul>
<?php
    edit_post_link(__('Edit post', 'mundothemes'), '<li><b>', '</b></li>'); ?>
<li><a href="<?php
    bloginfo('url'); ?>/wp-admin/admin.php?page=wpmovies"><?php
    _e('Theme Settings', 'mundothemes'); ?></a></li>
<li><a href="<?php
    bloginfo('url'); ?>/wp-admin/admin.php?page=mundothemes"><?php
    _e('License Status', 'mundothemes'); ?></a></li>
<li class="right"><a href="https://mundothemes.com/forums/" target="_blank" class="right"><?php
    _e('Support Forums', 'mundothemes'); ?></a></li>
</ul>
</div>
<?php
} ?>
<div class="headingder">
<div class="cover"<?php
if ($values = get_post_custom_values("cover_url")) { ?> style="background-image: url(<?php
    echo $values[0]; ?>);"<?php
}
else { ?> style="background-image: url(<?php
    echo $imgsrc;
    $imgsrc = ''; ?>);"<?php
} ?> ></div>
<div class="datos" style="background: transparent;margin-bottom: 0;">
<?php
if (has_post_thumbnail()) {
    $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'original');
    $imgsrc = $imgsrc[0];
}
elseif ($postimages = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=0")) {
    foreach ($postimages as $postimage) {
        $imgsrc = wp_get_attachment_image_src($postimage->ID, 'original');
        $imgsrc = $imgsrc[0];
    }
}
elseif (preg_match('/<img [^>]*src=["|\']([^"|\']+)/i', get_the_content(), $match) != FALSE) {
    $imgsrc = $match[1];
}
else {
    $img = get_post_custom_values("poster_url");
    $imgsrc = $img[0];
}
?>
<div class="imgs tsll"><a href="#dato-2"><img src="<?php
echo $imgsrc;
$imgsrc = ''; ?>" alt="<?php
the_title(); ?>" /></a></div><!-- imgs -->
<div class="dataplus">
<h1><?php
the_title(); ?></h1>
<?php
if ($values = get_post_custom_values("Title")) { ?><span class="original"><?php
    echo $values[0]; ?></span><?php
} ?>
<div id="dato-1" class="data-content">
<p>
<?php
if ($values = get_post_custom_values("Rated")) { ?><span class="<?php
    echo $values[0]; ?>"><?php
    echo $values[0]; ?></span><?php
} ?>
<?php
if ($mostrar = $terms = strip_tags($terms = get_the_term_list($post->ID, '' . $year_estreno . ''))) { ?><span>
<?php
    echo get_the_term_list($post->ID, '' . $year_estreno . '', '', '', ''); ?>
</span><?php
} ?>
<?php
if ($values = get_post_custom_values("Runtime")) { ?><span><b class="icon-query-builder"></b> <?php
    echo $values[0]; ?></span><?php
} ?>
<?php
the_category(',&nbsp;', ''); ?>
</p>
<div class="score">
<div class="rank"><?php
$values = get_post_custom_values("imdbRating");
echo $values[0]; ?></div>
<div class="stars">
<span class="abc-c" style="width:174px;">
<span class="abc-r" style="width: <?php
$values = get_post_custom_values("imdbRating");
echo $values[0] * 10; ?>%;"></span>
</span>
<div class="imdbdatos">
<i><a href="http://www.imdb.com/title/<?php
$values = get_post_custom_values("Checkbx2");
echo $values[0]; ?>/">IMDb</a> <span class="icon-chevron-right2"></span></i>
<i><?php
$values = get_post_custom_values("imdbRating");
echo $values[0]; ?>/10</i>
<i><?php
$values = get_post_custom_values("imdbVotes");
echo $values[0]; ?> <?php
_e('votes', 'mundothemes'); ?></i>
</div>
</div>
</div>

<div class="xmll"><p class="xcsd"><?php
echo get_the_term_list($post->ID, '' . $director . '', '<b class="icon-bullhorn"></b> &nbsp;', ', ', ''); ?></p></div>
<div class="xmll"><p class="xcsd"><?php
echo get_the_term_list($post->ID, '' . $actor . '', '<b class="icon-star"></b> &nbsp;', ', ', ''); ?> </p></div>
<?php
if ($values = get_post_custom_values("Released")) { ?><div class="xmll"><p class="xcsd"><b class="icon-check"></b> <?php
    echo $values[0]; ?></p></div><?php
} ?>
<?php
if ($values = get_post_custom_values("Awards")) { ?><div class="xmll"><p class="xcsd"><b class="icon-trophy"></b> <?php
    echo $values[0]; ?></p></div><?php
} ?>
<div class="xmll"><p class="tsll xcsd"><b class="icon-info-circle"></b> <a href="#dato-2"><?php
if ($tex = get_option('text-28')) {
    echo $tex;
}
else {
    _e('Synopsis', 'mundothemes');
} ?></a></p></div>
</div>
<div id="dato-2" class="data-content tsll">
<h2><?php
_e('Synopsis', 'mundothemes'); ?></h2>
<?php
the_content(); ?>
<div class="tsll">
<a class="regresar" href="#dato-1"><b class="icon-chevron-left2"></b> <?php
if ($tex = get_option('text-50')) {
    echo $tex;
}
else {
    _e('Go back', 'mundothemes');
} ?></a>
</div>
</div>
</div><!-- dataplus -->
</div>
</div><!-- headingder -->


