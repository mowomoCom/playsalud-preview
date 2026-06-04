/*==================================================================
	TABLE OF CONTENTS
====================================================================
	# MWM HEADER
	# MWM GTRANSLATE
	# SWIPER
	# MWM FILTER
	# MWM POPUP
*/

/*	# MWM HEADER
=============================================== */

var opening;
jQuery(document).ready(function () {

	// OPEN MENU WHEN CLICK ON BARS
	jQuery('.mwm-header__toggle').click(function () {
		opening = false;
		jQuery('.mwm-header__menu-container').toggleClass('is-opened');
		jQuery('body').toggleClass('offcanvas-overlay');
		setTimeout(function() {
			opening = true;
		}, 500);
	});

	// CREATE ELEMENT TO OPEN MENU ON MOBILE (ARROW)
	jQuery('.menu-item-has-children > a, .page_item_has_children > a').append(
		`<svg class="menu-item__btn" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M7 9.62422L11.375 5.24922L10.7625 4.63672L7 8.39922L3.2375 4.63672L2.625 5.24922L7 9.62422Z" fill="#2A2622"/>
		</svg>`
	);

	// TOGGLE CLASS ONCE, WHEN CLICKING THE ARROW
	jQuery(document).on('click', '.menu-item-has-children > a > svg', function (event) {
		event.stopPropagation();
		event.preventDefault();
		jQuery(this).parent().parent().toggleClass('is-open');
		jQuery(this).parent().parent().children('.sub-menu, .children').toggle();
		jQuery(this).parent().parent().children('.menu-item__btn').toggleClass('rotate');
	});

	// SET HEADER HEIGHT
	const headerHeight = () => {
		const doc = document.documentElement;
		let header = jQuery('.mwm-header').innerHeight();
		doc.style.setProperty('--header-height', `${header}px`);
	};

	window.addEventListener('resize', headerHeight);

	headerHeight();
});

/*	# MWM GTRANSLATE
=============================================== */

jQuery(document).ready(function() {
	var selectedValue = jQuery('.gt-current-lang').attr('data-gt-lang');
	var headerTitle = jQuery('.mwm-gtranslate__header-title');

	headerTitle.text(selectedValue);

	jQuery('.mwm-gtranslate a').on('click', function() {
		selectedValue = jQuery(this).attr('data-gt-lang');
		headerTitle.text(selectedValue);
	});

	jQuery('.mwm-gtranslate__header').click(function() {
		jQuery('.mwm-gtranslate').toggleClass('is-open');
	});

	jQuery('.mwm-gtranslate a').click(function() {
		jQuery('.mwm-gtranslate').toggleClass('is-open');
	});
});

/*	# SWIPER
=============================================== */

jQuery(document).ready(function() {
	if (typeof Swiper === 'undefined') {
		return;
	}

	jQuery('.mwm-slider-1').each(function() {
		var $this = jQuery(this);
		var $swiperElement = $this.find('.swiper').first();
		var swiperId = $swiperElement.attr('id');

		if (!swiperId) {
			return;
		}

		var swiperPrevId = swiperId + '-prev';
		var swiperNextId = swiperId + '-next';

		var swiper = new Swiper('#' + swiperId, {
			loop: true,
			slidesPerView: 1,
			spaceBetween: 20,
			autoplay: {
				delay: 4500,
				disableOnInteraction: false,
			},
			navigation: {
				nextEl: '#' + swiperNextId,
				prevEl: '#' + swiperPrevId,
			},
			breakpoints: {
				1024: {
					slidesPerView: 2,
				},
			},
			on: {
				init: function(swiper) {
					updateCounter(swiper);
				},
				slideChange: function(swiper) {
					updateCounter(swiper);
				}
			}
		});
		function updateCounter(swiper) {
			var currentSlide = swiper.realIndex + 1; // Adjusting for the real index
	
			$this.find('.mwm-slider-1__current').text(currentSlide);
		}
	});

	jQuery('.wp-block-mwm-muestra .mwm-muestra__swiper').each(function() {
		var swiperElement = this;
		if (swiperElement.swiper || swiperElement.dataset.mwmSwiperReady === '1') {
			return;
		}

		var block = swiperElement.closest('.wp-block-mwm-muestra');
		if (!block) {
			return;
		}

		var prevEl = block.querySelector('.swiper-button-prev');
		var nextEl = block.querySelector('.swiper-button-next');
		var paginationEl = block.querySelector('.swiper-pagination');

		if (!prevEl || !nextEl || !paginationEl) {
			return;
		}

		new Swiper(swiperElement, {
			loop: false,
			slidesPerView: 1,
			spaceBetween: 24,
			navigation: {
				nextEl: nextEl,
				prevEl: prevEl,
			},
			pagination: {
				el: paginationEl,
				clickable: true,
			},
		});

		swiperElement.dataset.mwmSwiperReady = '1';
	});

});

/*	# MWM FILTER
=============================================== */

jQuery(document).ready(function($) {
	$('.mwm-filter__toggle').click(function() {
		$('.mwm-filter').toggleClass('is-open');
	});

	$(document).on('click', function(event) {
		if (!$(event.target).closest('.mwm-filter__mobile-wrapper').length && !$(event.target).closest('.mwm-filter__toggle').length) {
			$('.mwm-filter').removeClass('is-open');
		}
	});
});

/*	# MWM POPUP
=============================================== */

jQuery(document).ready(function () {

	jQuery('.opens-popup-newsletter').each(function() {
		jQuery(this).on('click', function () {
			jQuery('.mwm-popup.is-newsletter').fadeIn(200);
		});
	});

	jQuery('.opens-popup-disenador').on('click', function(event) {
		event.preventDefault();
		var postId = jQuery(this).data('post-id');
		jQuery('.mwm-popup.is-disenador[data-post-id="' + postId + '"]').fadeIn(200);
	});

    // Attach click event to each element with class 'mwm-popup__close'
    jQuery('.mwm-popup__close').each(function() {
        jQuery(this).on('click', function () {
            jQuery('.mwm-popup').fadeOut(200);
        });
    });
});