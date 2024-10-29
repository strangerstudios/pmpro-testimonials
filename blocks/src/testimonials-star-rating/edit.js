import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ColorPicker } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useEffect } from '@wordpress/element';

export default function Edit( { attributes, setAttributes, context } ) {
	const blockProps = useBlockProps();
	const { postId, postType } = context;
	const { starColor } = attributes;

	// Fetch _rating metadata
	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId );
	const rating = meta ? meta._rating : 0;

	// Set the initial color only if `starColor` is undefined
	useEffect(() => {
		if ( starColor === undefined ) {
			setAttributes({ starColor: pmproTestimonialsSettings.defaultStarColor });
		}
	}, []);

	// Generate star SVGs based on the rating value
	const stars = Array.from( { length: 5 }, ( _, index ) => (
		<svg
			key={ index }
			xmlns="http://www.w3.org/2000/svg"
			viewBox="0 0 24 24"
			width="24"
			height="24"
			style={{
				fill: index < rating ? starColor : 'none',
				stroke: index < rating ? starColor : '#CCC'
			}}
			>
			<path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
		</svg>
	) );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Star Color', 'pmpro-testimonials' ) } initialOpen={ true }>
					<ColorPicker
						color={ starColor }
						onChangeComplete={ ( color ) => setAttributes( { starColor: color.hex } ) }
						disableAlpha
					/>
				</PanelBody>
			</InspectorControls>
			{ stars }
		</div>
	);
}
