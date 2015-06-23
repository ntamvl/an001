<div class="comentarios">
<?php $comentarios = get_option('activar-facebook'); if ($comentarios == "true") { ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php if($lang = get_option('fb_idioma')) { echo $lang; } else { echo 'en_EN'; } ?>/sdk.js#xfbml=1&appId=<?php if($appid = get_option('fb_id')) { echo $appid; } else { echo "209955335852854"; } ?>&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div class="fb-comments" data-href="<?php the_permalink() ?>"  mobile="false" data-numposts="<?php if($dato = get_option('fb_numero')) { echo $dato; } else { echo '5'; } ?>" data-colorscheme="<?php if($color = get_option('fb_color')) { echo $color; } else { echo 'light'; } ?>"></div>

<?php } else { ?> 
<?php $comentarios = get_option('activar-disqus'); if ($comentarios == "true") { ?>
<div id="disqus_thread"></div>
<script type="text/javascript">
        var disqus_shortname = '<?php $code = get_option('disqus_id'); if (!empty($code)) echo stripslashes(get_option('disqus_id')); ?>';
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
</script>
<?php } else { ?>

<?php comments_template('/comments.php',true); ?>
<?php }  
}
?>
</div>