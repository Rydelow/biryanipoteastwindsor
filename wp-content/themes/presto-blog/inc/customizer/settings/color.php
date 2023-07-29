<?php
/**
 * Color Setting
 *
 * @package Presto_Blog
 */

if( ! function_exists( 'presto_blog_customize_register_color' ) ) :
function presto_blog_customize_register_color( $wp_customize ) {
    
    /** Styling Settings */
    $wp_customize->add_panel( 
        'styling_settings',
         array(
            'priority'    => 70,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Styling Settings', 'presto-blog' ),
        ) 
    );
    
    $wp_customize->get_section( 'colors' )->panel              = 'styling_settings';
    $wp_customize->get_section( 'colors' )->title              = __( 'Colors Settings', 'presto-blog' );
    $wp_customize->get_section( 'colors' )->priority           = 20;
    $wp_customize->get_section( 'background_image' )->panel    = 'styling_settings';
    $wp_customize->get_section( 'background_image' )->priority = 30;

    $wp_customize->remove_control( 'header_textcolor' );
}
endif;
add_action( 'customize_register', 'presto_blog_customize_register_color' );