import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
	const blockProps = useBlockProps();
	const { openMultiple, startOpen } = attributes;

	const dummy_tabs = [
		{
			title: __('Description', 'pulsar-blocks'),
			content: __('Description', 'pulsar-blocks'),
		},
		{
			title: __('Additional Information', 'pulsar-blocks'),
			content: __('Additional Information', 'pulsar-blocks'),
		},
		{
			title: __('Reviews', 'pulsar-blocks'),
			content: __('Reviews', 'pulsar-blocks'),
		},
	];

	return (
		<div {...blockProps}>
			<InspectorControls group="settings">
				<PanelBody title={__('Settings', 'pulsar-blocks')}>
					<ToggleControl
						label={__(
							'Multiple items can be opened',
							'pulsar-blocks'
						)}
						checked={openMultiple}
						onChange={(value) =>
							setAttributes({ openMultiple: value })
						}
					/>

					<ToggleControl
						label={__(
							'First item open by default',
							'pulsar-blocks'
						)}
						checked={startOpen}
						onChange={(value) =>
							setAttributes({ startOpen: value })
						}
					/>
				</PanelBody>
			</InspectorControls>

			{dummy_tabs.map((tab, index) => (
				<details className="wp-block-pulsar-woocommerce-product-details-accordion__details">
					<summary className="wp-block-pulsar-woocommerce-product-details-accordion__summary">
						{tab.title}
					</summary>
					<div className="wp-block-pulsar-woocommerce-product-details-accordion__content">
						{tab.content}
					</div>
				</details>
			))}
		</div>
	);
}
