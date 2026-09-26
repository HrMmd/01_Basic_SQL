<?php
/**
 * Blockwerk functions and definitions.
 *
 * @package Blockwerk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BLOCKWERK_VERSION', '1.1.0' );
define( 'BLOCKWERK_DIR', get_template_directory() );
define( 'BLOCKWERK_URI', get_template_directory_uri() );

/**
 * Theme setup.
 */
function blockwerk_setup() {
	load_theme_textdomain( 'blockwerk', BLOCKWERK_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 80,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );

	add_editor_style( 'assets/css/editor.css' );

	add_image_size( 'blockwerk-card', 720, 480, true );

	register_nav_menus(
		array(
			'primary'   => __( 'Primary Menu', 'blockwerk' ),
			'secondary' => __( 'Top Bar Menu', 'blockwerk' ),
			'footer'    => __( 'Footer Menu', 'blockwerk' ),
		)
	);
}
add_action( 'after_setup_theme', 'blockwerk_setup' );

/**
 * Content width for embeds.
 */
function blockwerk_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'blockwerk_content_width', (int) get_theme_mod( 'blockwerk_content_width', 760 ) );
}
add_action( 'after_setup_theme', 'blockwerk_content_width', 0 );

/**
 * Widget areas.
 */
function blockwerk_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'blockwerk' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Main sidebar shown on posts and archives.', 'blockwerk' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	if ( class_exists( 'WooCommerce' ) ) {
		register_sidebar(
			array(
				'name'          => __( 'Shop Sidebar', 'blockwerk' ),
				'id'            => 'sidebar-shop',
				'description'   => __( 'Sidebar shown on WooCommerce pages.', 'blockwerk' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}

	for ( $i = 1; $i <= 4; $i++ ) {
		register_sidebar(
			array(
				/* translators: %d: footer column number. */
				'name'          => sprintf( __( 'Footer Column %d', 'blockwerk' ), $i ),
				'id'            => 'footer-' . $i,
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
}
add_action( 'widgets_init', 'blockwerk_widgets_init' );

/**
 * Front-end assets.
 */
function blockwerk_scripts() {
	wp_enqueue_style( 'blockwerk-style', get_stylesheet_uri(), array(), BLOCKWERK_VERSION );
	wp_add_inline_style( 'blockwerk-style', blockwerk_dynamic_css() );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'blockwerk-woocommerce', BLOCKWERK_URI . '/assets/css/woocommerce.css', array( 'blockwerk-style' ), BLOCKWERK_VERSION );
	}

	wp_enqueue_script( 'blockwerk-main', BLOCKWERK_URI . '/assets/js/main.js', array(), BLOCKWERK_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'blockwerk_scripts' );

/**
 * Apply the stored colour scheme before first paint to avoid a flash.
 */
function blockwerk_color_scheme_script() {
	$default = esc_js( get_theme_mod( 'blockwerk_default_scheme', 'auto' ) );
	$stored  = get_theme_mod( 'blockwerk_dark_mode_toggle', true ) ? "localStorage.getItem('blockwerk-scheme')||" : '';
	?>
	<script>
	(function(){try{var s=<?php echo $stored; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>'<?php echo $default; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';if(s==='auto'){s=window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';}document.documentElement.setAttribute('data-scheme',s);}catch(e){}})();
	</script>
	<?php
}
add_action( 'wp_head', 'blockwerk_color_scheme_script', 0 );

/**
 * Body classes driven by Customizer options.
 *
 * @param string[] $classes Existing classes.
 * @return string[]
 */
function blockwerk_body_classes( $classes ) {
	$classes[] = 'header-layout-' . sanitize_html_class( get_theme_mod( 'blockwerk_header_layout', 'inline' ) );
	$classes[] = 'sidebar-' . sanitize_html_class( blockwerk_sidebar_position() );

	if ( get_theme_mod( 'blockwerk_sticky_header', true ) ) {
		$classes[] = 'has-sticky-header';
	}
	if ( get_theme_mod( 'blockwerk_transparent_header', false ) && is_front_page() ) {
		$classes[] = 'has-transparent-header';
	}
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
		$classes[] = 'blog-layout-' . sanitize_html_class( get_theme_mod( 'blockwerk_blog_layout', 'grid' ) );
	}
	if ( 'boxed' === get_theme_mod( 'blockwerk_site_layout', 'wide' ) ) {
		$classes[] = 'site-boxed';
	}
	return $classes;
}
add_filter( 'body_class', 'blockwerk_body_classes' );

/**
 * Resolve the sidebar position for the current view.
 *
 * @return string left|right|none
 */
function blockwerk_sidebar_position() {
	if ( is_page() || is_404() ) {
		$pos = get_theme_mod( 'blockwerk_page_sidebar', 'none' );
	} elseif ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		$pos = is_product() ? 'none' : get_theme_mod( 'blockwerk_shop_sidebar', 'left' );
	} else {
		$pos = get_theme_mod( 'blockwerk_sidebar_position', 'right' );
	}

	$area = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ? 'sidebar-shop' : 'sidebar-1';
	if ( 'none' !== $pos && ! is_active_sidebar( $area ) ) {
		$pos = 'none';
	}
	return apply_filters( 'blockwerk_sidebar_position', $pos );
}

/**
 * Custom excerpt length and "more" string.
 */
add_filter(
	'excerpt_length',
	function () {
		return (int) get_theme_mod( 'blockwerk_excerpt_length', 24 );
	}
);
add_filter(
	'excerpt_more',
	function () {
		return '&hellip;';
	}
);

require BLOCKWERK_DIR . '/inc/template-tags.php';
require BLOCKWERK_DIR . '/inc/customizer.php';
require BLOCKWERK_DIR . '/inc/dynamic-css.php';
require BLOCKWERK_DIR . '/inc/block-patterns.php';

if ( class_exists( 'WooCommerce' ) ) {
	require BLOCKWERK_DIR . '/inc/woocommerce.php';
}
