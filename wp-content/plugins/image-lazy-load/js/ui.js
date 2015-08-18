/**
* Image Lazy Load (Unveil.js) JS
*/
jQuery(document).ready(function($){
	if(typeof imageUnveilload === 'undefined'){
		var imageUnveilload = 0;
	}
	$('img[data-unveil="true"]').unveil(imageUnveilload, function() {
		$(this).load(function() {
			// Fade in the image (combined with a CSS transition to complete the fade)
			this.style.opacity = 1;
		});
	});
});