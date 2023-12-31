<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

global $saturnwp_a13;

//custom template
$custom_404_page = $saturnwp_a13->get_option( 'page_404_template' );
if($saturnwp_a13->get_option( 'page_404_template_type' ) === 'custom' && $custom_404_page !== ''&& $custom_404_page !== '0'){
	//make query
	$query = new WP_Query( array('page_id' => $custom_404_page ) );

	//show
	saturnwp_page_like_content( $query );

	// Reset Post Data
	wp_reset_postdata();

	return;
}

//default template
else{
	define( 'SATURNWP_NO_RESULTS', true );
	get_header();

	// Elementor `404` location
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
		$_title = '<span class="emblem">404</span>'.esc_html__('The page you are looking for can\'t be found!', 'saturnwp');
		$subtitle = sprintf(
			/* translators:  Go to our home page(link) or go back to previous page(link) */
			esc_html__( 'Go to our %1$s or go back to %2$s', 'saturnwp' ),
			'<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'home page', 'saturnwp' ) . '</a>',
			'<a href="javascript:history.go(-1)">' . esc_html__( 'previous page', 'saturnwp' ) . '</a>'
		);

		saturnwp_title_bar( 'outside', $_title, $subtitle );
	}

	get_footer();
}