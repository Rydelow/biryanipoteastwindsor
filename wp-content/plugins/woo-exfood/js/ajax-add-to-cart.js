;(function($){
	'use strict';
	function exfd_flytocart(imgtodrag){
		var cart = jQuery('.exfd-shopping-cart');
		if (cart.length == 0) {return;}
	    if (imgtodrag.length) {
	        var imgclone = imgtodrag.clone().offset({
	            top: imgtodrag.offset().top,
	            left: imgtodrag.offset().left
	        }).css({
	            'opacity': '0.5',
	                'position': 'absolute',
	                'height': '150px',
	                'width': '150px',
	                'z-index': '1001'
	        }).appendTo(jQuery('body'))
	            .animate({
	            'top': cart.offset().top + 10,
	                'left': cart.offset().left,
	                'width': 40,
	                'height': 40
	        }, 800);
	        imgclone.animate({
	            'width': 0,
	                'height': 0
	        }, function () {
	            jQuery(this).detach()
	        });
	    }
	}
	function exf_refresh_cart(){
		var data = {
			action: 'exwoofood_refresh_cart',
		};
		$('.exfd-cart-mini').css('opacity','.6');
		$.ajax({
			type: 'post',
			url: wc_add_to_cart_params.ajax_url,
			data: data,
			success: function(res){
				$('div.exfd-cart-mini').remove();
				$(res.fragments['div.exfd-cart-mini']).insertAfter('.exfd-cart-content .exfd-close-cart');
				$('.exfd-shopping-cart span.exfd-cart-num').replaceWith(res.fragments['span.exfd-cart-num']);
			}
		});
	}
	$(document).on('submit', '.exwoofood-woocommerce form', function (e) {
		$("#food_modal .exwoofood-woocommerce > div .exfd-out-notice").remove();
		var $button = $(this).find('.single_add_to_cart_button');
		var $form = $(this);
		var product_id = $form.find('input[name=add-to-cart]').val() || $button.val();
		var loc = $('.ex-fdlist > input[name=food_loc]').val();
		if (!product_id){ return;}
		if ($button.is('.disabled')){ return;}
		e.preventDefault();
		var data = {
			action: 'exwoofood_add_to_cart',
			'add-to-cart': product_id,
			'loc': loc,
		};
		$form.serializeArray().forEach(function (element) {
			data[element.name] = element.value;
		});
		$(document.body).trigger('adding_to_cart', [$button, data]);
		if($(this).find(".exrow-group.ex-checkbox").length){
			$(this).find(".exrow-group.ex-checkbox").each(function() {
				var $name = $(this).find('.ex-options').attr('name');
				var dt_cb =[];
				$(this).find('input[name="'+$name+'"]:checked').each(function() {
					dt_cb.push($(this).val());
				});
				data[$name] = dt_cb;
			});
		}
		if($(".wc-pao-addon-checkbox").length){
			$(".wc-pao-addon-checkbox").each(function() {
				var $name = $(this).attr('name');
				var dt_cb =[];
				$('input[name="'+$name+'"]:checked').each(function() {
				  dt_cb.push($(this).val());
				});
				data[$name] = dt_cb;
			});
		}
		if($(".ppom-check-input").length){
			$(".ppom-check-input").each(function() {
				if($(this).attr('type')=='checkbox'){
					var $name = $(this).attr('name');
					var dt_cb =[];
					$('input[name="'+$name+'"]:checked').each(function() {
					  dt_cb.push($(this).val());
					});
					data[$name] = dt_cb;
				}
			});
		}
		if($(".wccf_product_field_checkbox").length){
			$(".wccf_product_field_checkbox").each(function() {
				var $name = $(this).attr('name');
				var dt_cb =[];
				$('input[name="'+$name+'"]:checked').each(function() {
				  dt_cb.push($(this).val());
				});
				data[$name] = dt_cb;
			});
		}
		var old_dtcart = $('.exfd-cart-mini').html();
		$.ajax({
			type: 'post',
			url: wc_add_to_cart_params.ajax_url,
			data: data,
			beforeSend: function (response) {
				$button.removeClass('added').addClass('ex-loading');
			},
			complete: function (response) {
				$button.removeClass('ex-loading');
				var new_dtcart = $('.exfd-cart-mini');
				if(old_dtcart == new_dtcart){ return;}
				if (!response.error) {
					$button.addClass('added');
				}
			},
			success: function (response) {
				if (response.error) {
					$("#food_modal .exwoofood-woocommerce > div").append(response.message);
					return;
				} else {
					if(!$('.ex-fdlist.exwf-disable-atctg').length){
						$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $button]);
					}
					$('.woocommerce-notices-wrapper').empty().append(response.notices);
					if($('.ex-fdlist.ex-food-plug .exfd-choice.ex-loading').length){
						$('.ex-fdlist.ex-food-plug .exfd-choice.ex-loading').removeClass('ex-loading');
					}else if($('.ex-fdlist .exstyle-3 .exstyle-3-image .exbt-inline span.ex-loading').length){
						$('.ex-fdlist .exstyle-3 .exstyle-3-image .exbt-inline span.ex-loading').removeClass('ex-loading');
					}
					var new_dtcart = $('.exfd-cart-mini').html();
					if(old_dtcart == new_dtcart){ 
						$button.removeClass('added');
						if($("#food_modal .modal-content").data('close-popup') == 'yes'){
		                	$("#food_modal .ex_close").trigger('click');
		                }
						//return;
					}
					
	                var imgtodrag;
	                var id_parent =$button.closest(".ex-fdlist ").attr('id');
	                var layout = $('#'+id_parent).hasClass('table-layout') ? 'table' : '';
	                imgtodrag = $button.closest("#food_modal").find("img").eq(0);
	                if (imgtodrag.length == 0) {
	                	if (layout!='table') {
		                	imgtodrag = $button.closest(".item-grid").find("img").eq(0);
		                	if (imgtodrag.length == 0) {
		                		imgtodrag = $button.closest(".item-grid").find(".ex-fly-cart").eq(0);
		                		if (imgtodrag.length == 0) {
		                			imgtodrag = $button.closest(".item-grid");
		                		}
		                	}
		                }else{
		                	imgtodrag = $button.closest("tr").find("img").eq(0);
		                	if (imgtodrag.length == 0) {
		                		imgtodrag = $button.closest("tr").find(".item-grid");
		                	}
		                }
	                }
	                if(imgtodrag.length == 0 && jQuery('.ex_modal.exfd-modal-active').length){
	                	imgtodrag = jQuery('.ex_modal.exfd-modal-active .exmd-no-img');
	                }
	                exfd_flytocart(imgtodrag);
	                exf_refresh_cart();
	                if($("#food_modal .modal-content").data('close-popup') == 'yes'){
	                	$("#food_modal .ex_close").trigger('click');
	                }
	                if($button.hasClass('ex-loading')){
	                	$button.removeClass('ex-loading');
	                }
	                $(document).trigger('exwfqv_added_tocart');
				}
			},
		});
		return false;
	});
    
}(jQuery));


/*
jQuery(document).ready(function() {
	jQuery('.ex-fdlist .exfd-shopping-cart').on('click',function($) {
		if (jQuery(window).width() > 700){ return;}
		//console.log('exfd-shopping-cart');
		jQuery('html').addClass('exfd-hidden-scroll');
		const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
		const body = document.body;
		if(!jQuery('.ex_modal.exfd-modal-active').length){
			body.style.position = 'fixed';
			body.style.top = `-${scrollY}`;
		}
	});
	jQuery('.ex-fdlist .exfd-cart-content .exfd-close-cart, .ex-fdlist .exfd-overlay').on("click",function(event){
		if (jQuery(window).width() > 700){ return;}
		//console.log('close-cart');
		if(!jQuery('.ex_modal.exfd-modal-active').length){
			jQuery('html').removeClass('exfd-hidden-scroll');
			const body = document.body;
			const scrollY = body.style.top;
			body.style.position = '';
			body.style.top = '';
			window.scrollTo(0, parseInt(scrollY || '0') * -1);
		}else{
			setTimeout(function(){ jQuery('html').addClass('exfd-hidden-scroll');}, 400);
		}
	});
	jQuery('body .ex-food-plug .ex_modal').on('click', function (event) {
		if (jQuery(window).width() > 700){ return;}
		//console.log(event.target.className);console.log('ex_modal-cart');
		if (event.target.className == 'ex_modal exfd-modal-active' || event.target.className == 'ex_modal') {
			jQuery('html').removeClass('exfd-hidden-scroll');
			const body = document.body;
			const scrollY = body.style.top;
			body.style.position = '';
			body.style.top = '';
			window.scrollTo(0, parseInt(scrollY || '0') * -1);
		}
		
	});
	
	jQuery("body .ex-food-plug #food_modal").on("click", ".ex_close",function(event){
		if (jQuery(window).width() > 700){ return;}
		//console.log('ex_modal-close');
		const body = document.body;
		//if(!jQuery('.exfd-hidden-scroll').length){return;}
		jQuery('html').removeClass('exfd-hidden-scroll');
		const scrollY = body.style.top;
		body.style.position = '';
		body.style.top = '';
		if(scrollY!=''){
			window.scrollTo(0, parseInt(scrollY || '0') * -1);
		}
	});
});
jQuery(document).on("exwfqv_loaded", function(event){
	if (jQuery(window).width() > 700){ return;}
	jQuery('html').addClass('exfd-hidden-scroll');
	const scrollY = document.documentElement.style.getPropertyValue('--scroll-y');
	const body = document.body;
	body.style.position = 'fixed';
	body.style.top = `-${scrollY}`;
});
window.addEventListener('scroll', () => {
  document.documentElement.style.setProperty('--scroll-y', `${window.scrollY}px`);
});

jQuery(document).on("added_to_cart", function(event){jQuery("#food_modal .ex_close").trigger('click');});
*/