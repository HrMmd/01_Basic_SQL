<?php
/**
 * 404 page.
 *
 * @package Blockwerk
 */

get_header();
?>

<div class="bw-container">
	<main id="primary" class="site-main bw-404">
		<p class="bw-404-code">404</p>
		<h1 class="page-title"><?php esc_html_e( 'This page could not be found.', 'blockwerk' ); ?></h1>
		<p><?php esc_html_e( 'It may have moved, or the link may be wrong. Try a search instead:', 'blockwerk' ); ?></p>
		<?php get_search_form(); ?>
		<p><a class="bw-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'blockwerk' ); ?></a></p>
	</main>
</div>

<?php
get_footer();
