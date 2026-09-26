<?php
/**
 * Sidebar.
 *
 * @package Blockwerk
 */

if ( 'none' === blockwerk_sidebar_position() ) {
	return;
}

$blockwerk_area = ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) ? 'sidebar-shop' : 'sidebar-1';
?>
<aside id="secondary" class="widget-area" aria-label="<?php esc_attr_e( 'Sidebar', 'blockwerk' ); ?>">
	<?php dynamic_sidebar( $blockwerk_area ); ?>
</aside>
