// File: index.js (Testimonials-Query Block)

wp.blocks.registerBlockVariation( 'core/query', {
	name: 'pmpro/testimonials-query',
	title: 'PMPro Testimonials',
	description: 'Query for PMPro Testimonials only.',
	isActive: ( { query } ) => {
		return query?.postType === 'pmpro_testimonial';
	},
	icon: 'testimonial',
	category: 'pmpro-testimonials',
	attributes: {
		query: {
			postType: 'pmpro_testimonial',
			taxQuery: [
				{
					taxonomy: 'pmpro_testimonial_category',
					field: 'slug',
					terms: []
				},
				{
					taxonomy: 'pmpro_testimonial_tag',
					field: 'slug',
					terms: []
				}
			]
		}
	},
	innerBlocks: [
		[
			'core/post-template',
			{},
			[
				[ 'pmpro/testimonials-star-rating' ],
				[ 'pmpro/testimonials-content' ],
				[ 'pmpro/testimonials-name' ],
			]
		]
	],
	allowedControls: [ 'inherit', 'order', 'taxQuery' ],
	scope: [ 'inserter' ]
} );
