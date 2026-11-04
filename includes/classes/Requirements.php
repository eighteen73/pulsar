<?php
/**
 * Theme requirements.
 *
 * Pulsar 6 relies on Matter 1.x for menus, panels, sections, colour schemes
 * and site settings. Without it the header renders only the logo, and
 * sections can't be edited.
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
					__( 'The Pulsar theme needs the Matter plugin (version %s or later) for menus, sections and site settings.', 'pulsar' ),
					self::MATTER_VERSION
				)
			)
		);
	}
}
