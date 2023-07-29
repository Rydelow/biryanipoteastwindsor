<?php
include 'class-food-taxonomy.php';
include 'shortcode-builder.php';

add_action( 'admin_enqueue_scripts', 'exwoofood_admin_scripts' );
function exwoofood_admin_scripts(){
	$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'jquery', 'exwoofood_ajax', $js_params  );
	wp_enqueue_style('ex-woo-food', EX_WOOFOOD_PATH . 'admin/css/style.css','','2.0');
	wp_enqueue_script('ex-woo-food', EX_WOOFOOD_PATH . 'admin/js/admin.min.js', array( 'jquery' ),'2.0' );
}



add_filter( 'manage_exwoofood_scbd_posts_columns', 'exwoofood_edit_scbd_columns',99 );
function exwoofood_edit_scbd_columns( $columns ) {
	unset($columns['date']);
	$columns['layout'] = esc_html__( 'Type' , 'woocommerce-food' );
	$columns['shortcode'] = esc_html__( 'Shortcode' , 'woocommerce-food' );
	$columns['date'] = esc_html__( 'Publish date' , 'woocommerce-food' );		
	return $columns;
}
add_action( 'manage_exwoofood_scbd_posts_custom_column', 'exwoofood_scbd_custom_columns',12);
function exwoofood_scbd_custom_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'layout':
			$sc_type = get_post_meta($post->ID, 'sc_type', true);
			$exwoofood_id = $post->ID;
			echo '<span class="layout">'.wp_kses_post($sc_type).'</span>';
			break;
		case 'shortcode':
			$_shortcode = get_post_meta($post->ID, '_shortcode', true);
			echo '<input type="text" readonly name="_shortcode" value="'.esc_attr($_shortcode).'">';
			break;	
	}
}

function exwoofood_id_taxonomy_columns( $columns ){
	$columns['cat_id'] = esc_html__('ID','woocommerce-food');

	return $columns;
}
add_filter('manage_edit-product_cat_columns' , 'exwoofood_id_taxonomy_columns');
function exwoofood_taxonomy_columns_content( $content, $column_name, $term_id ){
    if ( 'cat_id' == $column_name ) {
        $content = $term_id;
    }
	return $content;
}
add_filter( 'manage_product_cat_custom_column', 'exwoofood_taxonomy_columns_content', 10, 3 );

add_action('wp_ajax_exfd_change_order_menu', 'wp_ajax_exfd_change_order_menu' );
function wp_ajax_exfd_change_order_menu(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if ($value == '') {
		$value = 0;
	}
	if(isset($post_id) && $post_id != 0)
	{
		update_term_meta($post_id, 'exwoofood_menu_order', esc_attr($value));
	}
	die;
}
// Order column
add_filter( 'manage_product_posts_columns', 'exwf_edit_columns',99 );
function exwf_edit_columns( $columns ) {
	$columns['exwoofood_order'] = esc_html__( 'CT Order' , 'woocommerce-food' );	
	return $columns;
}
add_action( 'manage_product_posts_custom_column', 'exwf_custom_columns',12);
function exwf_custom_columns( $column ) {
	global $post;
	switch ( $column ) {	
		case 'exwoofood_order':
			$exwf_order = get_post_meta($post->ID, 'exwoofood_order', true);
			echo '<input type="number" style="max-width:50px" data-id="' . $post->ID . '" name="exwoofood_order" value="'.esc_attr($exwf_order).'">';
			break;
	}
}

add_action( 'wp_ajax_exwoofood_change_sort_food', 'exwf_change_sort' );
function exwf_change_sort(){
	$post_id = $_POST['post_id'];
	$value = $_POST['value'];
	if(isset($post_id) && $post_id != 0)
	{
		update_post_meta($post_id, 'exwoofood_order', esc_attr(str_replace(' ', '', $value)));
	}
	die;
}
// upgrade data of delivery time from 1.1.2 to 1.2
add_action( 'init', 'exwf_update_option' );
if(!function_exists('exwf_update_option')){
	function exwf_update_option() {
		if(is_user_logged_in() && current_user_can( 'manage_options' ) && isset($_GET['page']) && $_GET['page']=='exwoofood_verify_options' && isset($_GET['delete_license']) && $_GET['delete_license']=='yes' ){
			$_name = exwoofood_get_option('exwoofood_evt_name','exwoofood_verify_options');
			$_pcode = exwoofood_get_option('exwoofood_evt_pcode','exwoofood_verify_options');
			$site = get_site_url();
			$url = 'https://exthemes.net/verify-purchase-code/';
			$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'25457330', 'site' => $site, 'delete'=>'yes');
			$options = array(
			        'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'POST',
			        'content' => http_build_query($data),
			    )
			);

			$context  = stream_context_create($options);
			$res = @file_get_contents($url, false, $context);
			delete_option( 'exwoofood_verify_options');
			delete_option( 'exwf_ckforupdate');
			delete_option( 'exwf_li_mes');
			delete_option( 'exwf_cupdate');
			update_option( 'exwf_license','');
			wp_redirect( ( admin_url( '?page=exwoofood_verify_options' ) ) );
			die;
		}
		if (get_option('_exwp_udoption')!='updated' && is_user_logged_in() && current_user_can( 'manage_options' ) && function_exists('exwoofood_get_option')){
			$_timesl = exwoofood_get_option('exwoofood_ck_times','exwoofood_advanced_options');
			if(is_array($_timesl) && !empty($_timesl)){
				$_newtsl= array();
				foreach ($_timesl as $value) {
					$_newtsl[] = array(
						'name-ts'=> $value
					);
				}
				if(!empty($_newtsl)){
					$all_options = get_option( 'exwoofood_advanced_options' );
					$all_options['exwoofood_ck_times'] = '';
					$all_options['exwfood_deli_time'] = $_newtsl;
					update_option( 'exwoofood_advanced_options', $all_options );
				}
			}
			update_option( '_exwp_udoption', 'updated' );
		}else if(is_user_logged_in() && current_user_can( 'manage_options' )){
			if(isset($_GET['exot_reset']) && $_GET['exot_reset']=='yes' && isset($_GET['page']) && strpos($_GET['page'], 'exwoofood') !== false ){
				update_option( $_GET['page'], '' );
			}
		}
	}
}
// active into
if(!function_exists('exwf_check_purchase_code') && is_admin()){
	function exwf_check_purchase_code() {
		$class = 'notice notice-error';
		$message =  'You are using an unregistered version of WooCommerce Food, please <a href="'.esc_url(admin_url('admin.php?page=exwoofood_verify_options')).'">active your license</a> of WooCoommerce Food';
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	function exwf_invalid_pr_code() {
		$class = 'notice notice-error';
		$get_mes = get_option( 'exwf_li_mes');
		$get_mes = $get_mes!='' ? explode('|', $get_mes) : '';
		if(is_array($get_mes) && !empty($get_mes)){
			$message =  'Invalid purchase code for WooCommerce Food plugin, This license has registered for: '. $get_mes[0] .' - '. $get_mes[1] ;
		}else{
			$message =  'Invalid purchase code for WooCommerce Food plugin, please find check how to find your purchase code <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">here </a>';
		}
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	$scd_ck = get_option( 'exwf_ckforupdate');
	$crt = strtotime('now');
	$_name = exwoofood_get_option('exwoofood_evt_name','exwoofood_verify_options');
	$_pcode = exwoofood_get_option('exwoofood_evt_pcode','exwoofood_verify_options');
	if ($_name =='' || $_pcode=='' ) {
		add_action( 'admin_notices', 'exwf_check_purchase_code' );
		delete_option( 'exwf_ckforupdate');
	}
	if($scd_ck=='' || $crt > $scd_ck ){
		$check_version = '';
		global $pagenow;
		if((isset($_GET['page']) && ($_GET['page'] =='exwoofood_options' || $_GET['page'] =='exwoofood_verify_options' )) || (isset($_GET['post_type']) && $_GET['post_type']=='product') || $pagenow == 'plugins.php' ){
			
			$site = get_site_url();
			$url = 'https://exthemes.net/verify-purchase-code/';
			$myvars = 'buyer=' . $_name . '&code=' . $_pcode. '&site='.$site.'&item_id=25457330';
			$res = '';
			if(function_exists('stream_context_create')){
				$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'25457330', 'site' => $site);
				$options = array(
				        'http' => array(
				        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				        'method'  => 'POST',
				        'content' => http_build_query($data),
				    )
				);

				$context  = stream_context_create($options);
				$res = @file_get_contents($url, false, $context);
				if($res=== false){
					$res!='';
				}
			}
			if($res!=''){
				$res = json_decode($res);
			}else{
				$ch = curl_init( $url );
				curl_setopt( $ch, CURLOPT_POST, 1);
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt( $ch, CURLOPT_HEADER, 0);
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 2);
				$res=json_decode(curl_exec($ch),true);
				curl_close($ch);
			}
			$check_version = isset($res[5]) ? $res[5] : '';
			update_option( 'exwf_version', $check_version );
			//print_r( $res) ;exit;
			update_option( 'exwf_license', '');
			if(isset($res[0]) && $res[0] == 'error' && $_name!='' && $_pcode!=''){
				update_option( 'exwf_ckforupdate', '' );
				if(isset($res[2]) && isset($res[2][0]) && $res[2][0] == 'invalid'){
					update_option( 'exwf_li_mes', $res[2][1][0] );
				}
				update_option( 'exwf_ckforupdate', strtotime('+3 day') );
				update_option( 'exwf_license', 'invalid');
			}else if(isset($res[0]) && $res[0] == 'success'){
				update_option( 'exwf_ckforupdate', strtotime('+10 day') );
				delete_option( 'exwf_li_mes');
			}else{
				update_option( 'exwf_ckforupdate', strtotime('+5 day') );
			}
		}
	}
	if(get_option('exwf_license') =='invalid'){
		add_action( 'admin_notices', 'exwf_invalid_pr_code' );
	}
	if( ! function_exists('get_plugin_data') ){
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    if (file_exists( WP_PLUGIN_DIR.'/woocommerce-food/woo-food.php' ) ) {
    	$plugin_data = get_plugin_data( WP_PLUGIN_DIR  . '/woocommerce-food/woo-food.php' );
    }else{
	    $plugin_data = get_plugin_data( WP_PLUGIN_DIR  . '/woo-exfood/woo-food.php' );
	}
    $plugin_version = str_replace('.', '',$plugin_data['Version']);
    $check_version = get_option( 'exwf_version');
    $check_version = $check_version !='' ? str_replace('.', '',$check_version) : '';
    if(strlen($check_version) > strlen($plugin_version)){
    	$plugin_version = is_numeric($plugin_version) ?  $plugin_version *10 : '';
    }else if(strlen($check_version) < strlen($plugin_version)){
    	$check_version = is_numeric($check_version) ?  $check_version *10 : '';
    }
 	if($check_version!='' && $check_version > $plugin_version){
 		add_filter('wp_get_update_data','exwf_up_count_pl',10);
 		function exwf_up_count_pl($update_data){
 			$update_data['counts']['plugins'] =  $update_data['counts']['plugins'] + 1;
 			return $update_data;
 		}
 		if (file_exists( WP_PLUGIN_DIR.'/woocommerce-food/woo-food.php' ) ) {
 			add_action( 'after_plugin_row_woocommerce-food/woo-food.php', 'show_purchase_notice_under_plugin', 10 );
 		}else{
			add_action( 'after_plugin_row_woo-exfood/woo-food.php', 'show_purchase_notice_under_plugin', 10 );
		}
		function show_purchase_notice_under_plugin(){
			$text = sprintf(
				esc_html__( 'There is a new version of WooComemrce Food available. %1$s View details %2$s and please check how to update plugin %3$s here%4$s.', 'woocommerce-food' ),
					'<a href="https://exthemes.net/woocommerce-food/changelog/" target="_blank">',
					'</a>', 
					'<a href="https://exthemes.net/docs/all/woocommerce-food/installation/" target="_blank">',
					'</a>'
				);
			echo '
			<style>[data-slug="woo-exfood"].active td,[data-slug="woo-exfood"].active th { box-shadow: none;}</style>
			<tr class="plugin-update-tr active">
				<td colspan="4" class="plugin-update">
					<div class="update-message notice inline notice-alt"><p>'.$text.'</p></div>
				</td>
			</tr>';
		}
	}
}
//print_r(exwf_license_infomation());exit;
function exwf_license_infomation(){
	$scd_ck = get_option( 'exwf_cupdate');
	$crt = strtotime('now');
	$res = '';
	if($scd_ck=='' || $crt > $scd_ck ){
		$_name = exwoofood_get_option('exwoofood_evt_name','exwoofood_verify_options');
		$_pcode = exwoofood_get_option('exwoofood_evt_pcode','exwoofood_verify_options');
		if($_name=='' || $_pcode==''){
			return array('error');
		}
		$site = get_site_url();
		$url = 'https://exthemes.net/verify-purchase-code/';
		$myvars = 'buyer=' . $_name . '&code=' . $_pcode. '&site='.$site.'&item_id=25457330';
		$res = '';
		if(function_exists('stream_context_create')){
			$data = array('buyer' => $_name, 'code' => $_pcode, 'item_id' =>'25457330', 'site' => $site);
			$options = array(
			        'http' => array(
			        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			        'method'  => 'POST',
			        'content' => http_build_query($data),
			    )
			);

			$context  = stream_context_create($options);
			$res = @file_get_contents($url, false, $context);
			if($res=== false){
				$res!='';
			}
		}
		if($res!=''){
			$res = json_decode($res);
		}else{
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_POST, 1);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt( $ch, CURLOPT_HEADER, 0);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
			curl_setopt($ch, CURLOPT_TIMEOUT, 2);
			$res=json_decode(curl_exec($ch),true);
			curl_close($ch);
		}
		//print_r( $res) ;exit;
		if(isset($res[0]) && $res[0] == 'error'){
			update_option( 'exwf_cupdate', '' );
		}else if(isset($res[0]) && $res[0] == 'success'){
			update_option( 'exwf_cupdate', strtotime('+10 day') );
		}else{
			update_option( 'exwf_cupdate', strtotime('+3 day') );
		}
	}
	return $res;
}

// Show delivery date column
add_filter( 'manage_shop_order_posts_columns', 'exwf_edit_order_columns',99 );
function exwf_edit_order_columns( $columns ) {
	$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
	if($method_ship!=''){
		$columns['order-method'] = esc_html__( 'Order method' , 'woocommerce-food' );
	}
	$columns['date-delivery'] = esc_html__( 'Delivery time' , 'woocommerce-food' );
	$args = array(
		'hide_empty'        => true,
		'parent'        => '0',
	);
	$terms = get_terms('exwoofood_loc', $args);
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		$columns['order-loc'] = esc_html__( 'Location' , 'woocommerce-food' );
	}
	return $columns;
}
add_action( 'manage_shop_order_posts_custom_column', 'exwf_admin_order_delivery_columns',12);
function exwf_admin_order_delivery_columns( $column ) {
	global $post;
	switch ( $column ) {
		case 'order-method':
			$exfood_id = $post->ID;
			$order_method = get_post_meta( $exfood_id, 'exwfood_order_method', true );
			$order_method = $order_method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $order_method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : esc_html__('Delivery','woocommerce-food'));
			echo '<span class="order-method">'.$order_method.'</span>';
			break;
		case 'date-delivery':
			$exfood_id = $post->ID;
			echo '<span class="exfood_id">'.get_post_meta( $exfood_id, 'exwfood_date_deli', true ).' '.get_post_meta( $exfood_id, 'exwfood_time_deli', true ).'</span>';
			break;
		case 'order-loc':
			$exfood_id = $post->ID;
			$log_name = get_term_by('slug', get_post_meta( $exfood_id, 'exwoofood_location', true ), 'exwoofood_loc');
			if(isset($log_name->name) && $log_name->name){
			echo '<span class="order-loc">'.$log_name->name.'</span>';
			}
			break;		
	}
}
add_action( 'manage_edit-shop_order_sortable_columns', 'exwf_order_sortable_date_deli',12);
function exwf_order_sortable_date_deli( $columns )
{
    $columns['date-delivery'] = 'date_delivery';
    return $columns;
}
/***** add filter order by delivery date *****/
if(!function_exists('exwf_admin_filter_order_delivery')){
	function exwf_admin_filter_order_delivery( $post_type, $which ) {
		if ( $post_type == 'shop_order' ) {	
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-datetimepicker');
			// Display filter HTML
			echo '<input type="text" class="date-picker" name="date_delivery" placeholder="'.esc_html__( 'Select delivery date', 'woocommerce-food' ).'" value="'.(isset( $_GET['date_delivery'] ) ? $_GET['date_delivery'] : '' ).'">';

			// timeslot
			$array_time = array();
			$default_time = exwoofood_get_option('exwfood_deli_time','exwoofood_advanced_options');
		    $adv_timesl = exwoofood_get_option('exwfood_adv_timedeli','exwoofood_adv_timesl_options');
		    if( is_array($adv_timesl) && !empty($adv_timesl)){
		    	foreach ($adv_timesl as $it_timesl) {
		    		foreach ($it_timesl['exwfood_deli_time'] as $time_option) {
		    			$r_time ='';
						if(isset($time_option['start-time']) && $time_option['start-time']!='' && isset($time_option['end-time']) && $time_option['end-time']!=''){
							$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
						}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
							$r_time = $time_option['start-time'];
						}
						$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
			    		$array_time[$name] = $name;
			    	}
		    	}
		    	//echo '<pre>';print_r($array_time);echo '</pre>';
		    }else if(empty($array_time) && !empty($default_time)){
		    	foreach ($default_time as $time_option) {
	    			$r_time ='';
					if(isset($time_option['start-time']) && $time_option['start-time']!='' && isset($time_option['end-time']) && $time_option['end-time']!=''){
						$r_time = $time_option['start-time'].' - '.$time_option['end-time'];
					}elseif(isset($time_option['start-time']) && $time_option['start-time']!=''){
						$r_time = $time_option['start-time'];
					}
					$name = isset($time_option['name-ts']) && $time_option['name-ts']!=''? $time_option['name-ts'] : $r_time;
		    		$array_time[$name] = $name;
		    	}
		    }
		    if(!empty($array_time)){
		    	echo "<select name='time_slot' id='time_slot' class='postform'>";
				echo '<option value="">' . esc_html__( 'All Time slots', 'exthemes' ) . '</option>';
				foreach ($array_time as $itemsl) {
					echo '<option value="'.esc_attr($itemsl).'">' . $itemsl . '</option>';
				}
				echo '</select>';
		    }
			//echo '<input type="text" class="time-slots" name="time_slot" placeholder="'.esc_html__( 'Select a Time slot', 'woocommerce-food' ).'" value="'.(isset( $_GET['time_slot'] ) ? $_GET['time_slot'] : '' ).'">';

			$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
			$dine_in = exwoofood_get_option('exwoofood_enable_dinein','exwoofood_shpping_options');
			if($dine_in=='yes' && $method_ship!='' || $method_ship=='both'){
				echo "<select name='method' id='method' class='postform'>";
				echo '<option value="">' . esc_html__( 'All Shipping methods', 'exthemes' ) . '</option>';
				if($method_ship!='takeaway'){
					echo '<option value="delivery" '.(( isset( $_GET['method'] ) && ( $_GET['method'] == 'delivery' ) ) ? ' selected="selected"' : '' ).'>'.esc_html__( 'Delivery', 'woocommerce-food' ).'</option>';
				}
				if($method_ship!='delivery'){
					echo '<option value="takeaway" '.(( isset( $_GET['method'] ) && ( $_GET['method'] == 'takeaway' ) ) ? ' selected="selected"' : '' ).'>'.esc_html__( 'Takeaway', 'woocommerce-food' ).'</option>';
				}
				if($dine_in=='yes'){
					echo '<option value="dinein" '.(( isset( $_GET['method'] ) && ( $_GET['method'] == 'dinein' ) ) ? ' selected="selected"' : '' ).'>'.esc_html__( 'Dine-in', 'woocommerce-food' ).'</option>';
				}
				echo '</select>';
			}
			$args = array(
				'hide_empty'        => false,
				'parent'        => '0',
			);
			$loc_selected = isset( $_GET['floc'] ) ? ( $_GET['floc'] ) : '';
			$terms = get_terms('exwoofood_loc', $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){?>
				<select class="postform" name="floc">
					<?php 
			    	$count_stop = 5;
			    	echo '<option value="">'.esc_html__( '-- Select --', 'woocommerce-food' ) .'</option>';
			    	foreach ( $terms as $term ) {
			    		$select_loc = '';
			    		if ($term->slug !='' && $term->slug == $loc_selected) {
			                $select_loc = ' selected="selected"';
			              }
				  		echo '<option value="'. esc_attr($term->slug) .'" '.$select_loc.'>'. wp_kses_post($term->name) .'</option>';
				  		echo exfd_show_child_location('',$term,$count_stop,$loc_selected,'yes');
				  	}
			        ?>
				</select>
				<?php
			}

		}
	
	}
	add_action( 'restrict_manage_posts', 'exwf_admin_filter_order_delivery' , 10, 2);
}
add_action( 'admin_init', function () {
    global $pagenow;
    # Check current admin page.
    $id = isset( $_GET['post'] ) ? $_GET['post'] : '';
    if ( $pagenow == 'post.php' && $id!='' && ( in_array ( get_post_type( $id ), array('shop_order','product') )) ) {
    	$user = wp_get_current_user();
    	$loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
    	if (isset($user->roles[0]) &&  $user->roles[0]=='shop_manager' && get_post_type( $id ) =='shop_order' ) {
    		$log_name =  get_post_meta( $id, 'exwoofood_location', true );
    		if(is_array($loc_selected) && !empty($loc_selected) && !in_array($log_name,$loc_selected)){
    			wp_redirect( admin_url( '/edit.php?post_type='.get_post_type($id) ) );
        		exit;
    		}
    	}else{
    		if(is_array($loc_selected) && !empty($loc_selected) && !has_term( $loc_selected, 'exwoofood_loc', $id )){
    			wp_redirect( admin_url( '/edit.php?post_type='.get_post_type($id) ) );
        		exit;
    		}
    	}
    }
    if( isset($_GET['exwf_uddel']) && $_GET['exwf_uddel']=='yes' || isset($_GET['page']) && $_GET['page']=='exwf_ocal_options' ){
		if(get_option('_exwf_uddel')!='updated'){
			update_option('_exwf_uddel','updated');
			$my_posts = $orders = wc_get_orders( array('numberposts' => -1) );
			foreach ( $my_posts as $post ):
				$id = $post->get_id();
				$datetime = get_post_meta($id,'exwfood_datetime_deli_unix', true );
				$date = get_post_meta($id,'exwfood_date_deli_unix', true );
				if($datetime==''){
					update_post_meta( $id, 'exwfood_datetime_deli_unix', $date );
				}
			endforeach;
		}
	}

} );
add_action( 'pre_get_posts','exwf_admin_filter_delivery_qr',101 );
if (!function_exists('exwf_admin_filter_delivery_qr')) {
	function exwf_admin_filter_delivery_qr($query) {
		if ( isset($_GET['post_type']) && $_GET['post_type']=='shop_order' && is_admin()) {
			$meta_query_args = array();
			$method = isset($_GET['method']) ? $_GET['method'] : '';
			if( $method!='' ){
				$meta_query_args['relation'] = 'AND';
				if($method!='delivery'){
					$meta_query_args[]= array(
						'key' => 'exwfood_order_method',
						'value' => $method,
						'compare' => '=',
					);
				}else{
					$meta_query_args[] = array(
						'relation' => 'OR',
						array(
							'key' => 'exwfood_order_method',
							'value' => $method,
							'compare' => '=',
						),
						array(
							'key' => 'exwfood_order_method',
							'value' => '',
							'compare' => 'NOT EXISTS',
						)
					);
				}
			}
			if( isset($_GET['date_delivery']) && $_GET['date_delivery']!='' ){
				$unix_tdl = strtotime($_GET['date_delivery']);
				/*
				$query->set('meta_key', 'exwfood_date_deli_unix');
				//$query->set('orderby', 'meta_value_num');
				$query->set('meta_value', $unix_tdl);
				$query->set('meta_compare', '=');
				//$query->set('order', 'ASC');
				*/
				$meta_query_args[] = array(
					'relation' => 'AND',
					array(
						'key' => 'exwfood_date_deli_unix',
						'value' => $unix_tdl,
						'compare' => '>=',
					),
					array(
						'key' => 'exwfood_date_deli_unix',
						'value' => ($unix_tdl + 86399 ),
						'compare' => '<=',
					)
				);
			}
			if( isset($_GET['time_slot']) && $_GET['time_slot']!='' ){
				$meta_query_args[] = array(
					'key' => 'exwfood_time_deli',
					'value' => $_GET['time_slot'],
					'compare' => '=',
				);
			}
			$loc = isset($_GET['floc']) ? $_GET['floc'] : '';
			if( $loc!='' ){
				$meta_query_args['relation'] = 'AND';
				$args = array(
					'hide_empty'        => false,
					'parent'        => '0'
				);
				$terms = get_terms('exwoofood_loc', $args);
				$locs_to_filter[] = $loc;
				foreach($terms AS $term){
					if($term->parent === 0 AND $term->slug == $loc){
						$args2 = array(
							'hide_empty'        => false,
							'parent'        => $term->term_id
						);
						$childTerms = get_terms('exwoofood_loc', $args2);
						foreach($childTerms AS $childTerm){
							$locs_to_filter[] = $childTerm->slug;
						}
					}
				}
				$meta_query_args[] = array(
					'key' => 'exwoofood_location',
					'value' => $locs_to_filter,
					'compare' => 'IN',
				);
			}
			//echo '<pre>';print_r($meta_query_args);exit;
			if(!empty($meta_query_args)){
				$query->set('meta_query', $meta_query_args);
			}
			if( isset($_GET['orderby']) && $_GET['orderby']=='date_delivery' ){
				$query->set( 'order', $_GET['order'] );
        		$query->set( 'orderby', 'meta_value_num' );
        		$query->set( 'meta_key', 'exwfood_datetime_deli_unix' );
			}
		}
	}
}

add_filter( 'plugin_row_meta','exwf_plugin_row_meta_link', 10, 2 );
function exwf_plugin_row_meta_link( $links, $file ) {
	if ( 'woo-exfood/woo-food.php' === $file || 'woocommerce-food/woo-food.php' === $file ) {
		$row_meta = array(
			'support' => '<a href="https://codecanyon.net/item/woocommerce-food-restaurant-menu-food-ordering/25457330/support" target="_blank" title="">Support</a>',
			'doc'  => '<a href="https://exthemes.net/docs/all/woocommerce-food/" target="_blank" title="">Plugin Documentation</a>',
		);
		return array_merge( $links, $row_meta );
	}

	return (array) $links;
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'exwf_adm_display_date_deli', 10, 1 );

function exwf_adm_display_date_deli($order){
	$text_datedel = exwf_date_time_text('date',$order);
	$text_timedel = exwf_date_time_text('time',$order);
	$order_method = get_post_meta( $order->get_id(), 'exwfood_order_method', true );
	$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
	$dine_in = exwoofood_get_option('exwoofood_enable_dinein','exwoofood_shpping_options');
	echo '<p class="exwf-adm-odmethod"><strong>'.esc_html__('Order method','woocommerce-food').':</strong> 
	<select name="exwf_odmethod" class="postform" data-tdel="'.esc_html__( 'Delivery Date', 'woocommerce-food' ).'" data-ttk="'.esc_html__( 'Pickup Date', 'woocommerce-food' ).'" data-tdin="'.esc_html__( 'Date', 'woocommerce-food' ).'" data-ttdel="'.esc_html__( 'Delivery Time', 'woocommerce-food' ).'" data-tttk="'.esc_html__( 'Pickup Time', 'woocommerce-food' ).'" data-ttdin="'.esc_html__( 'Time', 'woocommerce-food' ).'">';
	echo '<option value="">'.esc_html__( '-- Select --', 'woocommerce-food' ) .'</option>';
	if($method_ship!='takeaway'){
		echo '<option value="delivery" '.( $order_method == 'delivery'  ? ' selected="selected"' : '' ).'>'.esc_html__( 'Delivery', 'woocommerce-food' ).'</option>';
	}
	if($method_ship!='delivery'){
		echo '<option value="takeaway" '.($order_method == 'takeaway' ? ' selected="selected"' : '' ).'>'.esc_html__( 'Takeaway', 'woocommerce-food' ).'</option>';
	}
	if($dine_in=='yes'){
		echo '<option value="dinein" '.($order_method == 'dinein' ? ' selected="selected"' : '' ).'>'.esc_html__( 'Dine-in', 'woocommerce-food' ).'</option>';
	}
	if($order_method != '' && $order_method != 'delivery' && $order_method != 'takeaway' && $order_method != 'dinein'){
		echo '<option value="'.esc_attr($order_method).'" selected="selected">'.$order_method.'</option>';
	}
	echo '</select></p>';
	$log_name =  get_post_meta( $order->get_id(), 'exwoofood_location', true );
	$args = array(
		'hide_empty'        => false,
		'parent'        => '0',
	);
	$terms = get_terms('exwoofood_loc', $args);
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
		echo '<p><strong>'.esc_html__( 'Location', 'woocommerce-food' ).':</strong> '?>
		<select class="postform" name="exwf_odloc">
			<?php 
	    	$count_stop = 5;
	    	echo '<option value="">'.esc_html__( '-- Select --', 'woocommerce-food' ) .'</option>';
	    	foreach ( $terms as $term ) {
	    		$select_loc = '';
	    		if ($term->slug !='' && $term->slug == $log_name) {
	                $select_loc = ' selected="selected"';
	              }
		  		echo '<option value="'. esc_attr($term->slug) .'" '.$select_loc.'>'. wp_kses_post($term->name) .'</option>';
		  		echo exfd_show_child_location('',$term,$count_stop,$log_name,'yes');
		  	}
	        ?>
		</select>
		<?php
	}else if($log_name!=''){
		echo '<p><strong>'.esc_html__( 'Location', 'woocommerce-food' ).':</strong> ' . $log_name . '</p>';
	}
	$_datedl = get_post_meta( $order->get_id(), 'exwfood_date_deli_unix', true );
    echo '<p class="exwf-adm-oddate" ><strong>'.$text_datedel.':</strong> <input type="text" class="date-picker" name="exwf_deldate" placeholder="'.esc_html__( 'Select delivery date', 'woocommerce-food' ).'" value="'.($_datedl!='' ? esc_attr( date_i18n( 'Y-m-d', $_datedl ) ) : '' ).'"></p>';
	$_timedl = get_post_meta( $order->get_id(), 'exwfood_time_deli', true );
	echo '<p class="exwf-adm-odtime"><strong>'.$text_timedel.':</strong> <input type="text" class="" name="exwf_deltime" placeholder="'.esc_html__( 'Set delivery time', 'woocommerce-food' ).'" value="'.($_timedl!='' ? $_timedl : '' ).'"></p>';
    $_nb_per = get_post_meta( $order->get_id(), 'exwfood_person_dinein', true );
    echo '<p class="exwf-di-person '.($order_method != 'dinein' ? 'ex-hidden' :'').'"><strong>'.esc_html__('Number of person','woocommerce-food').':</strong> <input type="text" class="" name="exwf_diperson" placeholder="'.esc_html__( 'Set Number of person', 'woocommerce-food' ).'" value="'.($_nb_per!='' ? $_nb_per : '' ).'"></p>';
}
// For saving the metabox data.
add_action( 'save_post_shop_order', 'exwf_custom_order_meta_data' );
function exwf_custom_order_meta_data( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_shop_order', $post_id ) ) {
        return;
    }
    //echo '<pre>';print_r($_POST);exit;
    if ( isset( $_POST['exwf_odmethod'] ) ) {
        update_post_meta( $post_id, 'exwfood_order_method', sanitize_text_field( $_POST['exwf_odmethod'] ) );
    }
    if ( isset( $_POST['exwf_odloc'] ) ) {
        update_post_meta( $post_id, 'exwoofood_location', sanitize_text_field( $_POST['exwf_odloc'] ) );
    }
    if ( isset( $_POST['exwf_deldate'] ) ) {
    	$dunix = strtotime($_POST['exwf_deldate']);
        update_post_meta( $post_id, 'exwfood_date_deli_unix', $dunix );
        if ( isset( $_POST['exwf_deltime'] ) ) {
        	$timedel = str_replace(' ', '', $_POST['exwf_deltime']);
        	$timedel = explode('-',$timedel);
        	$start = isset($timedel[0]) ? strtotime($timedel[0]) - strtotime(date('Y-m-d')) : 0;
        	update_post_meta( $post_id, 'exwfood_datetime_deli_unix', ($start> 0 ? $dunix+$start : $dunix ) );
        }else{
        	update_post_meta( $post_id, 'exwfood_datetime_deli_unix', $dunix );
        }
        update_post_meta( $post_id, 'exwfood_date_deli', sanitize_text_field( date_i18n(get_option('date_format'),$dunix) ) );
    }
    if ( isset( $_POST['exwf_deltime'] ) ) {
        update_post_meta( $post_id, 'exwfood_time_deli', sanitize_text_field( $_POST['exwf_deltime'] ) );
    }
    if ( isset( $_POST['exwf_odmethod'] ) && $_POST['exwf_odmethod']=='dinein' && isset( $_POST['exwf_diperson'] )  ) {
        update_post_meta( $post_id, 'exwfood_person_dinein', sanitize_text_field( $_POST['exwf_diperson'] ) );
    }
}

function exwf_adm_get_method_enable(){
	$method_ship = exwoofood_get_option('exwoofood_enable_method','exwoofood_shpping_options');
	$dine_in = exwoofood_get_option('exwoofood_enable_dinein','exwoofood_shpping_options');
	$arr_methods= array();
	if($method_ship=='takeaway' || $method_ship=='delivery'){
		$arr_methods[] = $method_ship;
	}else if($method_ship=='both'){
		$arr_methods[] = 'takeaway';
		$arr_methods[] = 'delivery';
	}
	if($dine_in=='yes'){
		$arr_methods[] = 'dinein';
	}
	return apply_filters('exwf_adm_arr_enable_method',$arr_methods);
}

// add user location role
add_action( 'show_user_profile', 'exwf_user_manager_loc_profile_fields' );
add_action( 'edit_user_profile', 'exwf_user_manager_loc_profile_fields' );

function exwf_user_manager_loc_profile_fields( $user ) {
	if(!current_user_can( 'promote_user', $user->ID )){
		return;
	}?>
    <h3><?php esc_html_e("Location manager", "woocommerce-food"); ?></h3>
    <table class="form-table">
    <tr>
        <th><label for="exwf_mng_loc"><?php esc_html_e("Locations", "woocommerce-food"); ?></label></th>
        <td>
        	<?php 
        	$args = array(
				'hide_empty'        => false,
			);
			$loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
        	$terms = get_terms('exwoofood_loc', $args);
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){?>
				<select name="exwf_mng_loc[]" multiple>
					<?php 
			    	$count_stop = 5;
			    	echo '<option value="">'.esc_html__( '-- Select --', 'woocommerce-food' ) .'</option>';
			    	foreach ( $terms as $term ) {
			    		$select_loc = '';
			    		if ($term->slug !='' && is_array($loc_selected) && in_array($term->slug,$loc_selected) ) {
			                $select_loc = ' selected="selected"';
			              }
				  		echo '<option value="'. esc_attr($term->slug) .'" '.$select_loc.'>'. wp_kses_post($term->name) .'</option>';
				  	}
			        ?>
				</select>
				<?php
			}
        	?>
            <p class="description"><?php esc_html_e('Select locations to allow this user can manage order and product, leave blank to allow this user can manage all orders and products','woocommerce-food'); ?></p>
        </td>
    </tr>
    </table>
<?php }
add_action( 'personal_options_update', 'exwf_save_manager_loc_profile_fields' );
add_action( 'edit_user_profile_update', 'exwf_save_manager_loc_profile_fields' );

function exwf_save_manager_loc_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if(!current_user_can( 'promote_user', $user_id )){
		return;
	}
    update_user_meta( $user_id, 'exwf_mng_loc', $_POST['exwf_mng_loc'] );
}

add_action( 'pre_get_posts','exwf_admin_manage_by_loc',102 );
if (!function_exists('exwf_admin_manage_by_loc')) {
	function exwf_admin_manage_by_loc($query) {
		if(!is_admin()){
			return;
		}
		if ( !function_exists('wp_get_current_user') ) {
		    include(ABSPATH . "wp-includes/pluggable.php"); 
		}
		$user = wp_get_current_user();
		if ( is_admin() &&  isset($user->roles[0]) &&  $user->roles[0]=='shop_manager' && in_array ( $query->get('post_type'), array('shop_order','product') )) {
			$loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
			if(is_array($loc_selected) && !empty($loc_selected)){
				if($query->get('post_type')=='shop_order'){
					$meta_query_args['relation'] = 'AND';
					$meta_query_args[] = array(
						'key' => 'exwoofood_location',
						'value' => $loc_selected,
						'compare' => 'IN',
					);
					$query->set('meta_query', $meta_query_args);
				}else{
					$tax_query_args['relation'] = 'OR';
					$tax_query_args[] = array(
						'taxonomy' => 'exwoofood_loc',
						'field' => 'slug',
						'terms' => $loc_selected,
						'operator' => 'IN',
					);
					$query->set('tax_query', $tax_query_args);
				}
			}
		}
	}
}

add_action( 'pre_insert_term', function ( $term, $taxonomy ){
	$user = wp_get_current_user();
    $loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
    if('exwoofood_loc' === $taxonomy && is_array($loc_selected) && !empty($loc_selected)){
    	return new WP_Error( 'term_addition_blocked', esc_html__( 'You cannot add terms to this taxonomy', 'woocommerce-food' ) );
    }
    return  $term;
}, 0, 2 );

add_filter( 'get_terms_args', function ( $query,$taxonomies ){
	if(is_admin() && isset($taxonomies[0]) && $taxonomies[0] == 'exwoofood_loc'){
		$user = wp_get_current_user();
	    $loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
	    if(is_array($loc_selected) && !empty($loc_selected)){
			$query['slug'] = $loc_selected;
		}
	}
	return $query;
}, 0,2 );
add_filter('exwoofood_loc_row_actions','exwf_remove_delete_link_term',10, 2 );
function exwf_remove_delete_link_term($actions,$post) {
	$user = wp_get_current_user();
    $loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
    if(is_array($loc_selected) && !empty($loc_selected)){
    	unset($actions['delete']);
    }
    return $actions;
}
add_action('admin_init', 'exwf_set_object_terms_terms',1);
function exwf_set_object_terms_terms(){
	$post_id = isset($_POST['post_ID']) ? $_POST['post_ID'] : '';
	if($post_id == '' || 'product' !== get_post_type($post_id)){ return;}
	$user = wp_get_current_user();
    $loc_selected = get_the_author_meta( 'exwf_mng_loc', $user->ID );
    if( is_array($loc_selected) && !empty($loc_selected)){
		$term_obj_list = wp_get_post_terms($post_id, 'exwoofood_loc' );
		$terms_arr = wp_list_pluck($term_obj_list, 'term_id');print_r($terms_arr);
		$_POST['tax_input']['exwoofood_loc'] = $terms_arr;
	}	
}