// WordPress dependencies.
import { InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, PanelBody } from '@wordpress/components';
import { addFilter } from '@wordpress/hooks';
import TokenList from '@wordpress/token-list';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

const withAdditionalResponsiveControls = (BlockEdit) => (props) => {
	const { attributes, setAttributes } = props;
	const { className, overlayMenu } = attributes;

	const isResponsive = overlayMenu === 'mobile' || overlayMenu === 'always';

	const classes = {
		back: 'has-back-button',
		all: 'has-view-all',
		title: 'has-parent-title',
	};

	const toggleClass = (enable, ClassToToggle) => {
		const list = new TokenList(className);

		if (enable) {
			list.add(ClassToToggle);
		} else {
			list.remove(ClassToToggle);
		}

		setAttributes({ className: list.value });
	};

	const removeAllClasses = (classes) => {
		const list = new TokenList(className);

		for (const item in classes) {
			list.remove(classes[item]);
		}

		setAttributes({ className: list.value });
	};

	const hasClass = (name) => {
		return attributes.className?.includes(name);
	};

	// Set the initial classes.
	useEffect(() => {
		if (isResponsive) {
			const activeClass = Object.keys(classes).find((item) =>
				hasClass(classes[item])
			);

			if (activeClass) {
				toggleClass(true, classes[activeClass]);
			} else {
				removeAllClasses();
			}
		} else {
			removeAllClasses();
		}
	}, [overlayMenu]);

	return 'core/navigation' === props.name ? (
		<>
			<BlockEdit {...props} />
			<InspectorControls>
				{isResponsive && (
					<PanelBody>
						<ToggleControl
							label={__('Show back button', 'pulsar')}
							checked={hasClass(classes.back)}
							onChange={(val) => {
								toggleClass(val, classes.back);
							}}
						/>

						{hasClass(classes.back) && (
							<ToggleControl
								label={__('Show view all', 'pulsar')}
								checked={hasClass(classes.all)}
								onChange={(val) => {
									toggleClass(val, classes.all);
								}}
							/>
						)}

						{hasClass(classes.back) && (
							<ToggleControl
								label={__('Show parent title', 'pulsar')}
								checked={hasClass(classes.title)}
								onChange={(val) => {
									toggleClass(val, classes.title);
								}}
							/>
						)}
					</PanelBody>
				)}
			</InspectorControls>
		</>
	) : (
		<BlockEdit {...props} />
	);
};
addFilter(
	'editor.BlockEdit',
	'pulsar/navigation-block',
	withAdditionalResponsiveControls
);
