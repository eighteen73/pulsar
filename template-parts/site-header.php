<?php
/**
 * Site header.
 *
 * Version-controlled header, rendered in templates with
 * `<!-- wp:pulsar/template-part {"slug":"site-header"} /-->`.
 *
 * The primary menu sits in a responsive panel: an off-canvas drawer with a
 * drill-down menu below the `lg` breakpoint, an inline dropdown menu above it.
 * Editable content comes from Appearance > Menus and Site Settings.
 *
 * Title: Site header
 * Slug: site-header
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\menu;
use function Eighteen73\Matter\panel;
use function Eighteen73\Matter\panel_toggle;

$pulsar_has_matter = function_exists( 'Eighteen73\Matter\menu' );
?>
<header class="site-header">
	<div class="site-header__inner">
		<?php get_template_part( 'template-parts/header/logo' ); ?>

		<?php
		if ( $pulsar_has_matter ) {
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
		}
		?>

		<div class="site-header__actions">
			<?php
			if ( class_exists( 'WooCommerce' ) ) {
				Pulsar\the_block( 'woocommerce/mini-cart' );
			}

			if ( $pulsar_has_matter ) {
				panel_toggle(
					'site-navigation',
					[
						'collapse_below' => 'lg',
						'label'          => __( 'Menu', 'pulsar' ),
						'class'          => 'site-header__menu-toggle',
					]
				);
			}
			?>
		</div>
	</div>
</header>
