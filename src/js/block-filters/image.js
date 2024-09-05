// WordPress dependencies.
import { InspectorControls } from '@wordpress/block-editor';
import {
	FocalPointPicker,
	PanelBody,
	__experimentalToolsPanel as ToolsPanel,
	__experimentalToolsPanelItem as ToolsPanelItem,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';

// Third party dependencies.
import { registerBlockExtension } from '@10up/block-components';

/**
 * additionalAttributes object
 * @type {object}
 */
const additionalAttributes = {
	focalPoint: {
		type: 'object',
	},
};

/**
 * ImageEdit
 * @param {object} props
 * @returns {object} JSX
 */
const ImageFocalPointEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { url, aspectRatio, focalPoint } = attributes;

	const onChangeFocalPoint = (value) => {
		setAttributes({ focalPoint: value });
	};

	return (
		<>
			{aspectRatio && (
				<InspectorControls>
					<PanelBody>
						<FocalPointPicker
							label={__('Focal Point', 'pulsar')}
							url={url}
							value={focalPoint}
							onChange={onChangeFocalPoint}
						/>
					</PanelBody>
				</InspectorControls>
			)}
		</>
	);
};

const generateInlineStyles = (attributes) => {
	const { focalPoint } = attributes;

	if (!focalPoint) {
		return null;
	}

	const { x, y } = focalPoint;

	return {
		objectPosition: `${x * 100}% ${y * 100}%`,
	};
};

/**
 * add the block extension
 */
registerBlockExtension('core/image', {
	extensionName: 'pulsar/image-focal-point',
	attributes: additionalAttributes,
	classNameGenerator: () => null,
	inlineStyleGenerator: generateInlineStyles,
	Edit: ImageFocalPointEdit,
});
