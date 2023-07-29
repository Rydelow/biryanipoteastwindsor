;(function($){
	'use strict';
	$(document).ready(function() {
		$(".exwf-calendar").each(function(){
			var $this = $(this);
			var id_crsc  		= $(this).data('id');
			var ajax_url  		= $('#'+id_crsc+' input[name=ajax_url]').val();
			var location  		= $('#'+id_crsc+' input[name=location]').val();
			var taxonomy  		= $('#'+id_crsc+' input[name=taxonomy]').val();
			var terms  		= $('#'+id_crsc+' input[name=terms]').val();
			var calendar_orderby  		= $('#'+id_crsc+' input[name=calendar_orderby]').val();
			var calendar_view  		= $('#'+id_crsc+' input[name=calendar_view]').val();
			var calendar_defaultDate  		= $('#'+id_crsc+' input[name=calendar_defaultDate]').val();
			var calendar_firstDay  		= $('#'+id_crsc+' input[name=calendar_firstDay]').val();
			var scrolltime  		= $('#'+id_crsc+' input[name=scrolltime]').val();
			var mintime  		= $('#'+id_crsc+' input[name=mintime]').val();
			var maxtime  		= $('#'+id_crsc+' input[name=maxtime]').val();
			var viewas_button  		= $('#'+id_crsc+' input[name=viewas_button]').val();
			var current_url  		= $('#'+id_crsc+' input[name=current_url]').val();
			var param_shortcode  		= $('#'+id_crsc+' input[name=param_shortcode]').val();
			var mindate  		= $('#'+id_crsc+' input[name=mindate]').val();
			var maxdate  		= $('#'+id_crsc+' input[name=maxdate]').val();
			var ct_hd = 'title';
			if(viewas_button==''){
				ct_hd ='';
				viewas_button = 'title';
			}
			var events;
			var $defaultView =  'month';
			var $target = 'bottom';
			if(calendar_view == 'week'){
				$defaultView =  'agendaWeek';
				$target = 'mouse';
			}else if(calendar_view == 'day'){
				$defaultView =  'agendaDay';
				$target = 'mouse';
			}else if(calendar_view != ''){
				$defaultView = calendar_view;
				if(calendar_view != 'month'){ $target = 'mouse';}
			}
			if(!$('#'+id_crsc+' #calendar').hasClass('widget-style') && !$('#'+id_crsc+' #calendar').hasClass('fullcal-style') && $(window).width() < 765  ){
				$defaultView =  'listMonth';
			}
			$('#'+id_crsc+' #calendar').fullCalendar({
				windowResize: function(view) {
					if(!$('#'+id_crsc+' #calendar').hasClass('widget-style') && !$('#'+id_crsc+' #calendar').hasClass('fullcal-style')){
						if($(window).width() < 765 ){
							$('#'+id_crsc+' #calendar').fullCalendar('changeView','listMonth');
						}else{
							if(!$('#'+id_crsc+' #calendar .fc-listYear-button').hasClass('fc-state-active')){
								$('#'+id_crsc+' #calendar').fullCalendar('changeView',$defaultView);
							}
						}
					}
				},
				views: {
				  listYear: {
					type: 'listYear',
					buttonText: $('#'+id_crsc+' input[name=yearl_text]').val()
				  }
				},
				header: {
					left: 'prev,next today',
					center: ct_hd,
					right: viewas_button
				},
				validRange: {
				    start: mindate!='' ? mindate : '',
    				end: maxdate!='' ? maxdate : '',
				},
				defaultDate: calendar_defaultDate,
				defaultView: $defaultView,
				firstDay: calendar_firstDay,
				displayEventTime : false,
				locale: $('#'+id_crsc+' input[name=calendar_language]').val(),
				eventLimit: false, // allow "more" link when too many events
				nextDayThreshold: '00:00:00',
				scrollTime: scrolltime!='' ? scrolltime : '00:00:00',
				minTime: mintime!='' ? mintime : '00:00:00',
				maxTime: maxtime!='' ? maxtime : '24:00:00',
				events: function(start, end, timezone, callback) {
					$.ajax({
						type: 'GET',
						url: ajax_url,
						dataType: 'json',
						data: {
							action: 'exwf_get_events_calendar',
							start: start.unix(),
							end: end.unix(),
							location: location,
							orderby: calendar_orderby,
							current_url: current_url,
							type:calendar_view,
							lang:$('#'+id_crsc+' input[name=calendar_wpml]').val(),
							param_shortcode: param_shortcode,						
						},
						success: function(data){
							if(data != '0')
							{
								events = (data);
								if(typeof(events)!='object' || events==null){
									$('#'+id_crsc+' .calendar-info').removeClass('hidden');
								}else{
									$('#'+id_crsc+' .calendar-info').addClass('hidden');
								}
								callback(events);
							}
						}
					});
				},
				eventRender: function(event, element) {
					
					element.find('.fc-title').html(event.title);
					element.find('.fc-title').closest('a').attr('href',event.url.replace('&amp;', '&' ));
					if(calendar_view == 'basicWeek' && (event.speakers!='' || event.times!='')){
						element.find('.fc-title').html(event.times+'<span>'+event.title+'</span> '+event.speakers);
					}
					element.find('.fc-list-item-title').html(event.title);
					var content = '<div class="exwf-tooltip">'
					+'<div class="exwf-tooltip-content">'
					+'<h4><a href="'+event.url+'">'+event.title+'</a></h4>'
					+(event.order_method && '<p class="spk-status">'+event.order_method+'</p>' || '')
					+'<p class="tt-start">'+event.startdate+'</p>'
					+(event.location && '<p class="tt-loca">'+event.location+'</p>' || '')
					+(event.status && '<p class="tt-status">'+event.status+'</p>' || '')
					+(event.ship_add && '<p class="spk-des">'+event.ship_add+'</p>' || '')
					+(event.price && '<p class="tt-price"><span>'+event.price+'</span></p>' || '')
					+(event.url_ontt && '<p class="tt-bt"><a href="'+event.url_ontt+'" class="btn btn btn-primary exwf-button">'+event.text_onbt+'</a></p>' || '')
					+'</div></div>';
					element.qtip({
						prerender: true,
						content: {text:content, button: 'Close'},
						style: {
							tip: {
								corner: false,
								width: 12
							},
							classes: 'ex-qtip'
						},
						position: {
							my: 'bottom left',
							at: 'bottom center',
							target:$target,
							viewport: $('body'),
						},
						show: {  solo: false,},
						hide: {
						  delay: 100,
						  fixed: true,
						  effect: function() { $(this).fadeOut(100); }
						},
					});
				},
				eventClick: function(event) {
				    if (event.url) {
				        window.open(evurl, "_blank");
				        return false;
				    }
				},
				loading: function(bool) {
					if (bool) {
						$('#'+id_crsc).addClass('loading');
					}else {
						$('#'+id_crsc).removeClass('loading');
					}
				},
				eventAfterAllRender: function(event, element) {
					setTimeout(function() {
						if($('#'+id_crsc+' .fc-listMonth-view').length && !$('#'+id_crsc+' .fc-list-empty').length){
							$('#'+id_crsc+' .calendar-info').addClass('hidden');
						}else if(!$('#'+id_crsc+' .fc-listYear-view').length && !$('#'+id_crsc+' .fc-event').length || $('#'+id_crsc+' .fc-listYear-view').length && !$('#'+id_crsc+' .fc-listYear-view .fc-list-item').length) {
							$('#'+id_crsc+' .calendar-info').removeClass('hidden');
						}else{
							$('#'+id_crsc+' .calendar-info').addClass('hidden');
						}
					}, 1000);
				},
				viewRender: function(view, element) {
					if(view['name'] && view['name']=='listYear'){
						$('#'+id_crsc+' #calendar .exwf-cal-filter-month').hide(200);
					}else{
						$('#'+id_crsc+' #calendar .exwf-cal-filter-month').show(200);
					}
					if(!$('#'+id_crsc+' #calendar .fc-toolbar + .exwf-cal-ftgr').length){
						$('#'+id_crsc+' #calendar .fc-toolbar').after( $('#'+id_crsc+' .exwf-cal-ftgr').show());
					}
					$('#'+id_crsc+' #calendar select[name=food_loc]').on('change', function() {
						var food_loc = $('#'+id_crsc+' #calendar select[name=food_loc]').val();
						if($('#'+id_crsc+' .exwfical-bt').length){
							var url_ical = new URL($('#'+id_crsc+' .exwfical-bt a').attr('href'));
							url_ical.searchParams.set('location', food_loc);
							$('#'+id_crsc+' .exwfical-bt a').attr('href',url_ical.toString());
						}
						$('#'+id_crsc+' #calendar').fullCalendar('removeEventSources');
						if($('#'+id_crsc+' #calendar').hasClass('widget-style')){
							jQuery('#'+id_crsc+' .fc-day-top').removeClass('hasevent');
						}
						$('#'+id_crsc+' #calendar').fullCalendar(
							'addEventSource', 
							function(start, end, timezone, callback) {
								//$('#'+id_crsc).addClass('loading');
								$.ajax({
									type: 'GET',
									url: ajax_url,
									dataType: 'json',
									data: {
										action: 'exwf_get_events_calendar',
										start: start.unix(),
										end: end.unix(),
										location: food_loc,
										orderby: calendar_orderby,
										current_url: current_url,
										type:calendar_view,
										lang:$('#'+id_crsc+' input[name=calendar_wpml]').val(),
										param_shortcode: param_shortcode,						
									},
									success: function(data){
										//$('#'+id_crsc).removeClass('loading');
										if(data != '0'){
											events = (data);
											if(typeof(events)!='object' || events==null){
												$('#'+id_crsc+' .calendar-info').removeClass('hidden');
											}else{
												$('#'+id_crsc+' .calendar-info').addClass('hidden');
											}
										}
										callback(events);
									}// end filter
								});
							}
						);
					});
				}
			});
			$('#'+id_crsc+' select[name=cal-filter-month]').on('change', function() {
				$('#'+id_crsc+' #calendar').fullCalendar('gotoDate', $(this).val());
			});
		});
	});
    
}(jQuery));
