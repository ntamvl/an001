<?php wp_reset_query(); ?>
<?php $tags =  get_the_tags();
    $list_tags = array();
    foreach($tags as $tag) {
        array_push($list_tags, $tag->term_id);
    }
    $args = array(
        'tag__in' => $list_tags,
        'post__not_in' => array($post->ID),
        'post_type' => array('episodios'),
        'order' => 'DESC',
        'posts_per_page' => 1000,
        'orderby' => 'meta_value_num',
        'meta_key' => 'episodio_serie'
    );
    $episode_query = new WP_Query($args);
?>
<?php  $ep_index = 1;
if( $episode_query->have_posts() ) { ?>
<div class="table table-striped datos list-episode-box">
    <table class="table table-bordered">
      <!-- <thead>
        <tr>
          <th class="col-md-1 text-center">Ep [#]</th>
          <th>Episode</th>
        </tr>
      </thead> -->
      <tbody>
        <?php
        while ($episode_query->have_posts()) : $episode_query->the_post(); ?>
        <tr>
          <!-- <th scope="row" class="text-center"><?php echo get_field('episodio_serie'); ?></th> -->
          <td>
            <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                <?php the_title(); ?>
            </a>
          </td>
        </tr>
        <?php $ep_index++; endwhile; ?>
      </tbody>
    </table>
</div>

<?php } wp_reset_query(); ?>
