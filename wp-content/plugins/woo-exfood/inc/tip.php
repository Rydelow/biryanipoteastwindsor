<?php
// tip setting
add_action( 'cmb2_admin_init', 'exwoofood_register_setting_tip',24 );
function exwoofood_register_setting_tip(){
	$args = array(
		'id'           => 'exwoofood_tip',
		'menu_title'   => '',
		'object_types' => array( 'options-page' ),
		'option_key'   => 'exwoofood_tip_options',
		//'parent_slug'  => 'edit.php?post_type=product',
		'tab_group'    => 'exwoofood_options',
		'capability'    => 'manage_woocommerce',
		'tab_title'    => esc_html__('Order Tip','woocommerce-food'),
	);
	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'exwoofood_options_display_with_tabs';
	}
	$tip_options = new_cmb2_box( $args );
	$tip_options->add_field( array(
		'name'             => esc_html__( 'Enable Order Tip', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enable Tip feature on checkout page', 'woocommerce-food' ),
		'id'               => 'exwoofood_enb_tip',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'' => esc_html__( 'No', 'woocommerce-food' ),
			'yes'   => esc_html__( 'Yes', 'woocommerce-food' ),
		),
		'before_row'     => 'exwf_ot_add_adv_time_html',
	) );
	$tip_options->add_field( array(
		'name'             => esc_html__( 'Order Tip position', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Choose position of order tip', 'woocommerce-food' ),
		'id'               => 'exwoofood_pos_tip',
		'type'             => 'select',
		'show_option_none' => false,
		'options'          => array(
			'' => esc_html__( 'Before checkout form', 'woocommerce-food' ),
			'woocommerce_checkout_after_customer_details'   => esc_html__( 'After customer details', 'woocommerce-food' ),
			'woocommerce_checkout_order_review'   => esc_html__( 'After order review', 'woocommerce-food' ),
		),
		'before_row'     => '',
	) );
	$tip_options->add_field( array(
		'name'             => esc_html__( 'Title', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enter tip title, default: Tips', 'woocommerce-food' ),
		'id'               => 'exwoofood_tip_title',
		'type'             => 'text',
		'escape_cb'   => '',
	) );
	$tip_options->add_field( array(
		'name'             => esc_html__( 'Button label', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enter label of Button, default: Add', 'woocommerce-food' ),
		'id'               => 'exwoofood_tip_lbad',
		'type'             => 'text',
		'escape_cb'   => '',
	) );
	$tip_options->add_field( array(
		'name'             => esc_html__( 'Remove Button label', 'woocommerce-food' ),
		'desc'             => esc_html__( 'Enter label of Button, default: Remove', 'woocommerce-food' ),
		'id'               => 'exwoofood_tip_lbrm',
		'type'             => 'text',
		'escape_cb'   => '',
	) );
	
}
add_action( 'exwf_admin_adv_settings_tab_html', 'exwf_adv_tip_settings_tab_html',30,2 );
function exwf_adv_tip_settings_tab_html($html,$tab){
	$html .= ' | <a href="?page=exwoofood_tip_options" class="'.($tab=='exwoofood_tip_options' ? 'current' : '').'">'.esc_html__('Order Tip','woocommerce-food').'</a>';
	return $html; 
}
$tip_alloptions = get_option( 'exwoofood_tip_options' );
$pos_of_tip = isset($tip_alloptions['exwoofood_pos_tip']) && $tip_alloptions['exwoofood_pos_tip']!='' ?  $tip_alloptions['exwoofood_pos_tip'] : 'woocommerce_before_checkout_form';
//$t = exwoofood_get_option('exwoofood_pos_tip','exwoofood_tip_options');
add_action( $pos_of_tip, 'exwf_tip_form_html',15 );
function exwf_tip_form_html(){
	$tip = exwoofood_get_option('exwoofood_enb_tip','exwoofood_tip_options');
	if($tip!='yes'){
		return;
	}
	$title = exwoofood_get_option('exwoofood_tip_title','exwoofood_tip_options');
	$addlb = exwoofood_get_option('exwoofood_tip_lbad','exwoofood_tip_options');
	$remlb = exwoofood_get_option('exwoofood_tip_lbrm','exwoofood_tip_options');
	wp_enqueue_script( 'exwf-tip', EX_WOOFOOD_PATH.'js/tip.js', array( 'jquery' ),'1.0' );
	?>
	<div class="exwf-tip-form">
		<?php if($title!='off'){
			echo '<div class="exwf-tip-title">'.($title==''? esc_html__('Tips','woocommerce-food') : $title).'</div>';
		}
		$plachd = apply_filters('exwf_tip_hoder', '('.get_woocommerce_currency_symbol().')')
		?>
		<input type="text" name="exwf-tip" placeholder="<?php echo esc_attr($plachd);?>">
		<input type="button" name="exwf-add-tip" value="<?php echo ($addlb!=''? $addlb : esc_html__('Add','woocommerce-food')); ?>">
		<input type="button" name="exwf-remove-tip" value="<?php echo ($remlb!='' ? $remlb : esc_html__('Remove','woocommerce-food')); ?>">
		<div class="exwf-tip-error"><?php esc_html_e('Please enter a valid number','woocommerce-food');?></div>
    </div>
	<?php
}

add_action( 'wp_ajax_exwf_update_tip', 'ajax_exwf_update_tip' );
add_action( 'wp_ajax_nopriv_exwf_update_tip', 'ajax_exwf_update_tip' );
function ajax_exwf_update_tip(){
	$tip = $_POST['tip'];
	WC()->session->set( '_user_tip_fee' , $tip);
}
add_action( 'woocommerce_cart_calculate_fees','exwf_update_tip_fee' ); 
function exwf_update_tip_fee() { 
	global $woocommerce; 
	if ( is_admin() && ! defined( 'DOING_AJAX' ) ) 
	return;
	$_user_tip_fee = WC()->session->get( '_user_tip_fee' );
	if($_user_tip_fee > 0){
		$tax_fee = apply_filters('exwf_tip_fee_tax',false);
		$title = exwoofood_get_option('exwoofood_tip_title','exwoofood_tip_options');
		$woocommerce->cart->add_fee( ($title=='' || $title=='off'? esc_html__('Tips','woocommerce-food') : $title), $_user_tip_fee, $tax_fee, '' );
	}  
}