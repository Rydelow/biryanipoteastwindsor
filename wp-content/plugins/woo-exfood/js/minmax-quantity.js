;(function($){
	'use strict';
	$(document).ready(function() {
		jQuery( document ).on( "found_variation.first", function ( e, variation ) {
			var $this = $(this);console.log($this);console.log(variation);
			if(variation.exwf_minquantity!='' || variation.exwf_maxquantity!=''){
				$("form.cart .qty").each(function(){
					$(this).val($(this).attr('min'));
				});
				$($this).closest('form.cart').find('.qty').trigger('keyup');
			}
		});
    });
}(jQuery));