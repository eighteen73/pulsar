import { subscribe } from '@wordpress/data';
import themeJson from '../../../theme.json';

subscribe(() => {
	if (themeJson.settings.layout.contentSize) {
		document.body.style.setProperty(
			'--pulsar-content-width',
			themeJson.settings.layout.contentSize
		);
	}

	const titleVisibility = document.querySelector('.title-visibility');
	const titleBlock = document.querySelector(
		'.edit-post-visual-editor__post-title-wrapper'
	);

	if (null === titleVisibility && null !== titleBlock) {
		let titleVisibilityTrigger = '';
		if (
			false ===
			wp.data.select('core/editor').getEditedPostAttribute('meta')[
				'display_post_title'
			]
		) {
			titleVisibilityTrigger =
				'<span class="dashicons dashicons-hidden title-visibility" data-tooltip="Enable Title"></span>';
			titleBlock.classList.toggle('invisible');
		} else {
			titleVisibilityTrigger =
				'<span class="dashicons dashicons-visibility title-visibility" data-tooltip="Disable Title"></span>';
		}

		titleBlock.insertAdjacentHTML('beforeend', titleVisibilityTrigger);
		document
			.querySelector('.title-visibility')
			.addEventListener('click', function () {
				titleBlock.classList.toggle('invisible');

				if (this.classList.contains('dashicons-hidden')) {
					this.classList.add('dashicons-visibility');
					this.classList.remove('dashicons-hidden');
					this.dataset.tooltip = 'Disable Title';
					wp.data.dispatch('core/editor').editPost({
						meta: {
							display_post_title: true,
						},
					});
				} else {
					this.classList.add('dashicons-hidden');
					this.classList.remove('dashicons-visibility');
					this.dataset.tooltip = 'Enable Title';
					wp.data.dispatch('core/editor').editPost({
						meta: {
							display_post_title: false,
						},
					});
				}
			});
	}
});
