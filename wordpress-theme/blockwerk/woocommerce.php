<?php
/**
 * WooCommerce pages wrapper.
 *
 * @package Blockwerk
 */

get_header();
?>

<div class="bw-container bw-layout">
	<main id="primary" class="site-main bw-shop">
		<?php
		blockwerk_breadcrumbs();
		woocommerce_content();
		?>
	</main>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
