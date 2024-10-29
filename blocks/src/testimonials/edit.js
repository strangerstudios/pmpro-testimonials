/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InnerBlocks, useInnerBlocksProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, TextControl, FormTokenField } from "@wordpress/components";
import { useSelect } from '@wordpress/data';

const ALLOWED_BLOCKS = [ 'pmpro/testimonials-name', 'pmpro/testimonials-content' ];

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps({});
	const innerBlocksProps = useInnerBlocksProps( blockProps, {
		allowedBlocks: ALLOWED_BLOCKS,
		template: [
			[ 'pmpro/testimonials-name' ],
			[ 'pmpro/testimonials-content' ]
		] 
	});

	// Fetch categories and tags from the database
	const categories = useSelect( select => {
		return select( 'core' ).getEntityRecords( 'taxonomy', 'pmpro_testimonial_category', { per_page: -1 } );
	}, [] );

	const tags = useSelect( select => {
		return select( 'core' ).getEntityRecords( 'taxonomy', 'pmpro_testimonial_tag', { per_page: -1 } );
	}, [] );

	// Convert fetched categories and tags into options for FormTokenField
	const categoryOptions = categories ? categories.map( ( category ) => category.name ) : [];
	const tagOptions = tags ? tags.map( ( tag ) => tag.name ) : [];

	// Convert category/tag names to their IDs for saving
	const getSelectedIds = ( selectedNames, allTerms ) => {
		return selectedNames
			.map( name => {
				const term = allTerms.find( term => term.name === name );
				return term ? term.id : null;
			})
			.filter( Boolean ); // Filter out nulls (in case any selected name isn't found)
	};

	// Update number attribute
	const updateLimit = ( value ) => {
		setAttributes( { limit: parseInt( value, 10 ) || 0 } );
	};

	// Update selected categories and store their IDs
	const updateCategories = ( selectedCategories ) => {
		const selectedCategoryIds = getSelectedIds( selectedCategories, categories );
		setAttributes( { categories: selectedCategoryIds } );
	};

	// Update selected tags and store their IDs
	const updateTags = ( selectedTags ) => {
		const selectedTagIds = getSelectedIds( selectedTags, tags );
		setAttributes( { tags: selectedTagIds } );
	};

	// Convert IDs back to names for display in FormTokenField (for pre-selected values)
	const getNamesFromIds = ( ids, allTerms ) => {
		return ids
			.map( id => {
				const term = allTerms.find( term => term.id === id );
				return term ? term.name : null;
			})
			.filter( Boolean ); // Filter out nulls
	};

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'Block Settings', 'testimonials' ) }>
					<PanelRow>
						<TextControl
							label={ __( 'Number of Testimonials', 'testimonials' ) }
							type="number"
							value={ attributes.limit }
							onChange={ updateLimit }
						/>
					</PanelRow>

					<PanelRow>
						<FormTokenField
							label={ __( 'Select Categories', 'testimonials' ) }
							value={ getNamesFromIds( attributes.categories, categories ) }
							suggestions={ categoryOptions }
							onChange={ updateCategories }
						/>
					</PanelRow>

					<PanelRow>
						<FormTokenField
							label={ __( 'Select Tags', 'testimonials' ) }
							value={ getNamesFromIds( attributes.tags, tags ) }
							suggestions={ tagOptions }
							onChange={ updateTags }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<div { ...innerBlocksProps }></div>
		</div>
	);
}

/*
			<InspectorControls>
				<PanelBody title={ __( 'Block Settings', 'testimonials' ) }>
					<PanelRow>
						<TextControl
							label={ __( 'Number of Testimonials', 'testimonials' ) }
							type="number"
							value={ attributes.limit }
							onChange={ updateLimit }
						/>
					</PanelRow>

					<PanelRow>
						<FormTokenField
							label={ __( 'Select Categories', 'testimonials' ) }
							value={ getNamesFromIds( attributes.categories, categories ) }
							suggestions={ categoryOptions }
							onChange={ updateCategories }
						/>
					</PanelRow>

					<PanelRow>
						<FormTokenField
							label={ __( 'Select Tags', 'testimonials' ) }
							value={ getNamesFromIds( attributes.tags, tags ) }
							suggestions={ tagOptions }
							onChange={ updateTags }
						/>
					</PanelRow>
				</PanelBody>
			</InspectorControls>

			<InnerBlocks
				allowedBlocks={ ALLOWED_BLOCKS }
				template={ [
					[ 'pmpro/testimonials-name' ],
					[ 'pmpro/testimonials-content' ]
				] }
				templateLock={ false }  // Allow flexibility to reorder or remove blocks
			/>
*/