<div class="sidebar_right">
<?php $activar_ads = get_option('activar-anuncio-300-250'); if ($activar_ads == "true") { # activacion de anucios?>
<div class="ads-300">
<?php $ads = get_option('anuncio-300-250'); if (!empty($ads)) echo stripslashes(get_option('anuncio-300-250')); #imprimir anuncio ?>
</div>
<?php } else { echo "<br>"; }?>




<?php #include_once 'includes/funciones/mas_votados.php'; # top mas votados ?>
<?php include_once 'includes/funciones/mas_vistos.php'; # top mas vistos ?>

<div class="links">
<h3><?php if($tex = get_option('text-48')) { echo $tex; } else { _e('Release Year','mundothemes'); } ?> <span class="icon-sort"></span></h3>
<ul class="scrolling years">
<?php
$cc = date('Y');
$cd = date('Y')-50; 
foreach (range($cc, $cd) as $número) { ?>
<li><a class="ito" HREF="<?php bloginfo('url'); ?>/<?php echo $year_estreno; ?>/<?php echo $número; ?>"><?php echo $número; ?></a></li>
<?php } ?>
</ul>
</div>


<div class="footer">
<div class="box">

<ul class="totales">
<li><i><?php echo total_peliculas(); ?></i> <span><?php _e('Movies','mundothemes'); ?></span></li>
<li><i><?php echo total_series(); ?></i> <span><?php _e('TVShows','mundothemes'); ?></span></li>
<li><i><?php echo total_episodios(); ?></i> <span><?php _e('Episodes','mundothemes'); ?></span></li>
</ul>



</div>
</div>
</div>