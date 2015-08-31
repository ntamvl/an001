<div class="links">
  <h3><?php
  if ($tex = get_option('text-11')) {
  echo $tex;
  }
  else {
  _e('Ongoing', 'mundothemes');
  } ?>
  <!-- <span class="icon-eye"></span> -->
  </h3>

  <ul class="scrolling lista">
    <?php
    $numerado = 1; {
    // query_posts('v_sortby=views&v_orderby=desc&showposts=20&ignore_sticky_posts=1&episode_status=ongoing');
    query_posts('showposts=20&episode_status=ongoing');
    while (have_posts()):
    the_post(); ?>
    <li>
      <b><?php
      echo $numerado; ?></b>
      <a href="<?php
        the_permalink() ?>"><?php
      the_title(); ?></a>
      <span><?php
        $values = get_post_custom_values("views");
      echo $values[0]; ?></span>
      <?php
      if ($mostrar = $terms = strip_tags($terms = get_the_term_list($post->ID, '' . $year_estreno . ''))) { ?><i><?php
      echo $mostrar; ?></i><?php
      } ?>
    </li>
    <?php
    $numerado++; ?>
    <?php
    endwhile; ?>
    <?php
    } ?>
  </ul>
</div>