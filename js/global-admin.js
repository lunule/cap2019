/* ------------------------------------------------------------------------------------------------
# Debounce
------------------------------------------------------------------------------------------------ */

/**
 * Debouncing function by John Hann
 * @see http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
 */

(function($,sr){

	var debounce = function (func, threshold, execAsap) {
  	
  		var timeout;

	 	return function debounced () {

			var obj = this, args = arguments;
			
			function delayed () {
				if (!execAsap)
					func.apply(obj, args);
					timeout = null;
			};

			if (timeout)
				clearTimeout(timeout);
			else if (execAsap)
				func.apply(obj, args);

			timeout = setTimeout(delayed, threshold || 100);
		
		};

	}

	// smartresize 
	jQuery.fn[sr] = function(fn) { 

		return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); 
	
	};
 	
 })(jQuery,'smartresize');

jQuery(document).ready( function($) {

	/* ============================================================================================
	# Member Directory - Disable ACF fields filled based on custom taxonomy values
	============================================================================================ */
	$(window).on('load', function() {

		$('.post-type-nwmember #acf-field_5d5727d45dd97, .post-type-nwmember #acf-field_5d57296927cd2, .post-type-nwmember #acf-field_5d5729f015bd6').each(function() {
			$(this).attr('disabled', 'disabled');
		});

	})

	/* ============================================================================================
	# Disable Sumo badge
	============================================================================================ */	
	$(window).on('load', function() {

		setTimeout( function() { 

		console.log( $('.sumo-control').length > 0 );

			if ( $('.sumo-control').length > 0 ) {

				const $sumoGrandpa = $('.sumo-control').closest('div').closest('div');
				console.log( $sumoGrandpa );
		
				$sumoGrandpa.prev('div').prev('iframe').remove();
				$sumoGrandpa.prev('div').remove();
				$sumoGrandpa.remove();

			}


		}, 3000 );	

	})			

});