<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* Here you can insert your functions, filters and actions. */

/* That's all, stop editing! Make a great website!. */

/* Init of the framework */

/* This function exist in WP 4.7 and above.
 * Theme has protection from runing it on version below 4.7
 * However, it has to at least run, to give user info about having not compatible WP version :-)
 */
if( function_exists('get_theme_file_path') ){
	/** @noinspection PhpIncludeInspection */
	require_once( get_theme_file_path( '/advance/class-saturnwp-framework.php' ) );
}
else{
	/** @noinspection PhpIncludeInspection */
	require_once( get_template_directory() . '/advance/class-saturnwp-framework.php' );
}

global $saturnwp_a13;
$saturnwp_a13 = new SaturnWp_Framework();
$saturnwp_a13->saturnwp_start();

function saturnwp_scripts() {
	wp_enqueue_script( 'saturnwp-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), '20190715', true );
	wp_enqueue_script( 'saturnwp-customscripts', get_template_directory_uri() . '/js/custom.js', array('jquery') );
	wp_localize_script( 'saturnwp-navigation', 'NavigationScreenReaderText', array() );
}
add_action( 'wp_enqueue_scripts', 'saturnwp_scripts' );