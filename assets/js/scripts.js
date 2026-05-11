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

// Mobile header search popup
jQuery(document).ready(function($) {
	const $searchForm = $('.site-header__search');
	const $searchPopup = $('.site-header__search-popup');
	const $searchPopupInput = $('.site-header__search-popup-input');
	const mobileSearchBreakpoint = 991;

	if (!$searchForm.length || !$searchPopup.length || !$searchPopupInput.length) {
		return;
	}

	function isMobileHeader() {
		return window.innerWidth <= mobileSearchBreakpoint;
	}

	function closeSearchPopup() {
		$searchPopup.removeClass('is-open').attr('aria-hidden', 'true');
		$searchForm.find('.site-header__search-button').attr('aria-expanded', 'false');
	}

	$searchForm.on('click', '.site-header__search-button', function(event) {
		if (!isMobileHeader()) {
			return;
		}

		event.preventDefault();
		$searchPopup.addClass('is-open').attr('aria-hidden', 'false');
		$(this).attr('aria-expanded', 'true');

		window.setTimeout(function() {
			$searchPopupInput.trigger('focus');
		}, 50);
	});

	$(document).on('click', function(event) {
		if (!$searchPopup.hasClass('is-open')) {
			return;
		}

		if ($(event.target).closest('.site-header__search-popup-form, .site-header__search-button').length) {
			return;
		}

		closeSearchPopup();
	});

	$(document).on('keydown', function(event) {
		if (event.key === 'Escape') {
			closeSearchPopup();
		}
	});

	$(window).on('resize', function() {
		if (!isMobileHeader()) {
			closeSearchPopup();
		}
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

// Calendly popup links
jQuery(document).ready(function($) {
	$(document).on('click', '.js-calendly-popup', function(event) {
		const url = this.getAttribute('data-calendly-url') || this.getAttribute('href');

		if (!url || !window.Calendly || typeof window.Calendly.initPopupWidget !== 'function') {
			return;
		}

		event.preventDefault();

		window.Calendly.initPopupWidget({
			url: url
		});
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

// Single article interactions
jQuery(document).ready(function($) {
	$('.single-article__summary-toggle').on('click', function() {
		const $button = $(this);
		const $summary = $button.closest('.single-article__summary');
		const $article = $button.closest('.single-article');
		const expanded = $summary.hasClass('is-expanded');

		$summary.toggleClass('is-expanded', !expanded);
		$article.toggleClass('single-article--summary-expanded', !expanded);
		$button.attr('aria-expanded', String(!expanded));
	});

	$('.single-article__mobile-guide-toggle').on('click', function() {
		const $button = $(this);
		const $guide = $button.closest('.single-article__mobile-guide');
		const $content = $guide.find('.single-article__mobile-guide-content').first();
		const $label = $button.find('.single-article__mobile-guide-toggle-text').first();
		const expanded = $guide.hasClass('is-expanded');

		$guide.toggleClass('is-expanded', !expanded);
		$button.attr('aria-expanded', String(!expanded));
		$label.text(expanded ? 'View' : 'Hide');
		$content.stop(true, true)[expanded ? 'slideUp' : 'slideDown'](220);
	});

	$('.single-article__sources-toggle').on('click', function() {
		const $button = $(this);
		const $section = $button.closest('.single-article__sources');
		const $content = $section.find('.single-article__sources-content').first();
		const expanded = $section.hasClass('is-expanded');

		$section.toggleClass('is-expanded', !expanded);
		$button.attr('aria-expanded', String(!expanded));
		$content.stop(true, true)[expanded ? 'slideUp' : 'slideDown'](220);
	});

	$('.single-article__update-history-toggle').on('click', function() {
		const $button = $(this);
		const $section = $button.closest('.single-article__update-history');
		const $content = $section.find('.single-article__update-history-content').first();
		const expanded = $section.hasClass('is-expanded');

		$section.toggleClass('is-expanded', !expanded);
		$button.attr('aria-expanded', String(!expanded));
		$content.stop(true, true)[expanded ? 'slideUp' : 'slideDown'](220);
	});

	$('.entry-meta__person-trigger').on('click', function() {
		const $button = $(this);
		const $item = $button.closest('.entry-meta__item--person');
		const expanded = $item.hasClass('is-open');

		$('.entry-meta__item--person').not($item).removeClass('is-open').find('.entry-meta__person-trigger').attr('aria-expanded', 'false');
		$item.toggleClass('is-open', !expanded);
		$button.attr('aria-expanded', String(!expanded));
	});

	$(document).on('click', function(event) {
		const $target = $(event.target);

		if (!$target.closest('.entry-meta__item--person').length) {
			$('.entry-meta__item--person').removeClass('is-open').find('.entry-meta__person-trigger').attr('aria-expanded', 'false');
		}
	});

	$('.entry-meta__citation-trigger').on('click', function() {
		const button = this;
		const citation = button.getAttribute('data-citation') || '';
		const originalText = button.textContent;

		if (!citation) {
			return;
		}

		function markCopied() {
			button.textContent = 'Copied';
			window.setTimeout(function() {
				button.textContent = originalText;
			}, 1000);
		}

		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(citation).then(markCopied);
			return;
		}

		const textarea = document.createElement('textarea');
		textarea.value = citation;
		textarea.setAttribute('readonly', 'readonly');
		textarea.style.position = 'absolute';
		textarea.style.left = '-9999px';
		document.body.appendChild(textarea);
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);
		markCopied();
	});
});
