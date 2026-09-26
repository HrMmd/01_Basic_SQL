<?php
/**
 * Customizer options (Appearance → Customize → Blockwerk).
 *
 * @package Blockwerk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a value against a list of allowed choices.
 *
 * @param string               $value   Submitted value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function blockwerk_sanitize_choice( $value, $setting ) {
	$control = $setting->manager->get_control( $setting->id );
	$choices = $control ? $control->choices : array();
	return array_key_exists( $value, $choices ) ? $value : $setting->default;
}

/**
 * Sanitize a checkbox.
 *
 * @param mixed $value Submitted value.
 * @return bool
 */
function blockwerk_sanitize_checkbox( $value ) {
	return (bool) $value;
}

/**
 * Register Customizer panels, sections, settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function blockwerk_customize_register( $wp_customize ) {
	foreach ( array( 'blogname', 'blogdescription' ) as $core_setting ) {
		if ( $wp_customize->get_setting( $core_setting ) ) {
			$wp_customize->get_setting( $core_setting )->transport = 'postMessage';
		}
	}

	$wp_customize->add_panel(
		'blockwerk',
		array(
			'title'    => __( 'Blockwerk Options', 'blockwerk' ),
			'priority' => 30,
		)
	);

	$sections = array(
		'blockwerk_general' => __( 'General & Layout', 'blockwerk' ),
		'blockwerk_colors'  => __( 'Colors & Dark Mode', 'blockwerk' ),
		'blockwerk_type'    => __( 'Typography', 'blockwerk' ),
		'blockwerk_header'  => __( 'Header', 'blockwerk' ),
		'blockwerk_blog'    => __( 'Blog & Posts', 'blockwerk' ),
		'blockwerk_footer'  => __( 'Footer', 'blockwerk' ),
		'blockwerk_social'  => __( 'Social Networks', 'blockwerk' ),
	);
	if ( class_exists( 'WooCommerce' ) ) {
		$sections['blockwerk_shop'] = __( 'Shop (WooCommerce)', 'blockwerk' );
	}
	foreach ( $sections as $id => $title ) {
		$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'blockwerk' ) );
	}

	$sidebar_choices = array(
		'right' => __( 'Right', 'blockwerk' ),
		'left'  => __( 'Left', 'blockwerk' ),
		'none'  => __( 'No sidebar', 'blockwerk' ),
	);

	$fields = array(
		// General.
		array( 'blockwerk_site_layout', 'blockwerk_general', 'select', 'wide', __( 'Site layout', 'blockwerk' ), array( 'wide' => __( 'Full width', 'blockwerk' ), 'boxed' => __( 'Boxed', 'blockwerk' ) ) ),
		array( 'blockwerk_container_width', 'blockwerk_general', 'number', 1290, __( 'Container max width (px)', 'blockwerk' ) ),
		array( 'blockwerk_content_width', 'blockwerk_general', 'number', 760, __( 'Narrow content width (px)', 'blockwerk' ) ),
		array( 'blockwerk_border_radius', 'blockwerk_general', 'number', 16, __( 'Border radius (px)', 'blockwerk' ) ),
		array( 'blockwerk_sidebar_position', 'blockwerk_general', 'select', 'right', __( 'Sidebar on posts & archives', 'blockwerk' ), $sidebar_choices ),
		array( 'blockwerk_page_sidebar', 'blockwerk_general', 'select', 'none', __( 'Sidebar on pages', 'blockwerk' ), $sidebar_choices ),
		array( 'blockwerk_breadcrumbs', 'blockwerk_general', 'checkbox', true, __( 'Show breadcrumbs', 'blockwerk' ) ),
		array( 'blockwerk_back_to_top', 'blockwerk_general', 'checkbox', true, __( 'Show "back to top" button', 'blockwerk' ) ),
		array( 'blockwerk_reading_progress', 'blockwerk_general', 'checkbox', true, __( 'Reading progress bar on posts', 'blockwerk' ) ),

		// Colors.
		array( 'blockwerk_color_primary', 'blockwerk_colors', 'color', '#6366f1', __( 'Primary / accent', 'blockwerk' ) ),
		array( 'blockwerk_color_secondary', 'blockwerk_colors', 'color', '#4f46e5', __( 'Accent hover', 'blockwerk' ) ),
		array( 'blockwerk_color_text', 'blockwerk_colors', 'color', '#475569', __( 'Text', 'blockwerk' ) ),
		array( 'blockwerk_color_heading', 'blockwerk_colors', 'color', '#0f172a', __( 'Headings', 'blockwerk' ) ),
		array( 'blockwerk_color_bg', 'blockwerk_colors', 'color', '#ffffff', __( 'Site background', 'blockwerk' ) ),
		array( 'blockwerk_color_header_bg', 'blockwerk_colors', 'color', '#ffffff', __( 'Header background', 'blockwerk' ) ),
		array( 'blockwerk_color_footer_bg', 'blockwerk_colors', 'color', '#0b1120', __( 'Footer background', 'blockwerk' ) ),
		array( 'blockwerk_dark_mode_toggle', 'blockwerk_colors', 'checkbox', true, __( 'Show dark mode switch', 'blockwerk' ) ),
		array( 'blockwerk_default_scheme', 'blockwerk_colors', 'select', 'auto', __( 'Default color scheme', 'blockwerk' ), array( 'light' => __( 'Light', 'blockwerk' ), 'dark' => __( 'Dark', 'blockwerk' ), 'auto' => __( 'Follow system', 'blockwerk' ) ) ),

		// Typography.
		array( 'blockwerk_font_body', 'blockwerk_type', 'select', 'inter', __( 'Body font', 'blockwerk' ), blockwerk_font_choices() ),
		array( 'blockwerk_font_heading', 'blockwerk_type', 'select', 'inter', __( 'Heading font', 'blockwerk' ), blockwerk_font_choices() ),
		array( 'blockwerk_font_size', 'blockwerk_type', 'number', 16, __( 'Base font size (px)', 'blockwerk' ) ),

		// Header.
		array( 'blockwerk_header_layout', 'blockwerk_header', 'select', 'inline', __( 'Header layout', 'blockwerk' ), array( 'inline' => __( 'Logo left, menu right', 'blockwerk' ), 'centered' => __( 'Logo centered, menu below', 'blockwerk' ), 'split' => __( 'Menu left, logo center, actions right', 'blockwerk' ) ) ),
		array( 'blockwerk_header_height', 'blockwerk_header', 'number', 72, __( 'Header height (px)', 'blockwerk' ) ),
		array( 'blockwerk_topbar', 'blockwerk_header', 'checkbox', false, __( 'Show top bar', 'blockwerk' ) ),
		array( 'blockwerk_topbar_text', 'blockwerk_header', 'text', '', __( 'Top bar text', 'blockwerk' ) ),
		array( 'blockwerk_sticky_header', 'blockwerk_header', 'checkbox', true, __( 'Sticky header', 'blockwerk' ) ),
		array( 'blockwerk_transparent_header', 'blockwerk_header', 'checkbox', false, __( 'Transparent header on front page', 'blockwerk' ) ),
		array( 'blockwerk_header_search', 'blockwerk_header', 'checkbox', true, __( 'Show search icon', 'blockwerk' ) ),
		array( 'blockwerk_header_button', 'blockwerk_header', 'text', '', __( 'Header button label', 'blockwerk' ) ),
		array( 'blockwerk_header_button_url', 'blockwerk_header', 'url', '', __( 'Header button link', 'blockwerk' ) ),

		// Blog.
		array( 'blockwerk_blog_layout', 'blockwerk_blog', 'select', 'grid', __( 'Archive layout', 'blockwerk' ), array( 'grid' => __( 'Grid', 'blockwerk' ), 'list' => __( 'List', 'blockwerk' ), 'classic' => __( 'Classic (full width)', 'blockwerk' ) ) ),
		array( 'blockwerk_blog_columns', 'blockwerk_blog', 'select', '3', __( 'Grid columns', 'blockwerk' ), array( '2' => '2', '3' => '3', '4' => '4' ) ),
		array( 'blockwerk_excerpt_length', 'blockwerk_blog', 'number', 24, __( 'Excerpt length (words)', 'blockwerk' ) ),
		array( 'blockwerk_show_reading_time', 'blockwerk_blog', 'checkbox', true, __( 'Show reading time', 'blockwerk' ) ),
		array( 'blockwerk_show_author_box', 'blockwerk_blog', 'checkbox', true, __( 'Show author box on posts', 'blockwerk' ) ),
		array( 'blockwerk_show_share', 'blockwerk_blog', 'checkbox', true, __( 'Show share buttons on posts', 'blockwerk' ) ),
		array( 'blockwerk_show_related', 'blockwerk_blog', 'checkbox', true, __( 'Show related posts', 'blockwerk' ) ),
		array( 'blockwerk_show_post_nav', 'blockwerk_blog', 'checkbox', true, __( 'Show previous/next post navigation', 'blockwerk' ) ),

		// Footer.
		array( 'blockwerk_footer_columns', 'blockwerk_footer', 'select', '4', __( 'Widget columns', 'blockwerk' ), array( '0' => __( 'None', 'blockwerk' ), '1' => '1', '2' => '2', '3' => '3', '4' => '4' ) ),
		/* translators: 1: year placeholder, 2: site name placeholder. */
		array( 'blockwerk_copyright', 'blockwerk_footer', 'text', __( '© {year} {site} — Powered by WordPress', 'blockwerk' ), __( 'Copyright text ({year}, {site})', 'blockwerk' ) ),
	);

	foreach ( blockwerk_social_networks() as $key => $label ) {
		$fields[] = array( 'blockwerk_social_' . $key, 'blockwerk_social', 'url', '', $label );
	}

	if ( class_exists( 'WooCommerce' ) ) {
		$fields[] = array( 'blockwerk_shop_columns', 'blockwerk_shop', 'select', '3', __( 'Products per row', 'blockwerk' ), array( '2' => '2', '3' => '3', '4' => '4', '5' => '5' ) );
		$fields[] = array( 'blockwerk_shop_per_page', 'blockwerk_shop', 'number', 12, __( 'Products per page', 'blockwerk' ) );
		$fields[] = array( 'blockwerk_shop_sidebar', 'blockwerk_shop', 'select', 'left', __( 'Shop sidebar', 'blockwerk' ), $sidebar_choices );
		$fields[] = array( 'blockwerk_header_cart', 'blockwerk_shop', 'checkbox', true, __( 'Show cart icon in header', 'blockwerk' ) );
		$fields[] = array( 'blockwerk_header_account', 'blockwerk_shop', 'checkbox', true, __( 'Show account icon in header', 'blockwerk' ) );
		$fields[] = array( 'blockwerk_sale_badge', 'blockwerk_shop', 'select', 'percent', __( 'Sale badge', 'blockwerk' ), array( 'text' => __( '"Sale!" text', 'blockwerk' ), 'percent' => __( 'Discount percentage', 'blockwerk' ) ) );
		$fields[] = array( 'blockwerk_shop_hover_image', 'blockwerk_shop', 'checkbox', true, __( 'Swap to second gallery image on hover', 'blockwerk' ) );
	}

	foreach ( $fields as $field ) {
		list( $id, $section, $type, $default, $label ) = $field;
		$choices = isset( $field[5] ) ? $field[5] : array();

		switch ( $type ) {
			case 'checkbox':
				$sanitize = 'blockwerk_sanitize_checkbox';
				break;
			case 'color':
				$sanitize = 'sanitize_hex_color';
				break;
			case 'number':
				$sanitize = 'absint';
				break;
			case 'url':
				$sanitize = 'esc_url_raw';
				break;
			case 'select':
				$sanitize = 'blockwerk_sanitize_choice';
				break;
			default:
				$sanitize = 'sanitize_text_field';
		}

		$wp_customize->add_setting(
			$id,
			array(
				'default'           => $default,
				'sanitize_callback' => $sanitize,
			)
		);

		if ( 'color' === $type ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $label, 'section' => $section ) ) );
		} else {
			$wp_customize->add_control(
				$id,
				array(
					'label'   => $label,
					'section' => $section,
					'type'    => $type,
					'choices' => $choices,
				)
			);
		}
	}

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array( 'selector' => '.site-title a', 'render_callback' => 'blockwerk_partial_blogname' ) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array( 'selector' => '.site-description', 'render_callback' => 'blockwerk_partial_blogdescription' ) );
	}
}
add_action( 'customize_register', 'blockwerk_customize_register' );

/**
 * Selective refresh callbacks.
 */
function blockwerk_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Selective refresh callback for the tagline.
 */
function blockwerk_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Available font stacks.
 *
 * @return array<string,string>
 */
function blockwerk_font_choices() {
	return array(
		'system'  => __( 'System UI', 'blockwerk' ),
		'inter'   => 'Inter',
		'poppins' => 'Poppins',
		'manrope' => 'Manrope',
		'space'   => 'Space Grotesk',
		'lora'    => 'Lora',
		'roboto'  => 'Roboto',
		'serif'   => __( 'Classic serif', 'blockwerk' ),
	);
}

/**
 * Supported social networks.
 *
 * @return array<string,string>
 */
function blockwerk_social_networks() {
	return array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'x'         => 'X / Twitter',
		'linkedin'  => 'LinkedIn',
		'youtube'   => 'YouTube',
		'github'    => 'GitHub',
	);
}
