<?php
/**
 * Footer contact details, from Appearance > Options.
 *
 * Title: Footer: contact details
 * Slug: footer/contact
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\setting;

if ( ! function_exists( 'Eighteen73\Matter\setting' ) ) {
	return;
}

$pulsar_address = setting( 'contact.address' );
$pulsar_phone   = setting( 'contact.phone' );
$pulsar_email   = setting( 'contact.email' );

if ( ! $pulsar_address && ! $pulsar_phone && ! $pulsar_email ) {
	return;
}
?>
<address class="site-footer__contact">
	<?php if ( $pulsar_address ) : ?>
		<p class="site-footer__address"><?php echo nl2br( esc_html( $pulsar_address ) ); ?></p>
	<?php endif; ?>

	<?php if ( $pulsar_phone ) : ?>
		<p class="site-footer__phone">
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $pulsar_phone ) ); ?>"><?php echo esc_html( $pulsar_phone ); ?></a>
		</p>
	<?php endif; ?>

	<?php if ( $pulsar_email ) : ?>
		<p class="site-footer__email">
			<a href="mailto:<?php echo esc_attr( antispambot( $pulsar_email ) ); ?>"><?php echo esc_html( antispambot( $pulsar_email ) ); ?></a>
		</p>
	<?php endif; ?>
</address>
