<div class="sidebar_left">
<div id="largo">
<div class="contenido">
<div class="header">
<?php $logo = get_option('general-logo');if (!empty($logo)) { ?>
<div class="logo">
<a href="<?php bloginfo('url'); ?>/"><img src="<?php echo $logo; ?>" alt="<?php bloginfo('name') ?>" /></a>
</div>
<?php } else { ?>
<div class="logo">
<a href="<?php bloginfo('url'); ?>/"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php bloginfo('name') ?>" /></a>
</div>
<?php } ?>
<?php echo buscador_form_home(); ?>
<div class="data">
<h3><?php if($tex = get_option('text-1')) { echo $tex; } else { _e('Main','mundothemes'); } ?><span class="icon-caret-down"></span></h3>
<?php function_exists('wp_nav_menu') && has_nav_menu('menusidebar' ); wp_nav_menu( array( 'theme_location' => 'menusidebar', 'container' => '',  'menu_class' => 'leftmenu') ); ?>
<br>
<h3><?php if($tex = get_option('text-2')) { echo $tex; } else { _e('Genre','mundothemes'); } ?><span class="icon-caret-down"></span></h3>
<ul class="scrolling generos">
<?php categorias(); ?>
</ul>
</div>
</div>		
<div class="bajon">
<div class="normal">
<?php if($url = get_option('add_movie')) { ?>
<a class="agregar-movie" href="<?php echo stripslashes($url); ?>"><span class="icon-plus"></span> <?php if($tex = get_option('text-7')) { echo $tex; } else { _e('Add movies','mundothemes'); } ?></a>
<?php } ?>
</div>
<div class="movil">
<?php if($url = get_option('add_movie')) { ?>
<a class="agregar-movie-movil" href="<?php echo stripslashes($url); ?>"><span class="icon-plus"></span></a>
<?php } ?>
</div>
</div>

</div>
</div>
</div>