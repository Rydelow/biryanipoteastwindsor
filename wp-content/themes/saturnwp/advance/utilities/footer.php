<?php
/**
 * Functions that operates in themes footer element
 */

if(!function_exists('saturnwp_theme_footer')){
	function saturnwp_theme_footer(){
		global $saturnwp_a13;

		//Header Footer Elementor Plugin support
		if ( function_exists( 'hfe_render_footer' ) ) {
			hfe_render_footer();
		}

		if( $saturnwp_a13->get_option( 'footer_switch', 'on' ) === 'off' ){
			//no theme footer
			return;
		}

		if ( function_exists( 'elementor_location_exits' ) && elementor_location_exits( 'footer', true ) ) {
			echo '<div class="container-elementor-footer">';
		}


		if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {


			$html = '';

			ob_start();
			saturnwp_footer_widgets();
			saturnwp_footer_items();

			$output = ob_get_contents();
			ob_end_clean();
			
			$footer_logo = $saturnwp_a13->get_option( 'footer_logo' );
			$footer_logo_link = $saturnwp_a13->get_option( 'footer_logo_link' );

			global $saturnwp_a13; 
			if(strlen($output)){
				$header_type = $saturnwp_a13->get_option( 'header_type' );
				$to_move     = $header_type === 'vertical' ? '' : 'to-move';
				$width       = ' ' . $saturnwp_a13->get_option( 'footer_content_width' );
				$style       = ' ' . $saturnwp_a13->get_option( 'footer_content_style' );

				$footer_class = $to_move.$width.$style;
				$html = '<footer id="footer" class="'.esc_attr($footer_class).'"'.saturnwp_get_schema_args('footer').'>';
				if(!empty($footer_logo)){
					$html .= '<div class="content-limiter">';
					
					$html .= '<div class="footer-logo">';
					if(!empty($footer_logo_link)){
						$html .= '<a href="'.esc_url($footer_logo_link).'">';
					}
					$html .= '<img src="'.esc_url($footer_logo['url']).'"/>';
					if(!empty($footer_logo_link)){
					$html .= '</a>';
					}
					$html .= '</div>';
					
					global $saturnwp_a13;
					if( $saturnwp_a13->get_option( 'footer_socials' ) === 'on' ){					
					$html .= '<div class="footer-social">';
					$html .= saturnwp_social_icons($saturnwp_a13->get_option( 'footer_socials_color' ), $saturnwp_a13->get_option( 'footer_socials_color_hover' ));
					$html .= '</div>';					
					}
										
					$html .= '<div class="clear"></div>';
					
					$html .= '</div>';
				}
				$html .= $output.'</footer>';
			}
			//escaped on creation
			print wp_kses_post($html);
		}


		if ( function_exists( 'elementor_location_exits' ) && elementor_location_exits( 'footer', true ) ) {
			echo '</div>';//.container-elementor-footer
		}
	}
}


function saturnwp_footer_css() {
	global $saturnwp_a13;

	$css = '';

	if ( $saturnwp_a13->get_option( 'footer_switch' ) === 'on' ) {
		$footer_bg_color          = saturnwp_make_css_rule( 'background-color', $saturnwp_a13->get_option_color_rgba( 'footer_bg_color' ) );
		$footer_lower_bg_color    = saturnwp_make_css_rule( 'background-color', $saturnwp_a13->get_option_color_rgba( 'footer_lower_bg_color' ) );
		$footer_font_size         = saturnwp_make_css_rule( 'font-size', $saturnwp_a13->get_option( 'footer_font_size' ), '%spx' );
		$footer_widgets_font_size = saturnwp_make_css_rule( 'font-size', $saturnwp_a13->get_option( 'footer_widgets_font_size' ), '%spx' );
		$footer_font_color        = saturnwp_make_css_rule( 'color', $saturnwp_a13->get_option_color_rgba( 'footer_font_color' ) );
		$footer_link_color        = saturnwp_make_css_rule( 'color', $saturnwp_a13->get_option_color_rgba( 'footer_link_color' ) );
		$footer_hover_color       = saturnwp_make_css_rule( 'color', $saturnwp_a13->get_option_color_rgba( 'footer_hover_color' ) );

		$css .= "
/* ==================
   FOOTER
   ==================*/
#footer{
    $footer_bg_color
    $footer_font_size
}
#footer .widget,
#footer .widget .search-form input[type=\"search\"]{
    $footer_widgets_font_size
}
.foot-items{
    $footer_lower_bg_color
}
.foot-items{
    $footer_font_color
}
.foot-items .foot-text a{
    $footer_link_color
}
.foot-items .foot-text a:hover{
    $footer_hover_color
}";
	}

	return $css;
}

function saturnwp_footer_partial_css($response) {
	return saturnwp_prepare_partial_css($response, 'footer_switch', 'saturnwp_footer_css');
}
add_filter( 'customize_render_partials_response', 'saturnwp_footer_partial_css' );


if(!function_exists('saturnwp_footer_widgets')) {
	/**
	 * Prints out HTML for footer widgets in columns
	 */
	function saturnwp_footer_widgets() {
		global $saturnwp_a13;

		//is there any widgets
		if ( is_active_sidebar( 'footer-widget-area' ) ) {
			//class for widgets
			$_class = '';
			$columns = (int)$saturnwp_a13->get_option( 'footer_widgets_columns' );
			if ( $columns === 1 ) {
				$_class = ' one-col';
			} elseif ( $columns === 2 ) {
				$_class = ' two-col';
			} elseif ( $columns === 3 ) {
				$_class = ' three-col';
			} elseif ( $columns === 4 ) {
				$_class = ' four-col';
			} elseif ( $columns === 5 ) {
				$_class = ' five-col';
			}

			//color of sidebar
			$_class .= ' '.$saturnwp_a13->get_option( 'footer_widgets_color' );

			echo '<div class="foot-widgets' . esc_attr( $_class ) . '">';
                echo '<div class="foot-content clearfix">';

			dynamic_sidebar( 'footer-widget-area' );

			echo '</div>
                </div>';
		}
	}
}


if(!function_exists('saturnwp_footer_items')) {
	/**
	 * Prints out HTML for footer items
	 */
	function saturnwp_footer_items() {
		global $saturnwp_a13; ?>

<div class="foot-items">
    <div class="foot-content clearfix">
        <?php
	                //footer text
	                $ft = do_shortcode( $saturnwp_a13->get_option( 'footer_text' ) );
	                $privacy = $saturnwp_a13->get_option( 'footer_privacy_link' ) === 'on';

					if(!empty($ft)){
		                echo '<div class="foot-text">';
		                echo nl2br( wp_kses_post( balanceTags( $ft, true ) ) );
		                if ( $privacy && function_exists( 'the_privacy_policy_link' ) ) {
			                the_privacy_policy_link( '<div class="test">', '</div>');
		                }
		                echo '</div>';
	                }

	                ?>
    </div>
</div>
<?php
	}
}

if(!function_exists('saturnwp_footer_for_site_modules')) {
	/**
	 * Prints out HTML for elements needed to be printed after whole site
	 */
	function saturnwp_footer_for_site_modules() {
		global $saturnwp_a13;

		$to_top_off = $saturnwp_a13->get_option( 'to_top' ) === 'off';

		if( $to_top_off ){
			return;
		}

		$to_top_icon = $saturnwp_a13->get_option( 'to_top_icon' );
		$to_top_icon = 'fa-'.(strlen($to_top_icon)? $to_top_icon : 'chevron-up');

		//top top and overlay for various things ?>
<a href="#top" id="to-top" class="to-top fa <?php echo esc_attr($to_top_icon); ?>"></a>
<div id="content-overlay" class="to-move"></div>
<?php
	}
}



if(!function_exists('saturnwp_footer_for_header_modules')) {
	/**
	 * Prints out HTML for elements used in header
	 */
	function saturnwp_footer_for_header_modules() {
		global $saturnwp_a13;

		//hidden sidebar
		if( is_active_sidebar( 'side-widget-area' ) ){
			$hidden_sb_classes = ' '.$saturnwp_a13->get_option( 'hidden_sidebar_widgets_color' );
			$hidden_sb_classes .= ' at-'.$saturnwp_a13->get_option( 'hidden_sidebar_side' );
		?>
<nav id="side-menu" class="side-widget-menu<?php echo esc_attr($hidden_sb_classes) ?>">
    <div class="scroll-wrap">
        <?php dynamic_sidebar( 'side-widget-area' ); ?>
    </div>
    <span class="a13icon-cross close-sidebar"></span> </nav>
<?php
		}

	}
}