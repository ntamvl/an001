<div class="fixed-head">
<div class="header_resp">
<div class="logo">
<a class="movies_a" href="<?php bloginfo('url'); ?>">
<?php $logo = get_option('general-logo-responsive');if (!empty($logo)) { ?>
<img src="<?php echo $logo; ?>" alt="<?php bloginfo('name') ?>" />
<?php } else { ?>
<b class="icon-home"></b>
<?php } ?>
</a>
</div>
<div class="nav pss"><a href="#nav-responsive"><b class="icon-bars"></b></a></div>
</div>
<div id="block"></div>
<div class="menust" id="nav-responsive">
<?php function_exists('wp_nav_menu') && has_nav_menu('menu1' ); wp_nav_menu( array( 'theme_location' => 'menu1', 'container' => '',  'menu_class' => 'home_links2') ); ?>
<?php if($url = get_option('add_movie')) { ?>
<a class="agregar_movie_resp" href="<?php echo stripslashes($url); ?>"><span class="icon-plus"></span> <?php if($tex = get_option('text-7')) { echo $tex; } else { _e('Add movies','mundothemes'); } ?></a>
<?php } ?>
<ul class="generos">
<?php categorias(); ?>
</ul>
<div class="pss"><a class="cerrar_resp" href="#block"><?php _e('Close', 'mundothemes'); ?></a></div>
</div>
</div>