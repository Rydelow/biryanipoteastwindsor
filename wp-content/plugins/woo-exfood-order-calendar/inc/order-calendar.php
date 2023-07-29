<?php
function parse_exwf_calendar_func($atts, $content){
	if(phpversion()>=7){
		$atts = (array)$atts;
	}
	$admin_pg = isset($_GET['page']) && ($_GET['page']  == 'order-calendar' || $_GET['page']  == 'exwf_ocal_options') ? 'yes' : 'no';
	if((is_admin()&& !defined( 'DOING_AJAX' ) && $admin_pg=='no') || (defined('REST_REQUEST') && REST_REQUEST)){ return;}
	$ID = isset($atts['ID']) ? $atts['ID'] : rand(10,9999);
	$view =  isset($atts['view']) ? $atts['view'] :'month';
	$viewas_button =  isset($atts['viewas_button']) ? $atts['viewas_button'] :'month,basicWeek,basicDay,listYear';
	$defaultDate =  isset($atts['defaultdate']) && $atts['defaultdate']!='' ? $atts['defaultdate'] : date('Y-m-d');
	$firstDay =  isset($atts['firstday']) && $atts['firstday']!='' ? $atts['firstday'] : '1';
	$scrolltime =  isset($atts['scrolltime']) && $atts['scrolltime']!='' ? $atts['scrolltime'] : '';
	$location 		=isset($atts['location']) ? $atts['location'] : '';
	$orderby =  isset($atts['orderby']) ? $atts['orderby'] :'';
	$calendar_language =  isset($atts['calendar_language']) ? $atts['calendar_language'] :'';
	$show_ical =  isset($atts['show_ical']) ? $atts['show_ical'] :'';

	$mintime =  isset($atts['mintime']) ? $atts['mintime'] :'';
	$maxtime =  isset($atts['maxtime']) ? $atts['maxtime'] :'';
	$mindate =  isset($atts['mindate']) ? $atts['mindate'] :'';
	$maxdate =  isset($atts['maxdate']) ? $atts['maxdate'] :'';
	
	wp_enqueue_style('fullcalendar', EX_WOOFOOD_OCAL_PATH.'js/fullcalendar/fullcalendar.min.css');
	wp_enqueue_style('qtip-css', EX_WOOFOOD_OCAL_PATH.'js/fullcalendar/lib/qtip/jquery.qtip.min.css');
	wp_enqueue_style('exwf-order-cal', EX_WOOFOOD_OCAL_PATH.'css/style.css');
	wp_enqueue_script( 'exwf-order-calendar',EX_WOOFOOD_OCAL_PATH.'/js/order-calendar.min.js', array( 'jquery' ), '1.0', true  );
	wp_enqueue_script( 'moment', EX_WOOFOOD_OCAL_PATH.'js/fullcalendar/lib/moment.min.js', array( 'jquery' ), '1.0', true  );
	wp_enqueue_script( 'exwf-fullcalendar', EX_WOOFOOD_OCAL_PATH.'js/fullcalendar/fullcalendar.min.js', array( 'jquery' ), '1.0', true  );
	$language_crr = '';
	$wpml_crr = '';
	if (class_exists('SitePress')){
		global $sitepress;
		$wpml_crr = $sitepress->get_current_language();
		if($language_crr != ''){
			$language_crr = $wpml_crr;
		}
	}
	if($calendar_language != ''){
		$language_crr = $calendar_language;
	}
	if($language_crr!='' && $language_crr!='en'){
		wp_enqueue_script( 'exwf-fullcalendar-language', EX_WOOFOOD_OCAL_PATH.'js/fullcalendar/locale/'.$language_crr.'.js', array( 'jquery' ), '1.0', true  );
	}
	wp_enqueue_script( 'exwf-jquery-qtip',  EX_WOOFOOD_OCAL_PATH.'js/fullcalendar/lib/qtip/jquery.qtip.min.js' , array( 'jquery' ), '1.0', true  );
	ob_start();
	global $wp;
	$crurl =  home_url( $wp->request );
	$crurl = apply_filters('exwf_current_link',$crurl);?>
    <div class="exwf-calendar" data-id ="<?php echo esc_attr($ID);?>" id="<?php echo esc_attr($ID);?>">
        <div class="exwf-loading">
            <div class="wpex-spinner">
                <div class="rect1"></div>
                <div class="rect2"></div>
                <div class="rect3"></div>
                <div class="rect4"></div>
                <div class="rect5"></div>
            </div>
        </div>
        <?php 
		echo '<div class="calendar-info hidden"><i class="fa fa-exclamation-triangle"></i>'.esc_html__('No Orders Found','exwf-order-calendar').'</div>'; ?>
        <input type="hidden"  name="calendar_view" value="<?php if($view!=''){ echo esc_attr($view);}else{ echo 'month';}?>">
        <input type="hidden"  name="calendar_language" value="<?php echo esc_attr($language_crr);?>">
        <input type="hidden"  name="calendar_wpml" value="<?php echo esc_attr($wpml_crr);?>">
        <input type="hidden"  name="calendar_defaultDate" value="<?php echo esc_attr($defaultDate);?>">
        <input type="hidden"  name="calendar_firstDay" value="<?php echo esc_attr($firstDay);?>">
        <input type="hidden"  name="calendar_orderby" value="<?php echo esc_attr($orderby);?>">
        <input type="hidden"  name="location" value="<?php echo esc_attr($location);?>">
        <input type="hidden" name="param_shortcode" value="<?php echo esc_html(str_replace('\/', '/', json_encode($atts)));?>">
        <input type="hidden"  name="current_url" value="<?php echo esc_url($crurl);?>">
        <input type="hidden"  name="scrolltime" value="<?php echo esc_attr($scrolltime);?>">
        <input type="hidden"  name="mintime" value="<?php echo esc_attr($mintime);?>">
        <input type="hidden"  name="maxtime" value="<?php echo esc_attr($maxtime);?>">
        <input type="hidden"  name="mindate" value="<?php echo esc_attr($mindate);?>">
        <input type="hidden"  name="maxdate" value="<?php echo esc_attr($maxdate);?>">
        <input type="hidden"  name="viewas_button" value="<?php echo esc_attr($viewas_button);?>">
        <input type="hidden"  name="yearl_text" value="<?php echo esc_html__('List Year','exwf-order-calendar');?> ">
        <div class="exwf-ctnr-cal">
            <div class="exwf-cal-ftgr" style=" display:none;"><?php exwf_calendar_month_select();?></div>
            <div id="calendar" class=""></div>
            <?php if($show_ical=='yes'){
            	$vr_code = get_option('exwf_ical_code');?>
                <div class="exwfical-bt">
                    <a class="exwf-button" href="<?php echo home_url().'?ical_orders=yes&code='.esc_attr($vr_code).'&location='.esc_attr($location); ?>"><?php echo esc_html__('+ Ical Import','exwf-order-calendar');?></a>
                </div>
            <?php }?>
        </div>
        <input type="hidden"  name="ajax_url" value="<?php echo esc_url(admin_url( 'admin-ajax.php' ));?>">
    </div>
    <?php
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'exwf_order_calendar', 'parse_exwf_calendar_func' );
add_action( 'wp_ajax_exwf_get_events_calendar', 'exwf_get_events_calendar',99 );
add_action( 'wp_ajax_nopriv_exwf_get_events_calendar', 'exwf_get_events_calendar',99 );
function exwf_get_events_calendar() {
	$atts = json_decode( stripslashes( $_GET['param_shortcode'] ), true );
	$curl = $_GET['lang'];
	if (class_exists('SitePress')){
		global $sitepress;
		$sitepress->switch_lang($curl, true);
	}
	$result ='';
	$args = array(
		'post_type' => 'shop_order',
		'posts_per_page' => -1,
	);
	$time_now =  strtotime("now");
	$gmt_offset = get_option('gmt_offset');
	if($gmt_offset!=''){
		$time_now = $time_now + ($gmt_offset*3600);
	}
	$upcom = exwoofood_get_option('exwf_ocal_upcom','exwf_ocal_options');
	if($upcom=='yes'){
		$_GET['start'] = $time_now;
	}elseif(isset($_GET['orderby']) && $_GET['orderby']=='past'){
		$_GET['end'] = $time_now;
	}
	$arr_status = exwoofood_get_option('exwf_ocal_status','exwf_ocal_options');
	if(!is_array($arr_status) || is_array($arr_status) && empty($arr_status)){
		$arr_status = array_keys( wc_get_order_statuses() );
	}
	if($_GET['end'] && $_GET['start']){
		$args = array(
			  'post_type' => 'shop_order',
			  'posts_per_page' => -1,
			  'meta_key' => 'exwfood_datetime_deli_unix',
			  'post_status'       =>  $arr_status,
			  'meta_query' => array(
			  'relation' => 'AND',
			  array('key'  => 'exwfood_datetime_deli_unix',
				   'value' => $_GET['start'],
				   'compare' => '>='),
			  array('key'  => 'exwfood_datetime_deli_unix',
				   'value' => $_GET['end'],
				   'compare' => '<=')
			  ),
			  'suppress_filters' => false 
		);
		//location
		if(isset($_GET['location']) && $_GET['location']!=''){
			$location = $_GET['location'];
			$args['meta_query'][] =  array(
				'key'  => 'exwoofood_location',
				'value' => $location,
				'compare' => '='
			);
		}
		$arr_method = exwoofood_get_option('exwf_ocal_method','exwf_ocal_options');
		if(is_array($arr_method) && !empty($arr_method)){
			$args['meta_query'][] =  array(
				'key'  => 'exwfood_order_method',
				'value' => $arr_method,
				'compare' => 'IN',
			);
		}
		
		if(isset($_GET['ids']) && $_GET['ids']!=''){
			if(!is_array($_GET['ids'])){
				$ids = explode(",", $_GET['ids']);
			}
			$args['post__in'] = $ids;
		}
		
		global $post;
		$args = apply_filters( 'exwf_calendar_query_var', $args );
		$the_query = get_posts( $args );
		$rs=array();
		$current_url =  isset($_GET['current_url']) ? $_GET['current_url'] :'';
		if(!empty($the_query)){
			$date_format = get_option('date_format');
			$result = array();
			foreach ( $the_query as $post ) : setup_postdata( $post );
				$order = wc_get_order(get_the_ID());
				$time_unix = get_post_meta(get_the_ID(),'exwfood_datetime_deli_unix', true );
				if($time_unix!=''){
				   // $startdate_cal = gmdate("Ymd\THis", $startdate);
					$exwf_startdate = gmdate("Y-m-d\TH:i:s", $time_unix);// convert date ux
					$exwf_enddate = gmdate("Y-m-d\TH:i:s", $time_unix+1);
				}
				$start_hourtime = $end_hourtime = '';
				if($time_unix!=''){
					$loc_ar = get_post_meta( $order->get_id(), 'exwoofood_location', true );
					$log_name = $loc_ar!='' ? get_term_by('slug', $loc_ar, 'exwoofood_loc') : '';
					$log_name = $log_name->name ? esc_html__( 'Location: ', 'woocommerce-food' ).$log_name->name : '';
					$order_method = get_post_meta( $order->get_id(), 'exwfood_order_method', true );
					$order_method = $order_method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $order_method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : ( $order_method=='delivery' ? esc_html__('Delivery','woocommerce-food') : '') );
					$text_datedel = exwf_date_time_text('date',$order);

					$order_status = $order->get_status();
					$exwf_eventcolor = '#9e9e9e';
					if($order_status=='processing'){$exwf_eventcolor = '#00bcd4';}
					elseif($order_status=='completed'){$exwf_eventcolor = '#4caf50';}
					elseif($order_status=='on-hold'){$exwf_eventcolor = '#ff9800';}
					$exwf_eventcolor = apply_filters('exwf_order_status_color',$exwf_eventcolor,$order_status,$order);
					$time_html = get_post_meta(get_the_ID(),'exwfood_time_deli', true );
					$dt_fm = date_i18n( $date_format, $time_unix).' '.$time_html;
					$ar_rs= array(
						'id'=> get_the_ID(),
						'title'=> '#'.esc_attr($order->get_order_number()).' - '.wc_get_order_status_name( $order_status ).' - '.$dt_fm,
						'url'=> (get_edit_post_link(get_the_ID())),
						'start'=>$exwf_startdate,
						'end'=>$exwf_enddate,
						'startdate'=> $text_datedel.': '.$dt_fm,
						'enddate'=> $edt_fm,
						'unix_startdate'=> $exwf_startdate_unix,
						'unix_enddate'=> $exwf_startdate_unix,
						'price'=> esc_html__('Total price: ','exwf-order-calendar').$order->get_formatted_order_total(),
						'color'=> $exwf_eventcolor,
						'status'=>  esc_html__('Order Status: ','exwf-order-calendar').wc_get_order_status_name( $order_status ),
						'ship_add' => $order->get_formatted_shipping_address()!='' ? esc_html__('Shipping Address: ','exwf-order-calendar'). wp_kses_post( $order->get_formatted_shipping_address() ) : '',
						'allDay' => '',
						'location' => $log_name,
						'url_ontt'=> get_edit_post_link(get_the_ID()),
						'text_onbt'=> esc_html__('View Details','exwf-order-calendar'),
						'order_method'=> esc_html__('Order Method: ','exwf-order-calendar').$order_method,
						'times'=> $time_html,
					);
				}
				$result[]=$ar_rs;
			endforeach;
			$result = apply_filters( 'exwf_event_json_info', $result);
			wp_reset_postdata();
		}
		echo str_replace('\/', '/', json_encode($result));
		exit;
	}
}

if(!function_exists('exwf_calendar_month_select')){
	function exwf_calendar_month_select(){?>
        <div class="exwf-calendar-filter">
            <div class="exwf-cal-filter-month">
                <select name="cal-filter-month" class="exwf-mft-select">
                    <option value=""><?php echo esc_html__('Months','exwf-order-calendar');?></option>
					<?php 
                    $currentMonth = (int)date('m');
                    for ($x = $currentMonth; $x < $currentMonth + 12; $x++) {
                        $date = date_i18n('F j, Y', mktime(0, 0, 0, $x, 1));
                        $value = date('Y-m-d', mktime(0, 0, 0, $x, 1));
                        $selected = '';
                        if((isset($_GET['month']) && $_GET['month'] ==$value)){
                            $selected ='selected';
                        }
                        echo '<option value="'. $value .'" '.$selected.'>'. $date .'</option>';
                    }?>
                </select>
            </div>
            <?php 
            $args = array( 'hide_empty' => false ); 
            $terms = get_terms('exwoofood_loc', $args);
            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){ ?>
                <div class="exwf-cal-filter-cat">
                    <select name="food_loc">
                        <option value=""><?php echo esc_html__('All Locations','exwf-order-calendar');?></option>
                        <?php 
                        foreach ( $terms as $term ) {
                            $selected = '';
                            if((isset($_GET['food_loc']) && $_GET['food_loc'] == $term->slug)){
                                $selected ='selected';
                            }
                            echo '<option value="'. $term->slug .'" '.$selected.'>'. $term->name .'</option>';
                        }?>
                    </select>
                </div>
            <?php }?>
        </div>
        <?php
	}
}