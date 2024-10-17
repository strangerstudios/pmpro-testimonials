jQuery(document).ready(function($) {
	const stars = $('.pmpro_star_rating .pmpro_star');
	const ratingInput = $('input[name="rating"]');
	let currentRating = parseInt(ratingInput.val(), 10);

	// Apply initial rating
	stars.each(function() {
		const value = $(this).data('value');
		if (value <= currentRating) {
			$(this).addClass('filled');
		}
	});

	// Handle hover events
	stars.on('mouseover', function() {
		resetStars();
		const hoverValue = $(this).data('value');
		fillStars(hoverValue);
	});

	// Handle click events
	stars.on('click', function() {
		currentRating = $(this).data('value');
		ratingInput.val(currentRating);
	});

	// Reset stars on mouse out
	stars.on('mouseout', function() {
		resetStars();
		fillStars(currentRating); // Re-apply rating after hover
	});

	// Function to reset stars
	function resetStars() {
		stars.removeClass('filled');
	}

	// Function to fill stars up to the specified value
	function fillStars(value) {
		stars.each(function() {
			if ($(this).data('value') <= value) {
				$(this).addClass('filled');
			}
		});
	}
});
