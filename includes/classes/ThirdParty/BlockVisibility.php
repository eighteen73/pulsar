<?php
/**
 * Block Visibility functions and filters.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty;

use Pulsar\Contracts\Bootable;

/**
 * Gravity Forms class.
 *
 * @access public
 */
class BlockVisibility implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @access public
	 * @return void
	 */
	public function boot(): void {
		add_filter( 'block_visibility_settings_defaults', [ $this, 'set_block_visibility_defaults' ] );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return class_exists( 'Block_Visibility' );
	}

	/**
	 * Adds our default breakpoints to Block Visibility.
	 *
	 * @param array $defaults The default settings.
	 *
	 * @return array
	 */
	public function set_block_visibility_defaults( $defaults ): array {
		if ( isset( $defaults['visibility_controls']['screen_size'] ) ) {
			$defaults['visibility_controls']['screen_size']['enable_advanced_controls'] = true;
		}

		if ( isset( $defaults['visibility_controls']['screen_size']['breakpoints'] ) ) {
			$defaults['visibility_controls']['screen_size']['breakpoints'] = [
				'extra_large' => '1280px',
				'large'       => '1024px',
				'medium'      => '768px',
				'small'       => '640px',
			];
		}

		return $defaults;
	}
}
