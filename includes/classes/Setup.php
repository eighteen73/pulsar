<?php
/**
 * Theme setup.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;

/**
 * Setup class.
 */
class Setup implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'after_setup_theme', [ $this, 'supports' ], 5 );
		add_action( 'init', [ $this, 'image_sizes' ] );
		add_filter( 'image_size_names_choose', [ $this, 'image_size_names' ] );
	}

	/**
	 * Set up theme support.
	 *
	 * @return void
	 */
	public function supports(): void {

		// Disable core block patterns.
		remove_theme_support( 'core-block-patterns' );
	}

	/**
	 * Add custom image sizes.
	 *
	 * @return void
	 */
	public function image_sizes(): void {
		// add_image_size( '4x3', 640, 480, true );
	}

	/**
	 * Register custom image size names.
	 *
	 * @param array $sizes Array of image size names.
	 *
	 * @return array
	 */
	public function image_size_names( array $sizes ): array {
		return array_merge(
			$sizes,
			[
				// 'example' => __( 'Example' ),
			]
		);
	}
}
