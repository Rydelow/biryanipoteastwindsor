<?php
/**
 * Render openclose Field
 */
function exwfcmb2_render_openclose_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
	// make sure we specify each part of the value we need.
	$value = wp_parse_args( $value, array(
		'open-time' => '',
		'close-time' => '',
	) );
	?>
	<div class="exwf-open-time"><p><label for="<?php echo $field_type->_id( '_open_time' ); ?>"><?php esc_html_e('Opening time','woocommerce-food')?></label></p>
		<?php echo $field_type->input( array(
			'class' => 'cmb2-timepicker text-time',
			'name'  => $field_type->_name( '[open-time]' ),
			'id'    => $field_type->_id( '_open_time' ),
			'value' => $value['open-time'],
			'type'  => 'text',
			'js_dependencies' => array( 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-datetimepicker' ),
			'desc'  => '',
		) ); ?>
	</div>
	<div class="exwf-close-time"><p><label for="<?php echo $field_type->_id( '_close_time' ); ?>'"><?php esc_html_e('Closing time','woocommerce-food')?></label></p>
		<?php echo $field_type->input( array(
			'class' => 'cmb2-timepicker text-time',		
			'name'  => $field_type->_name( '[close-time]' ),
			'id'    => $field_type->_id( '_close_time' ),
			'value' => $value['close-time'],
			'type'  => 'text',
			'js_dependencies' => array( 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-datetimepicker' ),
			'desc'  => '',
		) ); ?>
	</div>
	<br class="clear">
	<?php
	echo $field_type->_desc( true );

}
add_filter( 'cmb2_render_openclose', 'exwfcmb2_render_openclose_field_callback', 10, 5 );
function exwfcmb2_sanitize_openclose_callback( $override_value, $value ) {
	echo '<pre>';print_r($value);exit;
	return $value;
}
//add_filter( 'cmb2_sanitize_openclose', 'exwfcmb2_sanitize_openclose_callback', 10, 2 );


add_filter( 'cmb2_sanitize_openclose', 'exwfsanitize' , 10, 5 );
add_filter( 'cmb2_types_esc_openclose', 'exwfescape' , 10, 4 );
function exwfsanitize( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {

	// if not repeatable, bail out.
	if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
		return $check;
	}

	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_filter( array_map( 'sanitize_text_field', $val ) );
	}

	return array_filter( $meta_value );
}

function exwfescape( $check, $meta_value, $field_args, $field_object ) {
	// if not repeatable, bail out.
	if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
		return $check;
	}

	foreach ( $meta_value as $key => $val ) {
		$meta_value[ $key ] = array_filter( array_map( 'esc_attr', $val ) );
	}

	return array_filter( $meta_value );
}

// Delivery Time  option

function exwfcmb2_render_timedelivery_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
	// make sure we specify each part of the value we need.
	$value = wp_parse_args( $value, array(
		'start-time' => '',
		'end-time' => '',
		'name-ts' => '',
		'max-odts' => '',
		'ship-fee' => '',
		'disable-slot' => '',
	) );
	$ship_bytime = exwoofood_get_option('exwoofood_shipfee_bytime','exwoofood_shpping_options');
	$disable_sl = exwoofood_get_option('exwoofood_disable_tslot','exwoofood_advanced_options');
	$class = $ship_bytime=='yes' ? 'fee-bytimeslot' : '';
	if($disable_sl=='yes'){$class .= ' disable-slt';}
	?>
	<div class="exwf-timeslots <?php echo esc_attr($class);?>">
		<div class="exwf-open-time"><p><label for="<?php echo $field_type->_id( '_st_time' ); ?>"><?php esc_html_e('Start time','woocommerce-food')?></label></p>
			<?php echo $field_type->input( array(
				'class' => 'cmb2-timepicker text-time',
				'name'  => $field_type->_name( '[start-time]' ),
				'id'    => $field_type->_id( '_st_time' ),
				'value' => $value['start-time'],
				'type'  => 'text',
				'js_dependencies' => array( 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-datetimepicker' ),
				'desc'  => '',
			) ); ?>
		</div>
		<div class="exwf-close-time"><p><label for="<?php echo $field_type->_id( '_ed_time' ); ?>'"><?php esc_html_e('End time','woocommerce-food')?></label></p>
			<?php echo $field_type->input( array(
				'class' => 'cmb2-timepicker text-time',		
				'name'  => $field_type->_name( '[end-time]' ),
				'id'    => $field_type->_id( '_ed_time' ),
				'value' => $value['end-time'],
				'type'  => 'text',
				'js_dependencies' => array( 'jquery-ui-core', 'jquery-ui-datepicker', 'jquery-ui-datetimepicker' ),
				'desc'  => '',
			) ); ?>
		</div>
		<div class="exwf-name-time"><p><label for="<?php echo $field_type->_id( '_name_ts' ); ?>'"><?php esc_html_e('Name of time slot','woocommerce-food')?></label></p>
			<?php echo $field_type->input( array(
				'class' => 'regular-text',		
				'name'  => $field_type->_name( '[name-ts]' ),
				'id'    => $field_type->_id( '_name_ts' ),
				'value' => $value['name-ts'],
				'type'  => 'text',
				'desc'  => '',
			) ); ?>
		</div>
		<div class="exwf-max-order"><p><label for="<?php echo $field_type->_id( '_max_odts' ); ?>'"><?php esc_html_e('Max number of order','woocommerce-food')?></label></p>
			<?php echo $field_type->input( array(
				'class' => 'regular-text',		
				'name'  => $field_type->_name( '[max-odts]' ),
				'id'    => $field_type->_id( '_max_odts' ),
				'value' => $value['max-odts'],
				'type'  => 'text',
				'desc'  => '',
			) ); ?>
		</div>
		<?php if($ship_bytime=='yes'){?>
			<div class="exwf-shipping-fee" style="display: none;"><p><label for="<?php echo $field_type->_id( '_ship_fee' ); ?>'"><?php esc_html_e('Shipping fee','woocommerce-food')?></label></p>
				<?php echo $field_type->input( array(
					'class' => 'regular-text',		
					'name'  => $field_type->_name( '[ship-fee]' ),
					'id'    => $field_type->_id( '_ship_fee' ),
					'value' => $value['ship-fee'],
					'type'  => 'text',
					'desc'  => '',
				) ); ?>
			</div>
		<?php }?>
		<?php if($disable_sl=='yes'){?>
			<div class="exwf-disable-slot"><p><label for="<?php echo $field_type->_id( '_disable_sl' ); ?>'"><?php esc_html_e('Disable ?','woocommerce-food')?></label></p>
				<?php 
				$arr_ck = array(
					'class' => 'checkbox-dis',		
					'name'  => $field_type->_name( '[disable-slot]' ),
					'id'    => $field_type->_id( '_disable_sl' ),
					'value' => $value['disable-slot'],
					'type'  => 'checkbox',
					'desc'  => '',
				);
				if($value['disable-slot']=='1'){
					$arr_ck['checked'] = 'checked';
				}
				echo $field_type->input($arr_ck);
				echo $field_type->input( array(
					'class' => 'regular-text',		
					'name'  => $field_type->_name( '[disable-slot]' ),
					'id'    => $field_type->_id( '_disable_sl' ),
					'value' => $value['disable-slot'],
					'type'  => 'hidden',
					'desc'  => '',
				) ); ?>
			</div>
		<?php }?>
	</div>
	<br class="clear">
	<?php
	echo $field_type->_desc( true );

}
add_filter( 'cmb2_render_timedelivery', 'exwfcmb2_render_timedelivery_field_callback', 10, 5 );

add_filter( 'cmb2_sanitize_timedelivery', 'exwfsanitize' , 10, 5 );
add_filter( 'cmb2_types_esc_timedelivery', 'exwfescape' , 10, 4 );
