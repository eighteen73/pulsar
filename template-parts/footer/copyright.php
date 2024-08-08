<?php
/**
 * The Copyright
 *
 * Title: Footer: copyright
 * slug: footer/copyright
 *
 * @package Pulsar
 */

printf(
	esc_html__( 'Copyright &#169; %1$s %2$s', 'pulsar' ),
	esc_html( date_i18n( 'Y' ) ),
	esc_html( get_bloginfo( 'name' ) ),
);
