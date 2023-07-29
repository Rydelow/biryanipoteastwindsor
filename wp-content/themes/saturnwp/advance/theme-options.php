<?php
function saturnwp_setup_theme_options() {
	global $saturnwp_a13;

	//get all cursors
	$cursors = array(
		'christmas.png'         => 'christmas.png',
		'empty_black.png'       => 'empty_black.png',
		'empty_black_white.png' => 'empty_black_white.png',
		'superior_cursor.png'   => 'superior_cursor.png'
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'cursors', $cursors );
	
	//get all menu effects
	$menu_effects = array(
		'none'      => esc_html__( 'None', 'saturnwp' ),
		'ferdinand' => 'ferdinand'
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'menu_effects', $menu_effects );

	//get all custom sidebars
	$header_sidebars = $saturnwp_a13->get_option( 'custom_sidebars' );
	if ( ! is_array( $header_sidebars ) ) {
		$header_sidebars = array();
	}
	//create 2 arrays for different purpose
	$header_sidebars            = array_merge( array( 'off' => esc_html__( 'Off', 'saturnwp' ) ), $header_sidebars );
	$header_sidebars_off_global = array_merge( array( 'G' => esc_html__( 'Global settings', 'saturnwp' ) ), $header_sidebars );
	//re-indexing these arrays
	array_unshift( $header_sidebars, null );
	unset( $header_sidebars[0] );
	array_unshift( $header_sidebars_off_global, null );
	unset( $header_sidebars_off_global[0] );
	$saturnwp_a13->saturnwp_set_settings_set( 'header_sidebars', $header_sidebars );
	$saturnwp_a13->saturnwp_set_settings_set( 'header_sidebars_off_global', $header_sidebars_off_global );

	$on_off = array(
		'on'  => esc_html__( 'Enable', 'saturnwp' ),
		'off' => esc_html__( 'Disable', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'on_off', $on_off );

	$font_weights = array(
		'100'    => esc_html__( '100', 'saturnwp' ),
		'200'    => esc_html__( '200', 'saturnwp' ),
		'300'    => esc_html__( '300', 'saturnwp' ),
		'normal' => esc_html__( 'Normal 400', 'saturnwp' ),
		'500'    => esc_html__( '500', 'saturnwp' ),
		'600'    => esc_html__( '600', 'saturnwp' ),
		'bold'   => esc_html__( 'Bold 700', 'saturnwp' ),
		'800'    => esc_html__( '800', 'saturnwp' ),
		'900'    => esc_html__( '900', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'font_weights', $font_weights );

	$font_transforms = array(
		'none'      => esc_html__( 'None', 'saturnwp' ),
		'uppercase' => esc_html__( 'Uppercase', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'font_transforms', $font_transforms );

	$text_align = array(
		'left'   => esc_html__( 'Left', 'saturnwp' ),
		'center' => esc_html__( 'Center', 'saturnwp' ),
		'right'  => esc_html__( 'Right', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'text_align', $text_align );

	$image_fit = array(
		'cover'    => esc_html__( 'Cover', 'saturnwp' ),
		'contain'  => esc_html__( 'Contain', 'saturnwp' ),
		'fitV'     => esc_html__( 'Fit Vertically', 'saturnwp' ),
		'fitH'     => esc_html__( 'Fit Horizontally', 'saturnwp' ),
		'center'   => esc_html__( 'Just center', 'saturnwp' ),
		'repeat'   => esc_html__( 'Repeat', 'saturnwp' ),
		'repeat-x' => esc_html__( 'Repeat X', 'saturnwp' ),
		'repeat-y' => esc_html__( 'Repeat Y', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'image_fit', $image_fit );
	
	$slider_type_select = array(
		'default_slider'    => esc_html__( 'Default Slider', 'saturnwp' ),
		'custom_slider'  	=> esc_html__( 'Custom Slider', 'saturnwp' ),
		'disable_slider'    => esc_html__( 'Disable Slider', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'slider_type_select', $slider_type_select );		

	$content_layouts = array(
		'center'        => esc_html__( 'Center fixed width', 'saturnwp' ),
		'left'          => esc_html__( 'Left fixed width', 'saturnwp' ),
		'left_padding'  => esc_html__( 'Left fixed width + padding', 'saturnwp' ),
		'right'         => esc_html__( 'Right fixed width', 'saturnwp' ),
		'right_padding' => esc_html__( 'Right fixed width + padding', 'saturnwp' ),
		'full_fixed'    => esc_html__( 'Full width + fixed content', 'saturnwp' ),
		'full_padding'  => esc_html__( 'Full width + padding', 'saturnwp' ),
		'full'          => esc_html__( 'Full width', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'content_layouts', $content_layouts );

	$parallax_types = array(
		"tb"   => esc_html__( 'top to bottom', 'saturnwp' ),
		"bt"   => esc_html__( 'bottom to top', 'saturnwp' ),
		"lr"   => esc_html__( 'left to right', 'saturnwp' ),
		"rl"   => esc_html__( 'right to left', 'saturnwp' ),
		"tlbr" => esc_html__( 'top-left to bottom-right', 'saturnwp' ),
		"trbl" => esc_html__( 'top-right to bottom-left', 'saturnwp' ),
		"bltr" => esc_html__( 'bottom-left to top-right', 'saturnwp' ),
		"brtl" => esc_html__( 'bottom-right to top-left', 'saturnwp' ),
	);

	$content_under_header = array(
		'content' => esc_html__( 'Yes, hide the content', 'saturnwp' ),
		'title'   => esc_html__( 'Yes, hide the content and add top padding to the outside title bar.', 'saturnwp' ),
		'off'     => esc_html__( 'Turn it off', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'content_under_header', $content_under_header );

	$social_colors = array(
		'black'            => esc_html__( 'Black', 'saturnwp' ),
		'color'            => esc_html__( 'Color', 'saturnwp' ),
		'white'            => esc_html__( 'White', 'saturnwp' ),
		'semi-transparent' => esc_html__( 'Semi transparent', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'social_colors', $social_colors );

	$bricks_hover = array(
		'cross'      => esc_html__( 'Show cross', 'saturnwp' ),
		'drop'       => esc_html__( 'Drop', 'saturnwp' ),
		'shift'      => esc_html__( 'Shift', 'saturnwp' ),
		'pop'        => esc_html__( 'Pop text', 'saturnwp' ),
		'border'     => esc_html__( 'Border', 'saturnwp' ),
		'scale-down' => esc_html__( 'Scale down', 'saturnwp' ),
		'none'       => esc_html__( 'None', 'saturnwp' ),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'bricks_hover', $bricks_hover );

	//tags allowed in descriptions
	$valid_tags = array(
		'a'      => array(
			'href' => array(),
		),
		'br'     => array(),
		'code'   => array(),
		'em'     => array(),
		'strong' => array(),
	);
	$saturnwp_a13->saturnwp_set_settings_set( 'valid_tags', $valid_tags );
	/*
	 *
	 * ---> START SECTIONS
	 *
	 */

//GENERAL SETTINGS
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'    => esc_html__( 'General settings', 'saturnwp' ),
		'desc'     => '',
		'id'       => 'section_general_settings',
		'icon'     => 'el el-adjust-alt',
		'priority' => 3,
		'fields'   => array()
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Front page', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_general_front_page',
		'icon'       => 'fa fa-home',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'fp_variant',
				'type'        => 'select',
				'title'       => esc_html__( 'What to show on the front page?', 'saturnwp' ),
				/* translators: %s: URL */
				'description' => sprintf( wp_kses( __( 'If you choose <strong>Page</strong> then make sure that in <a href="%s">WordPress Homepage Settings</a> you have selected <strong>A static page</strong>, that you wish to use as the front page.', 'saturnwp' ), $valid_tags ), 'javascript:wp.customize.section( \'static_front_page\' ).focus();' ),
				'options'     => array(
					'page'         => esc_html__( 'Page', 'saturnwp' ),
					'blog'         => esc_html__( 'Blog', 'saturnwp' ),
				),
				'default'     => 'page',

			),
		)
	) );
	
	/* SLIDER START */
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Slider', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_slider',
		'icon'       => 'fa fa-wrench',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'custom_slider_option',
				'type'    => 'radio',
				'title'   => esc_html__( 'Slider Type', 'saturnwp' ),
				'options' => array(
					'defaultslide' => esc_html__( 'Default', 'saturnwp' ),
					'customslide'  => esc_html__( 'Custom', 'saturnwp' ),
					'disableslide'  => esc_html__( 'Disable', 'saturnwp' )
				),
				'default' => 'disableslide',
				'js'      => true,
			),
			array(
				'id'          => 'slide_title_fonts',
				'type'        => 'font',
				'title'       => esc_html__( 'Slide Title Font Family:', 'saturnwp' ),
				'default'     => array(
					'font-family'    => 'Poppins',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal',
					'font-variant' => '600'
				),
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'          => 'slide_description_fonts',
				'type'        => 'font',
				'title'       => esc_html__( 'Slide Description Font Family:', 'saturnwp' ),
				'default'     => array(
					'font-family'    => 'Poppins',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal'
				),
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),
			array(
				'id'          => 'slide_button_fonts',
				'type'        => 'font',
				'title'       => esc_html__( 'Slide Button Font Family:', 'saturnwp' ),
				'default'     => array(
					'font-family'    => 'Poppins',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal'
				),
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_title_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Slide Title', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'step'    => 1,
				'max'     => 100,
				'unit'    => 'px',
				'default' => 42,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),
			array(
				'id'      => 'slide_description_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Slide Description', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'step'    => 1,
				'max'     => 100,
				'unit'    => 'px',
				'default' => 18,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_button_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Slide Button', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'step'    => 1,
				'max'     => 100,
				'unit'    => 'px',
				'default' => 18,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_title_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Title', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#282828',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_description_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Description', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#282828',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_button_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Button Text', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#ffffff',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_button_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Button Background', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#fe911c',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_button_hover_text_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Button Hover Text', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#282828',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_button_hover_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Button Hover Background', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#fd4c1c',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_pager_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Pager', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#003de4',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),	
			array(
				'id'      => 'slide_active_pager_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Slide Active Pager', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#fd4c1c',
				'partial' => true,
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
			),
			array(
				'id'       => 'slide_image1',
				'type'     => 'image',
				'title'    => esc_html__( 'Slide Image 1', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_title1',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Title 1', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_description1',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Slide Description 1', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_link1',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Link 1', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_button1',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Button 1', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_image2',
				'type'     => 'image',
				'title'    => esc_html__( 'Slide Image 2', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_title2',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Title 2', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_description2',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Slide Description 2', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_link2',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Link 2', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_button2',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Button 2', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_image3',
				'type'     => 'image',
				'title'    => esc_html__( 'Slide Image 3', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_title3',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Title 3', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_description3',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Slide Description 3', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_link3',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Link 3', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),
			array(
				'id'       => 'slide_button3',
				'type'     => 'text',
				'title'    => esc_html__( 'Slide Button 3', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'custom_slider_option', '=', 'defaultslide' ),
				'js'       => true,
			),																												
			array(
				'id'       => 'customslide',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Custom Slider Shortcode', 'saturnwp' ),
				'required' => array( 'custom_slider_option', '=', 'customslide' ),
				'js'       => true,
			),
		)
	) );
	
	/* SLIDER END */	

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'General layout', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_main_settings',
		'icon'       => 'fa fa-wrench',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'appearance_body_image',
				'type'    => 'image',
				'title'   => esc_html__( 'Background image', 'saturnwp' ),
				'partial' => array(
					'selector'            => '.page-background',
					'container_inclusive' => true,
					'settings'            => array(
						'appearance_body_image',
						'appearance_body_image_fit',
						'appearance_body_bg_color',
					),
					'render_callback'     => 'saturnwp_page_background',
				),
			),
			array(
				'id'      => 'appearance_body_image_fit',
				'type'    => 'select',
				'title'   => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options' => $image_fit,
				'default' => 'cover',
				'partial' => true,
			),
			array(
				'id'      => 'appearance_body_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ),
				'default' => '#ffffff',
				'partial' => true,
			),
			array(
				'id'          => 'layout_type',
				'type'        => 'radio',
				'title'       => esc_html__( 'Layout', 'saturnwp' ),
				'options'     => array(
					'full' => esc_html__( 'Full width', 'saturnwp' ),
				),
				'default'     => 'full',
			),
			array(
				'id'      => 'custom_cursor',
				'type'    => 'radio',
				'title'   => esc_html__( 'Mouse cursor', 'saturnwp' ),
				'options' => array(
					'default' => esc_html__( 'Normal', 'saturnwp' ),
					'select'  => esc_html__( 'Predefined', 'saturnwp' ),
					'custom'  => esc_html__( 'Custom', 'saturnwp' ),
				),
				'default' => 'default',
				'js'      => true,
			),
			array(
				'id'       => 'cursor_select',
				'type'     => 'select',
				'title'    => esc_html__( 'Cursor', 'saturnwp' ),
				'options'  => $cursors,
				'default'  => 'empty_black_white.png',
				'required' => array( 'custom_cursor', '=', 'select' ),
				'js'       => true,
			),
			array(
				'id'       => 'cursor_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Custom cursor image', 'saturnwp' ),
				'required' => array( 'custom_cursor', '=', 'custom' ),
				'js'       => true,
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Page preloader', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_page_preloader',
		'icon'       => 'fa fa-spinner',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'preloader',
				'type'        => 'radio',
				'title'       => esc_html__( 'Page preloader', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
				'js'          => true,
			),
			array(
				'id'          => 'preloader_hide_event',
				'type'        => 'radio',
				'title'       => esc_html__( 'Hide event', 'saturnwp' ),
				'description' => wp_kses( __( '<strong>On load</strong> is called when the whole site, with all the images, is loaded, which can take a lot of time on heavier sites, and even more time on mobile devices. Also,  it can sometimes hang and never hide the preloader, when there is a problem with some resource. <br /><strong>On DOM ready</strong> is called when the whole HTML with CSS is loaded, so after the preloader is hidden, you can still see the loading of images. This is a much safer option.', 'saturnwp' ), $valid_tags ),
				'options'     => array(
					'ready' => esc_html__( 'On DOM ready', 'saturnwp' ),
					'load'  => esc_html__( 'On load', 'saturnwp' ),
				),
				'default'     => 'ready',
				'required'    => array( 'preloader', '=', 'on' ),
				'js'          => true,
			),
			array(
				'id'       => 'preloader_bg_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'required' => array( 'preloader', '=', 'on' ),
				'partial'  => array(
					'selector'            => '#preloader',
					'container_inclusive' => true,
					'settings'            => array(
						'preloader_bg_image',
						'preloader_bg_image_fit',
						'preloader_bg_color',
						'preloader_type',
						'preloader_color',
					),
					'render_callback'     => 'saturnwp_page_preloader',
				),
			),
			array(
				'id'       => 'preloader_bg_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'cover',
				'required' => array( 'preloader', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'preloader_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Background color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'preloader', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'preloader_type',
				'type'     => 'select',
				'title'    => esc_html__( 'Type', 'saturnwp' ),
				'options'  => array(
					'none'              => esc_html__( 'none', 'saturnwp' ),
					'atom'              => esc_html__( 'Atom', 'saturnwp' ),
					'flash'             => esc_html__( 'Flash', 'saturnwp' ),
					'indicator'         => esc_html__( 'Indicator', 'saturnwp' ),
					'radar'             => esc_html__( 'Radar', 'saturnwp' ),
					'circle_illusion'   => esc_html__( 'Circle Illusion', 'saturnwp' ),
					'square_of_squares' => esc_html__( 'Square of squares', 'saturnwp' ),
					'plus_minus'        => esc_html__( 'Plus minus', 'saturnwp' ),
					'hand'              => esc_html__( 'Hand', 'saturnwp' ),
					'blurry'            => esc_html__( 'Blurry', 'saturnwp' ),
					'arcs'              => esc_html__( 'Arcs', 'saturnwp' ),
					'tetromino'         => esc_html__( 'Tetromino', 'saturnwp' ),
					'infinity'          => esc_html__( 'Infinity', 'saturnwp' ),
					'cloud_circle'      => esc_html__( 'Cloud circle', 'saturnwp' ),
					'dots'              => esc_html__( 'Dots', 'saturnwp' ),
					'jet_pack_man'      => esc_html__( 'Jet-Pack-Man', 'saturnwp' ),
					'circle'            => 'Circle'
				),
				'default'  => 'flash',
				'required' => array( 'preloader', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'preloader_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Animation color', 'saturnwp' ),
				'required' => array(
					array( 'preloader', '=', 'on' ),
					array( 'preloader_type', '!=', 'tetromino' ),
					array( 'preloader_type', '!=', 'blurry' ),
					array( 'preloader_type', '!=', 'square_of_squares' ),
					array( 'preloader_type', '!=', 'circle_illusion' ),
				),
				'default'  => '',
				'partial'  => true,
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Theme Header', 'saturnwp' ),
		'desc'       => esc_html__( 'Theme header is a place where you usually find the logo of your site, main menu, and a few other elements.', 'saturnwp' ),
		'id'         => 'subsection_header',
		'icon'       => 'fa fa-ellipsis-h',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'header_switch',
				'type'    => 'radio',
				'title'   => esc_html__( 'Theme Header', 'saturnwp' ),
				'description' => esc_html__( 'If you do not plan to use theme header or want to replace it with something else, just disable it here.', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			)
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Footer', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_footer',
		'icon'       => 'fa fa-ellipsis-h',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'footer_switch',
				'type'    => 'radio',
				'title'   => esc_html__( 'Footer', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
				'partial'     => array(
					'selector'            => '#footer',
					'container_inclusive' => true,
					'settings'            => array(
						'footer_switch',
						'footer_widgets_columns',
						'footer_text',
						'footer_privacy_link',
						'footer_content_width',
						'footer_content_style',
						'footer_bg_color',
						'footer_lower_bg_color',
						'footer_lower_bg_color',
						'footer_widgets_color',
						'footer_font_size',
						'footer_widgets_font_size',
						'footer_font_color',
						'footer_link_color',
						'footer_hover_color',
					),
					'render_callback'     => 'saturnwp_theme_footer',
				),
			),
			array(
				'id'          => 'footer_logo',
				'type'        => 'image',
				'title'       => esc_html__( 'Footer Logo', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
			array(
				'id'          => 'footer_logo_link',
				'type'        => 'text',
				'title'       => esc_html__( 'Footer Logo Link', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
			array(
				'id'       => 'footer_widgets_columns',
				'type'     => 'select',
				'title'    => esc_html__( 'Widgets columns number', 'saturnwp' ),
				'options'  => array(
					'1' => esc_html__( '1', 'saturnwp' ),
					'2' => esc_html__( '2', 'saturnwp' ),
					'3' => esc_html__( '3', 'saturnwp' ),
					'4' => esc_html__( '4', 'saturnwp' ),
					'5' => esc_html__( '5', 'saturnwp' ),
				),
				'default'  => '4',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'          => 'footer_text',
				'type'        => 'textarea',
				'title'       => esc_html__( 'Content', 'saturnwp' ),
				'description' => esc_html__( 'You can use HTML here.', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
			array(
				'id'          => 'footer_privacy_link',
				'type'        => 'radio',
				'title'       => esc_html__( 'Privacy Policy Link', 'saturnwp' ),
				'description' => esc_html__( 'Since WordPress 4.9.6 there is an option to set Privacy Policy page. If you enable this option it will display a link to it in the footer after footer content.', 'saturnwp' ).' <a href="'.esc_url( admin_url( 'options-privacy.php' ) ).'">'.esc_html__( 'Here you can set your Privacy Policy page', 'saturnwp' ).'</a>',
				'options'     => $on_off,
				'default'     => 'off',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
			array(
				'id'          => 'footer_socials',
				'type'        => 'radio',
				'title'       => esc_html__( 'Social icons', 'saturnwp' ),
				/* translators: %s: URL */
				'description' => sprintf( wp_kses( __( 'If you need to edit social links go to <a href="%s">Social icons</a> settings.', 'saturnwp' ), $valid_tags ), 'javascript:wp.customize.section( \'section_social\' ).focus();' ),
				'options'     => $on_off,
				'default'     => 'off',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => array(
					'selector'            => '.f-links',
					'container_inclusive' => true,
					'settings'            => array(
						'footer_socials',
						'footer_socials_color',
						'footer_socials_color_hover',
					),
					'render_callback'     => 'footer_socials'
				),
			),
			array(
				'id'       => 'footer_socials_color',
				'type'     => 'select',
				'title'    => esc_html__( 'Social icons', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'options'  => $social_colors,
				'default'  => 'white',
				'required' => array(
					array( 'footer_switch', '=', 'on' ),
					array( 'footer_socials', '=', 'on' ),
				),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_socials_color_hover',
				'type'     => 'select',
				'title'    => esc_html__( 'Social icons', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'options'  => $social_colors,
				'default'  => 'semi-transparent',
				'required' => array(
					array( 'footer_switch', '=', 'on' ),
					array( 'footer_socials', '=', 'on' ),
				),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_content_width',
				'type'     => 'radio',
				'title'    => esc_html__( 'Content', 'saturnwp' ). ' : ' .esc_html__( 'Width', 'saturnwp' ),
				'options'  => array(
					'narrow' => esc_html__( 'Narrow', 'saturnwp' ),
					'full'   => esc_html__( 'Full width', 'saturnwp' ),
				),
				'default'  => 'narrow',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_content_style',
				'type'     => 'radio',
				'title'    => esc_html__( 'Content', 'saturnwp' ). ' : ' .esc_html__( 'Style', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'classic',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_socials_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Footer Social Icon Background color', 'saturnwp' ),
				'default'  => '#000000',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),			
			array(
				'id'       => 'footer_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Background color', 'saturnwp' ),
				'default'  => '#f3f7fc',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_lower_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Lower part', 'saturnwp' ). ' : ' .esc_html__( 'Background color', 'saturnwp' ),
				'desc'     => esc_html__( 'If you want to have a different color in the lower part than in the footer widgets.', 'saturnwp' ),
				'default'  => '#ebf1f8',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_widgets_color',
				'type'     => 'radio',
				'title'    => esc_html__( 'Widgets colors', 'saturnwp' ),
				'desc'     => esc_html__( 'Depending on what background you have set up, choose proper option.', 'saturnwp' ),
				'options'  => array(
					'dark-sidebar'  => esc_html__( 'On dark', 'saturnwp' ),
					'light-sidebar' => esc_html__( 'On light', 'saturnwp' ),
				),
				'default'  => 'dark-sidebar',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_font_size',
				'type'     => 'slider',
				'title'    => esc_html__( 'Lower part', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'default'  => 10,
				'min'      => 10,
				'max'      => 30,
				'step'     => 1,
				'unit'     => 'px',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'       => 'footer_widgets_font_size',
				'type'     => 'slider',
				'title'    => esc_html__( 'Widgets part', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'default'  => 10,
				'min'      => 10,
				'max'      => 30,
				'step'     => 1,
				'unit'     => 'px',
				'required' => array( 'footer_switch', '=', 'on' ),
				'partial'  => true,
			),
			array(
				'id'          => 'footer_font_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Lower part', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'description' => esc_html__( 'Does not work for footer widgets.', 'saturnwp' ),
				'default'     => '#282828',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
			array(
				'id'          => 'footer_link_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Lower part', 'saturnwp' ). ' : ' .esc_html__( 'Links', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'description' => esc_html__( 'Does not work for footer widgets.', 'saturnwp' ),
				'default'     => '#fe4c1c',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
			array(
				'id'          => 'footer_hover_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Lower part', 'saturnwp' ). ' : ' .esc_html__( 'Links', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'description' => esc_html__( 'Does not work for footer widgets.', 'saturnwp' ),
				'default'     => '#ffffff',
				'required'    => array( 'footer_switch', '=', 'on' ),
				'partial'     => true,
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Hidden sidebar', 'saturnwp' ),
		'desc'       => esc_html__( 'It is active only if it contains active widgets. After activation, displays the opening button in the header.', 'saturnwp' ),
		'id'         => 'subsection_hidden_sidebar',
		'icon'       => 'fa fa-columns',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'      => 'hidden_sidebar_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ),
				'default' => '',
			),
			array(
				'id'      => 'hidden_sidebar_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Font size', 'saturnwp' ),
				'default' => 10,
				'min'     => 5,
				'max'     => 30,
				'step'    => 1,
				'unit'    => 'px',
			),
			array(
				'id'          => 'hidden_sidebar_widgets_color',
				'type'        => 'radio',
				'title'       => esc_html__( 'Widgets colors', 'saturnwp' ),
				'description' => esc_html__( 'Depending on what background you have set up, choose proper option.', 'saturnwp' ),
				'options'     => array(
					'dark-sidebar'  => esc_html__( 'On dark', 'saturnwp' ),
					'light-sidebar' => esc_html__( 'On light', 'saturnwp' ),
				),
				'default'     => 'dark-sidebar',
			),
			array(
				'id'      => 'hidden_sidebar_side',
				'type'    => 'radio',
				'title'   => esc_html__( 'Side', 'saturnwp' ),
				'options' => array(
					'left'  => esc_html__( 'Left', 'saturnwp' ),
					'right' => esc_html__( 'Right', 'saturnwp' ),
				),
				'default' => 'left',
			),
			array(
				'id'      => 'hidden_sidebar_effect',
				'type'    => 'select',
				'title'   => esc_html__( 'Opening effect', 'saturnwp' ),
				'options' => array(
					'1' => esc_html__( 'Slide in on top', 'saturnwp' ),
					'2' => esc_html__( 'Reveal', 'saturnwp' ),
					'3' => esc_html__( 'Push', 'saturnwp' ),
					'4' => esc_html__( 'Slide along', 'saturnwp' ),
					'5' => esc_html__( 'Reverse slide out', 'saturnwp' ),
					'6' => esc_html__( 'Fall down', 'saturnwp' ),
				),
				'default' => '2',
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Fonts settings', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_fonts_settings',
		'icon'       => 'fa fa-font',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'nav_menu_fonts',
				'type'        => 'font',
				'title'       => esc_html__( 'Font for main navigation menu and overlay menu:', 'saturnwp' ),
				'default'     => array(
					'font-family'    => 'Poppins',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal'
				),
			),
			array(
				'id'          => 'titles_fonts',
				'type'        => 'font',
				'title'       => esc_html__( 'Font for Titles/Headings:', 'saturnwp' ),
				'default'     => array(
					'font-family'    => 'Poppins',
					'font-weight'    => '700',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal'
				),
			),
			array(
				'id'          => 'normal_fonts',
				'type'        => 'font',
				'title'       => esc_html__( 'Font for normal(content) text:', 'saturnwp' ),
				'default'     => array(
					'font-family'    => 'Poppins',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal'
				),
			),
			array(
				'id'      => 'logo_fonts',
				'type'    => 'font',
				'title'   => esc_html__( 'Font for text logo:', 'saturnwp' ),
				'default' => array(
					'font-family'    => 'Poppins',
					'word-spacing'   => 'normal',
					'letter-spacing' => 'normal'
				),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Headings', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_heading_styles',
		'icon'       => 'fa fa-header',
		'subsection' => true,
		'fields'     => array(

			array(
				'id'      => 'headings_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ),
				'default' => '',
			),
			array(
				'id'      => 'headings_color_hover',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '',
			),
			array(
				'id'      => 'headings_weight',
				'type'    => 'select',
				'title'   => esc_html__( 'Font weight', 'saturnwp' ),
				'options' => $font_weights,
				'default' => 'bold',
			),
			array(
				'id'      => 'headings_transform',
				'type'    => 'radio',
				'title'   => esc_html__( 'Text transform', 'saturnwp' ),
				'options' => $font_transforms,
				'default' => 'none',
			),
			array(
				'id'      => 'page_title_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Main titles', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'default' => 36,
				'min'     => 10,
				'step'    => 1,
				'max'     => 60,
				'unit'    => 'px',
			),
			array(
				'id'          => 'page_title_font_size_768',
				'type'        => 'slider',
				'title'       => esc_html__( 'Main titles', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ). ' - ' .esc_html__( 'on mobile devices', 'saturnwp' ),
				'description' => esc_html__( 'It will be used on devices less than 768 pixels wide.', 'saturnwp' ),
				'min'         => 10,
				'max'         => 60,
				'step'        => 1,
				'unit'        => 'px',
				'default'     => 32,
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Content', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_content_styles',
		'icon'       => 'fa fa-align-left',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'content_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Background color', 'saturnwp' ),
				'description' => esc_html__( 'It will change the default white background color that is set under content on pages, blog, posts', 'saturnwp' ),
				'default'     => '#f7f7f7',
			),
			array(
				'id'      => 'content_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ),
				'default' => '#000000',
			),
			array(
				'id'      => 'content_link_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Links', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default' => '',
			),
			array(
				'id'      => 'content_link_color_hover',
				'type'    => 'color',
				'title'   => esc_html__( 'Links', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '',
			),
			array(
				'id'      => 'content_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Font size', 'saturnwp' ),
				'default' => 16,
				'min'     => 10,
				'step'    => 1,
				'max'     => 30,
				'unit'    => 'px',
			),
			array(
				'id'          => 'first_paragraph',
				'type'        => 'radio',
				'title'       => esc_html__( 'First paragraph', 'saturnwp' ). ' : ' .esc_html__( 'Highlight', 'saturnwp' ),
				'description' => esc_html__( 'If enabled, it highlights(font size and color) the first paragraph in the content(blog, post, page).', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'       => 'first_paragraph_color',
				'type'     => 'color',
				'title'    => esc_html__( 'First paragraph', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'first_paragraph', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Social icons', 'saturnwp' ),
		'desc'       => esc_html__( 'These icons will be used in various places across theme if you decide to activate them.', 'saturnwp' ),
		'id'         => 'section_social',
		'icon'       => 'fa fa-facebook-official',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'socials_variant',
				'type'    => 'radio',
				'title'   => esc_html__( 'Type of icons', 'saturnwp' ),
				'options' => array(
					'squares'    => esc_html__( 'Squares', 'saturnwp' ),
					'circles'    => esc_html__( 'Circles', 'saturnwp' ),
					'icons-only' => esc_html__( 'Only icons', 'saturnwp' ),
				),
				'default' => 'squares',
			),
			array(
				'id'          => 'social_services',
				'type'        => 'socials',
				'title'       => esc_html__( 'Links', 'saturnwp' ),
				'description' => esc_html__( 'Drag and drop to change order of icons. Only filled links will show up as social icons.', 'saturnwp' ),
				'label'       => false,
				'options'     => $saturnwp_a13->saturnwp_get_social_icons_list( 'names' ),
				'default'     => $saturnwp_a13->saturnwp_get_social_icons_list( 'empty' )
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Lightbox settings', 'saturnwp' ),
		'desc'       => esc_html__( 'If you wish to use some other plugin/script for images and items, you can switch off default theme lightbox. If you are planning to use different lightbox script instead, then you will have to do some extra work with scripting to make it work in every theme place.', 'saturnwp' ),
		'id'         => 'subsection_lightbox',
		'icon'       => 'fa fa-picture-o',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'skt_lightbox',
				'type'        => 'radio',
				'title'       => esc_html__( 'Theme lightbox', 'saturnwp' ),
				'options'     => array(
					'lightGallery' => esc_html__( 'Light Gallery', 'saturnwp' ),
					'off'          => esc_html__( 'Disable', 'saturnwp' ),
				),
				'default'     => 'lightGallery',
			),
			array(
				'id'          => 'lightbox_single_post',
				'type'        => 'radio',
				'title'       => esc_html__( 'Use theme lightbox for images in posts', 'saturnwp' ),
				'description' => esc_html__( 'If enabled, the theme will use a lightbox to display images in the post content. To work properly, Images should link to "Media File".', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'off',
				'required'    => array( 'skt_lightbox', '=', 'lightGallery' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Widgets', 'saturnwp' ),
		'id'         => 'subsection_widgets',
		'icon'       => 'fa fa-puzzle-piece',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'widgets_top_margin',
				'type'        => 'radio',
				'title'       => esc_html__( 'Top margin', 'saturnwp' ),
				'description' => esc_html__( 'It only affects the widgets in the vertical sidebars.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'      => 'widget_title_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Main titles', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'max'     => 60,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 20,
			),
			array(
				'id'          => 'widget_font_size',
				'type'        => 'slider',
				'title'       => esc_html__( 'Content', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'description' => esc_html__( 'It only affects the widgets in the vertical sidebars.', 'saturnwp' ),
				'min'         => 5,
				'max'         => 30,
				'step'        => 1,
				'unit'        => 'px',
				'default'     => 16,
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'To top button', 'saturnwp' ),
		'id'         => 'subsection_to_top',
		'icon'       => 'fa fa-chevron-up',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'to_top',
				'type'        => 'radio',
				'title'       => esc_html__( 'To top button', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'      => 'to_top_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ),
				'default' => '#524F51',
				'required' => array( 'to_top', '=', 'on' ),
			),
			array(
				'id'      => 'to_top_bg_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#fe4c1c',
				'required' => array( 'to_top', '=', 'on' ),
			),
			array(
				'id'      => 'to_top_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Icon', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'default' => '#cccccc',
				'required' => array( 'to_top', '=', 'on' ),
			),
			array(
				'id'      => 'to_top_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Icon', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#ffffff',
				'required' => array( 'to_top', '=', 'on' ),
			),
			array(
				'id'      => 'to_top_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Icon', 'saturnwp' ). ' : ' .esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'step'    => 1,
				'max'     => 60,
				'unit'    => 'px',
				'default' => 13,
				'required' => array( 'to_top', '=', 'on' ),
			),
			array(
				'id'          => 'to_top_icon',
				'type'        => 'text',
				'title'       => esc_html__( 'Icon', 'saturnwp' ),
				'default'     => 'chevron-up',
				'input_attrs' => array(
					'class' => 'a13-fa-icon',
				),
				'required' => array( 'to_top', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Buttons', 'saturnwp' ),
		'desc'       => esc_html__( 'You can change here colors of buttons that submit forms. For shop buttons, go to the shop settings.', 'saturnwp' ),
		'id'         => 'subsection_buttons',
		'icon'       => 'fa fa-arrow-down',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'button_submit_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ),
				'default' => '#524F51',
			),
			array(
				'id'      => 'button_submit_bg_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#000000',
			),
			array(
				'id'      => 'button_submit_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ),
				'default' => '#cccccc'
			),
			array(
				'id'      => 'button_submit_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#ffffff'
			),
			array(
				'id'      => 'button_submit_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'max'     => 60,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 13,
			),
			array(
				'id'      => 'button_submit_weight',
				'type'    => 'select',
				'title'   => esc_html__( 'Font weight', 'saturnwp' ),
				'options' => $font_weights,
				'default' => 'bold',
			),
			array(
				'id'      => 'button_submit_transform',
				'type'    => 'radio',
				'title'   => esc_html__( 'Text transform', 'saturnwp' ),
				'options' => $font_transforms,
				'default' => 'uppercase',
			),
			array(
				'id'      => 'button_submit_padding',
				'type'    => 'spacing',
				'title'   => esc_html__( 'Padding', 'saturnwp' ),
				'mode'    => 'padding',
				'sides'   => array( 'left', 'right' ),
				'units'   => array( 'px', 'em' ),
				'default' => array(
					'padding-left'  => '30px',
					'padding-right' => '30px',
					'units'         => 'px'
				),
			),
			array(
				'id'          => 'button_submit_radius',
				'type'        => 'slider',
				'title'       => esc_html__( 'Border radius', 'saturnwp' ),
				'min'         => 0,
				'max'         => 20,
				'step'        => 1,
				'unit'        => 'px',
				'default'     => 20,
			),
		)
	) );

//HEADER SETTINGS
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'    => esc_html__( 'Header settings', 'saturnwp' ),
		'desc'     => '',
		'id'       => 'section_header_settings',
		'icon'     => 'el el-magic',
		'priority' => 6,
		'fields'   => array()
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Type, variant, background', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_header_type',
		'icon'       => 'fa fa-cogs',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'header_type',
				'type'        => 'radio',
				'title'       => esc_html__( 'Type', 'saturnwp' ),
				'options'     => array(
					'horizontal' => esc_html__( 'Horizontal', 'saturnwp' ),
				),
				'default'     => 'horizontal',
			),
			array(
				'id'       => 'header_horizontal_sticky',
				'type'     => 'select',
				'title'    => esc_html__( 'Header Type', 'saturnwp' ),
				'options'  => array(
					'no-sticky no-fixed' => esc_html__( 'Disabled, show only default header(not fixed)', 'saturnwp' ),
					'no-sticky'          => esc_html__( 'Disabled, show only default header fixed', 'saturnwp' )
				),
				'default'  => 'no-sticky no-fixed',
				'required' => array( 'header_type', '=', 'horizontal' ),
			),
			/* Header Layout */
						array(
				'id'          => 'header_layout',
				'type'        => 'select',
				'title'       => esc_html__( 'Header Layout', 'saturnwp' ),
				'options'     => array(
					'header_layout_one' => esc_html__( 'Header Layout One', 'saturnwp' ),
					'header_layout_two' => esc_html__( 'Header Layout Two', 'saturnwp' ),
				),
				'default'     => 'header_layout_one',
			),
			/* Header Layout */			
			array(
				'id'       => 'header_content_width',
				'type'     => 'radio',
				'title'    => esc_html__( 'Content width', 'saturnwp' ),
				'options'  => array(
					'narrow' => esc_html__( 'Narrow', 'saturnwp' ),
					'full'   => esc_html__( 'Full width', 'saturnwp' ),
				),
				'default'  => 'full',
				'required' => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'       => 'header_content_width_narrow_bg',
				'type'     => 'radio',
				'title'    => esc_html__( 'Narrow the entire header as well', 'saturnwp' ),
				'options'  => $on_off,
				'default'  => 'off',
				'required' => array(
					array( 'header_type', '=', 'horizontal' ),
					array( 'header_content_width', '=', 'narrow' )
				),
			),
			array(
				'id'      => 'header_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ),
				'default' => '#ffffff',
			),
			array(
				'id'          => 'header_bg_hover_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Background color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'description' => esc_html__( 'Useful in special cases, like when you make a transparent header.', 'saturnwp' ),
				'default'     => '',
			),
			array(
				'id'       => 'header_social_bgcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Header Social Area Bg Color', 'saturnwp' ),
				'default'  => '#fe911c',
				'required' => array( 'header_type', '=', 'horizontal' ),
			),		
			array(
				'id'       => 'topbar_bgcolor',
				'type'     => 'color',
				'title'    => esc_html__( 'Topbar Bg Color', 'saturnwp' ),
				'default'  => '#fe911c',
				'required' => array( 'header_type', '=', 'horizontal' ),
			),				
			array(
				'id'       => 'topbar_txt_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Topbar Text Color', 'saturnwp' ),
				'default'  => '#fe911c',
				'required' => array( 'header_type', '=', 'horizontal' ),
			),							
			array(
				'id'          => 'header_socials',
				'type'        => 'radio',
				'title'       => esc_html__( 'Social icons', 'saturnwp' ),
				/* translators: %s: URL */
				'description' => sprintf( wp_kses( __( 'If you need to edit social links go to <a href="%s">Social icons</a> settings.', 'saturnwp' ), $valid_tags ), 'javascript:wp.customize.section( \'section_social\' ).focus();' ),
				'options'     => $on_off,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'       => 'header_socials_color',
				'type'     => 'select',
				'title'    => esc_html__( 'Social icons', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ),
				'options'  => $social_colors,
				'default'  => 'white',
				'required' => array(
					array( 'header_type', '=', 'horizontal' ),
					array( 'header_socials', '=', 'on' ),
				)
			),
			array(
				'id'       => 'header_socials_color_hover',
				'type'     => 'select',
				'title'    => esc_html__( 'Social icons', 'saturnwp' ). ' : ' .esc_html__( 'Color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'options'  => $social_colors,
				'default'  => 'color',
				'required' => array(
					array( 'header_type', '=', 'horizontal' ),
					array( 'header_socials', '=', 'on' ),
				)
			),
			array(
				'id'          => 'header_socials_display_on_mobile',
				'type'        => 'radio',
				'title'       => esc_html__( 'Social icons', 'saturnwp' ). ' - ' .esc_html__( 'Display it on mobiles', 'saturnwp' ),
				'description' => esc_html__( 'Should it be displayed on devices less than 600 pixels wide.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
				'required'    => array(
					array( 'header_type', '=', 'horizontal' ),
					array( 'header_socials', '=', 'on' ),
				)
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Logo', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_logo',
		'icon'       => 'fa fa-picture-o',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'logo_type',
				'type'    => 'radio',
				'title'   => esc_html__( 'Type', 'saturnwp' ),
				'options' => array(
					'image' => esc_html__( 'Image', 'saturnwp' ),
					'text'  => esc_html__( 'Text', 'saturnwp' ),
				),
				'default' => 'image',
			),
			array(
				'id'          => 'logo_image',
				'type'        => 'image',
				'title'       => esc_html__( 'Image', 'saturnwp' ),
				'description' => esc_html__( 'Upload an image for logo.', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'logo_type', '=', 'image' ),
			),
			array(
				'id'          => 'logo_image_high_dpi',
				'type'        => 'image',
				'title'       => esc_html__( 'Image for HIGH DPI screen', 'saturnwp' ),
				'description' => esc_html__( 'For example Retina(iPhone/iPad) screen has HIGH DPI screen.', 'saturnwp' ) . ' ' . esc_html__( 'Upload an image for logo.', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'logo_type', '=', 'image' ),
			),
			array(
				'id'          => 'logo_image_max_desktop_width',
				'type'        => 'slider',
				'title'       => esc_html__( 'Max width', 'saturnwp' ). ' - ' .esc_html__( 'on desktop', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ) .' '. esc_html__( 'It works only on large screens(1025px wide or more).', 'saturnwp' ),
				'min'         => 25,
				'step'        => 1,
				'max'         => 400,
				'unit'        => 'px',
				'default'     => 200,
				'required'    => array(
					array( 'logo_type', '=', 'image' ),
					array( 'header_type', '=', 'horizontal' ),
				)
			),
			array(
				'id'          => 'logo_image_max_mobile_width',
				'type'        => 'slider',
				'title'       => esc_html__( 'Max width', 'saturnwp' ). ' - ' .esc_html__( 'on mobile devices', 'saturnwp' ),
				'description' => esc_html__( 'It works only on mobile devices(1024px wide or less).', 'saturnwp' ),
				'min'         => 25,
				'max'         => 250,
				'step'        => 1,
				'unit'        => 'px',
				'default'     => 200,
				'required'    => array(
					array( 'logo_type', '=', 'image' ),
				)
			),
			array(
				'id'          => 'logo_image_height',
				'type'        => 'slider',
				'title'       => esc_html__( 'Height', 'saturnwp' ),
				'description' => esc_html__( 'Leave empty if you do not need anything fancy', 'saturnwp' ),
				'min'         => 0,
				'max'         => 100,
				'step'        => 1,
				'unit'        => 'px',
				'default'     => '',
				'required'    => array( 'logo_type', '=', 'image' ),
			),
			array(
				'id'       => 'logo_image_normal_opacity',
				'type'     => 'slider',
				'title'    => esc_html__( 'Opacity', 'saturnwp' ),
				'min'      => 0,
				'max'      => 1,
				'step'     => 0.01,
				'default'  => '1.00',
				'required' => array( 'logo_type', '=', 'image' ),
			),
			array(
				'id'       => 'logo_image_hover_opacity',
				'type'     => 'slider',
				'title'    => esc_html__( 'Opacity', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'min'      => 0,
				'max'      => 1,
				'step'     => 0.01,
				'default'  => '1.00',
				'required' => array( 'logo_type', '=', 'image' ),
			),
			array(
				'id'          => 'logo_text',
				'type'        => 'text',
				'title'       => esc_html__( 'Text', 'saturnwp' ),
				'description' => wp_kses( __( 'If you use more than one word in the logo, you can use <code>&amp;nbsp;</code> instead of a white space, so the words will not break into many lines.', 'saturnwp' ), $valid_tags ).
				                 /* translators: %s: Customizer JS URL */
				                 '<br />'.sprintf( wp_kses( __( 'If you want to change the font for logo go to <a href="%s">Font settings</a>.', 'saturnwp' ), $valid_tags ), 'javascript:wp.customize.control( \''.SATURNWP_OPTIONS_NAME.'[logo_fonts]\' ).focus();' ),
				'default'     => get_bloginfo( 'name' ),
				'required'    => array( 'logo_type', '=', 'text' ),
			),
			array(
				'id'       => 'logo_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'logo_type', '=', 'text' ),
			),
			array(
				'id'       => 'logo_color_hover',
				'type'     => 'color',
				'title'    => esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'logo_type', '=', 'text' ),
			),
			array(
				'id'       => 'logo_font_size',
				'type'     => 'slider',
				'title'    => esc_html__( 'Font size', 'saturnwp' ),
				'min'      => 10,
				'max'      => 60,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => 22,
				'required' => array( 'logo_type', '=', 'text' ),
			),
			array(
				'id'       => 'logo_weight',
				'type'     => 'select',
				'title'    => esc_html__( 'Font weight', 'saturnwp' ),
				'options'  => $font_weights,
				'default'  => 'normal',
				'required' => array( 'logo_type', '=', 'text' ),
			),
			array(
				'id'          => 'logo_padding',
				'type'        => 'spacing',
				'title'       => esc_html__( 'Padding', 'saturnwp' ). ' - ' .esc_html__( 'on desktop', 'saturnwp' ),
				'description' => esc_html__( 'It works only on large screens(1025px wide or more).', 'saturnwp' ),
				'mode'        => 'padding',
				'sides'       => array( 'top', 'bottom' ),
				'units'       => array( 'px', 'em' ),
				'default'     => array(
					'padding-top'    => '30px',
					'padding-bottom' => '20px',
					'units'          => 'px'
				)
			),
			array(
				'id'          => 'logo_padding_mobile',
				'type'        => 'spacing',
				'title'       => esc_html__( 'Padding', 'saturnwp' ). ' - ' .esc_html__( 'on mobile devices', 'saturnwp' ),
				'description' => esc_html__( 'It works only on mobile devices(1024px wide or less).', 'saturnwp' ),
				'mode'        => 'padding',
				'sides'       => array( 'top', 'bottom' ),
				'units'       => array( 'px', 'em' ),
				'default'     => array(
					'padding-top'    => '10px',
					'padding-bottom' => '10px',
					'units'          => 'px'
				)
			),
			array(
				'id'      => 'logo_bg_effect',
				'type'    => 'radio',
				'title'   => esc_html__( 'Logo Background', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),		
           array(
				'id'       => 'logo_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Logo Background Color', 'saturnwp' ),
				'default'  => '#f4f6fd',
				'required' => array( 'header_type', '=', 'horizontal' ),
			),				
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Main Menu', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_header_menu',
		'icon'       => 'fa fa-bars',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'menu_font_size',
				'type'     => 'slider',
				'title'    => esc_html__( 'Font size', 'saturnwp' ),
				'min'      => 10,
				'max'      => 30,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => 16,
				'required' => array( 'header_main_menu', '=', 'on' ),
			),
			array(
				'id'       => 'menu_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Links', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '#333333',
				'required' => array( 'header_main_menu', '=', 'on' ),
			),
			array(
				'id'       => 'menu_hover_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Links', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover/active', 'saturnwp' ),
				'default'  => '#fe4c1c',
				'required' => array( 'header_main_menu', '=', 'on' ),
			),
			array(
				'id'       => 'menu_weight',
				'type'     => 'select',
				'title'    => esc_html__( 'Font weight', 'saturnwp' ),
				'options'  => $font_weights,
				'default'  => 'normal',
				'required' => array( 'header_main_menu', '=', 'on' ),
			),
			array(
				'id'       => 'menu_transform',
				'type'     => 'radio',
				'title'    => esc_html__( 'Text transform', 'saturnwp' ),
				'options'  => $font_transforms,
				'default'  => 'uppercase',
				'required' => array( 'header_main_menu', '=', 'on' ),
			),
			array(
				'id'       => 'submenu_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Submenu', 'saturnwp' ). ' : ' .esc_html__( 'Background color', 'saturnwp' ),
				'default'  => '#ffffff',
				'required' => array( 'header_main_menu', '=', 'on' ),
			),
			array(
				'id'       => 'submenu_hover_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Submenu', 'saturnwp' ). ' : ' .esc_html__( 'Background Hover color', 'saturnwp' ),
				'default'  => '#282828',
				'required' => array( 'header_main_menu', '=', 'on' ),
			),						 
		)
	) );

//BLOG SETTINGS
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'    => esc_html__( 'Blog settings', 'saturnwp' ),
		'desc'     => esc_html__( 'Posts list refers to Blog, Search and Archives pages', 'saturnwp' ),
		'id'       => 'section_blog_layout',
		'icon'     => 'fa fa-pencil',
		'priority' => 9,
		'fields'   => array()
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Background', 'saturnwp' ),
		'id'         => 'subsection_blog_bg',
		'desc'       => esc_html__( 'This will be the default background for pages related to the blog.', 'saturnwp' ),
		'icon'       => 'fa fa-picture-o',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'blog_custom_background',
				'type'    => 'radio',
				'title'   => esc_html__( 'Custom background', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'off',
			),
			array(
				'id'       => 'blog_body_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'required' => array( 'blog_custom_background', '=', 'on' ),
			),
			array(
				'id'       => 'blog_body_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'cover',
				'required' => array( 'blog_custom_background', '=', 'on' ),
			),
			array(
				'id'       => 'blog_body_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Background color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'blog_custom_background', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Posts list', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_blog',
		'icon'       => 'fa fa-list',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'blog_content_under_header',
				'type'        => 'select',
				'title'       => esc_html__( 'Hide content under the header', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ),
				'options'     => $content_under_header,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'      => 'blog_content_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Content Layout', 'saturnwp' ),
				'options' => $content_layouts,
				'default' => 'center',
			),
			array(
				'id'      => 'blog_content_padding',
				'type'    => 'select',
				'title'   => esc_html__( 'Content', 'saturnwp' ). ' : ' .esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'options' => array(
					'both'   => esc_html__( 'Both on', 'saturnwp' ),
					'top'    => esc_html__( 'Only top', 'saturnwp' ),
					'bottom' => esc_html__( 'Only bottom', 'saturnwp' ),
					'off'    => esc_html__( 'Both off', 'saturnwp' ),
				),
				'default' => 'off',
			),
			array(
				'id'      => 'blog_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Sidebar', 'saturnwp' ),
				'options' => array(
					'left-sidebar'  => esc_html__( 'Left', 'saturnwp' ),
					'right-sidebar' => esc_html__( 'Right', 'saturnwp' ),
					'off'           => esc_html__( 'Off', 'saturnwp' ),
				),
				'default' => 'off',
			),
			array(
				'id'      => 'blog_post_look',
				'type'    => 'select',
				'title'   => esc_html__( 'Post look', 'saturnwp' ),
				'options' => array(
					'vertical_no_padding' => esc_html__( 'Vertical no padding', 'saturnwp' ),
					'vertical_padding'    => esc_html__( 'Vertical with padding', 'saturnwp' ),
					'vertical_centered'   => esc_html__( 'Vertical centered', 'saturnwp' ),
					'horizontal'          => esc_html__( 'Horizontal', 'saturnwp' ),
				),
				'default' => 'vertical_padding',
			),
			array(
				'id'          => 'blog_layout_mode',
				'type'        => 'radio',
				'title'       => esc_html__( 'How to place items in rows', 'saturnwp' ),
				'description' => esc_html__( 'If your items have different heights, you can start each row of items from a new line instead of the masonry style.', 'saturnwp' ),
				'options'     => array(
					'packery' => esc_html__( 'Masonry', 'saturnwp' ),
					'fitRows' => esc_html__( 'Each row from new line', 'saturnwp' ),
				),
				'default'     => 'packery',
			),
			array(
				'id'          => 'blog_brick_columns',
				'type'        => 'slider',
				'title'       => esc_html__( 'Bricks columns', 'saturnwp' ),
				'description' => esc_html__( 'It is a maximum number of columns displayed on larger devices. On smaller devices, it can be a lower number of columns.', 'saturnwp' ),
				'min'         => 1,
				'max'         => 4,
				'step'        => 1,
				'unit'        => '',
				'default'     => 2,
				'required'    => array( 'blog_post_look', '!=', 'horizontal' ),
			),
			array(
				'id'          => 'blog_bricks_max_width',
				'type'        => 'slider',
				'title'       => esc_html__( 'The maximum width of the brick layout', 'saturnwp' ),
				'description' => esc_html__( 'Depending on the actual width of the screen, the available space for bricks may be smaller, but never greater than this number.', 'saturnwp' ),
				'min'         => 200,
				'max'         => 2500,
				'step'        => 1,
				'unit'        => 'px',
				'default'     => 1920,
				'required'    => array( 'blog_post_look', '!=', 'horizontal' ),
			),
			array(
				'id'      => 'blog_brick_margin',
				'type'    => 'slider',
				'title'   => esc_html__( 'Brick margin', 'saturnwp' ),
				'min'     => 0,
				'max'     => 100,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 10,
			),
			array(
				'id'      => 'blog_lazy_load',
				'type'    => 'radio',
				'title'   => esc_html__( 'Lazy load', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'blog_lazy_load_mode',
				'type'     => 'radio',
				'title'    => esc_html__( 'Lazy load', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'options'  => array(
					'button' => esc_html__( 'By clicking button', 'saturnwp' ),
					'auto'   => esc_html__( 'On scroll', 'saturnwp' ),
				),
				'default'  => 'button',
				'required' => array( 'blog_lazy_load', '=', 'on' ),
			),
			array(
				'id'          => 'blog_read_more',
				'type'        => 'radio',
				'title'       => esc_html__( 'Display "Read more" link', 'saturnwp' ),
				'description' => esc_html__( 'Should "Read more" link be displayed after excerpts on blog list/search results.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'blog_excerpt_type',
				'type'        => 'radio',
				'title'       => esc_html__( 'Type of post excerpts', 'saturnwp' ),
				'description' => wp_kses( __(
					'In the Manual mode, excerpts are used only if you add the "Read More Tag" (&lt;!--more--&gt;).<br /> In the Automatic mode, if you will not provide the "Read More Tag" or explicit excerpt, the content of the post will be truncated automatically.<br /> This setting only concerns blog list, archive list, search results.', 'saturnwp' ), $valid_tags ),
				'options'     => array(
					'auto'   => esc_html__( 'Automatic', 'saturnwp' ),
					'manual' => esc_html__( 'Manual', 'saturnwp' ),
				),
				'default'     => 'auto',
			),
			array(
				'id'          => 'blog_excerpt_length',
				'type'        => 'slider',
				'title'       => esc_html__( 'Number of words to cut post', 'saturnwp' ),
				'description' => esc_html__( 'After this many words post will be cut in the automatic mode.', 'saturnwp' ),
				'min'         => 3,
				'max'         => 200,
				'step'        => 1,
				'unit'        => '',
				'default'     => 40,
				'required'    => array( 'blog_excerpt_type', '=', 'auto' ),
			),
			array(
				'id'          => 'blog_media',
				'type'        => 'radio',
				'title'       => esc_html__( 'Display post Media', 'saturnwp' ),
				'description' => esc_html__( 'You can set to not display post media(featured image/video/slider) inside of the post brick.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'blog_videos',
				'type'        => 'radio',
				'title'       => esc_html__( 'Display of posts video', 'saturnwp' ),
				'description' => esc_html__( 'You can set to display videos as featured image on posts list. This can speed up loading of pages with many posts(blog, archive, search results) when the videos are used.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'blog_date',
				'type'        => 'radio',
				'title'       => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Date of publish or last update', 'saturnwp' ),
				'description' => esc_html__( 'You can\'t use both dates, because the Search Engine will not know which date is correct.', 'saturnwp' ),
				'options'     => array(
					'on'      => esc_html__( 'Published', 'saturnwp' ),
					'updated' => esc_html__( 'Updated', 'saturnwp' ),
					'off'     => esc_html__( 'Disable', 'saturnwp' ),
				),
				'default'     => 'on',
			),
			array(
				'id'      => 'blog_author',
				'type'    => 'radio',
				'title'   => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Author', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'      => 'blog_comments',
				'type'    => 'radio',
				'title'   => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Comments number', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'      => 'blog_cats',
				'type'    => 'radio',
				'title'   => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Categories', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'          => 'blog_tags',
				'type'        => 'radio',
				'title'       => esc_html__( 'Tags', 'saturnwp' ),
				'description' => esc_html__( 'Displays list of post tags under a post content.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'off',
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Posts list', 'saturnwp' ). ' - ' .esc_html__( 'Title bar', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_blog_title',
		'icon'       => 'fa fa-text-width',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'blog_title',
				'type'    => 'radio',
				'title'   => esc_html__( 'Title', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'blog_title_bar_variant',
				'type'     => 'radio',
				'title'    => esc_html__( 'Variant', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic(to side)', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'centered',
				'required' => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'       => 'blog_title_bar_width',
				'type'     => 'radio',
				'title'    => esc_html__( 'Width', 'saturnwp' ),
				'options'  => array(
					'full'  => esc_html__( 'Full', 'saturnwp' ),
					'boxed' => esc_html__( 'Boxed', 'saturnwp' ),
				),
				'default'  => 'full',
				'required' => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'       => 'blog_title_bar_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'       => 'blog_title_bar_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'repeat',
				'required' => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'       => 'blog_title_bar_parallax',
				'type'     => 'radio',
				'title'    => esc_html__( 'Parallax', 'saturnwp' ),
				'options'  => $on_off,
				'default'  => 'off',
				'required' => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'          => 'blog_title_bar_parallax_type',
				'type'        => 'select',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'description' => esc_html__( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'options'     => $parallax_types,
				'default'     => 'tb',
				'required'    => array(
					array( 'blog_title', '=', 'on' ),
					array( 'blog_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'blog_title_bar_parallax_speed',
				'type'        => 'slider',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Speed', 'saturnwp' ),
				'description' => esc_html__( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
				'default'     => '1.00',
				'required'    => array(
					array( 'blog_title', '=', 'on' ),
					array( 'blog_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'blog_title_bar_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Overlay color', 'saturnwp' ),
				'description' => esc_html__( 'Will be placed above the image(if used)', 'saturnwp' ),
				'default'     => '#ffffff',
				'required'    => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'       => 'blog_title_bar_title_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'          => 'blog_title_bar_color_1',
				'type'        => 'color',
				'title'       => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'blog_title', '=', 'on' ),
			),
			array(
				'id'       => 'blog_title_bar_space_width',
				'type'     => 'slider',
				'title'    => esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'min'      => 0,
				'max'      => 600,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => '210',
				'required' => array( 'blog_title', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Single post', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_post',
		'icon'       => 'fa fa-pencil-square',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'post_content_under_header',
				'type'        => 'select',
				'title'       => esc_html__( 'Hide content under the header', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ),
				'options'     => $content_under_header,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'      => 'post_content_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Content Layout', 'saturnwp' ),
				'options' => $content_layouts,
				'default' => 'center',
			),
			array(
				'id'      => 'post_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Sidebar', 'saturnwp' ),
				'options' => array(
					'left-sidebar'  => esc_html__( 'Left', 'saturnwp' ),
					'right-sidebar' => esc_html__( 'Right', 'saturnwp' ),
					'off'           => esc_html__( 'Off', 'saturnwp' ),
				),
				'default' => 'right-sidebar',
			),
			array(
				'id'          => 'post_media',
				'type'        => 'radio',
				'title'       => esc_html__( 'Display post Media', 'saturnwp' ),
				'description' => esc_html__( 'You can set to not display post media(featured image/video/slider) inside of the post.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'post_author_info',
				'type'        => 'radio',
				'title'       => esc_html__( 'Author info', 'saturnwp' ),
				'description' => esc_html__( 'Will show information about author below post content.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'off',
			),
			array(
				'id'          => 'post_date',
				'type'        => 'radio',
				'title'       => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Date of publish or last update', 'saturnwp' ),
				'description' => esc_html__( 'You can\'t use both dates, because the Search Engine will not know which date is correct.', 'saturnwp' ),
				'options'     => array(
					'on'      => esc_html__( 'Published', 'saturnwp' ),
					'updated' => esc_html__( 'Updated', 'saturnwp' ),
					'off'     => esc_html__( 'Disable', 'saturnwp' ),
				),
				'default'     => 'on',
			),
			array(
				'id'      => 'post_author',
				'type'    => 'radio',
				'title'   => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Author', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'      => 'post_comments',
				'type'    => 'radio',
				'title'   => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Comments number', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'      => 'post_cats',
				'type'    => 'radio',
				'title'   => esc_html__( 'Post info', 'saturnwp' ). ' : ' .esc_html__( 'Categories', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'          => 'post_tags',
				'type'        => 'radio',
				'title'       => esc_html__( 'Tags', 'saturnwp' ),
				'description' => esc_html__( 'Displays list of post tags under a post content.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'post_navigation',
				'type'        => 'radio',
				'title'       => esc_html__( 'Posts navigation', 'saturnwp' ),
				'description' => esc_html__( 'Links to next and prev post.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),

		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Single post', 'saturnwp' ). ' - ' .esc_html__( 'Title bar', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_post_title',
		'icon'       => 'fa fa-text-width',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'post_title',
				'type'    => 'radio',
				'title'   => esc_html__( 'Title', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'post_title_bar_position',
				'type'     => 'radio',
				'title'    => esc_html__( 'Title position', 'saturnwp' ),
				'options'  => array(
					'outside' => esc_html__( 'Before the content', 'saturnwp' ),
					'inside'  => esc_html__( 'Inside the content', 'saturnwp' ),
				),
				'default'  => 'inside',
				'required' => array( 'post_title', '=', 'on' ),
			),
			array(
				'id'       => 'post_title_bar_variant',
				'type'     => 'radio',
				'title'    => esc_html__( 'Variant', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic(to side)', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'classic',
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'post_title_bar_width',
				'type'     => 'radio',
				'title'    => esc_html__( 'Width', 'saturnwp' ),
				'options'  => array(
					'full'  => esc_html__( 'Full', 'saturnwp' ),
					'boxed' => esc_html__( 'Boxed', 'saturnwp' ),
				),
				'default'  => 'full',
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'post_title_bar_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'default'  => '',
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'post_title_bar_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'repeat',
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'post_title_bar_parallax',
				'type'     => 'radio',
				'title'    => esc_html__( 'Parallax', 'saturnwp' ),
				'default'  => 'off',
				'options'  => $on_off,
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'          => 'post_title_bar_parallax_type',
				'type'        => 'select',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'description' => esc_html__( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'options'     => $parallax_types,
				'default'     => 'tb',
				'required'    => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
					array( 'post_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'post_title_bar_parallax_speed',
				'type'        => 'slider',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Speed', 'saturnwp' ),
				'description' => esc_html__( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
				'default'     => '1.00',
				'required'    => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
					array( 'post_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'post_title_bar_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Overlay color', 'saturnwp' ),
				'description' => esc_html__( 'Will be placed above the image(if used)', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'post_title', '=', 'on' ),
			),
			array(
				'id'       => 'post_title_bar_title_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'          => 'post_title_bar_color_1',
				'type'        => 'color',
				'title'       => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'     => '',
				'required'    => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'post_title_bar_space_width',
				'type'     => 'slider',
				'title'    => esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'min'      => 0,
				'max'      => 600,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => '250',
				'required' => array(
					array( 'post_title', '=', 'on' ),
					array( 'post_title_bar_position', '!=', 'inside' ),
				)
			),
		)
	) );

//SHOP SETTINGS
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'    => esc_html__( 'Shop(WooCommerce) settings', 'saturnwp' ),
		'desc'     => '',
		'id'       => 'section_shop_general',
		'icon'     => 'fa fa-shopping-cart',
		'priority' => 12,
		'woocommerce_required' => true,//only visible with WooCommerce plugin being available
		'fields'   => array()
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Background', 'saturnwp' ),
		'desc'       => esc_html__( 'These options will work for all shop pages - product list, single product and other.', 'saturnwp' ),
		'id'         => 'subsection_shop_general',
		'icon'       => 'fa fa-picture-o',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'shop_custom_background',
				'type'    => 'radio',
				'title'   => esc_html__( 'Custom background', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'off',
			),
			array(
				'id'       => 'shop_body_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'required' => array( 'shop_custom_background', '=', 'on' ),
			),
			array(
				'id'       => 'shop_body_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'cover',
				'required' => array( 'shop_custom_background', '=', 'on' ),
			),
			array(
				'id'       => 'shop_body_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Background color', 'saturnwp' ),
				'required' => array( 'shop_custom_background', '=', 'on' ),
				'default'  => '',
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Products list', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_shop',
		'icon'       => 'fa fa-list',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'shop_search',
				'type'        => 'radio',
				'title'       => esc_html__( 'Search in products instead of pages', 'saturnwp' ),
				'description' => esc_html__( 'It will change WordPress default search function to make shop search. So when this is activated search function in header or search widget will act as WooCommerece search widget.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'shop_content_under_header',
				'type'        => 'select',
				'title'       => esc_html__( 'Hide content under the header', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ),
				'options'     => $content_under_header,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'      => 'shop_content_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Content Layout', 'saturnwp' ),
				'options' => $content_layouts,
				'default' => 'center',
			),
			array(
				'id'      => 'shop_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Sidebar', 'saturnwp' ),
				'options' => array(
					'left-sidebar'  => esc_html__( 'Left', 'saturnwp' ),
					'right-sidebar' => esc_html__( 'Right', 'saturnwp' ),
					'off'           => esc_html__( 'Off', 'saturnwp' ),
				),
				'default' => 'right-sidebar',
			),
			array(
				'id'      => 'shop_products_variant',
				'type'    => 'radio',
				'title'   => esc_html__( 'Look of products on list', 'saturnwp' ),
				'options' => array(
					'overlay' => esc_html__( 'Text as overlay', 'saturnwp' ),
					'under'   => esc_html__( 'Text under photo', 'saturnwp' ),
				),
				'default' => 'overlay',
			),
			array(
				'id'       => 'shop_products_subvariant',
				'type'     => 'select',
				'title'    => esc_html__( 'Look of products on list', 'saturnwp' ),
				'options'  => array(
					'left'   => esc_html__( 'Texts to left', 'saturnwp' ),
					'center' => esc_html__( 'Texts to center', 'saturnwp' ),
					'right'  => esc_html__( 'Texts to right', 'saturnwp' ),
				),
				'default'  => 'center',
				'required' => array( 'shop_products_variant', '=', 'under' ),
			),
			array(
				'id'      => 'shop_products_second_image',
				'type'    => 'radio',
				'title'   => esc_html__( 'Show second image of product on hover', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'          => 'shop_products_layout_mode',
				'type'        => 'radio',
				'title'       => esc_html__( 'How to place items in rows', 'saturnwp' ),
				'description' => esc_html__( 'If your items have different heights, you can start each row of items from a new line instead of the masonry style.', 'saturnwp' ),
				'options'     => array(
					'packery' => esc_html__( 'Masonry', 'saturnwp' ),
					'fitRows' => esc_html__( 'Each row from new line', 'saturnwp' ),
				),
				'default'     => 'packery',
			),
			array(
				'id'          => 'shop_products_columns',
				'type'        => 'slider',
				'title'       => esc_html__( 'Bricks columns', 'saturnwp' ),
				'description' => esc_html__( 'It is a maximum number of columns displayed on larger devices. On smaller devices, it can be a lower number of columns.', 'saturnwp' ),
				'min'         => 1,
				'max'         => 4,
				'step'        => 1,
				'unit'        => '',
				'default'     => 4,
			),
			array(
				'id'      => 'shop_products_per_page',
				'type'    => 'slider',
				'title'   => esc_html__( 'Items per page', 'saturnwp' ),
				'min'     => 1,
				'max'     => 30,
				'step'    => 1,
				'unit'    => 'products',
				'default' => 12,
			),
			array(
				'id'      => 'shop_brick_margin',
				'type'    => 'slider',
				'title'   => esc_html__( 'Brick margin', 'saturnwp' ),
				'min'     => 0,
				'max'     => 100,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 20,
			),
			array(
				'id'      => 'shop_lazy_load',
				'type'    => 'radio',
				'title'   => esc_html__( 'Lazy load', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'shop_lazy_load_mode',
				'type'     => 'radio',
				'title'    => esc_html__( 'Lazy load', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'options'  => array(
					'button' => esc_html__( 'By clicking button', 'saturnwp' ),
					'auto'   => esc_html__( 'On scroll', 'saturnwp' ),
				),
				'default'  => 'auto',
				'required' => array( 'shop_lazy_load', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Products list', 'saturnwp' ). ' - ' .esc_html__( 'Title bar', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_shop_title',
		'icon'       => 'fa fa-text-width',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'shop_title',
				'type'    => 'radio',
				'title'   => esc_html__( 'Title', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'shop_title_bar_variant',
				'type'     => 'radio',
				'title'    => esc_html__( 'Variant', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic(to side)', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'classic',
				'required' => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_title_bar_width',
				'type'     => 'radio',
				'title'    => esc_html__( 'Width', 'saturnwp' ),
				'options'  => array(
					'full'  => esc_html__( 'Full', 'saturnwp' ),
					'boxed' => esc_html__( 'Boxed', 'saturnwp' ),
				),
				'default'  => 'full',
				'required' => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_title_bar_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_title_bar_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'repeat',
				'required' => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_title_bar_parallax',
				'type'     => 'radio',
				'title'    => esc_html__( 'Parallax', 'saturnwp' ),
				'options'  => $on_off,
				'default'  => 'off',
				'required' => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'          => 'shop_title_bar_parallax_type',
				'type'        => 'select',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'description' => esc_html__( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'options'     => $parallax_types,
				'default'     => 'tb',
				'required'    => array(
					array( 'shop_title', '=', 'on' ),
					array( 'shop_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'shop_title_bar_parallax_speed',
				'type'        => 'slider',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Speed', 'saturnwp' ),
				'description' => esc_html__( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
				'default'     => '1.00',
				'required'    => array(
					array( 'shop_title', '=', 'on' ),
					array( 'shop_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'shop_title_bar_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Overlay color', 'saturnwp' ),
				'description' => esc_html__( 'Will be placed above the image(if used)', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_title_bar_title_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'          => 'shop_title_bar_color_1',
				'type'        => 'color',
				'title'       => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'shop_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_title_bar_space_width',
				'type'     => 'slider',
				'title'    => esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'min'      => 0,
				'max'      => 600,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => '250',
				'required' => array( 'shop_title', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Single product', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_product',
		'icon'       => 'fa fa-pencil-square',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'product_content_under_header',
				'type'        => 'select',
				'title'       => esc_html__( 'Hide content under the header', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ),
				'options'     => $content_under_header,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'      => 'product_content_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Content Layout', 'saturnwp' ),
				'options' => $content_layouts,
				'default' => 'full_fixed',
			),
			array(
				'id'      => 'product_sidebar',
				'type'    => 'select',
				'title'   => esc_html__( 'Sidebar', 'saturnwp' ),
				'options' => array(
					'left-sidebar'  => esc_html__( 'Left', 'saturnwp' ),
					'right-sidebar' => esc_html__( 'Right', 'saturnwp' ),
					'off'           => esc_html__( 'Off', 'saturnwp' ),
				),
				'default' => 'left-sidebar',
			),
			array(
				'id'          => 'product_custom_thumbs',
				'type'        => 'radio',
				'title'       => esc_html__( 'Theme thumbnails', 'saturnwp' ),
				'description' => esc_html__( 'If disabled it will display standard WooCommerce thumbnails.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
			array(
				'id'          => 'product_related_products',
				'type'        => 'radio',
				'title'       => esc_html__( 'Related products', 'saturnwp' ),
				'description' => esc_html__( 'Should related products be displayed on single product page.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Single product', 'saturnwp' ). ' - ' .esc_html__( 'Title bar', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_product_title',
		'icon'       => 'fa fa-text-width',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'product_title',
				'type'    => 'radio',
				'title'   => esc_html__( 'Title', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'product_title_bar_position',
				'type'     => 'radio',
				'title'    => esc_html__( 'Title position', 'saturnwp' ),
				'options'  => array(
					'outside' => esc_html__( 'Before the content', 'saturnwp' ),
					'inside'  => esc_html__( 'Inside the content', 'saturnwp' ),
				),
				'default'  => 'inside',
				'required' => array( 'product_title', '=', 'on' ),
			),
			array(
				'id'       => 'product_title_bar_variant',
				'type'     => 'radio',
				'title'    => esc_html__( 'Variant', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic(to side)', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'classic',
				'required' => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'product_title_bar_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'default'  => '',
				'required' => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'product_title_bar_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'repeat',
				'required' => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'product_title_bar_parallax',
				'type'     => 'radio',
				'title'    => esc_html__( 'Parallax', 'saturnwp' ),
				'options'  => $on_off,
				'default'  => 'off',
				'required' => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'          => 'product_title_bar_parallax_type',
				'type'        => 'select',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'description' => esc_html__( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'options'     => $parallax_types,
				'default'     => 'tb',
				'required'    => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
					array( 'product_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'product_title_bar_parallax_speed',
				'type'        => 'slider',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Speed', 'saturnwp' ),
				'description' => esc_html__( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
				'default'     => '1.00',
				'required'    => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
					array( 'product_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'product_title_bar_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Overlay color', 'saturnwp' ),
				'description' => esc_html__( 'Will be placed above the image(if used)', 'saturnwp' ),
				'default'     => '',
				'required'    => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'product_title_bar_title_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'          => 'product_title_bar_color_1',
				'type'        => 'color',
				'title'       => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'     => '',
				'required'    => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'product_title_bar_space_width',
				'type'     => 'slider',
				'title'    => esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'min'      => 0,
				'max'      => 600,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => '40',
				'required' => array(
					array( 'product_title', '=', 'on' ),
					array( 'product_title_bar_position', '!=', 'inside' ),
				)
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Other shop pages', 'saturnwp' ),
		'desc'       => esc_html__( 'Settings for cart, checkout, order received and my account pages.', 'saturnwp' ),
		'id'         => 'subsection_shop_no_major_pages',
		'icon'       => 'fa fa-cog',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'shop_no_major_pages_content_under_header',
				'type'        => 'select',
				'title'       => esc_html__( 'Hide content under the header', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ),
				'options'     => $content_under_header,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'      => 'shop_no_major_pages_content_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Content Layout', 'saturnwp' ),
				'options' => $content_layouts,
				'default' => 'full_fixed',
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'desc'       => esc_html__( 'Settings for cart, checkout, order received and my account pages.', 'saturnwp' ),
		'title'      => esc_html__( 'Other shop pages', 'saturnwp' ). ' - ' .esc_html__( 'Title bar', 'saturnwp' ),
		'id'         => 'subsection_shop_no_major_pages_title',
		'icon'       => 'fa fa-text-width',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'shop_no_major_pages_title',
				'type'    => 'radio',
				'title'   => esc_html__( 'Title', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_variant',
				'type'     => 'radio',
				'title'    => esc_html__( 'Variant', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic(to side)', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'classic',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_width',
				'type'     => 'radio',
				'title'    => esc_html__( 'Width', 'saturnwp' ),
				'options'  => array(
					'full'  => esc_html__( 'Full', 'saturnwp' ),
					'boxed' => esc_html__( 'Boxed', 'saturnwp' ),
				),
				'default'  => 'full',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'repeat',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_parallax',
				'type'     => 'radio',
				'title'    => esc_html__( 'Parallax', 'saturnwp' ),
				'options'  => $on_off,
				'default'  => 'off',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'          => 'shop_no_major_pages_title_bar_parallax_type',
				'type'        => 'select',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'description' => esc_html__( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'options'     => $parallax_types,
				'default'     => 'tb',
				'required'    => array(
					array( 'shop_no_major_pages_title', '=', 'on' ),
					array( 'shop_no_major_pages_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'shop_no_major_pages_title_bar_parallax_speed',
				'type'        => 'slider',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Speed', 'saturnwp' ),
				'description' => esc_html__( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
				'default'     => '1.00',
				'required'    => array(
					array( 'shop_no_major_pages_title', '=', 'on' ),
					array( 'shop_no_major_pages_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'shop_no_major_pages_title_bar_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Overlay color', 'saturnwp' ),
				'description' => esc_html__( 'Will be placed above the image(if used)', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_title_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'          => 'shop_no_major_pages_title_bar_color_1',
				'type'        => 'color',
				'title'       => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
			array(
				'id'       => 'shop_no_major_pages_title_bar_space_width',
				'type'     => 'slider',
				'title'    => esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'min'      => 0,
				'max'      => 600,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => '250',
				'required' => array( 'shop_no_major_pages_title', '=', 'on' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Buttons', 'saturnwp' ),
		'desc'       => esc_html__( 'You can change here the colors of buttons used in the shop. Alternative buttons colors are used in various places in the shop.', 'saturnwp' ),
		'id'         => 'subsection_buttons_shop',
		'icon'       => 'fa fa-arrow-down',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'button_shop_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ),
				'default' => '#524F51',
			),
			array(
				'id'      => 'button_shop_bg_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Background color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#000000',
			),
			array(
				'id'      => 'button_shop_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ),
				'default' => '#cccccc'
			),
			array(
				'id'      => 'button_shop_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#ffffff'
			),
			array(
				'id'      => 'button_shop_alt_bg_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Alternative button', 'saturnwp' ). ' : ' .esc_html__( 'Background color', 'saturnwp' ),
				'default' => '#524F51',
			),
			array(
				'id'      => 'button_shop_alt_bg_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Alternative button', 'saturnwp' ). ' : ' .esc_html__( 'Background color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#000000',
			),
			array(
				'id'      => 'button_shop_alt_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Alternative button', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default' => '#cccccc'
			),
			array(
				'id'      => 'button_shop_alt_hover_color',
				'type'    => 'color',
				'title'   => esc_html__( 'Alternative button', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ). ' - ' .esc_html__( 'on hover', 'saturnwp' ),
				'default' => '#ffffff'
			),
			array(
				'id'      => 'button_shop_font_size',
				'type'    => 'slider',
				'title'   => esc_html__( 'Font size', 'saturnwp' ),
				'min'     => 10,
				'max'     => 60,
				'step'    => 1,
				'unit'    => 'px',
				'default' => 13,
			),
			array(
				'id'      => 'button_shop_weight',
				'type'    => 'select',
				'title'   => esc_html__( 'Font weight', 'saturnwp' ),
				'options' => $font_weights,
				'default' => 'bold',
			),
			array(
				'id'      => 'button_shop_transform',
				'type'    => 'radio',
				'title'   => esc_html__( 'Text transform', 'saturnwp' ),
				'options' => $font_transforms,
				'default' => 'uppercase',
			),
			array(
				'id'      => 'button_shop_padding',
				'type'    => 'spacing',
				'title'   => esc_html__( 'Padding', 'saturnwp' ),
				'mode'    => 'padding',
				'sides'   => array( 'left', 'right' ),
				'units'   => array( 'px', 'em' ),
				'default' => array(
					'padding-left'  => '30px',
					'padding-right' => '30px',
					'units'         => 'px'
				),
			),
		)
	) );

//PAGE SETTINGS
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'    => esc_html__( 'Page settings', 'saturnwp' ),
		'desc'     => '',
		'id'       => 'section_page',
		'icon'     => 'el el-file-edit',
		'priority' => 15,
		'fields'   => array()
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Single page', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_page',
		'icon'       => 'el el-file-edit',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'page_comments',
				'type'    => 'radio',
				'title'   => esc_html__( 'Comments', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'          => 'page_content_under_header',
				'type'        => 'select',
				'title'       => esc_html__( 'Hide content under the header', 'saturnwp' ),
				'description' => esc_html__( 'Works only with the horizontal header.', 'saturnwp' ),
				'options'     => $content_under_header,
				'default'     => 'off',
				'required'    => array( 'header_type', '=', 'horizontal' ),
			),
			array(
				'id'      => 'page_content_layout',
				'type'    => 'select',
				'title'   => esc_html__( 'Content Layout', 'saturnwp' ),
				'options' => $content_layouts,
				'default' => 'center',
			),
			array(
				'id'          => 'page_sidebar',
				'type'        => 'select',
				'title'       => esc_html__( 'Sidebar', 'saturnwp' ),
				'description' => esc_html__( 'You can change it in each page settings.', 'saturnwp' ),
				'options'     => array(
					'left-sidebar'          => esc_html__( 'Sidebar on the left', 'saturnwp' ),
					'left-sidebar_and_nav'  => esc_html__( 'Children Navigation + sidebar on the left', 'saturnwp' ),
					'left-nav'              => esc_html__( 'Only children Navigation on the left', 'saturnwp' ),
					'right-sidebar'         => esc_html__( 'Sidebar on the right', 'saturnwp' ),
					'right-sidebar_and_nav' => esc_html__( 'Children Navigation + sidebar on the right', 'saturnwp' ),
					'right-nav'             => esc_html__( 'Only children Navigation on the right', 'saturnwp' ),
					'off'                   => esc_html__( 'Off', 'saturnwp' ),
				),
				'default'     => 'off',
			),
			array(
				'id'      => 'page_custom_background',
				'type'    => 'radio',
				'title'   => esc_html__( 'Custom background', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'off',
			),
			array(
				'id'       => 'page_body_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'required' => array( 'page_custom_background', '=', 'on' ),
			),
			array(
				'id'       => 'page_body_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'cover',
				'required' => array( 'page_custom_background', '=', 'on' ),
			),
			array(
				'id'       => 'page_body_bg_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Background color', 'saturnwp' ),
				'required' => array( 'page_custom_background', '=', 'on' ),
				'default'  => '',
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Single page', 'saturnwp' ). ' - ' .esc_html__( 'Title bar', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_page_title',
		'icon'       => 'fa fa-text-width',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'page_title',
				'type'    => 'radio',
				'title'   => esc_html__( 'Title', 'saturnwp' ),
				'options' => $on_off,
				'default' => 'on',
			),
			array(
				'id'       => 'page_title_bar_position',
				'type'     => 'radio',
				'title'    => esc_html__( 'Title position', 'saturnwp' ),
				'options'  => array(
					'outside' => esc_html__( 'Before the content', 'saturnwp' ),
					'inside'  => esc_html__( 'Inside the content', 'saturnwp' ),
				),
				'default'  => 'outside',
				'required' => array( 'page_title', '=', 'on' ),
			),
			array(
				'id'       => 'page_title_bar_variant',
				'type'     => 'radio',
				'title'    => esc_html__( 'Variant', 'saturnwp' ),
				'options'  => array(
					'classic'  => esc_html__( 'Classic(to side)', 'saturnwp' ),
					'centered' => esc_html__( 'Centered', 'saturnwp' ),
				),
				'default'  => 'classic',
				'required' => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'page_title_bar_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Background image', 'saturnwp' ),
				'default'  => '',
				'required' => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'page_title_bar_image_fit',
				'type'     => 'select',
				'title'    => esc_html__( 'How to fit the background image', 'saturnwp' ),
				'options'  => $image_fit,
				'default'  => 'repeat',
				'required' => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'page_title_bar_parallax',
				'type'     => 'radio',
				'title'    => esc_html__( 'Parallax', 'saturnwp' ),
				'options'  => $on_off,
				'default'  => 'off',
				'required' => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'          => 'page_title_bar_parallax_type',
				'type'        => 'select',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Type', 'saturnwp' ),
				'description' => esc_html__( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'options'     => $parallax_types,
				'default'     => 'tb',
				'required'    => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
					array( 'page_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'page_title_bar_parallax_speed',
				'type'        => 'slider',
				'title'       => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Speed', 'saturnwp' ),
				'description' => esc_html__( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'min'         => 0,
				'max'         => 1,
				'step'        => 0.01,
				'default'     => '1.00',
				'required'    => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
					array( 'page_title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'id'          => 'page_title_bar_bg_color',
				'type'        => 'color',
				'title'       => esc_html__( 'Overlay color', 'saturnwp' ),
				'description' => esc_html__( 'Will be placed above the image(if used)', 'saturnwp' ),
				'default'     => '',
				'required'    => array( 'page_title', '=', 'on' ),
			),
			array(
				'id'       => 'page_title_bar_title_color',
				'type'     => 'color',
				'title'    => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'default'  => '',
				'required' => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'          => 'page_title_bar_color_1',
				'type'        => 'color',
				'title'       => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'description' => esc_html__( 'Used in breadcrumbs.', 'saturnwp' ),
				'default'     => '',
				'required'    => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'id'       => 'page_title_bar_space_width',
				'type'     => 'slider',
				'title'    => esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'min'      => 0,
				'max'      => 600,
				'step'     => 1,
				'unit'     => 'px',
				'default'  => '150',
				'required' => array(
					array( 'page_title', '=', 'on' ),
					array( 'page_title_bar_position', '!=', 'inside' ),
				)
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( '404 page template', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_404_page',
		'icon'       => 'fa fa-exclamation-triangle',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'page_404_template_type',
				'type'        => 'radio',
				'title'       => esc_html__( 'Type', 'saturnwp' ),
				'options'     => array(
					'default' => esc_html__( 'Default', 'saturnwp' ),
				),
				'default'     => 'default',
			),
			array(
				'id'       => 'page_404_bg_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Default but I want to change the background image', 'saturnwp' ),
				'required' => array( 'page_404_template_type', '=', 'default' ),
			),
		)
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Password protected page template', 'saturnwp' ),
		'id'         => 'subsection_password_page',
		'icon'       => 'fa fa-lock',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'page_password_template_type',
				'type'        => 'radio',
				'title'       => esc_html__( 'Type', 'saturnwp' ),
				'options'     => array(
					'default' => esc_html__( 'Default', 'saturnwp' ),
				),
				'default'     => 'default',
			),
			array(
				'id'       => 'page_password_bg_image',
				'type'     => 'image',
				'title'    => esc_html__( 'Default but I want to change the background image', 'saturnwp' ),
				'required' => array( 'page_password_template_type', '=', 'default' ),
			),
		)
	) );

//MISCELLANEOUS
	$saturnwp_a13->saturnwp_set_sections( array(
		'title'    => esc_html__( 'Miscellaneous', 'saturnwp' ),
		'desc'     => '',
		'id'       => 'section_miscellaneous',
		'icon'     => 'fa fa-question',
		'priority' => 24,
		'fields'   => array(),
	) );

	$saturnwp_a13->saturnwp_set_sections( array(
		'title'      => esc_html__( 'Anchors', 'saturnwp' ),
		'desc'       => '',
		'id'         => 'subsection_anchors',
		'icon'       => 'fa fa-external-link',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'anchors_in_bar',
				'type'        => 'radio',
				'title'       => esc_html__( 'Display anchors in address bar', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'off',
			),
			array(
				'id'          => 'scroll_to_anchor',
				'type'        => 'radio',
				'title'       => esc_html__( 'Scroll to anchor handling', 'saturnwp' ),
				'description' => esc_html__( 'If enabled it will scroll to anchor after it is clicked on the same page. It can, however, conflict with plugins that uses the same mechanism, and the page can scroll in a weird way. In such case disable this feature.', 'saturnwp' ),
				'options'     => $on_off,
				'default'     => 'on',
			),
		)
	) );

	/*
 * <--- END SECTIONS
 */

	do_action( 'saturnwp_additional_theme_options' );
}

saturnwp_setup_theme_options();