import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';

export default function Edit( { context } ) {
	const blockProps = useBlockProps();

	// Access post ID and type from context
	const { postId, postType } = context;

	// Get the post title dynamically using useEntityProp
	const [ title ] = useEntityProp( 'postType', postType, 'title', postId );

	return (
		<div { ...blockProps }>
			{ title || __( 'Loading...', 'pmpro-testimonials' ) }
		</div>
	);
}
