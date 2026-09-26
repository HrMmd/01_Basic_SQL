/**
 * Blockwerk front-end behaviour. Vanilla JS, no dependencies.
 */
( function () {
	'use strict';

	var root = document.documentElement;
	var lastTrigger = null;

	/* ---------- Modals: off-canvas menu + search ---------- */

	function openModal( modal, trigger ) {
		if ( ! modal ) {
			return;
		}
		lastTrigger = trigger || null;
		modal.hidden = false;
		document.body.style.overflow = 'hidden';
		if ( trigger ) {
			trigger.setAttribute( 'aria-expanded', 'true' );
		}
		var focusable = modal.querySelector( 'input, a, button:not([data-bw-close])' );
		if ( focusable ) {
			focusable.focus();
		}
	}

	function closeModal( modal ) {
		if ( ! modal || modal.hidden ) {
			return;
		}
		modal.hidden = true;
		document.body.style.overflow = '';
		if ( lastTrigger ) {
			lastTrigger.setAttribute( 'aria-expanded', 'false' );
			lastTrigger.focus();
			lastTrigger = null;
		}
	}

	document.addEventListener( 'click', function ( event ) {
		var toggle = event.target.closest( '[data-bw-toggle]' );
		if ( toggle ) {
			openModal( document.getElementById( toggle.getAttribute( 'aria-controls' ) ), toggle );
			return;
		}
		var closer = event.target.closest( '[data-bw-close]' );
		if ( closer ) {
			closeModal( closer.closest( '.bw-offcanvas, .bw-search-modal' ) );
		}
	} );

	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key ) {
			document.querySelectorAll( '.bw-offcanvas, .bw-search-modal' ).forEach( closeModal );
		}
		// Keyboard shortcut: "/" opens search.
		if ( '/' === event.key && ! /INPUT|TEXTAREA|SELECT/.test( document.activeElement.tagName ) && ! document.activeElement.isContentEditable ) {
			var searchToggle = document.querySelector( '[data-bw-toggle="search"]' );
			if ( searchToggle ) {
				event.preventDefault();
				searchToggle.click();
			}
		}
	} );

	// Close the off-canvas menu when an in-page link is followed.
	document.querySelectorAll( '.bw-offcanvas a[href*="#"]' ).forEach( function ( link ) {
		link.addEventListener( 'click', function () {
			closeModal( link.closest( '.bw-offcanvas' ) );
		} );
	} );

	/* ---------- Dark mode ---------- */

	document.querySelectorAll( '.bw-scheme-toggle' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var next = 'dark' === root.getAttribute( 'data-scheme' ) ? 'light' : 'dark';
			root.setAttribute( 'data-scheme', next );
			try {
				localStorage.setItem( 'blockwerk-scheme', next );
			} catch ( e ) {}
		} );
	} );

	/* ---------- Scroll effects ---------- */

	var header = document.querySelector( '.site-header' );
	var backToTop = document.querySelector( '.bw-back-to-top' );
	var progress = document.querySelector( '.bw-progress span' );
	var article = document.querySelector( '.bw-entry .entry-content' );
	var ticking = false;

	function onScroll() {
		var y = window.scrollY;
		if ( header ) {
			header.classList.toggle( 'is-scrolled', y > 10 );
		}
		if ( backToTop ) {
			backToTop.classList.toggle( 'is-visible', y > 600 );
		}
		if ( progress && article ) {
			var rect = article.getBoundingClientRect();
			var total = rect.height - window.innerHeight;
			var ratio = total > 0 ? Math.min( 1, Math.max( 0, -rect.top / total ) ) : 1;
			progress.style.transform = 'scaleX(' + ratio + ')';
		}
		ticking = false;
	}

	window.addEventListener( 'scroll', function () {
		if ( ! ticking ) {
			window.requestAnimationFrame( onScroll );
			ticking = true;
		}
	}, { passive: true } );
	onScroll();

	if ( backToTop ) {
		backToTop.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	/* ---------- Copy link ---------- */

	document.querySelectorAll( '.bw-copy-link' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			if ( ! navigator.clipboard ) {
				return;
			}
			navigator.clipboard.writeText( btn.getAttribute( 'data-url' ) ).then( function () {
				btn.classList.add( 'is-copied' );
				setTimeout( function () {
					btn.classList.remove( 'is-copied' );
				}, 1600 );
			} );
		} );
	} );
}() );
