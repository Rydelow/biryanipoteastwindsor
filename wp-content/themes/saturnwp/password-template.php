<?php
/**
 * Template used for displaying password protected page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly


global $saturnwp_a13;


//custom template
if($saturnwp_a13->get_option( 'page_password_template_type' ) === 'custom' ){
	$_page = $saturnwp_a13->get_option( 'page_password_template' );

	define('SATURNWP_CUSTOM_PASSWORD_PROTECTED', true );

	//make query
	$query = new WP_Query( array('page_id' => $_page ) );

	//add password form to content
	add_filter( 'the_content', 'saturnwp_add_password_form_to_template' );

	//show
	saturnwp_page_like_content($query);

	// Reset Post Data
	wp_reset_postdata();

	return;
}

//default template
else{
	define('SATURNWP_PASSWORD_PROTECTED', true); //to get proper class in body

	$_title = '<span class="fa fa-lock emblem"></span>' . esc_html__( 'This content is password protected.', 'saturnwp' )
	         .'<br />'
	         .esc_html__( 'To view it please enter your password below', 'saturnwp' );

	get_header();

	saturnwp_title_bar( 'outside', $_title );

	echo wp_kses_post(saturnwp_password_form()); //escaped on creation

	get_footer();
}