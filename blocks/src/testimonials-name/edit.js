import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useEffect } from '@wordpress/element';

export default function Edit( { attributes, setAttributes, context } ) {
	const blockProps = useBlockProps();
	const { linkEnabled } = attributes;

	// Access post ID and type from context
	const { postId, postType } = context;

	// Get the post title dynamically using useEntityProp
	const [ title ] = useEntityProp( 'postType', postType, 'title', postId );
	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId );

	const url = meta ? meta._url : '';

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Link Settings', 'pmpro-testimonials' ) }>
					<ToggleControl
						label={ __( 'Enable Link to URL', 'pmpro-testimonials' ) }
						checked={ linkEnabled }
						onChange={ ( value ) => setAttributes( { linkEnabled: value } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ linkEnabled && url ? (
					<a href={ url } target="_blank" rel="noopener noreferrer">
						{ title || __( 'No name provided', 'pmpro-testimonials' ) }
					</a>
				) : (
					title || __( 'No name provided', 'pmpro-testimonials' )
				) }
			</div>
		</>
	);
}
