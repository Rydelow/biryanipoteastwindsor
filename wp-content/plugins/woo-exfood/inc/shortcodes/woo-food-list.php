<?php
function exwoofood_shortcode_list( $atts ) {
	if(phpversion()>=7){
		$atts = (array)$atts;
	}
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
	global $ID,$number_excerpt,$img_size,$location,$hide_atc;
	$ID = isset($atts['ID']) && $atts['ID'] !=''? $atts['ID'] : 'ex-'.rand(10,9999);
	if(!isset($atts['ID'])){$atts['ID']= $ID;}
	$style = isset($atts['style']) && $atts['style'] !='' ? $atts['style'] : '1';
	$column = isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '';
	$posttype   = isset($atts['posttype']) && $atts['posttype']!='' ? $atts['posttype'] : 'ex_food';
	$ids   = isset($atts['ids']) ? str_replace(' ', '', $atts['ids']) : '';
	$taxonomy  = isset($atts['taxonomy']) ? $atts['taxonomy'] : '';
	$cat   = isset($atts['cat']) ? str_replace(' ', '', $atts['cat']) : '';
	$order_cat   = isset($atts['order_cat']) ? $atts['order_cat'] : '';
	$tag  = isset($atts['tag']) ? $atts['tag'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$posts_per_page   = isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3';
	$order  = isset($atts['order']) ? $atts['order'] : '';
	$orderby  = isset($atts['orderby']) ? $atts['orderby'] : '';
	$meta_key  = isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value  = isset($atts['meta_value']) ? $atts['meta_value'] : '';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	$page_navi  = isset($atts['page_navi']) ? $atts['page_navi'] : '';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$menu_filter   = isset($atts['menu_filter']) ? $atts['menu_filter'] : 'hide';
	$active_filter   = isset($atts['active_filter']) ? $atts['active_filter'] : '';
	$menu_pos   = isset($atts['menu_pos']) ? $atts['menu_pos'] : '';
	$enable_modal = isset($atts['enable_modal']) ? $atts['enable_modal'] : '';
	$paged = get_query_var('paged')?get_query_var('paged'):(get_query_var('page')?get_query_var('page'):1);
	$featured =  isset($atts['featured']) ? $atts['featured'] :'';
	$filter_style =  isset($atts['filter_style']) ? $atts['filter_style'] :'';
	$show_count =  isset($atts['show_count']) ? $atts['show_count'] :'';
	$hide_ftall =  isset($atts['hide_ftall']) ? $atts['hide_ftall'] :'';
	$enable_search =  isset($atts['enable_search']) ? $atts['enable_search'] :'';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'';
	$hide_atc =  isset($atts['hide_atc']) ? $atts['hide_atc'] :'';
	$enb_mnd =  isset($atts['enb_mnd']) ? $atts['enb_mnd'] :'';
	if($hide_atc=='yes'){$atts['enable_mtod'] = 'no';}
	if($hide_ftall=='yes' && $active_filter ==''){
		if($cat!=''){
			$cats = explode(",",$cat);
			$active_filter = $cats[0];
		}else{
			$args_ca = array('hide_empty'=> true,'parent'=> '0');
			if ($order_cat == 'yes') {
				$args_ca['meta_key'] = 'exwoofood_menu_order';
				$args_ca['orderby'] = 'meta_value_num';
			}
			$terms = get_terms('product_cat', $args_ca);
			$loc_selected = exwf_get_loc_selected();
			$exclude = array();
			if($loc_selected!=''){
				$exclude = get_term_meta( $loc_selected, 'exwp_loc_hide_menu', true );
			}
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
				foreach ( $terms as $term ) {
					if(is_array($exclude) && !empty($exclude) && in_array($term->slug, $exclude)){
	            		// if exclude
	            	}else{
						$active_filter = $term->slug;
						break;
					}
				}
			}
		}
	}
	// remove space
	$cat = preg_replace('/\s+/', '', $cat);
	$ids = preg_replace('/\s+/', '', $ids);
	if(isset($_GET['menu']) && $_GET['menu']!=''){
		$active_filter = $_GET['menu'];
	}
	
	$args = exwoofood_query($posttype, $posts_per_page, $order, $orderby, $cat, $tag, $taxonomy, $meta_key, $ids, $meta_value,'','',$active_filter,$featured,$location);
	$the_query = new WP_Query( $args );
	ob_start();
	$html_modal ='';
	$class = $class." ex-food-plug ";
	$class = $class." list-style-".$style;
	if($column!=''){ $class = $class." column-".$column;}
	$class = $hide_atc=='yes' ? $class.' exwf-listing-mode' : $class;
	if (!exwf_check_open_close_time()) {
		//$class = $class." exfd-out-open-time";
	}
	
	if($menu_filter=="show" && $menu_pos == 'left'){
		$class = $class." category_left";
	}
	if($enable_modal=='no'){
		$class = $class." exfdisable-modal";
	}
	//$locations ='';
	wp_enqueue_script( 'wc-add-to-cart-variation' );
	if ($enable_modal!='no' && 'yes' === get_option( 'woocommerce_enable_reviews', 'yes' ) ) {
		wp_enqueue_script( 'exwf-reviews' );
	}
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (is_plugin_active( 'woocommerce-product-addons/woocommerce-product-addons.php' ) ) {
		$GLOBALS['Product_Addon_Display']->addon_scripts();
	}
	wp_enqueue_style( 'ionicon' );
	do_action( 'exwoofood_before_shortcode');
	?>
	<div class="ex-fdlist list-layout <?php echo esc_attr($class);?>" id ="<?php echo esc_attr($ID);?>">
		<?php 
		do_action('exwf_before_shortcode_content',$atts);
		if ( exwoofood_get_option('exwoofood_enable_loc') =='yes' ) {
			$loc_selected = WC()->session->get( 'ex_userloc' );
			if($location!='' && $loc_selected != $location){
				WC()->session->set( 'ex_userloc', $location);
			}
			echo "<input type='hidden' name='food_loc' value='".esc_attr($location)."'/>";
		}
		exwf_search_html($enable_search);
		if($location!='OFF'){
			exwoofood_select_location_html($location);
		}
		echo $html_cart!='' ? $html_cart :'';?>
        <?php if($menu_filter=="show") {
        	exwoofood_search_form_html($cat,$order_cat, $menu_pos,$active_filter,$filter_style,$hide_ftall,$show_count);
        }
        ?>
        
		<div class="ctlist">
		<?php 
        if($active_filter!=''){
        	
			$term = get_term_by('slug', $active_filter, 'product_cat');
			if($term->description!=''){
				echo '<div class="exwf-dcat" style="display:block;">'.$term->description.'</div>';
			}
		}
		if(function_exists('exwf_select_date_html')){exwf_select_date_html('',$enb_mnd);}   
        ?>	
		<?php
		$num_pg = '';
		$arr_ids = array();
		if ($the_query->have_posts()){
			$i=0;
			$it = $the_query->found_posts;
			if($it < $count || $count=='-1'){ $count = $it;}
			if($count  > $posts_per_page){
				$num_pg = ceil($count/$posts_per_page);
				$it_ep  = $count%$posts_per_page;
			}else{
				$num_pg = 1;
			}
			$arr_ids = array();?>
				<?php 
				while ($the_query->have_posts()) { $the_query->the_post();
					global $product;
					$disable_is_visible = apply_filters('exwf_disable_is_visible','no',$product);
					if ($disable_is_visible=='yes' || $product && $product->is_visible() ) {
						$arr_ids[] = get_the_ID();
						$i++;
						if(($num_pg == $paged) && $num_pg!='1'){
							if($i > $it_ep){ break;}
						}
						echo '<div class="fditem-list item-grid" data-id="ex_id-'.esc_attr($ID).'-'.get_the_ID().'" data-id_food="'.get_the_ID().'" id="ctc-'.esc_attr($ID).'-'.get_the_ID().'"> ';
							exwf_custom_color('list',$style,'ctc-'.esc_attr($ID).'-'.get_the_ID());
							?>
							<div class="exp-arrow" >
								<?php 
								exwoofood_template_plugin('list-'.$style,1);
								?>
							<div class="exfd_clearfix"></div>
							</div>
							<?php
						echo '</div>';
					}
				}
				?>
				<!-- </div>
			</div> -->
			<?php 
		}else{ echo '<span class="exwf-no-rs">'.esc_html__('No matching records found','woocommerce-food').'</span>';} ?>
		</div>
		<!-- Modal ajax -->
		<?php global $modal_html;
		if(!isset($modal_html) || $modal_html!='on' || $enable_modal=='yes'){
			$modal_html = 'on';
			echo "<div id='food_modal' class='ex_modal'></div>";
		}?>
        <?php
		if($page_navi=='loadmore'){
			exwoofood_ajax_navigate_html($ID,$atts,$num_pg,$args,$arr_ids); 
		}else{ ?>
			<div class="exfd-pagination-parent">
			<?php exwoofood_page_number_html($the_query,$ID,$atts,$num_pg,$args,$arr_ids); ?>
			</div>
		<?php }
		?>
	</div>
	
	<?php
	wp_reset_postdata();
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'ex_wf_list', 'exwoofood_shortcode_list' );
add_action( 'after_setup_theme', 'ex_reg_wfood_list_vc' );
function ex_reg_wfood_list_vc(){
    if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("Food List", "woocommerce-food"),
	   "base" => "ex_wf_list",
	   "class" => "",
	   "icon" => "icon-grid",
	   "controls" => "full",
	   "category" => esc_html__('Woocommerce Food','woocommerce-food'),
	   "params" => array(
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Style", 'woocommerce-food'),
			 "param_name" => "style",
			 "value" => array(
				esc_html__('1', 'woocommerce-food') => '1',
				esc_html__('2', 'woocommerce-food') => '2',
				esc_html__('3', 'woocommerce-food') => '3',
			 ),
			 "description" => esc_html__('Select style of grid', 'woocommerce-food')
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Columns", 'woocommerce-food'),
			 "param_name" => "column",
			 "value" => array(
				esc_html__('1 column', 'woocommerce-food') => '',
				esc_html__('2 columns', 'woocommerce-food') => '2',
				esc_html__('3 columns', 'woocommerce-food') => '3',
				esc_html__('4 columns', 'woocommerce-food') => '4',
			 ),
			 "description" => esc_html__('Select number column of grid', 'woocommerce-food')
		  ), 
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Count", "woocommerce-food"),
			"param_name" => "count",
			"value" => "",
			"description" => esc_html__("Enter number of foods to show", 'woocommerce-food'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Food per page", "woocommerce-food"),
			"param_name" => "posts_per_page",
			"value" => "",
			"description" => esc_html__("Enter Number food per page", 'woocommerce-food'),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("IDs", "woocommerce-food"),
			"param_name" => "ids",
			"value" => "",
			"description" => esc_html__("Specify food IDs to retrieve", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Menu", "woocommerce-food"),
			"param_name" => "cat",
			"value" => "",
			"description" => esc_html__("List of cat ID (or slug), separated by a comma", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order", 'woocommerce-food'),
			 "param_name" => "order",
			 "value" => array(
			 	esc_html__('DESC', 'woocommerce-food') => 'DESC',
				esc_html__('ASC', 'woocommerce-food') => 'ASC',
			 ),
			 "description" => ''
		  ),
		  array(
		  	 "admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Order by", 'woocommerce-food'),
			 "param_name" => "orderby",
			 "value" => array(
			 	esc_html__('Date', 'woocommerce-food') => 'date',
			 	esc_html__('Custom order field', 'woocommerce-food') => 'order_field',
				esc_html__('ID', 'woocommerce-food') => 'ID',
				esc_html__('Author', 'woocommerce-food') => 'author',
			 	esc_html__('Title', 'woocommerce-food') => 'title',
				esc_html__('Name', 'woocommerce-food') => 'name',
				esc_html__('Modified', 'woocommerce-food') => 'modified',
			 	esc_html__('Parent', 'woocommerce-food') => 'parent',
				esc_html__('Random', 'woocommerce-food') => 'rand',
				esc_html__('Menu order', 'woocommerce-food') => 'menu_order',
				esc_html__('Meta value', 'woocommerce-food') => 'meta_value',
				esc_html__('Meta value num', 'woocommerce-food') => 'meta_value_num',
				esc_html__('Post__in', 'woocommerce-food') => 'post__in',
				esc_html__('None', 'woocommerce-food') => 'none',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta key", "woocommerce-food"),
			"param_name" => "meta_key",
			"value" => "",
			"description" => esc_html__("Enter meta key to query", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Meta value", "woocommerce-food"),
			"param_name" => "meta_value",
			"value" => "",
			"description" => esc_html__("Enter meta value to query", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number of Excerpt ( short description)", "woocommerce-food"),
			"param_name" => "number_excerpt",
			"value" => "",
			"description" => esc_html__("Enter number of Excerpt, enter:0 to disable excerpt", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Page navi", 'woocommerce-food'),
			 "param_name" => "page_navi",
			 "value" => array(
			 	esc_html__('Number', 'woocommerce-food') => '',
				esc_html__('Load more', 'woocommerce-food') => 'loadmore',
			 ),
			 "description" => esc_html__("Select type of page navigation", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Menu filter", 'woocommerce-food'),
			 "param_name" => "menu_filter",
			 "value" => array(
			 	esc_html__('Hide', 'woocommerce-food') => 'hide',
			 	esc_html__('Show', 'woocommerce-food') => 'show',
			 ),
			 "description" => esc_html__("Select show or hide Menu filter", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Menu/Category count", 'woocommerce-food'),
			 "param_name" => "show_count",
			 "value" => array(
			 	esc_html__('No', 'woocommerce-food') => '',
			 	esc_html__('Yes', 'woocommerce-food') => 'yes',
			 ),
			 "description" => esc_html__("Select Yes to show menu/category count", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Menu filter style", 'woocommerce-food'),
			 "param_name" => "filter_style",
			 "value" => array(
			 	esc_html__('Default', 'woocommerce-food') => '',
			 	esc_html__('Icon', 'woocommerce-food') => 'icon',
			 ),
			 "description" => esc_html__("Select Menu filter style", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Active filter", "woocommerce-food"),
			"param_name" => "active_filter",
			"value" => "",
			"description" => esc_html__("Enter slug of menu to active", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "dropdown",
			"heading" => esc_html__("Order Menu Filter", "woocommerce-food"),
			"param_name" => "order_cat",
			"description" => esc_html__("Order Menu with custom order", "woocommerce-food"),
			"value" => array(
			 	esc_html__('No', 'woocommerce-food') => '',
				esc_html__('Yes', 'woocommerce-food') => 'yes',
			 ),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Menu filter Position", 'woocommerce-food'),
			 "param_name" => "menu_pos",
			 "value" => array(
			 	esc_html__('Top', 'woocommerce-food') => 'top',
			 	esc_html__('Left', 'woocommerce-food') => 'left',
			 ),
			 "description" => esc_html__("Select posstion of menu filter", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "dropdown",
			"heading" => esc_html__("Hide 'All' Filter", "woocommerce-food"),
			"param_name" => "hide_ftall",
			"description" => esc_html__("Select 'yes' to disalbe 'All' filter", "woocommerce-food"),
			"value" => array(
			 	esc_html__('No', 'woocommerce-food') => '',
				esc_html__('Yes', 'woocommerce-food') => 'yes',
			 ),
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
			 "heading" => esc_html__("Enable Live search", 'woocommerce-food'),
			 "param_name" => "enable_search",
			 "value" => array(
			 	esc_html__('No', 'woocommerce-food') => '',
			 	esc_html__('Yes', 'woocommerce-food') => 'yes',
			 ),
			 "description" => esc_html__("Enable ajax live search", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Show only Featured food", 'woocommerce-food'),
			 "param_name" => "featured",
			 "value" => array(
			 	esc_html__('No', 'woocommerce-food') => '',
				esc_html__('Yes', 'woocommerce-food') => '1',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Hide add to cart form", 'woocommerce-food'),
			 "param_name" => "hide_atc",
			 "value" => array(
			 	esc_html__('No', 'woocommerce-food') => 'no',
				esc_html__('Yes', 'woocommerce-food') => 'yes',
			 ),
			 "description" => ''
		  ),
		  array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Enable modal", 'woocommerce-food'),
			 "param_name" => "enable_modal",
			 "value" => array(
			 	esc_html__('Default', 'woocommerce-food') => '',
				esc_html__('Yes', 'woocommerce-food') => 'yes',
				esc_html__('No', 'woocommerce-food') => 'no',
			 ),
			 "description" => ''
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