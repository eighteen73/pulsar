<?php
/**
 * Header call to action, from Appearance > Options.
 *
 * Title: Header: call to action
 * Slug: header/cta
 *
 * @package Pulsar
 */

use function Eighteen73\Matter\setting;

$pulsar_cta_label = setting( 'header.cta_label' );
$pulsar_cta_url   = setting( 'header.cta_url' );

if ( ! $pulsar_cta_label || ! $pulsar_cta_url ) {
	return;
}
?>
<a class="site-header__cta wp-element-button" href="<?php echo esc_url( $pulsar_cta_url ); ?>">
	<?php echo esc_html( $pulsar_cta_label ); ?>
</a>
