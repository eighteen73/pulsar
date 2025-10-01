<?php
/**
 * The Copyright
 *
 * Title: Footer: copyright
 * Slug: footer/copyright
 *
 * @package Pulsar
 */

?>

<div class="site-footer__copyright">
	<?php
	printf(
		/* translators: %1$s: year, %2$s: site name */
		esc_html__( '&#169; %1$s %2$s', 'pulsar' ),
		esc_html( date_i18n( 'Y' ) ),
		esc_html( get_bloginfo( 'name' ) ),
	);
	?>
</div>
