<?php
/**
 * Turns Customizer values into CSS custom properties and loads web fonts.
 *
 * @package Blockwerk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Font stacks and their Google Fonts family query (null = no download).
 *
 * @return array<string,array{0:string,1:?string}>
 */
function blockwerk_font_stacks() {
	$system = 'ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
	return array(
		'system'  => array( $system, null ),
		'inter'   => array( '"Inter", ' . $system, 'Inter:wght@400;500;600;700;800' ),
		'poppins' => array( '"Poppins", ' . $system, 'Poppins:wght@400;500;600;700' ),
		'manrope' => array( '"Manrope", ' . $system, 'Manrope:wght@400;500;600;700;800' ),
		'space'   => array( '"Space Grotesk", ' . $system, 'Space+Grotesk:wght@400;500;600;700' ),
		'lora'    => array( '"Lora", Georgia, serif', 'Lora:ital,wght@0,400;0,600;0,700;1,400' ),
		'roboto'  => array( '"Roboto", ' . $system, 'Roboto:wght@400;500;700' ),
		'serif'   => array( 'Georgia, "Times New Roman", serif', null ),
	);
}

/**
 * Enqueue the selected Google Fonts (only the ones actually in use).
 */
function blockwerk_enqueue_fonts() {
	$stacks   = blockwerk_font_stacks();
	$families = array();
	foreach ( array( 'blockwerk_font_body', 'blockwerk_font_heading' ) as $mod ) {
		$key = get_theme_mod( $mod, 'inter' );
		if ( isset( $stacks[ $key ][1] ) ) {
			$families[ $key ] = 'family=' . $stacks[ $key ][1];
		}
	}
	$families = apply_filters( 'blockwerk_google_fonts', $families );
	if ( $families ) {
		wp_enqueue_style( 'blockwerk-fonts', 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap', array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
}
add_action( 'wp_enqueue_scripts', 'blockwerk_enqueue_fonts', 5 );
add_action( 'enqueue_block_editor_assets', 'blockwerk_enqueue_fonts' );

/**
 * Build the inline CSS for the current Customizer state.
 *
 * @return string
 */
function blockwerk_dynamic_css() {
	$stacks = blockwerk_font_stacks();
	$body   = get_theme_mod( 'blockwerk_font_body', 'inter' );
	$head   = get_theme_mod( 'blockwerk_font_heading', 'inter' );

	$vars = array(
		'--bw-primary'         => get_theme_mod( 'blockwerk_color_primary', '#6366f1' ),
		'--bw-primary-hover'   => get_theme_mod( 'blockwerk_color_secondary', '#4f46e5' ),
		'--bw-text'            => get_theme_mod( 'blockwerk_color_text', '#475569' ),
		'--bw-heading'         => get_theme_mod( 'blockwerk_color_heading', '#0f172a' ),
		'--bw-bg'              => get_theme_mod( 'blockwerk_color_bg', '#ffffff' ),
		'--bw-header-bg'       => get_theme_mod( 'blockwerk_color_header_bg', '#ffffff' ),
		'--bw-footer-bg'       => get_theme_mod( 'blockwerk_color_footer_bg', '#0b1120' ),
		'--bw-container'       => absint( get_theme_mod( 'blockwerk_container_width', 1290 ) ) . 'px',
		'--bw-narrow'          => absint( get_theme_mod( 'blockwerk_content_width', 760 ) ) . 'px',
		'--bw-radius'          => absint( get_theme_mod( 'blockwerk_border_radius', 16 ) ) . 'px',
		'--bw-header-height'   => absint( get_theme_mod( 'blockwerk_header_height', 72 ) ) . 'px',
		'--bw-font-size'       => absint( get_theme_mod( 'blockwerk_font_size', 16 ) ) . 'px',
		'--bw-blog-columns'    => absint( get_theme_mod( 'blockwerk_blog_columns', 3 ) ),
		'--bw-footer-columns'  => max( 1, absint( get_theme_mod( 'blockwerk_footer_columns', 4 ) ) ),
		'--bw-font-body'       => isset( $stacks[ $body ] ) ? $stacks[ $body ][0] : $stacks['system'][0],
		'--bw-font-heading'    => isset( $stacks[ $head ] ) ? $stacks[ $head ][0] : $stacks['system'][0],
	);

	$css = ':root{';
	foreach ( $vars as $name => $value ) {
		$css .= $name . ':' . wp_strip_all_tags( (string) $value ) . ';';
	}
	$css .= '}';

	return apply_filters( 'blockwerk_dynamic_css', $css );
}

/**
 * Mirror the Customizer variables inside the (iframed) block editor.
 *
 * @param array $settings Editor settings.
 * @return array
 */
function blockwerk_editor_dynamic_css( $settings ) {
	$settings['styles'][] = array( 'css' => blockwerk_dynamic_css() );
	return $settings;
}
add_filter( 'block_editor_settings_all', 'blockwerk_editor_dynamic_css' );
