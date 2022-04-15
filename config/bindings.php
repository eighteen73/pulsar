<?php
return [
	NewTheme\Setup::class => new NewTheme\Setup(),
	NewTheme\Enqueue::class => new NewTheme\Enqueue(),
	NewTheme\WooCommerce\Setup::class => new NewTheme\WooCommerce\Setup(),
	NewTheme\WooCommerce\SingleProduct::class => new NewTheme\WooCommerce\SingleProduct(),
];
