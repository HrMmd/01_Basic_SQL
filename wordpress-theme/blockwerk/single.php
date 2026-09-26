<?php
/**
 * Single post.
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
			get_template_part( 'template-parts/content', 'single' );

			if ( get_theme_mod( 'blockwerk_show_post_nav', true ) ) {
				the_post_navigation(
					array(
						'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'blockwerk' ) . '</span><span class="nav-title">%title</span>',
						'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'blockwerk' ) . '</span><span class="nav-title">%title</span>',
					)
				);
			}

			blockwerk_related_posts();

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
