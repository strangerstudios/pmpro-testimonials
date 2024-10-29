import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

export default function Edit( { context } ) {
	const blockProps = useBlockProps();
	const { postId, postType } = context;

	// Fetch the full post data using useSelect
	const content = useSelect( ( select ) => {
		const post = select( 'core' ).getEntityRecord( 'postType', postType, postId );
		return post ? post.content?.rendered : null;
	}, [ postId, postType ] );

	return (
		<div { ...blockProps }>
			{ content ? (
				<div dangerouslySetInnerHTML={ { __html: content } } />
			) : (
				__( 'Loading...', 'pmpro-testimonials' )
			) }
		</div>
	);
}
