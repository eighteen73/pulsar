<?php
/**
 * Theme assets enqueue.
 *
 * @package NewTheme
 */

namespace NewTheme;

use NewTheme\Contracts\Bootable;
use NewTheme\Tools\Mix;

/**
 * Enqueue scripts, styles and fonts.
 */
class Enqueue implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {
		add_action( 'wp_enqueue_scripts', [ $this, 'styles' ], 10 );
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 10 );
	}

	/**
	 * CSS
	 *
	 * @return void
	 */
	public function styles() {

		wp_enqueue_style(
			'new-theme-app',
			Mix::asset( 'css/app.css' ),
			false,
			null
		);
	}

	/**
	 * JavaScript
	 *
	 * @return void
	 */
	public function scripts() {

		wp_enqueue_script(
			'new-theme-app',
			Mix::asset( 'js/app.js' ),
			null,
			null,
			true
		);

		// Load WordPress' comment-reply script where appropriate.
		if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
