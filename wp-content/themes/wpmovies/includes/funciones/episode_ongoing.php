<div class="links">
    <h3><?php
        if ($tex = get_option('text-11')) {
            echo $tex;
        } else {
            _e('Ongoing Series', 'mundothemes');
        } ?>
        <!-- <span class="icon-eye"></span> -->
    </h3>

    <?php // wp_reset_query(); ?>

    <!-- <ul class="scrolling lista"> -->
    <ul class="list-ongoing">
        <?php
        // query_posts('v_sortby=views&v_orderby=desc&showposts=20&ignore_sticky_posts=1&episode_status=ongoing');
        $args = array(
            // 'showposts' => 10,
            // 'post_type' => 'post',
            // 'movie_status' => 'ongoing',
            // 'taxonomy' => 'movie_status',
            // 'term' => 'ongoing'
            // 'genre' => 'cars'
            // 'tax_query' => array(
            //     array(
            //         'taxonomy' => 'movie_status',
            //         'field' => 'name',
            //         'terms' => array('Ongoing')
            //     )
            // )
        );

        $args = array(
            'post_type' => 'post',
            'showposts' => 60,
            'movie_status' => 'ongoing',
        );

        query_posts($args);
        while (have_posts()) : the_post();
            ?>
            <li>
                <a href="<?php the_permalink() ?>"><?php the_title(); ?></a>
            </li>
        <?php endwhile; ?>
    </ul>
    <?php // wp_reset_query(); ?>
</div>
