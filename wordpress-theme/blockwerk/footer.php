<?php
/**
 * Site footer.
 *
 * @package Blockwerk
 */

$blockwerk_cols = absint( get_theme_mod( 'blockwerk_footer_columns', 4 ) );
$blockwerk_has  = false;
for ( $blockwerk_i = 1; $blockwerk_i <= $blockwerk_cols; $blockwerk_i++ ) {
	$blockwerk_has = $blockwerk_has || is_active_sidebar( 'footer-' . $blockwerk_i );
}
?>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<?php if ( $blockwerk_has ) : ?>
			<div class="footer-widgets">
				<div class="bw-container footer-grid">
					<?php for ( $blockwerk_i = 1; $blockwerk_i <= $blockwerk_cols; $blockwerk_i++ ) : ?>
						<div class="footer-col">
							<?php dynamic_sidebar( 'footer-' . $blockwerk_i ); ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="footer-bottom">
			<div class="bw-container">
				<p class="copyright"><?php blockwerk_copyright(); ?></p>
				<?php
				if ( has_nav_menu( 'footer' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => 'nav',
							'container_class' => 'footer-navigation',
							'menu_class'     => 'menu',
							'depth'          => 1,
						)
					);
				}
				blockwerk_social_links();
				?>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php if ( get_theme_mod( 'blockwerk_back_to_top', true ) ) : ?>
	<button type="button" class="bw-back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'blockwerk' ); ?>"><?php blockwerk_the_icon( 'arrow-up' ); ?></button>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
