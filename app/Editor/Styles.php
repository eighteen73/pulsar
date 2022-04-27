<?php
/**
 * Block styles handling.
 *
 * @package Pulsar
 */

namespace Pulsar\Editor;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;

class Styles implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot() {
		add_action( 'init', [ $this, 'register' ] );
	}

	/**
	 * Retrieve the config for this class.
	 *
	 * @return array
	 */
	public function config() {
		return Config::get( 'block-styles' );
	}

	/**
	 * Registers custom block styles.
	 *
	 * @access public
	 * @return void
	 */
	public function register() {

		$block_styles = $this->config() ?: [];

		foreach ( $block_styles as $style_slug => $styles ) {
			foreach ( $styles as $style ) {
				$this->add( $style_slug, $style );
			}
		}
	}

	/**
	 * Registers a block style.
	 *
	 * @access protected
	 * @param  string $slug
	 * @param  array  $args
	 * @return void
	 */
	protected function add( string $slug, array $args = [] ) {

		register_block_style( $slug, $args );
	}
}
