<?php
$video_source = get_post_meta($post->ID, 'video_source')[0];
$crawl_source = get_post_meta($post->ID, 'crawl_source')[0];
$poster_url = get_post_meta($post->ID, 'poster_url')[0];
$movie_id = '';
$movie_title = get_the_title();

try {
  $headers = get_headers($video_source);
  $video_status = substr($headers[0], 9, 3);
} catch (Exception $e) {
  $video_status = '404';
  echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>
<div id="player-container">
  <div class="play-c" ng-controller="MovieController">
    <div class="video-player {{ movie_ready_class }}">
      <video id="movie_video_player" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="100%"
        data-setup='{ "controls": true, "autoplay": false, "preload": "auto" }' ng-init="get_movie_source('<?php echo $movie_title; ?>', '<?php echo $movie_id; ?>', '<?php echo $video_source; ?>', '<?php echo $crawl_source; ?>', '<?php echo $video_status; ?>')">
        <source src="{{ video_source }}" type='video/mp4' />
        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
      </video>
    </div>
    <div class="video-player movie-loading {{ movie_wait_class }}" ng-if="!movie_ready">
      <h3 class="loading-text">{{ loading_text }}</h3>
      <h3 class="countdown-text {{ countdown_class }}"><timer countdown="9" interval="1000" finish-callback="countdown_ready()">{{seconds}} second{{secondsS}}</timer></h3>
    </div>

    <?php //echo do_shortcode( '[videojs mp4="' . get_post_meta($post->ID, 'video_source')[0] . '"]' ); ?>
  </div>
  <div class="play-c">
  </div>
</div>
