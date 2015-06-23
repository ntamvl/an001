<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title><?php wp_title( '-', true, 'right' ); ?> <?php bloginfo('name'); ?></title>
  <?php $favicon = get_option('general-favicon'); if (!empty($favicon)) { ?>
  <link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/x-icon" />
  <?php } ?>
  <base href="<?php bloginfo('url'); ?>"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/reset.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/mt.min.css"/>
  <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/responsive.css"/>
  <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/icons.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <?php $activar = get_option('activar-is'); if ($activar== "true") { ?>
  <script src="<?php echo get_template_directory_uri(); ?>/js/paginador.js" type="text/javascript"></script>
  <?php } ?>
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
<body>
<div class="toper"><div id="sec1"></div></div>
<div id="contenedor">