<?php
/**
 * The footer credit
 *
 * Title: Footer: Credit
 * Slug: footer/credit
 *
 * @package Pulsar
 */

?>
<?php
$pulsar_credit_url = add_query_arg(
	[
		'utm_source'   => wp_parse_url( home_url(), PHP_URL_HOST ),
		'utm_medium'   => 'referral',
		'utm_campaign' => 'websitecredit',
	],
	'https://eighteen73.co.uk'
);
?>
<a class="site-footer__credit" href="<?php echo esc_url( $pulsar_credit_url ); ?>" target="_blank" rel="noopener">
	<span><?php esc_html_e( 'Site by web and creative agency', 'pulsar' ); ?></span>

	<?php
	Pulsar\display_svg(
		'eighteen73',
		[
			'class' => 'site-footer__credit-logo',
			'title' => 'eighteen73',
		]
	);
	?>
</a>
