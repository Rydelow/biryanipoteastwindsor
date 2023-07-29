<?php
/*
Plugin Name: WooCommerce Food Order Calendar
Plugin URI: https://exthemes.net/woocommerce-food/
Description: Order calendar for WooCommerce Food
Version: 1.0
Author: Ex-Themes
Author URI: https://exthemes.net
Text Domain: exwf-order-calendar
WC tested up to: 5.8
License: Envato Split Licence
Domain Path: /languages/
*/
define( 'EX_WOOFOOD_OCAL_PATH', plugin_dir_url( __FILE__ ) );
// Make sure we don't expose any info if called directly
if ( !defined('EX_WOOFOOD_OCAL_PATH') ){
	die('-1');
}
if(!function_exists('exwf_ocal_get_plugin_url')){
	function exwf_ocal_get_plugin_url(){
		return plugin_dir_path(__FILE__);
	}
}
if(!function_exists('exwf_ocal_check_woo_exists')){
	function exwf_ocal_check_woo_exists() {
		$class = 'notice notice-error';
		$message = esc_html__( 'WooCommerce Food is Required to WooCommerce Food Order Calendar addon work, please install or activate WooCommerce Food plugin', 'exwf-order-calendar' );
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if (!is_plugin_active( 'woocommerce-food/woo-food.php' ) && !is_plugin_active( 'woo-exfood/woo-food.php' )  ) {
		add_action( 'admin_notices', 'exwf_ocal_check_woo_exists' );
		return;
	}
}
$_license = function_exists('exwf_license_infomation') ? exwf_license_infomation() : '';
if(isset($_license[0]) && $_license[0] == 'error'){
	return;
}
class EXWF_Order_Calendar{
	public $plugin_path;
	public function __construct(){
		$this->includes();
		//add_action('wp_enqueue_scripts', array( $this, 'frontend_style'),99 );
		add_action('plugins_loaded',array( $this, 'load_textdomain'));
    }
    // load text domain
    function load_textdomain() {
		$textdomain = 'exwf-order-calendar';
		$locale = '';
		if ( empty( $locale ) ) {
			if ( is_textdomain_loaded( $textdomain ) ) {
				return true;
			} else {
				return load_plugin_textdomain( $textdomain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			}
		} else {
			return load_textdomain( $textdomain, plugin_basename( dirname( __FILE__ ) ) . '/exwf-order-calendar/' . $textdomain . '-' . $locale . '.mo' );
		}
	}
	public function plugin_path() {
		if ( $this->plugin_path ) return $this->plugin_path;
		return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	function includes(){
		include_once exwf_ocal_get_plugin_url().'admin/functions.php';
		include_once exwf_ocal_get_plugin_url().'inc/functions.php';
	}
	// Load js and css
	function frontend_style(){
		//$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
		//wp_localize_script( 'ex-woo-food', 'exwf_jspr', $js_params  );
	}
	
}
$EXWF_Order_Calendar = new EXWF_Order_Calendar();