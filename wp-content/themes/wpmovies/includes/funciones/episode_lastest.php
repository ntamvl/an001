<div class="links">
  <h3><?php
  if ($tex = get_option('text-11')) {
  echo $tex;
  }
  else {
  _e('Lastest episodes', 'mundothemes');
  } ?>
    </h3>

  <!-- <ul class="scrolling lista"> -->
  <ul class="lista scrolling scroll-y">
    <?php
    $numerado = 1; {
    // query_posts('v_sortby=views&v_orderby=desc&showposts=20&ignore_sticky_posts=1&episode_status=ongoing');
    // query_posts('showposts=20&episode_status=ongoing');
    query_posts('showposts=20&post_type=episodios&orderby=modified');
    while (have_posts()):
    the_post(); ?>
    <li>
      <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
    </li>
    <?php $numerado++; ?>
    <?php
    endwhile; ?>
    <?php
    } ?>
  </ul>
</div>