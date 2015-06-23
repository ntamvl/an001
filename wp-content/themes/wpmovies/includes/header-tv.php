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
<ul class="generos">
<?php categorias_tv(); ?>
</ul>
<div class="pss"><a class="cerrar_resp" href="#block"><?php _e('Close', 'mundothemes'); ?></a></div>
</div>
</div>