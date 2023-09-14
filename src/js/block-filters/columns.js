// WordPress dependencies.
import { InspectorControls } from '@wordpress/block-editor';
import {
	ToggleControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControl as ToggleGroupControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
	PanelBody,
} from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import TokenList from '@wordpress/token-list';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { assign, merge } from 'lodash';

const withMultiBreakpoint = (BlockEdit) => (props) => {
	if (props.name !== 'core/columns') return <BlockEdit {...props} />;

	const { attributes, setAttributes } = props;
	const { className, breakpoint } = attributes;

	// Define our breakpoints.
	const breakpoints = ['sm', 'md', 'lg', 'xl'];

	// Define the classes used for setting stacking.
	const classes = {
		reversed: 'is-reversed-when-stacked',
		...breakpoints.reduce((acc, breakpoint) => {
			acc[breakpoint] = `is-stacked-on-${breakpoint}`;
			return acc;
		}, {}),
	};

	// Set state for the active breakpoint.
	const [activeBreakpoint, setActiveBreakpoint] = useState(breakpoint);

	// Define useful helper text for each breakpoint.
	const helpText = {
		sm: __('Mobile screens.'),
		md: __('Landscape mobiles and below.'),
		lg: __('Tablets in portrait mode and below.'),
		xl: __('Smaller laptops or tablets in landscape mode and below.'),
	};

	// Fetch the helper text for a breakpoint.
	const getHelpText = (key) => helpText[key];

	// Toggles a breakpoint class, and removes any other existing ones.
	const toggleBreakpointClass = (toggleClass, enable) => {
		const list = new TokenList(className);

		// Remove all classes except the one being toggled
		breakpoints.forEach((breakpoint) => {
			if (breakpoint !== toggleClass) {
				list.remove(classes[breakpoint]);
			}
		});

		if (enable) {
			list.add(toggleClass);
		}

		setAttributes({ className: list.value });
	};

	const onChangeBreakpoint = (newBreakpoint) => {
		setActiveBreakpoint(newBreakpoint);
		setAttributes({ breakpoint: newBreakpoint });
	};

	// Toggle the reversed class independently.
	const toggleReversedClass = (enable) => {
		const list = new TokenList(className);

		if (enable) {
			list.add(classes.reversed);
		} else {
			list.remove(classes.reversed);
		}

		setAttributes({ className: list.value });
	};

	// Remove all our custom classes.
	// Used when stack on mobile is turned off.
	const removeAllClasses = () => {
		const list = new TokenList(className);

		for (const size in classes) {
			list.remove(classes[size]);
		}

		setAttributes({ className: list.value });
	};

	// Check if the columns have a particular class.
	const hasClass = (className) => {
		return attributes.className?.includes(className);
	};

	// Set the initial columns state, along with clearing classes when stacking is disabled.
	useEffect(() => {
		if (!attributes.isStackedOnMobile) {
			removeAllClasses();
			setActiveBreakpoint(false);
			setAttributes({ breakpoint: '' });
		}
	}, [attributes.isStackedOnMobile]);

	return (
		<>
			<BlockEdit {...props} />
			<InspectorControls>
				{attributes.isStackedOnMobile && (
					<PanelBody>
						<ToggleGroupControl
							label={__('Screen sizes up to')}
							onChange={(value) => {
								onChangeBreakpoint(value);
								toggleBreakpointClass(classes[value], value);
							}}
							value={activeBreakpoint}
							isBlock
							help={getHelpText(activeBreakpoint)}
						>
							{breakpoints.map((breakpoint) => (
								<ToggleGroupControlOption
									key={breakpoint}
									value={breakpoint}
									label={__(breakpoint.toUpperCase())}
								/>
							))}
						</ToggleGroupControl>

						<ToggleControl
							label={__('Reversed when stacked')}
							help={__('Reverse columns when stacked.')}
							checked={hasClass(classes.reversed)}
							onChange={(val) => {
								toggleReversedClass(val);
							}}
						/>
					</PanelBody>
				)}
			</InspectorControls>
		</>
	);
};

/**
 * Add Size attribute to Button block
 *
 * @param  {Object} settings Original block settings
 * @param  {string} name     Block name
 * @return {Object}          Filtered block settings
 */
function addAttributes(settings, name) {
	if (name === 'core/columns') {
		return assign({}, settings, {
			attributes: merge(settings.attributes, {
				breakpoint: {
					type: 'string',
					default: '',
				},
				className: {
					type: 'string',
					default: '',
				},
			}),
		});
	}
	return settings;
}

addFilter('blocks.registerBlockType', 'pulsar/columns-block', addAttributes);
addFilter('editor.BlockEdit', 'pulsar/columns-block', withMultiBreakpoint);
