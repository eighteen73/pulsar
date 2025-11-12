<?php
/**
 * Bindings are all of the classes from includes/classes that
 * need to be registered in the theme. This is typically anything
 * not in the includes/classes/Tools folder.
 *
 * @package Pulsar
 */

return [
	Pulsar\Setup::class,
	Pulsar\Enqueue::class,
	Pulsar\Editor\Blocks::class,
	Pulsar\Editor\Patterns::class,
	Pulsar\Editor\TemplateParts::class,
	Pulsar\ThirdParty\BlockVisibility::class,
	Pulsar\ThirdParty\GravityForms::class,
	Pulsar\ThirdParty\WooCommerce\Setup::class,
	Pulsar\ThirdParty\WooCommerce\Account::class,
	Pulsar\ThirdParty\WooCommerce\SingleProduct::class,
];
