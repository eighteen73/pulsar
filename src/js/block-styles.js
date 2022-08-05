const { register, unregister } = require( '../../config/block-styles.json' );

import domReady from '@wordpress/dom-ready';
import { registerBlockStyle, unregisterBlockStyle } from '@wordpress/blocks';

// Required so we have the correct dependencies for the script.
// unregisterBlockStyle required wp-edit-post as a dependency,
// so this will include this in our depdendencies.
import editPost from '@wordpress/edit-post';

domReady( () => {

	/**
	 * Register block styles.
	 *
	 * Expects the following json format under the key "register":
	 * {
	 * 		"core/button": {
	 * 			"huge": "Huge"
	 * 		}
	 * }
	 */
	Object.entries( register ).forEach( ( [ blockName, styles ] ) => {
		Object.entries( styles ).forEach( ( [ name, label ] ) => {
			registerBlockStyle(
				blockName,
				{
					name: name,
					label: label,
				}
			);
		} );
	} );

	/**
	 * Unregister block styles.
	 *
	 * Expects the following json format under the key "unregister":
	 * {
	 * 		"core/button": ["outline"]
	 * }
	 */
	Object.entries( unregister ).forEach( ( [ blockName, styles ] ) => {
		styles.forEach( ( style ) => {
			unregisterBlockStyle( blockName, style );
		} );
	} );
} );
