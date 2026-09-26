<?php
/**
 * Site header.
 *
 * @package Blockwerk
 */

$blockwerk_layout = get_theme_mod( 'blockwerk_header_layout', 'inline' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( is_singular( 'post' ) && get_theme_mod( 'blockwerk_reading_progress', true ) ) : ?>
	<div class="bw-progress" aria-hidden="true"><span></span></div>
<?php endif; ?>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'blockwerk' ); ?></a>

	<?php if ( get_theme_mod( 'blockwerk_topbar', false ) ) : ?>
		<div class="bw-topbar">
			<div class="bw-container">
				<span><?php echo esc_html( get_theme_mod( 'blockwerk_topbar_text', '' ) ); ?></span>
				<?php
				if ( has_nav_menu( 'secondary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'secondary',
							'container'      => 'nav',
							'menu_class'     => 'menu',
							'depth'          => 1,
						)
					);
				}
				blockwerk_social_links();
				?>
			</div>
		</div>
	<?php endif; ?>

	<header id="masthead" class="site-header">
		<div class="bw-container header-inner">
			<?php if ( 'split' === $blockwerk_layout ) : ?>
				<?php blockwerk_primary_nav( 'nav-left' ); ?>
				<?php blockwerk_site_branding(); ?>
				<?php blockwerk_header_actions(); ?>
			<?php elseif ( 'centered' === $blockwerk_layout ) : ?>
				<div class="header-spacer"></div>
				<?php blockwerk_site_branding(); ?>
				<?php blockwerk_header_actions(); ?>
			<?php else : ?>
				<?php blockwerk_site_branding(); ?>
				<?php blockwerk_primary_nav(); ?>
				<?php blockwerk_header_actions(); ?>
			<?php endif; ?>
		</div>
		<?php if ( 'centered' === $blockwerk_layout ) : ?>
			<div class="header-row-bottom">
				<div class="bw-container"><?php blockwerk_primary_nav( 'nav-centered' ); ?></div>
			</div>
		<?php endif; ?>
	</header>

	<div id="bw-offcanvas" class="bw-offcanvas" hidden>
		<div class="bw-offcanvas-backdrop" data-bw-close></div>
		<div class="bw-offcanvas-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Menu', 'blockwerk' ); ?>">
			<button type="button" class="bw-icon-btn bw-offcanvas-close" data-bw-close aria-label="<?php esc_attr_e( 'Close menu', 'blockwerk' ); ?>"><?php blockwerk_the_icon( 'close' ); ?></button>
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => 'nav',
						'container_class' => 'mobile-navigation',
						'menu_class'     => 'menu',
					)
				);
			}
			get_search_form();
			blockwerk_social_links();
			?>
		</div>
	</div>

	<div id="bw-search-modal" class="bw-search-modal" hidden>
		<div class="bw-offcanvas-backdrop" data-bw-close></div>
		<div class="bw-search-panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'blockwerk' ); ?>">
			<?php get_search_form(); ?>
			<button type="button" class="bw-icon-btn" data-bw-close aria-label="<?php esc_attr_e( 'Close search', 'blockwerk' ); ?>"><?php blockwerk_the_icon( 'close' ); ?></button>
		</div>
	</div>

	<div id="content" class="site-content">
