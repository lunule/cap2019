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
	# Helper - add .loaded class to body on window load IF logged in
	============================================================================================ */
	$(window).on('load', function() {

		$('body').addClass('loaded');

	})

	// Remove empty paragraphs and paragraphs with &nbsp content
	$('p').each(function() {
	    var $this = $(this);
	    if( $this.is(':empty') || $this.html().match(/^\s*&nbsp;\s*$/) )
	        $this.remove();
	});	

	/* ============================================================================================
	# Forms
	============================================================================================ */

	/* $('.ginput_container select, .dthank-main .gform_wrapper select').change(function(){
		$(this).attr('style', 'color: #1450ff!important;');
	}) */

	function cap_form_styles() {

		/* General
		---------------------------------------------------------------------------------------- */

		// Selectize
        $( 'select' ).each( function () {

            var $select = $( this ).selectize({
				plugins: ['remove_button']
            });

            // CAP Network page search form
            // ----------------------------
            // Reset selected when form Reset button is clicked
			$('.search-filter-reset').on('click', function() {

                var selectize = $select[0].selectize;
                selectize.clear(true);

			})

        } );		

		$("input[type=file]").nicefileinput();		

		/* Member Directory
		---------------------------------------------------------------------------------------- */

        $(window).on('load', function() {

        	$('.posts-table-select-filters select').selectize({
				plugins: ['remove_button']
            });

            $('.page-template-page-nw .cap-page__hentry input[type="select-one"], .page-template-page-nw .cap-page__hentry input[type="select-multiple"]').each(function() {
            	$(this).removeAttr('placeholder');
            })

            $('th.col-net_press').html( 'Press<br />Accessible' );

        })		

	}

	cap_form_styles();

	/* ============================================================================================
	# Gravity Forms
	============================================================================================ */

	// Bind stuff to successful Gravity Forms form submission
	$(document).on('gform_confirmation_loaded', function(event, formId){
	   
	   	// Top subscribe form successful submission action 
		if ( 1 == formId ) {
	
			setTimeout( function(){ 
				$('.wrap--top-subscribe').fadeOut();
			}, 2000);
	
		}

	   	// In-post subscribe form successful submission action 
		if ( 4 == formId ) {
	
			setTimeout( function(){ 
				$('.wrap--in-post').fadeOut();
			}, 2000);
	
		}		

	});

	// Reinit jQuery form style plugins on form validation
	$(document).bind('gform_post_render', function(){
		cap_form_styles();
	});

	/* ============================================================================================
	# Top Subscribe Form
	============================================================================================ */

	/**
	 * Remove the form for 30 days is user is clicking the close button
	 * @see https://gist.github.com/shaik2many/039a8efe13dcafb4a3ffc4e5fb1dad97
	 */
	
	const setupTime = localStorage.getItem('setupTime');

	// Testing...
	// localStorage.removeItem('setupTime');
	// alert( setupTime );

	if ( setupTime == null ) {

		$('.wrap--top-subscribe').addClass('unclosed');

	} else {

	    if( now - setupTime > hours*60*60*1000 ) { 	 
	        localStorage.removeItem('setupTime');
	    }

	}		

	$('.top-subscribe > a').on('click', function(e) {

		const hours = 720, // 30 days = 30*24 hours = 720 hours
		  	  now = new Date().getTime();

		localStorage.setItem('setupTime', now);
		$('.wrap--top-subscribe').fadeOut(200);		
	
	})

	/* ============================================================================================
	# Header Search
	============================================================================================ */

	$('.main-navigation .wrap--search a').on('click', function(e) {

		e.preventDefault();

		const $this = $(this),
			  $sform = $this.next('.searchform');

		$sform.fadeToggle('200');

	})

	$(document).mouseup(function (e) {

		const $sform 	= $('.main-navigation .searchform'),
			  $zoomer 	= $('.main-navigation .wrap--search a'); 

		if ( $sform.is(':visible') ) {

			if ( !$sform.is(e.target) 					// if the target of the click isn't 
														// the $sform...
			  	 && $sform.has(e.target).length === 0 	// ... nor a descendant of the $sform
				 && !$zoomer.is(e.target) 
				 && $zoomer.has(e.target).length === 0
			   )
			{
			$('.main-navigation .wrap--search a').trigger('click');
			}

		}

	});	

	/* ============================================================================================
	# Hamburger
	============================================================================================ */

    $('.hamburger').on('click', function() {
    	$(this).toggleClass('is-active');
    })

    $('.hamburger:not(.is-active)').on('click', function() {
    	$('.mobile-navigation').fadeToggle();
    })

	function hamburger_visibility() {

		const window_width = window.innerWidth;

		if ( window_width < 1024 ) {

		    if ( $('.hamburger').hasClass('is-active') ) $('.mobile-navigation').show();

		} else {		

			if ( $('.mobile-navigation').is(':visible') ) $('.mobile-navigation').hide();
		
		}

	}

	hamburger_visibility();
	// Use Paul Irish' debounce function instead of the window load event
	$(window).smartresize(function(){ hamburger_visibility(); });    

	/* ============================================================================================
	# Slick sliders
	============================================================================================ */

	/* Home - CAP Expert Network Member Slider
	------------------------------------------ */

	$('.home .memslides').slick({
		arrows: true,
		dots: false,
		autoplay: false,
		infinite: true,
		speed: 1000,
		cssEase: 'ease-in-out',
		pauseOnHover: true,
		fade: true,
	});

	/* Blog - Cobus Analysis Slider
	------------------------------- */

	$('.anaslides').slick({
		arrows: true,
		dots: false,
		autoplay: false,
		speed: 1000,
		cssEase: 'ease-in-out',
		pauseOnHover: true,
		fade: false,
		slidesToShow: 2,
		slidesToScroll: 2,
		infinite: false,	
	  	responsive: [
		    {
		      	breakpoint: 540,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
		    	},
		    },
		]			
	});	

	$('.anaslides').on('setPosition', function (event, slick) {

		slick.$slides.find('.anaslide').matchHeight({
		    byRow: true,
		    property: 'height',
		    target: null,
		    remove: false,		
		})

		slick.$slides.find('.anaslide__entry-title').matchHeight({
		    byRow: true,
		    property: 'height',
		    target: null,
		    remove: false,		
		})

		slick.$slides.find('.anaslide__entry-content').matchHeight({
		    byRow: true,
		    property: 'height',
		    target: null,
		    remove: false,		
		})				

		slick.$slides.find('.anaslide').each(function() {

			$(this).css({
				verticalAlign: 'top'
			});
		
			$(this).find('article').css({
				height: '100%'
			});

		})

	})	

	/* Blog - Student Post Slider
	----------------------------- */

	$('.blog .studslides').slick({
		arrows: true,
		dots: false,
		autoplay: false,
		speed: 1000,
		cssEase: 'ease-in-out',
		pauseOnHover: true,
		fade: false,
		//slidesToShow: 2,
		//slidesToScroll: 2,
	    slidesPerRow: 2,
	    rows: 2,		
		infinite: false,
		adaptiveHeight: true,
	  	responsive: [
		    {
		      	breakpoint: 640,
				settings: {
			    	slidesPerRow: 1,
			    	rows: 4,		
		    	},
		    },
		]		
	});

	$('.blog .studslides').on('setPosition', function (event, slick) {

		slick.$slides.find('.studslide').matchHeight({
		    byRow: true,
		    property: 'height',
		    target: null,
		    remove: false,		
		})

		slick.$slides.find('.studentpost__header').matchHeight({
		    byRow: true,
		    property: 'height',
		    target: null,
		    remove: false,		
		})		

		slick.$slides.find('.studslide').each(function() {
			$(this).css({
				verticalAlign: 'top'
			});
		})

		slick.$slides.find('.studslide').each(function() {
			$(this).find('article').css({
				height: '100%'
			});
		})

	})

	/* ============================================================================================
	# JS Social
	============================================================================================ */

	if ( cap.is_singular ) {

		$('.entry-meta__post-share ul > li > a').on('click', function(e) {
			e.preventDefault();
		})

		jsSocials.setDefaults('facebook', {
		    logo: 'fab fa-facebook-f',
		    label: 'Facebook', 
		});

		jsSocials.setDefaults('twitter', {
		    logo: 'fab fa-twitter',
		    label: 'Twitter',
		    via: cap.via,		    
		});

		jsSocials.setDefaults('linkedin', {
		    logo: 'fab fa-linkedin-in',
		    label: 'LinkedIn',
		});

		jsSocials.shares.weibo = {
		    label: 'Weibo',
		    logo: 'fab fa-weibo',
		    shareUrl: "http://service.weibo.com/share/share.php?url={url}&title={title}&pic={thumbnail}",
		    url: cap.permalink,
		    title: cap.title,
		    thumbnail: cap.thumbnail,
		};

		jsSocials.setDefaults('email', {
		    logo: 'fas fa-envelope',
		    label: 'Email',
		});			

	    $('.entry-meta__post-share .jssocials').jsSocials({
	        shares: ["facebook", "twitter", "linkedin", "weibo", "email"],
	        shareIn: "popup",
		    showLabel: true,
		    showCount: false,        
	    });		

	}
	if ( cap.is_home || cap.is_archive || cap.is_search ) { 

		$('.cap-listing__posts article, .cobus-showcase article').each(function() {

			const $thisform = $(this).closest('article').find('form'),
				  title 	= $thisform.find('.input--title').val(),
				  permalink = $thisform.find('.input--permalink').val(),
				  thumbnail = $thisform.find('.input--thumbnail').val(),
				  content 	= $(this).closest('article').find('form').find('textarea').val();

			jsSocials.setDefaults('facebook', {
			    via: cap.via,
			    logo: 'fab fa-facebook-f',
			    label: 'Facebook', 
			    url: permalink,			
			});

			jsSocials.setDefaults('twitter', {
			    logo: 'fab fa-twitter',
			    label: 'Twitter',
				url: permalink,
				text: content,
				via: cap.via,
				// hashtags:		    
			});

			jsSocials.setDefaults('linkedin', {
			    logo: 'fab fa-linkedin-in',
			    label: 'LinkedIn',
				url: permalink,  
			});

			jsSocials.shares.weibo = {
			    label: 'Weibo',
			    logo: 'fab fa-weibo',
			    shareUrl: "http://service.weibo.com/share/share.php?url={url}&title={title}&pic={thumbnail}",
			    url: permalink,
			    title: title,
			    thumbnail: thumbnail,
			};

			jsSocials.setDefaults('email', {
			    logo: 'fas fa-envelope',
			    label: 'Email',
				text: permalink,
				url: content,
			});			

		    $(this).find('.jssocials').jsSocials({
		        shares: ["facebook", "twitter", "linkedin", "weibo", "email"],
		        shareIn: "popup",
			    showLabel: false,
			    showCount: false,        
		    });		

		})

	}	

	/* ============================================================================================
	# Media
	============================================================================================ */	
	$('#content iframe, #content video').each(function(i) {

		const src 		= $(this).attr('src'),
			  hasSrc 	= (typeof src !== typeof undefined && src !== false ),
			  name 		= $(this).attr('name'),
			  hasName 	= (typeof name !== typeof undefined && name !== false ),
			  noWrap 	= (
							( hasSrc && ( src.indexOf('libsyn.com') >= 0 ) ) 	||
							( hasName && ( name.indexOf('gform_ajax') >= 0 ) ) 	||
							( $(this).hasClass('podframe') ) 					||
							( $(this).closest('.mp-form-row').length > 0 )
			  			  );
		
		// if ( hasName ) console.log( name.indexOf('gform_ajax') );

		if ( !noWrap ) { 
			$(this).wrap('<div class="responsive-container">');
		}

	})

	/* ============================================================================================
	# MatchHeight
	============================================================================================ */	
	$('.related-post h4').matchHeight({
	    byRow: true,
	    property: 'height',
	    target: null,
	    remove: false,		
	});	

	$('.recent-posts-404 .widgettitle, .categories-404 .widget-title, .tagcloud-404 .widgettitle').matchHeight({
	    byRow: true,
	    property: 'height',
	    target: null,
	    remove: false,		
	});	


	/* ============================================================================================
	# Sticky Sidebar
	============================================================================================ */

	const isIE_old = ( !!window.ActiveXObject ),
		  isIE_new = ( document.body.style.msTouchAction !== undefined );

	// console.log( isIE_old );
	// console.log( isIE_new );	

	if ( ( true == isIE_old ) || ( true == isIE_new ) ) {

		const sidebarArr = [
			'cap-single__sidebar',
			'cap-page__sidebar',
			'cap-listing__sidebar',
			'cap-cpt-archive__sidebar',
		]

		$.each(sidebarArr, function (index, value) {

			$( '.' + value ).stick_in_parent();

			function sticky_made_responsive() {

				const window_width = window.innerWidth;

				if ( window_width < 1024 ) {

				    $( '.' + value ).trigger('sticky_kit:detach');

				} else {		

					$( '.' + value ).stick_in_parent();
				
				}

			}

			sticky_made_responsive();
			// Use Paul Irish' debounce function instead of the window load event
			$(window).smartresize(function(){ sticky_made_responsive(); }); 

		});

	};

	/* ============================================================================================
	# In-Post Subscribe Form
	============================================================================================ */
	// console.log( cap.is_singular );
	// console.log( !cap.is_home );
	// console.log( cap.is_single_member );
	if ( 
		cap.inpostSubscribe 	&& 
		cap.is_singular 		&& 
		!cap.is_home 			&&
		!cap.showBox  			&&
		!cap.is_single_member
	   ) {
		
		$('.cap-single__hentry .entry-content > *:nth-child(3)').after( '<div class="wrap--in-post wrap--in-post-1"><div class="in-post">' + cap.inpostSubscribe + '</div></div>' );
		
		$('.wrap--in-post').fadeIn();

	}

	/* ============================================================================================
	# CAP Block - Description List
	============================================================================================ */	
	$('.cap-dl').each(function() {

		$('dt').on('click', function() {

			$(this).next('dd').fadeToggle();

		})

	})

	/* ============================================================================================
	# Member Directory
	============================================================================================ */

	// Add search icon to the submit button
	$('[id*="search-filter-form-"] .sf-field-submit').append('<i class="fas fa-search" />');

	// Clicking the search icon should trigger a click on the submit button itself
	$('[id*="search-filter-form-"] .sf-field-submit i').on('click', function(){
		$(this).siblings('input').trigger('click');
	})


	$(window).on('load', function() {

		// Fix placeholders
		$('.posts-table-above .selectize-control input[type="select-one"]').each(function(i) {

			let ph;

			if ( i == 0 ) { ph = 'Profession'; }
			if ( i == 1 ) { ph = 'Countries (of Residence)'; }
			if ( i == 2 ) { ph = 'Languages'; }

			$(this).attr('placeholder', ph);
			// console.log( ph );

		})

		// Wrap the table-above keyword search label with span tags - the only way to fix
		// vertical alignment issues
		$('.posts-table-above > div:nth-child(2) label')
			.contents()
		    .filter(function(){return this.nodeType === 3})
		    .wrap('<span />');		

	})	

	/* ============================================================================================
	# Subscribe page
	============================================================================================ */

	// Remove white spaces from the price info ( with $.trim() ), then wrap each word 
	// in span tags - this way different parts of the price info label can have different styling.
	if ( $('.page-template-page-subscribe').length > 0 ) {

		const priceinfo = $('.mepr_price_cell').text(),
			  words = $.trim( priceinfo ),
			  trimmed = words.split(' ');

		$('.mepr_price_cell').empty();

		$.each(trimmed, function(i, v) {

		    $('.mepr_price_cell').append( $('<span class="price-info--word' + i + '">').text(v) );
		    $('.mepr_price_cell span:empty').remove();
		
		});

		const per = $('.mepr_price_cell span:nth-child(2)').text(),
			  newper = per.replace( '/', ' per ' );
			  
		$('.mepr_price_cell span:nth-child(2)').text( newper );

	}

	// Replace default MemberPress/WordPress loader gif
	$(window).on('load', function() {

		// Replace default Memberpress spinner
		$('.mepr-submit').siblings('img').attr('src', cap.templatedir + '/img/mepr-ajax-loader.gif' ); 
		$('.mepr-submit').siblings('img').attr('data-src', cap.templatedir + '/img/mepr-ajax-loader.gif' ); 	

	})		

	// DOM manipulation - fix payment icons positioning bug when one-page checkout is activated
	$('.mepr-payment-methods-radios label').each(function(i) {

		$(this)
			.addClass('flex-item')
			.wrap('<div class="flex-container" />');

	});

	$('.mepr-payment-method-icon').each(function(i) {

		const $flexcont = $('.mepr-payment-methods-radios').find('.flex-container:nth-child(' + parseInt( i + 1 ) + ')');

		$(this)
			.addClass('flex-item')
			.appendTo( $flexcont );

	})

	$('.mepr-payment-method-label-text').each(function() {

		const this_Str = $(this).text();
		$(this).text( this_Str.replace( 'Stripe', 'Credit/Debit Card') );
		$(this).text( this_Str.replace( 'Credit/Debit', 'Credit/Debit Card') );

	})

	// Asterisks - fix "no info on asterisks" plugin bug
	$('.mp-form-label label').each(function(i) {

		const label_Str = $(this).text();

		if ( label_Str.endsWith('*') ) {

			const expl = label_Str.split( ':' );

			$(this).html( expl[0] + ' <span style="color: red;">' + expl[1] + '<span>' );

		}

	})

	$('.mp-form-submit').before('<div class="mepr_req_info">All fields marked with an asterisk (<span>*</span>) are required.</div>');

	/* ============================================================================================
	# Disable Sumo badge
	============================================================================================ */	
	$(window).on('load', function() {

		setTimeout( function() { 

			// console.log( $('.sumo-control').length > 0 );

			if ( $('.sumo-control').length > 0 ) {

				const $sumoGrandpa = $('.sumo-control').closest('div').closest('div');
				// console.log( $sumoGrandpa );
		
				$sumoGrandpa.prev('div').prev('iframe').remove();
				$sumoGrandpa.prev('div').remove();
				$sumoGrandpa.remove();

			}

		}, 3000 );	

	})

})