<?php
/* Functions for registering and printing scripts & styles */

add_action( 'wp_enqueue_scripts', 'saturnwp_theme_scripts', 26 ); //put it later then woocommerce
if(!function_exists('saturnwp_theme_scripts')){
	/**
	 * Registering frontend theme scripts
	 */
	function saturnwp_theme_scripts(){
        global $saturnwp_a13;
        static $printed = false;

        if($printed){
            //second call of this functions
            return;
        }

        $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

        $page_type = saturnwp_what_page_type_is_it();

        /* We add some JavaScript to pages with the comment form
          * to support sites with threaded comments (when in use).
          */
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ){
            wp_enqueue_script( 'comment-reply' );
        }

        $script_depends = array( 'saturnwp-plugins' );
		
		wp_enqueue_script('nivoslider',get_template_directory_uri().'/assets/js/jquery.nivo.slider.js', array('jquery'), true);

        //fitvids script
        wp_register_script('jquery-fitvids', get_theme_file_uri( 'js/jquery.fitvids.min.js' ), array('jquery'), '1.1', true);
        $script_depends[] = 'jquery-fitvids';

        //fittext script
        wp_register_script('jquery-fittext', get_theme_file_uri( 'js/jquery.fittext.min.js' ), array('jquery'), '1.2', true);
        $script_depends[] = 'jquery-fittext';

        //plugins used in theme (cheat sheet)
        wp_register_script('saturnwp-plugins', get_theme_file_uri( 'js/helpers.min.js' ),
            false, //depends
            SATURNWP_THEME_VERSION, //version number
            true //in footer
        );

        //flickity
        wp_register_script( 'flickity', get_theme_file_uri( 'js/flickity.pkgd.min.js' ), array('jquery'), '2.0.9', true);

		//slidesjs
		wp_register_script( 'jquery-slides', get_theme_file_uri( 'js/jquery.slides.min.js' ), array('jquery'), '3.0.4', true);
        $script_depends[] = 'jquery-slides';

		//sticky kit
		wp_register_script( 'jquery-sticky-kit', get_theme_file_uri( 'js/jquery.sticky-kit.min.js' ), array('jquery'), '1.1.2', true);
        $script_depends[] = 'jquery-sticky-kit';

        //mouse scroll support
		wp_register_script( 'jquery-mousewheel', get_theme_file_uri( 'js/jquery.mousewheel.min.js' ), array('jquery'), '3.1.13', true);
        $script_depends[] = 'jquery-mousewheel';

        //writting effect support
		wp_register_script( 'jquery-typed', get_theme_file_uri( 'js/typed.min.js' ), array('jquery'), '1.1.4', true);
        $script_depends[] = 'jquery-typed';

		//counter - for counter shortcode
		wp_register_script( 'jquery-countto', get_theme_file_uri( 'js/jquery.countTo.js' ), array('jquery'), '1.0', true);

		//countdown
		wp_register_script( 'jquery-countdown', get_theme_file_uri( 'js/jquery.countdown.min.js' ), array('jquery'), '2.2.0', true);

		//lightGallery lightbox
		wp_register_script( 'jquery-lightgallery', get_theme_file_uri( 'js/light-gallery/js/lightgallery-all.min.js' ), array('jquery'), '1.6.9', true);

	    //modified isotope for bricks layouts
	    wp_register_script( 'saturnwp-isotope', get_theme_file_uri( 'js/isotope.pkgd.min.js' ), array('jquery'), '3.0.6', true);
	    $script_depends[] = 'saturnwp-isotope';

        //flickity needed
        $flickity_themes = array('scroller', 'scroller-parallax');

		//A13 STICKY ONE PAGE
		wp_register_script( 'jquery-slimscroll', get_theme_file_uri( 'js/jquery.slimscroll.min.js' ), array('jquery','saturnwp-plugins'), '1.3.2', true);
		wp_register_script( 'fullPage', get_theme_file_uri( 'js/jquery.fullPage.min.js' ), array('jquery','jquery-slimscroll'), '2.7.6', true);
		if( $saturnwp_a13->saturnwp_get_meta( '_content_sticky_one_page' ) === 'on' ){
			$script_depends[] = 'fullPage';
		}

		//Image carousel
		wp_register_script( 'jquery-owl-carousel', get_theme_file_uri( 'js/owl.carousel.min.js' ), array('jquery','saturnwp-plugins'), '2.2.1', true);

	    //lightbox
	    $lightbox = $saturnwp_a13->get_option( 'skt_lightbox' );
	    if( $lightbox === 'lightGallery' ){
		    $script_depends[] = 'jquery-lightgallery';
	    }

        //options passed to JS
        $skt_params = saturnwp_js_parameters();
        //hand written scripts for theme
        wp_enqueue_script('saturnwp-scripts', get_theme_file_uri( 'js/script' . $suffix . '.js' ), $script_depends, SATURNWP_THEME_VERSION, true );
        //transfer options
        wp_localize_script( 'saturnwp-plugins', 'SKTParams', $skt_params );
        $printed = true;
    }
}

if(!function_exists('saturnwp_js_parameters')){
	/**
	 * Special parameters passed to JavaScript vie Global variable
	 * @return array of all parameters
	 */
	function saturnwp_js_parameters(){
        global $saturnwp_a13;

        $allow_mobile_menu = $saturnwp_a13->get_option( 'header_type' ) === 'vertical'
                             || ($saturnwp_a13->get_option( 'header_main_menu' ) === 'on' && $saturnwp_a13->get_option( 'menu_allow_mobile_menu' ) !== 'off');

        $params = array(
            /* GLOBAL OPTIONS */
            'ajaxurl'                   => admin_url('admin-ajax.php'),
            'home_url'                  => home_url().'/',
            'defimgurl'                 => get_theme_file_uri( 'images/holders/photo.png'),
            'options_name'              => SATURNWP_OPTIONS_NAME,

	        /* MISC */
            'load_more'                 => esc_html__( 'Load more', 'saturnwp' ),
            'loading_items'             => esc_html__( 'Loading next items', 'saturnwp' ),
            'anchors_in_bar'            => $saturnwp_a13->get_option( 'anchors_in_bar' ) === 'on',
            'scroll_to_anchor'          => $saturnwp_a13->get_option( 'scroll_to_anchor' ) === 'on',
            'writing_effect_mobile'     => $saturnwp_a13->get_option( 'writing_effect_mobile' ) === 'on',
            'writing_effect_speed'      => $saturnwp_a13->get_option( 'writing_effect_speed', 10 ),

            /* HORIZONTAL HEADER */
            'hide_content_under_header' => saturnwp_content_under_header(),
            'default_header_variant'    => saturnwp_horizontal_header_color_variant(),
            'header_sticky_top_bar'     => $saturnwp_a13->get_option( 'header_sticky_top_bar' ) === 'on',
            'header_color_variants'     => $saturnwp_a13->get_option( 'header_color_variants' ),
            'show_header_at'            => $saturnwp_a13->saturnwp_get_meta('_horizontal_header_show_header_at' ),

            /* HORIZONTAL HEADER VARIANTS */
            'header_normal_social_colors' => $saturnwp_a13->get_option( 'header_socials_color' ).
                                            '|'.$saturnwp_a13->get_option( 'header_socials_color_hover' ).'_hover'.
                                            '|'.$saturnwp_a13->get_option( 'top_bar_socials_color' ).
                                            '|'.$saturnwp_a13->get_option( 'top_bar_socials_color_hover' ).'_hover',

            'header_light_social_colors' => $saturnwp_a13->get_option( 'header_light_socials_color' ).
                                            '|'.$saturnwp_a13->get_option( 'header_light_socials_color_hover' ).'_hover'.
                                            '|'.$saturnwp_a13->get_option( 'header_light_top_bar_socials_color' ).
                                            '|'.$saturnwp_a13->get_option( 'header_light_top_bar_socials_color_hover' ).'_hover',

            'header_dark_social_colors' => $saturnwp_a13->get_option( 'header_dark_socials_color' ).
                                            '|'.$saturnwp_a13->get_option( 'header_dark_socials_color_hover' ).'_hover'.
                                            '|'.$saturnwp_a13->get_option( 'header_dark_top_bar_socials_color' ).
                                            '|'.$saturnwp_a13->get_option( 'header_dark_top_bar_socials_color_hover' ).'_hover',

            /* MENU */
            'close_mobile_menu_on_click' => $saturnwp_a13->get_option( 'menu_close_mobile_menu_on_click' ) === 'on',
            'menu_overlay_on_click'      => $saturnwp_a13->get_option( 'header_menu_overlay_on_click', 'off' ) === 'on',
            'allow_mobile_menu'          => $allow_mobile_menu,
            'submenu_opener'             => 'fa-' . $saturnwp_a13->get_option( 'submenu_opener' ),
            'submenu_closer'             => 'fa-' . $saturnwp_a13->get_option( 'submenu_closer' ),
            'submenu_third_lvl_opener'   => 'fa-' . $saturnwp_a13->get_option( 'submenu_third_lvl_opener' ),
            'submenu_third_lvl_closer'   => 'fa-' . $saturnwp_a13->get_option( 'submenu_third_lvl_closer' ),

            /* BLOG */
            'posts_layout_mode'          => $saturnwp_a13->get_option( 'blog_layout_mode' ),

            /* SHOP */
            'products_brick_margin'      => $saturnwp_a13->get_option( 'shop_brick_margin' ),
            'products_layout_mode'       => $saturnwp_a13->get_option( 'shop_products_layout_mode' ),

            /* lightGallery lightbox */
            'lg_lightbox_share'          => $saturnwp_a13->get_option( 'lg_lightbox_share', 'on' ) === 'on',
            'lg_lightbox_controls'       => $saturnwp_a13->get_option( 'lg_lightbox_controls', 'on' ) === 'on',
            'lg_lightbox_download'       => $saturnwp_a13->get_option( 'lg_lightbox_download', 'off' ) === 'on',
            'lg_lightbox_counter'        => $saturnwp_a13->get_option( 'lg_lightbox_counter', 'on' ) === 'on',
            'lg_lightbox_thumbnail'      => $saturnwp_a13->get_option( 'lg_lightbox_thumbnail', 'on' ) === 'on',
            'lg_lightbox_show_thumbs'    => $saturnwp_a13->get_option( 'lg_lightbox_show_thumbs', 'off' ) === 'on',
            'lg_lightbox_autoplay'       => $saturnwp_a13->get_option( 'lg_lightbox_autoplay', 'on' ) === 'on',
            'lg_lightbox_autoplay_open'  => $saturnwp_a13->get_option( 'lg_lightbox_autoplay_open', 'off' ) === 'on',
            'lg_lightbox_progressbar'    => $saturnwp_a13->get_option( 'lg_lightbox_progressbar', 'on' ) === 'on',
            'lg_lightbox_full_screen'    => $saturnwp_a13->get_option( 'lg_lightbox_full_screen', 'on' ) === 'on',
            'lg_lightbox_zoom'           => $saturnwp_a13->get_option( 'lg_lightbox_zoom', 'on' ) === 'on',
            'lg_lightbox_mode'           => $saturnwp_a13->get_option( 'lg_lightbox_mode', 'lg-slide' ),
            'lg_lightbox_speed'          => $saturnwp_a13->get_option( 'lg_lightbox_speed', '600' ),
            'lg_lightbox_preload'        => $saturnwp_a13->get_option( 'lg_lightbox_preload', '1' ),
            'lg_lightbox_hide_delay'     => $saturnwp_a13->get_option( 'lg_lightbox_hide_delay', '2000' ),
            'lg_lightbox_autoplay_pause' => $saturnwp_a13->get_option( 'lg_lightbox_autoplay_pause', '5000' ),
            'lightbox_single_post'       => $saturnwp_a13->get_option( 'lightbox_single_post', 'off' ) === 'on',
        );

        //add only if proofing is enabled
        $proofing = (int)( $saturnwp_a13->saturnwp_get_meta( '_proofing' ) === 'on' );
        if($proofing){
            $params['proofing_manual_ids']          = $saturnwp_a13->saturnwp_get_meta('_proofing_ids' ) === 'manual';
            $params['proofing_add_comment']         = esc_html__( 'Add comment', 'saturnwp' );
            $params['proofing_comment_placeholder'] = esc_html__( 'Write your comment here&hellip;', 'saturnwp' );
            $params['proofing_mark_item']           = esc_html__( 'Mark item', 'saturnwp' );
            $params['proofing_uncheck_item']        = esc_html__( 'Uncheck item', 'saturnwp' );
            $params['proofing_nonce']               = wp_create_nonce( "proofing_ajax" );
        }

        return $params;
    }
}


add_action( 'wp_head', 'saturnwp_get_web_fonts_dynamic' );
if(!function_exists( 'saturnwp_get_web_fonts_dynamic' )) {
    function saturnwp_get_web_fonts_dynamic() {
        //add small inline script that adds class "js" to HTML if JavaScript is supported
        //important for accessibility
?><script type="text/javascript">
// <![CDATA[
(function(){
    var docElement = document.documentElement,
        className = docElement.className;
    // Change `no-js` to `js`
    var reJS = new RegExp('(^|\\s)no-js( |\\s|$)');
    //space as literal in second capturing group cause there is strange situation when \s is not catched on load when other plugins add their own classes
    className = className.replace(reJS, '$1js$2');
    docElement.className = className;
})();
// ]]>
</script><?php

        global $saturnwp_a13;
        $use_loader = $saturnwp_a13->get_option( 'use_webfontloader', 'on' ) === 'on';

        //we load fonts in static way
        if(!$use_loader){
            return;
        }

        //add webfonts
        $fonts_js = saturnwp_get_theme_web_fonts();
        if ( sizeof( $fonts_js['families'] ) ):
?><script type="text/javascript">
// <![CDATA[
WebFontConfig = {
    google: <?php echo wp_json_encode( $fonts_js ); ?>,
    active: function () {
        //tell listeners that fonts are loaded
        if (window.jQuery) {
            jQuery(document.body).trigger('webfontsloaded');
        }
    }
};
(function (d) {
    var wf = d.createElement('script'), s = d.scripts[0];
    wf.src = '<?php echo esc_url( get_theme_file_uri( 'js/webfontloader.min.js' ) ); ?>';
    wf.type = 'text/javascript';
    wf.async = 'true';
    s.parentNode.insertBefore(wf, s);
})(document);
// ]]>
</script><?php
        endif;
    }
}



add_action( 'wp_enqueue_scripts', 'saturnwp_get_web_fonts_static' );
if(!function_exists( 'saturnwp_get_web_fonts_static' )) {
    function saturnwp_get_web_fonts_static(){
        global $saturnwp_a13;
        $use_loader = $saturnwp_a13->get_option( 'use_webfontloader', 'on' ) === 'on';

        //we load fonts dynamically
        if($use_loader){
            return;
        }

        $fonts = saturnwp_get_theme_web_fonts( true );
        if( sizeof( $fonts ) ){
            $url     = 'https://fonts.googleapis.com/css?family=';
            $parts   = array();
            $subsets = array();

            foreach( $fonts as $font ) {
                $url_part = str_replace(' ', '%20', $font['font-family'] );
                $weights  = str_replace( 'italic', 'i', implode( ',', $font['variants'] ) );
                $url_part .= strlen( $weights ) ? ':' . $weights : '';
                $_subsets = implode( ',', $font['subsets'] );
                if(strlen( $_subsets )){
                    $subsets[] = $_subsets;
                }

                //rule for font
                $parts[] = $url_part;
            }

            //join fonts
            $url .= implode( '%7C', $parts );

            //add subsets
            if( sizeof( $subsets ) ){
                $url .= '&amp;subset=' . implode( ',', $subsets );
            }

            //add link to web fonts
            wp_enqueue_style( 'a13-google-font-combined', $url, false, false, 'all' );
        }
    }
}



add_action( 'wp_enqueue_scripts', 'saturnwp_theme_styles', 26 ); //put it later then woocommerce
if(!function_exists('saturnwp_theme_styles')){
	/**
	 * Adds CSS files to theme
	 */
	function saturnwp_theme_styles(){
        global $saturnwp_a13;

	    //woocommerce
	    if(saturnwp_is_woocommerce_activated()){
		    wp_register_style( 'saturnwp-woocommerce', get_theme_file_uri( 'css/woocommerce.css' ), array('saturnwp-a13-main-style'), SATURNWP_THEME_VERSION);
            wp_style_add_data( 'saturnwp-woocommerce', 'rtl', 'replace' );
            wp_enqueue_style( 'saturnwp-woocommerce');
	    }
		
		wp_enqueue_style('saturnwp-static_css',get_template_directory_uri().'/assets/css/nivo-slider.css', 'static_css' );

        wp_register_style( 'font-awesome', get_theme_file_uri( 'css/font-awesome.min.css' ), false, '4.7.0');
	    wp_register_style( 'a13-icomoon', get_theme_file_uri( 'css/icomoon.css' ), false, SATURNWP_THEME_VERSION);
        wp_register_style( 'saturnwp-a13-main-style', SATURNWP_TPL_URI . '/style.css', array('font-awesome', 'a13-icomoon'), SATURNWP_THEME_VERSION);
        wp_style_add_data( 'saturnwp-a13-main-style', 'rtl', 'replace' );

		//Image carousel
		wp_register_style( 'jquery-owl-carousel', get_theme_file_uri( 'css/owl.carousel.css' ), array(), SATURNWP_THEME_VERSION);

        //lightGallery lightbox
	    wp_register_style( 'jquery-lightgallery-transitions', get_theme_file_uri( 'js/light-gallery/css/lg-transitions.min.css' ), false, '1.6.9' );
	    $lg_default_transition = $saturnwp_a13->get_option( 'lg_lightbox_mode' ) === 'lg-slide';
	    wp_register_style( 'jquery-lightgallery', get_theme_file_uri( 'js/light-gallery/css/lightgallery.min.css' ), ($lg_default_transition ? false : array('jquery-lightgallery-transitions')), '1.6.9' );

	    //lightbox
	    $lightbox = $saturnwp_a13->get_option( 'skt_lightbox' );
	    if( $lightbox === 'lightGallery' ){
		    wp_enqueue_style('jquery-lightgallery');
	    }

        wp_enqueue_style('saturnwp-a13-main-style');

        //in customizer we print user settings CSS with unique IDS inline
        if( ! is_customize_preview() ){
            $fallback = false;

            if( function_exists( 'saturnwp-a13fe_generate_user_css' ) ){
                $user_css_file = saturnwp-a13fe_user_css_name();
                if( file_exists( $user_css_file ) ){
                    $last_modified = filemtime( $user_css_file );
                    wp_enqueue_style( 'saturnwp-a13-user-css', saturnwp-a13fe_user_css_name( true ), array( 'saturnwp-a13-main-style' ), SATURNWP_THEME_VERSION . '_' . $last_modified );
                }
                else{
                    $fallback = true;
                }
            }
            else{
                $fallback = true;
            }

            if($fallback){
                //register empty handle to not break backward compatibility with child themes
                //it is also used on styles generated per post
                wp_register_style( 'saturnwp-a13-user-css', false, array('saturnwp-a13-main-style') );
                //add user settings CSS
                saturnwp_enable_user_css_functions();
                wp_add_inline_style( 'saturnwp-a13-user-css', saturnwp_get_user_css() );
                wp_enqueue_style( 'saturnwp-a13-user-css' );
            }
        }
        else{
            //register empty handle so styles could attach
            wp_register_style( 'saturnwp-a13-user-css', false, array('saturnwp-a13-main-style') );
        }



        if( class_exists( 'YITH_WCWL' ) ){
            //remove conflicting styles from wishlist plugin
            global $wp_styles;

            $wp_styles->registered['yith-wcwl-font-awesome']->src = get_theme_file_uri( 'css/font-awesome.min.css' );
            $wp_styles->registered['yith-wcwl-font-awesome']->ver = '4.7.0';
        }
    }
}

add_action( 'wp_enqueue_scripts', 'saturnwp_elementor_fa5_fix', 27 ); //put it after theme styles
if(!function_exists('saturnwp_elementor_fa5_fix')){
    /**
     * If there is Elementor in version 2.6.0 or newer we use Elementor shim to move to FontAwesome 5
     */
    function saturnwp_elementor_fa5_fix() {
        if( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.6.0', '>=' ) ){
            //load font awesome shim
            \Elementor\Icons_Manager::enqueue_shim();
        }
    }
}


if(!function_exists('saturnwp_pingback_header')){
    /**
     * Add a pingback url auto-discovery header for singularly identifiable articles.
     */
    function saturnwp_pingback_header() {
        if( is_singular() && pings_open() ){
            printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
        }
    }
}
add_action( 'wp_head', 'saturnwp_pingback_header' );