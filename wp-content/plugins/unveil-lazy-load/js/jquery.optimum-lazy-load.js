/**
 * Plugin Name: Unveil Lazy Load
 * Author: Daisuke Maruyama
 * Author URI: http://marubon.info/
 * Plugin URI: http://wordpress.org/plugins/unveil-lazy-load/
 * License: GPLv2 or later 
 */
;(function($) {
    var $w = $(window),
        th = 200,
        attrib = "data-src",
        images = $('img[data-src]'),
        inview;

    images.bind('scrollin', {}, function() {
        load_image(this);
    });
    $w.scroll(unveil);
    $w.resize(unveil);
    unveil();

    function unveil() {
        inview = images.filter(function() {
            var $e = $(this),
                wt = $w.scrollTop(),
                wb = wt + $w.height(),
                et = $e.offset().top,
                eb = et + $e.height();
            return eb >= wt - th && et <= wb + th;
        });
        images = images.not(inview.trigger('scrollin'));
    }

    function load_image(img) {
        var $img = jQuery(img),
            src = $img.attr(attrib);
        $img.unbind('scrollin').hide().removeAttr(attrib);
        img.src = src;
        $img.fadeIn();
    }
    return this;
})(jQuery);