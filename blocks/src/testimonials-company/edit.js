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

	// Get the post meta dynamically using useEntityProp
	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId );

	// Access specific metadata fields
	const company = meta ? meta._company : '';
	const companyUrl = meta ? meta._url : '';

	// Update the toggle control in InspectorControls
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Link Settings', 'pmpro-testimonials' ) }>
					<ToggleControl
						label={ __( 'Enable Link to Company URL', 'pmpro-testimonials' ) }
						checked={ linkEnabled }
						onChange={ ( value ) => setAttributes( { linkEnabled: value } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ linkEnabled && companyUrl ? (
					<a href={ companyUrl } target="_blank" rel="noopener noreferrer">
						{ company || __( 'No company provided', 'pmpro-testimonials' ) }
					</a>
				) : (
					company || __( 'No company provided', 'pmpro-testimonials' )
				) }
			</div>
		</>
	);
}
