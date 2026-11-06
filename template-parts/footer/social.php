<?php
/**
 * Footer social links, from Appearance > Options.
 *
 * Icons come from core's social link block.
 *
 * Title: Footer: social links
 * Slug: footer/social
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\setting;

if ( ! function_exists( 'Eighteen73\Matter\setting' ) || ! function_exists( 'block_core_social_link_get_icon' ) ) {
	return;
}

$pulsar_links = array_filter(
	(array) setting( 'social.links', [] ),
	fn( $link ) => ! empty( $link['service'] ) && ! empty( $link['url'] )
);

if ( ! $pulsar_links ) {
	return;
}
?>
<ul class="site-footer__social">
	<?php foreach ( $pulsar_links as $pulsar_link ) : ?>
		<li>
			<a class="site-footer__social-link" href="<?php echo esc_url( $pulsar_link['url'] ); ?>" target="_blank" rel="noopener">
				<?php echo block_core_social_link_get_icon( $pulsar_link['service'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core SVG. ?>
				<span class="screen-reader-text"><?php echo esc_html( block_core_social_link_get_name( $pulsar_link['service'] ) ); ?></span>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
