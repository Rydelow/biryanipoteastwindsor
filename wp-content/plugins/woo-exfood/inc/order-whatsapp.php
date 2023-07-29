<?php
// tip setting
add_action( 'cmb2_admin_init', 'exwoofood_register_setting_whatsapp',24 );
function exwoofood_register_setting_whatsapp(){
	$args = array(
		'id'           => 'exwoofood_whatsapp',
		'menu_title'   => '',
		'object_types' => array( 'options-page' ),
		'option_key'   => 'exwoofood_whatsapp_options',
		//'parent_slug'  => 'edit.php?post_type=product',
		'tab_group'    => 'exwoofood_options',
		'capability'    => 'manage_woocommerce',
		'tab_title'    => esc_html__('Order On whatsapp','woocommerce-food'),
	);
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'exwoofood_options_display_with_tabs';
	}
	$whatsapp_options = new_cmb2_box( $args );
	$whatsapp_options->add_field( array(
		'name'             => esc_html__( 'Whatsapp number', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enter Whatsapp number in International format, example: +12124567890', 'woocommerce-food' ),
		'id'               => 'exwoofood_whatsapp_nb',
		'type'             => 'text',
		'escape_cb'   => '',
		'before_row'     => 'exwf_ot_add_adv_time_html',
	) );
	
	$whatsapp_options->add_field( array(
		'name'             => esc_html__( 'Message title', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enter Message title', 'woocommerce-food' ),
		'id'               => 'exwoofood_whatsapp_mes',
		'type'             => 'text',
		'escape_cb'   => '',
	) );
	$whatsapp_options->add_field( array(
		'name'             => esc_html__( 'Whatsapp number by location', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enable whatsapp number setting by location', 'woocommerce-food' ),
		'id'               => 'exwoofood_whatsapp_loc',
		'type' => 'select',
		'show_option_none' => false,
		'default' => '',
		'options'          => array(
			'' => esc_html__( 'No', 'woocommerce-food' ),
			'yes'   => esc_html__( 'Yes', 'woocommerce-food' ),
		),
		'escape_cb'   => '',
	) );
	
}
add_action( 'exwf_admin_adv_settings_tab_html', 'exwf_adv_whatsapp_settings_tab_html',31,2 );
function exwf_adv_whatsapp_settings_tab_html($html,$tab){
	$html .= ' | <a href="?page=exwoofood_whatsapp_options" class="'.($tab=='exwoofood_whatsapp_options' ? 'current' : '').'">'.esc_html__('Order On whatsapp','woocommerce-food').'</a>';
	return $html; 
}
add_action( 'cmb2_admin_init', 'exwf_whatsapp_meta_loc' );
function exwf_whatsapp_meta_loc(){
	$whatsapp_loc = exwoofood_get_option('exwoofood_whatsapp_loc','exwoofood_whatsapp_options');
	if($whatsapp_loc!=='yes'){ return;}
	$prefix = 'exwp_loc_';
	/**
	 * Metabox to add fields to categories and tags
	 */
	$exwf_log_meta = new_cmb2_box( array(
		'id'               => $prefix . 'data',
		'title'            => esc_html__( 'Category Metabox', 'woocommerce-food' ), // Doesn't output for term boxes
		'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
		'taxonomies'       => array( 'exwoofood_loc'), // Tells CMB2 which taxonomies should have these fields
		'new_term_section' => true, // Will display in the "Add New Category" section
	) );
	$exwf_log_meta->add_field( array(
		'name' => esc_html__( 'Whatsapp number', 'woocommerce-food' ),
		'id'   => $prefix .'whatsapp_nb',
		'desc' => esc_html__( 'Enter Whatsapp number in International format to receive order for this location, example: +12124567890', 'woocommerce-food' ),
		'type' => 'text',
	) );
}
add_action( 'exwf_sidecart_after_content', 'exwf_whatsapp_by_loc_refresh' );
function exwf_whatsapp_by_loc_refresh(){
	if(isset($_GET["loc"])){
		$whatsapp_loc = exwoofood_get_option('exwoofood_whatsapp_loc','exwoofood_whatsapp_options');
		if($whatsapp_loc=='yes'){
			echo '<div class="exwf-whastsapp-byloc ex-hidden">'.exwf_add_whatsapp_order_button(true).'</div>';
		}
	};
}

function exwf_add_whatsapp_order_button($rtbt=false) {
	$whatsapp = exwoofood_get_option('exwoofood_whatsapp_nb','exwoofood_whatsapp_options');
	$mes = exwoofood_get_option('exwoofood_whatsapp_mes','exwoofood_whatsapp_options');
	$whatsapp_loc = exwoofood_get_option('exwoofood_whatsapp_loc','exwoofood_whatsapp_options');
	if($whatsapp_loc =='yes'){
		$loc_selected = exwf_get_loc_selected();
		$whatsapp_nb_loc = get_term_meta( $loc_selected, 'exwp_loc_whatsapp_nb', true );
		if($whatsapp_nb_loc!=''){
			$whatsapp = $whatsapp_nb_loc;
		}
	}
	if($whatsapp=='' || $whatsapp=='off'){
		return;
	}
	$html_item_details = '';
	$i = 0;
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
		$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
		$i++;
		if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

			$html_item_details .= $i.'. '.$_product->get_name(). "\n";
			$options = wc_get_formatted_cart_item_data( $cart_item,true );
			if($options!=''){
				$options = html_entity_decode($options);
				$html_item_details .= $options;
			}
			$html_item_details .= esc_html__('Price: ','woocommerce-food').html_entity_decode(apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key )). "\n"; 
			$html_item_details .= esc_html__('Qty: ','woocommerce-food').$cart_item['quantity']. "\n";
			$html_item_details .= esc_html__('Total: ','woocommerce-food').html_entity_decode(apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key )). "\n";	
		}
	}
	if($mes==''){
		if($i==1){
			$htext = esc_html__('Hello! I want to order following product: ','woocommerce-food'). "\n";
			$htext .= '------------------------------'. "\n";
		}else{
			$htext = esc_html__('Hello! I want to order following products: ','woocommerce-food'). "\n";
			$htext .= '------------------------------'. "\n";
		}
	}else{
		$htext = $mes. "\n";
	}
	$html_item_details = $htext.$html_item_details;
	$html_item_details .='------------------------------'. "\n";
	$user_odmethod = WC()->session->get( '_user_order_method' );
	if($user_odmethod!=''){
		$user_odmethod = $user_odmethod=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $user_odmethod=='dinein' ? esc_html__('Dine-in','woocommerce-food') : esc_html__('Delivery','woocommerce-food'));
		$html_item_details .= esc_html__('Order method','woocommerce-food').': ' . $user_odmethod. "\n";
	}
	$html_item_details .= esc_html__( 'Subtotal: ', 'woocommerce-food' ) . html_entity_decode(WC()->cart->get_cart_subtotal()). "\n";
	foreach ( WC()->cart->get_fees() as $fee ) :
		$cart_totals_fee_html = WC()->cart->display_prices_including_tax() ? wc_price( $fee->total + $fee->tax ) : wc_price( $fee->total );
		$html_item_details .= esc_html( $fee->name ).': '. html_entity_decode(apply_filters( 'woocommerce_cart_totals_fee_html', $cart_totals_fee_html, $fee )). "\n"; 
	endforeach;

	$def_sp_price = isset(WC()->session->get('cart_totals')['shipping_total']) ? WC()->session->get('cart_totals')['shipping_total'] : '';
	if ( $def_sp_price > 0 &&  WC()->cart->needs_shipping() ) {
		$html_item_details .= esc_html__('Shipping fee','woocommerce-food').': ' .  html_entity_decode(wc_price($def_sp_price)). "\n";
	}
	$value = WC()->cart->get_total();
	// If prices are tax inclusive, show taxes here.
	if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() ) {
		$tax_string_array = array();
		$cart_tax_totals  = WC()->cart->get_tax_totals();
		if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) {
			foreach ( $cart_tax_totals as $code => $tax ) {
				$tax_string_array[] = sprintf( '%s %s', $tax->formatted_amount, $tax->label );
			}
		} elseif ( ! empty( $cart_tax_totals ) ) {
			$tax_string_array[] = sprintf( '%s %s', wc_price( WC()->cart->get_taxes_total( true, true ) ), WC()->countries->tax_or_vat() );
		}
		if ( ! empty( $tax_string_array ) ) {
			$taxable_address = WC()->customer->get_taxable_address();
			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				$country = WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ];
				$tax_text = wp_kses_post( sprintf( __( '(includes %1$s estimated for %2$s)', 'woocommerce' ), implode( ', ', $tax_string_array ), $country ) );
			} else {
				$tax_text = wp_kses_post( sprintf( __( '(includes %s)', 'woocommerce' ), implode( ', ', $tax_string_array ) ) );
			}
			$value .= '<small class="includes_tax">' . $tax_text . '</small>';
		}
	}
	$html_item_details .= esc_html__( 'Total: ', 'woocommerce-food' ) . html_entity_decode(apply_filters( 'woocommerce_cart_totals_order_total_html', $value )). "\n";

	$html_item_details = apply_filters('exwf_whatsapp_message',$html_item_details);
    $link = 'https://wa.me/'.($whatsapp!='' && $whatsapp!='+' ? $whatsapp : '').'/?text='.urlencode(strip_tags($html_item_details));
    $link = '<div class="exwf-order-whastsapp"><p class="woocommerce-mini-cart__buttons buttons"><a href="'.$link.'" target="_blank" class="button exwf-button"><i class="ion-social-whatsapp-outline"></i>'.esc_html__( 'Order on Whatsapp', 'woocommerce-food' ).'</a></p></div>';
    if(isset($rtbt) && $rtbt==true){
    	return $link;
    }else{
	    echo $link;
	}
}
add_action( 'woocommerce_widget_shopping_cart_after_buttons', 'exwf_add_whatsapp_order_button', 99 );
add_action( 'woocommerce_proceed_to_checkout', 'exwf_add_whatsapp_order_button', 99 );