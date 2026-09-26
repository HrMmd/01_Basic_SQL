<?php
/**
 * Template helpers used across theme templates.
 *
 * @package Blockwerk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline SVG icon set (stroke icons, 24x24 viewBox).
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 * @return string
 */
function blockwerk_icon( $name, $size = 20 ) {
	$paths = array(
		'search'    => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
		'menu'      => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'     => '<path d="M6 6l12 12M18 6 6 18"/>',
		'sun'       => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>',
		'moon'      => '<path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/>',
		'arrow-up'  => '<path d="M12 19V5M5 12l7-7 7 7"/>',
		'cart'      => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.7 12.4a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.5L21 8H6"/>',
		'user'      => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'link'      => '<path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7"/>',
		'facebook'  => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5h.01"/>',
		'x'         => '<path d="M4 4l16 16M20 4 4 20"/>',
		'linkedin'  => '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10v7M8 7v.01M12 17v-4a2 2 0 0 1 4 0v4M12 10v7"/>',
		'youtube'   => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 5 3-5 3z"/>',
		'github'    => '<path d="M9 19c-4 1.5-4-2-6-2.5M15 22v-3.5a3 3 0 0 0-.9-2.4c3-.3 6-1.5 6-6.6a5.2 5.2 0 0 0-1.4-3.6 4.8 4.8 0 0 0-.1-3.6s-1.1-.3-3.7 1.4a12.6 12.6 0 0 0-6.6 0C5.7 2 4.6 2.3 4.6 2.3a4.8 4.8 0 0 0-.1 3.6A5.2 5.2 0 0 0 3 9.5c0 5.1 3 6.3 6 6.6a3 3 0 0 0-.9 2.3V22"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="bw-icon bw-icon-%1$s" width="%2$d" height="%2$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr( $name ),
		(int) $size,
		$paths[ $name ]
	);
}

/**
 * Echo an icon (markup is static and trusted).
 *
 * @param string $name Icon name.
 * @param int    $size Pixel size.
 */
function blockwerk_the_icon( $name, $size = 20 ) {
	echo blockwerk_icon( $name, $size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Site branding: logo or title + tagline.
 */
function blockwerk_site_branding() {
	echo '<div class="site-branding">';
	if ( has_custom_logo() ) {
		the_custom_logo();
	} else {
		$tag = ( is_front_page() && is_home() ) ? 'h1' : 'p';
		printf(
			'<%1$s class="site-title"><a href="%2$s" rel="home">%3$s</a></%1$s>',
			tag_escape( $tag ),
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name' ) )
		);
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) {
			echo '<p class="site-description">' . esc_html( $description ) . '</p>';
		}
	}
	echo '</div>';
}

/**
 * Estimated reading time in minutes.
 *
 * @param int|null $post_id Post ID.
 * @return int
 */
function blockwerk_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id ? $post_id : get_the_ID() );
	$words   = str_word_count( wp_strip_all_tags( strip_shortcodes( (string) $content ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * Post meta line: author, date, reading time.
 */
function blockwerk_post_meta() {
	$time = sprintf(
		'<time class="entry-date" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);

	echo '<div class="entry-meta">';
	printf(
		'<span class="meta-author">%1$s <a href="%2$s">%3$s</a></span>',
		get_avatar( get_the_author_meta( 'ID' ), 24 ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
	echo '<span class="meta-date">' . $time . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	if ( get_theme_mod( 'blockwerk_show_reading_time', true ) ) {
		$minutes = blockwerk_reading_time();
		printf(
			'<span class="meta-reading">%1$s %2$s</span>',
			blockwerk_icon( 'clock', 14 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			/* translators: %d: minutes. */
			esc_html( sprintf( _n( '%d min read', '%d min read', $minutes, 'blockwerk' ), $minutes ) )
		);
	}
	echo '</div>';
}

/**
 * First category as a pill badge.
 */
function blockwerk_category_badge() {
	$cats = get_the_category();
	if ( empty( $cats ) ) {
		return;
	}
	printf(
		'<a class="bw-badge" href="%1$s">%2$s</a>',
		esc_url( get_category_link( $cats[0]->term_id ) ),
		esc_html( $cats[0]->name )
	);
}

/**
 * Breadcrumbs with schema.org markup. Uses Yoast / Rank Math if present.
 */
function blockwerk_breadcrumbs() {
	if ( ! get_theme_mod( 'blockwerk_breadcrumbs', true ) || is_front_page() ) {
		return;
	}
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb( '<nav class="bw-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'blockwerk' ) . '">', '</nav>' );
		return;
	}
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		rank_math_the_breadcrumbs();
		return;
	}
	if ( function_exists( 'woocommerce_breadcrumb' ) && function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		woocommerce_breadcrumb(
			array(
				'wrap_before' => '<nav class="bw-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'blockwerk' ) . '"><ol>',
				'wrap_after'  => '</ol></nav>',
				'before'      => '<li>',
				'after'       => '</li>',
				'delimiter'   => '',
			)
		);
		return;
	}

	$items   = array();
	$items[] = array( __( 'Home', 'blockwerk' ), home_url( '/' ) );

	if ( is_singular( 'post' ) ) {
		$cats = get_the_category();
		if ( $cats ) {
			$items[] = array( $cats[0]->name, get_category_link( $cats[0]->term_id ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor ) {
			$items[] = array( get_the_title( $ancestor ), get_permalink( $ancestor ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( is_singular() ) {
		$items[] = array( get_the_title(), '' );
	} elseif ( is_archive() ) {
		$items[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$items[] = array( sprintf( __( 'Search: %s', 'blockwerk' ), get_search_query() ), '' );
	} elseif ( is_404() ) {
		$items[] = array( __( 'Page not found', 'blockwerk' ), '' );
	} elseif ( is_home() ) {
		$items[] = array( single_post_title( '', false ), '' );
	}

	echo '<nav class="bw-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'blockwerk' ) . '"><ol itemscope itemtype="https://schema.org/BreadcrumbList">';
	foreach ( $items as $i => $item ) {
		echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		if ( $item[1] ) {
			printf( '<a itemprop="item" href="%1$s"><span itemprop="name">%2$s</span></a>', esc_url( $item[1] ), esc_html( $item[0] ) );
		} else {
			printf( '<span itemprop="name" aria-current="page">%s</span>', esc_html( $item[0] ) );
		}
		printf( '<meta itemprop="position" content="%d" /></li>', (int) $i + 1 );
	}
	echo '</ol></nav>';
}

/**
 * Social profile links from the Customizer.
 */
function blockwerk_social_links() {
	$out = '';
	foreach ( blockwerk_social_networks() as $key => $label ) {
		$url = get_theme_mod( 'blockwerk_social_' . $key );
		if ( $url ) {
			$out .= sprintf(
				'<li><a href="%1$s" target="_blank" rel="noopener me" aria-label="%2$s">%3$s</a></li>',
				esc_url( $url ),
				esc_attr( $label ),
				blockwerk_icon( $key, 18 )
			);
		}
	}
	if ( $out ) {
		echo '<ul class="bw-social">' . $out . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Share buttons for single posts.
 */
function blockwerk_share_buttons() {
	if ( ! get_theme_mod( 'blockwerk_show_share', true ) ) {
		return;
	}
	$url   = rawurlencode( get_permalink() );
	$title = rawurlencode( get_the_title() );
	$links = array(
		'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
		'x'        => 'https://x.com/intent/post?url=' . $url . '&text=' . $title,
		'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $url,
		'mail'     => 'mailto:?subject=' . $title . '&body=' . $url,
	);

	echo '<div class="bw-share"><span>' . esc_html__( 'Share', 'blockwerk' ) . '</span>';
	foreach ( $links as $key => $href ) {
		printf(
			'<a href="%1$s" target="_blank" rel="noopener nofollow" aria-label="%2$s">%3$s</a>',
			esc_url( $href ),
			/* translators: %s: network name. */
			esc_attr( sprintf( __( 'Share on %s', 'blockwerk' ), ucfirst( $key ) ) ),
			blockwerk_icon( $key, 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	printf(
		'<button type="button" class="bw-copy-link" data-url="%1$s" aria-label="%2$s">%3$s</button>',
		esc_url( get_permalink() ),
		esc_attr__( 'Copy link', 'blockwerk' ),
		blockwerk_icon( 'link', 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
	echo '</div>';
}

/**
 * Author box below single posts.
 */
function blockwerk_author_box() {
	if ( ! get_theme_mod( 'blockwerk_show_author_box', true ) ) {
		return;
	}
	$id = get_the_author_meta( 'ID' );
	?>
	<aside class="bw-author-box">
		<?php echo get_avatar( $id, 72 ); ?>
		<div>
			<p class="bw-author-name"><a href="<?php echo esc_url( get_author_posts_url( $id ) ); ?>"><?php the_author(); ?></a></p>
			<?php if ( get_the_author_meta( 'description' ) ) : ?>
				<p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
			<?php endif; ?>
		</div>
	</aside>
	<?php
}

/**
 * Related posts by shared category.
 */
function blockwerk_related_posts() {
	if ( ! get_theme_mod( 'blockwerk_show_related', true ) ) {
		return;
	}
	$cats = wp_get_post_categories( get_the_ID() );
	if ( ! $cats ) {
		return;
	}
	$query = new WP_Query(
		array(
			'category__in'        => $cats,
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( ! $query->have_posts() ) {
		return;
	}
	echo '<section class="bw-related"><h2>' . esc_html__( 'You might also like', 'blockwerk' ) . '</h2><div class="bw-related-grid">';
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		<article class="bw-card">
			<a class="bw-card-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'blockwerk-card', array( 'loading' => 'lazy' ) );
				}
				?>
			</a>
			<div class="bw-card-body">
				<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
			</div>
		</article>
		<?php
	}
	echo '</div></section>';
	wp_reset_postdata();
}

/**
 * Numbered pagination for archives.
 */
function blockwerk_pagination() {
	the_posts_pagination(
		array(
			'mid_size'  => 1,
			'prev_text' => '&larr; <span class="screen-reader-text">' . esc_html__( 'Previous', 'blockwerk' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'blockwerk' ) . '</span> &rarr;',
		)
	);
}

/**
 * Render the copyright line.
 */
function blockwerk_copyright() {
	$text = get_theme_mod( 'blockwerk_copyright', __( '© {year} {site} — Powered by WordPress', 'blockwerk' ) );
	$text = str_replace( array( '{year}', '{site}' ), array( gmdate( 'Y' ), get_bloginfo( 'name' ) ), $text );
	echo esc_html( $text );
}

/**
 * Header action icons (search, dark mode, cart, account, button).
 */
function blockwerk_header_actions() {
	echo '<div class="header-actions">';

	if ( get_theme_mod( 'blockwerk_header_search', true ) ) {
		printf(
			'<button type="button" class="bw-icon-btn" data-bw-toggle="search" aria-controls="bw-search-modal" aria-expanded="false" aria-label="%1$s">%2$s</button>',
			esc_attr__( 'Open search', 'blockwerk' ),
			blockwerk_icon( 'search' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	if ( get_theme_mod( 'blockwerk_dark_mode_toggle', true ) ) {
		printf(
			'<button type="button" class="bw-icon-btn bw-scheme-toggle" aria-label="%1$s">%2$s%3$s</button>',
			esc_attr__( 'Toggle dark mode', 'blockwerk' ),
			blockwerk_icon( 'moon' ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			blockwerk_icon( 'sun' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}

	do_action( 'blockwerk_header_actions' );

	$label = get_theme_mod( 'blockwerk_header_button' );
	if ( $label ) {
		printf(
			'<a class="bw-button bw-header-button" href="%1$s">%2$s</a>',
			esc_url( get_theme_mod( 'blockwerk_header_button_url', '#' ) ),
			esc_html( $label )
		);
	}

	printf(
		'<button type="button" class="bw-icon-btn bw-menu-toggle" data-bw-toggle="offcanvas" aria-controls="bw-offcanvas" aria-expanded="false" aria-label="%1$s">%2$s</button>',
		esc_attr__( 'Open menu', 'blockwerk' ),
		blockwerk_icon( 'menu', 22 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);

	echo '</div>';
}

/**
 * Primary navigation.
 *
 * @param string $class Extra class.
 */
function blockwerk_primary_nav( $class = '' ) {
	if ( ! has_nav_menu( 'primary' ) ) {
		if ( current_user_can( 'edit_theme_options' ) ) {
			printf(
				'<nav class="main-navigation %1$s"><ul class="menu"><li><a href="%2$s">%3$s</a></li></ul></nav>',
				esc_attr( $class ),
				esc_url( admin_url( 'nav-menus.php' ) ),
				esc_html__( 'Add a menu', 'blockwerk' )
			);
		}
		return;
	}
	echo '<nav class="main-navigation ' . esc_attr( $class ) . '" aria-label="' . esc_attr__( 'Primary', 'blockwerk' ) . '">';
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'menu',
			'depth'          => 3,
		)
	);
	echo '</nav>';
}
