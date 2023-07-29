;(function($){
	'use strict';
	function exfd_flytocart(imgtodrag){
		var cart = jQuery('.exfd-shopping-cart');
		if (cart.length == 0) {return;}
	    if (imgtodrag) {
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
	// auto address
	function initialize() {
		var input = document.getElementById('exwf-user-address');
		if(input!=null){
			var autocomplete = new google.maps.places.Autocomplete(input);
			// Set initial restrict to the greater list of countries.
			var limit = jQuery('#exwf_auto_limit').val();
			if(limit!=''){
				autocomplete.setComponentRestrictions({
					country: jQuery.parseJSON(limit),
				});
			}
			google.maps.event.addListener(autocomplete, 'place_changed', function () {
	            var place = autocomplete.getPlace();
				if(place.geometry.location.lat()!='' && place.geometry.location.lng()!=''){
					//document.getElementById('we_latitude_longitude-exc_mb-field-0').value = place.geometry.location.lat()+', '+place.geometry.location.lng();
				}
	        });
		}
	}
	function initialize_bl1() {
		var input = document.getElementById('billing_address_1');
		if(input!=null){
			var dis_auto = jQuery('#exwf_dis_auto').val();
			if(dis_auto=='yes'){return;}
			var autocomplete = new google.maps.places.Autocomplete(input);
			var limit = jQuery('#exwf_auto_limit').val();
			if(limit!=''){
				autocomplete.setComponentRestrictions({country: jQuery.parseJSON(limit),});
			}
			google.maps.event.addListener(autocomplete, 'place_changed', function () {
	            var place = autocomplete.getPlace();
	            var data_address = place.address_components;
	            var i;var $name ='';var $st_nb ='';var $rout ='';var $neig ='';var $city ='';var $country ='';var $postcode ='';
	            for (i = 0; i < data_address.length; ++i) {
	            	if(jQuery.inArray("street_number", data_address[i].types) !== -1){
	            		$st_nb = data_address[i].long_name;
	            	}else if(jQuery.inArray("route", data_address[i].types) !== -1){
	            		$rout = data_address[i].long_name;
	            	}else if(jQuery.inArray("neighborhood", data_address[i].types) !== -1){
	            		$neig = data_address[i].long_name;
	            	}else if(jQuery.inArray("locality", data_address[i].types) !== -1){
	            		$city = data_address[i].long_name;
	            	}else if($city=='' && jQuery.inArray("administrative_area_level_1", data_address[i].types) !== -1){
	            		$city = data_address[i].long_name;
	            	}else if(jQuery.inArray("country", data_address[i].types) !== -1){
	            		$country = data_address[i].short_name;
	            	}else if(jQuery.inArray("postal_code", data_address[i].types) !== -1){
	            		$postcode = data_address[i].long_name;
	            	}
				}
				var $pos_st =  $st_nb!='' ? place.name.indexOf($st_nb) : 0;
        		if($pos_st > 0){
            		$name = ($rout)+' '+ $st_nb + ($neig!='' ? ' '+$neig : '');
            	}else{
            		$name =  $st_nb+' '+($rout) + ($neig!='' ? ' '+$neig : '');
            	}
				jQuery('#billing_address_1').val($name);
			    jQuery('select#billing_country').val($country).trigger('change');
			    jQuery('#billing_city').val($city);
			    jQuery('#billing_postcode').val($postcode);
	        });
		}
	}
	function initialize_sp1() {
		var input = document.getElementById('shipping_address_1');
		if(input!=null){
			var dis_auto = jQuery('#exwf_dis_auto').val();
			if(dis_auto=='yes'){return;}
			var autocomplete = new google.maps.places.Autocomplete(input);
			var limit = jQuery('#exwf_auto_limit').val();
			if(limit!=''){
				autocomplete.setComponentRestrictions({country: jQuery.parseJSON(limit),});
			}
			google.maps.event.addListener(autocomplete, 'place_changed', function () {
	            var place = autocomplete.getPlace();
	            var data_address = place.address_components;
	            var i;var $name ='';var $st_nb ='';var $rout ='';var $neig ='';var $city ='';var $country ='';var $postcode ='';
	            for (i = 0; i < data_address.length; ++i) {
	            	if(jQuery.inArray("street_number", data_address[i].types) !== -1){
	            		$st_nb = data_address[i].long_name;
	            	}else if(jQuery.inArray("route", data_address[i].types) !== -1){
	            		$rout = data_address[i].long_name;
	            	}else if(jQuery.inArray("neighborhood", data_address[i].types) !== -1){
	            		$neig = data_address[i].long_name;
	            	}else if(jQuery.inArray("locality", data_address[i].types) !== -1){
	            		$city = data_address[i].long_name;
	            	}else if($city=='' && jQuery.inArray("administrative_area_level_1", data_address[i].types) !== -1){
	            		$city = data_address[i].long_name;
	            	}else if(jQuery.inArray("country", data_address[i].types) !== -1){
	            		$country = data_address[i].short_name;
	            	}else if(jQuery.inArray("postal_code", data_address[i].types) !== -1){
	            		$postcode = data_address[i].long_name;
	            	}
				}
				var $pos_st =  $st_nb!='' ? place.name.indexOf($st_nb) : 0;
        		if($pos_st > 0){
            		$name = ($rout)+' '+ $st_nb + ($neig!='' ? ' '+$neig : '');
            	}else{
            		$name =  $st_nb+' '+($rout) + ($neig!='' ? ' '+$neig : '');
            	}
				jQuery('#shipping_address_1').val($name);
			    jQuery('select#shipping_country').val($country).trigger('change');
			    jQuery('#shipping_city').val($city);
			    jQuery('#shipping_postcode').val($postcode);
	        });
		}
	}
	if (typeof google === 'object' && typeof google.maps === 'object' && google.maps.event.addDomListener) {
		google.maps.event.addDomListener(window, 'load', initialize);
		google.maps.event.addDomListener(window, 'load', initialize_bl1);
		google.maps.event.addDomListener(window, 'load', initialize_sp1);
	};
	$(document).ready(function() {
		// move popup to body
		jQuery(document).ready(function($) {
			if(jQuery('.ex-fdlist').length && !jQuery('body > .ex-fdlist').length){ 
				jQuery('body').append('<div class="ex-fdlist"></div>');
				jQuery('body > .ex-fdlist').append(jQuery('.exfd-cart-content'));
				jQuery('body > .ex-fdlist').append(jQuery('.exfd-shopping-cart'));
				jQuery('body > .ex-fdlist').append(jQuery('.exfd-overlay'));
				jQuery('body > .ex-fdlist').append(jQuery('#food_modal'));
				if(jQuery('.ex-popup-location.ex-popup-active').length){ 
			  		jQuery('body > .ex-fdlist').append(jQuery('.ex-popup-location.ex-popup-active'));
				}
				jQuery('body > .ex-fdlist').append(jQuery('.exwf-order-method'));
				jQuery('body > .ex-fdlist').append(jQuery('.exwf-opcls-info:not(.exwf-odtype)'));
			}
		});

		if(jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").length>0){
			var date_fm = "mm/dd/yyyy";
			var dis_day = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('disday')+ '';
			var dis_date = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('disdate');
			var fm_date = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('fm');
			if(fm_date!='' && fm_date!= undefined){
				date_fm = fm_date;
			}
			var mindate = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('mindate');
			var fmon = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('fmon');
			var smon = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('smon');
			var sday = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('sday');
			var fiday = jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").data('fiday');
			$.fn.extl_datepicker.dates['en'] = {
			    days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
			    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
			    daysMin: sday,
			    months: fmon,
			    monthsShort: smon,
			    today: "Today",
			    clear: "Clear",
			    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
			    weekStart: fiday
			};
			jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").extl_datepicker({
					"todayHighlight" : true,
					"startDate": mindate!='0' ? new Date(mindate) : new Date(),
					"autoclose": true,
					"format":date_fm,
					"daysOfWeekDisabled": dis_day != 'undefined' && dis_day !='' ? dis_day : '[]',
                    "datesDisabled": dis_date != 'undefined' && dis_date !='' ? dis_date : '[01/01/1000]',
			}).on('show', function(e) {
		        //jQuery('.extl_datepicker-days .disabled[data-date="1621814400000"]').removeClass('disabled');
		    });
			/*
			jQuery(".exwf-deli-field .exwfood-date-deli input[type=text]").extl_datepicker().on(show, function(e) {
		    });*/
		}
		// Start Modal
		var ex_html_width;
		ex_html_width = $('html').width();
		$( window ).resize(function() {
			$('html').css("max-width","");

			ex_html_width = $('html').width();
			if ($(".ex_modal.exfd-modal-active").css("display") =='block') {
				$('html').css("max-width",ex_html_width);
			}
		});
		// popup
		function exwf_open_modal(id_food,id_crsc,param_shortcode,ajax_url,this_click,cart_itemkey) {
			var param = {
				action: 'exwoofood_booking_info',
				id_food: id_food,
				id_crsc: id_crsc,
				cart_itemkey: cart_itemkey,
				param_shortcode: param_shortcode,
			};
			$.ajax({
				type: "post",
				url: ajax_url,
				dataType: 'html',
				data: (param),
				success: function(data){
					if(data != '0'){
						if(data == ''){ 
							$('.row.loadmore').html('error');
						}else{
							var $el_md = '#food_modal';
							if($('body > .ex-fdlist #food_modal').length){
								$el_md = 'body > .ex-fdlist #food_modal';
							}
							$($el_md).empty();
							$($el_md).append(data);
							// Variation Form
			                var form_variation = $($el_md+" .modal-content").find('.variations_form');
			                form_variation.each( function() {
			                    $( this ).wc_variation_form();
			                });
			                // woo_add_pls_mn();
			                form_variation.trigger( 'check_variations' );
			                form_variation.trigger( 'reset_image' );
			                if (typeof $.fn.init_addon_totals === 'function') {
			                	$( 'body' ).find( '.cart:not(.cart_group)' ).each( function() {
									$( this ).init_addon_totals();
								});
			                }
			                // remove loading
			                if(this_click!=false){
								this_click.removeClass('ex-loading');
							}
							$('html').css("max-width",ex_html_width);
					        $("html").fadeIn("slow", function() {
							    $(this).addClass('exfd-hidden-scroll');
							});
							$($el_md).css("display", "block");
							$($el_md).addClass('exfd-modal-active');
							var rtl_mode = $($el_md+" .exfd-modal-carousel").attr('rtl_mode');
							$($el_md+" .exfd-modal-carousel:not(.ex_s_lick-initialized)").EX_ex_s_lick({
								dots: true,
								slidesToShow: 1,
								infinite: true,
								speed: 500,
								fade: true,
								cssEase: 'linear',
								arrows: true,
								rtl:rtl_mode =='yes' ? true : false,
								adaptiveHeight: true,
							});
							if($($el_md+" .woosb-wrap").length){
								$(document).trigger('woosq_loaded');
							}
							setTimeout(function() {
    							var cont_hi = $($el_md+' .ex-modal-big').height();
    						    var img_hi = $($el_md+' .fd_modal_img').height();
    						    if(cont_hi > img_hi && $(window).width() > 767){
    						    	$($el_md+' .ex-modal-big').addClass('ex-padimg');
    						    }
							},10);
						    setTimeout(function() {
							    $($el_md+' .exfd-modal-carousel:not(.exwp-no-galle)').EX_ex_s_lick('setPosition');
							}, 150);
							setTimeout(function() {
								
							    $($el_md+' .exfd-modal-carousel:not(.exwp-no-galle)').EX_ex_s_lick('setPosition');
							}, 300);
							$(document).trigger('exwfqv_loaded');
						}
					}else{$('.row.loadmore').html('error');}
				}
			});
		}
	    $('body').on("click",".ex-fdlist.ex-food-plug:not(.exfdisable-modal) .parent_grid .ctgrid a:not(.mndate-sl), .ex-fdlist:not(.exfdisable-modal) .ctlist a:not(.mndate-sl)", function(event){
	    	event.preventDefault();
	    	var id_crsc = $(this).closest(".ex-fdlist ").attr('id');
	    	var layout = $('#'+id_crsc).hasClass('table-layout') ? 'table' : '';
	    	if ($('#'+id_crsc).hasClass('ex-fdcarousel')) {
	    		layout = 'Carousel';
	    	}
	    	var $this_click;
	    	if (layout != 'table') {
	    		$this_click = $(this).closest(".item-grid");
	    	}else{
	    		$this_click = $(this).closest("tr");
	    	}
	    	if($this_click.hasClass('ex-loading')){ return;}
	    	$('.ex-fdlist .item-grid, .ex-fdlist tr').removeClass('exwf-crfood')
	    	$this_click.addClass('exwf-crfood')
	    	$this_click.addClass('ex-loading');
	    	var id_food = $this_click.data('id_food');
	    	var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
	    	var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
	    	exwf_open_modal(id_food,id_crsc,param_shortcode,ajax_url,$this_click,'');
			return false;
	    });
	    $('body').on("click",".exwf-nepr .exwf-previous:not(.exwf-disclick), .exwf-nepr .exwf-next:not(.exwf-disclick)", function(event){
	    	event.preventDefault();
	    	if($(this).hasClass('exwf-next')){
	    		var $this_click = $('.ex-fdlist .exwf-crfood').next();
	    	}else{
		    	var $this_click = $('.ex-fdlist .exwf-crfood').prev();
		    }
		    if (!$this_click.length) { return }
		    $('.ex-fdlist .item-grid, .ex-fdlist tr').removeClass('exwf-crfood')
		    $this_click.addClass('exwf-crfood')
	    	var id_crsc = $this_click.closest(".ex-fdlist ").attr('id');
	    	var layout = $('#'+id_crsc).hasClass('table-layout') ? 'table' : '';
	    	if ($('#'+id_crsc).hasClass('ex-fdcarousel')) {
	    		layout = 'Carousel';
	    	}
	    	var id_food = $this_click.data('id_food');
	    	var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
	    	var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
	    	exwf_open_modal(id_food,id_crsc,param_shortcode,ajax_url,$this_click,'');
			return false;
	    });
	    $(document).on('exwfqv_loaded', function(event){
			if(jQuery('.exwf-nepr .exwf-previous').length){
				var $next_click = $('.ex-fdlist .exwf-crfood').next();
				if (!$next_click.length) { jQuery('.exwf-nepr .exwf-next').addClass('exwf-disclick'); }
				var $prev_click = $('.ex-fdlist .exwf-crfood').prev();
				if (!$prev_click.length) { jQuery('.exwf-nepr .exwf-previous').addClass('exwf-disclick'); }
			}
			return false;
		});
	    $('body').on("click",".exwf-edit-options", function(event){
	    	event.preventDefault();
	    	
	    	var id_food = $(this).data('id_food');
	    	var item_key = $(this).data('key');
	    	var ajax_url  		= $('.ex-fdlist input[name=ajax_url]').val();
	    	exwf_open_modal(id_food,'','',ajax_url,false,item_key);
			return false;
	    });
	    $(window).on('resize', function(){
	    	var cont_hi = $('#food_modal .ex-modal-big').height();
		    var img_hi = $('#food_modal .fd_modal_img').height();
		    if(cont_hi > img_hi && $(window).width() > 767){
		    	$('#food_modal .ex-modal-big').addClass('ex-padimg');
		    }else{
		    	$('#food_modal .ex-modal-big').removeClass('ex-padimg');
		    }
	    });
	    // cart content
	    $('.exfd-shopping-cart').on("click", function(event){
			event.preventDefault();
			//$('html').addClass('exfd-hidden-scroll');
			$(".exfd-cart-content").addClass('excart-active');
			$(".exfd-overlay").addClass('exfd-overlay-active');
			$( ".exfd-cart-content .exfd-out-notice" ).remove();
			$(document).trigger('exwf_open_sidecart');
			if($('.exfd-cart-mini .ex-fdcarousel').length){
				setTimeout(function(){ 
					jQuery('.exfd-cart-mini .ex-fdcarousel .ctgrid.ex_s_lick-initialized').EX_ex_s_lick('setPosition');
				}, 200);
			}
			return false;
		});	
		$('.exfd-cart-content .exfd-close-cart, .exfd-overlay').on("click",function(event){
			$(".exfd-cart-content").removeClass('excart-active');
			$(".exfd-overlay").removeClass('exfd-overlay-active');
			//$('html').removeClass('exfd-hidden-scroll');
			$(document).trigger('exwf_close_sidecart');
			$( ".exfd-cart-content .exfd-out-notice" ).remove();
			return false;
		});
		$('body').on("click", ".ex-fdlist.ex-food-plug:not(.exfdisable-modal) .exfd-choice", function(event){
			if($(this).prev('.ex-hidden').find('form').length){
				$(this).addClass('ex-loading');
				//$(this).prev('.ex-hidden').find('form button').trigger('click');
				$(this).prev('.ex-hidden').find('.exwoofood-woocommerce form').trigger('submit');
			}else{
				$(this).prev('.ex-hidden').find('a').trigger('click');
			}
			return false;
		});
		$('body').on("click", ".ex-fdlist.ex-food-plug.exfdisable-modal .exfd-choice", function(event){
			var $url = $(this).closest('figure').find('.exfd_modal_click').attr('href');
			if($url!='' && $url!= undefined){ location.href = $url;}
			return false;
		});
		$('body').on("click", ".ex-fdlist.ex-food-plug:not(.exfdisable-modal) .exbt-inline .exstyle-3-button", function(event){
			if($(this).closest('figure').find('.ex-hidden form').length){
				event.preventDefault();
				$(this).addClass('ex-loading');
				//$(this).prev('.ex-hidden').find('form button').trigger('click');
				$(this).closest('figure').find('.ex-hidden form').trigger('submit');
			}else{
				$(this).closest('figure').find('.ex-hidden a').trigger('click');
			}
			return false;
		});
		
	    $(".ex-food-plug #food_modal").on("click", ".ex_close",function(event){
	    	event.preventDefault();
	    	var $this = $(this);
	    	var $el_md = '#food_modal';
			if($('body > .ex-fdlist #food_modal').length){
				$el_md = 'body > .ex-fdlist #food_modal';
			}
	        $($el_md).css("display", "none");
			$('html').removeClass('exfd-hidden-scroll');
			$($el_md).removeClass('exfd-modal-active');
			$('html').css("max-width","");
	    });
		$('.ex-food-plug .ex_modal').on('click', function (event) {
			if (event.target.className == 'ex_modal exfd-modal-active') {
				event.preventDefault();
				$(this).css("display", "none");
				$('html').removeClass('exfd-hidden-scroll');
				$(this).removeClass('exfd-modal-active');
				$('html').css("max-width","");
			}
		});
		// End Modal

		// Js popup location
		if(!$('.exwf-order-method .exwf-opcls-info.exwf-odtype').length){
			var $popup_loc = $(".ex-popup-location");
			var $popup_loc = $(".ex-popup-location");
			if($('.exwf-menu-bydate.ex-popup-location').length){
				$('.exwf-menu-bydate.ex-popup-location').addClass("ex-popup-active");
			}else{
				$popup_loc.addClass("ex-popup-active");
			}
		}
		// End popup location

		// Js Category
		$('.ex-food-plug .ex-menu-list .ex-menu-item').on('click',function(event) {
			event.preventDefault();
        	var $this = $(this);
        	var $parent = $this.closest(".ex-fdlist");
        	if (!$parent.hasClass("category_left")) {
        		$parent.find(".ex-menu-item").removeClass("ex-menu-item-active");
	        	$this.addClass("ex-menu-item-active");
	        	$this.parents('.ex-menu-item').addClass('ex-menu-item-active');
        	}else{
        		$parent.find(".ex-menu-item").removeClass("ex-active-left");
	        	$this.addClass("ex-active-left");
	        	$this.parents('.ex-menu-item').addClass('ex-active-left');
        	}

			var $this_click = $(this);
			var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
			var cat = $this.attr("data-value");
			var key_word = $('#'+id_crsc+' input[name=s]').val();
			var mode = 'search';
			exfd_ajax_search($this_click,key_word,cat,mode);
			return false;
		});

		$('.ex-fdlist.ex-food-plug .ex-menu-select select[name=exfood_menu]').on('change',function(event) {
			event.preventDefault();
			var $this_click = $(this);
			$this_click.closest(".exfd-filter-group").find('.ex-menu-list [data-value="'+$this_click.val()+'"]').trigger('click');
			/*
			var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
			var cat = $('#'+id_crsc+' select[name=exfood_menu]').val();
			var key_word = $('#'+id_crsc+' input[name=s]').val();
			var mode = 'search';
			exfd_ajax_search($this_click,key_word,cat,mode);
			*/
			return false;
		});
		$('.ex-fdlist .exwf-search-form .exwf-s-submit').on('click',function(event) {
			event.preventDefault();
			var $this_click = $(this);
			var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
			if($("#"+id_crsc+" .ex-menu-item-active").length){
				$this_click.closest(".ex-fdlist").find('.ex-menu-item-active').trigger('click');
			}else{
				var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
				var cat = '';
				var key_word = $('#'+id_crsc+' input[name=s]').val();
				var mode = 'search';
				exfd_ajax_search($this_click,key_word,cat,mode);
			}
			return false;
		});

		// Js SEARCH
		function exfd_ajax_search($this_click, $key_word,$cat,mode){
			var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
			var layout = $('#'+id_crsc).hasClass('table-layout') ? 'table' : '';
			if($('#'+id_crsc).hasClass('ex-loading')){ return;}
			$('#'+id_crsc).addClass('ex-loading');
			if($('#'+id_crsc).hasClass('list-layout')){ layout = 'list';}
			var param_query  		= $('#'+id_crsc+' input[name=param_query]').val();
			var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
			var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
			var param = {
				action: 'exfood_menuegory',
				param_query: param_query,
				id_crsc: id_crsc,
				param_shortcode: param_shortcode,
				layout: layout,
				key_word: $key_word,
				cat: $cat,
			};
			$.ajax({
				type: "post",
				url: ajax_url,
				dataType: 'json',
				data: (param),
				success: function(data){
					if(data != '0')
					{
						if($('#'+id_crsc+' .ex-loadmore').length){
							var $loadmore=1;
							if(data.page_navi =='off'){
								$('#'+id_crsc+' .ex-loadmore .loadmore-exfood').remove();
							}else{
								$('#'+id_crsc+' .ex-loadmore').remove();	
							}
							
						};
						$('#'+id_crsc+' input[name=num_page_uu]').val('1');
						$('#'+id_crsc+' input[name=current_page]').val('1');
						var $showin='';
						if(layout=='table'){
							$showin = $('#'+id_crsc+' table tbody');
						}else if(layout=='list'){
							$showin = $('#'+id_crsc+' .ctlist');
						}else{
							$showin = $('#'+id_crsc+' .ctgrid');
						}
						
						if(data.page_navi !='' && data.page_navi !='off'){
							if ($loadmore ==1) {
								$('#'+id_crsc).append(data.page_navi);
							}
							else{
								$('#'+id_crsc+' .exfd-pagination').fadeOut({
									duration:0,
									complete:function(){
										$( this ).remove();
									}
								});
								$('#'+id_crsc+' .exfd-pagination-parent').append(data.page_navi);
							}
						}else if(data.page_navi=='off'){
								$('#'+id_crsc+' .exfd-pagination .page-navi').fadeOut({
									duration:0,
									complete:function(){
										$( this ).remove();
									}
								});
						}
						$('#'+id_crsc).removeClass('ex-loading');
						$($showin).fadeOut({
							duration:0,
							complete:function(){
								$( this ).empty();
								$showin.append(data.html_content).fadeIn();
							}
						});
						
						if($('#'+id_crsc+' .exwf-dcat').length){
							$('#'+id_crsc+' .exwf-dcat').remove();
						}
						if(layout=='table'){
							$(data.html_dcat).insertBefore('#'+id_crsc+' .ctlist');
						}else if (layout=='list' && $('#'+id_crsc+'.category_left').length ){
							$('#'+id_crsc+' .ctlist').prepend(data.html_dcat);
						}else{
							$(data.html_dcat).insertBefore($showin);
						}
						if(data.html_modal!=''){
							$('#'+id_crsc+' .ex-hidden .exp-mdcontaner').fadeOut({
								duration:0,
								complete:function(){
									$( this ).empty();
								}
							});
							$('#'+id_crsc+' .ex-hidden .exp-mdcontaner').append(data.html_modal).fadeIn();
						}
						exfd_loadmore();
					}else{$('#'+id_crsc+' .loadmore-exfood').html('error');}
				}
			});
			
		};
		// END SEARCH

		// Load more
		function exfd_loadmore(){
			$('.ex-food-plug .loadmore-exfood').on('click',function() {
				if($(this).hasClass('disable-click')){
					return;
				}
				var $this_click = $(this);
				var id_crsc  = $this_click.closest(".ex-fdlist").attr('id');
				exfd_ajax_load_page('loadmore' ,$this_click,id_crsc,'');
			});
		}
		exfd_loadmore();
		// Page number
		$('.ex-fdlist.ex-food-plug .exfd-pagination-parent').on('click','.page-numbers',function(event) {
			event.preventDefault();
			var $this_click = $(this);
			var id_crsc  		= $this_click.closest(".ex-fdlist").attr('id');
			$('#'+id_crsc+' .page-numbers').removeClass('current');
			$($this_click).addClass('current');
			var page_link = $this_click.text();
			if(page_link*1 > 1){
				$('#'+id_crsc+' .prev-ajax').removeClass('disable-click');
			}
			$('#'+id_crsc+' .next-ajax').removeClass('disable-click');
			exfd_ajax_load_page('page_link',$this_click,id_crsc,page_link);
		});
		$('.ex-fdlist.ex-food-plug .exfd-pagination-parent').on('click','.next-ajax',function(event) {
			event.preventDefault();
			var $this_click = $(this);
			var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
			var $current =  $('#'+id_crsc+' .current');
			var current_page =  $current.text();
			$('#'+id_crsc+' .prev-ajax').removeClass('disable-click');

			$current.removeClass('current');
			$current.next().addClass('current');
			var page_link = current_page*1+1;
			exfd_ajax_load_page('page_link',$this_click,id_crsc,page_link);
			$this_click.removeClass('disable-click');
		});
		$('.ex-fdlist.ex-food-plug .exfd-pagination-parent').on('click','.prev-ajax',function(event) {
			event.preventDefault();
			var $this_click = $(this);
			var id_crsc = $this_click.closest(".ex-fdlist").attr('id');
			var $current =  $('#'+id_crsc+' .page-navi .current');
			var current_page =  parseInt($current.text());
			$('#'+id_crsc+' .next-ajax').removeClass('disable-click');
			if (current_page == 1) {
				$('#'+id_crsc+' .prev-ajax').addClass('disable-click');
				return false;
			}
			$current.removeClass('current');
			$current.prev().addClass('current');
			var page_link = current_page-1;
			exfd_ajax_load_page('page_link',$this_click,id_crsc,page_link);
			if(page_link*1 > 1){
				$('#'+id_crsc+' .prev-ajax').removeClass('disable-click');
			}
		});
		function exfd_ajax_load_page($style,$this_click,id_crsc,page_link){
			if($style !='loadmore'){
				$('#'+id_crsc+' .page-numbers').removeClass('disable-click');
			}
			$this_click.addClass('disable-click');
			var n_page = $('#'+id_crsc+' input[name=num_page_uu]').val();
			if($style=='loadmore'){
				$('#'+id_crsc+' .loadmore-exfood').addClass('ex-loading');
			}else{
				$('#'+id_crsc).addClass('ex-loading');
			}
			var layout = $('#'+id_crsc).hasClass('table-layout') ? 'table' : '';
			if($('#'+id_crsc).hasClass('list-layout')){ layout = 'list';}
			var param_query  		= $('#'+id_crsc+' input[name=param_query]').val();
			var param_ids  		= $('#'+id_crsc+' input[name=param_ids]').val();
			var page  		= $('#'+id_crsc+' input[name=current_page]').val();
			var num_page  		= $('#'+id_crsc+' input[name=num_page]').val();
			var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
			var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
			
				var param = {
					action: 'exwoofood_loadmore',
					param_query: param_query,
					param_ids: param_ids,
					id_crsc: id_crsc,
					page: page_link!='' ? page_link : page*1+1,
					param_shortcode: param_shortcode,
					layout: layout,
				};
				$.ajax({
					type: "post",
					url: ajax_url,
					dataType: 'json',
					data: (param),
					success: function(data){
						if(data != '0')
						{
							if($style=='loadmore'){
								n_page = n_page*1+1;
								$('#'+id_crsc+' input[name=num_page_uu]').val(n_page)
								if(data.html_content == ''){ 
									$('#'+id_crsc+' .loadmore-exfood').remove();
								}else{
									$('#'+id_crsc+' input[name=current_page]').val(page*1+1);
									if(layout=='table'){
										var $g_container = $('#'+id_crsc+' table tbody');
										$g_container.append(data.html_content);
									}else if(layout=='list'){
										var $g_container = $('#'+id_crsc+' .ctlist');
										$g_container.append(data.html_content);
									}else{
										var $g_container = $('#'+id_crsc+' .ctgrid');
										$g_container.append(data.html_content);
										setTimeout(function(){ 
											$('#'+id_crsc+' .item-grid').addClass("active");
										}, 200);
									}
									$('#'+id_crsc+' .loadmore-exfood').removeClass('ex-loading');
									$this_click.removeClass('disable-click');
								}
								if(n_page == num_page){
									$('#'+id_crsc+' .loadmore-exfood').remove();
								}
							}else{
								var $showin ='';
								if(layout=='table'){
									$showin = $('#'+id_crsc+' table tbody');
								}else if(layout=='list'){
									$showin = $('#'+id_crsc+' .ctlist');
								}else{
									$showin = $('#'+id_crsc+' .ctgrid');
								}
								$($showin).fadeOut({
									duration:0,
									complete:function(){
										$( this ).empty();
									}
								});
								$('#'+id_crsc).removeClass('ex-loading');
								$showin.append(data.html_content).fadeIn();

							}
							if(data.html_modal!=''){
								
								$('#'+id_crsc+' .ex-hidden .exp-mdcontaner').append(data.html_modal).fadeIn();
							}
							if($('#'+id_crsc).hasClass('extp-masonry') && !$('#'+id_crsc).hasClass('column-1')){
								if (typeof imagesLoaded === "function"){
									$('#'+id_crsc+'.extp-masonry .ctgrid').imagesLoaded( function() {
										$('#'+id_crsc+'.extp-masonry .ctgrid').masonry('reloadItems');
										$('#'+id_crsc+'.extp-masonry .ctgrid').masonry({
											isInitLayout : false,
											horizontalOrder: true,
											itemSelector: '.item-grid'
										});
									});
								}
							}
						}else{$('#'+id_crsc+' .loadmore-exfood').html('error');}
					}
				});
			return false;	
		}
		// end paging
		// Carousel
		function exfd_carousel(id_clas,infinite,start_on,rtl_mode,slidesshow,slidesscroll,auto_play,auto_speed,mobile_items,tb_items){
			if(jQuery(id_clas).hasClass('ex_s_lick-initialized')){
				return;
			}
		  jQuery(id_clas).EX_ex_s_lick({
			infinite: infinite,
			initialSlide:start_on,
			rtl: rtl_mode =='yes' ? true : false,
			prevArrow:'<button type="button" class="ex_s_lick-prev"></button>',
			nextArrow:'<button type="button" class="ex_s_lick-next"></button>',	
			slidesToShow: slidesshow,
			slidesToScroll: slidesscroll,
			dots: true,
			autoplay: auto_play==1 ? true : false,
			autoplaySpeed: auto_speed!='' ? auto_speed : 3000,
			arrows: true,
			centerMode:  false,
			focusOnSelect: false,
			ariableWidth: true,
			adaptiveHeight: true,
			responsive: [
			  {
				breakpoint: 1024,
				settings: {
				  slidesToShow: slidesshow,
				  slidesToScroll: slidesscroll,
				}
			  },
			  {
				breakpoint: 768,
				settings: {
				  slidesToShow: (tb_items!='' ? tb_items : 2),
				  slidesToScroll: 1
				}
			  },
			  {
				breakpoint: 480,
				settings: {
				  slidesToShow: mobile_items,
				  slidesToScroll: 1
				}
			  }
			]
			  
		  });
		}
		jQuery('.ex-fdcarousel').each(function(){
			var $this = jQuery(this);
			var id =  $this.attr('id');
			var slidesshow =  $this.data('slidesshow');
			var slidesscroll =  $this.data('slidesscroll');
			if(slidesshow==''){ slidesshow = 3;}
			if (slidesscroll==''){ slidesscroll = slidesshow;}
			var startit =  $this.data('startit') > 0 ? $this.data('startit') : 1;
			var auto_play = $this.data('autoplay');
			var auto_speed = $this.data('speed');
			var rtl_mode = $this.data('rtl');
			var mobile_items = $this.data('mobile_item') > 1 ? $this.data('mobile_item') : 1;
			var start_on =  $this.data('start_on') > 0 ? $this.data('start_on') : 0;
			if($this.data('infinite')=='0'){
			  var infinite = 0;
			}else{
			  var infinite =  $this.data('infinite') == 'yes' || $this.data('infinite') == '1' ? true : false;
			}
			exfd_carousel('#'+id+' .ctgrid',infinite,start_on,rtl_mode,slidesshow,slidesscroll,auto_play,auto_speed,mobile_items,'');
		});
		// filter slider
		if($('.exfd-filter.exwf-filter-slider').length){
			jQuery('.exfd-filter.exwf-filter-slider .ex-menu-list').each(function(){
				var $this = $(this);
				var w_pr = $this.width();
				var t_width = 0;
				$(this).children().outerWidth(function(i,w){t_width+=w;});
				if(t_width > w_pr){
					jQuery($this).closest('.exfd-filter-group').addClass('act-exslick');
					jQuery($this).EX_ex_s_lick({
						dots: false,
						infinite: false,
						prevArrow:'<button type="button" class="exslick-pre"><i class="icon ion-chevron-left"></i></button>',
						nextArrow:'<button type="button" class="exslick-nex"><i class="icon ion-chevron-right"></i></button>',
						speed: 300,
						slidesToShow: 1,
						centerMode: false,
						variableWidth: true,
						focusOnSelect: false,
					});
				}
			});
		}

		jQuery(window).on('load', function(){
			jQuery('.ex-fdcarousel.ld-screen').each(function(){
	            jQuery(this).addClass('at-childdiv');
	        });
	        jQuery('.ex-fdck.ld-screen').each(function(){
	            jQuery(this).addClass('at-childdiv');
	        });
        });
        setTimeout(function() {
            jQuery('.ex-fdcarousel.ld-screen').each(function(){
	            jQuery(this).addClass('at-childdiv');
	        });
        }, 4000);
		// End Carousel
		var def_loc = $('.ex-loc-select').val();
		$('.ex-loc-select:not(.exwf-disable-red)').on('change', function () {
			var $this = $(this);
			var url = $this.val(); // get selected value
			var tex = $this.attr('data-text');
			var nb_item = parseInt($('.exfd-cart-num').html());
			if (def_loc != null && url != def_loc && tex!= undefined && nb_item > 0) { // require a URL
				if(confirm(tex)){
					window.location = url; // redirect
				}else{
					$this.val(def_loc);
				}
			}else if (url != def_loc){
				window.location = url; // redirect
			}
			return false;
		});
		$('.exwf-menuof-date:not(.exwf-disable-red) .mndate-sl').on('click', function () {
			var tex = $(this).attr('data-text');
			var nb_item = parseInt($('.exfd-cart-num').html());
			if ( tex!= undefined && nb_item > 0) {
				return confirm(tex);
			}
		});
		$('body').on('change', '.ex-loc-select.exwf-mn-timesl', function () {
			$('.exwf-menu-bydate').addClass('ex-loading');
			var $this = $(this);
			var $date_f = $(this).find(':selected').attr('data-date');
			var $cr_url = $(this).attr('data-current_url');
			var param = {
				action: 'exwf_menu_by_timesl',
				date: $date_f,
				cr_url: $cr_url,
			};
			var $url_ajax = exwf_jspr.ajaxurl;
			$.ajax({
				type: "post",
				url: $url_ajax,
				dataType: 'json',
				data: (param),
				success: function(data){
					$('.exwf-menu-bydate').removeClass('ex-loading');
					if(data.html_content==''){
						var $url = $this.find(':selected').val();
						window.location = $url;
					}else{
						$('.exwf-mnsl').remove();
						$('.exwf-menu-bydate .ex-popup-info').append(data.html_content);
					}
				}
			});
			
		});
		// check delivery time status
		function exwf_get_crtime( offset) {
		    var d = new Date();
		    var utc = d.getTime() / 1000;
		    var _curent_time = utc + (3600*offset);
		    // return time unix
		    return _curent_time;
		}
		function exwf_date_time_delivery_status(){
			var _datecr = $('.exwf-deli-field select#exwfood_time_deli').data('date');
			if($('.exwf-deli-field select[name=exwfood_date_deli]').length){
				var _date_del = $('.exwf-deli-field select[name=exwfood_date_deli]').val();
				_datecr = _date_del;
			}else if($('.exwf-deli-field input[name=exwfood_date_deli]').length){
				var _datefm = $('.exwf-deli-field input#exwfood_date_deli').data('fm');
				var _date_del = $('.exwf-deli-field input[name=exwfood_date_deli]').val();
				if(_date_del!=''){
					if(_datefm == 'dd-mm-yyyy'){
						var $_cv_ddel = _date_del.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3");
						_datecr = new Date($_cv_ddel+' 00:00:00+0000').getTime() / 1000;
					}else{
						_datecr = new Date(_date_del+' 00:00:00+0000').getTime() / 1000;
					}
					_date_del = _datecr;
				}
			}
			var _time_del = $('.exwf-deli-field select[name=exwfood_time_deli]').val();
			/*
			if(_datecr!=''){
				var $data_tslot = $('.exwf-deli-field select#exwfood_time_deli').data('time');
				var $date_check = '';
				$('.exwf-deli-field select#exwfood_time_deli option').prop('disabled', false);
				$.each($data_tslot, function (key, value) {
					if(value['start-time']!=undefined && value['start-time']!=''){
						var $cv_time = value['start-time'].split(':');
						var _timecr = $('.exwf-deli-field select#exwfood_time_deli').data('crtime');
						$cv_time = (+$cv_time[0]) * 60 * 60 + (+$cv_time[1]) * 60; 
						$date_check = _datecr*1 + $cv_time*1;
						if(_timecr > $date_check){
							var _opsl = value['name-ts']!=undefined && value['name-ts']!='' ? 'select#exwfood_time_deli option[value="'+ value['name-ts']+'"]' : 'select#exwfood_time_deli option[value^="'+ value['start-time']+'"]';
							$(_opsl).attr('disabled','disabled');
						}
					}
				});
			}*/
			if($('.exwf-loc-field select[name=exwoofood_ck_loca]').length){
				var _loc = $('.exwf-loc-field select[name=exwoofood_ck_loca]').val();
			}else{
				_loc = '';
			}
			if(_date_del!='' && _time_del!=''){
				$('.exwf-deli-field').addClass('ex-loading');
				var param = {
					action: 'exwf_time_delivery_status',
					date: _date_del,
					time: _time_del,
					loc: _loc,
				};
				var $url_ajax = exwf_jspr.ajaxurl;
				$.ajax({
					type: "post",
					url: $url_ajax,
					dataType: 'json',
					data: (param),
					success: function(data){
						$('.exwf-deli-field').removeClass('ex-loading');
						if(data.html_content != null){
							$('.exwf-deli-field p.exwf-time-stt').remove();
							$('.exwf-deli-field').append(data.html_content);
						}else{
							$('.exwf-deli-field p.exwf-time-stt').remove();
						}
						if(data.refresh_order && data.refresh_order == true){
							$( 'body' ).trigger( 'update_checkout' );
						}
					}
				});
			}
		}
		function exwf_time_delivery_slots(){
			if(!$('.exwf-deli-field #exwfood_time_deli').length || $('.exwf-deli-field .exwfood-time-deli').hasClass('exwf-mn-timesl') ){
				return;
			}
			$('.exwf-deli-field p.exwf-time-stt').remove();
			var _datecr = $('.exwf-deli-field select#exwfood_time_deli').data('date');
			if($('.exwf-deli-field select[name=exwfood_date_deli]').length){
				var _date_del = $('.exwf-deli-field select[name=exwfood_date_deli]').val();
				_datecr = _date_del;
			}else if($('.exwf-deli-field input[name=exwfood_date_deli]').length){
				var _datefm = $('.exwf-deli-field input#exwfood_date_deli').data('fm');
				var _date_del = $('.exwf-deli-field input[name=exwfood_date_deli]').val();
				if(_date_del!=''){
					var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
					if(_datefm == 'dd-mm-yyyy'){
						//var $_cv_ddel = _date_del.replace( /(\d{2})-(\d{2})-(\d{4})/, "$2/$1/$3");
						if(is_safari){
							var $_cv_ddel = _date_del.replace( /(\d{2})-(\d{2})-(\d{4})/, "$3/$2/$1");
						}else{
							var $_cv_ddel = _date_del.replace( /(\d{2})-(\d{2})-(\d{4})/, "$3-$2-$1");
						}
						_datecr = new Date($_cv_ddel+' 00:00:00+0000').getTime() / 1000;
					}else{
						if(is_safari){
							var $_cv_ddel = _date_del.replace( /(\d{2})\/(\d{2})\/(\d{4})/, "$3/$1/$2");//alert($_cv_ddel);
						}else{
							var $_cv_ddel = _date_del.replace( /(\d{2})\/(\d{2})\/(\d{4})/, "$3-$1-$2");//alert($_cv_ddel);
						}
						_datecr = new Date($_cv_ddel+' 00:00:00+0000').getTime() / 1000;
					}
					_date_del = _datecr;
				}
			}
			if($('.exwf-loc-field select[name=exwoofood_ck_loca]').length){
				var _loc = $('.exwf-loc-field select[name=exwoofood_ck_loca]').val();
			}else{
				_loc = '';
			}
			if(_date_del!=''){
				$('.exwf-deli-field').addClass('ex-loading');
				var param = {
					action: 'exwf_time_delivery_slots',
					date: _date_del,
					loc: _loc,
				};
				var slted_time = $('.exwf-deli-field select#exwfood_time_deli option:selected').val();
				var $url_ajax = exwf_jspr.ajaxurl;
				$.ajax({
					type: "post",
					url: $url_ajax,
					dataType: 'json',
					data: (param),
					success: function(data){
						$('.exwf-deli-field').removeClass('ex-loading');
						if(data.html_timesl != null && data.html_timesl !=''){
							//$('.exwf-deli-field select#exwfood_time_deli').html(data.html_timesl);
							$('#exwfood_time_deli_field .woocommerce-input-wrapper').html(data.html_timesl);
							if(slted_time!=undefined && $('.exwf-deli-field select#exwfood_time_deli option[value="'+slted_time+'"]').length){
								$('.exwf-deli-field select#exwfood_time_deli').val(slted_time);
							}
							/*setTimeout(function(){ 
								exwf_date_time_delivery_status();
							}, 200);*/
						}
						if(data.data_time != null && data.data_time !=''){
							$('.exwf-deli-field select#exwfood_time_deli').attr('data-time',data.data_time);
						}
					}
				});
			}
		}
		jQuery(document).ready(function($) {
			var contenId = '#exwfood_date_deli';
			if($(contenId).length){
				$(contenId).trigger('change');
			}
		});
		$('body').on('change', '.exwf-deli-field select#exwfood_time_deli', function() {
			exwf_date_time_delivery_status();	
		});
		$('.exwf-deli-field select#exwfood_date_deli').on('change',function() {
			exwf_time_delivery_slots();	
		});
		$('.exwf-loc-field select[name=exwoofood_ck_loca]').on('change',function() {
			exwf_time_delivery_slots();	
		});

		if(!jQuery('.exwf-deli-field #exwfood_date_deli').length && $('.exwf-deli-field select[name=exwfood_time_deli]').length){
			exwf_date_time_delivery_status();
		}
		jQuery('.exwf-deli-field input[name=exwfood_date_deli]').on('propertychange change keyup paste input', function() {
		    //exwf_date_time_delivery_status();
		    exwf_time_delivery_slots();
		});
		if($('.exwf-deli-field select#exwfood_time_deli').length){
			var $_timecr = $('.exwf-deli-field select#exwfood_time_deli').data('crtime');
			if($_timecr!='' && !isNaN($_timecr)){
				var timeleft = 300;
				var downloadTimer = setInterval(function(){
					if(timeleft <= 0){
					    clearInterval(downloadTimer);
					} else {
						$_timecr = $_timecr + 1;
						$('.exwf-deli-field select#exwfood_time_deli').attr('data-crtime',$_timecr);
					}
					timeleft -= 1;
				}, 1000);
			}
		}
		// update checkout when change location
		function exwf_update_shipping_fee(){
			var $_loc = $('.exwf-loc-field select').val();
			if($_loc==''){
				//return
			}
			$('.exwf-loc-field').addClass('ex-loading');
			var _addte = jQuery('.woocommerce-checkout #billing_address_1').val();
			var _city = jQuery('.woocommerce-checkout #billing_city').val();
			var _country = jQuery('.woocommerce-checkout #billing_country').val();
			var param = {
				action: 'exwf_update_shipping_fee',
				loc: $_loc,
				address: _addte!=undefined ? _addte : '',
				city: _city!=undefined ? _city : '',
				country: _country!=undefined ? _country : '',
			};
			var $url_ajax = exwf_jspr.ajaxurl;
			$.ajax({
				type: "post",
				url: $url_ajax,
				dataType: 'json',
				data: (param),
				success: function(data){
					$('.exwf-loc-field').removeClass('ex-loading');
					$( 'body' ).trigger( 'update_checkout' );
				}
			});
		}
		$('body').on('change', '.exwf-loc-field select', function() {
			if(!jQuery('.exwf-order-take.at-method').length){
				exwf_update_shipping_fee();	
			}
		});
		if(!jQuery('.exwf-order-take.at-method').length && !jQuery('.exwf-order-dinein.at-method').length && jQuery('.woocommerce-checkout #billing_address_1').length){
			jQuery('.woocommerce-checkout #billing_address_1').addClass('exwf-re-upsh');
			if(jQuery('.woocommerce-checkout #ship-to-different-address-checkbox').is(':checked')){
				var _addte = jQuery('.woocommerce-checkout #shipping_address_1').val();
				var _city = jQuery('.woocommerce-checkout #shipping_city').val();
				var _country = jQuery('.woocommerce-checkout #shipping_country').val();
			}else{
				var _addte = jQuery('.woocommerce-checkout #billing_address_1').val();
				var _city = jQuery('.woocommerce-checkout #billing_city').val();
				var _country = jQuery('.woocommerce-checkout #billing_country').val();
			}
			$('body').on('blur', '.woocommerce-checkout #billing_address_1, .woocommerce-checkout #shipping_address_1', function() {
				if(jQuery('.woocommerce-checkout #ship-to-different-address-checkbox').is(':checked')){
					var _addte_ch = jQuery('.woocommerce-checkout #shipping_address_1').val();
					var _city_ch = jQuery('.woocommerce-checkout #shipping_city').val();
					var _country_ch = jQuery('.woocommerce-checkout #shipping_country').val();
				}else{
					var _addte_ch = jQuery('.woocommerce-checkout #billing_address_1').val();
					var _city_ch = jQuery('.woocommerce-checkout #billing_city').val();
					var _country_ch = jQuery('.woocommerce-checkout #billing_country').val();
				}
				var _loc = jQuery('.exwf-loc-field select').val();
				if(jQuery('.woocommerce-checkout #billing_address_1').hasClass('exwf-re-upsh') || _addte_ch!= _addte || _city!=_city_ch || _country_ch!=_country){
					jQuery('.woocommerce-checkout #billing_address_1').removeClass('exwf-re-upsh');
					_addte = _addte_ch;
					_city = _city_ch;
					_country = _country_ch;
					// start caculate
					var param = {
						action: 'exwf_update_shipping_fee_bykm',
						address: _addte,
						city: _city,
						country: _country,
						loc: _loc,
					};
					var $url_ajax = exwf_jspr.ajaxurl;
					$.ajax({
						type: "post",
						url: $url_ajax,
						dataType: 'json',
						data: (param),
						success: function(data){
							if(data!='unc'){
								$( 'body' ).trigger( 'update_checkout' );
							}
						}
					});
				}
			});
			$(window).on('scroll', function() {
				if (!$(".woocommerce-checkout #billing_address_1").is(":focus")) {
					$(".woocommerce-checkout #billing_address_1").trigger('blur');
				}
			});
		}

		$('body').on('click','.exwo-showmore',function(event) {
			$(this).remove();
			$('.exwo-product-options').addClass('exwo-show');
		});
		jQuery('body').on('click', '.exwf-user-dl-info a', function (event) {
			jQuery( document.body ).trigger( 'wc_fragment_refresh' );
            return;
        });
        // qunatity field for side cart
		jQuery('body').on('click', '.exfd-cart-mini .exwf-quantity #exminus_ticket',function() {
			var $this = jQuery(this);
			var $val = parseInt(jQuery(this).closest(".exwf-con-quantity").find('.qty').val()) - 1;
			if($val>0){
				var $key = $(this).closest(".exwf-con-quantity").attr("data-cart_key");
				var $def_val = $(this).closest(".exwf-con-quantity").attr("data-quantity");
				if($val>= 0 && $val!= $def_val){
					exwf_update_quantity($this,$val,$key);
				}
			}
		});
		jQuery('body').on('click', '.exfd-cart-mini #explus_ticket',function() {
			var $this = jQuery(this);
			var $val = parseInt(jQuery(this).closest(".exwf-con-quantity").find('.qty').val()) + 1;
			var $key = $(this).closest(".exwf-con-quantity").attr("data-cart_key");
			var $def_val = $(this).closest(".exwf-con-quantity").attr("data-quantity");
			if($val>= 0 && $val!= $def_val){
				exwf_update_quantity($this,$val,$key);
			}
		});
		jQuery('body').on('keyup change', '.exfd-cart-mini .exwf-quantity .quantity .qty', function(){ 
			var $this = jQuery(this);
			var $val = $(this).val();
			var $key = $(this).closest(".exwf-con-quantity").attr("data-cart_key");
			var $def_val = $(this).closest(".exwf-con-quantity").attr("data-quantity");
			if($val>= 0 && $val!= $def_val){
				exwf_update_quantity($this,$val,$key);
			}

		});
		function exwf_update_quantity($this,$val,$key){
			var min = $this.closest(".exwf-con-quantity").find('.qty').attr('min');
			var max = $this.closest(".exwf-con-quantity").find('.qty').attr('max');
			if((max!='' && $val > max) || (min!='' && $val < min) ){
				return;
			}
			$this.closest(".exwf-con-quantity").find('.qty').val($val);
			$('.exfd-cart-mini').addClass('ex-loading');
			$( ".exfd-cart-content .exfd-out-notice" ).remove();
			var param = {
				action: 'exwf_update_quantity',
				quantity: $val,
				key: $key,
			};
			var $url_ajax = exwf_jspr.ajaxurl;
			$.ajax({
				type: "post",
				url: $url_ajax,
				dataType: 'json',
				data: (param),
				success: function(data){
					if(data.message && data.message!=''){
						$(data.message).insertAfter( $( ".exfd-cart-content .exfd-close-cart" ) );
						setTimeout(function(){ $( ".exfd-cart-content .exfd-out-notice" ).remove(); }, 5000);
					}
					jQuery( document.body ).trigger( 'wc_fragment_refresh' );
					$('.exfd-cart-mini').removeClass('ex-loading');
            		return;
				}
			});
		};
		exwf_menugroup_scroll();
		$(document).scroll(function() {
			exwf_menugroup_scroll();
		});
		function exwf_menugroup_scroll(){
			if(!$(".exwf-mngroup").length){
				return;
			}
			$(".exwf-mngroup ").each(function(){
				var $this = $(this);
				var $w = $this.width();
				$this.find('.exfd-filter').width($w);
				var Id_tm = jQuery(this).attr("id");
				var $tl_top = $this.offset().top;
				var $tl_end = $tl_top + $this.height();
				$tl_top =  $tl_top -50;
				$tl_end =  $tl_end -100;
				if (($(document).scrollTop() >= $tl_top) && ($(document).scrollTop() <= $tl_end)) {
					$("#"+Id_tm+" .exwf-mngrfilter").addClass('exsticky').fadeIn();
				}else{
					$("#"+Id_tm+" .exwf-mngrfilter").removeClass('exsticky').fadeOut();
				}
				$("#"+Id_tm+" .exwf-mngr-content .exwf-mngr-item").each(function(){
					var $this_item = $(this);
					var $menu = $(this).attr("data-menu");
					var $it_top = $this_item.offset().top;
					var $it_end = $it_top + $this_item.height();
					$it_top =  $it_top -50;
					$it_end =  $it_end -50;
					if (($(document).scrollTop() > $it_top) && ($(document).scrollTop() < $it_end)) {
						$("#"+Id_tm+" .exwf-mngrfilter .filtermngr-item[data-menu="+$menu+"]").addClass('ex-menu-item-active');
						//$("#"+Id_tm+" .exwf-mngrfilter .ex-menu-select option[value="+$menu+"]").attr('selected','selected');
						$("#"+Id_tm+" .exwf-mngrfilter .ex-menu-select select").val($menu);
					}else{
						$("#"+Id_tm+" .exwf-mngrfilter .filtermngr-item[data-menu="+$menu+"]").removeClass('ex-menu-item-active');
						//$("#"+Id_tm+" .exwf-mngrfilter .ex-menu-select option[value="+$menu+"]").removeAttr('selected');
					}
				});
			});
		};
		$('.exwf-mngroup').on('click', '.exwf-mngrfilter .filtermngr-item', function () {
			var idsc = $(this).attr('data-id');
			var $menu = $(this).attr("data-menu");
			if($menu=='exmmore'){
				var windowHeight = $(window).height();
				$('html,body').animate({
					scrollTop: $("#"+idsc+" .exwf-mngr-endel").offset().top - 30},
					'slow');
			}else if($("#"+idsc+" .exwf-mngr-item[data-menu="+$menu+"]").length){
				var windowHeight = $(window).height();
				$('html,body').animate({
					scrollTop: $("#"+idsc+" .exwf-mngr-item[data-menu="+$menu+"]").offset().top - 30},
					'slow');
			}
		});
		$('.exwf-mngrfilter').on('change', 'select', function() {
			var idsc = $(this).attr('data-id');
			var $menu = $(this).val();
			if($menu=='exmmore'){
				var windowHeight = $(window).height();
				$('html,body').animate({
					scrollTop: $("#"+idsc+" .exwf-mngr-endel").offset().top - 30},
					'slow');
			}else if($("#"+idsc+" .exwf-mngr-item[data-menu="+$menu+"]").length){
				var windowHeight = $(window).height();
				$('html,body').animate({
					scrollTop: $("#"+idsc+" .exwf-mngr-item[data-menu="+$menu+"]").offset().top - 30},
					'slow');
			}
		});
		if($('.exwf-mngroup-more').length){
			function exwf_isScrolledInto_View($elem){ //in visible
				var docViewTop = jQuery(window).scrollTop();
				var docViewBottom = docViewTop + jQuery(window).height();
				var elemTop = $elem.offset().top;
				var elemBottom = elemTop + $elem.height();
				return ((elemBottom <= docViewBottom + 200) && (elemTop >= docViewTop));
			}
			function exwf_mngroup_more(){
				jQuery('.exwf-mngroup-more').each(function(){
					var $this = $(this);
					var $endel = $this.children('.exwf-mngr-endel');
					if (exwf_isScrolledInto_View($endel) && !$this.hasClass('ex-loading')){
						$this.find('.exwf-mngrfilter .filtermngr-item[data-menu="exmmore"]').remove();
						$this.find('.exwf-mngrfilter select option[value="exmmore"]').remove();
						var $data_sc = $this.attr('data-sc');
						var $data_menu = $this.attr('data-menu');
						if($data_menu=='' || $data_menu=='[]'){ return;}
						$this.addClass('ex-loading');
						var param = {
							action: 'exwoofood_more_menu',
							param_shortcode: $data_sc,
							data_menu: $data_menu,
						};
						var $url_ajax = exwf_jspr.ajaxurl;
						$.ajax({
							type: "post",
							url: $url_ajax,
							dataType: 'json',
							data: (param),
							success: function(data){
								$this.removeClass('ex-loading');
								if(data.html_content!=''){
									$this.find('.exwf-mngr-content').append(data.html_content);
								}
								$this.attr('data-menu',data.arr_menu);
								$this.find('.exwf-mngrfilter .ex-menu-list').append(data.html_infilter);
								$this.find('.exwf-mngrfilter .ex-menu-select select').append(data.html_slfilter);
								exfd_loadmore();
							}
						});
					}
				});
			}
			exwf_mngroup_more();
			jQuery(document).scroll(function() {
				exwf_mngroup_more();
			});
		}
		// scroll sell
		function exwf_cross_sale_car(){
			if(!jQuery('.exfd-cart-mini .ex-fdcarousel').length){return;}
			setTimeout(function() {
				jQuery('.exfd-cart-mini .ex-fdcarousel').each(function(){
					var $this = jQuery(this);
					$this.closest('.exwf-cart-cross-sells').removeClass('ex-load-hidden');
					var id =  $this.attr('id');
					var slidesshow =  $this.data('slidesshow');
					var slidesscroll =  $this.data('slidesscroll');
					if(slidesshow==''){ slidesshow = 3;}
					if (slidesscroll==''){ slidesscroll = slidesshow;}
					var startit =  $this.data('startit') > 0 ? $this.data('startit') : 1;
					var auto_play = $this.data('autoplay');
					var auto_speed = $this.data('speed');
					var rtl_mode = $this.data('rtl');
					var mobile_items = $this.data('mobile_item') > 1 ? $this.data('mobile_item') : 1;
					var start_on =  $this.data('start_on') > 0 ? $this.data('start_on') : 0;
					if($this.data('infinite')=='0'){
					  var infinite = 0;
					}else{
					  var infinite =  $this.data('infinite') == 'yes' || $this.data('infinite') == '1' ? true : false;
					}
					exfd_carousel('#'+id+' .ctgrid',infinite,start_on,rtl_mode,slidesshow,slidesscroll,auto_play,auto_speed,mobile_items,1);
				});
			}, 1500);
			setTimeout(function() {
		        jQuery('.exfd-cart-mini .ex-fdcarousel.ld-screen').addClass('at-childdiv');
		        jQuery('.exfd-cart-mini .ex-fdcarousel .ctgrid.ex_s_lick-initialized').EX_ex_s_lick('setPosition');
	        }, 2000);
		}
		exwf_cross_sale_car();
		$( document ).ajaxComplete(function() {
			exwf_cross_sale_car();
		});
		$(document).on('exwfqv_loaded', function(event){
			if(jQuery('.exfd-cart-content.excart-active').length){
				$(".exfd-close-cart").trigger('click');
			}
			return false;
		});
		// whatsapp by loc
		if($('.exwf-whastsapp-byloc').length){
			setTimeout(function() {
				var $html = $('.exwf-whastsapp-byloc').html();
				$('.exwf-order-whastsapp').remove();
				$('.exfd-cart-mini').append($html);
			}, 500);
		}	
    });
}(jQuery));