<?php
function exwoofood_shortcode_order_button( $atts ) {
	if(phpversion()>=7){$atts = (array)$atts;}
	if(is_admin()&& !defined( 'DOING_AJAX' ) || (defined('REST_REQUEST') && REST_REQUEST)){ return;}
	$html_cart ='';
	$cart_enable  = isset($atts['cart_enable']) ? $atts['cart_enable'] : '';
	if($cart_enable !='no') {
    	global $excart_html;
    	if($excart_html != 'on' || $cart_enable =='yes'){
    		$excart_html = 'on';
        	$html_cart = exwoofood_woo_cart_icon_html($cart_enable);
        }
    }
	global $ID;
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'ex-'.rand(10,9999);
	if(!isset($atts['ID'])){$atts['ID']= $ID;}
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$product_id   = isset($atts['product_id']) ? str_replace(' ', '', $atts['product_id']) : '';
	$show_price   = isset($atts['show_price']) ? str_replace(' ', '', $atts['show_price']) : '';
	$enable_modal = isset($atts['enable_modal']) ? $atts['enable_modal'] : '';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$enb_mnd =  isset($atts['enb_mnd']) ? $atts['enb_mnd'] :'';
	// remove space
	$product_id = preg_replace('/\s+/', '', $product_id);
	if(!is_numeric($product_id)){ return;}
	$args = exwoofood_query('', 1, '', 'post__in', '', '', '', '', $product_id, '','','','','','');
	if($product_id =='-1'){$args['post__in'] = array('-1');}
	$the_query = new WP_Query( $args );
	ob_start();
	$class = $class." ex-food-plug ";
	$class = $class." ordbutton-".$style;
	if($enable_modal=='no'){
		$class = $class." exfdisable-modal";
	}
	$html_modal ='';
	wp_enqueue_script( 'wc-add-to-cart-variation' );
	if ($enable_modal!='no' && 'yes' === get_option( 'woocommerce_enable_reviews', 'yes' ) ) {
		wp_enqueue_script( 'exwf-reviews' );
	}
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active( 'woocommerce-product-addons/woocommerce-product-addons.php' ) ) {
		$GLOBALS['Product_Addon_Display']->addon_scripts();
	}
	do_action( 'exwoofood_before_shortcode');
	wp_enqueue_style( 'ionicon' );
	?>
	<div class="ex-fdlist exwf-order-button <?php echo esc_attr($class);?>" id ="<?php echo esc_attr($ID);?>">
		<?php
		do_action('exwf_before_shortcode_content',$atts);
		if ( exwoofood_get_option('exwoofood_enable_loc') =='yes' ) {
			$loc_selected = WC()->session->get( 'ex_userloc' );
			if($location!='' && $loc_selected != $location){
				WC()->session->set( 'ex_userloc', $location);
			}
			echo "<input type='hidden' name='food_loc' value='".esc_attr($location)."'/>";
		}
        echo $html_cart!='' ? $html_cart :'';
	    if(function_exists('exwf_select_date_html')){exwf_select_date_html('',$enb_mnd);}   
        ?>
        <div class="parent_grid">	
        <div class="ctgrid">
		<?php
		$num_pg = '';
		$arr_ids = array();
		if ($the_query->have_posts()){ 
			$i=0;
			$it = $the_query->found_posts;
			$num_pg = 1;
			while ($the_query->have_posts()) { $the_query->the_post();
				global $product;
				if($show_price!='no'){
					add_filter( 'woocommerce_product_single_add_to_cart_text', 'exwf_add_price_button' );
					add_filter( 'exwf_order_button_text', 'exwf_add_price_button' );
				}
				$disable_is_visible = apply_filters('exwf_disable_is_visible','no',$product);
				if ($disable_is_visible=='yes' || $product && $product->is_visible() ) {
					$arr_ids[] = get_the_ID();
					echo '<div class="item-grid" data-id="ex_id-'.esc_attr($ID).'-'.get_the_ID().'" data-id_food="'.get_the_ID().'" id="ctc-'.esc_attr($ID).'-'.get_the_ID().'"><a href="'.EX_WPFood_customlink(get_the_ID()).'"></a>';
						exwf_custom_color('grid',$style,'ctc-'.esc_attr($ID).'-'.get_the_ID());
						echo '<div class="exwf-orbt">';exwoofood_booking_button_html(1);echo '</div>';
					echo '</div>';
				}
				if($show_price!='no'){
					remove_filter( 'woocommerce_product_single_add_to_cart_text', 'exwf_add_price_button' );
					remove_filter( 'exwf_order_button_text', 'exwf_add_price_button' );
				}
			}
		}else{} ?>
		</div>
		</div>
		<!-- Modal ajax -->
		<?php global $modal_html;
		if(!isset($modal_html) || $modal_html!='on' || $enable_modal=='yes'){
			$modal_html = 'on';
			echo "<div id='food_modal' class='ex_modal'></div>";
		}?>
		<?php
		exwoofood_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids); 
		?>
	</div>
	<?php
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'ex_wf_ordbutton', 'exwoofood_shortcode_order_button' );
function exwf_add_price_button($text){
	global $product;
	$id_food = $product->get_id();
	$custom_price = get_post_meta( $id_food, 'exwoofood_custom_price', true );
	$price = exwoofood_price_with_currency($id_food);
	if ($custom_price != '') {
		$price = $custom_price;
	}
	return apply_filters('exwf_price_on_button', $text.' ('.strip_tags($price).')',$text);
}
add_action( 'after_setup_theme', 'ex_reg_wfood_order_button_vc' );
function ex_reg_wfood_order_button_vc(){
    if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("Order button", "woocommerce-food"),
	   "base" => "ex_wf_ordbutton",
	   "class" => "",
	   "icon" => "",
	   "controls" => "full",
	   "category" => esc_html__('Woocommerce Food','woocommerce-food'),
	   "params" => array(
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("ID", "woocommerce-food"),
			"param_name" => "product_id",
			"value" => "",
			"description" => esc_html__("Enter specify food ID to display order button", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Enable cart", 'woocommerce-food'),
			 "param_name" => "cart_enable",
			 "value" => array(
			 	esc_html__('Default', 'woocommerce-food') => '',
			 	esc_html__('Yes', 'woocommerce-food') => 'yes',
			 	esc_html__('No', 'woocommerce-food') => 'no',
			 ),
			 "description" => esc_html__("Enable side cart icon", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Show price", 'woocommerce-food'),
			 "param_name" => "show_price",
			 "value" => array(
			 	esc_html__('Yes', 'woocommerce-food') => '',
			 	esc_html__('No', 'woocommerce-food') => 'no',
			 ),
			 "description" => esc_html__("Show price on button", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Class name", "woocommerce-food"),
			"param_name" => "class",
			"value" => "",
			"description" => esc_html__("add a class name and refer to it in custom CSS", "woocommerce-food"),
		  ),
	   )
	));
	}
}