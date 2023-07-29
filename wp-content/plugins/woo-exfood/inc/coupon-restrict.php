<?php
add_action( 'woocommerce_coupon_options_usage_restriction','exwf_coupon_meta', 99, 2 );
function exwf_coupon_meta($coupon_id, $coupon){
	$method_enable = exwf_get_method_enable();
	if($method_enable =='' || empty($method_enable)){
		return;
	}
	$cp_methods = get_post_meta($coupon_id, 'exwf_cp_method',true);
	?>
	<p class="form-field">
		<label for="exwf_cp_method"><?php _e( 'Order methods', 'woocommerce-food' ); ?></label>
		<select id="exwf_cp_method" name="exwf_cp_method[]" style="width: 50%;"  class="wc-enhanced-select" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Any method', 'woocommerce' ); ?>">
			<?php
			if ( $method_enable ) {
				foreach ( $method_enable as $method ) {
					$name_method = $method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : esc_html__('Delivery','woocommerce-food'));
					echo '<option value="' . esc_attr( $method ) . '"' . wc_selected( $method, $cp_methods ) . '>' . esc_html( $name_method ) . '</option>';
				}
			}
			?>
		</select>
		<?php echo wc_help_tip( __( 'Select order methods to apply for this coupon, leave blank to apply for all methods', 'woocommerce-food' ) ); ?>
	</p>
	<?php
}
add_action( 'woocommerce_coupon_options_save', 'exwf_save_coupon_method',99,2);
function exwf_save_coupon_method($post_id, $coupon){
	$cp_methods = isset( $_POST['exwf_cp_method'] ) ? (array) $_POST['exwf_cp_method'] : array();
	update_post_meta( $post_id, 'exwf_cp_method', (!empty($cp_methods) ? array_filter( array_map( 'trim', $cp_methods )): '') );
}
add_filter( 'woocommerce_coupon_is_valid', 'exwf_coupon_by_mt_validate', 99, 2);
function exwf_coupon_by_mt_validate($valid, $coup){
	$coupon_id = wc_get_coupon_id_by_code( $coup->get_code() );
	if( $coupon_id && isset(WC()->session) ) {
		$cp_methods = get_post_meta($coupon_id, 'exwf_cp_method',true);
		if(is_array($cp_methods) && !empty($cp_methods) ) {
			$method = WC()->session->get( '_user_order_method' );
			$method = $method !='' ? $method : 'delivery'; 
			if(!in_array($method,$cp_methods)){
				$valid = false;
			}
		}
	}
	return $valid;
}