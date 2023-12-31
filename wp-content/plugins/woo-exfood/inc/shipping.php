<?php
function exwf_get_shipping_packages($value) {
    // Packages array for storing 'carts'
    $packages = array();
    $packages[0]['contents']                = WC()->cart->cart_contents;
    //$packages[0]['contents_cost']           = '5';
    $packages[0]['applied_coupons']         = WC()->session->applied_coupon;
    if ( !is_user_logged_in() ) {
	    //$packages[0]['user']['ID']  = 0;
	}
    $packages[0]['destination']['country']  = '';
    $packages[0]['destination']['state']    = '';
    $packages[0]['destination']['postcode'] = $value['postcode'];
    $packages[0]['destination']['city']     = '';
    $packages[0]['destination']['address']  = '';
    $packages[0]['destination']['address_2']= '';
    //$packages[0]['cart_subtotal']           = '5.03';
    return apply_filters('woocommerce_cart_shipping_packages', $packages);
}
function exwf_get_shipping_packages_available($values) {
	$postcode = exwoofood_get_option('exwoofood_ship_postcodes','exwoofood_shpping_options');
	$delivery_zones = WC_Shipping_Zones::get_zones();
	if(empty($delivery_zones) && $postcode==''){
		return array('ok');
	}
	$active_methods   = array();
	if(!empty($delivery_zones)){
		global $woocommerce;
	    $empty_cart = 0;
	    if ( WC()->cart->is_empty() ) {
	    	$empty_cart = 1;
	    	$id_fac = apply_filters('exwf_fac_id_addtocart','');
	    	if($id_fac=='' || !is_numeric($id_fac)){
		    	$args = array(
				    'post_type'      => 'product',
				    'posts_per_page' => 1,
				    'post_status' => array( 'publish'),
				    'meta_query' => array(
						array(
							'key' => '_price',
							'value' => 0,
							'compare' => '>',
							'type' => 'NUMERIC'
						)
					), 
				);
				$id_qrs= get_posts($args);
				if(!empty($id_qrs)){
					foreach ( $id_qrs as $id_qr ) {
						$woocommerce->cart->add_to_cart($id_qr->ID);
						break;
					}
				}
			}else{
				$woocommerce->cart->add_to_cart($id_fac);
			}
	    }
	    WC()->shipping->calculate_shipping(exwf_get_shipping_packages($values));
	    $shipping_methods = WC()->shipping->packages;
	    foreach ($shipping_methods[0]['rates'] as $id => $shipping_method) {
	        $active_methods[] = array(  'id'        => $shipping_method->method_id,
	                                    'type'      => $shipping_method->method_id,
	                                    'provider'  => $shipping_method->method_id,
	                                    'name'      => $shipping_method->label,
	                                    'price'     => number_format($shipping_method->cost, 2, '.', ''));
	    }
	    if($empty_cart == 1){
	    	$woocommerce->cart->empty_cart(); 
	    }
	}
    if(empty($active_methods)){
    	if($postcode!=''){
    		$postcode = str_replace(' ', '', $postcode);
    		$postcode = explode(',',$postcode);
    		if(is_array($postcode) && isset($postcode) && $postcode[0]=='*'){
    			return array('ok');
    		}else if(in_array($values['postcode'], $postcode)){
    			return array('ok');
    		}else{
    			$needle = '*';
    			$ret = array_filter($postcode, function($var) use ($needle){
				    return strpos($var, $needle) !== false;
				});
    			if(is_array($ret) && !empty($ret)){
    				foreach ($ret as $itpc) {
    					$itpc = str_replace("*","",$itpc);
    					if (strpos($values['postcode'], $itpc) !== FALSE){
    						return array('ok');
    					}
    				}
    			}
    		}
    	}
    }
    return apply_filters('exwf_postcode_shipping_defpack',$active_methods,$values);
}
// Get distance
$api = exwoofood_get_option('exwoofood_gg_api','exwoofood_shpping_options');
function exwf_get_distance($from,$to,$km){
	do_action('exwf_before_caculate_distance',$from,$to,$km);
	$rs =array();
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	if($ship_mode=='postcode'){
		if($to!=''){
			$data = array ('country' => '', 'postcode'  => $to);
			$get_pk = exwf_get_shipping_packages_available($data);
			if(is_array($get_pk) && !empty($get_pk)){
				$rs['mes'] ='';
				WC()->session->set( '_user_postcode' , $to);
				/*$customer = WC()->session->get('customer');
				$customer['shipping_postcode'] = $to;
				WC()->session->set('customer',$customer);*/
				WC()->customer->set_shipping_postcode($to);

			}else{
				$rs['mes'] = esc_html__('Your address are out of delivery zone, please change to carryout channel','woocommerce-food');
			}
		}else{
			$rs['distance'] = '0';
			$rs['mes'] = esc_html__('Please add your postcode','woocommerce-food');
		}
		return $rs;
	}
	$map_lang = urlencode(apply_filters('exwf_map_matrix_lang','en-EN'));
	$api = exwoofood_get_option('exwoofood_gg_api','exwoofood_shpping_options');
	$diskm = exwoofood_get_option('exwoofood_restrict_km','exwoofood_shpping_options');
	$distace_api = exwoofood_get_option('exwoofood_gg_distance_api','exwoofood_shpping_options');
	if($distace_api==''){
		$distace_api = $api;
	}
	if($km =='' ){ $km = $diskm;}
	if($api =='' || $km=='99999'){
		WC()->session->set( '_user_deli_adress' , $to);
		if($api !=''){
			$data_address = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=".esc_attr($distace_api)."&address=".urlencode($to)."&language=$map_lang&sensor=true");
			$data_address = json_decode($data_address);
			if($data_address->status == 'REQUEST_DENIED'){
				$data_address = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=".esc_attr($api)."&address=".urlencode($to)."&language=$map_lang&sensor=true");
				$data_address = json_decode($data_address);
			}
			if(isset($data_address->results[0]->address_components)){
				WC()->session->set( '_user_deli_adress_details' , $data_address->results[0]->address_components);
			}
		}else{
			
		}
		$rs['mes'] ='';
		return $rs;
	}
	$store_address = get_option( 'woocommerce_store_address', '' );
	$store_address = apply_filters('exwf_default_store_address',$store_address);
	if($from =='' ){ $from = $store_address;}

	$from = urlencode($from);
	$to = urlencode($to);

	$calcu_mode = exwoofood_get_option('exwoofood_calcu_mode','exwoofood_shpping_options');
	$calcu_mode = $calcu_mode!='' ? $calcu_mode : 'driving';
	$mode_transport = apply_filters('exwf_mode_transport_map_api',$calcu_mode);
	$data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?key=".esc_attr($distace_api)."&origins=".$from."&destinations=".$to."&language=$map_lang&sensor=false&mode=".esc_attr($mode_transport));
	$data = json_decode($data);

	if($data==''){
		$curlSession = curl_init();
		curl_setopt($curlSession, CURLOPT_URL, "https://maps.googleapis.com/maps/api/distancematrix/json?key=".esc_attr($distace_api)."&origins=".$from."&destinations=".$to."&language=$map_lang&sensor=false&mode=".esc_attr($mode_transport));
		curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
		curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
		$jsonData = curl_exec($curlSession);
		curl_close($curlSession);
		$data = json_decode($jsonData);
	}
	//print_r($data);exit;
	WC()->session->set( '_user_deli_adress' , '');
	WC()->session->set( '_user_deli_adress_details','');
	if(isset($data->rows[0])){
		$time = 0;
		$distance = 0;
		foreach($data->rows[0]->elements as $road) {
		    $time += $road->duration->value;
		    $distance += $road->distance->value;
		}
		if($distance<='0'){
			$rs['distance'] = '0';
			$rs['mes'] = esc_html__('Could not calculate distance to your address, please re-check your address','woocommerce-food');
		}else{
			$distance = $distance/1000;
			if($km!='' && $distance > $km){
				$rs['distance'] = $distance;
				$rs['limit'] = $km;
				$rs['mes'] = esc_html__('Your address are out of delivery zone, please change to carryout channel','woocommerce-food');;
			}else{
				if(isset($data->destination_addresses[0])){
					$data_address = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=".esc_attr($distace_api)."&address=".$to."&language=$map_lang&sensor=true");
					$data_address = json_decode($data_address);
					if($data_address->status == 'REQUEST_DENIED'){
						$data_address = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=".esc_attr($api)."&address=".urlencode($to)."&language=$map_lang&sensor=true");
						$data_address = json_decode($data_address);
					}
					//print_r($data_address);exit;
					if(isset($data_address->results[0]->address_components)){
						WC()->session->set( '_user_deli_adress_details' , $data_address->results[0]->address_components);
						// extract address to get street 
						/*foreach($data_address->results[0]->address_components as $address_component){
						    if(in_array('country', $address_component->types)){
						        $country = $address_component->long_name;
						        continue;
						    } elseif(in_array('route', $address_component->types)) {
						        $address = $address_component->long_name;
						        continue;
						    }
						    // etc...
						}*/
					}
					WC()->session->set( '_user_deli_adress' , $data->destination_addresses[0]);
				}
				$distance = apply_filters('exwf_user_distance_km',$distance,$data);
				WC()->session->set( '_user_distance' , $distance);
				$rs['distance'] = $distance;
				$rs['mes'] ='';
			}
		}
	}else{
		$rs['distance'] = 'null';
		$rs['mes'] = isset($data->error_message) ? $data->error_message : '';
	}
	return $rs;
}
// 

add_action( 'init','exwf_clear_user_address' );
function exwf_clear_user_address(){
	if(is_admin()&& !defined( 'DOING_AJAX' ) || !isset(WC()->session) ){ return;}
	if(isset($_GET['change-address']) && $_GET['change-address']==1){
		WC()->session->set( '_user_deli_adress' , '');
		WC()->session->set( '_user_deli_log' , '');
		WC()->session->set( '_user_postcode' , '');
		if(exwoofood_loc_field_html()==''){
			WC()->session->set( '_user_order_method' , '');
		}
	}
	if(isset($_GET['change-method']) && ($_GET['change-method']=='delivery' || $_GET['change-method']=='takeaway'  || $_GET['change-method']=='dinein' )){
		WC()->session->set( '_user_order_method' , $_GET['change-method']);
	}
	if(isset($_GET["loc"]) && WC()->session->get( '_user_deli_log' )==''){
		WC()->session->set( '_user_deli_log', $_GET["loc"] );
	}
	$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
	$dine_in = exwoofood_get_option('exwoofood_enable_dinein','exwoofood_shpping_options');
	if($method_ship=='takeaway' && $dine_in!='yes'){
		WC()->session->set( '_user_order_method' , 'takeaway');
	}else if($method_ship=='delivery' && $dine_in!='yes'){
		WC()->session->set( '_user_order_method' , 'delivery');
	}else if($method_ship == '' && $dine_in=='yes'){
		WC()->session->set( '_user_order_method' , 'dinein');
	}else if($method_ship=='' && $dine_in!='yes'){
		WC()->session->set( '_user_order_method' , '');
	}
}
// Popup order method
function exwoofood_loc_field_html($loc=false,$s_all=false){
	$args = array(
		'hide_empty'        => true,
		'parent'        => '0',
	);
	if(exwoofood_get_option('exwoofood_enable_loc') !='yes'){
		$args['hide_empty'] = false; 
	}
	$locations = isset($loc) && $loc!='' ? explode(",",$loc) : array();
	if (!empty($locations) && !is_numeric($locations[0])) {
		$args['slug'] = $locations;
	}else if (!empty($locations)) {
		$args['include'] = $locations;
	}
	$args = apply_filters('exwf_location_args',$args);
	if(isset($s_all) && $s_all==true){$args['exclude']='';}else{ $s_all='';}
	$terms = get_terms('exwoofood_loc', $args);
	ob_start();
	$loc_selected = WC()->session->get( 'ex_userloc' );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){?>
		<select class="ex-ck-select exfd-choice-locate ex-logreq" name="_location">
			<?php 
			global $wp;
	    	$count_stop = 5;
	    	echo '<option disabled selected value>'.esc_html__( '-- Select --', 'woocommerce-food' ) .'</option>';
	    	foreach ( $terms as $term ) {
	    		$select_loc = '';
	    		if ($term->slug !='' && $term->slug == $loc_selected) {
	                $select_loc = ' selected="selected"';
	              }
		  		echo '<option value="'. esc_attr($term->slug) .'" '.$select_loc.'>'. wp_kses_post($term->name) .'</option>';
		  		echo exfd_show_child_location('',$term,$count_stop,$loc_selected,'yes',$s_all);
		  	}
	        ?>
		</select>
		<?php
	}
	$loca = ob_get_contents();
	ob_end_clean();
	return $loca;
}

function exwf_poup_delivery_type_html($args){
	$enable_mtod = isset($args['enable_mtod']) ? $args['enable_mtod'] : '';
	if($enable_mtod=='no'){ return;}
	global $locations;
	$locations = isset($args['locations']) ? $args['locations'] : '';
	$method = WC()->session->get( '_user_order_method' );
	$user_log = WC()->session->get( '_user_deli_log' );
	$user_addre = WC()->session->get( '_user_deli_adress' );
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	if($ship_mode=='postcode'){
		$user_addre = WC()->session->get( '_user_postcode' );
	}
	$loc_selected = WC()->session->get( 'ex_userloc' );
	if(( ($method=='takeaway' || $method=='dinein') && (exwoofood_loc_field_html($locations)=='' || /*$loc_selected!='' ||*/  $user_log!='') )){
		return;
	}
	if($user_addre!='' && exwoofood_get_option('exwoofood_enable_loc') !='yes' || ( $user_addre!='' && exwoofood_get_option('exwoofood_enable_loc') =='yes' && $loc_selected!='') ){ 
		return;
	}
	if($enable_mtod!='yes'){
		global $pu_del;
		if($pu_del==''){
			$pu_del = 1;
		}else{
			return;
		}
	}
	
	exwoofood_template_plugin('order-method',1);
}
add_action('exwf_before_shortcode_content','exwf_poup_delivery_type_html',12,1);
// add shipping method to top of checkout field
function exwf_get_method_enable(){
	$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
	$dine_in = exwoofood_get_option('exwoofood_enable_dinein','exwoofood_shpping_options');
	$arr_methods= array();
	if($method_ship=='takeaway' || $method_ship=='delivery'){
		$arr_methods[] = $method_ship;
	}else if($method_ship=='both'){
		$arr_methods[] = 'delivery';
		$arr_methods[] = 'takeaway';
	}
	if($dine_in=='yes'){
		$arr_methods[] = 'dinein';
	}
	return apply_filters('exwf_arr_enable_method',$arr_methods);
}
add_action( 'woocommerce_before_checkout_form', 'exwf_shipping_method_selectbox' );
function exwf_shipping_method_selectbox(){
	$arr_methods = exwf_get_method_enable();
	if(empty($arr_methods)){ return;}
	$user_odmethod = WC()->session->get( '_user_order_method' );
	$cr_url =  wc_get_checkout_url();
	$cr_url = apply_filters('exwf_url_change_method',$cr_url);
	$check_ex = exwf_if_check_product_notin_shipping();
	if($check_ex == false){
		WC()->session->set( '_user_order_method' , '');
		return ;
	}
	if(!in_array($user_odmethod, $arr_methods)){
		$user_odmethod = $arr_methods[0];
		WC()->session->set( '_user_order_method' , $user_odmethod);
	}
	$loc_selected = WC()->session->get( 'ex_userloc' );
	if($loc_selected!=''){
		$exclude_tk =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_adv_takeaway_options');
		if(is_array($exclude_tk) && in_array($loc_selected, $exclude_tk)){
			if (($key = array_search('takeaway', $arr_methods)) !== false) {
			    unset($arr_methods[$key]);
			}
		}
		$exclude_di =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_adv_dinein_options');
		if(is_array($exclude_di) && in_array($loc_selected, $exclude_di)){
			if (($key_2 = array_search('dinein', $arr_methods)) !== false) {
			    unset($arr_methods[$key_2]);
			}
		}
		$exclude_dl =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_advanced_options');
		if(is_array($exclude_dl) && in_array($loc_selected, $exclude_dl)){
			if (($key_3 = array_search('delivery', $arr_methods)) !== false) {
			    unset($arr_methods[$key_3]);
			}
		}
	}

	?>
	<div class="exwf-cksp-method exwf-method-ct">
		<div class="exwf-method-title">
	        <?php if(in_array('delivery', $arr_methods)){?>
	        	<a href="<?php echo esc_url(add_query_arg(array('change-method' => 'delivery' ), $cr_url));?>" class="exwf-order-deli <?php if($user_odmethod=='delivery'){?> at-method <?php }?>">
		            <?php esc_html_e('Delivery','woocommerce-food');?>
		        </a>
                <?php 
            }
            if(in_array('takeaway', $arr_methods)){
                ?>
                <a href="<?php echo esc_url(add_query_arg(array('change-method' => 'takeaway' ), $cr_url));?>" class="exwf-order-take <?php if($user_odmethod=='takeaway'){?> at-method <?php }?>">
		            <?php esc_html_e('Takeaway','woocommerce-food');?>
		        </a>
            <?php }
            if(in_array('dinein', $arr_methods)){
                ?>
                <a href="<?php echo esc_url(add_query_arg(array('change-method' => 'dinein' ), $cr_url));?>" class="exwf-order-dinein <?php if($user_odmethod=='dinein'){?> at-method <?php }?>">
		            <?php esc_html_e('Dine-in','woocommerce-food');?>
		        </a>
            <?php }?>
	    </div>
    </div>
	<?php
}



add_action( 'wp_ajax_exwf_check_distance', 'ajax_exwf_check_distance' );
add_action( 'wp_ajax_nopriv_exwf_check_distance', 'ajax_exwf_check_distance' );
function ajax_exwf_check_distance(){
	$method = $_POST['method'];
	$log = $_POST['log'];
	WC()->session->set( '_user_deli_log' , $log);
	WC()->session->set( '_user_order_method' , $method);
	$output = array();
	if($method=='takeaway' || $method=='dinein'){
		$output['mes']= '';
	}else{
		$address = $_POST['address'];
		$from = '';
		$diskm = exwoofood_get_option('exwoofood_restrict_km','exwoofood_shpping_options');
		if($log!=''){
			$term = get_term_by('slug', $log, 'exwoofood_loc');
			if(isset($term->term_id)){
				$addres_log = get_term_meta( $term->term_id, 'exwp_loc_address', true );
				if($addres_log !=''){
					$from = $addres_log;
				}
				$addres_km = get_term_meta( $term->term_id, 'exwp_loc_diskm', true );
				if($addres_km !=''){
					$diskm = $addres_km;
				}
			}
		}
		$output = exwf_get_distance($from,$address,$diskm);
	}
	echo str_replace('\/', '/', json_encode($output));
	die;
}


add_action( 'exwf_sidecart_after_content', 'exwf_add_user_info_sidecart' );
function exwf_add_user_info_sidecart(){
	$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
	$dine_in = exwoofood_get_option('exwoofood_enable_dinein','exwoofood_shpping_options');
	if($method_ship=='' && $dine_in!='yes'){ return;}
	$user_odmethod = WC()->session->get( '_user_order_method' );
	global $wp;
	$cr_url =  home_url( $wp->request );
	$cr_url = apply_filters('exwf_current_link',$cr_url);
	$addres_log = '';
    if($user_odmethod=='takeaway' || $user_odmethod=='dinein'){
    	$user_log = WC()->session->get( '_user_deli_log' );
    	$url = add_query_arg(array('change-address' => 1), $cr_url);
    	if($user_log!=''){
			$term = get_term_by('slug', $user_log, 'exwoofood_loc');
			if(isset($term->term_id)){
				$addres_log = get_term_meta( $term->term_id, 'exwp_loc_address', true );
				if($addres_log ==''){
					$addres_log = $term->name;
				}
			}
		}else{
			$addres_log = get_option( 'woocommerce_store_address', '' );
			$addres_log = apply_filters('exwf_default_store_address',$addres_log);
		}?>
		<div class="exwf-user-dl-info">
			<?php if($user_odmethod=='dinein'){ ?>
				<span class="adrl-title"><?php esc_html_e('Dine-in at: ','woocommerce-food'); ?></span>
			<?php }else{?>
	            <span class="adrl-title"><?php esc_html_e('Carryout at: ','woocommerce-food'); ?></span>
	        <?php }?>
            <span class="adrl-info"><?php echo $addres_log;?></span>
            <span class="adrl-link"><a href="<?php echo esc_url($url);?>"><?php esc_html_e(' Change it ?','woocommerce-food'); ?></a></span>
        </div>
		<?php
    }else{
    	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
		if($ship_mode=='postcode'){
			$user_address = WC()->session->get( '_user_postcode' );
		}else{
	        $user_address = WC()->session->get( '_user_deli_adress' );
	    }
        if($user_address!=''){
            $url = add_query_arg(array('change-address' => 1), $cr_url);?>
            <div class="exwf-user-dl-info">
                <span class="adrl-title"><?php esc_html_e('Delivery to: ','woocommerce-food'); ?></span>
                <span class="adrl-info"><?php echo $user_address;?></span>
                <span class="adrl-link"><a href="<?php echo esc_url($url);?>"><?php esc_html_e(' Change it ?','woocommerce-food'); ?></a></span>
            </div>
        <?php }
    }
}
// Verify address checkout
add_action('woocommerce_checkout_process', 'exwf_verify_address_deli_field');

function exwf_verify_address_deli_field() {
	$user_odmethod = WC()->session->get( '_user_order_method' );
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	if($user_odmethod=='takeaway' || $user_odmethod=='dinein' || $ship_mode=='postcode'){
		return;
	}
	$check_ex = exwf_if_check_product_notin_shipping();
	if($check_ex == false){
		return;
	}
	$loc_sl = isset($_POST['exwoofood_ck_loca']) ? $_POST['exwoofood_ck_loca'] : '';
	$to = isset($_POST['billing_address_1']) ? $_POST['billing_address_1'] : '';
	if(isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address']=='1'){
		$to = isset($_POST['shipping_address_1']) ? $_POST['shipping_address_1'] : $to;
	}
	$user_address = WC()->session->get( '_user_deli_adress' );
	if($user_address!= $to){
		if(isset($_POST['ship_to_different_address']) && $_POST['ship_to_different_address']=='1'){
			$to = $to.' '.$_POST['shipping_city'] .' '.exwf_get_country_code($_POST['shipping_country']);
		}else{
			$to = $to.' '.$_POST['billing_city'] .' '.exwf_get_country_code($_POST['billing_country']);
		}
	}
	$diskm ='';
	if($loc_sl!=''){
		$term = get_term_by('slug', $loc_sl, 'exwoofood_loc');
		if(isset($term->term_id)){
			$addres_log = get_term_meta( $term->term_id, 'exwp_loc_address', true );
			if($addres_log !=''){
				$from = $addres_log;
			}
			$addres_km = get_term_meta( $term->term_id, 'exwp_loc_diskm', true );
			if($addres_km !=''){
				$diskm = $addres_km;
			}
		}
	}
	$output = exwf_get_distance($from,$to,$diskm);
	if($output['mes']!=''){
		wc_add_notice( $output['mes'], 'error' );
	}
}
// add shipping fee
add_action( 'woocommerce_cart_calculate_fees','exwd_add_shipping_fee' );
function exwd_add_shipping_fee() {
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ){
		return;	
	}//print_r($_POST);exit;
	$check_ex = exwf_if_check_product_notin_shipping();
	if($check_ex == false){
		return;
	}
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway' || $user_odmethod=='dinein'){
		return;
	}
	$fee = exwoofood_get_option('exwoofood_ship_fee','exwoofood_shpping_options');
	$free_shipping = exwoofood_get_option('exwoofood_ship_free','exwoofood_shpping_options');
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	
  	global $woocommerce;
	// min by log
	$loc_selected = exwf_get_loc_selected();
	if($loc_selected!=''){
		$free_shipping_log = get_term_meta( $loc_selected, 'exwp_loc_ship_free', true );
		$free_shipping = $free_shipping_log !='' && is_numeric($free_shipping_log) ? $free_shipping_log : $free_shipping;
		$fee_log = get_term_meta( $loc_selected, 'exwp_loc_ship_fee', true );
		$fee = $fee_log !='' && is_numeric($fee_log) ? $fee_log : $fee;
	}
	if($ship_mode!='postcode'){
		$user_distance = WC()->session->get( '_user_distance' );
		if(is_numeric($user_distance) && $user_distance > 0){
			$adv_fee = exwoofood_get_option('exwfood_adv_feekm','exwoofood_shpping_options');
			if(is_array($adv_fee) && !empty($adv_fee)){
				usort($adv_fee, function($a, $b) { // anonymous function
					return $a['km'] - $b['km'];
				});
				foreach ($adv_fee as $key => $item) {
					if( $user_distance <= $item['km']){
						$fee = isset($item['fee']) && is_numeric($item['fee']) ? $item['fee'] : '';
						$free_shipping = isset($item['free']) && is_numeric($item['free']) ? $item['free'] : $free_shipping;
						break;
					}
				}
			}
		}
	}else{
		$adv_fee = exwoofood_get_option('exwfood_adv_feepos','exwoofood_shpping_options');
		$pcode = WC()->session->get( '_user_postcode' );
		$user_postc = isset($_POST['postcode']) ? $_POST['postcode'] : '';
		$user_postc = isset($_POST['s_postcode']) && $_POST['s_postcode'] !='' ? $_POST['s_postcode'] : $user_postc;
		if($user_postc==''){$user_postc = $pcode;}
		if($user_postc!= '' && is_array($adv_fee) && !empty($adv_fee)){
			foreach ($adv_fee as $key => $item) {
				if( $user_postc == $item['postcode']){
					WC()->session->set( '_user_postcode', $user_postc);
					$fee = isset($item['fee']) && is_numeric($item['fee']) ? $item['fee'] : '';
					$free_shipping = isset($item['free']) && is_numeric($item['free']) ? $item['free'] : $free_shipping;
					break;
				}
			}
		}
	}
	$fee = apply_filters('exwf_shipping_fee_amount',$fee);
	if($fee!='' && is_numeric($fee)){
		$total = apply_filters( 'exwf_total_cart_price_fee', WC()->cart->get_subtotal() );
		if($free_shipping!='' && is_numeric($free_shipping) && $total>=$free_shipping){
			$fee = 0;
		}
		$tax_fee = apply_filters('exwf_shipping_fee_tax',true);
		$woocommerce->cart->add_fee( esc_html__('Shipping fee','woocommerce-food'), $fee, $tax_fee, '' );
	}
}
add_filter( 'exwf_minimum_amount_required','exwd_change_mini_mum_by_km' );
function exwd_change_mini_mum_by_km($minimum) {
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway' || $user_odmethod=='dinein'){
		return $minimum;
	}
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	if($ship_mode!='postcode'){
		$user_distance = WC()->session->get( '_user_distance' );
		if(is_numeric($user_distance) && $user_distance > 0){
			$adv_fee = exwoofood_get_option('exwfood_adv_feekm','exwoofood_shpping_options');
			if(is_array($adv_fee) && !empty($adv_fee)){
				usort($adv_fee, function($a, $b) { // anonymous function
					return $a['km'] - $b['km'];
				});
				foreach ($adv_fee as $key => $item) {
					if( $user_distance <= $item['km']){
						$minimum = isset($item['min_amount']) && is_numeric($item['min_amount']) ? $item['min_amount'] : $minimum;
						break;
					}
				}
			}
		}
	}else{
		$adv_fee = exwoofood_get_option('exwfood_adv_feepos','exwoofood_shpping_options');
		$pcode = WC()->session->get( '_user_postcode' );
		$user_postc = isset($_POST['postcode']) ? $_POST['postcode'] : '';
		$user_postc = isset($_POST['s_postcode']) && $_POST['s_postcode'] !='' ? $_POST['s_postcode'] : $user_postc;
		if($user_postc==''){$user_postc = $pcode;}
		if($user_postc!= '' && is_array($adv_fee) && !empty($adv_fee)){
			foreach ($adv_fee as $key => $item) {
				if( $user_postc == $item['postcode']){
					$minimum = isset($item['min_amount']) && is_numeric($item['min_amount']) ? $item['min_amount'] : $minimum;
					break;
				}
			}
		}
	}
	return $minimum;
}
add_filter( 'exwf_minimum_amount_required','exwd_change_mini_mum_order_method' );
function exwd_change_mini_mum_order_method($minimum) {
	$user_odmethod = WC()->session->get( '_user_order_method' );
	$minimum_mt = '';
	if($user_odmethod=='takeaway'){
		$minimum_mt = exwoofood_get_option('exwoofood_ck_mini_amount','exwoofood_adv_takeaway_options');
	}else if($user_odmethod=='dinein'){
		$minimum_mt = exwoofood_get_option('exwoofood_ck_mini_amount','exwoofood_adv_dinein_options');
	}
	
	return $minimum_mt!='' ? $minimum_mt : $minimum;
}
// display shipping free
add_action( 'woocommerce_widget_shopping_cart_before_buttons' , 'exwf_minimum_amount_free_deli_sidecart',999 );
function exwf_minimum_amount_free_deli_sidecart($return = false){
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway' || $user_odmethod=='dinein'){	
		return;
	}
	$free_shipping = exwoofood_get_option('exwoofood_ship_free','exwoofood_shpping_options');
	$fee = exwoofood_get_option('exwoofood_ship_fee','exwoofood_shpping_options');
	$total = apply_filters( 'exwf_total_cart_price_fee', WC()->cart->get_subtotal() );
	// min by log
	$loc_selected = exwf_get_loc_selected();
	if($loc_selected!=''){
		$free_shipping_log = get_term_meta( $loc_selected, 'exwp_loc_ship_free', true );
		$free_shipping = $free_shipping_log !='' && is_numeric($free_shipping_log) ? $free_shipping_log : $free_shipping;
		$fee_log = get_term_meta( $loc_selected, 'exwp_loc_ship_fee', true );
		$fee = $fee_log !='' && is_numeric($fee_log) ? $fee_log : $fee;
	}
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	if($ship_mode!='postcode'){
		$user_distance = WC()->session->get( '_user_distance' );
		if(is_numeric($user_distance) && $user_distance > 0){
			$adv_fee = exwoofood_get_option('exwfood_adv_feekm','exwoofood_shpping_options');
			if(is_array($adv_fee) && !empty($adv_fee)){
				usort($adv_fee, function($a, $b) { // anonymous function
					return $a['km'] - $b['km'];
				});//print_r($adv_fee);
				foreach ($adv_fee as $key => $item) {
					if( $user_distance <= $item['km']){
						$fee = isset($item['fee']) ? $item['fee'] : '';
						$free_shipping = isset($item['free']) && is_numeric($item['free']) ? $item['free'] : $free_shipping;
						break;
					}
				}
				if($fee== 0){$free_shipping=0;}
			}
		}
	}else{
		$adv_fee = exwoofood_get_option('exwfood_adv_feepos','exwoofood_shpping_options');
		$pcode = WC()->session->get( '_user_postcode' );
		$user_postc = isset($_POST['postcode']) ? $_POST['postcode'] : '';
		$user_postc = isset($_POST['s_postcode']) && $_POST['s_postcode'] !='' ? $_POST['s_postcode'] : $user_postc;
		if($user_postc==''){$user_postc = $pcode;}
		if($user_postc!= '' && is_array($adv_fee) && !empty($adv_fee)){
			foreach ($adv_fee as $key => $item) {
				if( $user_postc == $item['postcode']){
					WC()->session->set( '_user_postcode', $user_postc);
					$fee = isset($item['fee']) && is_numeric($item['fee']) ? $item['fee'] : '';
					$free_shipping = isset($item['free']) && is_numeric($item['free']) ? $item['free'] : $free_shipping;
					break;
				}
			}
			if($fee== 0){$free_shipping=0;}
		}
	}
	$html ='';
    if ( $fee!='' && $free_shipping!='' && is_numeric($fee) && is_numeric($free_shipping) && $total < $free_shipping ) {
    	$nbom_displ = apply_filters('exwf_free_ship_value_message',wc_price( $free_shipping - $total ),$free_shipping);
    	$html = sprintf( esc_html__('Order %s amount more to get free delivery','woocommerce-food' ) , $nbom_displ);
    }
    if($html!='' && isset($return) && $return==true){
    	return $html;
    }else if($html!=''){
    	echo '<p class="exwf-mini-amount exwf-min-free-ship exwf-warning">'.$html.'</p>';
    }
}
// if change loc
add_action( 'wp_ajax_exwf_update_shipping_fee', 'ajax_exwf_update_shipping_fee' );
add_action( 'wp_ajax_nopriv_exwf_update_shipping_fee', 'ajax_exwf_update_shipping_fee' );
function ajax_exwf_update_shipping_fee(){
	$loc = $_POST['loc'];
	WC()->session->set( '_user_deli_log' , $loc);
	ajax_exwf_update_shipping_fee_bykm();
}

add_action( 'wp_ajax_exwf_update_shipping_fee_bykm', 'ajax_exwf_update_shipping_fee_bykm' );
add_action( 'wp_ajax_nopriv_exwf_update_shipping_fee_bykm', 'ajax_exwf_update_shipping_fee_bykm' );
function ajax_exwf_update_shipping_fee_bykm(){
	$adv_fee = exwoofood_get_option('exwfood_adv_feekm','exwoofood_shpping_options');
	if(!is_array($adv_fee) || empty($adv_fee)){
		echo str_replace('\/', '/', json_encode(array('result'=>'unc')));die;
	}
	$address = $_POST['address'];
	$city = $_POST['city'];
	$log = $_POST['loc'];
	$country = $_POST['country'];
	$to = $address.' '.$city .' '.exwf_get_country_code($country);
	$from = '';
	if($log!=''){
		$term = get_term_by('slug', $log, 'exwoofood_loc');
		if(isset($term->term_id)){
			$addres_log = get_term_meta( $term->term_id, 'exwp_loc_address', true );
			if($addres_log !=''){
				$from = $addres_log;
			}
		}
	}
	$output = exwf_get_distance($from,$to,'');
	echo str_replace('\/', '/', json_encode($output));
	die;
}


add_action( 'woocommerce_before_cart' , 'exwf_minimum_amount_fee_deli' );
function exwf_minimum_amount_fee_deli() {
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway'){	
		return;
	}
	$prnotice = exwf_minimum_amount_free_deli_sidecart(true);
	if($prnotice!=''){
		wc_print_notice($prnotice, 'error');
	}
}
/**
 * Save method value
 */
add_action( 'woocommerce_checkout_update_order_meta', 'exwf_save_order_method_field' );

function exwf_save_order_method_field( $order_id ) {
	$user_odmethod = WC()->session->get( '_user_order_method' );
	update_post_meta( $order_id, 'exwfood_order_method', $user_odmethod );
}
//
add_filter( 'woocommerce_default_address_fields' , 'exwf_unrequired_address_field' );
function exwf_unrequired_address_field( $address_fields ) {
	if(is_admin()&& !defined( 'DOING_AJAX' ) || !isset(WC()->session) ){ return $address_fields;}
    $user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway'){	
	    $address_fields['address_1']['required'] = false;
	}

     return $address_fields;
}

add_filter( 'default_checkout_billing_country', 'exwf_change_default_checkout_country' );
//add_filter( 'default_checkout_billing_state', 'exwf_change_default_checkout_state' );
add_filter( 'default_checkout_billing_city', 'exwf_change_default_checkout_city' );
add_filter( 'default_checkout_billing_address_1', 'exwf_change_default_checkout_address_1' );
add_filter( 'default_checkout_billing_postcode', 'exwf_change_default_checkout_billing_postcode' );
function exwf_change_default_checkout_country($contr) {
	if(is_admin()&& !defined( 'DOING_AJAX' ) || !isset(WC()->session) ){ return $contr;}
	$user_address = WC()->session->get( '_user_deli_adress' );
	$user_details_address = WC()->session->get( '_user_deli_adress_details' );//print_r($user_details_address);exit;
	if($user_details_address!='' && is_array($user_details_address)){
		foreach($user_details_address as $address_component){
		    if(in_array('country', $address_component->types)){
		        $country = $address_component->short_name;
		        return $country;
		        break;
		    } 
		}
	}
	if($user_address!=''){
		$user_address = explode(",",$user_address);
		$contr = end($user_address);
		$contr = exwf_get_country_code(trim($contr));
	}
	return $contr; // country code
}

function exwf_change_default_checkout_state() {
  return 'US'; // state code
}

function exwf_change_default_checkout_city($city) {
	if(is_admin()&& !defined( 'DOING_AJAX' ) || !isset(WC()->session) ){ return $city;}
	$user_address = WC()->session->get( '_user_deli_adress' );
	$user_details_address = WC()->session->get( '_user_deli_adress_details' );//print_r($user_details_address);exit;
	if($user_details_address!='' && is_array($user_details_address)){
		foreach($user_details_address as $address_component){
		    if(in_array('locality', $address_component->types)){
		        $name = $address_component->long_name;
		        return $name;
		        break;
		    } 
		}
	}
	if($user_address!=''){
		$user_address = explode(",",$user_address);
		$count_ar = count($user_address);
		if($count_ar > 1){//print_r($user_address);exit;
			$city = $user_address[$count_ar-2];
		}
	}
	return $city; // state code
}

function exwf_change_default_checkout_address_1($street) {
	if(is_admin()&& !defined( 'DOING_AJAX' ) || !isset(WC()->session) ){ return $street;}
	$user_address = WC()->session->get( '_user_deli_adress' );
	$user_details_address = WC()->session->get( '_user_deli_adress_details' );
	if($user_details_address!='' && is_array($user_details_address)){
		$name = $stre = $route = $neig = '';
		foreach($user_details_address as $address_component){
		    if(in_array('street_number', $address_component->types)){
		        $stre = $address_component->long_name;
		    }else if(in_array('route', $address_component->types)){
		        $route = $address_component->long_name;
		    }else if(in_array('neighborhood', $address_component->types)){
		        $neig = $address_component->long_name;
		    }
		}
		$pos = $user_address!='' && $stre!='' ? strpos($user_address,$stre) : 0;
		if($pos > 0){
			$name = $route.' '.$stre.' '.$neig;
		}else{
			$name = $stre.' '.$route.' '.$neig;  
		}
		return $name;
	}
	if($user_address!=''){
		$user_address = explode(",",$user_address);
		$count_ar = count($user_address);
		if($count_ar > 3){
			$street = '';
			for($i= 0; $i < ($count_ar-2);$i ++ ){
				$street .= ' '.$user_address[$i];
			}
		}
	}
	return $street; // state code
}

function exwf_change_default_checkout_billing_postcode($code) {
	if(is_admin()&& !defined( 'DOING_AJAX' ) || !isset(WC()->session) ){ return $code;}
	$ship_mode = exwoofood_get_option('exwoofood_ship_mode','exwoofood_shpping_options');
	if($ship_mode=='postcode'){
		$code = WC()->session->get( '_user_postcode' );
		return $code;
	}
	$user_details_address = WC()->session->get( '_user_deli_adress_details' );//print_r($user_details_address);exit;
	if($user_details_address!='' && is_array($user_details_address)){
		foreach($user_details_address as $address_component){
		    if(in_array('postal_code', $address_component->types)){
		        $name = $address_component->long_name;
		        return $name;
		        break;
		    }
		    if(isset($address_component->types) && $address_component->types[0] == 'postal_code'){
	            $name = $address_component->long_name;
	            return $name;
		        break;          
	        }
		}
	}
	return $code; // state code
}

function exwf_get_country_code($name){
	$countrycodes = array (
		'AF' => 'Afghanistan','AX' => 'Åland Islands', 'AL' => 'Albania', 'DZ' => 'Algeria', 'AS' => 'American Samoa', 'AD' => 'Andorra', 'AO' => 'Angola', 'AI' => 'Anguilla', 'AQ' => 'Antarctica', 'AG' => 'Antigua and Barbuda', 'AR' => 'Argentina', 'AU' => 'Australia', 'AT' => 'Austria', 'AZ' => 'Azerbaijan', 'BS' => 'Bahamas', 'BH' => 'Bahrain', 'BD' => 'Bangladesh', 'BB' => 'Barbados', 'BY' => 'Belarus', 'BE' => 'Belgium', 'BZ' => 'Belize', 'BJ' => 'Benin', 'BM' => 'Bermuda', 'BT' => 'Bhutan', 'BO' => 'Bolivia', 'BA' => 'Bosnia and Herzegovina', 'BW' => 'Botswana', 'BV' => 'Bouvet Island', 'BR' => 'Brazil', 'IO' => 'British Indian Ocean Territory', 'BN' => 'Brunei Darussalam', 'BG' => 'Bulgaria', 'BF' => 'Burkina Faso', 'BI' => 'Burundi', 'KH' => 'Cambodia', 'CM' => 'Cameroon', 'CA' => 'Canada', 'CV' => 'Cape Verde', 'KY' => 'Cayman Islands', 'CF' => 'Central African Republic', 'TD' => 'Chad', 'CL' => 'Chile', 'CN' => 'China', 'CX' => 'Christmas Island', 'CC' => 'Cocos (Keeling) Islands', 'CO' => 'Colombia', 'KM' => 'Comoros', 'CG' => 'Congo', 'CD' => 'Zaire', 'CK' => 'Cook Islands', 'CR' => 'Costa Rica', 'CI' => 'Côte D\'Ivoire', 'HR' => 'Croatia', 'CU' => 'Cuba', 'CY' => 'Cyprus', 'CZ' => 'Czech Republic', 'DK' => 'Denmark', 'DJ' => 'Djibouti', 'DM' => 'Dominica', 'DO' => 'Dominican Republic', 'EC' => 'Ecuador', 'EG' => 'Egypt', 'SV' => 'El Salvador', 'GQ' => 'Equatorial Guinea', 'ER' => 'Eritrea', 'EE' => 'Estonia', 'ET' => 'Ethiopia', 'FK' => 'Falkland Islands (Malvinas)', 'FO' => 'Faroe Islands', 'FJ' => 'Fiji', 'FI' => 'Finland', 'FR' => 'France', 'GF' => 'French Guiana', 'PF' => 'French Polynesia', 'TF' => 'French Southern Territories', 'GA' => 'Gabon', 'GM' => 'Gambia', 'GE' => 'Georgia', 'DE' => 'Germany', 'GH' => 'Ghana', 'GI' => 'Gibraltar', 'GR' => 'Greece', 'GL' => 'Greenland', 'GD' => 'Grenada', 'GP' => 'Guadeloupe', 'GU' => 'Guam', 'GT' => 'Guatemala', 'GG' => 'Guernsey', 'GN' => 'Guinea', 'GW' => 'Guinea-Bissau', 'GY' => 'Guyana', 'HT' => 'Haiti', 'HM' => 'Heard Island and Mcdonald Islands', 'VA' => 'Vatican City State', 'HN' => 'Honduras', 'HK' => 'Hong Kong', 'HU' => 'Hungary', 'IS' => 'Iceland', 'IN' => 'India', 'ID' => 'Indonesia', 'IR' => 'Iran, Islamic Republic of', 'IQ' => 'Iraq', 'IE' => 'Ireland', 'IM' => 'Isle of Man', 'IL' => 'Israel', 'IT' => 'Italy', 'JM' => 'Jamaica', 'JP' => 'Japan', 'JE' => 'Jersey', 'JO' => 'Jordan', 'KZ' => 'Kazakhstan', 'KE' => 'KENYA', 'KI' => 'Kiribati', 'KP' => 'Korea, Democratic People\'s Republic of', 'KR' => 'Korea, Republic of', 'KW' => 'Kuwait', 'KG' => 'Kyrgyzstan', 'LA' => 'Lao People\'s Democratic Republic', 'LV' => 'Latvia', 'LB' => 'Lebanon', 'LS' => 'Lesotho', 'LR' => 'Liberia', 'LY' => 'Libyan Arab Jamahiriya', 'LI' => 'Liechtenstein', 'LT' => 'Lithuania', 'LU' => 'Luxembourg', 'MO' => 'Macao', 'MK' => 'Macedonia, the Former Yugoslav Republic of', 'MG' => 'Madagascar', 'MW' => 'Malawi', 'MY' => 'Malaysia', 'MV' => 'Maldives', 'ML' => 'Mali', 'MT' => 'Malta', 'MH' => 'Marshall Islands', 'MQ' => 'Martinique', 'MR' => 'Mauritania', 'MU' => 'Mauritius', 'YT' => 'Mayotte', 'MX' => 'Mexico', 'FM' => 'Micronesia, Federated States of', 'MD' => 'Moldova, Republic of', 'MC' => 'Monaco', 'MN' => 'Mongolia', 'ME' => 'Montenegro', 'MS' => 'Montserrat', 'MA' => 'Morocco', 'MZ' => 'Mozambique', 'MM' => 'Myanmar', 'NA' => 'Namibia', 'NR' => 'Nauru', 'NP' => 'Nepal', 'NL' => 'Netherlands', 'AN' => 'Netherlands Antilles', 'NC' => 'New Caledonia', 'NZ' => 'New Zealand', 'NI' => 'Nicaragua', 'NE' => 'Niger', 'NG' => 'Nigeria', 'NU' => 'Niue', 'NF' => 'Norfolk Island', 'MP' => 'Northern Mariana Islands', 'NO' => 'Norway', 'OM' => 'Oman', 'PK' => 'Pakistan', 'PW' => 'Palau', 'PS' => 'Palestinian Territory, Occupied', 'PA' => 'Panama', 'PG' => 'Papua New Guinea', 'PY' => 'Paraguay', 'PE' => 'Peru', 'PH' => 'Philippines', 'PN' => 'Pitcairn', 'PL' => 'Poland', 'PT' => 'Portugal', 'PR' => 'Puerto Rico', 'QA' => 'Qatar', 'RE' => 'Réunion', 'RO' => 'Romania', 'RU' => 'Russian Federation', 'RW' => 'Rwanda', 'SH' => 'Saint Helena', 'KN' => 'Saint Kitts and Nevis', 'LC' => 'Saint Lucia', 'PM' => 'Saint Pierre and Miquelon', 'VC' => 'Saint Vincent and the Grenadines', 'WS' => 'Samoa', 'SM' => 'San Marino', 'ST' => 'Sao Tome and Principe', 'SA' => 'Saudi Arabia', 'SN' => 'Senegal', 'RS' => 'Serbia', 'SC' => 'Seychelles', 'SL' => 'Sierra Leone', 'SG' => 'Singapore', 'SK' => 'Slovakia', 'SI' => 'Slovenia', 'SB' => 'Solomon Islands', 'SO' => 'Somalia', 'ZA' => 'South Africa', 'GS' => 'South Georgia and the South Sandwich Islands', 'ES' => 'Spain', 'LK' => 'Sri Lanka', 'SD' => 'Sudan', 'SR' => 'Suriname', 'SJ' => 'Svalbard and Jan Mayen', 'SZ' => 'Swaziland', 'SE' => 'Sweden', 'CH' => 'Switzerland', 'SY' => 'Syrian Arab Republic', 'TW' => 'Taiwan, Province of China', 'TJ' => 'Tajikistan', 'TZ' => 'Tanzania, United Republic of', 'TH' => 'Thailand', 'TL' => 'Timor-Leste', 'TG' => 'Togo', 'TK' => 'Tokelau', 'TO' => 'Tonga', 'TT' => 'Trinidad and Tobago', 'TN' => 'Tunisia', 'TR' => 'Turkey', 'TM' => 'Turkmenistan', 'TC' => 'Turks and Caicos Islands', 'TV' => 'Tuvalu', 'UG' => 'Uganda', 'UA' => 'Ukraine', 'AE' => 'United Arab Emirates', 'GB' => 'United Kingdom', 'US' => 'United States', 'UM' => 'United States Minor Outlying Islands', 'UY' => 'Uruguay', 'UZ' => 'Uzbekistan', 'VU' => 'Vanuatu', 'VE' => 'Venezuela', 'VN' => 'Vietnam', 'VG' => 'Virgin Islands, British', 'VI' => 'Virgin Islands, U.S.', 'WF' => 'Wallis and Futuna', 'EH' => 'Western Sahara', 'YE' => 'Yemen', 'ZM' => 'Zambia', 'ZW' => 'Zimbabwe',
	);
	$code = array_search($name, $countrycodes);
	return $code;
}
// Example change day of takeaway
//add_filter( 'option_exwoofood_advanced_options', 'exwf_change_disable_takeaway_day', 88, 1 );
function exwf_change_disable_takeaway_day($default){
	if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $default;}
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway'){	
		$default['exwoofood_ck_disday'] = '';
		$default['exwoofood_ck_disday'] = array(
			'0'=>'1',// Disable Monday
			'0'=>'2',// Disable Tuesday
			'0'=>'3',// Disable Wednesday
			'0'=>'4',// Disable Thursday
			'0'=>'5',// Disable Friday
			'0'=>'7',// Disable Sunday
		);
	}
	return $default;
}
if(!function_exists('exwf_hide_fields_takeaway')){
	//add_filter( 'woocommerce_checkout_fields' , 'exwf_hide_fields_takeaway' );
	function exwf_hide_fields_takeaway( $fields ) {
		if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $fields;}
		$user_odmethod = WC()->session->get( '_user_order_method' );
			if($user_odmethod=='takeaway'){
				unset($fields['billing']['billing_address_1']);
				unset($fields['billing']['billing_address_2']);
				unset($fields['billing']['billing_city']);
				unset($fields['billing']['billing_state']);
				unset($fields['billing']['billing_postcode']);
				unset($fields['billing']['billing_company']);
			}
		return $fields;
	}
}

if(!function_exists('exwf_hide_def_address_field_by_sp')){
	add_filter( 'woocommerce_checkout_fields' , 'exwf_hide_def_address_field_by_sp',99999 );
	function exwf_hide_def_address_field_by_sp( $fields ) {
		if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $fields;}
		$user_odmethod = WC()->session->get( '_user_order_method' );
		$disaddr = exwoofood_get_option('exwoofood_ck_disaddr','exwoofood_adv_takeaway_options');
		$disaddr_di = exwoofood_get_option('exwoofood_ck_disaddr','exwoofood_adv_dinein_options');
		if(($user_odmethod=='takeaway' && $disaddr=='yes') || ($user_odmethod=='dinein' && $disaddr_di=='yes')){
			unset($fields['billing']['billing_address_1']);
			unset($fields['billing']['billing_address_2']);
			unset($fields['billing']['billing_city']);
			unset($fields['billing']['billing_state']);
			unset($fields['billing']['billing_postcode']);
			unset($fields['billing']['billing_company']);
			unset($fields['billing']['billing_country']);
		}
		return $fields;
	}
}
// change setting for pickup
add_filter('exwf_get_option', 'exwf_change_settings_if_pickup',10,3);
function exwf_change_settings_if_pickup($val, $option_key, $key){
	if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $val;}
	$method = WC()->session->get( '_user_order_method' );
	if( $method =='takeaway'){
		if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_beforedate'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_beforedate','exwoofood_adv_takeaway_options');
			if($pickup_val!=''){ return $pickup_val;}
		}else if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_disdate'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_disdate','exwoofood_adv_takeaway_options');
			if(is_array($pickup_val) && !empty($pickup_val)){ return $pickup_val;}
		}else if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_enadate'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_enadate','exwoofood_adv_takeaway_options');
			if(is_array($pickup_val) && !empty($pickup_val)){ return $pickup_val;}
		}else if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_disday'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_disday','exwoofood_adv_takeaway_options');
			if(is_array($pickup_val) && !empty($pickup_val)){ return $pickup_val;}
		}
		
	}elseif( $method =='dinein'){
		if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_beforedate'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_beforedate','exwoofood_adv_dinein_options');
			if($pickup_val!=''){ return $pickup_val;}
		}else if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_disdate'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_disdate','exwoofood_adv_dinein_options');
			if(is_array($pickup_val) && !empty($pickup_val)){ return $pickup_val;}
		}else if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_enadate'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_enadate','exwoofood_adv_dinein_options');
			if(is_array($pickup_val) && !empty($pickup_val)){ return $pickup_val;}
		}else if($option_key=='exwoofood_advanced_options' && $key == 'exwoofood_ck_disday'){
			$pickup_val = exwoofood_get_option('exwoofood_ck_disday','exwoofood_adv_dinein_options');
			if(is_array($pickup_val) && !empty($pickup_val)){ return $pickup_val;}
		}
	}
	if($option_key=='exwoofood_shpping_options' && $key == 'exwfood_adv_feekm'){
		$loc_selected = exwf_get_loc_selected();
		if($loc_selected!=''){
			$loc_shipkm = exwoofood_get_option('exwfood_km_loc','exwoofood_shpping_options');
			if($loc_shipkm=='yes'){
				$fee_lockm = get_term_meta( $loc_selected, 'exwfood_adv_feekm', true );
				if(is_array($fee_lockm) && !empty($fee_lockm)){
					$val = $fee_lockm;
				}
			}
		}
	}else if($option_key=='exwoofood_shpping_options' && $key == 'exwoofood_ship_postcodes'){
		$loc_selected = exwf_get_loc_selected();
		if($loc_selected!=''){
			$postcodes_loc = get_term_meta( $loc_selected, 'exwp_loc_ship_postcodes', true );
			if($postcodes_loc!=''){
				$val = $postcodes_loc;
			}
		}
	}

	return $val;
}
// remove shipping option when takeaway or dinein
if(!function_exists('exwf_hide_df_shipping_when_takeaway_dinein')){
	add_filter( 'woocommerce_product_needs_shipping', 'exwf_hide_df_shipping_when_takeaway_dinein', 10, 2 );
	function exwf_hide_df_shipping_when_takeaway_dinein( $return, $data ) {
		if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $return;}
		
		$check_ex = exwf_if_check_product_in_shipping();
		if($check_ex == true){
			return $return;
		}

		$user_odmethod = WC()->session->get( '_user_order_method' );
		if($user_odmethod=='takeaway' || $user_odmethod=='dinein'){
			return false;
		}
		return $return;
	}
	//add_filter( 'woocommerce_cart_needs_shipping', 'exwf_disable_df_shipping_when_takeaway_dinein', 10 );
	function exwf_disable_df_shipping_when_takeaway_dinein( $return ) {
		if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $return;}
		$check_ex = exwf_if_check_product_in_shipping();
		if($check_ex == true){
			return $return;
		}
		$user_odmethod = WC()->session->get( '_user_order_method' );
		if($user_odmethod=='takeaway' || $user_odmethod=='dinein'){
			return false;
		}
		return $return;
	}
}

add_filter('exwf_shipping_fee_amount','exwf_fee_by_timeslot');
function exwf_fee_by_timeslot($fee){
	//print_r($_POST);
	if(isset($_POST['post_data']) && $_POST['post_data']!=''){
		parse_str($_POST['post_data'], $data);
		$timeslot = isset($data['exwfood_time_deli']) && $data['exwfood_time_deli']!='' ? $data['exwfood_time_deli'] : '';
		if($timeslot!=''){
			$_timeck = array();
			// advanced slots
			$rq_date = exwoofood_get_option('exwoofood_ck_date','exwoofood_advanced_options');
			$adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
			$_ftimesl = '';
			$user_log = isset($data['exwoofood_ck_loca'])  ? $data['exwoofood_ck_loca'] : '';
			$date_deli = isset($data['exwfood_date_deli'])  ? $data['exwfood_date_deli'] : '';
			$_date_type = exwoofood_get_option('exwoofood_dd_display','exwoofood_advanced_options');
		    $foodby_date = exwoofood_get_option('exwoofood_foodby_date');
			if($_date_type =='picker' && $date_deli !='' && $foodby_date!='yes'){
				$date_deli = strtotime($date_deli);
			}
			if($rq_date=='disable'){
				$date_deli = strtotime(date("Y-m-d"));
			}
			$method = 'delivery'; 
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
		    if(is_array($n_dl_time) && !empty($n_dl_time)){
			    foreach ($n_dl_time as $time_option) {
					$r_time ='';
					if(isset($time_option['start-time']) && $time_option['start-time']!='' && isset($time_option['end-time']) && $time_option['end-time']!=''){
						$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
					}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
						$r_time = $time_option['start-time'];
					}
					$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
					if($timeslot==$name){
						$_time_base = apply_filters('exwf_timebase_to_check_delivery',(isset($time_option['start-time']) ? $time_option['start-time'] : ''),$time_option);
						$_timeck = $time_option;
						break;
					}
				}
			}
			if(!empty($_timeck) && isset($_timeck['ship-fee']) && $_timeck >=0){
				return $_timeck['ship-fee'];
			}

		}
	}
	return $fee;
}

add_filter( 'exwf_location_args', 'exwf_location_by_method', 10 );
function exwf_location_by_method( $args ) {
	if(!isset(WC()->session) || is_admin()&& !defined( 'DOING_AJAX' ) ){ return $args;}
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod=='takeaway'){
		$exclude =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_adv_takeaway_options');
	}else if($user_odmethod=='dinein'){
		$exclude =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_adv_dinein_options');
	}else{
		$exclude =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_advanced_options');
	}
	$ids_to_exclude = array();
	if(is_array($exclude) && !empty($exclude)){
		$get_terms_to_exclude =  get_terms('exwoofood_loc',array('fields'  => 'ids','slug'=> $exclude, 'hide_empty'=> false));
		if( !is_wp_error( $get_terms_to_exclude ) && count($get_terms_to_exclude) > 0){
		    $ids_to_exclude = $get_terms_to_exclude; 
		}
	}
	$args['exclude'] = $ids_to_exclude;
	return $args;
}
add_action('woocommerce_checkout_process', 'exwf_verify_location_by_order_mt');
function exwf_verify_location_by_order_mt() {
	$user_odmethod = WC()->session->get( '_user_order_method' );
	$user_odmethod = $user_odmethod !='' ? $user_odmethod : 'delivery';
	if($user_odmethod=='takeaway'){
		$exclude =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_adv_takeaway_options');
	}else if($user_odmethod=='dinein'){
		$exclude =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_adv_dinein_options');
	}else{
		$exclude =  exwoofood_get_option('exwoofood_adv_dislog','exwoofood_advanced_options');
	}
	if(is_array($exclude) && !empty($exclude)){
		$user_log = WC()->session->get( '_user_deli_log' );
		if(isset($_POST['exwoofood_ck_loca'])){
			$user_log = $_POST['exwoofood_ck_loca'];
		}
		if(in_array($user_log, $exclude)){
			$user_odmethod = $user_odmethod=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $user_odmethod=='dinein' ? esc_html__('Dine-in','woocommerce-food') : esc_html__('Delivery','woocommerce-food'));
			wc_add_notice( sprintf(esc_html__( 'Sorry the location you have selected is not available for %s please try with other order methods','woocommerce-food'  ),$user_odmethod), 'error' );
		}
	}
}