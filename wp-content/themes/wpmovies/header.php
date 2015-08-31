<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <?php if (!is_home()) { ?>
  <title><?php wp_title( '-', true, 'right' ); ?> <?php //bloginfo('name'); ?></title>
  <?php } else { ?>
  <title><?php echo get_bloginfo('name') . ' - ' . get_bloginfo('description'); ?></title>
  <?php } ?>
  <?php $favicon = get_option('general-favicon'); if (!empty($favicon)) { ?>
  <link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
  <?php } ?>
  <base href="<?php bloginfo('url'); ?>"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/reset.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/mt.min.css"/>
  <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/icons.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/custom.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap.min.css">
  <!-- Optional theme -->
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/bootstrap/css/bootstrap-theme.min.css">
  <!-- Latest compiled and minified JavaScript -->
  <script src="<?php echo get_template_directory_uri(); ?>/bootstrap/js/bootstrap.min.js"></script>

  <!-- begin video player -->
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/timer/angular-timer-all.min.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/app.js"></script>
  <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/controllers/MovieController.js"></script>
  <link href="<?php echo get_template_directory_uri(); ?>/video-js/video-js.css" rel="stylesheet" type="text/css">
  <script src="<?php echo get_template_directory_uri(); ?>/video-js/video.js"></script>
  <script>
    videojs.options.flash.swf = "<?php echo get_template_directory_uri(); ?>/video-js/video-js.swf";
  </script>
  <!-- end video player -->

  <script src="<?php echo get_template_directory_uri(); ?>/js/paginador.js" type="text/javascript"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/js.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <?php wp_head(); ?>
  <?php $gwebmasters = get_option('analitica'); if (!empty($gwebmasters)) echo stripslashes(get_option('analitica')); ?>
  <?php javascript_theme(); ?>
<script>
$(function()
{
$('.scrolling').jScrollPane();
});
</script>
<?php css_theme(); ?>

</head>
<body ng-app="movieApp">
<div class="toper"><div id="sec1"></div></div>
<div id="contenedor">