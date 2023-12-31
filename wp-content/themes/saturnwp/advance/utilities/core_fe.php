<?php
/**
 * For getting URL of current page
 *
 * @return string   URL
 */
function saturnwp_current_url() {
	global $wp;

	//no permalinks
	if ( $wp->request === null ) {
		$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	} else {
		$current_url = trailingslashit( home_url( add_query_arg( array(), $wp->request ) ) );
	}

	return $current_url;
}



/**
 * Checks if current page has active sidebar
 * returns false if there is no active sidebar,
 * if there is active sidebar it returns its name
 *
 * @return bool|string
 */
function saturnwp_has_active_sidebar() {
	global $saturnwp_a13;
	$test              = '';
	$page_type         = saturnwp_what_page_type_is_it();
	$wc_active = saturnwp_is_woocommerce_activated();
	$shop_with_sidebar = $wc_active && saturnwp_is_woocommerce_sidebar_page();


	if ( $shop_with_sidebar ) {
		$test = 'shop-widget-area';
	} elseif ( $wc_active && saturnwp_is_woocommerce() ) {
		return false;
	} elseif ( $page_type['blog_type'] ) {
		$test = 'blog-widget-area';
	} elseif ( $page_type['post'] ) {
		$test = 'post-widget-area';
	} elseif ( $page_type['page'] ) {
		$test           = 'page-widget-area';
		$meta_id        = get_the_ID();
		$custom_sidebar = $saturnwp_a13->saturnwp_get_meta( '_sidebar_to_show', $meta_id );
		if ( strlen( $custom_sidebar ) && $custom_sidebar !== 'default' ) {
			$test = $custom_sidebar;
		}

		//if has children nav and it is activated then sidebar is active
		$sidebar_meta = $saturnwp_a13->saturnwp_get_meta( '_widget_area', $meta_id );
		if ( strrchr( $sidebar_meta, 'nav' ) && saturnwp_page_menu( true ) ) {
			return $test;
		}
	}

	if ( is_active_sidebar( $test ) ) {
		return $test;
	} else {
		return false;
	}
}



/**
 * Returns array with types of current page
 *
 * @return array
 */
function saturnwp_what_page_type_is_it() {
	global $saturnwp_a13;
	static $types;

	if ( empty( $types ) ) {
		$types = array(
			'404'         => is_404(),
			'page'        => is_page(),
			'home'        => is_home(),
			'front_page'  => is_front_page(),
			'archive'     => is_archive(),
			'search'      => is_search(),
			'single'      => is_single(),
			'post'        => is_singular( 'post' ),
			'attachment'  => is_attachment(),
			'shop'        => saturnwp_is_woocommerce_activated() && saturnwp_is_woocommerce(),
			'product'     => saturnwp_is_woocommerce_activated() && saturnwp_is_woocommerce() && is_product(),
		);

		$types['singular']          = is_singular();
		$types['singular_not_post'] = $types['singular'] && ! $types['post'];
		$types['blog_type']         = ( $types['home'] || $types['archive'] || $types['search'] ) && ! $types['shop'] ;

		$types['page'] = $types['page'] || ($types['404'] && ($saturnwp_a13->get_option( 'page_404_template_type' ) === 'custom')) || defined('SATURNWP_CUSTOM_PASSWORD_PROTECTED');
	}

	return $types;
}



/**
 * If page is empty search result or 404 it is no property page, and you can read meta fields from it
 *
 * @return bool
 */
function saturnwp_is_no_property_page() {
	global $post;

	return ! is_object( $post );
}



add_filter( 'body_class', 'saturnwp_body_classes' );
/**
 * Add classes for <body> element
 *
 * @param array $classes
 *
 * @return array
 */
function saturnwp_body_classes( $classes ) {
	global $saturnwp_a13;

	$page_type = saturnwp_what_page_type_is_it();

	//hidden sidebar
	if ( is_active_sidebar( 'side-widget-area' ) ) {
		$side   = $saturnwp_a13->get_option( 'hidden_sidebar_side' );
		$effect = (int) $saturnwp_a13->get_option( 'hidden_sidebar_effect' );
		if ( $side === 'right' ) {
			$effect += 6;//right side effects have number bigger by 6
		}

		$classes[] = 'side-menu-eff-' . $effect;
	}

	//widgets top margin
	if($saturnwp_a13->get_option( 'widgets_top_margin' ) === 'off'){
		$classes[] = 'widgets_margin_top_off';
	}

	//header classes
	$header_type = $saturnwp_a13->get_option( 'header_type' );
	//header type
	$classes[] = 'header-'.$header_type;

	//header side(vertical only)
	if($header_type === 'vertical'){
		if(is_rtl()){
			$classes[] = 'header-side-'.$saturnwp_a13->get_option( 'header_side_rtl' );
		}
		else{
			$classes[] = 'header-side-'.$saturnwp_a13->get_option( 'header_side' );
		}
	}

	//site layout
	$layout_type = $saturnwp_a13->get_option( 'layout_type' );
	$classes[] = 'site-layout-'.$layout_type;
	if($layout_type === 'bordered'){
		$borders = array( 'top', 'left', 'bottom', 'right' );
		$borders_on = $saturnwp_a13->get_option( 'theme_borders' );

		if(is_array($borders_on)){
			foreach($borders as $border){
				if(!in_array($border, $borders_on)){
					$classes[] = 'no-border-'.$border;
				}
			}
		}
	}

	//sticky one page
	if( $saturnwp_a13->saturnwp_get_meta( '_content_sticky_one_page' ) === 'on'){
		$classes[] = 'a13-body-sticky-one-page';
	}

	//page with posts list
	if ( $page_type['blog_type'] && ! defined( 'SATURNWP_NO_RESULTS' ) ) {
		$classes[] = 'posts-list';
	}

	//cart and others not sidebar/title pages of woocommerce
	if ( $page_type['shop'] && ! saturnwp_is_woocommerce_sidebar_page() ) {
		$classes[] = 'woocommerce-no-major-page';
	}

	//add special class for pages with products list
	if ( saturnwp_is_woocommerce_activated() && saturnwp_is_woocommerce_products_list_page() ) {
		$classes[] = 'products-list';
	}

	//password protected
	if ( defined( 'SATURNWP_PASSWORD_PROTECTED' ) ) {
		$classes[] = 'password-protected';
	}

	//custom password page
	if( defined( 'SATURNWP_CUSTOM_PASSWORD_PROTECTED' ) ){
		$classes[] = 'custom-password-page';
		$classes[] = 'page';
	}

	if( is_archive() && !have_posts() ){
		$classes[] = 'search-no-results';
	}

	if( $page_type['404'] ){
		//custom 404 page
		if( $saturnwp_a13->get_option( 'page_404_template_type' ) === 'custom' ){
			$classes[] = 'custom404';
			$classes[] = 'page';
		}
		else{
			$classes[] = 'default404';
		}

	}


	return $classes;
}



/**
 * Get classes for mid element, depending on context of many things like:
 * -sidebar availability
 * -sidebar side
 * -layout of current page
 * -type of layout of current page
 *
 * @return string   classes of #mid
 */
function saturnwp_get_mid_classes() {
	global $saturnwp_a13;

	//mid classes for type of layout align and widget area display(on/off)
	$mid_classes = array();

	$page_type  = saturnwp_what_page_type_is_it();
	$page       = $page_type['page'];
	$post       = $page_type['post'];
	$attachment = $page_type['attachment'];
	$shop       = $page_type['shop'];
	$product    = $page_type['product'];


	/*
	 * content layout classes
	 * */
	$meta_id = get_the_ID();
	//layouts that have space between content and sidebar
	$parted_layouts = array( 'left', 'right', 'left_padding', 'right_padding', 'center' );
	//layouts that sit on one edge of screen
	$edge_layouts = array( 'left', 'right', 'left_padding', 'right_padding' );
	//layouts that have content with fixed width
	$fluid_layouts = array( 'full', 'full_padding' );

	$layout = 'center';

	if ( $attachment ) {
		//nothing, but we add it cause every attachment has also type of post, page depending to which
		//it was attached
		$layout = 'center';
	} //albums are Full width
	//cart and others not sidebar/title pages of woocommerce
	elseif($page_type['shop'] && !saturnwp_is_woocommerce_sidebar_page()){
		$layout = $saturnwp_a13->get_option( 'shop_no_major_pages_content_layout' );
	}
	//wish list
	elseif ( class_exists( 'YITH_WCWL' ) && (get_the_ID() === (int)yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) ) ) ) {
		$layout = $saturnwp_a13->get_option( 'shop_no_major_pages_content_layout' );
	} //shop
	elseif ( $page_type['shop'] && ! $page_type['product'] ) {
		$layout = $saturnwp_a13->get_option( 'shop_content_layout' );

		//only on pages where list of products are displayed
		if ( is_shop() || is_product_taxonomy() ) {
			$mid_classes[] = 'shop-columns-'.$saturnwp_a13->get_option( 'shop_products_columns' );
		}
	} //product
	elseif ( $page_type['product'] ) {
		$layout = $saturnwp_a13->get_option( 'product_content_layout' );
	} //page
	elseif ($page ) {
		$layout_option = $saturnwp_a13->saturnwp_get_meta( '_content_layout', $meta_id );
		$layout        = $layout_option === 'global' ? $saturnwp_a13->get_option( 'page_content_layout' ):
			$layout_option;

		//in content padding
		$top_bottom_padding = $saturnwp_a13->saturnwp_get_meta('_content_padding');
		if($top_bottom_padding === 'top'){
			$mid_classes[] = 'no-bottom-space';
		}
		elseif($top_bottom_padding === 'bottom'){
			$mid_classes[] = 'no-top-space';
		}
		elseif($top_bottom_padding === 'off'){
			$mid_classes[] = 'no-top-space';
			$mid_classes[] = 'no-bottom-space';
		}

		$side_padding = $saturnwp_a13->saturnwp_get_meta('_content_side_padding');
		if($side_padding === 'off'){
			$mid_classes[] = 'no-side-space';
		}
	} //single post
	elseif ( $post ) {
		$layout = $saturnwp_a13->get_option( 'post_content_layout' );
	} //blog type
	elseif ( $page_type['blog_type'] ) {
		$layout = $saturnwp_a13->get_option( 'blog_content_layout' );

		//in content padding
		$top_bottom_padding = $saturnwp_a13->get_option( 'blog_content_padding' );
		if($top_bottom_padding === 'top'){
			$mid_classes[] = 'no-bottom-space';
		}
		elseif($top_bottom_padding === 'bottom'){
			$mid_classes[] = 'no-top-padding'; /* padding instead of space to not clash this two different scenarios */
		}
		elseif($top_bottom_padding === 'off'){
			$mid_classes[] = 'no-top-padding';
			$mid_classes[] = 'no-bottom-padding';
		}
	}


	$mid_classes[] = 'layout-' . $layout;
	if ( in_array( $layout, $parted_layouts ) ) {
		$mid_classes[] = 'layout-parted';
	}

	if ( in_array( $layout, $edge_layouts ) ) {
		$mid_classes[] = 'layout-edge';
	}
	else{
		$mid_classes[] = 'layout-no-edge';
	}

	//layouts that sit on edge of screen and have margin
	if ( strpos( $layout, 'padding' ) !== false ) {
		$mid_classes[] = 'layout-padding';
	}

	if ( in_array( $layout, $fluid_layouts ) ) {
		$mid_classes[] = 'layout-fluid';
	}
	else{
		$mid_classes[] = 'layout-fixed';
	}


	/*
	 * sidebar classes
	 * */

	//check if there is active sidebar for current page
	$force_full_width = false;
	if ( 
		 $attachment ||
	     saturnwp_has_active_sidebar() === false
	) {
		$force_full_width = true;
	}

	function saturnwp__inner__set_full_width( &$mid_classes ) {
		define( 'SATURNWP_NO_SIDEBARS', true ); /* so we don't have to check again in sidebar.php */
		$mid_classes[] = 'no-sidebars';
	}

	function saturnwp__inner__set_sidebar_class( &$mid_classes, $sidebar ) {
		if ( ( $sidebar == 'off' ) ) {
			saturnwp__inner__set_full_width( $mid_classes );
		} else {
			$mid_classes[] = 'with-sidebar';
			$mid_classes[] = $sidebar;
		}
	}

	function saturnwp__inner__check_for_rtl_sidebar_version( $option_name ) {
		global $saturnwp_a13;
		$sidebar = $saturnwp_a13->get_option( $option_name );
		if ( is_rtl() ){
			$rtl_opposites = array(
				'left-sidebar'  => 'right-sidebar',
				'right-sidebar'  => 'left-sidebar',
				'off' => 'off'
			);

			$sidebar = $saturnwp_a13->get_option( $option_name.'_rtl', $rtl_opposites[$sidebar] );
		}

		return $sidebar;
	}

	if ( $force_full_width ) {
		saturnwp__inner__set_full_width( $mid_classes );
	} //shop type
	elseif ( $shop && ! $product ) {
		saturnwp__inner__set_sidebar_class( $mid_classes, saturnwp__inner__check_for_rtl_sidebar_version( 'shop_sidebar' ) );
	} //product type
	elseif ( $product ) {
		saturnwp__inner__set_sidebar_class( $mid_classes, saturnwp__inner__check_for_rtl_sidebar_version( 'product_sidebar' ) );
	} //blog type
	elseif ( $page_type['blog_type'] ) {
		saturnwp__inner__set_sidebar_class( $mid_classes, saturnwp__inner__check_for_rtl_sidebar_version( 'blog_sidebar' ) );
	} //single post
	elseif ( $post ) {
		saturnwp__inner__set_sidebar_class( $mid_classes, saturnwp__inner__check_for_rtl_sidebar_version( 'post_sidebar' ) );
	} //single page
	elseif ( $page ) {
		//special treatment cause of children menu option

		//get value from this page
		$sidebar = get_post_meta($meta_id, '_widget_area', true);

		//if global value
		if( $sidebar === 'G' || $sidebar === '' ){
			$sidebar = saturnwp__inner__check_for_rtl_sidebar_version( 'page_sidebar' );
		}

		//check is it left or right type sidebar
		if ( strrchr( $sidebar, 'left' ) ) {
			$sidebar = 'left-sidebar';
		} elseif ( strrchr( $sidebar, 'right' ) ) {
			$sidebar = 'right-sidebar';
		}

		saturnwp__inner__set_sidebar_class( $mid_classes, $sidebar );
	}

	//make class string
	$mid_classes = implode(' ', $mid_classes);

	return $mid_classes;
}



add_action('pre_get_posts','saturnwp_frontpage');
/**
 * Function that changes query for front page if user decided to use one of theme features
 *
 * @param WP_Query $query
 */
function saturnwp_frontpage( $query ) {
	global $saturnwp_a13;

	if ( is_admin() || ! $query->is_main_query() ){
		return;
	}

	$theme_decided_home_page = false;

	if ( 'page' == get_option( 'show_on_front') && get_option( 'page_on_front' ) && ($query->query_vars['page_id'] === get_option( 'page_on_front' ) ) ){
		$theme_decided_home_page = true;
	}

}



/**
 * Solves issue with badly named templates in previous theme versions.
 * It works while entering page on front-end
 */
function saturnwp_check_for_renamed_templates(){
	//check what is current template name
	$current_name = get_post_meta( get_the_ID(), '_wp_page_template', true );

	//verify if it is up to date
	$checked_name = saturnwp_proper_page_template_name($current_name);
	if( $checked_name !== $current_name  ){
		//update post with new template file name
		update_post_meta(get_the_ID(), '_wp_page_template', $checked_name);

		//only name without .php suffix
		$template_name = basename($checked_name, '.php');

		//run new template
		get_template_part( $template_name );

		//inform that there was redirect
		return false;
	}

	return true;
}



/**
 * pre-connect to google fonts server - speeds up loading site that use Google fonts from theme
 *
 * @param array $urls
 * @param string $relation_type
 *
 * @return array
 *
 */
function saturnwp_faster_google_fonts($urls, $relation_type){
	if('preconnect' === $relation_type){
		global $saturnwp_a13;

		$standard_fonts = array_keys( $saturnwp_a13->saturnwp_get_standard_fonts_list() );
		$options_fonts = array(
			$saturnwp_a13->get_option( 'nav_menu_fonts' ),
			$saturnwp_a13->get_option( 'slide_title_fonts' ),
			$saturnwp_a13->get_option( 'slide_description_fonts'),
			$saturnwp_a13->get_option( 'slide_button_fonts'),					
			$saturnwp_a13->get_option( 'titles_fonts' ),
			$saturnwp_a13->get_option( 'normal_fonts' ),
			//default to titles fonts as it was in previous versions
			$saturnwp_a13->get_option( 'logo_fonts', $saturnwp_a13->get_option( 'titles_fonts' ) ),
		);

		foreach ( $options_fonts as $font ) {
			//if not standard font create then it is google font
			if ( ! in_array( $font['font-family'], $standard_fonts ) ) {
				$urls[] = array(
					'href' => 'https://fonts.gstatic.com',
					'crossorigin',
				);
				break;
			}
		}
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'saturnwp_faster_google_fonts', 10, 2 );



/**
 * Collects web-fonts from theme settings
 *
 * @param bool $as_array - should fonts be returned in format to process
 *
 * @return array|string web-fonts used
 *
 */
function saturnwp_get_theme_web_fonts($as_array = false){
	global $saturnwp_a13;

	$standard_fonts = array_keys( $saturnwp_a13->saturnwp_get_standard_fonts_list() );

	$options_fonts = array(
		$saturnwp_a13->get_option( 'nav_menu_fonts' ),
		$saturnwp_a13->get_option( 'slide_title_fonts'),
		$saturnwp_a13->get_option( 'slide_description_fonts'),
		$saturnwp_a13->get_option( 'slide_button_fonts'),		
		$saturnwp_a13->get_option( 'titles_fonts' ),
		$saturnwp_a13->get_option( 'normal_fonts' ),
		//default to titles fonts as it was in previous versions
		$saturnwp_a13->get_option( 'logo_fonts', $saturnwp_a13->get_option( 'titles_fonts' ) ),
	);

	if($as_array){
		$fonts = array();
		foreach ( $options_fonts as $id => $font ) {
			if( ! in_array( $font['font-family'], $standard_fonts ) ){
				$fonts[$id] = $font;
			}
		}
	}
	else{
		$fonts = array( 'families' => array() );
		foreach ( $options_fonts as $font ) {
			//if not standard font create font definition for request
			if ( ! in_array( $font['font-family'], $standard_fonts ) ) {
				//start with font family
				$font_definition = $font['font-family'];


				//add variants
				$variants = false;
				//legacy setting for variants
				if ( isset( $font['font-multi-style'] ) && strlen( $font['font-multi-style'] ) ) {
					$variants = json_decode( $font['font-multi-style'], true );
				} //new setting for variants
				elseif ( isset( $font['variants'] ) && is_array( $font['variants'] ) ) {
					$variants = $font['variants'];
				}
				//we got variants finally
				if ( $variants !== false ) {
					$font_definition .= ':';
					foreach ( $variants as $index => $variant ) {
						if ( $index > 0 ) {
							$font_definition .= ',';
						}
						$font_definition .= $variant;
					}
				}

				//add subsets
				if( isset( $font['subsets'] ) ){
					//convert subsets to array if legacy setting
					if( !is_array( $font['subsets'] ) ){
						$font['subsets'] = strlen( $font['subsets'] ) > 0 ? array( $font['subsets'] ) : array();
					}

					if ( sizeof( $font['subsets'] ) ) {
						$font_definition .= ':';
						foreach ( $font['subsets'] as $index => $subset ) {
							if ( $index > 0 ) {
								$font_definition .= ',';
							}
							$font_definition .= $subset;
						}
					}
				}

				array_push( $fonts['families'], $font_definition );
			}
		}
	}

	return $fonts;
}