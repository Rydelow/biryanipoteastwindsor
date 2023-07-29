;(function($){
	'use strict';
	$(document).ready(function() {
		$("#toplevel_page_exwoofood_options").appendTo("#menu-posts-product > ul");
		if($("#toplevel_page_exwoofood_options > a").hasClass('current') || $("#toplevel_page_exwoofood_advanced_options > a").hasClass('current') || $("#toplevel_page_exwoofood_shpping_options > a").hasClass('current') || $("#toplevel_page_exwoofood_custom_code_options > a").hasClass('current') || $("#toplevel_page_exwoofood_js_css_file_options > a").hasClass('current') ){
			$("#adminmenu > li#menu-posts-product").removeClass('wp-not-current-submenu');
			$("#adminmenu > li#menu-posts-product").addClass('wp-has-current-submenu');
			$("#toplevel_page_exwoofood_options").addClass('current');
		}
		/*-ready-*/
		if(jQuery('.post-type-exwoofood_scbd .cmb2-metabox select[name="sc_type"]').length>0){
			var $val = jQuery('.post-type-exwoofood_scbd .cmb2-metabox select[name="sc_type"]').val();
			if($val==''){
				$val ='grid';
			}
			if($val =='list'){
				jQuery('.post-type-exwoofood_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-exwoofood_scbd select#style option[value="1"], .post-type-exwoofood_scbd select#style option[value="2"], .post-type-exwoofood_scbd select#style option[value="3"]').removeAttr("disabled");
			}else if($val =='table'){
				jQuery('.post-type-exwoofood_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-exwoofood_scbd select#style option[value="1"]').removeAttr("disabled");
			}else{
				jQuery('.post-type-exwoofood_scbd select#style option').removeAttr('disabled','disabled');
			}
			$('body').removeClass (function (index, className) {
				return (className.match (/(^|\s)ex-layout\S+/g) || []).join(' ');
			});
			$('body').addClass('ex-layout-'+$val);
		}
		/*-on change-*/
		jQuery('.post-type-exwoofood_scbd .cmb2-metabox select[name="sc_type"]').on('change',function() {
			var $this = $(this);
			var $val = $this.val();
			if($val==''){
				$val ='grid';
			}
			if($val =='list'){
				jQuery('.post-type-exwoofood_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-exwoofood_scbd select#style option[value="1"], .post-type-exwoofood_scbd select#style option[value="2"], .post-type-exwoofood_scbd select#style option[value="3"]').removeAttr("disabled");
			}else if($val =='table'){
				jQuery('.post-type-exwoofood_scbd select#style option').attr('disabled','disabled');
				jQuery('.post-type-exwoofood_scbd select#style option[value="1"]').removeAttr("disabled");
			}else{
				jQuery('.post-type-exwoofood_scbd select#style option').removeAttr('disabled','disabled');
			}
			$('body').removeClass (function (index, className) {
				return (className.match (/(^|\s)ex-layout\S+/g) || []).join(' ');
			});
			$('body').addClass('ex-layout-'+$val);
			
		});
		/*-ajax save meta-*/
		jQuery('input[name="exwoofood_order"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var valu = $this.val();
           	var param = {
	   			action: 'exwoofood_change_sort_food',
	   			post_id: post_id,
				value: valu
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exwoofood_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		

		function ex_add_title($box){
			$box.find( '.cmb-group-title' ).each( function() {
				var $this = $( this );
				var txt = $this.next().find( '[id$="_name"]' ).val();
				var rowindex;
				if ( ! txt ) {
					txt = $box.find( '[data-grouptitle]' ).data( 'grouptitle' );
					if ( txt ) {
						rowindex = $this.parents( '[data-iterator]' ).data( 'iterator' );
						txt = txt.replace( '{#}', ( rowindex + 1 ) );
					}
				}
				if ( txt ) {
					$this.text( txt );
				}
			});
		}
		function ex_replace_title(evt){
			var $this = $( evt.target );
			var id = 'name';
			if ( evt.target.id.indexOf(id, evt.target.id.length - id.length) !== -1 ) {
				$this.parents( '.cmb-row.cmb-repeatable-grouping' ).find( '.cmb-group-title' ).text( $this.val() );
			}
		}
		jQuery('#exwoofood_addition_options,#exwf_menubydate,#exwoofood_custom_data').on( 'cmb2_add_row cmb2_shift_rows_complete', ex_replace_title )
				.on( 'keyup', ex_replace_title );
		ex_add_title(jQuery('#exwoofood_addition_options,#exwoofood_custom_data,#exwf_menubydate'));

		jQuery('.cmb2-id-exorder-store input[name="exorder_store"]').on('change paste keyup',function(e) {
			e.preventDefault();
			var $this = $(this);
			var store_id = $this.val();
			var param = {
	   			action: 'exwoofood_admin_show_store',
	   			store_id: store_id,
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exwoofood_ajax.ajaxurl,
	   			dataType: 'json',
	   			data: (param),
	   			success: function(data){
	   				if(data!=0){
		   				$('.cmb2-id-exorder-store .cmb2-metabox-description').empty();
		   				$('.cmb2-id-exorder-store .cmb2-metabox-description').append(data.store_name);
		   			}
	   			}	
	   		});
		});

		// change sort menu
		jQuery('input[name="exfd_sort_menu"]').on('change',function() {
			var $this = $(this);
			var post_id = $this.attr('data-id');
			var value = $this.val();
           	var param = {
	   			action: 'exfd_change_order_menu',
	   			post_id: post_id,
				value: value
	   		};
	   		$.ajax({
	   			type: "post",
	   			url: exwoofood_ajax.ajaxurl,
	   			dataType: 'html',
	   			data: (param),
	   			success: function(data){
	   				return true;
	   			}	
	   		});
		});
		function exwfconvertH2M(timeInHour){
			var timeParts = timeInHour.split(":");
			return Number(timeParts[0]) * 60 + Number(timeParts[1]);
		}
		jQuery('body').on('click', '.exwf-generatesl a', function() {
		    var $this = $(this);
		    var $timefr = $this.closest('.cmb-field-list').find('.sltime-fr input').val();
		    var $timeto = $this.closest('.cmb-field-list').find('.sltime-to input').val();
		    var $maxod = $this.closest('.cmb-field-list').find('.sltime-maxod input').val();
		    var $minu = $this.closest('.cmb-field-list').find('.sltime-minu input').val();
		    if($timefr!='' && $timeto!=''){
			    if($minu==''){
			    	var $defnb_sl = $this.closest('.cmb-field-list').find('.cmb-type-timedelivery .cmb-td .cmb-tbody > .cmb-row').length;
			    	var $item_pre = $this.closest('.cmb-field-list').find('.cmb-type-timedelivery .cmb-row.hidden').prev();
			    	if($defnb_sl==2 && $item_pre.find('.exwf-open-time input').val()=='' && $item_pre.find('.exwf-close-time input').val() =='' && $item_pre.find('.exwf-max-order input').val() =='' ){
			    	}else{
				    	$this.closest('.cmb-field-list').find('.cmb-type-timedelivery p.cmb-add-row button.cmb-add-row-button').trigger('click');
				    	var $item_pre = $this.closest('.cmb-field-list').find('.cmb-type-timedelivery .cmb-row.hidden').prev();
				    }
			    	$item_pre.find('.exwf-open-time input').val($timefr);
			    	$item_pre.find('.exwf-close-time input').val($timeto);
			    	$item_pre.find('.exwf-max-order input').val($maxod);
			    }else{
			    	$timefr = exwfconvertH2M($timefr);
			    	$timeto = exwfconvertH2M($timeto);
			    	if($timefr < $timeto ){
			    		for (var $i = $timefr; $i < $timeto; $i=$i+$minu*1) {
			    			var $str_sec = $i%60 >= 10 ? $i%60 : '0'+$i%60;
			    			var $str_sl = Math.floor($i / 60)+':'+$str_sec;
			    			var $end_sec = ($i+$minu*1)%60 >= 10 ? ($i+$minu*1)%60 : '0'+($i+$minu*1)%60;
			    			var $end_sl = Math.floor(($i+$minu*1) / 60)+':'+$end_sec;
			    			var $defnb_sl = $this.closest('.cmb-field-list').find('.cmb-type-timedelivery .cmb-td .cmb-tbody > .cmb-row').length;
					    	var $item_pre = $this.closest('.cmb-field-list').find('.cmb-type-timedelivery .cmb-row.hidden').prev();
					    	if($defnb_sl==2 && $item_pre.find('.exwf-open-time input').val()=='' && $item_pre.find('.exwf-close-time input').val() =='' && $item_pre.find('.exwf-max-order input').val() =='' ){
					    	}else{
						    	$this.closest('.cmb-field-list').find('.cmb-type-timedelivery p.cmb-add-row button.cmb-add-row-button').trigger('click');
						    	var $item_pre = $this.closest('.cmb-field-list').find('.cmb-type-timedelivery .cmb-row.hidden').prev();
						    }
					    	$item_pre.find('.exwf-open-time input').val($str_sl);
					    	$item_pre.find('.exwf-close-time input').val($end_sl);
					    	$item_pre.find('.exwf-max-order input').val($maxod);
			    		}
			    	}
			    }
			}
		});
		jQuery('.exwf-adm-odmethod select').on('change',function() {
			var $val = $(this).val();
			if($val=='takeaway'){
				var $t_date = $(this).attr('data-ttk');
				var $t_time = $(this).attr('data-tttk');
				$('.exwf-di-person').addClass('ex-hidden');
			}else if($val=='dinein'){
				var $t_date = $(this).attr('data-tdin');
				var $t_time = $(this).attr('data-ttdin');
				$('.exwf-di-person').removeClass('ex-hidden');
			}else{
				var $t_date = $(this).attr('data-tdel');
				var $t_time = $(this).attr('data-ttdel');
				$('.exwf-di-person').addClass('ex-hidden');
			}
			$('.exwf-adm-oddate strong').text($t_date);
			$('.exwf-adm-odtime strong').text($t_time);
		});
		// copy
		jQuery('#exwf_menubydate').on('click', '.cmb-add-row button',function(e) {
	    	$(this).closest('.cmb-row').prev().find('.exwo-paste-mes').css('display','none');
	    	$(this).closest('.cmb-row').prev().find('.exwo-paste-tt').css('display','block');
	    	$(this).closest('.cmb-row').prev().find('.exwo-copy').addClass('disabled').css('opacity','0.5');
	    });
	    jQuery('#exwf_menubydate').on('click', '.exwo-copy.disabled',function(e) {
	    	alert($(this).attr('data-textdis'));
	    });
		jQuery('#exwf_menubydate').on('click', '.exwo-copypre',function() {
	    	var $crr_info = $(this).closest('.cmb-repeatable-grouping');
	    	var $pre_info = $crr_info.prev();
    		$crr_info.find('.exwf-mnt-st .cmb-td input').val($pre_info.find('.exwf-mnt-ed .cmb-td input').val());
    		$crr_info.find('.exwf-mnt-ed .cmb-td input').val($pre_info.find('.exwf-mnt-ed .cmb-td input').val());
    		$crr_info.find('.exwf-mnt-name .cmb-td input').val($pre_info.find('.exwf-mnt-name .cmb-td input').val());
    		$crr_info.find('.exwf-mnt-max .cmb-td input').val($pre_info.find('.exwf-mnt-max .cmb-td input').val());
    		$crr_info.find('.exwf-mnt-ids .cmb-td input').val($pre_info.find('.exwf-mnt-ids .cmb-td input').val());
    		$pre_info.find('.exwf-mnt-cat .cmb-td input[type="checkbox"]:checked').each(function() {
				$crr_info.find('.exwf-mnt-cat .cmb-td input[value="'+$(this).val()+'"]').prop('checked', true);
			});
			$pre_info.find('.exwf-mnt-omt .cmb-td input[type="checkbox"]:checked').each(function() {
				$crr_info.find('.exwf-mnt-omt .cmb-td input[value="'+$(this).val()+'"]').prop('checked', true);
			});
	    });
	    // Copy option
	    jQuery('#exwf_menubydate').on('click', '.exwo-copy',function() {
	    	var $temp = $("<input class='exwo-ctcopy'>");
	    	var $crr_info = $(this).closest('.cmb-repeatable-grouping');
			$("body").append($temp);
			$temp.val($crr_info.html()).select();
			document.execCommand("copy");
			$temp.remove();
	    });
	    jQuery('#exwf_menubydate').on('click', '.exwo-paste',function(e) {
	    	$(this).find('.exwo-ctpaste').fadeIn();
	    	$(this).find('.exwo-paste-tt').css('display','block');
	    	$(this).find('.exwo-paste-mes').css('display','none');
	    	/*
	    	navigator.clipboard.readText().then(text => {
		        // use text as a variable, here text = 'clipboard text'
		        $("body").append('<div class="copy-hidden"></div>');
		        $('.copy-hidden').html(text);
		    });
		    */
	    });
	    if(jQuery('#exwf_menubydate').length){
		    jQuery(document).on('click', function (e) {
			    if ($(e.target).closest(".exwo-paste").length === 0) {
			        $('.exwo-ctpaste').fadeOut();
			    }
			});
		}
		$("body").on('paste', '#exwf_menubydate .exwo-ctpaste', function (){
	    	var $this = $(this);
	    	var $crr_info = $this.closest('.cmb-repeatable-grouping');
	    	setTimeout(function () {
		    	var $pre_info = $this.val();
		    	$pre_info = $('<div>'+$pre_info+'<div>');
		    	$crr_info.find('.exwf-mnt-st .cmb-td input').val($pre_info.find('.exwf-mnt-ed .cmb-td input').val());
	    		$crr_info.find('.exwf-mnt-ed .cmb-td input').val($pre_info.find('.exwf-mnt-ed .cmb-td input').val());
	    		$crr_info.find('.exwf-mnt-name .cmb-td input').val($pre_info.find('.exwf-mnt-name .cmb-td input').val());
	    		$crr_info.find('.exwf-mnt-max .cmb-td input').val($pre_info.find('.exwf-mnt-max .cmb-td input').val());
	    		$crr_info.find('.exwf-mnt-ids .cmb-td input').val($pre_info.find('.exwf-mnt-ids .cmb-td input').val());
	    		$pre_info.find('.exwf-mnt-cat .cmb-td input[type="checkbox"]:checked').each(function() {
					$crr_info.find('.exwf-mnt-cat .cmb-td input[value="'+$(this).val()+'"]').prop('checked', true);
				});
				$pre_info.find('.exwf-mnt-omt .cmb-td input[type="checkbox"]:checked').each(function() {
					$crr_info.find('.exwf-mnt-omt .cmb-td input[value="'+$(this).val()+'"]').prop('checked', true);
				});

	    		$this.closest('.exwo-paste').find('.exwo-paste-tt').css('display','none');
	    		$this.closest('.exwo-paste').find('.exwo-paste-mes').css('display','block');
	    		$this.val('').fadeOut();

	    	}, 100);	
		} );
		jQuery('body').on('click', '.exwf-disable-slot input.checkbox-dis', function() {
			if(this.checked){
        		$(this).next().val('1');
           	}else{
            	$(this).next().val('');
           	}
		});
	});
}(jQuery));