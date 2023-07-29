;(function($){
	'use strict';
	$(document).ready(function() {
		$("#toplevel_page_exwf_ocal_options").insertAfter("#toplevel_page_woocommerce > ul li:nth-child(3)");
		$("#toplevel_page_exwf_ocal_options,#toplevel_page_exwf_ocal_options > a").removeClass('menu-top');
		if($("#toplevel_page_exwf_ocal_options > a").hasClass('current') ){
			$("#adminmenu > li#toplevel_page_woocommerce").removeClass('wp-not-current-submenu');
			$("#adminmenu > li#toplevel_page_woocommerce").addClass('wp-has-current-submenu');
		}
	});
    
}(jQuery));
