import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { Disabled, PanelBody, SelectControl } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes: { slug }, setAttributes }) {
	const blockProps = useBlockProps();
	const [templateParts, setTemplateParts] = useState([]);

	useEffect(() => {
		apiFetch({ path: '/pulsar/v1/template-parts' })
			.then((response) => {
				setTemplateParts(response);
			})
			.catch((error) => {
				console.error(error);
			});
	}, []);

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody title="Settings">
					<SelectControl
						label={__('Template Part', 'pulsar')}
						value={slug}
						options={[
							{
								label: __(
									'Select a theme template part',
									'pulsar'
								),
								value: '',
							},
							...templateParts
								.sort((a, b) => a.title.localeCompare(b.title))
								.map((part) => ({
									label: part.title,
									value: part.slug,
								})),
						]}
						onChange={(value) => setAttributes({ slug: value })}
					/>
				</PanelBody>
			</InspectorControls>
			<Disabled>
				<ServerSideRender
					block="pulsar/template-part"
					attributes={{ slug }}
				/>
			</Disabled>
		</div>
	);
}
