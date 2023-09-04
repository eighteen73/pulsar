import { useBlockProps } from '@wordpress/block-editor';
import { Disabled } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit({ attributes }) {
	const blockProps = useBlockProps();

	return (
		<Disabled>
			<div {...blockProps}>
				<ServerSideRender
					block="pulsar/template-part"
					attributes={attributes}
				/>
			</div>
		</Disabled>
	);
}
