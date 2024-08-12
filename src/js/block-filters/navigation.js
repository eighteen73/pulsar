// WordPress dependencies.
import { InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, PanelBody } from '@wordpress/components';
import TokenList from '@wordpress/token-list';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

// Third party dependencies.
import { registerBlockExtension } from '@10up/block-components';

/**
 * additionalAttributes object
 * @type {object}
 */
const additionalAttributes = {
	hasSubmenuBack: {
		type: 'boolean',
		default: false,
	},
	hasSubmenuLabel: {
		type: 'boolean',
		default: false,
	},
	hasSubmenuAll: {
		type: 'boolean',
		default: false,
	},
};

/**
 * classes object
 * @type {object}
 */
const classes = {
	slide: 'is-submenu-style-slide',
	accordion: 'is-submenu-style-accordion',
	back: 'has-submenu-back',
	label: 'has-submenu-label',
	all: 'has-submenu-all',
};

/**
 * SubmenuOptionsEdit
 * @param {object} props
 * @returns {object} JSX
 */
const SubmenuOptionsEdit = (props) => {
	const { attributes, setAttributes } = props;
	const { hasSubmenuBack, hasSubmenuLabel, hasSubmenuAll, overlayMenu } =
		attributes;

	const isResponsive = overlayMenu === 'mobile' || overlayMenu === 'always';

	// Set disable submenu options if menu isnt responsive.
	useEffect(() => {
		if (!isResponsive) {
			setAttributes({
				hasSubmenuBack: false,
				hasSubmenuLabel: false,
				hasSubmenuAll: false,
			});
		}
	}, [overlayMenu]);

	return (
		<InspectorControls>
			{isResponsive && (
				<PanelBody title="Submenu" initialOpen={true}>
					<ToggleControl
						label={__('Show back navigation', 'pulsar')}
						checked={hasSubmenuBack}
						onChange={(val) => {
							setAttributes({ hasSubmenuBack: val });
						}}
					/>

					{hasSubmenuBack && (
						<ToggleControl
							label={__('Show label', 'pulsar')}
							checked={hasSubmenuLabel}
							onChange={(val) => {
								setAttributes({ hasSubmenuLabel: val });
							}}
						/>
					)}

					{hasSubmenuBack && (
						<ToggleControl
							label={__('Show view all', 'pulsar')}
							checked={hasSubmenuAll}
							onChange={(val) => {
								setAttributes({ hasSubmenuAll: val });
							}}
						/>
					)}
				</PanelBody>
			)}
		</InspectorControls>
	);
};

/**
 * generateClassNames
 *
 * a function to generate the new className string that should get added to
 * the wrapping element of the block.
 *
 * @param {object} attributes block attributes
 * @returns {string}
 */
function generateClassNames(attributes) {
	const { overlayMenu, hasSubmenuBack, hasSubmenuLabel, hasSubmenuAll } =
		attributes;

	const isResponsive = overlayMenu === 'mobile' || overlayMenu === 'always';

	const classesList = new TokenList();

	if (isResponsive) {
		if (hasSubmenuBack) {
			classesList.add(classes.slide);
			classesList.add(classes.back);
		} else {
			classesList.add(classes.accordion);
		}

		if (hasSubmenuLabel) {
			classesList.add(classes.label);
		}
		if (hasSubmenuAll) {
			classesList.add(classes.all);
		}
	}

	return classesList.toString();
}

/**
 * add the block extension
 */
registerBlockExtension('core/navigation', {
	extensionName: 'pulsar/submenu-options',
	attributes: additionalAttributes,
	classNameGenerator: generateClassNames,
	inlineStyleGenerator: () => null,
	Edit: SubmenuOptionsEdit,
});
