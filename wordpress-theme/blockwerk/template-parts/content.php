<?php
/**
 * Post card used in archives (grid / list / classic).
 *
 * @package Blockwerk
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bw-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="bw-card-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'blockwerk-card', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>

	<div class="bw-card-body">
		<?php blockwerk_category_badge(); ?>
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		<div class="entry-summary"><?php the_excerpt(); ?></div>
		<?php if ( 'post' === get_post_type() ) : ?>
			<?php blockwerk_post_meta(); ?>
		<?php endif; ?>
	</div>
</article>
