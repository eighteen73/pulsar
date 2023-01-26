import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControl as ToggleGroupControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { assign, merge } from 'lodash';
import { Fragment } from '@wordpress/element';

/*
    This modifies the block settings for the core/columns block
    We enable support for className and define two additional state
    attributes that will be used in the InspectorControls
*/
function modifyColumnsBlockSettings(settings, name) {
	if (name !== 'core/columns') {
		return settings;
	}
	return assign({}, settings, {
		supports: merge(settings.supports, {
			className: true,
		}),
		attributes: merge(settings.attributes, {
			stackedBreakpoint: { type: 'string', default: 'md' },
			isReversedWhenStacked: { type: 'boolean', default: false },
		}),
	});
}

/*
    This adds a slider to the Inspector Controls for the core/columns block
    and tracks the state / updates the block when changed
*/
const addInspectorControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const {
			attributes: { stackedBreakpoint, isReversedWhenStacked },
			setAttributes,
			name,
		} = props;

		if (name !== 'core/columns') {
			return <BlockEdit {...props} />;
		}

		return (
			<Fragment>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody>
						<ToggleGroupControl
							label={__('Stack on', 'pulsar')}
							value={stackedBreakpoint}
							onChange={(value) =>
								setAttributes({
									stackedBreakpoint: value,
								})
							}
							isBlock
							help={__(
								'Set the breakpoint size where you wish to stack the columns.',
								'pulsar'
							)}
						>
							<ToggleGroupControlOption
								value="sm"
								label={__('Small', 'pulsar')}
							/>
							<ToggleGroupControlOption
								value="md"
								label={__('Medium', 'pulsar')}
							/>
							<ToggleGroupControlOption
								value="lg"
								label={__('Large', 'pulsar')}
							/>
						</ToggleGroupControl>

						<ToggleControl
							label={__('Reverse when stacked', 'pulsar')}
							help={__(
								'Allows column order to be reversed when stacked. Useful for example if you have an image in the right column, but want it to be on top when stacked.'
							)}
							checked={isReversedWhenStacked}
							onChange={() =>
								setAttributes({
									isReversedWhenStacked:
										!isReversedWhenStacked,
								})
							}
						/>
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withInspectorControl');

/**
 * Adds classes to the core/columns block in the editor.
 */
const addEditorClasses = createHigherOrderComponent((BlockListBlock) => {
	return (props) => {
		const {
			attributes: { stackedBreakpoint, reverseOnStacked },
			name,
		} = props;

		if (name !== 'core/columns') {
			return <BlockListBlock {...props} />;
		}

		const classes = [];

		if (stackedBreakpoint) {
			classes.push(`is-stacked-on-${stackedBreakpoint}`);
		}

		if (reverseOnStacked) {
			classes.push('is-reversed-on-stacked');
		}

		return <BlockListBlock {...props} className={classes.join(' ')} />;
	};
}, 'withClientIdClassName');

/**
 * Add classes to the frontend.
 */
function addFrontendClasses(props, block, attributes) {
	if (block.name !== 'core/columns') {
		return props;
	}

	const classes = [props.className];

	const { stackedBreakpoint, isReversedWhenStacked } = attributes;

	classes.push(`is-stacked-on-${stackedBreakpoint}`);

	if (isReversedWhenStacked) {
		classes.push('is-reversed-when-stacked');
	}

	return assign({}, props, {
		className: classes.join(' '),
	});
}

addFilter(
	'blocks.registerBlockType',
	'pulsar/columns-block/block-settings',
	modifyColumnsBlockSettings
);

addFilter(
	'editor.BlockEdit',
	'pulsar/columns-block/add-inspector-controls',
	addInspectorControls
);

addFilter(
	'editor.BlockListBlock',
	'pulsar/columns-block/add-editor-classes',
	addEditorClasses
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'pulsar/columns-block/add-frontend-classes',
	addFrontendClasses
);
