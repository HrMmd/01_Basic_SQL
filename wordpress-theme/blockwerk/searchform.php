<?php
/**
 * Search form.
 *
 * @package Blockwerk
 */

$blockwerk_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $blockwerk_id ); ?>"><?php esc_html_e( 'Search for:', 'blockwerk' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $blockwerk_id ); ?>" class="search-field" placeholder="<?php esc_attr_e( 'Search…', 'blockwerk' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s">
	<button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'blockwerk' ); ?>"><?php blockwerk_the_icon( 'search', 18 ); ?></button>
</form>
