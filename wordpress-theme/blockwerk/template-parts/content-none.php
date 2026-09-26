<?php
/**
 * Shown when no posts are found.
 *
 * @package Blockwerk
 */

?>
<section class="no-results">
	<h2><?php esc_html_e( 'Nothing found', 'blockwerk' ); ?></h2>
	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'No results matched your search. Try different keywords.', 'blockwerk' ); ?></p>
	<?php else : ?>
		<p><?php esc_html_e( 'There is nothing here yet.', 'blockwerk' ); ?></p>
	<?php endif; ?>
	<?php get_search_form(); ?>
</section>
