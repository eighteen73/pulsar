<?php
/**
 * Footer copyright, from Site Settings.
 *
 * Supports `{year}` and `{site}` placeholders.
 *
 * Title: Footer: copyright
 * Slug: footer/copyright
 *
 * @package Pulsar
 */

$pulsar_copyright = function_exists( 'Eighteen73\Matter\setting' )
	? (string) Eighteen73\Matter\setting( 'footer.copyright', '{year} {site}' )
	: '{year} {site}';

$pulsar_copyright = strtr(
	$pulsar_copyright,
	[
		'{year}' => date_i18n( 'Y' ),
		'{site}' => get_bloginfo( 'name' ),
	]
);
?>
<p class="site-footer__copyright">&copy; <?php echo esc_html( $pulsar_copyright ); ?></p>
