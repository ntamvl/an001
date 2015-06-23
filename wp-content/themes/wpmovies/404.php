<?php get_header(); ?>
<?php include_once 'includes/header.php'; ?>
<?php include_once 'sidebar_left.php'; ?>
<div class="items">
<div id="directorio">
<?php include_once 'includes/aviso.php'; ?>
<div class="error404">
<b><i class="icon-power-off"></i> <?php if($tex = get_option('text-14')) { echo $tex; } else { _e('Error 404','mundothemes'); } ?></b>
<span><?php if($tex = get_option('text-13')) { echo $tex; } else { _e('No content available','mundothemes'); } ?></span>
</div>
</div>
</div>
<?php include_once 'sidebar_right.php'; ?>
<?php get_footer(); ?>