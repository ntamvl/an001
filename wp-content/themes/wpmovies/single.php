<?php
get_header(); ?>
<?php
include_once 'includes/header.php'; ?>
<!-- sidebar -->
<?php
include_once 'sidebar_left.php'; ?>
<div class="items ptts">
<!-- single -->
<div id="movie">
<?php
include_once 'includes/aviso.php'; ?>
<?php
if (have_posts()): ?>
<?php
    while (have_posts()):
        the_post(); ?>
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
    <div class="post">
    <!-- meta-datos -->
    <?php
        include_once 'includes/single/data.php'; ?>
    <!-- player -->
    <?php
        include_once 'includes/single/player.php'; ?>
    <!-- imagenes -->
    <?php
        include_once 'includes/single/backdrops.php'; ?>
    <!-- enlaces -->
    <?php
        tvshows_ul_2(); ?>
    <div class="datos">
    <div class="responsive">
    <div class="alerta"><b class="icon-screen-rotation"></b></div>
    <div class="contenido">
    <?php
        enalces_verenlinea(); ?>
    <?php
        enlaces_descargas(); ?>
    </div>
    </div>
    </div>
    <!-- botones sociales -->
    <?php
        social_botones(); ?>
    <!-- comentarios -->
    <?php
    endwhile; ?>
<?php
else: ?>
<div class="no_data"><?php
    _e('No content available', 'mundothemes'); ?></div>
<?php
endif; ?>
<?php
include_once 'includes/single/relacionados_m.php'; ?>
<?php
if (have_posts()): ?>
<?php
    while (have_posts()):
        the_post(); ?>
    <?php
        $active = get_option('activar-com-single');
        if ($active == "true") {
            include_once 'includes/single/comentarios.php';
        }
?>
    <!-- post -->
    <?php
    endwhile; ?>
<?php
else: ?>
<div class="no_data"><?php
    _e('No content available', 'mundothemes'); ?></div>
<?php
endif; ?>
<?php
drss_plus(); ?>
</div>
</div>
</div>
<!-- sidebar -->
<?php
include_once 'sidebar_single_movies.php'; ?>
<!-- footer -->
<?php
get_footer(); ?>