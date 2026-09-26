<?php
/**
 * Main template: blog index, archives and search fallback.
 *
 * @package Blockwerk
 */

get_header();
?>

<div class="bw-container bw-layout">
	<main id="primary" class="site-main">
		<?php blockwerk_breadcrumbs(); ?>

		<?php if ( is_home() && ! is_front_page() ) : ?>
			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>
		<?php elseif ( is_archive() ) : ?>
			<header class="page-header">
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
				<?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
			</header>
		<?php elseif ( is_search() ) : ?>
			<header class="page-header">
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Results for “%s”', 'blockwerk' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
					?>
				</h1>
			</header>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="bw-posts">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', get_post_type() );
				endwhile;
				?>
			</div>
			<?php blockwerk_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content', 'none' ); ?>
		<?php endif; ?>
	</main>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
