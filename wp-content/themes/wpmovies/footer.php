</div>
<?php wp_footer(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/custom.js"></script>
<script>
<?php $activar = get_option('activar-is'); if ($activar== "true") { ?>
var ias = $.ias({
    container: "#box_movies",
    item: ".movie",
    pagination: ".pages_respo",
    next: ".siguiente a"
});
/*ias.extension(new IASTriggerExtension({ offset: 0 })),*/
ias.extension(new IASSpinnerExtension),
/*ias.extension(new IASNoneLeftExtension),*/
<?php } ?>
$(document).ready(function() {
    $("#arriba").click(function() {
        return $("html, body").animate({
            scrollTop: 0
        }, 1250), !1
    })
});
$(document).ready(function(){var t=$("#owl-demo2");t.owlCarousel({items:7,pagination:!1,autoPlay:!1,itemsDesktop:[1336,5],itemsDesktopSmall:[900,4],itemsTablet:[600,4],itemsMobile:[384,3]}),$(".next").click(function(){t.trigger("owl.next")}),$(".prev").click(function(){t.trigger("owl.prev")}),$(".play").click(function(){t.trigger("owl.play",1e3)}),$(".stop").click(function(){t.trigger("owl.stop")})}),$(document).ready(function(){var t=$("#series");t.owlCarousel({items:7,pagination:!1,autoPlay:!1,itemsDesktop:[1336,5],itemsDesktopSmall:[900,4],itemsTablet:[600,4],itemsMobile:[384,3]}),$(".nexts").click(function(){t.trigger("owl.next")}),$(".prevs").click(function(){t.trigger("owl.prev")}),$(".play").click(function(){t.trigger("owl.play",1e3)}),$(".stop").click(function(){t.trigger("owl.stop")})}),$(document).ready(function(){$("#owl-demo").owlCarousel({autoPlay:3e3,items:4,pagination:!1,lazyLoad:!0,itemsDesktop:[1199,4],itemsDesktopSmall:[979,3],itemsMobile:[384,1]})});
</script>
<?php $code = get_option('code_integracion'); if (!empty($code)) echo stripslashes(get_option('code_integracion')); ?>
</body>
</html>
