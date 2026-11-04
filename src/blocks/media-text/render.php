<?php
/**
 * Renders the media-text section.
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
	<div class="wp-block-pulsar-media-text__inner">
		<div class="wp-block-matter-slot wp-block-pulsar-media-text__media"><?php echo $pulsar_slots['media']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<div class="wp-block-matter-slot wp-block-pulsar-media-text__content"><?php echo $pulsar_slots['content']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	</div>
</section>
