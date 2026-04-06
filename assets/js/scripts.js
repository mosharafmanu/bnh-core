/**
 * Carousel and UI initialization
 *
 * Trip showcase, testimonials, why choose us, latest news, FAQ accordion,
 * and video autoplay behaviors
 *
 * @package BNH_Core
 */

// Dynamic header offset calculation
jQuery(document).ready(function($) {

	function updateHeaderOffset() {
		const header = $('.site-header');
		if (header.length) {
			const headerHeight = header.outerHeight();
			document.documentElement.style.setProperty('--header-offset', headerHeight + 'px');
		}
	}

	// Update on load
	updateHeaderOffset();

	// Update on resize (header height might change)
	let headerResizeTimer;
	$(window).on('resize', function() {
		clearTimeout(headerResizeTimer);
		headerResizeTimer = setTimeout(updateHeaderOffset, 100);
	});

	// Update after fonts load (header height might change)
	$(window).on('load', function() {
		setTimeout(updateHeaderOffset, 200);
	});
});

// Header Scroll Class
jQuery(document).ready(function($) {
	const header = $('.site-header');

	// Only run if header exists
	if (!header.length) {
		return;
	}

	const scrollThreshold = 30; // Pixels to scroll before changing header

	function handleHeaderScroll() {
		const scrollTop = $(window).scrollTop();

		if (scrollTop > scrollThreshold) {
			header.addClass('is-scrolled');
		} else {
			header.removeClass('is-scrolled');
		}
	}

	// Check on load
	handleHeaderScroll();

	// Check on scroll
	$(window).on('scroll', function() {
		handleHeaderScroll();
	});
});


// Global stage padding right - Add classes to elements with class="js-stage-padding"
jQuery(document).ready(function($) {
	function toggleStagePaddingClasses() {
		const elements = $('.js-stage-padding');
		if ($(window).width() <= 767) {
			elements.addClass('stagePaddingRight itemMargin');
		} else {
			elements.removeClass('stagePaddingRight itemMargin');
		}
	}

	// Initial check
	toggleStagePaddingClasses();

	// Update on resize
	let stagePaddingTimer;
	$(window).on('resize', function() {
		clearTimeout(stagePaddingTimer);
		stagePaddingTimer = setTimeout(toggleStagePaddingClasses, 100);
	});
});


// Stage Padding Carousel (Mobile Only)
jQuery(document).ready(function($) {

	/**
	 * Set equal height for all cards in carousel
	 */
	function setEqualHeight() {
		if (window.innerWidth < 768) {
			$('.js-stage-padding').each(function() {
				const $carousel = $(this);
				let maxHeight = 0;

				// Find cards - supports both .card and .icon-card classes
				const $cards = $carousel.find('.card, .icon-card, .product-card');

				// Reset heights first
				$cards.css('height', '');

				// Calculate max height
				$cards.each(function() {
					maxHeight = Math.max(maxHeight, $(this).outerHeight());
				});

				// Apply equal height
				$cards.css('height', maxHeight + 'px');
			});
		} else {
			// Reset heights on desktop
			$('.js-stage-padding .card, .js-stage-padding .icon-card, .js-stage-padding .product-card').css('height', '');
		}
	}

	/**
	 * Initialize stage padding carousel
	 */
	function initStagePaddingCarousel() {
		// Exclude grids that have their own carousels
		const $carousel = $('.js-stage-padding').not('.latest-news-grid, .related-products-grid, .logo-showcase-grid');

		if (!$carousel.length) {
			return;
		}

		if (window.innerWidth < 768) {
			if (!$carousel.hasClass('slick-initialized')) {
				$carousel.slick({
					dots: false,
					arrows: false,
					infinite: true,
					speed: 300,
					slidesToShow: 1,
					slidesToScroll: 1,
					adaptiveHeight: false,
					onSetPosition: function() {
						setEqualHeight();
					}
				});

				// Call setEqualHeight after initialization
				setTimeout(setEqualHeight, 100);
			}
		} else {
			if ($carousel.hasClass('slick-initialized')) {
				$carousel.slick('unslick');
				// Reset heights when unslicking
				$('.js-stage-padding .card, .js-stage-padding .icon-card, .js-stage-padding .product-card').css('height', '');
			}
		}
	}

	// Initialize on load with delay to ensure DOM is ready
	setTimeout(initStagePaddingCarousel, 100);

	// Re-initialize on resize
	let carouselResizeTimer;
	$(window).on('resize', function() {
		clearTimeout(carouselResizeTimer);
		carouselResizeTimer = setTimeout(initStagePaddingCarousel, 250);
	});
});


// Video autoplay on scroll
document.addEventListener('DOMContentLoaded', function () {
    const videoContainers = document.querySelectorAll('.autoplay-video');

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                const video = entry.target.querySelector('video');
                if (!video) {
                    return;
                }

                if (entry.isIntersecting) {
                    video.currentTime = 0;
                    video.play().catch((error) => {
                        console.error('Video autoplay failed:', error);
                    });
                } else {
                    video.pause();
                }
            });
        },
        {
            threshold: 0.5,
        }
    );

    videoContainers.forEach((container) => {
        observer.observe(container);
    });
});

