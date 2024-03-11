<?php
/**
 * The header for our theme
 *
 * Title: Header
 * slug: header
 *
 * @package Pulsar
 */

?>

<div class="site-header__inner">
	<?php Pulsar\site_logo(); ?>
	<?php get_template_part( 'template-parts/menu', 'primary' ); ?>
</div>
