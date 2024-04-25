/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { createInterpolateElement } from '@wordpress/element';
import {
	ComboboxControl,
	PanelBody,
	Notice,
	TextControl,
	TextareaControl,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import './edit.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {Element} Element to render.
 */
export default function Edit({ attributes, setAttributes }) {
	const { label, menuId, title, description } = attributes;

	// Get the Url for the template part screen in the Site Editor.
	const siteUrl = useSelect((select) => select('core').getSite().url);
	const menuTemplateUrl = siteUrl
		? siteUrl +
		  '/wp-admin/site-editor.php?path=%2Fpatterns&categoryType=wp_template_part&categoryId=megamenu'
		: '';

	// Fetch all template parts.
	const { hasResolved, records } = useEntityRecords(
		'postType',
		'wp_template_part',
		{
			per_page: -1,
		}
	);

	let menuOptions = [];

	// Filter the template parts for those in the 'menu' area.
	if (hasResolved) {
		menuOptions = records
			.filter((item) => item.area === 'megamenu')
			.map((item) => ({
				label: item.title.rendered,
				value: item.id,
			}));
	}

	const hasMenus = menuOptions.length > 0;
	const selectedMenuAndExists = menuId
		? menuOptions.some((option) => option.value === menuId)
		: true;

	// Notice for when no menus have been created.
	const noMenusNotice = (
		<Notice status="warning" isDismissible={false}>
			{createInterpolateElement(
				__(
					'No megamenu template parts could be found. Create a new one in the <a>Site Editor</a>.',
					'pulsar'
				),
				{
					a: (
						<a // eslint-disable-line
							href={menuTemplateUrl}
							target="_blank"
							rel="noreferrer"
						/>
					),
				}
			)}
		</Notice>
	);

	// Notice for when the selected menu template part no longer exists.
	const menuDoesntExistNotice = (
		<Notice status="warning" isDismissible={false}>
			{__(
				'The selected megamenu template part no longer exists. Choose another.',
				'pulsar'
			)}
		</Notice>
	);

	// Modify block props.
	const blockProps = useBlockProps({
		className:
			'wp-block-navigation-item wp-block-pulsar-navigation-megamenu__toggle',
	});

	return (
		<>
			<InspectorControls group="settings">
				<PanelBody
					className="pulsar-megamenu__settings-panel"
					title={__('Settings', 'pulsar')}
					initialOpen={true}
				>
					<TextControl
						label={__('Label', 'pulsar')}
						type="text"
						value={label}
						onChange={(value) => setAttributes({ label: value })}
						autoComplete="off"
					/>
					<ComboboxControl
						label={__('Template part', 'pulsar')}
						value={menuId}
						options={menuOptions}
						onChange={(value) => setAttributes({ menuId: value })}
						help={
							hasMenus &&
							createInterpolateElement(
								__(
									'Create and modify megamenu template parts in the <a>Site Editor</a>.',
									'pulsar'
								),
								{
									a: (
										<a // eslint-disable-line
											href={menuTemplateUrl}
											target="_blank"
											rel="noreferrer"
										/>
									),
								}
							)
						}
					/>
					{!hasMenus && noMenusNotice}
					{hasMenus &&
						!selectedMenuAndExists &&
						menuDoesntExistNotice}
					<TextareaControl
						className="settings-panel__description"
						label={__('Description', 'pulsar')}
						type="text"
						value={description || ''}
						onChange={(descriptionValue) => {
							setAttributes({ description: descriptionValue });
						}}
						help={__(
							'The description will be displayed in the menu if the current theme supports it.',
							'pulsar'
						)}
						autoComplete="off"
					/>
					<TextControl
						label={__('Title', 'pulsar')}
						type="text"
						value={title || ''}
						onChange={(titleValue) => {
							setAttributes({ title: titleValue });
						}}
						help={__(
							'Additional information to help clarify the purpose of the link.',
							'pulsar'
						)}
						autoComplete="off"
					/>
				</PanelBody>
			</InspectorControls>
			<div {...blockProps}>
				<button className="wp-block-navigation-item__content wp-block-pulsar-navigation-megamenu__toggle">
					<RichText
						identifier="label"
						className="wp-block-navigation-item__label"
						value={label}
						onChange={(labelValue) =>
							setAttributes({
								label: labelValue,
							})
						}
						aria-label={__('Mega menu link text', 'pulsar')}
						placeholder={__('Add label…', 'pulsar')}
						allowedFormats={[
							'core/bold',
							'core/italic',
							'core/image',
							'core/strikethrough',
						]}
					/>
					<span className="wp-block-navigation__submenu-icon wp-block-pulsar-navigation-megamenu__icon">
						<svg
							xmlns="http://www.w3.org/2000/svg"
							width="12"
							height="12"
							viewBox="0 0 12 12"
							fill="none"
							aria-hidden="true"
							focusable="false"
						>
							<path
								d="M1.50002 4L6.00002 8L10.5 4"
								strokeWidth="1.5"
							></path>
						</svg>
					</span>
					{description && (
						<span className="wp-block-navigation-item__description">
							{description}
						</span>
					)}
				</button>
			</div>
		</>
	);
}
