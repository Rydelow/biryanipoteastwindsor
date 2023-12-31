<?php
/**
 * Add the field to the checkout
 */
add_action( 'woocommerce_before_order_notes', 'exwf_date_deli_field' );
function exwoofood_ckselect_loc_html($rq){
	$args = array(
		'hide_empty'        => false,
		'parent'        => '0',
	);
	$args = apply_filters('exwf_location_args',$args);
	$terms = get_terms('exwoofood_loc', $args);
	ob_start();
	$loc_selected = WC()->session->get( 'ex_userloc' );
	$user_log = '';
	if($loc_selected==''){
		$user_log = WC()->session->get( '_user_deli_log' );
		$loc_selected=  $user_log ;
	}
	if (  empty( $terms ) || is_wp_error( $terms ) ){ return;}
	?>
	<div class="exwf-loc-field ">
		<p class="form-row <?php echo $rq=='req' ? 'validate-required' : ''; ?>">
			<label for="exwfood_time_deli" class="">
				<?php esc_html_e('Locations ','woocommerce-food');
				echo $rq=='req' ? '<abbr class="required" title="required">*</abbr>' : '';?>
				<small style="display: block;"><?php echo esc_html__( '(Please choose area you want to order)', 'woocommerce-food' );?></small>
			</label>
			<span class="woocommerce-input-wrapper">
			<select class="exck-loc select" name="exwoofood_ck_loca">
				<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
					if($loc_selected!='' && $user_log == ''){
						$log_name = get_term_by('slug', $loc_selected, 'exwoofood_loc');
						if(isset($log_name->name) && $log_name->name){
							$html = '<option value="'. esc_attr($log_name->slug) .'" selected >'. wp_kses_post($log_name->name) .'</option>';
						}
					}else{
			        	$html =  '<option value=""></option>';
			        	$count_stop = 5;
			        	foreach ( $terms as $term ) {
			        		$selected = $loc_selected == $term->slug ? 'selected' : '';
			        		if( $loc_selected!='' && ($loc_selected == $term->slug)){
			        			if($user_log == ''){
			        				$html = '<option value="'. esc_attr($term->slug) .'" selected >'. wp_kses_post($term->name) .'</option>';
			        				break;
			        			}else{
			        				$html .= '<option value="'. esc_attr($term->slug) .'" selected >'. wp_kses_post($term->name) .'</option>';
			        			}
			        		}else{
						  		$html .= '<option value="'. esc_attr($term->slug) .'" >'. wp_kses_post($term->name) .'</option>';
						  		$html .= exfd_show_child_location('',$term,$count_stop,$loc_selected,'yes');
						  	}
					  	}
					  }
				  	echo $html;
		        } //if have terms ?>
			</select>
			</span>
		</p>	
	</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
function exwf_dinein_field(){
	$method = WC()->session->get( '_user_order_method' );
	if($method!='dinein'){
		return;
	}
	$nbperson = exwoofood_get_option('exwoofood_ck_nbperson','exwoofood_adv_dinein_options');
	$max_nbperson = exwoofood_get_option('exwoofood_ck_maxperson','exwoofood_adv_dinein_options');
	ob_start();
	?>
	<div class="exwf-dine-field ">
		<?php do_action('exwf_before_dinein_field');
		if($nbperson!='disable'){ ?>
			<p class="form-row <?php echo $nbperson=='req' ? 'validate-required' : ''; ?>">
				<?php 
				if($max_nbperson=='' || !is_numeric($max_nbperson)){
					woocommerce_form_field( 
				    	'exwfood_person_dinein', array(
					        'type'          => 'text',
					        'required'  => $nbperson=='req' ? true : false,
					        'class'         => array('exwfood-person-dinein form-row-wide'),
					        'label'         => esc_html__('Number of person','woocommerce-food'),
					        'placeholder'   => esc_html__('Enter number','woocommerce-food'),
					        'custom_attributes' => '', 
					    )
					);
				}else{
					$arr_nb = array();
					$arr_nb[] = '';
					for ($i = 1 ; $i<= $max_nbperson; $i ++ ) {
						$arr_nb[$i] = $i;
					}
					$arr_nb = apply_filters('exwf_nb_maxperson', $arr_nb);
					woocommerce_form_field( 
				    	'exwfood_person_dinein', array(
					        'type'          => 'select',
					        'required'  => $nbperson=='req' ? true : false,
					        'class'         => array('exwfood-person-dinein form-row-wide'),
					        'label'         => esc_html__('Number of person','woocommerce-food'),
					        'placeholder'   => '',
					        'options' => $arr_nb,
					        'default' => '',
					    )
					);
				}?>
			</p>
			<?php 
		}
		do_action('exwf_after_dinein_field'); ?>
	</div>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;

}
function exwf_date_deli_field( $checkout ) {
	$autocolimit = exwoofood_get_option('exwoofood_autocomplete_limit','exwoofood_shpping_options');
	$autocolimit = str_replace(' ', '',$autocolimit)!='' ? json_encode(explode(",",$autocolimit)) : '';
	echo '<input type="hidden" name="exwf_auto_limit" id="exwf_auto_limit" value="'.esc_attr($autocolimit).'">';
	$exwoofood_autocomplete_cko = exwoofood_get_option('exwoofood_autocomplete_cko','exwoofood_shpping_options');
	echo '<input type="hidden" name="exwf_dis_auto" id="exwf_dis_auto" value="'.esc_attr($exwoofood_autocomplete_cko).'">';
	// if disable fields
	$check_ex = exwf_if_check_product_notin_shipping();
	if($check_ex == false){
		return;
	}

	// Location select field
	$loca_field = exwoofood_get_option('exwoofood_ck_loca','exwoofood_advanced_options');
	if($loca_field=='req' || $loca_field=='op'){
		echo exwoofood_ckselect_loc_html($loca_field); 
	}
	echo exwf_dinein_field();
	// Delivery Date and time field
	$rq_date = exwoofood_get_option('exwoofood_ck_date','exwoofood_advanced_options');
	$rq_time = exwoofood_get_option('exwoofood_ck_time','exwoofood_advanced_options');
	if($rq_date!='disable' && $rq_time!='disable'){
		
	}else if($rq_date=='disable' && $rq_time=='disable'){
		return;
	}//print_r( WC()->session->get( 'chosen_shipping_methods' ));//exit;
	$text_datedel = exwf_date_time_text('date');
	$text_timedel = exwf_date_time_text('time');
	

	wp_enqueue_style( 'exwf-date', EX_WOOFOOD_PATH . 'js/jquery-timepicker/bootstrap-datepicker.css');
	wp_enqueue_script( 'exwf-date-js', EX_WOOFOOD_PATH . 'js/jquery-timepicker/bootstrap-datepicker.js', array( 'jquery' ) );
    echo '<div class="exwf-deli-field">';
    $date_before = exwoofood_get_option('exwoofood_ck_beforedate','exwoofood_advanced_options');
    $cure_time =  strtotime("now");
	$gmt_offset = get_option('gmt_offset');
	$menudate = function_exists('exwf_menuby_date_selected') ? exwf_menuby_date_selected() : '';
	if($menudate!=''){
		woocommerce_form_field( 
	    	'exwfood_date_deli', array(
		        'type'          => 'select',
		        'required'  => $rq_date=='no' ? false : true,
		        'class'         => array('exwfood-date-deli form-row-wide'),
		        'label'         => $text_datedel,
		        'placeholder'   => '',
		        'options' => array($menudate => date_i18n(get_option('date_format'), $menudate)),
		        'default' => '',
		    ),
		    $checkout->get_value( 'exwfood_date_deli' )
		);
    }elseif($rq_date!='disable'){
    	$dis_date = exwoofood_get_option('exwoofood_ck_disdate','exwoofood_advanced_options');
		$dis_day = exwoofood_get_option('exwoofood_ck_disday','exwoofood_advanced_options');
		$enb_date = exwoofood_get_option('exwoofood_ck_enadate','exwoofood_advanced_options');
		$_date_type = exwoofood_get_option('exwoofood_dd_display','exwoofood_advanced_options');

	    if($_date_type !='picker'){
	    	date_default_timezone_set('UTC');
		    if($date_before!='' && is_numeric($date_before)){
				$cure_time =  apply_filters( 'exwt_disable_book_day', strtotime("+$date_before day") );
			}else if($date_before!='' && is_numeric(str_replace("m","",$date_before))){
				$cure_time = apply_filters( 'exwt_disable_book_day', strtotime("+".str_replace("m","",$date_before)." minutes") );
			}
			if($gmt_offset!=''){
				$cure_time = floatval($cure_time) + ($gmt_offset*3600);
			}
			$date = strtotime(date('Y-m-d', $cure_time));
			$maxl = apply_filters( 'exwf_number_delivery_date',10);
			$deli_date = array();
			if($rq_date=='no'){
				$deli_date[] = '';
			}
			if(is_array($enb_date) && count($enb_date) > 0){
				foreach ($enb_date as $enb_date_it) {
					if($enb_date_it > $date){
						$date_fm = date_i18n(get_option('date_format'), $enb_date_it);
						$deli_date[$enb_date_it] = $date_fm;
					}
				}
			}else{
				for ($i = 0 ; $i<= $maxl; $i ++ ) {
					$date_un = strtotime("+$i day", $date);
					$day_ofdate = date('N',$date_un);
					if((!empty($dis_day) && count($dis_day)==7)){ break;}
					if( (!empty($dis_date) && in_array($date_un, $dis_date )) || (!empty($dis_day) && in_array($day_ofdate, $dis_day ) ) ){
					  $maxl = $maxl +1;
					}else{
					  $date_fm = date_i18n(get_option('date_format'), $date_un);
					  $deli_date[$date_un] = $date_fm;
					}
				}
			}
		    woocommerce_form_field( 
		    	'exwfood_date_deli', array(
			        'type'          => 'select',
			        'required'  => $rq_date=='no' ? false : true,
			        'class'         => array('exwfood-date-deli form-row-wide'),
			        'label'         => $text_datedel,
			        'placeholder'   => '',
			        'options' => $deli_date,
			        'default' => '',
			    ),
			    $checkout->get_value( 'exwfood_date_deli' )
			);
		}else{
			$date_fm = exwoofood_get_option('exwoofood_datepk_fm','exwoofood_advanced_options');
			$ct_attr = array();
			$ct_attr['data-disday'] = $ct_attr['data-disdate'] = $ct_attr['data-fm'] ='';
			if(is_array($dis_day) && count($dis_day)>0){
				$dis_day_st = implode(',',$dis_day);
				$ct_attr['data-disday'] = str_replace('7', '0', $dis_day_st);
			}
			if($date_fm=='dd-mm-yyyy'){
				$php_fm = 'd-m-Y';
			}else{
				$php_fm = 'm/d/Y';
			}
			$disable_book = '0';
			$dis_uni = '';
			if($date_before!='' && is_numeric($date_before)){
				$dis_uni = apply_filters( 'exwt_disable_book_day', strtotime("+$date_before day") );
			}else if($date_before!='' && is_numeric(str_replace("m","",$date_before))){
				$dis_uni = apply_filters( 'exwt_disable_book_day', strtotime("+".str_replace("m","",$date_before)." minutes") );
			}
			if($dis_uni!=''){
				if($gmt_offset!=''){
					$dis_uni = $dis_uni + ($gmt_offset*3600);
				}
				$disable_book = date_i18n('Y-m-d',$dis_uni);
			}
			$ct_attr['data-mindate'] = $disable_book;
			$tsl_fmonth = array(esc_html__('January','woocommerce-food'),esc_html__('February','woocommerce-food'),esc_html__('March','woocommerce-food'),esc_html__('April','woocommerce-food'),esc_html__('May','woocommerce-food'),esc_html__('June','woocommerce-food'),esc_html__('July','woocommerce-food'),esc_html__('August','woocommerce-food'),esc_html__('September','woocommerce-food'),esc_html__('October','woocommerce-food'),esc_html__('November','woocommerce-food'),esc_html__('December','woocommerce-food'));
			$ct_attr['data-fmon'] = str_replace('\/', '/', json_encode($tsl_fmonth));
			$tsl_smonth = array(esc_html__('Jan','woocommerce-food'),esc_html__('Feb','woocommerce-food'),esc_html__('Mar','woocommerce-food'),esc_html__('Apr','woocommerce-food'),esc_html__('May','woocommerce-food'),esc_html__('Jun','woocommerce-food'),esc_html__('Jul','woocommerce-food'),esc_html__('Aug','woocommerce-food'),esc_html__('Sep','woocommerce-food'),esc_html__('Oct','woocommerce-food'),esc_html__('Nov','woocommerce-food'),esc_html__('December','woocommerce-food'));
			$ct_attr['data-smon'] = str_replace('\/', '/', json_encode($tsl_smonth));
			$tsl_sday = array(esc_html__('Su','woocommerce-food'),esc_html__('Mo','woocommerce-food'),esc_html__('Tu','woocommerce-food'),esc_html__('We','woocommerce-food'),esc_html__('Th','woocommerce-food'),esc_html__('Fr','woocommerce-food'),esc_html__('Sa','woocommerce-food'));
			$ct_attr['data-sday'] = str_replace('\/', '/', json_encode($tsl_sday));

			$ct_attr['data-fiday'] = apply_filters( 'exwt_datepk_fday', 1);
			if(is_array($dis_date) && count($dis_date)>0){
				foreach ( $dis_date as $item ) {
					$arr_disdate[] = date($php_fm, $item);
				}
				$arr_disdate = str_replace('\/', '/', json_encode($arr_disdate));
				$ct_attr['data-disdate'] =  $arr_disdate;
			}
			$ct_attr['data-fm'] =  $date_fm;
			$ct_attr['readonly'] = 'readonly';
			woocommerce_form_field( 
		    	'exwfood_date_deli', array(
			        'type'          => 'text',
			        'required'  => $rq_date=='no' ? false : true,
			        'class'         => array('exwfood-date-deli form-row-wide'),
			        'label'         => $text_datedel,
			        'placeholder'   => '',
			        'custom_attributes' => $ct_attr, 
			    ),
			    $checkout->get_value( 'exwfood_date_deli' )
			);
		}
	}
    $menutime = function_exists('exwf_menuby_time_selected') ? exwf_menuby_time_selected() : '';
	if($menutime!=''){
		$_user_menusl = exwf_menuby_time_selected_details();
		$timefrom = isset($_user_menusl['time_from']) && $_user_menusl['time_from']!='' ? $_user_menusl['time_from'] :'';
		$time_to = isset($_user_menusl['time_to']) && $_user_menusl['time_to']!='' ? $_user_menusl['time_to'] :'';
		$name = isset($_user_menusl['mn_name']) && $_user_menusl['mn_name']!=''? $_user_menusl['mn_name'] : ($timefrom!='' && $time_to!='' ? $timefrom.' - '.$time_to : ($timefrom!='' ? $timefrom : ( $time_to!='' ? $time_to : '')  ) );
		woocommerce_form_field( 
	    	'exwfood_time_deli', array(
		        'type'          => 'select',
		        'required'  => $rq_date=='no' ? false : true,
		        'class'         => array('exwfood-time-deli exwf-mn-timesl form-row-wide'),
		        'label'         => $text_timedel,
		        'placeholder'   => '',
		        'options' => array($menutime => $name),
		        'default' => '',
		        //'custom_attributes' => $time_attr,
		    ),
		    $checkout->get_value( 'exwfood_time_deli' )
		);
    }else if($rq_time!='disable'){
	    $array_time = $deli_time = array();
	    $array_time = exwoofood_get_option('exwoofood_ck_times','exwoofood_advanced_options');
	    //$n_dl_time = exwoofood_get_option('exwfood_deli_time','exwoofood_advanced_options');
	    $adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
		$_ftimesl = '';
		$method = WC()->session->get( '_user_order_method' );
		$method = $method !='' ? $method : 'delivery';
		if($rq_date=='disable'){
			$date_deli = strtotime(date("Y-m-d"));
		}else{ $date_deli ='';}
		if( $date_deli !='' && is_array($adv_timesl) && !empty($adv_timesl)){
			$day_ofd = date('D',$date_deli);
			$user_log = WC()->session->get( '_user_deli_log' );
			foreach ($adv_timesl as $it_timesl) {
				$tsl_log = isset($it_timesl['times_loc'])  ?  $it_timesl['times_loc'] :'';
				if( isset ($it_timesl['repeat_'.$day_ofd]) && $it_timesl['repeat_'.$day_ofd] =='on' && 
					(!isset($it_timesl['deli_method']) 
						|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']=='') 
						|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']==$method)
					) && ($tsl_log=='' || is_array($tsl_log) && in_array($user_log, $tsl_log)) ){
					$_ftimesl = isset($it_timesl['exwfood_deli_time']) && is_array($it_timesl['exwfood_deli_time']) ? $it_timesl['exwfood_deli_time'] : '';
					break;
				}
			}
		}
		$n_dl_time = $_ftimesl!= '' ?  $_ftimesl : exwoofood_get_option('exwfood_deli_time','exwoofood_advanced_options');//print_r( $n_dl_time);exit;
		$disable_sl = exwoofood_get_option('exwoofood_disable_tslot','exwoofood_advanced_options');
		if($disable_sl=='yes' && is_array($n_dl_time)){
			foreach ($n_dl_time as $key => $it_dl_time) {
				if(isset($it_dl_time['disable-slot']) && $it_dl_time['disable-slot']=='1' ){
					unset($n_dl_time[$key]);
				}
			}
		}
		if(!empty($n_dl_time)){
			$array_time = $n_dl_time;
		}
	    if (empty($array_time)) {
			woocommerce_form_field( 
		    	'exwfood_time_deli', array(
			        'type'          => 'text',
			        'required'  => $rq_time=='no' ? false : true,
			        'class'         => array('exwfood-time-deli form-row-wide'),
			        'label'         => $text_timedel,
			        'placeholder'   => '',
			    ),
			    $checkout->get_value( 'exwfood_time_deli' )
			);
	    }else{
	    	if($rq_time=='no'){
				$deli_time[] = '';
			}
			if(!empty($n_dl_time)){
				foreach ($array_time as $time_option) {
					$r_time ='';
					if(isset($time_option['start-time']) && $time_option['start-time']!='' && isset($time_option['end-time']) && $time_option['end-time']!=''){
						$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
					}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
						$r_time = $time_option['start-time'];
					}
					$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
					$deli_time[$name] = $name;
				}
			}else{
		    	foreach ($array_time as $time_option) {
					$deli_time[$time_option] = $time_option;
				}
			}
			$time_attr = array();
			$time_attr['data-time'] = json_encode($n_dl_time);
			if($date_before!='' && is_numeric(str_replace("m","",$date_before))){
				$cure_time =  strtotime("now");
				if($gmt_offset!=''){
					$cure_time = $cure_time + ($gmt_offset*3600);
				}
				$cure_time = $cure_time + str_replace("m","",$date_before)*60;
				$time_attr['data-crtime'] = $cure_time;
				$time_attr['data-date'] = strtotime(date('Y-m-d', $cure_time));
			}
			woocommerce_form_field( 
		    	'exwfood_time_deli', array(
			        'type'          => 'select',
			        'required'  => $rq_time=='no' ? false : true,
			        'class'         => array('exwfood-time-deli form-row-wide'),
			        'label'         => $text_timedel,
			        'placeholder'   => '',
			        'options' => $deli_time,
			        'default' => '',
			        'custom_attributes' => $time_attr, 
			    ),
			    $checkout->get_value( 'exwfood_time_deli' )
			);
		}
	}

    echo '</div>';

}

/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'exwf_verify_date_deli_field');

function exwf_verify_date_deli_field() {
    // Check if set, if its not set add an error.
    $rq_date = exwoofood_get_option('exwoofood_ck_date','exwoofood_advanced_options');
    $rq_time = exwoofood_get_option('exwoofood_ck_time','exwoofood_advanced_options');
    $loca_field = exwoofood_get_option('exwoofood_ck_loca','exwoofood_advanced_options');
    $loc_sl = isset($_POST['exwoofood_ck_loca']) ? $_POST['exwoofood_ck_loca'] : '';
    // check if do not apply field in special product
    if($rq_date!='disable' || $rq_time!='disable'){
    	$check_ex = exwf_if_check_product_notin_shipping();
		if($check_ex == false){
			return;
		}

	}else if($rq_date=='disable' && $rq_time=='disable'){
		// loc check required
	    if($loca_field=='req' && $loc_sl==''){
		    wc_add_notice( esc_html__( 'Please select location you want to order','woocommerce-food' ), 'error' );
	    }
		return;
	}
	$text_datedel = exwf_date_time_text('date');
	$text_timedel = exwf_date_time_text('time');

    $date_deli = isset($_POST['exwfood_date_deli']) ? $_POST['exwfood_date_deli'] : '';
    if($rq_date!='no' && $rq_date!='disable'){
	    if ( $date_deli =='' ){
	        wc_add_notice( sprintf(esc_html__( 'Please select %s','woocommerce-food' ), $text_datedel), 'error' );
	    }
	}
	$_date_type = exwoofood_get_option('exwoofood_dd_display','exwoofood_advanced_options');
	$foodby_date = exwoofood_get_option('exwoofood_foodby_date');
	if($_date_type =='picker' && $date_deli !='' && $foodby_date!='yes'){
		$date_deli = strtotime($date_deli);
	}
	if($rq_date=='disable'){
		$date_deli = strtotime(date("Y-m-d"));
	}
	if ( $date_deli !='' ){
		$enb_date = exwoofood_get_option('exwoofood_ck_enadate','exwoofood_advanced_options');
		if($rq_date!='disable' && is_array($enb_date) && count($enb_date) > 0){
			if(!in_array($date_deli, $enb_date)){
				wc_add_notice( esc_html__( 'Error, please refresh page and try again','woocommerce-food' ), 'error' );
			}
		}
		$dis_date = exwoofood_get_option('exwoofood_ck_disdate','exwoofood_advanced_options');
		$dis_day = exwoofood_get_option('exwoofood_ck_disday','exwoofood_advanced_options');
		$day_ofdate = date('N',$date_deli);
		if((is_array($dis_date) && in_array($date_deli, $dis_date)) || (!empty($dis_day) && in_array($day_ofdate, $dis_day ) ) ){
			wc_add_notice( esc_html__( 'Error, please refresh page and try again','woocommerce-food' ), 'error' );
		}
	}
	$time_deli = isset($_POST['exwfood_time_deli']) ? $_POST['exwfood_time_deli'] : '';
	do_action('exwf_verify_date_time',$_POST);
	if( $rq_time!='disable'){
		if($time_deli=='0'){
			$time_deli=='';
		}
	    if ($rq_time!='no' && $time_deli=='' ){
	        wc_add_notice( sprintf(esc_html__( 'Please select %s','woocommerce-food' ),$text_timedel), 'error' );
	    }else if($rq_time=='no' && $time_deli=='0'){
	    	$time_deli='';
	    }
	    // check max order
	    if($time_deli!=''){
		    exwf_check_time_delivery_status($_POST);
		}
    }else if($rq_time=='no' && $time_deli=='0'){
    	$time_deli='';
    }
    // loc check required
    if($loca_field=='req' && $loc_sl==''){
	    wc_add_notice( esc_html__( 'Please select location you want to order','woocommerce-food' ), 'error' );
    }    
    // verify time has expired
    $method = WC()->session->get( '_user_order_method' );
	$method = $method !='' ? $method : 'delivery'; 
    $date_before = exwoofood_get_option('exwoofood_ck_beforedate','exwoofood_advanced_options');
    if ($date_before!='' && ($date_deli !='' || $time_deli!='')){
	    $check_time_exit = false; $_timeck = '';
	    $menutime = function_exists('exwf_menuby_time_selected') ? exwf_menuby_time_selected() : '';
	    if($time_deli!='' && ($foodby_date!='yes' ||($foodby_date=='yes' && $menutime=='')) ){
	    	// advanced slots
			$adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
			$_ftimesl = '';
			$user_log = WC()->session->get( '_user_deli_log' );
			if(isset($_POST['exwoofood_ck_loca'])){
				$user_log = $_POST['exwoofood_ck_loca'];
			}
			if( $date_deli !='' && is_array($adv_timesl) && !empty($adv_timesl)){
				$day_ofd = date('D',$date_deli);
				foreach ($adv_timesl as $it_timesl) {
					$tsl_log = isset($it_timesl['times_loc'])  ?  $it_timesl['times_loc'] :'';
					if(isset ($it_timesl['repeat_'.$day_ofd]) && $it_timesl['repeat_'.$day_ofd] =='on' && 
						(
							!isset($it_timesl['deli_method']) 
							|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']=='') 
							|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']==$method)
						) && ($tsl_log=='' || is_array($tsl_log) && in_array($user_log, $tsl_log)) ){
						$_ftimesl = isset($it_timesl['exwfood_deli_time']) && is_array($it_timesl['exwfood_deli_time']) ? $it_timesl['exwfood_deli_time'] : '';
						break;
					}
				}
			}
		    $n_dl_time = $_ftimesl!= '' ?  $_ftimesl : exwoofood_get_option('exwfood_deli_time','exwoofood_advanced_options');//print_r( $n_dl_time);exit;
		    $disable_sl = exwoofood_get_option('exwoofood_disable_tslot','exwoofood_advanced_options');
			if($disable_sl=='yes'){
				foreach ($n_dl_time as $key => $it_dl_time) {
					if(isset($it_dl_time['disable-slot']) && $it_dl_time['disable-slot']=='1' ){
						unset($n_dl_time[$key]);
					}
				}
			}
		    if(!is_array($n_dl_time) || empty($n_dl_time)){
		    	$check_time_exit = true;
		    }else{
			    foreach ($n_dl_time as $time_option) {
					$r_time ='';
					if($time_option['start-time']!='' && $time_option['end-time']!=''){
						$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
					}elseif($time_option['start-time']!=''){
						$r_time = $time_option['start-time'];
					}
					$name = $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
					if($time_deli==$name){
						WC()->session->set( '_st_timeslot' , $time_option);
						$_time_base = apply_filters('exwf_timebase_to_check_delivery',$time_option['start-time'],$time_option);
						$_timeck = $_time_base;
						$check_time_exit = true;
						break;
					}
				}
			}
		}else{ $check_time_exit = true;}

		if($check_time_exit==false){
			wc_add_notice( esc_html__( 'Error, please refresh page and try again','woocommerce-food' ), 'error' );
		}else if($_timeck!=''){
			$date_deli = $date_deli!='' ? $date_deli : strtotime(date("Y-m-d"));
	    	$_timeck = explode(':', $_timeck);
	    	$_timeck = $_timeck[1] * 60 + $_timeck[0] * 3600;
	    	$cure_time ='';
	    	if(is_numeric($date_before)){
	    		$cure_time =  apply_filters( 'exwt_disable_book_day', strtotime("+$date_before day") );

	    	}else if(is_numeric(str_replace("m","",$date_before))){
				$cure_time =  strtotime("now");
				$cure_time = $cure_time + str_replace("m","",$date_before)*60;
				
			}
			$gmt_offset = get_option('gmt_offset');
			if($gmt_offset!=''){
				$cure_time = $cure_time + ($gmt_offset*3600);
			}
			if(($date_deli + $_timeck) < $cure_time){
				wc_add_notice( esc_html__( 'Your time you have selected has closed, please try with different date or time','woocommerce-food'  ), 'error' );
			}
		}else if( $date_deli !=''){
			$date_deli = $date_deli + 86399;
			$cure_time ='';
	    	if(is_numeric($date_before)){
	    		$cure_time =  apply_filters( 'exwt_disable_book_day', strtotime("+$date_before day") );
	    	}else if(is_numeric(str_replace("m","",$date_before))){
				$cure_time =  strtotime("now");
				$cure_time = $cure_time + str_replace("m","",$date_before)*60;
				$gmt_offset = get_option('gmt_offset');
				if($gmt_offset!=''){
					$cure_time = $cure_time + ($gmt_offset*3600);
				}
			}
			
			if($date_deli < $cure_time){
				wc_add_notice( esc_html__( 'Your time you have selected has closed, please try with different date' ), 'error' );
			}
		}
	} 
}
function exwf_check_time_delivery_status($data,$return=false){
	$date_deli = isset($data['exwfood_date_deli']) ? $data['exwfood_date_deli'] : '';
	$rq_date = exwoofood_get_option('exwoofood_ck_date','exwoofood_advanced_options');
	if($rq_date=='disable'){
		$date_deli = strtotime(date("Y-m-d"));
	}
	$_date_type = exwoofood_get_option('exwoofood_dd_display','exwoofood_advanced_options');
	if($_date_type =='picker' && isset($data['exwfood_date_deli']) && !is_numeric($data['exwfood_date_deli'])){
		$date_deli = strtotime($data['exwfood_date_deli']);
		if($date_deli==''){ return;}
	}
	// advanced slots
	$method = WC()->session->get( '_user_order_method' );
	$method = $method !='' ? $method : 'delivery'; 
	$adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
	$_ftimesl =  $tsl_method = '';
	if( $date_deli !='' && is_array($adv_timesl) && !empty($adv_timesl)){
		$day_ofd = date('D',$date_deli);
		$user_log = WC()->session->get( '_user_deli_log' );
		if(isset($_POST['exwoofood_ck_loca'])){
			$user_log = $_POST['exwoofood_ck_loca'];
		}else if(isset($data['exwoofood_ck_loca'])){
			$user_log = $data['exwoofood_ck_loca'];
		}
		foreach ($adv_timesl as $it_timesl) {
			$tsl_log = isset($it_timesl['times_loc'])  ?  $it_timesl['times_loc'] :'';
			if(isset ($it_timesl['repeat_'.$day_ofd]) && $it_timesl['repeat_'.$day_ofd] =='on' && 
				(
					!isset($it_timesl['deli_method']) 
					|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']=='') 
					|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']==$method)
				) && ($tsl_log=='' || is_array($tsl_log) && in_array($user_log, $tsl_log)) ){
				$tsl_method = isset($it_timesl['deli_method']) ?  $it_timesl['deli_method'] : '';
				$_ftimesl = isset($it_timesl['exwfood_deli_time']) && is_array($it_timesl['exwfood_deli_time']) ? $it_timesl['exwfood_deli_time'] : '';
				break;
			}
		}
	}
	$_time = isset($_ftimesl) && is_array($_ftimesl) ? $_ftimesl : exwoofood_get_option('exwfood_deli_time','exwoofood_advanced_options');
	$disable_sl = exwoofood_get_option('exwoofood_disable_tslot','exwoofood_advanced_options');
	if($disable_sl=='yes'){
		foreach ($n_dl_time as $key => $it_dl_time) {
			if(isset($it_dl_time['disable-slot']) && $it_dl_time['disable-slot']=='1' ){
				unset($n_dl_time[$key]);
			}
		}
	}
    if(!empty($_time) && $date_deli!=''){
    	foreach ($_time as $key => $value) {
    		$name = isset($value['name-ts']) && $value['name-ts']!=''? $value['name-ts'] : ($value['start-time'].' - '.$value['end-time']);
    		if(isset($value['max-odts']) && is_numeric($value['max-odts']) && $name == $data['exwfood_time_deli'] ){
				$locat =  isset($data['exwoofood_ck_loca'])  ? $data['exwoofood_ck_loca'] : '';
				$total_rs = exwf_get_number_order_timeslot($date_deli,$data['exwfood_time_deli'],$locat,$method,$tsl_method,$_time);
				if ( $total_rs >= $value['max-odts']){
					$text_datedel = exwf_date_time_text('date');
					$text_timedel = exwf_date_time_text('time');
					$msg = sprintf(esc_html__( 'Sorry, the %s you have selected has full order, please try again with different  %s or time','woocommerce-food' ),$text_timedel,$text_datedel);
					if(isset($return) && $return==true){
						return $msg; 
					}else{
						wc_add_notice( $msg, 'error' );
					}
			    }
    		}
    	}
    }
}

function exwf_get_number_order_timeslot($date,$time,$loc,$method,$tsl_method,$timeslots){
	$status = wc_get_order_statuses();
	if(isset($status['wc-failed'])){ unset($status['wc-failed']); }
	if(isset($status['wc-refunded'])){ unset($status['wc-refunded']); }
	if(isset($status['wc-cancelled'])){ unset($status['wc-cancelled']); }
	$status = apply_filters('exwf_query_od_status',$status);
	$args = array(
		'posts_per_page' => 1,
		'post_type'   => 'shop_order',
		'post_status' =>  array_keys( $status ),
		'meta_query' => array(
			'relation' => 'AND',
	        array(
	            'key'   => 'exwfood_time_deli',
	            'value' => $time,
	            'compare' => '=',
	        ),
	        array(
	            'key' => 'exwfood_date_deli_unix',
	            'value'   => $date,
	            'type'    => 'numeric',
	            'compare' => '=',
	        ),
	    )
	);
	$enable_adv_tl = exwoofood_get_option('exwoofood_adv_loca','exwoofood_advanced_options');
	if($enable_adv_tl=='enable' && $loc!=''){
		$args['meta_query'][] = array(
            'key' => 'exwoofood_location',
            'value'   => $loc,
            'compare' => '=',
        );
	}
	if($tsl_method!=''){
		$args['meta_query'][] = array(
            'key' => 'exwfood_order_method',
            'value'   => $method,
            'compare' => '=',
        );
	}else if($tsl_method=='delivery'){
		$args['meta_query'][] = array(
			'relation' => 'OR',
            array(
	            'key' => 'exwfood_order_method',
	            'value'   => $method,
	            'compare' => '=',
	        ),
		    array(
	            'key' => 'exwfood_order_method',
	            'compare' => 'NOT EXISTS',
	        ),
	        array(
	            'key' => 'exwfood_order_method',
	            'value'   => '',
	            'compare' => '=',
	        )
        );
	}
	$args = apply_filters('exwf_limit_order_qr_args',$args,$timeslots);
	$my_query = new WP_Query($args);
	$total_rs = $my_query->found_posts;
	return $total_rs;
}

/**
 * Update the order meta with field value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'exwf_save_date_deli_field' );

function exwf_save_date_deli_field( $order_id ) {
    if ( ! empty( $_POST['exwfood_date_deli'] ) ) {
    	$_date_type = exwoofood_get_option('exwoofood_dd_display','exwoofood_advanced_options');
    	$foodby_date = exwoofood_get_option('exwoofood_foodby_date');
    	if($_date_type !='picker' || $foodby_date=='yes'){
	        update_post_meta( $order_id, 'exwfood_date_deli', sanitize_text_field( date_i18n(get_option('date_format'), $_POST['exwfood_date_deli']) ) );
	        $deli_unix = sanitize_text_field($_POST['exwfood_date_deli']);
	    }else{
	    	$date_dl= date_i18n(get_option('date_format'),strtotime($_POST['exwfood_date_deli']));
	    	update_post_meta( $order_id, 'exwfood_date_deli', sanitize_text_field( $date_dl) );
	    	$deli_unix = strtotime($_POST['exwfood_date_deli']);
	    }
    }else{
    	$deli_unix = strtotime(date("Y-m-d"));
    }
    update_post_meta( $order_id, 'exwfood_date_deli_unix', $deli_unix );
    if(isset(WC()->session) ){
		$timeslot = WC()->session->get( '_st_timeslot');
		WC()->session->set( '_st_timeslot','');
		if(isset($timeslot['start-time']) && $timeslot['start-time']!=''){
			$_timeck = explode(':', $timeslot['start-time']);
		    $_timeck = $_timeck[1] * 60 + $_timeck[0] * 3600;
		    $deli_unix = $deli_unix + $_timeck;
		    update_post_meta( $order_id, 'exwfood_datetime_deli_unix', $deli_unix );
		}else{
			update_post_meta( $order_id, 'exwfood_datetime_deli_unix', $deli_unix );
		}
	}else{
		update_post_meta( $order_id, 'exwfood_datetime_deli_unix', $deli_unix );
	}
    if ( ! empty( $_POST['exwfood_time_deli'] ) ) {
        update_post_meta( $order_id, 'exwfood_time_deli', sanitize_text_field( $_POST['exwfood_time_deli'] ) );
    }
    if ( ! empty( $_POST['exwoofood_ck_loca'] ) ) {
        update_post_meta( $order_id, 'exwoofood_location', sanitize_text_field( $_POST['exwoofood_ck_loca'] ) );
    }
    if ( ! empty( $_POST['exwfood_person_dinein'] ) ) {
        update_post_meta( $order_id, 'exwfood_person_dinein', sanitize_text_field( $_POST['exwfood_person_dinein'] ) );
    }
}


/**
 * Display field value on thank you page
 */
add_action( 'woocommerce_order_details_after_order_table_items', 'exwf_display_date_deli_fe', 10, 1 );
function exwf_display_date_deli_fe($order){
	$text_datedel = exwf_date_time_text('date',$order);
	$text_timedel = exwf_date_time_text('time',$order);

	$order_method = get_post_meta( $order->get_id(), 'exwfood_order_method', true );
	$order_method = $order_method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $order_method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : esc_html__('Delivery','woocommerce-food'));
	if(get_post_meta( $order->get_id(), 'exwfood_order_method', true )!=''){
    echo '
    <tr>
    	<th>'.esc_html__( 'Order method' , 'woocommerce-food' ).'</th>
    	<td> ' . $order_method . '</td>
    </tr>';
	}
    if(get_post_meta( $order->get_id(), 'exwfood_person_dinein', true )!=''){
	    echo '
	    <tr>
	    	<th>'.esc_html__('Number of person','woocommerce-food').'</th>
	    	<td> ' . get_post_meta( $order->get_id(), 'exwfood_person_dinein', true ) . '</td>
	    </tr>';
	}
	if(get_post_meta( $order->get_id(), 'exwfood_date_deli', true )!=''){
	    echo '
	    <tr>
	    	<th>'.$text_datedel.'</th>
	    	<td> ' . get_post_meta( $order->get_id(), 'exwfood_date_deli', true ) . '</td>
	    </tr>';
	}
	if(get_post_meta( $order->get_id(), 'exwfood_time_deli', true )!=''){
	    echo '
	    <tr>
	    	<th>'.$text_timedel.'</th>
	    	<td> ' . get_post_meta( $order->get_id(), 'exwfood_time_deli', true ) . '</td>
	    </tr>';
	}
	$log_name = get_term_by('slug', get_post_meta( $order->get_id(), 'exwoofood_location', true ), 'exwoofood_loc');
	if(isset($log_name->name) && $log_name->name){
	    echo '
	    <tr>
	    	<th>'.esc_html__( 'Location', 'woocommerce-food' ).'</th>
	    	<td> ' . $log_name->name . '</td>
	    </tr>';
	}
}

/**
 * Display field value on email
 */
add_action( 'woocommerce_email_after_order_table', 'exwf_display_date_deli_em', 10, 1 );
function exwf_display_date_deli_em($order){
	$text_align = is_rtl() ? 'right' : 'left';
	$dv_date = get_post_meta( $order->get_id(), 'exwfood_date_deli', true );
	$dv_time = get_post_meta( $order->get_id(), 'exwfood_time_deli', true );
	$loc_ar = get_post_meta( $order->get_id(), 'exwoofood_location', true );
	$nbperson = get_post_meta( $order->get_id(), 'exwfood_person_dinein', true );
	$log_name = get_term_by('slug', $loc_ar, 'exwoofood_loc');
	$order_method = get_post_meta( $order->get_id(), 'exwfood_order_method', true );
	if($order_method=='' && $dv_date =='' && $dv_time=='' && (!isset ($log_name->name) || $log_name->name=='')){
		return;
	}
	$text_datedel = exwf_date_time_text('date',$order);
	$text_timedel = exwf_date_time_text('time',$order);
    ?>
    <div style="margin-bottom: 40px;">
	    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
	    	<?php 
	    	$order_method = $order_method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $order_method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : ( $order_method=='delivery' ? esc_html__('Delivery','woocommerce-food') : '') );
	    	if($order_method !=''){?>
			    <tr>
			    	<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_html__('Order method','woocommerce-food'); ?></th>
			    	<td class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
			    		<?php echo $order_method; ?>
			    	</td>
			    </tr>
			<?php }
			if($nbperson !=''){?>
			    <tr>
			    	<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_html__('Number of person','woocommerce-food'); ?></th>
			    	<td class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo $nbperson; ?></td>
			    </tr>
			<?php }
	    	if($dv_date !=''){?>
			    <tr>
			    	<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo $text_datedel; ?></th>
			    	<td class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo $dv_date; ?></td>
			    </tr>
			<?php }
			if($dv_time !=''){
				?>
			    <tr>
			    	<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo $text_timedel; ?></th>
			    	<td class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo $dv_time; ?></td>
			    </tr>
			<?php }
			
			if(isset($log_name->name) && $log_name->name!=''){
				?>
			    <tr>
			    	<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_html__( 'Location', 'woocommerce-food' ); ?></th>
			    	<td class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo $log_name->name; ?></td>
			    </tr>
			<?php }?>
	    </table>
	</div>
    <?php
}

// add minimum order amount
add_action( 'woocommerce_checkout_process', 'exwf_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'exwf_minimum_order_amount' );
function exwf_minimum_order_amount() {
	// check open closing time
	$al_products = exwoofood_get_option('exwoofood_ign_op','exwoofood_advanced_options');
	$enable_time = exwoofood_get_option('exwoofood_open_close','exwoofood_advanced_options');
	$i = $j = 0;
	$check_it = false;
	if($enable_time== 'enable' && $al_products!=''){
		$al_products = explode(",",$al_products);
		$msg_it ='';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$i ++;
			$id_cr = $cart_item['product_id'];
			if(!in_array($id_cr, $al_products)){
				$j ++;
				$msg_it .= sprintf( esc_html__('The food "%s" ordering is now closed','woocommerce-food' ) ,get_the_title($id_cr));
			}

		}
		if(($i!=$j) && $j>0){
			$check_it = true;
			if (!exwf_check_open_close_time()){
				if( is_cart()) {
					wc_print_notice(  $msg_it,'error');
				}else{
					wc_add_notice(  $msg_it,'error');
				}
			}
		}else if($j==0){
			$check_it = true;
		}
	}
	if (!exwf_check_open_close_time() && $check_it != true) {
		if( is_cart()) {
			wc_print_notice(  exwfd_open_closing_message(true),'error');
		}else{
			wc_add_notice(  exwfd_open_closing_message(true),'error');
		}
	}else{
		$check_ex = exwf_if_check_product_notin_shipping();
		if($check_ex == false){
			return ;
		}
	    // Set this variable to specify a minimum order value
	    $minimum = exwoofood_get_option('exwoofood_ck_mini_amount','exwoofood_advanced_options');
	    // min by log
	    $loc_selected = exwf_get_loc_selected();
		if($loc_selected!=''){
			$minimum_log = get_term_meta( $loc_selected, 'exwp_loc_min_amount', true );
			if($minimum_log !='' && is_numeric($minimum_log)){
				$minimum = $minimum_log;
			}
		}

	    $total = apply_filters( 'exwf_total_cart_price', WC()->cart->get_subtotal() );
	    $coup = WC()->cart->get_applied_coupons();
		if(is_array($coup) && count($coup) > 0 && is_numeric($minimum) && $minimum > 0){
			foreach ($coup as $itcp) {
				$getDetails = ( new WC_Coupon($itcp));
		    	$discount  =  $getDetails->amount;
		    	if($discount > 0){
		    		$minimum = $minimum - $discount;
		    	}
			}
		}
		$minimum = apply_filters('exwf_minimum_amount_required',$minimum);
	    if ( $minimum!='' && is_numeric($minimum) && $total < $minimum ) {

	        if( is_cart()) {

	            wc_print_notice( 
	                sprintf( esc_html__('Your current order total is %s - you must have an order with a minimum of %s to place your order','woocommerce-food' ) , 
	                    wc_price( $total ), 
	                    wc_price( $minimum )
	                ), 'error' 
	            );

	        } else {

	            wc_add_notice( 
	                sprintf( esc_html__('Your current order total is %s - you must have an order with a minimum of %s to place your order','woocommerce-food' ) , 
	                    wc_price( $total ), 
	                    wc_price( $minimum )
	                ), 'error' 
	            );

	        }
	    }
	}
}
add_action( 'woocommerce_widget_shopping_cart_before_buttons' , 'exwf_minimum_amount_sidecart',999 );
function exwf_minimum_amount_sidecart(){
	// check open closing time
	$al_products = exwoofood_get_option('exwoofood_ign_op','exwoofood_advanced_options');
	$enable_time = exwoofood_get_option('exwoofood_open_close','exwoofood_advanced_options');
	$i = $j = 0;
	$check_it = false;
	if($enable_time== 'enable' && $al_products!=''){
		$al_products = explode(",",$al_products);
		$msg_it ='';
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$i ++;
			$id_cr = $cart_item['product_id'];
			if(!in_array($id_cr, $al_products)){
				$j ++;
				$msg_it .= '<p class="exwf-mini-amount exwf-opcs-warning exwf-warning">'.sprintf( esc_html__('The food "%s"  ordering is now closed','woocommerce-food' ) , 
	                    get_the_title($id_cr)
	        ).'</p>';
			}

		}
		if(($i!=$j) && $j>0){
			echo $msg_it;
			$check_it = true;
		}else if($j==0){
			$check_it = true;
		}
	}
	if (!exwf_check_open_close_time() && $check_it != true) {
		echo '<p class="exwf-mini-amount exwf-opcs-warning exwf-warning">'.exwfd_open_closing_message(true).'</p>';
	}else{
		$minimum = exwoofood_get_option('exwoofood_ck_mini_amount','exwoofood_advanced_options');
		// min by log
	    $loc_selected = WC()->session->get( 'ex_userloc' );
		$user_log = '';
		if($loc_selected==''){
			$user_log = WC()->session->get( '_user_deli_log' );
			$loc_selected=  $user_log ;
		}
		if($loc_selected!=''){
			$term = get_term_by('slug', $loc_selected, 'exwoofood_loc');
			if(isset($term->term_id)){
				$minimum_log = get_term_meta( $term->term_id, 'exwp_loc_min_amount', true );
				if($minimum_log !='' && is_numeric($minimum_log)){
					$minimum = $minimum_log;
				}
			}
		}
		
		$total = apply_filters( 'exwf_total_cart_price', WC()->cart->get_subtotal() );
		$coup = WC()->cart->get_applied_coupons();
		if(is_array($coup) && count($coup) > 0 && is_numeric($minimum) && $minimum > 0){
			foreach ($coup as $itcp) {
				$getDetails = ( new WC_Coupon($itcp));
		    	$discount  =  $getDetails->amount;
		    	if($discount > 0){
		    		$minimum = $minimum - $discount;
		    	}
			}
		}
		$minimum = apply_filters('exwf_minimum_amount_required',$minimum);
	    if ( $minimum!='' && is_numeric($minimum) && $total < $minimum ) {
	    	echo '<p class="exwf-mini-amount exwf-min-required exwf-warning">'.sprintf( esc_html__('Your current order total is %s - you must have an order with a minimum of %s to place your order','woocommerce-food' ) , 
	                    wc_price( $total ), 
	                    wc_price( $minimum )
	        ).'</p>';
	    }
	}
}
// ajax check delivery time available or not
add_action( 'wp_ajax_exwf_time_delivery_status', 'ajax_exwf_time_delivery_status' );
add_action( 'wp_ajax_nopriv_exwf_time_delivery_status', 'ajax_exwf_time_delivery_status' );
function ajax_exwf_time_delivery_status(){
	$data =array();
	$data['exwfood_date_deli'] = isset($_POST['date']) && $_POST['date']!='' && is_numeric($_POST['date']) ? $_POST['date'] : strtotime(date("Y-m-d"));
	$data['exwfood_time_deli'] = isset($_POST['time']) ? $_POST['time'] : '';
	$data['exwoofood_ck_loca'] = isset($_POST['loc']) ? $_POST['loc'] : '';
	/*$adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
	if(is_array($adv_timesl) && !empty($adv_timesl)){
		$day_ofd = date('D',$data['exwfood_date_deli']);
		foreach ($adv_timesl as $it_timesl) {
			if(isset ($it_timesl['repeat_'.$day_ofd]) && $it_timesl['repeat_'.$day_ofd] =='on'){
				//print_r( $it_timesl);
				if(isset($it_timesl['exwfood_deli_time']) && is_array($it_timesl['exwfood_deli_time'])){
					$html_timesl ='';
					foreach ($it_timesl['exwfood_deli_time'] as $time_option) {
						if(isset($time_option['start-time']) && $time_option['start-time']!='' && $time_option['end-time']!=''){
							$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
						}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
							$r_time = $time_option['start-time'];
						}
						$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
						$html_timesl .='<option value="'.esc_attr($name).'">'.$name.'</option>';
					}
				}
				break;
			}
		}
	}
	*/
	$html_timesl ='';
	$html = exwf_check_time_delivery_status($data,true);
	if($html!=''){
		$html = '<p class="exwf-time-stt">'.$html.'</p>';
	}
	$update_your_order = false;
	$method = WC()->session->get( '_user_order_method' );
	$method = $method !='' ? $method : 'delivery';
	$ship_bytime = exwoofood_get_option('exwoofood_shipfee_bytime','exwoofood_shpping_options');
	if($method == 'delivery' && $ship_bytime =='yes'){
		$update_your_order = true;
	}
	$output =  array('html_content'=>$html,'html_timesl' => $html_timesl,'refresh_order' => $update_your_order);
	echo str_replace('\/', '/', json_encode($output));
	die;
}
add_action( 'wp_ajax_exwf_time_delivery_slots', 'ajax_exwf_time_delivery_slots' );
add_action( 'wp_ajax_nopriv_exwf_time_delivery_slots', 'ajax_exwf_time_delivery_slots' );
function ajax_exwf_time_delivery_slots(){
	$data =array();
	$data['exwfood_date_deli'] = isset($_POST['date']) && $_POST['date']!='' && is_numeric($_POST['date']) ? $_POST['date'] : strtotime(date("Y-m-d"));
	$adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
	$html_timesl ='';
	$def_timesl = exwoofood_get_option('exwfood_deli_time','exwoofood_advanced_options');

	$cure_time =  strtotime("now");
	$date_before = exwoofood_get_option('exwoofood_ck_beforedate','exwoofood_advanced_options');
	if(is_numeric($date_before)){
		$cure_time =  apply_filters( 'exwt_disable_book_day', strtotime("+$date_before day") );

	}else if(is_numeric(str_replace("m","",$date_before))){
		$cure_time = $cure_time + str_replace("m","",$date_before)*60;
	}
	$gmt_offset = get_option('gmt_offset');
	if($gmt_offset!=''){
		$cure_time = $cure_time + ($gmt_offset*3600);
	}
	$disable_sl = exwoofood_get_option('exwoofood_disable_tslot','exwoofood_advanced_options');
	$user_log = isset($_POST['loc']) ? $_POST['loc'] : '';
	$method = WC()->session->get( '_user_order_method' );
	$method = $method !='' ? $method : 'delivery';
	$exwf_disslot = apply_filters('exwf_hide_slot_unavailable','yes');
	if(is_array($def_timesl) && !empty($def_timesl)){
		$html_timesl .= '<select name="exwfood_time_deli" id="exwfood_time_deli" class="select " data-time="'.json_encode($def_timesl).'" data-crtime="'.esc_attr($cure_time).'" data-date="'.strtotime(date('Y-m-d', $cure_time)).'" data-placeholder="">';
		foreach ($def_timesl as $time_option) {
			if($disable_sl=='yes' && isset($time_option['disable-slot']) && $time_option['disable-slot']=='1' ){
			
			}else{
				$r_time ='';
				if(isset($time_option['start-time']) && $time_option['start-time']!='' && $time_option['end-time']!=''){
					$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
				}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
					$r_time = $time_option['start-time'];
				}
				$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
				$disable ='';
				$_time_base = apply_filters('exwf_timebase_to_check_delivery',$time_option['start-time'],$time_option);
				if($_time_base!=''){
					$_timeck = $_time_base;
					$_timeck = explode(':', $_timeck);
			    	$_timeck = $_timeck[1] * 60 + $_timeck[0] * 3600;
					if(($data['exwfood_date_deli'] + $_timeck) < $cure_time){
						$disable ='disabled="disabled"';
					}
				}
				$maxsl = isset($time_option['max-odts']) && is_numeric($time_option['max-odts']) ? $time_option['max-odts'] : '';
    			if($disable!='disabled="disabled"' && $maxsl!=''){
					$total_rs = exwf_get_number_order_timeslot($data['exwfood_date_deli'],$name,$user_log,$method,'',$time_option);
					if ($total_rs >= $maxsl){
						$disable ='disabled="disabled"';
					}
				}
				if($exwf_disslot=='yes' && $disable =='disabled="disabled"' ){

				}else{
					$html_timesl .='<option value="'.esc_attr($name).'" '.$disable.'>'.$name.'</option>';
				}
			}
		}
		$html_timesl .= '</select>';
	}else{
		$html_timesl ='<input type="text" class="input-text " name="exwfood_time_deli" id="exwfood_time_deli" placeholder="" value="">';
	}
	if(is_array($adv_timesl) && !empty($adv_timesl)){
		$day_ofd = date('D',$data['exwfood_date_deli']);
		
		foreach ($adv_timesl as $it_timesl) {
			$tsl_log = isset($it_timesl['times_loc'])  ?  $it_timesl['times_loc'] :'';
			if(isset ($it_timesl['repeat_'.$day_ofd]) && $it_timesl['repeat_'.$day_ofd] =='on' && 
				(
					!isset($it_timesl['deli_method']) 
					|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']=='') 
					|| (isset($it_timesl['deli_method']) && $it_timesl['deli_method']==$method) 
				) && ($tsl_log=='' || is_array($tsl_log) && !empty($tsl_log) && in_array($user_log, $tsl_log)) ){
				$tsl_method = isset($it_timesl['deli_method']) ?  $it_timesl['deli_method'] : '';
				//print_r( $it_timesl);
				$html_timesl ='';
				$html_timesl .= '<select name="exwfood_time_deli" id="exwfood_time_deli" class="select " data-time="'.json_encode($def_timesl).'" data-crtime="'.esc_attr($cure_time).'" data-date="'.strtotime(date('Y-m-d', $cure_time)).'" data-placeholder="">';
				if(isset($it_timesl['exwfood_deli_time']) && is_array($it_timesl['exwfood_deli_time'])){
					$def_timesl = $it_timesl['exwfood_deli_time'];
					//$html_timesl .='<option value="">'.esc_html__('Please choose a time slot','woocommerce-food').'</option>';
					foreach ($it_timesl['exwfood_deli_time'] as $time_option) {
						if($disable_sl=='yes' && isset($time_option['disable-slot']) && $time_option['disable-slot']=='1' ){
			
						}else{
							$r_time ='';
							if(isset($time_option['start-time']) && $time_option['start-time']!='' && $time_option['end-time']!=''){
								$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
							}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
								$r_time = $time_option['start-time'];
							}
							$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
							$_time_base = apply_filters('exwf_timebase_to_check_delivery',$time_option['start-time'],$time_option);
							$disable ='';
							if($_time_base!=''){
								$_timeck = $_time_base;
								$_timeck = explode(':', $_timeck);
			    				$_timeck = $_timeck[1] * 60 + $_timeck[0] * 3600;
			    				if($time_option['start-time']!='' && ($data['exwfood_date_deli'] + $_timeck) < $cure_time){
			    					$disable ='disabled="disabled"';
			    				}
			    			}
			    			$maxsl = isset($time_option['max-odts']) && is_numeric($time_option['max-odts']) ? $time_option['max-odts'] : '';
			    			if($disable!='disabled="disabled"' && $maxsl!=''){
								$total_rs = exwf_get_number_order_timeslot($data['exwfood_date_deli'],$name,$user_log,$method,$tsl_method,$time_option);
								if ($total_rs >= $maxsl){
									$disable ='disabled="disabled"';
								}
							}
							if($exwf_disslot=='yes' && $disable =='disabled="disabled"' ){
							}else{
								$html_timesl .='<option value="'.esc_attr($name).'" '.$disable.'>'.$name.'</option>';
							}
						}
					}
				}else{
					$html_timesl .='<option value="">'.esc_html__('No time slot available for selection','woocommerce-food').'</option>';
				}
				$html_timesl .= '</select>';
				break;
			}
		}
	}
	$output =  array('html_timesl' => $html_timesl,'data_time' => json_encode($def_timesl));
	echo str_replace('\/', '/', json_encode($output));
	die;
}
// Send email loc
add_filter( 'woocommerce_email_recipient_new_order', 'exwf_change_email_recipient', 10, 2 );
function exwf_change_email_recipient($recipient, $order){
	$mail = '';
	if( is_object($order) && method_exists($order, 'get_id') && get_post_meta( $order->get_id(), 'exwoofood_location', true )!=''){
		$term = get_term_by('slug', $order->get_meta('exwoofood_location'), 'exwoofood_loc');
		if($term->term_id){
			$mail = get_term_meta($term->term_id,'exwp_loc_email',true);
		}
	}
	if($mail !=''){
		$recipient = $mail;
	}
	return $recipient;
}
// live total
add_action( 'woocommerce_before_add_to_cart_quantity','exwf_update_live_total_price', 32 );
function exwf_update_live_total_price() {
	$enable_livetotal = exwoofood_get_option('exwoofood_enable_livetotal','exwoofood_options');
	if($enable_livetotal=='yes'){
		exwoofood_template_plugin('live-total',1);
	}
}
// metdata
add_action( 'woocommerce_single_product_summary','exwf_food_meta_information_html');
function exwf_food_meta_information_html($id_food=false){
	if(!isset($id_food) || $id_food == ''){
		$id_food = get_the_ID();
	}
	$protein = get_post_meta( $id_food, 'exwoofood_protein', true );
	$calo = get_post_meta( $id_food, 'exwoofood_calo', true );
	$choles = get_post_meta( $id_food, 'exwoofood_choles', true );
	$fibel = get_post_meta( $id_food, 'exwoofood_fibel', true );
	$sodium = get_post_meta( $id_food, 'exwoofood_sodium', true );
	$carbo = get_post_meta( $id_food, 'exwoofood_carbo', true );
	$fat = get_post_meta( $id_food, 'exwoofood_fat', true );

	$custom_data = get_post_meta( $id_food, 'exwoofood_custom_data_gr', true );
	?>
	<div class="exfd_nutrition">
		<ul>
			<?php if($protein!=''){ ?>
				<li>
					<span><?php esc_html_e('Protein','woocommerce-food'); ?></span><?php echo wp_kses_post($protein);?>
				</li>
			<?php }if($calo!=''){ ?>
				<li><span><?php esc_html_e('Calories','woocommerce-food'); ?></span><?php echo wp_kses_post($calo);?></li>
			<?php }if($choles!=''){ ?>
				<li><span><?php esc_html_e('Cholesterol','woocommerce-food'); ?></span><?php echo wp_kses_post($choles);?></li>
			<?php }if($fibel!=''){ ?>
				<li><span><?php esc_html_e('Dietary fibre','woocommerce-food'); ?></span><?php echo wp_kses_post($fibel);?></li>
			<?php }if($sodium!=''){ ?>
				<li><span><?php esc_html_e('Sodium','woocommerce-food'); ?></span><?php echo wp_kses_post($sodium);?></li>
			<?php }if($carbo!=''){ ?>
				<li><span><?php esc_html_e('Carbohydrates','woocommerce-food'); ?></span><?php echo wp_kses_post($carbo);?></li>
			<?php }if($fat!=''){ ?>
				<li><span><?php esc_html_e('Fat total','woocommerce-food'); ?></span><?php echo wp_kses_post($fat);?></li>
			<?php }
			if ($custom_data != '') {
				foreach ($custom_data as $data_it) {?>
	    			<li><span><?php echo wp_kses_post($data_it['_name']); ?></span><?php echo wp_kses_post($data_it['_value']);?></li>
	    			<?php
				}
			}
			?>
			<div class="exfd_clearfix"></div>
	    </ul>
	</div>
	<?php

}
add_filter( 'woocommerce_widget_cart_item_quantity', 'exwf_add_minicart_quantity_fields', 10, 3 );
function exwf_add_minicart_quantity_fields( $html, $cart_item, $cart_item_key ) {
    $product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $cart_item['data'] ), $cart_item, $cart_item_key );
    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
    if ( !$_product->is_sold_individually() ) {
    	$input_args = array('input_value' => $cart_item['quantity']);
    	$product_id =  $_product->get_id();
    	$exwf_varmin = $exwf_varmax = '';
		if($_product->get_parent_id()){
			$exwf_varmin = get_post_meta( $_product->get_parent_id(), 'exwf_minquantity', true );
			$exwf_varmax = get_post_meta( $_product->get_parent_id(), 'exwf_maxquantity', true );
		}
		$exwf_minquantity = $exwf_varmin!='' ? $exwf_varmin : get_post_meta( $product_id, 'exwf_minquantity', true );
		$exwf_maxquantity = $exwf_varmax!='' ? $exwf_varmax : get_post_meta( $product_id, 'exwf_maxquantity', true );
		if ( $exwf_minquantity!='' && $exwf_minquantity > 0 ) {
			$input_args['min_value'] = $exwf_minquantity;
		}
		if ( $exwf_maxquantity!='' && $exwf_maxquantity > 0  ) {
			$input_args['max_value'] = $exwf_maxquantity;
		}
		if ( $_product->managing_stock() && ! $_product->backorders_allowed() ) {
			$stock = $_product->get_stock_quantity();
			$input_args['max_value'] = min( $stock, $input_args['max_value'] );	
		}
		$html = '
			<div class="ex-hidden">
			<div class="exwf-quantity">
				<div class="exwf-con-quantity" data-cart_key="'.esc_attr($cart_item_key).'" data-quatity="'.$cart_item['quantity'].'">
					<input type="button" value="-" id="exminus_ticket" class="ex-minus">'.
					woocommerce_quantity_input( $input_args, $cart_item['data'], false )
					.'<input type="button" value="+" id="explus_ticket" class="ex-plus">
				</div>
			</div>
			x '. $product_price.
			'</div>'.$html;
	}
    return $html;
}

add_action( 'wp_ajax_exwf_update_quantity', 'ajax_exwf_update_quantity' );
add_action( 'wp_ajax_nopriv_exwf_update_quantity', 'ajax_exwf_update_quantity' );
function ajax_exwf_update_quantity(){
	$key = $_POST['key'];
	$value = $_POST['quantity'];
	global $woocommerce;
	$values = WC()->cart->get_cart_item( $key );
	$passed_validation = apply_filters( 'woocommerce_update_cart_validation', true, $key, $values, $value );
	if($passed_validation==false){
	    $notice = wc_print_notices( true);
	    if($notice!=''){
	    	$data = array(
	            'error' => true,
	            'message' => '<div class="exfd-out-notice">'.$notice.'</div>'
	        );
	        echo wp_send_json($data);
	        wc_clear_notices();
	        wp_die();
	    }
	}
	WC()->cart->set_quantity( $key, $value );echo '1';
    wp_die();
}

add_filter( 'woocommerce_add_to_cart_validation', 'exwf_validate_food_in_loc', 4, 4 );
function exwf_validate_food_in_loc($passed, $product_id, $quantity, $variation_id=false){
	if ( exwoofood_get_option('exwoofood_enable_loc') =='yes' ) {
		if(is_numeric($variation_id) && $variation_id > 0){
			$variation = wc_get_product($variation_id);
			$product_id = $variation->get_parent_id();
		} else if(get_post_type($product_id) == 'product_variation') {
			$variation = wc_get_product($product_id);
			$variation_id = $product_id = $variation->get_parent_id();
		}
		$loc_selected = WC()->session->get( 'ex_userloc' );
		
		if(has_term( '', 'exwoofood_loc', $product_id ) && !has_term( $loc_selected, 'exwoofood_loc', $product_id )){
			$passed = false;
			wc_add_notice( esc_html__( 'Something went wrong, please try again', 'woocommerce-food' ), 'error' );
		}
	}
	return $passed;
}
add_action( 'woocommerce_checkout_process', 'exwf_if_product_is_not_inlocation_orpostcodes' );
function exwf_if_product_is_not_inlocation_orpostcodes(){
	$method = WC()->session->get( '_user_order_method' );
	$method = $method !='' ? $method : 'delivery';
	if($method =='delivery'){
		$delivery_zones = WC_Shipping_Zones::get_zones();
		if(is_array($delivery_zones) && empty($delivery_zones) || $delivery_zones==''){
			$postcode = exwoofood_get_option('exwoofood_ship_postcodes','exwoofood_shpping_options');
			$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	    	if($ship_mode=='postcode' && $postcode!=''){
	    		$postcode = str_replace(' ', '', $postcode);
	    		$postcode = explode(',',$postcode);
	    		$user_postc = isset($_POST['billing_postcode']) ? $_POST['billing_postcode'] : '';
	    		if(isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address'] =='1'){
	    			$user_postc = isset($_POST['shipping_postcode']) ? $_POST['shipping_postcode'] : '';
	    		}
	    		if($user_postc!=''){
		    		$valid = false;
		    		if(is_array($postcode) && isset($postcode) && $postcode[0]=='*'){
		    			$valid = true;
		    		}else if(in_array($user_postc, $postcode)){
		    			$valid = true;
		    		}else{
		    			$needle = '*';
		    			$ret = array_filter($postcode, function($var) use ($needle){
						    return strpos($var, $needle) !== false;
						});
		    			if(is_array($ret) && !empty($ret)){
		    				foreach ($ret as $itpc) {
		    					$itpc = str_replace("*","",$itpc);
		    					if (strpos($user_postc, $itpc) !== FALSE){
		    						$valid = true;
		    						break;
		    					}
		    				}
		    			}
		    		}
		    		if($valid == false){
		    			$title = sprintf( esc_html__('Your address are out of delivery zone, please change to carryout channel','woocommerce-food' ) ,$title);
						wc_add_notice(  $title,'error');
						return;
		    		}
		    	}
	    	}
		}
	}
	if ( exwoofood_get_option('exwoofood_enable_loc') =='yes' ) {
		$title ='';
		$loc_selected = isset($_POST['exwoofood_ck_loca']) ? $_POST['exwoofood_ck_loca'] : WC()->session->get( 'ex_userloc' );
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$product_id = $values['product_id'];
			if (has_term( '', 'exwoofood_loc', $product_id ) && !has_term( $loc_selected, 'exwoofood_loc', $product_id ) ) {
				$title = $title!='' ? $title.', '.get_the_title($product_id) : get_the_title($product_id);
        		WC()->cart->remove_cart_item( $cart_item_key );

    		}
		}
		if($title!=''){
			$title = sprintf( esc_html__('The food "%s" has been removed because it does not exist in this location, please refresh page and try again','woocommerce-food' ) ,$title);
			wc_add_notice(  $title,'error');
			return;
		}
	}
}


add_action( 'woocommerce_cart_calculate_fees','exwf_dine_in_surcharge' );
function exwf_dine_in_surcharge() {
	global $woocommerce;
	if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return;}
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod!='dinein' && $user_odmethod!='takeaway'){ return;}
	$tax_fee = apply_filters('exwf_surcharge_fee_tax',false);
	$total_cart = WC()->cart->get_cart_contents_total();
	$al_products = exwoofood_get_option('exwoofood_ign_deli','exwoofood_advanced_options');
	$al_cats = exwoofood_get_option('exwoofood_igncat_deli','exwoofood_advanced_options');
	if($al_products!='' || (is_array($al_cats) && !empty($al_cats))){
		$al_products = $al_products!='' ? explode(",",$al_products) : array();
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$id_cr = $cart_item['product_id'];
			if(is_array($al_cats) && !empty($al_cats)){
				if(in_array($id_cr, $al_products) || has_term( $al_cats, 'product_cat', $id_cr ) ){
					$line_subtotal = wc_format_decimal($cart_item['line_subtotal']);
					$total_cart = $total_cart - $line_subtotal;
				}
			}else{
				if(!empty($al_products) && in_array($id_cr, $al_products)){
					$line_subtotal = wc_format_decimal($cart_item['line_subtotal']);
					$total_cart = $total_cart - $line_subtotal; 
				}
			}
		}
	}
	if($total_cart<=0){
		return;
	}
	if($user_odmethod=='dinein'){
		$surcharge = exwoofood_get_option('exwoofood_dinein_sur','exwoofood_adv_dinein_options');
		$lablel = esc_html__('Dine-in Surcharge','woocommerce-food');
		if($surcharge!='' && is_numeric($surcharge)){
			if($surcharge < 0 ){$lablel = esc_html__('Dine-in Discount','woocommerce-food');}
			$woocommerce->cart->add_fee( $lablel, $surcharge, $tax_fee, '' );
		}else if($surcharge!='' && is_numeric(str_replace("%","",$surcharge))){
			if($surcharge < 0 ){$lablel = esc_html__('Dine-in Discount','woocommerce-food');}
			$fee = $total_cart*(str_replace("%","",$surcharge)/100);
			$woocommerce->cart->add_fee( $lablel, $fee, $tax_fee, '' );
		}
		
	}else if($user_odmethod=='takeaway'){
		$surcharge = exwoofood_get_option('exwoofood_takeaway_sur','exwoofood_adv_takeaway_options');
		$lablel = esc_html__('Takeaway Surcharge','woocommerce-food');
		if($surcharge!='' && is_numeric($surcharge)){
			if($surcharge < 0 ){$lablel = esc_html__('Takeaway Discount','woocommerce-food');}
			$woocommerce->cart->add_fee( $lablel, $surcharge, $tax_fee, '' );
		}else if($surcharge!='' && is_numeric(str_replace("%","",$surcharge))){
			if($surcharge < 0 ){$lablel = esc_html__('Takeaway Discount','woocommerce-food');}
			$fee = $total_cart*(str_replace("%","",$surcharge)/100);
			$woocommerce->cart->add_fee( $lablel, $fee, $tax_fee, '' );
		}
	}
}
// add review form to popup
add_action( 'exwf_modal_after_price','exwf_add_review_tab' );
function exwf_add_review_tab($id_food) {
	if ( comments_open($id_food) ){?>
		<div class="exwf-md-tabs">
			<span class="exwf-tab exwf-tab-current" data-control="exwf-md-details"><?php esc_html_e('Details','woocommerce-food')?></span>
			<span class="exwf-tab" data-control="exwf-reviews"><?php esc_html_e('Reviews','woocommerce-food')?></span>
		</div> 
		<?php
	}
	//exwoofood_template_plugin('review',1);
}
add_action( 'exwf_modal_after_content','exwf_add_review_form' );
function exwf_add_review_form() {
	exwoofood_template_plugin('review',1);
}
// ajax post rating
function exwf_ajax_comments( $comment_ID, $comment_status ){
    if( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
        //If AJAX Request Then
        switch( $comment_status ) {
            case '0':
                //notify moderator of unapproved comment
                wp_notify_moderator( $comment_ID );
                echo '<p>'.esc_html__('Your review is awaiting approval','woocommerce-food').'</p>';
                break;
            case '1': //Approved comment
                //echo "success";
                $commentdata = get_comment( $comment_ID, ARRAY_A );
                wp_notify_postauthor( $comment_ID);
                echo '<p>'.esc_html__('Thanks for your rating!','woocommerce-food').'</p>';
                break;
            default:
                echo '<p>'.esc_html__('Oops something went wrong!','woocommerce-food').'</p>';
                break;
        }
        exit;
    }
}
add_action( 'comment_post', 'exwf_ajax_comments', 20, 2 );
// add cross sells side cart
add_action( 'woocommerce_mini_cart_contents', 'exwf_show_cross_sells', 1 );
function exwf_show_cross_sells(){
	$limit = 2; $columns = 2; $orderby = 'rand'; $order = 'desc';
	$cross_sells = WC()->cart->get_cross_sells();
	if(is_array($cross_sells) && !empty($cross_sells)){
		shuffle($cross_sells);
		$cross_sells = apply_filters('exwf_cross_sells_arr',$cross_sells);
		$cross_sells = implode(",", $cross_sells);
		global $modal_html,$pu_del;
		$modal_html = "on";$pu_del = "1";
		echo '<div class="exwf-cart-cross-sells ex-load-hidden">
		<h3>'.esc_html__('You may be interested in...','woocommerce-food').'</h3>
		'.do_shortcode('[ex_wf_carousel style="3" cart_enable="no"  count="999" slidesshow="1" ids="'.$cross_sells.'" autoplay="no" loading_effect="1" infinite="yes" number_excerpt="0"]').'</div>';
		$modal_html = $pu_del = "";
	}
}

add_action( 'woocommerce_new_order', 'exwf_add_datafood_notes');
if(!function_exists('exwf_add_datafood_notes')){
	function exwf_add_datafood_notes( $order_id ) {
		$saveinfo = exwoofood_get_option('exwoofood_saveinfo_into_notes');
		if($saveinfo!='yes'){ return;}
		$order = new WC_Order( $order_id ); 
		$date = isset($_POST['exwfood_date_deli']) ? $_POST['exwfood_date_deli'] : '';
		$_date_type = exwoofood_get_option('exwoofood_dd_display','exwoofood_advanced_options');
		$foodby_date = exwoofood_get_option('exwoofood_foodby_date');
		if($_date_type =='picker' && $date !='' && $foodby_date!='yes'){
			$date = strtotime($date);
		}
		$date = $date!='' ? date_i18n(get_option('date_format'),$date) : '';
		$text_datedel = exwf_date_time_text('date',$order);
		$text_timedel = exwf_date_time_text('time',$order);
		$date = $date!='' ? $text_datedel.': '.$date : '';
		$time = isset($_POST['exwfood_time_deli']) ? $_POST['exwfood_time_deli'] : '';
		$time = $time!='' ? $text_timedel.': '.$time :'';
		$method = WC()->session->get( '_user_order_method' );
		$method = $method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : ( $method=='delivery' ? esc_html__('Delivery','woocommerce-food') : '') );
		$method = $method !='' ? esc_html__( 'Order method' , 'woocommerce-food' ).': '.$method :'';
		$loc = isset($_POST['exwoofood_ck_loca']) && $_POST['exwoofood_ck_loca']!='' ? $_POST['exwoofood_ck_loca'] :'';
		if($loc!=''){
			$log_name = get_term_by('slug', $loc, 'exwoofood_loc');
			if(isset($log_name->name) && $log_name->name){
				$loc = esc_html__( 'Location', 'woocommerce-food' ).': '.$log_name->name;
			}
		}
		$person_di = isset($_POST['exwfood_person_dinein']) && $_POST['exwfood_person_dinein']!='' ? esc_html__('Number of person','woocommerce-food').': '.$_POST['exwfood_person_dinein'] :'';
		$note = isset($_POST['order_comments']) ? $_POST['order_comments'] : '';
		$note = $note."\r\n".$method."\r\n".$date."\r\n".$time;
		if($loc!=''){
			$note = $note."\r\n".$loc;
		}
		if($person_di!=''){
			$note = $note."\r\n".$person_di;
		}
		$order->add_order_note( $note );
	}
}