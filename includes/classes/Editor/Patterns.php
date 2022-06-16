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
	public function boot() {
		add_action( 'init', [ $this, 'register_categories' ] );
	}

	/**
	 * Retrieve the config for this class.
	 *
	 * @return array
	 */
	public function config() {
		return Config::get( 'block-patterns' );
	}

	 /**
	  * Registers custom block patterns and categories.
	  *
	  * @access public
	  * @return void
	  */
	public function register_categories() {

		$categories = $this->config()['categories'] ?: [];

		foreach ( $categories as $category_slug => $category ) {
			$this->add_category( $category_slug, $category );
		}
	}

	/**
	 * Adds a block pattern category.
	 *
	 * @access protected
	 * @param  string $slug
	 * @param  string $label
	 * @return void
	 */
	protected function add_category( string $slug, string $label ) {

		// Register block pattern categories.
		register_block_pattern_category(
			$slug,
			[
				'label' => $label,
			]
		);
	}
}
