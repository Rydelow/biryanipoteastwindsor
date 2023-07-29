;(function($){
	'use strict';
	jQuery('document').ready(function($){
	    jQuery('body').on('submit', '.exwf-reviews #commentform', function (e) {
	        //serialize and store form data in a variable
	        var commentform = $(this);
	        commentform.addClass('ex-loading');
	        var statusdiv = commentform.find('#comment-status');
	        var formdata = commentform.serialize();
	        //Add a status message
	        //Extract action URL from commentform
	        var formurl=commentform.attr('action');
	        //Post Form with data
	        $.ajax({
	            type: 'post',
	            url: formurl,
	            data: formdata,
	            error: function(request, textStatus, errorThrown)
	                {
	                	commentform.removeClass('ex-loading');
	                	var wpErrorHtml = request.responseText.split("<p>"),
            			wpErrorStr = wpErrorHtml[1].split("</p>");
	                    statusdiv.html(wpErrorStr);
	                },
	            success: function(data, textStatus){
	            	
	                //if(data == "success" ){
	                //    statusdiv.html('<p class="ajax-success" >Thanks for your comment. We appreciate your response.</p>');
	                //}else{
	                	commentform.removeClass('ex-loading');
	                    var wpErrorHtml = data.split("<p>"),
						wpErrorStr = wpErrorHtml[1].split("</p>");
						statusdiv.html(wpErrorStr);
	                //}
	            }
	        });
	        return false;
	    });
	    jQuery('body').on('click', '.exwf-md-tabs .exwf-tab:not(.exwf-tab-current)', function () {
	    	$(".exwf-md-tabs .exwf-tab").removeClass('exwf-tab-current');
	    	$(this).addClass('exwf-tab-current');
			var control = $(this).attr('data-control');
			$(".exwf-act-tab").fadeOut("fast", function() {
			    $(this).removeClass('exwf-act-tab');
			});
			$("."+control).fadeIn("fast", function() {
			    $(this).addClass('exwf-act-tab');
			    var cont_hi = $('#food_modal .ex-modal-big').height();
			    var img_hi = $('#food_modal .fd_modal_img').height();
			    if(cont_hi > img_hi && $(window).width() > 767){
			    	$('#food_modal .ex-modal-big').addClass('ex-padimg');
			    }else{
			    	$('#food_modal .ex-modal-big').removeClass('ex-padimg');
			    }
			});
		});
		jQuery('body').on( 'click', '.exwf-reviews #respond p.stars a', function() {
			var $star   	= $( this ),
				$rating 	= $( this ).closest( '#respond' ).find( '#rating' ),
				$container 	= $( this ).closest( '.stars' );

			$rating.val( $star.text() );
			$star.siblings( 'a' ).removeClass( 'active' );
			$star.addClass( 'active' );
			$container.addClass( 'selected' );

			return false;
		} );
	});
    
}(jQuery));