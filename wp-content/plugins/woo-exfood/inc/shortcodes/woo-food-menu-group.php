<?php
function exwoofood_shortcode_menu_group( $atts ) {
	if(phpversion()>=7){
		$atts = (array)$atts;
	}
	if(is_admin()&& !defined( 'DOING_AJAX' ) || (defined('REST_REQUEST') && REST_REQUEST)){ return;}
	global $ID,$number_excerpt,$img_size,$location;
	
	$layout = isset($atts['layout']) && $atts['layout'] !=''? $atts['layout'] : 'grid';
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$column = isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '1';
	//$ids   = isset($atts['ids']) ? str_replace(' ', '', $atts['ids']) : '';
	$cat   = isset($atts['cat']) ? str_replace(' ', '', $atts['cat']) : '';
	$order_cat   = isset($atts['order_cat']) ? $atts['order_cat'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$menu_visible   = isset($atts['menu_visible']) &&  $atts['menu_visible'] !=''? $atts['menu_visible'] : '';
	$menu_onscroll   = isset($atts['menu_onscroll']) &&  $atts['menu_onscroll'] !=''? $atts['menu_onscroll'] : $menu_visible;
	$heading_style   = isset($atts['heading_style']) ? $atts['heading_style'] : '';
	$show_count =  isset($atts['show_count']) ? $atts['show_count'] :'';
	$menu_filter   = isset($atts['menu_filter']) ? $atts['menu_filter'] : 'hide';
	$posts_per_page   = isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3';
	$order  = isset($atts['order']) ? $atts['order'] : '';
	$orderby  = isset($atts['orderby']) ? $atts['orderby'] : '';
	$meta_key  = isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value  = isset($atts['meta_value']) ? $atts['meta_value'] : '';
	$page_navi  = isset($atts['page_navi']) ? $atts['page_navi'] : '';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$cart_enable  = isset($atts['cart_enable']) ? $atts['cart_enable'] : '';
	$enable_modal = isset($atts['enable_modal']) ? $atts['enable_modal'] : '';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'';
	$featured =  isset($atts['featured']) ? $atts['featured'] :'';
	$filter_style =  isset($atts['filter_style']) ? $atts['filter_style'] :'';
	//$enable_search =  isset($atts['enable_search']) ? $atts['enable_search'] :'';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	/*$autoplay =  isset($atts['autoplay']) ? $atts['autoplay'] :'';
	$loading_effect =  isset($atts['loading_effect']) ? $atts['loading_effect'] :'';
	$infinite =  isset($atts['infinite']) ? $atts['infinite'] :'';*/
	$live_sort =  isset($atts['live_sort']) ? $atts['live_sort'] :'';
	$hide_atc =  isset($atts['hide_atc']) ? $atts['hide_atc'] :'';
	$enable_mtod =  isset($atts['enable_mtod']) ? $atts['enable_mtod'] :'';
	
	ob_start();
	$args = array(
		'hide_empty'        => true,
		'parent'        => '0',
	);
	if($cat !=''){ unset($args['parent']);}
	$cat = $cat!=''? explode(",",$cat) : array();
	if (!empty($cat) && !is_numeric($cat[0])) {
		$args['slug'] = $cat;
		$args['orderby'] = 'slug__in';
	}else if (!empty($cat)) {
		$args['include'] = $cat;
		$args['orderby'] = 'include';
	}
	if ($order_cat == 'yes') {
		$args['meta_key'] = 'exwoofood_menu_order';
		$args['orderby'] = 'meta_value_num';
	}
	global $exwf_mngr;
	$exwf_mngr = get_terms('product_cat', $args);
	$id_sc = 'exwf-mn-'.esc_attr(rand(1,10000000)).'-'.esc_attr(rand(1,10000000));
	$atts['id_sc'] = $id_sc;
	if ( ! empty( $exwf_mngr ) && ! is_wp_error( $exwf_mngr ) ){
		$nb_mn = count($exwf_mngr);
			$mngr_ntag = apply_filters('exwf_mngroup_title_tag','h2');
			$content_html = $filter_html = $filter_slhtml= '';
			$i = 0;
			foreach ( $exwf_mngr as $k_mn =>$term ) {
				$i++;
				if($i==2){
					$enable_mtod ='';
					if($cart_enable !='no'){
						$cart_enable ='';
					}
				}
				$icon_html =  '';
				$_iconsc = get_term_meta( $term->term_id, 'exwoofood_menu_iconsc', true );
	  			if($_iconsc!=''){
	  				$icon_html =  '<span class="exwf-caticon exwf-iconsc">'.$_iconsc.'</span>'; 
	  			}else{
		  			$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
		  			if($thumbnail_id!=''){
						// get the medium-sized image url
						$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
						// Output in img tag
						if(isset($image[0]) && $image[0]!=''){
							$icon_html =  '<span class="exwf-caticon"><img src="' . $image[0] . '" alt="" /></span>'; 
						}
					}
				}
				$t_count ='';
				if($show_count=='yes'){
					$t_count = ' ('.$term->count.')'; 
				}
				$filter_html .= '<a class="filtermngr-item" href="javascript:;" data-menu="'.esc_attr($term->slug).'" data-id="'.esc_attr($id_sc).'">'.esc_attr($term->name).$t_count.'</a>';
				$filter_slhtml .= '<option value="'. esc_attr($term->slug) .'">'. wp_kses_post($term->name) .$t_count.'</option>';
				$content_html .='
				<div class="exwf-mngr-item" data-menu="'.esc_attr($term->slug).'">
					<div class="exwf-mnheading mnheading-'.esc_attr($heading_style).'">
						<'.$mngr_ntag.' class="mn-namegroup"><span>'.$icon_html.$term->name.$t_count.'</span></'.$mngr_ntag.'>
						'.($term->description!='' ? '<div class="mn-desgroup">'.$term->description.'</div>' : '').'
					</div>';
					$content_html .='<div class="exwf-mnlayout">';
					if($layout =='list'){
						$content_html .= do_shortcode('[ex_wf_list column="'.esc_attr($column).'" style="'.esc_attr($style).'" posts_per_page="'.esc_attr($posts_per_page).'" count="'.esc_attr($count).'" cat="'.esc_attr($term->slug).'" orderby="'.esc_attr($orderby).'" order="'.esc_attr($order).'" page_navi="'.esc_attr($page_navi).'" menu_filter="'.esc_attr($menu_filter).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'" meta_key="'.esc_attr($meta_key).'" img_size="'.esc_attr($img_size).'" class="'.esc_attr($class).'" enable_mtod="'.esc_attr($enable_mtod).'" hide_atc="'.esc_attr($hide_atc).'" location="'.esc_attr($location).'" number_excerpt="'.esc_attr($number_excerpt).'"]');
					}else if($layout =='carousel'){
						//$content_html .= do_shortcode('[ex_wf_carousel count="'.esc_attr($count).'" slidesshow="'.esc_attr($column).'" autoplay="'.esc_attr($autoplay).'" loading_effect="'.esc_attr($loading_effect).'" cat="'.esc_attr($term->slug).'" infinite="'.esc_attr($infinite).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'"]');
					}else if($layout =='table'){
						$content_html .= do_shortcode('[ex_wf_table count="'.esc_attr($count).'" posts_per_page="'.esc_attr($posts_per_page).'" page_navi="'.esc_attr($page_navi).'" live_sort="'.esc_attr($live_sort).'" cat="'.esc_attr($term->slug).'" orderby="'.esc_attr($orderby).'" order="'.esc_attr($order).'"  menu_filter="'.esc_attr($menu_filter).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'" meta_key="'.esc_attr($meta_key).'" img_size="'.esc_attr($img_size).'" class="'.esc_attr($class).'" location="'.esc_attr($location).'" enable_mtod="'.esc_attr($enable_mtod).'" hide_atc="'.esc_attr($hide_atc).'" number_excerpt="'.esc_attr($number_excerpt).'"]');
					}else{
						$content_html .= do_shortcode('[ex_wf_grid column="'.esc_attr($column).'" style="'.esc_attr($style).'" posts_per_page="'.esc_attr($posts_per_page).'" count="'.esc_attr($count).'" cat="'.esc_attr($term->slug).'" orderby="'.esc_attr($orderby).'" order="'.esc_attr($order).'"  page_navi="'.esc_attr($page_navi).'" menu_filter="'.esc_attr($menu_filter).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'" meta_key="'.esc_attr($meta_key).'" img_size="'.esc_attr($img_size).'" class="'.esc_attr($class).'" location="'.esc_attr($location).'" enable_mtod="'.esc_attr($enable_mtod).'" hide_atc="'.esc_attr($hide_atc).'" number_excerpt="'.esc_attr($number_excerpt).'"]');
					}
					$content_html .='
					</div>
				</div>';
				if(is_numeric($menu_visible) && $menu_visible> 0 && $i <=$menu_visible){
					unset($exwf_mngr[$k_mn]);
					if($i ==$menu_visible){
						break;
					}
				}
			}
		$css_class = '';
		if($menu_visible > 0 && $nb_mn > $menu_visible){
			$css_class = 'exwf-mngroup-more';
			/*
			foreach ( $exwf_mngr as $k_mn =>$term ) {
				$filter_html .= '<a class="filtermngr-item" href="javascript:;" data-menu="'.esc_attr($term->slug).'" data-id="'.esc_attr($id_sc).'">'.esc_attr($term->name).'</a>';
				$filter_slhtml .= '<option value="'. esc_attr($term->slug) .'">'. wp_kses_post($term->name) .'</option>';
			}*/
			$filter_html .= '<a class="filtermngr-item exwf-btmore" alt="'.esc_html__('Scroll down to see more!','woocommerce-food').'" href="javascript:;" data-menu="exmmore" data-id="'.esc_attr($id_sc).'">'.esc_html__('More...','woocommerce-food').'</a>';
			$filter_slhtml .= '<option value="exmmore">'. esc_html__('More...','woocommerce-food') .'</option>';
		}
		echo '<div class="exwf-mngroup mngroup-'.esc_attr($layout).' mngroup-st-'.esc_attr($style).' '.esc_attr($css_class).'" id="'.esc_attr($id_sc).'" data-menu="'.esc_attr(json_encode($exwf_mngr)).'" data-sc="'.esc_attr(json_encode($atts)).'">';
			echo '<div class="ex-fdlist exwf-mngrfilter">
			<div class="exfd-filter">
	    		<div class="exfd-filter-group">
	            	<div class="ex-menu-list">';
					echo $filter_html;
			echo '</div>
				<div class="ex-menu-select">
	            	<div>
			            <select name="exfood_menu" data-id="'.esc_attr($id_sc).'">'.$filter_slhtml.'</select>
			        </div>
			    </div>        
				</div></div></div>';
			echo '<div class="exwf-mngr-content">';
				echo $content_html;	
			echo '</div>';
			echo '<div class="exwf-mngr-endel"></div>';
		echo '</div>';
	}

	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
add_shortcode( 'ex_wf_mngroup', 'exwoofood_shortcode_menu_group' );
add_action( 'after_setup_theme', 'ex_reg_wfood_mngroup_vc' );
function ex_reg_wfood_mngroup_vc(){
    if(function_exists('vc_map')){
	vc_map( array(
	   "name" => esc_html__("Food Menu Group", "woocommerce-food"),
	   "base" => "ex_wf_mngroup",
	   "class" => "",
	   "icon" => "icon-grid",
	   "controls" => "full",
	   "category" => esc_html__('Woocommerce Food','woocommerce-food'),
	   "params" => array(
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Layout", 'woocommerce-food'),
			 "param_name" => "layout",
			 "value" => array(
				esc_html__('Grid', 'woocommerce-food') => 'grid',
				esc_html__('List', 'woocommerce-food') => 'list',
				//esc_html__('Carousel', 'woocommerce-food') => 'carousel',
				esc_html__('Table', 'woocommerce-food') => 'table',
			 ),
			 "description" => esc_html__('Select Layout of Menu group', 'woocommerce-food')
		  ),
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
				esc_html__('4', 'woocommerce-food') => '4',
			 ),
			 "description" => esc_html__('Select style of layout ( Grid supports style 1,2,3,4 List supports style 1,2,3 and Table supports style 1)', 'woocommerce-food')
		  ),
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Columns", 'woocommerce-food'),
			 "param_name" => "column",
			 "value" => array(
				esc_html__('1 column', 'woocommerce-food') => '1',
				esc_html__('2 columns', 'woocommerce-food') => '2',
				esc_html__('3 columns', 'woocommerce-food') => '3',
				esc_html__('4 columns', 'woocommerce-food') => '4',
				esc_html__('5 columns', 'woocommerce-food') => '5',
			 ),
			 'dependency' 	=> array(
				'element' => 'layout',
				'value'   => array('grid','list'),
			 ),
			 "description" => esc_html__('Select number column of grid or list style', 'woocommerce-food')
		  ),
		   array(
		  	"admin_label" => true,
			 "type" => "dropdown",
			 "class" => "",
			 "heading" => esc_html__("Heading style", 'woocommerce-food'),
			 "param_name" => "heading_style",
			 "value" => array(
				esc_html__('Default', 'woocommerce-food') => '',
				esc_html__('Style 1', 'woocommerce-food') => '1',
				esc_html__('Style 2', 'woocommerce-food') => '2',
				esc_html__('Style 3', 'woocommerce-food') => '3',
				esc_html__('Style 4', 'woocommerce-food') => '4',
			 ),
			 "description" => esc_html__('Select Heading style', 'woocommerce-food')
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
		  /*array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("IDs", "woocommerce-food"),
			"param_name" => "ids",
			"value" => "",
			"description" => esc_html__("Specify food IDs to retrieve", "woocommerce-food"),
		  ),*/
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
			 "heading" => esc_html__("Menu/Category count", 'woocommerce-food'),
			 "param_name" => "show_count",
			 "value" => array(
			 	esc_html__('No', 'woocommerce-food') => '',
			 	esc_html__('Yes', 'woocommerce-food') => 'yes',
			 ),
			 "description" => esc_html__("Select Yes to show menu/category count", "woocommerce-food"),
		  ),
		  /*
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number of menu visible", "woocommerce-food"),
			"param_name" => "menu_visible",
			"value" => "",
			"description" => esc_html__("Enter number of menu visible or leave blank to show all", "woocommerce-food"),
		  ),
		  array(
		  	"admin_label" => true,
			"type" => "textfield",
			"heading" => esc_html__("Number of menu visible on scroll", "woocommerce-food"),
			"param_name" => "menu_onscroll",
			"value" => "",
			"description" => esc_html__("Enter number of menu visible, leave blank if show all menus", "woocommerce-food"),
		  ),
		  */
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
			 	esc_html__('Sale', 'woocommerce-food') => 'sale',
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
		  /*array(
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
			 "heading" => esc_html__("Menu filter style", 'woocommerce-food'),
			 "param_name" => "filter_style",
			 "value" => array(
			 	esc_html__('Default', 'woocommerce-food') => '',
			 	esc_html__('Icon', 'woocommerce-food') => 'icon',
			 ),
			 "description" => esc_html__("Select Menu filter style", "woocommerce-food"),
		  ),*/
		  array(
		  	"admin_label" => true,
			"type" => "dropdown",
			"heading" => esc_html__("Order Menu", "woocommerce-food"),
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
			 "heading" => esc_html__("Enable cart", 'woocommerce-food'),
			 "param_name" => "cart_enable",
			 "value" => array(
			 	esc_html__('Default', 'woocommerce-food') => '',
			 	esc_html__('Yes', 'woocommerce-food') => 'yes',
			 	esc_html__('No', 'woocommerce-food') => 'no',
			 ),
			 "description" => esc_html__("Enable side cart icon", "woocommerce-food"),
		  ),
		  /*array(
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
		  ),*/
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

// menu group load more
add_action( 'wp_ajax_exwoofood_more_menu', 'ajax_exwoofood_more_menu' );
add_action( 'wp_ajax_nopriv_exwoofood_more_menu', 'ajax_exwoofood_more_menu' );
function ajax_exwoofood_more_menu(){
	$atts = json_decode( stripslashes( $_POST['param_shortcode'] ), true );
	$exwf_mngr = json_decode( stripslashes( $_POST['data_menu'] ), true );
	
	$layout = isset($atts['layout']) && $atts['layout'] !=''? $atts['layout'] : 'grid';
	$style = isset($atts['style']) && $atts['style'] !=''? $atts['style'] : '1';
	$column = isset($atts['column']) && $atts['column'] !=''? $atts['column'] : '1';
	$cat   = isset($atts['cat']) ? str_replace(' ', '', $atts['cat']) : '';
	$order_cat   = isset($atts['order_cat']) ? $atts['order_cat'] : '';
	$count   = isset($atts['count']) &&  $atts['count'] !=''? $atts['count'] : '9';
	$menu_visible   = isset($atts['menu_visible']) &&  $atts['menu_visible'] !=''? $atts['menu_visible'] : '';
	$menu_onscroll   = isset($atts['menu_onscroll']) &&  $atts['menu_onscroll'] !=''? $atts['menu_onscroll'] : $menu_visible;
	$heading_style   = isset($atts['heading_style']) ? $atts['heading_style'] : '';
	$menu_filter   = isset($atts['menu_filter']) ? $atts['menu_filter'] : 'hide';
	$posts_per_page   = isset($atts['posts_per_page']) && $atts['posts_per_page'] !=''? $atts['posts_per_page'] : '3';
	$order  = isset($atts['order']) ? $atts['order'] : '';
	$orderby  = isset($atts['orderby']) ? $atts['orderby'] : '';
	$meta_key  = isset($atts['meta_key']) ? $atts['meta_key'] : '';
	$meta_value  = isset($atts['meta_value']) ? $atts['meta_value'] : '';
	$page_navi  = isset($atts['page_navi']) ? $atts['page_navi'] : '';
	$number_excerpt =  isset($atts['number_excerpt'])&& $atts['number_excerpt']!='' ? $atts['number_excerpt'] : '10';
	$cart_enable  = isset($atts['cart_enable']) ? $atts['cart_enable'] : '';
	$enable_modal = isset($atts['enable_modal']) ? $atts['enable_modal'] : '';
	$img_size =  isset($atts['img_size']) ? $atts['img_size'] :'';
	$featured =  isset($atts['featured']) ? $atts['featured'] :'';
	$filter_style =  isset($atts['filter_style']) ? $atts['filter_style'] :'';
	$location =  isset($atts['location']) ? $atts['location'] :'';
	$class  = isset($atts['class']) ? $atts['class'] : '';
	$live_sort =  isset($atts['live_sort']) ? $atts['live_sort'] :'';
	$hide_atc =  isset($atts['hide_atc']) ? $atts['hide_atc'] :'';

	$id_sc =  isset($atts['id_sc']) ? $atts['id_sc'] :'';

	if(!empty($exwf_mngr)){
		$mngr_ntag = apply_filters('exwf_mngroup_title_tag','h2');
		$content_html = $filter_html = $filter_slhtml= '';
		$i = 0;
		foreach ( $exwf_mngr as $k_mn =>$term ) {
			$i++;
			$icon_html =  '';
			$_iconsc = get_term_meta( $term['term_id'], 'exwoofood_menu_iconsc', true );
  			if($_iconsc!=''){
  				$icon_html =  '<span class="exwf-caticon exwf-iconsc">'.$_iconsc.'</span>'; 
  			}else{
	  			$thumbnail_id = get_term_meta( $term['term_id'], 'thumbnail_id', true );
	  			if($thumbnail_id!=''){
					// get the medium-sized image url
					$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
					// Output in img tag
					if(isset($image[0]) && $image[0]!=''){
						$icon_html =  '<span class="exwf-caticon"><img src="' . $image[0] . '" alt="" /></span>'; 
					}
				}
			}
			$filter_html .= '<a class="filtermngr-item" href="javascript:;" data-menu="'.esc_attr($term['slug']).'" data-id="'.esc_attr($id_sc).'">'.esc_attr($term['name']).'</a>';
			$filter_slhtml .= '<option value="'. esc_attr($term['slug']) .'">'. wp_kses_post($term['name']) .'</option>';
			$content_html .='
			<div class="exwf-mngr-item" data-menu="'.esc_attr($term['slug']).'">
				<div class="exwf-mnheading mnheading-'.esc_attr($heading_style).'">
					<'.$mngr_ntag.' class="mn-namegroup"><span>'.$icon_html.$term['name'].'</span></'.$mngr_ntag.'>
					'.($term['description']!='' ? '<div class="mn-desgroup">'.$term['description'].'</div>' : '').'
				</div>';
				$content_html .='<div class="exwf-mnlayout">';
				if($layout =='list'){
					$content_html .= do_shortcode('[ex_wf_list column="'.esc_attr($column).'" style="'.esc_attr($style).'" posts_per_page="'.esc_attr($posts_per_page).'" count="'.esc_attr($count).'" cat="'.esc_attr($term['slug']).'" orderby="'.esc_attr($orderby).'" order="'.esc_attr($order).'" page_navi="'.esc_attr($page_navi).'" menu_filter="'.esc_attr($menu_filter).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'" meta_key="'.esc_attr($meta_key).'" img_size="'.esc_attr($img_size).'" class="'.esc_attr($class).'" hide_atc="'.esc_attr($hide_atc).'" location="'.esc_attr($location).'"]');
				}else if($layout =='carousel'){
					
				}else if($layout =='table'){
					$content_html .= do_shortcode('[ex_wf_table count="'.esc_attr($count).'" posts_per_page="'.esc_attr($posts_per_page).'" page_navi="'.esc_attr($page_navi).'" live_sort="'.esc_attr($live_sort).'" cat="'.esc_attr($term['slug']).'" orderby="'.esc_attr($orderby).'" order="'.esc_attr($order).'"  menu_filter="'.esc_attr($menu_filter).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'" meta_key="'.esc_attr($meta_key).'" img_size="'.esc_attr($img_size).'" class="'.esc_attr($class).'" location="'.esc_attr($location).'" hide_atc="'.esc_attr($hide_atc).'"]');
				}else{
					$content_html .= do_shortcode('[ex_wf_grid column="'.esc_attr($column).'" style="'.esc_attr($style).'" posts_per_page="'.esc_attr($posts_per_page).'" count="'.esc_attr($count).'" cat="'.esc_attr($term['slug']).'" orderby="'.esc_attr($orderby).'" order="'.esc_attr($order).'"  page_navi="'.esc_attr($page_navi).'" menu_filter="'.esc_attr($menu_filter).'" cart_enable="'.esc_attr($cart_enable).'" enable_modal="'.esc_attr($enable_modal).'" featured="'.esc_attr($featured).'" meta_key="'.esc_attr($meta_key).'" img_size="'.esc_attr($img_size).'" class="'.esc_attr($class).'" location="'.esc_attr($location).'" hide_atc="'.esc_attr($hide_atc).'"]');
				}
				$content_html .='
				</div>
			</div>';
			if(is_numeric($menu_onscroll) && $menu_onscroll> 0 && $i <=$menu_onscroll){
				unset($exwf_mngr[$k_mn]);
				if($i == $menu_onscroll){
					break;
				}
			}
		}
		if(count($exwf_mngr) > 0){
			$filter_html .= '<a class="filtermngr-item exwf-btmore" alt="'.esc_html__('Scroll down to see more!','woocommerce-food').'" href="javascript:;" data-menu="exmmore" data-id="'.esc_attr($id_sc).'">'.esc_html__('More...','woocommerce-food').'</a>';
			$filter_slhtml .= '<option value="exmmore">'. esc_html__('More...','woocommerce-food') .'</option>';
		}
	}
	$output =  array('html_content'=>$content_html,'arr_menu'=> (json_encode($exwf_mngr)),'html_infilter' =>$filter_html,'html_slfilter' =>$filter_slhtml );
	echo str_replace('\/', '/', json_encode($output));
	die;
}