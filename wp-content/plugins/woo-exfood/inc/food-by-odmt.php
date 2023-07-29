<?php
add_action( 'init', 'exwf_register_odmt_taxonomies' );
function exwf_register_odmt_taxonomies(){
	$labels = array(
		'name'              => esc_html__( 'Order Method', 'woocommerce-food' ),
		'singular_name'     => esc_html__( 'Order Method', 'woocommerce-food' ),
		'search_items'      => esc_html__( 'Order Method','woocommerce-food' ),
		'all_items'         => esc_html__( 'All Order Method','woocommerce-food' ),
		'parent_item'       => esc_html__( 'Parent Order Method' ,'woocommerce-food'),
		'parent_item_colon' => esc_html__( 'Parent Order Method:','woocommerce-food' ),
		'edit_item'         => esc_html__( 'Edit Order Method' ,'woocommerce-food'),
		'update_item'       => esc_html__( 'Update Order Method','woocommerce-food' ),
		'add_new_item'      => esc_html__( 'Add New Order Method' ,'woocommerce-food'),
		'menu_name'         => esc_html__( 'Order Method','woocommerce-food' ),
	);			
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_menu' => false,
		'public'         => false,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'order-method' ),
	);
	register_taxonomy('exwf_odmethod', array( 'product' ), $args);
	//register_taxonomy('exwf_odmethod', array( 'exwf_menubydate','product' ), $args);
		
}
add_action( 'pre_insert_term', function ( $term, $taxonomy ){
	global $exwf_fist_create;
    return ( 'exwf_odmethod' === $taxonomy && $exwf_fist_create!=true )
        ? new WP_Error( 'term_addition_blocked', esc_html__( 'You cannot add terms to this taxonomy', 'woocommerce-food' ) )
        : $term;
}, 0, 2 );

if(!function_exists('exwf_query_by_menu_odmt')){
    function exwf_query_by_menu_odmt($args){
    	$order_method = WC()->session->get( '_user_order_method' );
    	if($order_method==''){
    		$order_method ='delivery';
    	}
    	if($order_method!=''){
    		if(!isset($args['tax_query']) || !is_array($args['tax_query'])){
				$args['tax_query'] = array();
			}
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][] = 
		        array(
		            'taxonomy' => 'exwf_odmethod',
		            'field'    => 'slug',
		            'terms'    => $order_method,
		    );
		}
        return $args;
     }
}
add_filter( 'exwoofood_query', 'exwf_query_by_menu_odmt' );
add_filter( 'exwf_ajax_query_args', 'exwf_query_by_menu_odmt' );
add_filter( 'exwf_ajax_filter_query_args', 'exwf_query_by_menu_odmt' );

add_action( 'woocommerce_checkout_process', 'exwf_if_product_is_not_inmethod' );
function exwf_if_product_is_not_inmethod(){
	$method = WC()->session->get( '_user_order_method' );
	$method = $method !='' ? $method : 'delivery';
	foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
		$product_id = $values['product_id'];
		if (has_term( '', 'exwf_odmethod', $product_id ) && !has_term( $method, 'exwf_odmethod', $product_id ) ) {
			$title = $title!='' ? $title.', '.get_the_title($product_id) : get_the_title($product_id);
    		WC()->cart->remove_cart_item( $cart_item_key );
		}
	}
	if($title!=''){
		$title = sprintf( esc_html__('The food "%s" has been removed because it does not exist in this order method, please refresh page and try again','woocommerce-food' ) ,$title);
		wc_add_notice(  $title,'error');
		return;
	}
}
add_action( 'admin_init', 'exwf_force_create_tern_method', 1 );
function exwf_force_create_tern_method(){
	if(current_user_can( 'manage_options' ) && isset($_GET['create_mt']) && $_GET['create_mt'] =='yes'){
		global $exwf_fist_create;
		$exwf_fist_create = true;
		$term = term_exists( 'delivery', 'exwf_odmethod' );
		if ( $term == null ) {
		    wp_insert_term('Delivery', 'exwf_odmethod', array(
			    'slug' => 'delivery',
			    'description' => ''
			    )
			);
		}
		$term_takeaway = term_exists( 'takeaway', 'exwf_odmethod' );
		if ( $term_takeaway == null ) {
		    wp_insert_term('Takeaway', 'exwf_odmethod', array(
			    'slug' => 'takeaway',
			    'description' => ''
			    )
			);
		}
		$term_dinein = term_exists( 'dinein', 'exwf_odmethod' );
		if ( $term_dinein == null ) {
		    wp_insert_term('Dinein', 'exwf_odmethod', array(
			    'slug' => 'dinein',
			    'description' => ''
			    )
			);
		}
		$exwf_fist_create = false;
	}
}