<?php
/**
 * Block patterns handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;

/**
 * Block patterns handling.
 */
class Patterns implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_action( 'init', [ $this, 'register_categories' ] );
	}

	/**
	 * Retrieve the config for this class.
	 *
	 * @return array
	 */
	public function config(): array {
		return Config::get( 'pattern-categories' );
	}

	 /**
	  * Registers custom block patterns and categories.
	  *
	  * @access public
	  * @return void
	  */
	public function register_categories(): void {

		$categories = $this->config() ?: [];

		foreach ( $categories as $category_slug => $category ) {
			$this->add_category( $category_slug, $category );
		}
	}

	/**
	 * Adds a block pattern category.
	 *
	 * @access protected
	 * @param  string $slug  The slug
	 * @param  string $label The label
	 * @return void
	 */
	protected function add_category( string $slug, string $label ): void {

		// Register block pattern categories.
		register_block_pattern_category(
			$slug,
			[
				'label' => $label,
			]
		);
	}
}
