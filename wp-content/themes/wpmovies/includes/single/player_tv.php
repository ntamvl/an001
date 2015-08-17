<?php
$activar = get_option('activar-pelicula');
if ($activar == "true") { ?>
<div id="player-container">
<div class="play-c">
    <?php echo do_shortcode( '[videojs mp4="' . get_post_meta($post->ID, 'video_source')[0] . '"]' ); ?>
    <?php // echo do_shortcode( '[videojs mp4="' . get_post_meta($post->ID, 'tvplayer_0_video_source')[0] . '"]' ); ?>
</div>
<?php
    if (have_rows('tvplayer')): ?>
<div class="play-c hide">
<?php
        $numerado = 1; {
            while (have_rows('tvplayer')):
                the_row(); ?>
<div id="play-<?php
                echo $numerado; ?>" class="player-content">
                <?php  $embed_tvplayer = get_sub_field('embed_tvplayer'); ?>
                <?php  $video_source = strip_tags(get_sub_field('video_source')); ?>
                <?php if (!empty($embed_tvplayer)) { echo $embed_tvplayer; } ?>
                <?php if (!empty($video_source)) { echo do_shortcode( '[videojs mp4="' . $video_source . '"]' ); } ?>
</div>
<?php
                $numerado++; ?>
<?php
            endwhile;
        } ?>
 </div>
<?php
    else: ?>
<div class="no_link hide">
    <p><b class="icon-play-circle-outline bigtext"></b></p>
    <p><?php
            if ($tex = get_option('text-38')) {
                echo $tex;
            }
            else {
                _e('No sources available', 'mundothemes');
            } ?></p>
</div>
<?php
    endif; ?>
<?php
    if (have_rows('tvplayer')): ?>
<ul class="player-menu hide">
<li><a href="#play-0"></a></li>
<?php
        $numerado = 1; {
            while (have_rows('tvplayer')):
                the_row(); ?>
<li><a href="#play-<?php
                echo $numerado; ?>"><?php
                the_sub_field('title_tvplayer'); ?></a></li>
<?php
                $numerado++; ?>
<?php
            endwhile;
        } ?>
</ul>
<?php
    endif; ?>
</div>
<?php
} ?>