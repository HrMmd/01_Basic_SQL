<?php
/**
 * Block patterns: ready-made modern sections for the block editor.
 *
 * @package Blockwerk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the pattern category and patterns.
 */
function blockwerk_register_patterns() {
	register_block_pattern_category( 'blockwerk', array( 'label' => __( 'Blockwerk', 'blockwerk' ) ) );

	register_block_pattern(
		'blockwerk/hero',
		array(
			'title'      => __( 'Hero with gradient', 'blockwerk' ),
			'categories' => array( 'blockwerk', 'banner' ),
			'content'    => '<!-- wp:group {"align":"full","className":"bw-hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull bw-hero"><!-- wp:paragraph {"align":"center","className":"bw-eyebrow"} -->
<p class="has-text-align-center bw-eyebrow">' . esc_html__( 'New — Version 2.0 is here', 'blockwerk' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"huge"} -->
<h1 class="wp-block-heading has-text-align-center has-huge-font-size">' . esc_html__( 'Build beautiful websites, faster.', 'blockwerk' ) . '</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","fontSize":"large"} -->
<p class="has-text-align-center has-large-font-size">' . esc_html__( 'A lightweight, modern theme for Gutenberg and WooCommerce. Fast by default, flexible by design.', 'blockwerk' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">' . esc_html__( 'Get started', 'blockwerk' ) . '</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">' . esc_html__( 'Learn more', 'blockwerk' ) . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
		)
	);

	$features = array(
		array( '⚡', __( 'Blazing fast', 'blockwerk' ), __( 'Lightweight CSS and vanilla JS. No jQuery, no bloat.', 'blockwerk' ) ),
		array( '🎨', __( 'Fully customizable', 'blockwerk' ), __( 'Colors, fonts, layouts and header styles — live in the Customizer.', 'blockwerk' ) ),
		array( '🛒', __( 'Shop ready', 'blockwerk' ), __( 'Deep WooCommerce integration with a polished product grid.', 'blockwerk' ) ),
	);
	$columns  = '';
	foreach ( $features as $f ) {
		$columns .= '<!-- wp:column {"className":"bw-feature-card"} -->
<div class="wp-block-column bw-feature-card"><!-- wp:paragraph {"fontSize":"x-large"} -->
<p class="has-x-large-font-size">' . $f[0] . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">' . esc_html( $f[1] ) . '</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html( $f[2] ) . '</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

';
	}

	register_block_pattern(
		'blockwerk/features',
		array(
			'title'      => __( 'Feature cards', 'blockwerk' ),
			'categories' => array( 'blockwerk', 'columns' ),
			'content'    => '<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","wideSize":"1200px"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">' . esc_html__( 'Everything you need', 'blockwerk' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide">' . $columns . '</div>
<!-- /wp:columns --></div>
<!-- /wp:group -->',
		)
	);

	register_block_pattern(
		'blockwerk/cta',
		array(
			'title'      => __( 'Call to action banner', 'blockwerk' ),
			'categories' => array( 'blockwerk', 'call-to-action' ),
			'content'    => '<!-- wp:group {"align":"wide","className":"bw-cta","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide bw-cta"><!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">' . esc_html__( 'Ready to launch your site?', 'blockwerk' ) . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center">' . esc_html__( 'Join thousands of creators building with Blockwerk.', 'blockwerk' ) . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button">' . esc_html__( 'Start for free', 'blockwerk' ) . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
		)
	);
}
add_action( 'init', 'blockwerk_register_patterns' );

/**
 * Extra block styles.
 */
function blockwerk_register_block_styles() {
	register_block_style( 'core/group', array( 'name' => 'bw-card', 'label' => __( 'Card', 'blockwerk' ) ) );
	register_block_style( 'core/group', array( 'name' => 'bw-glass', 'label' => __( 'Glass', 'blockwerk' ) ) );
	register_block_style( 'core/image', array( 'name' => 'bw-rounded-shadow', 'label' => __( 'Rounded + shadow', 'blockwerk' ) ) );
	register_block_style( 'core/heading', array( 'name' => 'bw-gradient', 'label' => __( 'Gradient text', 'blockwerk' ) ) );
}
add_action( 'init', 'blockwerk_register_block_styles' );
