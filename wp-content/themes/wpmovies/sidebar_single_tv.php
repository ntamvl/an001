<div class="sidebar_right">
  <?php $activar_ads = get_option('activar-anuncio-300-250'); if ($activar_ads == "true") { # activacion de anucios?>
  <div class="ads-300">
    <?php $ads = get_option('anuncio-300-250'); if (!empty($ads)) echo stripslashes(get_option('anuncio-300-250')); #imprimir anuncio ?>
  </div>
  <?php } else { echo "<br>"; }?>
  <div class="links">
    <h3><?php if($tex = get_option('text-45')) { echo $tex; } else { _e('More Episode','mundothemes'); } ?></h3>
    <?php //relacionados_tv(); ?>
    <ul>
    <?php $tags =  get_the_tags();
        $list_tags = array();
        foreach($tags as $tag) {
            array_push($list_tags, $tag->term_id);
        }
        $args=array(
            'tag__in' => $list_tags,
            'post__not_in' => array($post->ID),
            'post_type' => 'episodios',
            'order' => 'DESC',
            'orderby' => 'meta_value',
            'meta_key' => 'episodio_serie'
        );
        $episode_query = new WP_Query($args);
        $ep_index = 1;
        if( $episode_query->have_posts() ) {
        while ($episode_query->have_posts()) : $episode_query->the_post();
    ?>
    <li>
      <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
        <?php the_title(); ?></a>
    </li>
    <?php $ep_index++; endwhile; } ?>
    </ul>
  </div>
  <div class="footer">
    <div class="box">
      <ul class="totales">
        <li><i><?php echo total_peliculas(); ?></i> <span><?php _e('Movies','mundothemes'); ?></span></li>
        <!-- <li><i><?php echo total_series(); ?></i> <span><?php _e('TVShows','mundothemes'); ?></span></li> -->
        <li><i><?php echo total_episodios(); ?></i> <span><?php _e('Episodes','mundothemes'); ?></span></li>
      </ul>
    </div>
  </div>
</div>