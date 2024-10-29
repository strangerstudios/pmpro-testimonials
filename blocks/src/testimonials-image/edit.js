import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useEffect } from '@wordpress/element';

export default function Edit( { attributes, setAttributes, context } ) {
	const blockProps = useBlockProps();
	const { postId, postType } = context;

	// Retrieve the featured image ID for this post
	const [ featuredImageId ] = useEntityProp( 'postType', postType, 'featured_media', postId );
	// Retrieve the email meta for this post
	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId );
	const email = meta ? meta._email : '';

	// Determine the image URL
	let imageUrl = '';

	if ( featuredImageId ) {
		// Fetch and use the featured image URL if available
		imageUrl = wp.data.select( 'core' ).getMedia( featuredImageId )?.source_url;
	} else if ( email ) {
		// Fallback to avatar URL based on email
		imageUrl = `https://secure.gravatar.com/avatar/${ md5( email.trim().toLowerCase() ) }?s=256`;
	} else {
		// Fallback to a default user image if no featured image or avatar exists
		imageUrl = `${ pmproTestimonialsSettings.defaultUserImage }`;
	}

	// Editor controls to modify featured image options
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Image Settings', 'pmpro-testimonials' ) }>
					<TextControl
						label={ __( 'Custom Overlay Color', 'pmpro-testimonials' ) }
						value={ attributes.customOverlayColor || '' }
						onChange={ ( value ) => setAttributes( { customOverlayColor: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<img src={ imageUrl } alt={ __( 'Testimonial Image', 'pmpro-testimonials' ) } />
			</div>
		</>
	);
}
