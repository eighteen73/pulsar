<?php
/**
 * Matter integration.
 *
 * Feeds the theme's configuration (settings fields, breakpoints…) into Matter.
 *
 * @package Pulsar
 */

namespace Pulsar\ThirdParty;

use Pulsar\Contracts\Bootable;
use Pulsar\Tools\Config;

/**
 * Matter integration.
 */
class Matter implements Bootable {

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_filter( 'matter_settings_fields', [ $this, 'settings_fields' ] );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return class_exists( \Eighteen73\Matter\Plugin::class );
	}

	/**
	 * Site settings fields from `config/settings.php`.
	 *
	 * @param array $fields Fields declared elsewhere.
	 * @return array
	 */
	public function settings_fields( array $fields ): array {
		return array_merge( $fields, Config::get( 'settings' ) );
	}
}
