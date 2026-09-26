<?php
/**
 * Single post content.
 *
 * @package Blockwerk
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bw-entry' ); ?>>
	<header class="entry-header">
		<?php blockwerk_category_badge(); ?>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php blockwerk_post_meta(); ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="entry-featured"><?php the_post_thumbnail( 'large', array( 'fetchpriority' => 'high' ) ); ?></figure>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();
		wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blockwerk' ), 'after' => '</div>' ) );
		?>
	</div>

	<footer class="entry-footer">
		<?php
		$blockwerk_tags = get_the_tag_list( '<ul class="bw-tags"><li>', '</li><li>', '</li></ul>' );
		if ( $blockwerk_tags && ! is_wp_error( $blockwerk_tags ) ) {
			echo $blockwerk_tags; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		blockwerk_share_buttons();
		blockwerk_author_box();
		?>
	</footer>
</article>
