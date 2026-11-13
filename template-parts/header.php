<?php
/**
 * Site header.
 *
 * Version-controlled header, rendered in templates with
 * `<!-- wp:matter/template-part {"slug":"header"} /-->`.
 *
 * The primary menu sits in a responsive panel: an off-canvas drawer with a
 * drill-down menu below the `lg` breakpoint, an inline dropdown menu above it.
 * Editable content comes from Appearance > Menus and Appearance > Options.
 *
 * Title: Site header
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\menu;
use function Eighteen73\Matter\panel;
use function Eighteen73\Matter\panel_toggle;

?>
<header class="site-header">
	<div class="site-header__inner">
		<?php get_template_part( 'template-parts/header/logo' ); ?>

		<?php
		panel(
			'site-navigation',
			function () {
				menu(
					[
						'location' => 'primary',
						'type'     => [
							'drill-down',
							'lg' => 'dropdown',
						],
						'label'    => __( 'Main', 'pulsar' ),
					]
				);

				get_template_part( 'template-parts/header/cta' );
			},
			[
				'collapse_below' => 'lg',
				'label'          => __( 'Main menu', 'pulsar' ),
				'class'          => 'site-header__navigation',
			]
		);
		?>

		<div class="site-header__actions">
			<?php
			if ( class_exists( 'WooCommerce' ) ) {
				// My account (or log in), then the basket.
				Pulsar\the_block(
					'woocommerce/customer-account',
					[
						'displayStyle' => 'icon_only',
						'iconStyle'    => 'line',
					]
				);
				// Not on the cart and checkout pages (WooCommerce's default
				// hides it there, keeping its space).
				Pulsar\the_block( 'woocommerce/mini-cart', [ 'cartAndCheckoutRenderStyle' => 'removed' ] );
			}

			panel_toggle(
				'site-navigation',
				[
					'collapse_below' => 'lg',
					'label'          => __( 'Menu', 'pulsar' ),
					'class'          => 'site-header__menu-toggle',
				]
			);
			?>
		</div>
	</div>
</header>
