<?php
/**
 * Theme requirements.
 *
 * Pulsar 6 relies on Matter 1.x for menus, panels, sections, colour schemes
 * and site settings. Matter is a must-use plugin installed by Composer, so
 * the theme never checks for it; this only warns when Composer has installed
 * an older version, or none, rather than failing later with a fatal error.
 *
 * @package Pulsar
 */

namespace Pulsar;

use Pulsar\Contracts\Bootable;

/**
 * Theme requirements.
 */
class Requirements implements Bootable {

	/**
	 * Minimum Matter version.
	 */
	private const MATTER_VERSION = '1.0.0';

	/**
	 * Bootstraps the class' actions/filters.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'admin_notices', [ $this, 'matter_notice' ] );
	}

	/**
	 * Determines if the class can be booted.
	 *
	 * @return bool
	 */
	public function can_boot(): bool {
		return ! defined( 'MATTER_VERSION' ) || version_compare( MATTER_VERSION, self::MATTER_VERSION, '<' );
	}

	/**
	 * Warn administrators when Matter is missing or too old.
	 *
	 * @return void
	 */
	public function matter_notice(): void {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		printf(
			'<div class="notice notice-error"><p>%s</p></div>',
			esc_html(
				sprintf(
					/* translators: %s: required Matter version */
					__( 'The Pulsar theme needs Matter %s or later, installed as a must-use plugin by Composer (eighteen73/matter).', 'pulsar' ),
					self::MATTER_VERSION
				)
			)
		);
	}
}
