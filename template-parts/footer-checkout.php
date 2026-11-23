<?php
/**
 * Checkout footer: legal links and a secure checkout notice.
 *
 * Title: Site footer (checkout)
 *
 * @package Pulsar
 */

?>
<footer class="site-footer site-footer--checkout">
	<div class="site-footer__inner">
		<div class="site-footer__legal">
			<?php
			Eighteen73\Matter\menu(
				[
					'location' => 'legal',
					'depth'    => 1,
					'class'    => 'site-footer__legal-menu',
				]
			);
			?>
			<p class="site-footer__secure-checkout"><?php esc_html_e( 'Secure Checkout', 'pulsar' ); ?></p>
		</div>
	</div>
</footer>
