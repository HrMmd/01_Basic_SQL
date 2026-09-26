<?php
/**
 * WooCommerce integration.
 *
 * @package Blockwerk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce support.
 */
function blockwerk_woocommerce_setup() {
	add_theme_support(
		'woocommerce',
		array(
			'thumbnail_image_width' => 600,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_columns' => 3,
				'min_columns'     => 2,
				'max_columns'     => 5,
			),
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'blockwerk_woocommerce_setup' );

// The theme ships complete shop styles (assets/css/woocommerce.css); WooCommerce's
// float-based defaults would fight the grid layout, so they are not loaded.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Theme provides its own wrappers, sidebar and breadcrumbs.
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Products per row.
 */
add_filter(
	'loop_shop_columns',
	function () {
		return absint( get_theme_mod( 'blockwerk_shop_columns', 3 ) );
	}
);

/**
 * Products per page.
 */
add_filter(
	'loop_shop_per_page',
	function () {
		return absint( get_theme_mod( 'blockwerk_shop_per_page', 12 ) );
	}
);

/**
 * Related products: one row matching the grid.
 *
 * @param array $args Related product args.
 * @return array
 */
function blockwerk_related_products_args( $args ) {
	$cols                   = absint( get_theme_mod( 'blockwerk_shop_columns', 3 ) );
	$args['posts_per_page'] = $cols;
	$args['columns']        = $cols;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'blockwerk_related_products_args' );

/**
 * Sale badge with discount percentage.
 *
 * @param string     $html    Default badge.
 * @param WP_Post    $post    Post.
 * @param WC_Product $product Product.
 * @return string
 */
function blockwerk_sale_flash( $html, $post, $product ) {
	if ( 'percent' !== get_theme_mod( 'blockwerk_sale_badge', 'percent' ) ) {
		return $html;
	}

	$percent = 0;
	if ( $product->is_type( 'variable' ) ) {
		foreach ( $product->get_visible_children() as $child_id ) {
			$child = wc_get_product( $child_id );
			if ( $child && $child->is_on_sale() && (float) $child->get_regular_price() > 0 ) {
				$percent = max( $percent, 100 - ( (float) $child->get_sale_price() / (float) $child->get_regular_price() * 100 ) );
			}
		}
	} elseif ( (float) $product->get_regular_price() > 0 ) {
		$percent = 100 - ( (float) $product->get_sale_price() / (float) $product->get_regular_price() * 100 );
	}

	if ( $percent <= 0 ) {
		return $html;
	}
	return '<span class="onsale">-' . esc_html( round( $percent ) ) . '%</span>';
}
add_filter( 'woocommerce_sale_flash', 'blockwerk_sale_flash', 10, 3 );

/**
 * Second gallery image shown on hover in product cards.
 */
function blockwerk_product_hover_image() {
	if ( ! get_theme_mod( 'blockwerk_shop_hover_image', true ) ) {
		return;
	}
	global $product;
	$ids = $product ? $product->get_gallery_image_ids() : array();
	if ( $ids ) {
		echo wp_get_attachment_image( $ids[0], 'woocommerce_thumbnail', false, array( 'class' => 'bw-hover-image', 'loading' => 'lazy' ) );
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'blockwerk_product_hover_image', 11 );

/**
 * Header cart and account icons.
 */
function blockwerk_woo_header_actions() {
	if ( get_theme_mod( 'blockwerk_header_account', true ) ) {
		printf(
			'<a class="bw-icon-btn" href="%1$s" aria-label="%2$s">%3$s</a>',
			esc_url( wc_get_page_permalink( 'myaccount' ) ),
			esc_attr__( 'My account', 'blockwerk' ),
			blockwerk_icon( 'user' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	if ( get_theme_mod( 'blockwerk_header_cart', true ) ) {
		echo blockwerk_cart_link(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'blockwerk_header_actions', 'blockwerk_woo_header_actions' );

/**
 * Cart icon with live item count.
 *
 * @return string
 */
function blockwerk_cart_link() {
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	return sprintf(
		'<a class="bw-icon-btn bw-cart-link" href="%1$s" aria-label="%2$s">%3$s<span class="bw-cart-count"%4$s>%5$s</span></a>',
		esc_url( wc_get_cart_url() ),
		/* translators: %d: number of items. */
		esc_attr( sprintf( _n( 'Cart, %d item', 'Cart, %d items', $count, 'blockwerk' ), $count ) ),
		blockwerk_icon( 'cart' ),
		$count ? '' : ' hidden',
		esc_html( $count )
	);
}

/**
 * Refresh the cart icon via AJAX fragments after add-to-cart.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function blockwerk_cart_fragment( $fragments ) {
	$fragments['a.bw-cart-link'] = blockwerk_cart_link();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'blockwerk_cart_fragment' );
