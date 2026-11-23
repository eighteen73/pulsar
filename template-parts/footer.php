<?php
/**
 * Site footer.
 *
 * Version-controlled footer, rendered in templates with
 * `<!-- wp:matter/template-part {"slug":"footer"} /-->`.
 * Editable content comes from Appearance > Menus and Appearance > Options.
 *
 * Title: Site footer
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\menu;

?>
<footer class="site-footer">
	<div class="site-footer__inner">
		<div class="site-footer__main">
			<div class="site-footer__brand">
				<?php
				get_template_part( 'template-parts/footer/logo' );
				get_template_part( 'template-parts/footer/contact' );
				get_template_part( 'template-parts/footer/social' );
				?>
			</div>

			<div class="site-footer__menus">
				<?php
				foreach ( [ 'footer-1', 'footer-2' ] as $pulsar_location ) {
					menu(
						[
							'location'    => $pulsar_location,
							'orientation' => 'vertical',
							'depth'       => 1,
							'class'       => 'site-footer__menu',
						]
					);
				}
				?>
			</div>
		</div>

		<div class="site-footer__legal">
			<?php
			menu(
				[
					'location' => 'legal',
					'depth'    => 1,
					'class'    => 'site-footer__legal-menu',
				]
			);

			get_template_part( 'template-parts/footer/copyright' );
			get_template_part( 'template-parts/footer/credit' );
			?>
		</div>
	</div>
</footer>
