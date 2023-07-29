<?php
function saturnwp_meta_boxes_post() {
	$meta = array(
		/*
		 *
		 * Tab: Posts list
		 *
		 */
		'posts_list' => array(
			array(
				'name' => __('Posts list', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-list'
			),
			array(
				'name'        => __( 'Size of brick', 'saturnwp' ),
				'description' => __( 'What should be the width of this post on the Posts list?', 'saturnwp' ),
				'id'          => 'brick_ratio_x',
				'default'     => 1,
				'unit'        => '',
				'min'         => 1,
				'max'         => 4,
				'type'        => 'slider'
			),
		),


		/*
		 *
		 * Tab: Featured media
		 *
		 */
		'featured_media' => array(
			array(
				'name' => __('Featured media', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-star'
			),
			array(
				'name'        => __( 'Post media', 'saturnwp' ),
				'id'          => 'image_or_video',
				'default'     => 'post_image',
				'options'     => array(
					'post_image'  => __( 'Image', 'saturnwp' ),
				),
				'type'        => 'radio',
			),
			array(
				'name'        => __( 'Featured Image ', 'saturnwp' ). ' : ' . __( 'Parallax', 'saturnwp' ),
				'id'          => 'image_parallax',
				'default'     => 'off',
				'type'        => 'radio',
				'options'     => array(
					'on'  => __( 'Enable', 'saturnwp' ),
					'off' => __( 'Disable', 'saturnwp' ),
				),
				'required'    => array( 'image_or_video', '=', 'post_image' ),
			),
			array(
				'name'     => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Area height', 'saturnwp' ),
				'description' => __( 'This limits the height of the image so that the parallax can work.', 'saturnwp' ),
				'id'       => 'image_parallax_height',
				'default'  => '260',
				'unit'     => 'px',
				'min'      => 100,
				'max'      => 600,
				'type'     => 'slider',
				'required' => array(
					array( 'image_or_video', '=', 'post_image' ),
					array( 'image_parallax', '=', 'on' ),
				)
			),
		),

		/*
		 *
		 * Tab: Header
		 *
		 */
		'header' => array(
			array(
				'name' => __('Header', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-cogs'
			),
			array(
				'name'          => __( 'Hide content under the header', 'saturnwp' ),
				'description'   => __( 'Works only with the horizontal header.', 'saturnwp' ),
				'id'            => 'content_under_header',
				'global_value'  => 'G',
				'default'       => 'G',
				'parent_option' => 'post_content_under_header',
				'type'          => 'select',
				'options'       => array(
					'G'       => __( 'Global settings', 'saturnwp' ),
					'content' => __( 'Yes, hide the content', 'saturnwp' ),
					'title'   => __( 'Yes, hide the content and add top padding to the outside title bar.', 'saturnwp' ),
					'off'     => __( 'Turn it off', 'saturnwp' ),
				),
			),
		),

		/*
		 *
		 * Tab: Title bar
		 *
		 */
		'title_bar' => array(
			array(
				'name' => __('Title bar', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-text-width'
			),
			array(
				'name'        => __( 'Title bar', 'saturnwp' ),
				'description' => __( 'You can use global settings or override them here', 'saturnwp' ),
				'id'          => 'title_bar_settings',
				'default'     => 'global',
				'type'        => 'radio',
				'options'     => array(
					'global' => __( 'Global settings', 'saturnwp' ),
					'custom' => __( 'Use custom settings', 'saturnwp' ),
					'off'    => __( 'Turn it off', 'saturnwp' ),
				),
			),
			array(
				'name'        => __( 'Position', 'saturnwp' ),
				'id'          => 'title_bar_position',
				'default'     => 'outside',
				'type'        => 'radio',
				'options'     => array(
					'outside' => __( 'Before the content', 'saturnwp' ),
					'inside'  => __( 'Inside the content', 'saturnwp' ),
				),
				'description' => __( 'To set the background image for the "Before the content" option, use the <strong>featured image</strong>.', 'saturnwp' ),
				'required'    => array( 'title_bar_settings', '=', 'custom' ),
			),
			array(
				'name'        => __( 'Variant', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_variant',
				'default'     => 'classic',
				'options'     => array(
					'classic'  => __( 'Classic(to side)', 'saturnwp' ),
					'centered' => __( 'Centered', 'saturnwp' ),
				),
				'type'        => 'radio',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Width', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_width',
				'default'     => 'full',
				'options'     => array(
					'full'  => __( 'Full', 'saturnwp' ),
					'boxed' => __( 'Boxed', 'saturnwp' ),
				),
				'type'        => 'radio',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'     => __( 'How to fit the background image', 'saturnwp' ),
				'id'       => 'title_bar_image_fit',
				'default'  => 'repeat',
				'options'  => array(
					'cover'    => __( 'Cover', 'saturnwp' ),
					'contain'  => __( 'Contain', 'saturnwp' ),
					'fitV'     => __( 'Fit Vertically', 'saturnwp' ),
					'fitH'     => __( 'Fit Horizontally', 'saturnwp' ),
					'center'   => __( 'Just center', 'saturnwp' ),
					'repeat'   => __( 'Repeat', 'saturnwp' ),
					'repeat-x' => __( 'Repeat X', 'saturnwp' ),
					'repeat-y' => __( 'Repeat Y', 'saturnwp' ),
				),
				'type'     => 'select',
				'required' => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Parallax', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_parallax',
				'default'     => 'off',
				'options'     => array(
					'on'  => __( 'Enable', 'saturnwp' ),
					'off' => __( 'Disable', 'saturnwp' ),
				),
				'type'        => 'radio',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Parallax', 'saturnwp' ). ' : ' . __( 'Type', 'saturnwp' ),
				'description' => __( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'id'          => 'title_bar_parallax_type',
				'default'     => 'tb',
				'options'     => array(
					"tb"   => __( 'top to bottom', 'saturnwp' ),
					"bt"   => __( 'bottom to top', 'saturnwp' ),
					"lr"   => __( 'left to right', 'saturnwp' ),
					"rl"   => __( 'right to left', 'saturnwp' ),
					"tlbr" => __( 'top-left to bottom-right', 'saturnwp' ),
					"trbl" => __( 'top-right to bottom-left', 'saturnwp' ),
					"bltr" => __( 'bottom-left to top-right', 'saturnwp' ),
					"brtl" => __( 'bottom-right to top-left', 'saturnwp' ),
				),
				'type'        => 'select',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
					array( 'title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'name'        => __( 'Parallax', 'saturnwp' ). ' : ' . __( 'Speed', 'saturnwp' ),
				'description' => __( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'id'          => 'title_bar_parallax_speed',
				'default'     => '1.00',
				'type'        => 'text',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
					array( 'title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'name'        => __( 'Overlay color', 'saturnwp' ),
				'description' => __( 'Will be placed above the image(if used)', 'saturnwp' ),
				'id'          => 'title_bar_bg_color',
				'default'     => '',
				'type'        => 'color',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'     => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'id'       => 'title_bar_title_color',
				'default'  => '',
				'type'     => 'color',
				'required' => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Top/bottom padding', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_space_width',
				'default'     => '40px',
				'unit'        => 'px',
				'min'         => 0,
				'max'         => 600,
				'type'        => 'slider',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
		),

	);

	return apply_filters( 'saturnwp_meta_boxes_post', $meta );
}



function saturnwp_meta_boxes_page() {
	$sidebars = array_merge(
		array(
			'default' => __( 'Default for pages', 'saturnwp' ),
		),
		saturnwp_meta_get_user_sidebars()
	);

	$meta = array(
		/*
		 *
		 * Tab: Layout &amp; Sidebar
		 *
		 */
		'layout' => array(
			array(
				'name' => __('Layout &amp; Sidebar', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-wrench'
			),
			array(
				'name'          => __( 'Content Layout', 'saturnwp' ),
				'id'            => 'content_layout',
				'default'       => 'global',
				'global_value'  => 'global',
				'parent_option' => 'page_content_layout',
				'type'          => 'select',
				'options'       => array(
					'global'        => __( 'Global settings', 'saturnwp' ),
					'center'        => __( 'Center fixed width', 'saturnwp' ),
					'left'          => __( 'Left fixed width', 'saturnwp' ),
					'left_padding'  => __( 'Left fixed width + padding', 'saturnwp' ),
					'right'         => __( 'Right fixed width', 'saturnwp' ),
					'right_padding' => __( 'Right fixed width + padding', 'saturnwp' ),
					'full_fixed'    => __( 'Full width + fixed content', 'saturnwp' ),
					'full_padding'  => __( 'Full width + padding', 'saturnwp' ),
					'full'          => __( 'Full width', 'saturnwp' ),
				),
			),
			array(
				'name'        => esc_html__( 'Content', 'saturnwp' ). ' : ' .esc_html__( 'Top/bottom padding', 'saturnwp' ),
				'id'          => 'content_padding',
				'default'     => 'both',
				'type'        => 'select',
				'options'     => array(
					'both'   => __( 'Both on', 'saturnwp' ),
					'top'    => __( 'Only top', 'saturnwp' ),
					'bottom' => __( 'Only bottom', 'saturnwp' ),
					'off'    => __( 'Both off', 'saturnwp' ),
				),
			),
			array(
				'name'        => __( 'Content', 'saturnwp' ). ' : ' .esc_html__( 'Left/right padding', 'saturnwp' ),
				'id'          => 'content_side_padding',
				'default'     => 'both',
				'type'        => 'radio',
				'options'     => array(
					'both'   => __( 'Both on', 'saturnwp' ),
					'off'    => __( 'Both off', 'saturnwp' ),
				),
			),
			array(
				'name'          => __( 'Sidebar', 'saturnwp' ),
				'id'            => 'widget_area',
				'global_value'  => 'G',
				'default'       => 'G',
				'parent_option' => 'page_sidebar',
				'options'       => array(
					'G'                     => __( 'Global settings', 'saturnwp' ),
					'left-sidebar'          => __( 'Sidebar on the left', 'saturnwp' ),
					'left-sidebar_and_nav'  => __( 'Children Navigation', 'saturnwp' ) . ' + ' . __( 'Sidebar on the left', 'saturnwp' ),
					/* translators: %s: Children Navigation */
					'left-nav'             => sprintf( __( 'Only %s on the left', 'saturnwp' ), __( 'Children Navigation', 'saturnwp' ) ),
					'right-sidebar'         => __( 'Sidebar on the right', 'saturnwp' ),
					'right-sidebar_and_nav' => __( 'Children Navigation', 'saturnwp' ) . ' + ' . __( 'Sidebar on the right', 'saturnwp' ),
					/* translators: %s: Children Navigation */
					'right-nav'             => sprintf( __( 'Only %s on the right', 'saturnwp' ), __( 'Children Navigation', 'saturnwp' ) ),
					'off'                   => __( 'Off', 'saturnwp' ),
				),
				'type'          => 'select',
			),
			array(
				'name'     => __( 'Sidebar to show', 'saturnwp' ),
				'id'       => 'sidebar_to_show',
				'default'  => 'default',
				'options'  => $sidebars,
				'type'     => 'select',
				'required' => array( 'widget_area', '!=', 'off' ),
			),
		),

		/*
		 *
		 * Tab: Header
		 *
		 */
		'header' => array(
			array(
				'name' => __('Header', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-cogs'
			),
			array(
				'name'          => __( 'Hide content under the header', 'saturnwp' ),
				'description'   => __( 'Works only with the horizontal header.', 'saturnwp' ),
				'id'            => 'content_under_header',
				'global_value'  => 'G',
				'default'       => 'G',
				'parent_option' => 'page_content_under_header',
				'type'          => 'select',
				'options'       => array(
					'G'       => __( 'Global settings', 'saturnwp' ),
					'content' => __( 'Yes, hide the content', 'saturnwp' ),
					'title'   => __( 'Yes, hide the content and add top padding to the outside title bar.', 'saturnwp' ),
					'off'     => __( 'Turn it off', 'saturnwp' ),
				),
			),
			array(
				'name'          => __( 'Show header from the Nth row', 'saturnwp' ),
				'description'   => __( 'Works only with the horizontal header.', 'saturnwp' ). '<br />' . __( 'If you use Elementor or WPBakery Page Builder, then you can decide to show header after the content is scrolled to Nth row. Thanks to that you can have a clean welcome screen.', 'saturnwp' ),
				'id'            => 'horizontal_header_show_header_at',
				'default'       => '0',
				'type'          => 'select',
				'options'       => array(
					'0' => __( 'Show always', 'saturnwp' ),
					'1' => __( 'from 1st row', 'saturnwp' ),
					'2' => __( 'from 2nd row', 'saturnwp' ),
					'3' => __( 'from 3rd row', 'saturnwp' ),
					'4' => __( 'from 4th row', 'saturnwp' ),
					'5' => __( 'from 5th row', 'saturnwp' ),
				),
			),
		),

		/*
		 *
		 * Tab: Title bar
		 *
		 */
		'title_bar' => array(
			array(
				'name' => __('Title bar', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-text-width'
			),
			array(
				'name'        => __( 'Title bar', 'saturnwp' ),
				'description' => __( 'You can use global settings or override them here', 'saturnwp' ),
				'id'          => 'title_bar_settings',
				'default'     => 'global',
				'type'        => 'radio',
				'options'     => array(
					'global' => __( 'Global settings', 'saturnwp' ),
					'custom' => __( 'Use custom settings', 'saturnwp' ),
					'off'    => __( 'Turn it off', 'saturnwp' ),
				),
			),
			array(
				'name'        => __( 'Position', 'saturnwp' ),
				'id'          => 'title_bar_position',
				'default'     => 'outside',
				'type'        => 'radio',
				'options'     => array(
					'outside' => __( 'Before the content', 'saturnwp' ),
					'inside'  => __( 'Inside the content', 'saturnwp' ),
				),
				'description' => __( 'To set the background image for the "Before the content" option, use the <strong>featured image</strong>.', 'saturnwp' ),
				'required'    => array( 'title_bar_settings', '=', 'custom' ),
			),
			array(
				'name'        => __( 'Variant', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_variant',
				'default'     => 'classic',
				'options'     => array(
					'classic'  => __( 'Classic(to side)', 'saturnwp' ),
					'centered' => __( 'Centered', 'saturnwp' ),
				),
				'type'        => 'radio',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Width', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_width',
				'default'     => 'full',
				'options'     => array(
					'full'  => __( 'Full', 'saturnwp' ),
					'boxed' => __( 'Boxed', 'saturnwp' ),
				),
				'type'        => 'radio',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'     => __( 'How to fit the background image', 'saturnwp' ),
				'id'       => 'title_bar_image_fit',
				'default'  => 'repeat',
				'options'  => array(
					'cover'    => __( 'Cover', 'saturnwp' ),
					'contain'  => __( 'Contain', 'saturnwp' ),
					'fitV'     => __( 'Fit Vertically', 'saturnwp' ),
					'fitH'     => __( 'Fit Horizontally', 'saturnwp' ),
					'center'   => __( 'Just center', 'saturnwp' ),
					'repeat'   => __( 'Repeat', 'saturnwp' ),
					'repeat-x' => __( 'Repeat X', 'saturnwp' ),
					'repeat-y' => __( 'Repeat Y', 'saturnwp' ),
				),
				'type'     => 'select',
				'required' => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Parallax', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_parallax',
				'default'     => 'off',
				'options'     => array(
					'on'  => __( 'Enable', 'saturnwp' ),
					'off' => __( 'Disable', 'saturnwp' ),
				),
				'type'        => 'radio',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Parallax', 'saturnwp' ). ' : ' . __( 'Type', 'saturnwp' ),
				'description' => __( 'It defines how the image will scroll in the background while the page is scrolled down.', 'saturnwp' ),
				'id'          => 'title_bar_parallax_type',
				'default'     => 'tb',
				'options'     => array(
					"tb"   => __( 'top to bottom', 'saturnwp' ),
					"bt"   => __( 'bottom to top', 'saturnwp' ),
					"lr"   => __( 'left to right', 'saturnwp' ),
					"rl"   => __( 'right to left', 'saturnwp' ),
					"tlbr" => __( 'top-left to bottom-right', 'saturnwp' ),
					"trbl" => __( 'top-right to bottom-left', 'saturnwp' ),
					"bltr" => __( 'bottom-left to top-right', 'saturnwp' ),
					"brtl" => __( 'bottom-right to top-left', 'saturnwp' ),
				),
				'type'        => 'select',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
					array( 'title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'name'        => __( 'Parallax', 'saturnwp' ). ' : ' . __( 'Speed', 'saturnwp' ),
				'description' => __( 'It will be only used for the background that is repeated. If the background is set to not repeat this value will be ignored.', 'saturnwp' ),
				'id'          => 'title_bar_parallax_speed',
				'default'     => '1.00',
				'type'        => 'text',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
					array( 'title_bar_parallax', '=', 'on' ),
				)
			),
			array(
				'name'        => __( 'Overlay color', 'saturnwp' ),
				'description' => __( 'Will be placed above the image(if used)', 'saturnwp' ),
				'id'          => 'title_bar_bg_color',
				'default'     => '',
				'type'        => 'color',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'     => esc_html__( 'Titles', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'id'       => 'title_bar_title_color',
				'default'  => '',
				'type'     => 'color',
				'required' => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => esc_html__( 'Other elements', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
				'id'          => 'title_bar_color_1',
				'default'     => '',
				'type'        => 'color',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
			array(
				'name'        => __( 'Top/bottom padding', 'saturnwp' ),
				'description' => '',
				'id'          => 'title_bar_space_width',
				'default'     => '40px',
				'unit'        => 'px',
				'min'         => 0,
				'max'         => 600,
				'type'        => 'slider',
				'required'    => array(
					array( 'title_bar_settings', '=', 'custom' ),
					array( 'title_bar_position', '!=', 'inside' ),
				)
			),
		),

		/*
		 *
		 * Tab: Featured media
		 *
		 */
		'featured_media' => array(
			array(
				'name' => __('Featured media', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-star'
			),
			array(
				'name'        => __( 'Post media', 'saturnwp' ),
				'id'          => 'image_or_video',
				'default'     => 'post_image',
				'options'     => array(
					'post_image'  => __( 'Image', 'saturnwp' ),
				),
				'type'        => 'radio',
			),
			array(
				'name'        => __( 'Featured Image ', 'saturnwp' ). ' : ' . __( 'Parallax', 'saturnwp' ),
				'id'          => 'image_parallax',
				'default'     => 'off',
				'type'        => 'radio',
				'options'     => array(
					'on'  => __( 'Enable', 'saturnwp' ),
					'off' => __( 'Disable', 'saturnwp' ),
				),
				'required'    => array( 'image_or_video', '=', 'post_image' ),
			),
			array(
				'name'     => esc_html__( 'Parallax', 'saturnwp' ). ' : ' . esc_html__( 'Area height', 'saturnwp' ),
				'description' => __( 'This limits the height of the image so that the parallax can work.', 'saturnwp' ),
				'id'       => 'image_parallax_height',
				'default'  => '260',
				'unit'     => 'px',
				'min'      => 100,
				'max'      => 600,
				'type'     => 'slider',
				'required' => array(
					array( 'image_or_video', '=', 'post_image' ),
					array( 'image_parallax', '=', 'on' ),
				)
			),
		),

		/*
		 *
		 * Tab: Sticky one page mode
		 *
		 */
		'sticky_one_page' => array(
			array(
				'name' => __('Sticky One Page mode', 'saturnwp'),
				'type' => 'fieldset',
				'tab'   => true,
				'icon'  => 'fa fa-anchor'
			),
			array(
				'name'        => __( 'Sticky One Page mode', 'saturnwp' ),
				'description' => __( 'This works only when page is designed with WPBakery Page Builder. With this option enabled, the page will turn into a vertical slider to the full height of the browser window, and each row created in the WPBakery Page Builder is a single slide.', 'saturnwp' ),
				'id'          => 'content_sticky_one_page',
				'default'     => 'off',
				'type'        => 'radio',
				'options'     => array(
					'on'  => __( 'Enable', 'saturnwp' ),
					'off' => __( 'Disable', 'saturnwp' ),
				),
			),
			array(
				'name'     => __( 'Color of navigation bullets', 'saturnwp' ),
				'id'       => 'content_sticky_one_page_bullet_color',
				'default'  => 'rgba(0,0,0,1)',
				'type'     => 'color',
				'required' => array(
					array( 'content_sticky_one_page', '=', 'on' )
				)
			),
			array(
				'name'        => __( 'Default bullets icon', 'saturnwp' ),
				'id'          => 'content_sticky_one_page_bullet_icon',
				'default'     => '',
				'type'        => 'text',
				'input_class' => 'a13-fa-icon a13-full-class',
				'required'    => array(
					array( 'content_sticky_one_page', '=', 'on' )
				)
			),
		),
	);

	return apply_filters( 'saturnwp_meta_boxes_page', $meta );
}

function saturnwp_meta_boxes_images_manager() {
	return apply_filters( 'saturnwp_meta_boxes_images_manager', array('images_manager' => array()) );
}



function saturnwp_get_socials_array() {
	global $saturnwp_a13;

	$tmp_arr = array();
	$socials = $saturnwp_a13->saturnwp_get_social_icons_list();
	foreach ( $socials as $id => $social ) {
		array_push( $tmp_arr, array( 'name' => $social, 'id' => $id, 'type' => 'text' ) );
	}
	return $tmp_arr;
}



function saturnwp_meta_boxes_people() {
	$meta =
		array(
			/*
			 *
			 * Tab: General
			 *
			 */
			'general' => array(
				array(
					'name' => __('General', 'saturnwp'),
					'type' => 'fieldset',
					'tab'   => true,
					'icon'  => 'fa fa-wrench'
				),
				array(
						'name'        => __( 'Subtitle', 'saturnwp' ),
						'description' => __( 'You can use HTML here.', 'saturnwp' ),
						'id'          => 'subtitle',
						'default'     => '',
						'type'        => 'text'
				),
				array(
						'name'    => __( 'Testimonial', 'saturnwp' ),
						'desc'    => '',
						'id'      => 'testimonial',
						'default' => '',
						'type'    => 'textarea'
				),
				array(
						'name'        => __( 'Overlay color', 'saturnwp' ),
						'id'          => 'overlay_bg_color',
						'default'     => 'rgba(0,0,0,0.5)',
						'type'        => 'color'
				),
				array(
						'name'        => __( 'Overlay', 'saturnwp' ). ' : ' .esc_html__( 'Text color', 'saturnwp' ),
						'id'          => 'overlay_font_color',
						'default'     => 'rgba(255,255,255,1)',
						'type'        => 'color'
				),
			),

			/*
			 *
			 * Tab: Socials
			 *
			 */
			'socials' => array_merge(
				array(
					array(
						'name' => __('Social icons', 'saturnwp'),
						'type' => 'fieldset',
						'tab'   => true,
						'icon'  => 'fa fa-facebook-official'
					),
				),
				saturnwp_get_socials_array()
			),
		);

	return $meta;
}