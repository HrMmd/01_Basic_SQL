<?php
/**
 * Static page.
 *
 * @package Blockwerk
 */

get_header();
?>

<div class="bw-container bw-layout">
	<main id="primary" class="site-main">
		<?php
		blockwerk_breadcrumbs();

		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bw-entry' ); ?>>
				<?php if ( ! is_front_page() ) : ?>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header>
				<?php endif; ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="entry-featured"><?php the_post_thumbnail( 'large' ); ?></figure>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'blockwerk' ), 'after' => '</div>' ) );
					?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</main>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
