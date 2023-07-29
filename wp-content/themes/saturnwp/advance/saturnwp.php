<?php
class SaturnWp_Main_Framework{
	function __construct(){
		//get Wpsaturn Universal first so it could fire its actions first
		/** @noinspection PhpIncludeInspection */
 		get_template_part('advance/saturnwp_uni');
		add_action( 'customize_register', array( $this, 'customizer_pro_section' ) );

		if(is_admin()){
			//check on what page we are
			$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

			//always registered in admin
			add_action( 'init', array( $this, 'import_notice_check' ), 9 );
		}
	}
	
	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  WP_Customize_Manager  $manager
	 * @return void
	 */
	public function customizer_pro_section( $manager ) {

		// Load custom section.
		get_template_part('advance/inc/customizer/sections/saturnwp-class-a13-customize-section-pro');

		// Register custom section types.
		$manager->register_section_type( 'SaturnWp_A13_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(
			new SaturnWp_A13_Customize_Section_Pro(
				$manager,
				'saturnwp-pro-theme',
				array(
					'title'    => esc_html__( 'Upgrade to Pro', 'saturnwp' ),
					'pro_text' => esc_html__( 'Buy now',         'saturnwp' ),
					'pro_url'  => 'https://www.sktthemes.org/shop/universal-wordpress-theme/',
					'priority' => 0
				)
			)
		);
	}	

	function import_notice_check(){
		$plugin_path = 'skt-templates/skt-templates.php';
		include_once ABSPATH . 'wp-admin/includes/plugin.php';// phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		if ( is_plugin_active( $plugin_path ) ){
			return;
		}

		if( !saturnwp_is_admin_notice_active( 'fresh_import' ) ){
			return;
		}

		remove_action('tgmpa_register', 'saturnwp_register_required_plugins');
		add_action( 'admin_notices', array( $this, 'import_notice' ) );
 	}

	function import_notice(){
		echo '<div class="a13fe-admin-notice notice notice-info is-dismissible" data-notice_id="fresh_import">';
		echo '<h3>'.sprintf( esc_html__( 'Welcome to SaturnWP Theme', 'saturnwp' ), esc_html(SATURNWP_OPTIONS_NAME_PART )).'</h3>';
		echo '<p>'.esc_html__( 'Click on the button below to complete theme installation process..', 'saturnwp' ).'</p>';
		echo '<p><a class="button button-primary" href="'.esc_url( admin_url( 'themes.php?page=saturnwpinfopage&amp;subpage=info' ) ).'">'.esc_html__( 'Complete Installation', 'saturnwp').'</a></p>';
		echo '</div>';
	}
}

//run
new SaturnWp_Main_Framework();