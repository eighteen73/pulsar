<?php
/**
 * Renders the cards section.
 *
 * Slots keep the classes the editor uses (wp-block-matter-slot + __{slot}),
 * so one stylesheet styles both.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Rendered slots.
 * @param WP_Block $block      Block instance.
 *
 * @package Pulsar
 */

$pulsar_slots = Eighteen73\Matter\slots( $content, $block );
?>
<section <?php echo get_block_wrapper_attributes(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="wp-block-pulsar-cards__inner">
		<?php if ( trim( wp_strip_all_tags( $pulsar_slots['intro'] ) ) ) : ?>
			<div class="wp-block-matter-slot wp-block-pulsar-cards__intro"><?php echo $pulsar_slots['intro']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>
		<div class="wp-block-matter-slot wp-block-pulsar-cards__items"><?php echo $pulsar_slots['items']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</div>
</section>
