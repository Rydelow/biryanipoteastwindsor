<?php
class EXWoofood_Minmax_quantity {
	public function __construct(){
		add_action( 'woocommerce_product_options_inventory_product_data', array($this,'meta_options'));
		add_action( 'woocommerce_process_product_meta', array($this,'save_options'), 10, 2 );
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variation_settings_fields'), 10, 3 );
		add_action( 'woocommerce_save_product_variation', array( $this, 'save_variation_settings_fields'), 10, 2 );
		//add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'data_minmax'), 10, 2 );
		add_action( 'exwoofood_before_shortcode', array( $this, 'js_verify'), 10, 2 );
		add_action( 'woocommerce_after_add_to_cart_quantity', array( $this, 'js_verify'), 10, 2 );
		add_filter( 'woocommerce_quantity_input_args', array( $this,'input_args'), 10, 2 );
		add_filter( 'woocommerce_add_to_cart_validation', array( $this,'add_to_cart_validation'), 100, 5 );
		add_filter( 'woocommerce_update_cart_validation', array( $this,'update_cart_validation'), 100, 4 );
		//add_filter( 'exwf_add_to_cart_validation_minmax', array( $this,'add_to_cart_validation'), 100, 5 );
		add_filter( 'woocommerce_available_variation', array( $this, 'load_variation_settings_fields'),15, 3 );
    }
	function meta_options(){
		echo '<div class="options_group">';
		woocommerce_wp_text_input( array(
			'id'      => 'exwf_minquantity',
			'value'   => get_post_meta( get_the_ID(), 'exwf_minquantity', true ),
			'label'   => esc_html__('Min quantity','woocommerce-food'),
			'desc_tip' => true,
			'description' => esc_html__('Set Min quantity for this product','woocommerce-food'),
			'type' => 'number',
			'custom_attributes' => array(
				'step' 	=> 'any',
				'min'	=> '1'
			) 
		) );
		woocommerce_wp_text_input( array(
			'id'      => 'exwf_maxquantity',
			'value'   => get_post_meta( get_the_ID(), 'exwf_maxquantity', true ),
			'label'   => esc_html__('Max quantity','woocommerce-food'),
			'desc_tip' => true,
			'description' => esc_html__('Set Max quantity for this product','woocommerce-food'),
			'type' => 'number',
			'custom_attributes' => array(
				'step' 	=> '1',
				'min'	=> '1'
			)
		) );
		echo '</div>';
	}
	
	function save_options( $id, $post ){
		update_post_meta( $id, 'exwf_minquantity', esc_attr($_POST['exwf_minquantity']) );
		update_post_meta( $id, 'exwf_maxquantity', esc_attr($_POST['exwf_maxquantity']) );
	}
	function variation_settings_fields( $loop, $variation_data, $variation ) {
		woocommerce_wp_text_input( 
			array( 
				'id'          => 'exwf_minquantity[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Minimum quantity','woocommerce-food' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-first',
				'placeholder' => esc_html__('Enter number', 'woocommerce-food' ),
				'description' => esc_html__( 'Set Min quantity for this variation', 'woocommerce-food' ),
				'value'       => get_post_meta( $variation->ID, 'exwf_minquantity', true ),
				'type' => 'number',
				'custom_attributes' => array(
					'step' 	=> '1',
					'min'	=> '1'
				)
			)
		);
		woocommerce_wp_text_input( 
			array( 
				'id'          => 'exwf_maxquantity[' . $variation->ID . ']', 
				'label'       => esc_html__( 'Maximum quantity','woocommerce-food' ), 
				'desc_tip'    => 'true',
				'wrapper_class' 	  => 'form-row form-row-last',
				'placeholder' => esc_html__('Enter number', 'woocommerce-food' ),
				'description' => esc_html__( 'Set Max quantity for this variation', 'woocommerce-food' ),
				'value'       => get_post_meta( $variation->ID, 'exwf_maxquantity', true ),
				'type' => 'number',
				'custom_attributes' => array(
					'step' 	=> '1',
					'min'	=> '1'
				)
			)
		);
	}
	function save_variation_settings_fields( $post_id ) {
		$exwf_minquantity = $_POST['exwf_minquantity'][ $post_id ];
		if( isset( $exwf_minquantity ) ) {
			update_post_meta( $post_id, 'exwf_minquantity', esc_attr( $exwf_minquantity ) );
		}
		$exwf_maxquantity = $_POST['exwf_maxquantity'][ $post_id ];
		if( isset( $exwf_maxquantity ) ) {
			update_post_meta( $post_id, 'exwf_maxquantity', esc_attr( $exwf_maxquantity ) );
		}
	}
	function load_variation_settings_fields( $data,$product,$_product_vari ) {
		$data['min_qty'] = $data['exwf_minquantity'] = get_post_meta( $data[ 'variation_id' ], 'exwf_minquantity', true );
		$data['max_qty'] = $data['exwf_maxquantity'] = get_post_meta( $data[ 'variation_id' ], 'exwf_maxquantity', true );
		return $data;
	}
	function data_minmax(){
		$exwf_minquantity = get_post_meta( get_the_ID(), 'exwf_minquantity', true );
		$exwf_maxquantity = get_post_meta( get_the_ID(), 'exwf_maxquantity', true );
		$_sold_individually = get_post_meta( get_the_ID(), '_sold_individually', true );
		if($_sold_individually!='yes'){
			echo '
			<input type="hidden" name="exwf_minq" value="'.esc_attr($exwf_minquantity).'">
			<input type="hidden" name="exwf_maxq" value="'.esc_attr($exwf_maxquantity).'">';
		}
	}
	function js_verify(){
		wp_enqueue_script( 'exwf-minmax-quantity' );
	}
	function input_args( $args, $product ) {
		$_sold_individually = get_post_meta( get_the_ID(), '_sold_individually', true );
		if($_sold_individually=='yes'){
			return $args;
		}
		//$product_id =  method_exists( $product, 'get_parent_id' ) ? $product->get_parent_id() : $product->get_id();
		$exwf_minquantity = get_post_meta( get_the_ID(), 'exwf_minquantity', true );
		$exwf_maxquantity = get_post_meta( get_the_ID(), 'exwf_maxquantity', true );
		if ( $exwf_minquantity!='' && $exwf_minquantity > 0 ) {
			$args['min_value'] = $exwf_minquantity;
			$args['input_value'] = $exwf_minquantity;
		}
		if ( $exwf_maxquantity!='' && $exwf_maxquantity > 0  ) {
			$args['max_value'] = $exwf_maxquantity;
		}
		if ( method_exists( $product, 'managing_stock' ) &&  $product->managing_stock() && ! $product->backorders_allowed() ) {
			$stock = $product->get_stock_quantity();
			$args['max_value'] = min( $stock, $args['max_value'] );	
		}
		return $args;
	}
	function add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {
		if(isset($_POST['exwf-up-cartitem']) && $_POST['exwf-up-cartitem']!=''){
			WC()->cart->set_quantity( $_POST['exwf-up-cartitem'], 0 );
		}
		/*$_sold_individually = get_post_meta( $product_id, '_sold_individually', true );
		if($_sold_individually=='yes'){
			return $args;
		}*/
		$id = $product_id;
		$exwf_varmin = $exwf_varmax = '';
		if(is_numeric($variation_id) && $variation_id > 0){
			$id = $variation_id;
			$exwf_varmin = get_post_meta( $id, 'exwf_minquantity', true );
			$exwf_varmax = get_post_meta( $id, 'exwf_maxquantity', true );
		}
		$exwf_minquantity = $exwf_varmin!='' ? $exwf_varmin : get_post_meta( $product_id, 'exwf_minquantity', true );
		$exwf_maxquantity = $exwf_varmax!='' ? $exwf_varmax : get_post_meta( $product_id, 'exwf_maxquantity', true );
		$already_in_cart 	= self::get_cart_qty( $product_id );
		//$product 			= wc_get_product( $product_id );
		$product_title 		= get_the_title($id);
		
		if ( $exwf_maxquantity!='' && $exwf_maxquantity > 0 ) {
			if ( ( $already_in_cart + $quantity ) > $exwf_maxquantity ) {
				$passed = false;
				if($already_in_cart > 0){		
					wc_add_notice( sprintf( esc_html__( 'You only can add a maximum of %1$s %2$s\'s to %3$s. You already have %4$s.', 'woocommerce-food' ), 
								$exwf_maxquantity,
								$product_title,
								'<a href="' . esc_url( wc_get_cart_url() ) . '">' . esc_html__( 'your cart', 'woocommerce-food' ) . '</a>',
								$already_in_cart ),
					'error' );
				}else{
					wc_add_notice( sprintf( esc_html__('Please select a value no more than %s.','woocommerce-food' ) , $exwf_maxquantity),'error' );
				}
			}
		}
		if ( $exwf_minquantity!='' && $exwf_minquantity > 0 ) {
			if ( ( $already_in_cart + $quantity ) < $exwf_minquantity ) {
				wc_add_notice( sprintf( esc_html__('Please select a value no less than %s.','woocommerce-food' ) , $exwf_minquantity),'error' );
				$passed = false;
			}
		}
		return $passed;
	}
	function update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {
		$_product   = apply_filters( 'woocommerce_cart_item_product', $values['data'], $values, $cart_item_key );
		$product_id =  $_product->get_id();
    	$exwf_varmin = $exwf_varmax = '';
		if($_product->get_parent_id()){
			$exwf_varmin = get_post_meta( $_product->get_parent_id(), 'exwf_minquantity', true );
			$exwf_varmax = get_post_meta( $_product->get_parent_id(), 'exwf_maxquantity', true );
		}
		$exwf_minquantity = $exwf_varmin!='' ? $exwf_varmin : get_post_meta( $product_id, 'exwf_minquantity', true );
		$exwf_maxquantity = $exwf_varmax!='' ? $exwf_varmax : get_post_meta( $product_id, 'exwf_maxquantity', true );

		$already_in_cart = self::get_cart_qty( $product_id,$cart_item_key );
		$product_title 		= get_the_title($product_id);
		if ( $exwf_maxquantity!='' && $exwf_maxquantity > 0 ) {
			if ( ( $already_in_cart + $quantity ) > $exwf_maxquantity ) {
				$passed = false;
				if($already_in_cart > 0){		
					wc_add_notice( sprintf( esc_html__( 'You only can add a maximum of %1$s %2$s\'s to %3$s', 'woocommerce-food' ), 
								$exwf_maxquantity,
								$product_title,
								esc_html__( 'your cart', 'woocommerce-food' ),
								$already_in_cart ),
					'error' );
				}else{
					wc_add_notice( sprintf( esc_html__('Please select a value no more than %s.','woocommerce-food' ) , $exwf_maxquantity),'error' );
				}
			}
		}
		if ( $exwf_minquantity!='' && $exwf_minquantity > 0 ) {
			if ( ( $already_in_cart + $quantity ) < $exwf_minquantity ) {
				wc_add_notice( sprintf( esc_html__('Please select a value no less than %s.','woocommerce-food' ) , $exwf_minquantity),'error' );
				$passed = false;
			}
		}
		return $passed;
	}
	function get_cart_qty( $product_id,$key=false ) {
		global $woocommerce;
		$running_qty = 0; 
		foreach($woocommerce->cart->get_cart() as $other_cart_item_keys => $values ) {
			if(isset($key) && $key!=''){
				if ( $key == $other_cart_item_keys ) {
					continue;
				}
			}
			if ( $product_id == $values['product_id'] ) {				
				$running_qty += (int) $values['quantity'];
			}
		}

		return $running_qty;
	}


}
$EXWoofood_Minmax_quantity = new EXWoofood_Minmax_quantity();