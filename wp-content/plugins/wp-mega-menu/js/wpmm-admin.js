/*
 * Plugin Name: WP Mega Menu
 * Plugin URI: http://mythemeshop.com/plugins/wp-mega-menu/
 * Description: WP MegaMenu is an easy to use plugin for creating beautiful, customized menus for your blog that show categories, subcategories and posts.
 * Author: MyThemeShop
 * Author URI: http://mythemeshop.com/
*/
jQuery(document).ready(function($) {
	var header_clicked, formfield;
	jQuery('#menu-to-edit').on('click', '.wpmm-select-background', function(e) {
		e.preventDefault();
		header_clicked = true;
		formfield = $(this).next('input');
		tb_show('', 'media-upload.php?type=image&amp;post_id=0&amp;TB_iframe=true');
	});


	// Store original function
	window.original_send_to_editor = window.send_to_editor;
	
	
	window.send_to_editor = function(html) {
		if (header_clicked) {
			var imgurl = jQuery('img',html).attr('src');
            
            var classes = jQuery('img', html).attr('class');
            var regex = /wp-image-([0-9]+)/g;
            var imgid = regex.exec(classes);
                imgid = imgid[1];
            var file = imgurl.substring(imgurl.lastIndexOf('/') + 1);
            if (file.length > 20)
            	file = file.substring(0, 20)+'...';
            formfield.val(imgurl).prev('a').text('Remove Background').removeClass('wpmm-select-background').addClass('wpmm-remove-background').nextAll('img').attr('src', imgurl);
            formfield.parent().next('.mmbgimagesettings').show();
			tb_remove();
			header_clicked = false;
		} else {
			window.original_send_to_editor(html);
		}
	}
	
	jQuery('#menu-to-edit').on('click', '.wpmm-remove-background', function(e){
		e.preventDefault();
		jQuery(this).parent().next('.mmbgimagesettings').hide();
		jQuery(this).text('Select Background').removeClass('wpmm-remove-background').addClass('wpmm-select-background').next('input').val('').nextAll('img').attr('src', wpmm.blank);
	});

	$('#add-category h3, #add-post_tag h3, #add-product_cat h3, #add-product_tag h3').append('<span>+ Mega Menu</span>');
});