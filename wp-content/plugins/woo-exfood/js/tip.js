;(function($){
	'use strict';
	jQuery('document').ready(function($){
		function exwf_update_tipping(tip_val){
			$('.exwf-tip-form').addClass('ex-loading');
			var param = {
				action: 'exwf_update_tip',
				tip: tip_val,
			};
			var $url_ajax = exwf_jspr.ajaxurl;
			$.ajax({
				type: "post",
				url: $url_ajax,
				dataType: 'json',
				data: (param),
				success: function(data){
					$('.exwf-tip-form').removeClass('ex-loading');
					$( 'body' ).trigger( 'update_checkout' );
				}
			});
	        
		}
		$('body').on('click', '.exwf-tip-form input[name=exwf-add-tip]', function (e) {
	    //$(document).on('submit', '.exwf-tip-form form', function (e) {
	        e.preventDefault();
	        var $form = $(this).closest('.exwf-tip-form');
			var tip_val = $form.find('input[name=exwf-tip]').val();
			if(tip_val=='' || !$.isNumeric(tip_val) ){
				$form.find('.exwf-tip-error').fadeIn();
				return;
			}else{
				$form.find('.exwf-tip-error').fadeOut();
			}
			exwf_update_tipping(tip_val);
			return false;
	    });
	    $('body').on('click', '.exwf-tip-form input[name=exwf-remove-tip]', function (e) {
			exwf_update_tipping('0');
			return false;
	    });
	});
    
}(jQuery));