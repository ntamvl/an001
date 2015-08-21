<?php
get_header(); ?>
<?php
include_once 'includes/header-tv.php'; ?>
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
        the_post();
        $tiulo = get_post_custom_values("titulo_serie");
        $url = get_post_custom_values("url_serie");
        $fecha = get_post_custom_values("fecha_serie");
        $s = get_post_custom_values("temporada_serie");
        $ep = get_post_custom_values("episodio_serie");
        ?>
        <div class="post">
            <div class="datos episodio">
                <h1><?php
                the_title(); ?></h1>
                <?php
                if ($values = get_post_custom_values("titulo_serie")) { ?>
                <p><span>S<?php
                echo $s[0]; ?></span> <span>Ep<?php
            echo $ep[0]; ?></span> <a href="<?php
            bloginfo('url'); ?>/<?php
            echo get_option('tvshows'); ?>/<?php
            echo $url[0]; ?>/"><?php
        echo $values[0]; ?></a> <i><?php
    echo $fecha[0]; ?></i></p>
    <?php
    } ?>
    <?php
    the_content(); ?>
</div>
<?php
include_once 'includes/single/player_tv.php'; ?>

<!-- Start to get next previous link by tag name -->
<?php
$post_id = $post->ID;
$tags =  get_the_tags();
$list_tags = array();
foreach($tags as $tag) {
    array_push($list_tags, $tag->term_id);
}
$args = array(
    'tag__in' => $list_tags,
    'post_type' => array('episodios'),
    'order' => 'DESC',
    'posts_per_page' => 1000,
    'orderby' => 'meta_value_num',
    'meta_key' => 'episodio_serie'
);
// $cat = get_the_category();
// $current_cat_id = $cat[0]->cat_ID; // current category Id

// $args = array('category'=>$current_cat_id,'orderby'=>'post_date','order'=> 'DESC');
$posts = get_posts($args);
// get ids of posts retrieved from get_posts
$ids = array();
foreach ($posts as $thepost) {
    $ids[] = $thepost->ID;
}
$thisindex = array_search($post->ID, $ids);
$previd = $ids[$thisindex + 1];
$nextid = $ids[$thisindex - 1];
?>
<div class="datos">
  <div class="responsive">
    <div class="col-md-6 col-xs-6">
      <?php if (!empty($previd)){ ?>
      <a rel="prev" href="<?php echo get_permalink($previd) ?>">Previous: <?php echo get_the_title( $previd ); ?></a>
      <?php } ?>
    </div>
    <div class="col-md-6 col-xs-6 text-right pull-right">
      <?php if (!empty($nextid)){ ?>
      <a rel="next" href="<?php echo get_permalink($nextid) ?>">Next: <?php echo get_the_title( $nextid ); ?></a>
      <?php } ?>
    </div>
  </div>
</div>
<!-- End to get next previous link by tag name -->

<!-- Related  movies -->
<?php include_once 'includes/single/relacionados_ep.php'; ?>

<h4 class="more-episode-title">More episodes</h4>
<?php include_once '_list_episode.php'; ?>

<div class="datos hide">
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
$active = get_option('activar-com-single');
if ($active == "true") {
include_once 'includes/single/comentarios.php';
} ?>
<?php
drss_plus(); ?>
</div>
<!-- post -->
<?php
endwhile; ?>
<?php
else: ?>
<div class="no_data"><?php
_e('No content available', 'mundothemes'); ?></div>
<?php
endif; ?>
</div>
</div>
<!-- sidebar -->
<?php
include_once 'sidebar_single_tv_ads.php'; ?>
<!-- footer -->
<?php
get_footer(); ?>