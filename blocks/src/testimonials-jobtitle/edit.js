import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityProp } from '@wordpress/core-data';

export default function Edit( { context } ) {
	const blockProps = useBlockProps();

	// Access post ID and type from context
	const { postId, postType } = context;

	// Get the post meta dynamically using useEntityProp
	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId );

	// Access the specific metadata field
	const jobTitle = meta ? meta._job_title : '';

	return (
		<div { ...blockProps }>
			{ jobTitle || __( 'No job title provided', 'pmpro-testimonials' ) }
		</div>
	);
}
