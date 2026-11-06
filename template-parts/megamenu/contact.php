<?php
/**
 * Megamenu panel: get in touch.
 *
 * An example panel, shown beside a megamenu's link columns. Attach it to a
 * top-level menu item in Appearance > Menus ("Megamenu panel"). Panels are
 * developer-built, so their markup is version controlled; their content can
 * still come from the client: here the menu item's description and the
 * contact details and call to action from Appearance > Options.
 *
 * Copy this file to add more panels (e.g. featured products or the latest
 * post). The `Title:` header names it in the panel list.
 *
 * Title: Get in touch
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\setting;

if ( ! function_exists( 'Eighteen73\Matter\setting' ) ) {
	return;
}

$pulsar_item        = $args['item'] ?? null;
$pulsar_description = $pulsar_item instanceof WP_Post ? trim( (string) $pulsar_item->description ) : '';
$pulsar_phone       = setting( 'contact.phone' );
$pulsar_email       = setting( 'contact.email' );
$pulsar_cta_label   = setting( 'header.cta_label' );
$pulsar_cta_url     = setting( 'header.cta_url' );

if ( ! $pulsar_description && ! $pulsar_phone && ! $pulsar_email && ! ( $pulsar_cta_label && $pulsar_cta_url ) ) {
	return;
}
?>
<div class="megamenu-panel has-scheme has-scheme-tint">
	<p class="megamenu-panel__title"><?php esc_html_e( 'Get in touch', 'pulsar' ); ?></p>

	<?php if ( $pulsar_description ) : ?>
		<p class="megamenu-panel__text"><?php echo esc_html( $pulsar_description ); ?></p>
	<?php endif; ?>

	<?php if ( $pulsar_phone || $pulsar_email ) : ?>
		<ul class="megamenu-panel__contact">
			<?php if ( $pulsar_phone ) : ?>
				<li>
					<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $pulsar_phone ) ); ?>"><?php echo esc_html( $pulsar_phone ); ?></a>
				</li>
			<?php endif; ?>

			<?php if ( $pulsar_email ) : ?>
				<li>
					<a href="mailto:<?php echo esc_attr( antispambot( $pulsar_email ) ); ?>"><?php echo esc_html( antispambot( $pulsar_email ) ); ?></a>
				</li>
			<?php endif; ?>
		</ul>
	<?php endif; ?>

	<?php if ( $pulsar_cta_label && $pulsar_cta_url ) : ?>
		<a class="megamenu-panel__button wp-element-button" href="<?php echo esc_url( $pulsar_cta_url ); ?>">
			<?php echo esc_html( $pulsar_cta_label ); ?>
		</a>
	<?php endif; ?>
</div>
