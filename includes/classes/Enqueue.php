<?php
/**
 * Theme assets enqueue.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Mix;

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
		add_action( 'admin_init', [ $this, 'editor_styles' ] );
	}

	/**
	 * CSS
	 *
	 * @return void
	 */
	public function styles() {

		wp_enqueue_style(
			'pulsar-app',
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
            'pulsar-app-manifest',
            Mix::asset( 'js/manifest.js' ),
            null,
            null,
            true
        );

        wp_enqueue_script(
            'pulsar-app-vendor',
            Mix::asset( 'js/vendor.js' ),
            [ 'pulsar-app-manifest' ],
            null,
            true
        );

        wp_enqueue_script(
            'pulsar-app',
            Mix::asset( 'js/app.js' ),
            [ 'pulsar-app-vendor' ],
            null,
            true
        );

		// Load WordPress' comment-reply script where appropriate.
		if ( is_singular() && get_option( 'thread_comments' ) && comments_open() ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Editor stylesheets.
	 *
	 * @return void
	 */
	public function editor_styles() {

		add_editor_style(
			[
				'dist/editor.css'
			]
		);
	}
}
