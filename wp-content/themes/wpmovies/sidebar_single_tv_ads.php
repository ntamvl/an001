<div class="sidebar_right">
  <?php $activar_ads = get_option('activar-anuncio-300-250'); if ($activar_ads == "true") { # activacion de anucios?>
  <div class="ads-300">
    <?php $ads = get_option('anuncio-300-250'); if (!empty($ads)) echo stripslashes(get_option('anuncio-300-250')); #imprimir anuncio ?>
  </div>
  <?php } else { echo "<br>"; }?>
  <!-- <div class="links">
  </div> -->
  <?php include_once 'includes/funciones/episode_lastest.php'; ?>
  <div class="footer">
    <div class="box">
      <ul class="totales">
        <li><i><?php echo total_peliculas(); ?></i> <span><?php _e('Movies','mundothemes'); ?></span></li>
        <!-- <li><i><?php echo total_series(); ?></i> <span><?php _e('TVShows','mundothemes'); ?></span></li> -->
        <li><i><?php echo total_episodios(); ?></i> <span><?php _e('Episodes','mundothemes'); ?></span></li>
      </ul>
    </div>
  </div>
</div>