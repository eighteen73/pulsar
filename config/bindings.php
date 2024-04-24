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
	Pulsar\Editor\Render\Navigation::class,
	Pulsar\Menu\Classes::class,
	Pulsar\Menu\MegaMenu::class,
	Pulsar\Menu\Responsive::class,
];
