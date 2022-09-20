<?php
/**
 * Theme assets enqueue.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Asset;

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
		add_action( 'enqueue_block_editor_assets', [ $this, 'editor_scripts' ] );
	}

	/**
	 * CSS
	 *
	 * @return void
	 */
	public function styles() {

		wp_enqueue_style(
			'pulsar-app-styles',
			get_theme_file_uri( 'dist/app-styles.css' ),
			[],
			Asset::attribute( 'app-styles', 'version' ),
		);
	}

	/**
	 * JavaScript
	 *
	 * @return void
	 */
	public function scripts() {

        wp_enqueue_script(
            'pulsar-app-scripts',
			get_theme_file_uri( 'dist/app-scripts.js' ),
			Asset::attribute( 'app-scripts', 'dependencies' ),
			Asset::attribute( 'app-scripts', 'version' ),
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
				'dist/editor-styles.css'
			]
		);
	}

	public function editor_scripts() {

		wp_enqueue_script(
			'pulsar-editor-scripts',
			get_theme_file_uri( 'dist/editor-scripts.js' ),
			Asset::attribute( 'editor-scripts', 'dependencies' ),
			Asset::attribute( 'editor-scripts', 'version' ),
			true,
		);
	}
}
