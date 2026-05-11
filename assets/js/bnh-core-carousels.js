/**
 * Example carousel initializer.
 *
 * This file intentionally keeps only one minimal Slick example for the new
 * theme. Add new carousel behaviors here only when the corresponding section
 * markup exists in bnh-core.
 *
 * @package BNH_Core
 */

(function ($) {
	'use strict';

	$(function () {
		const $carousel = $('.js-example-carousel');

		if (!$carousel.length || typeof $.fn.slick !== 'function') {
			return;
		}

		$carousel.each(function () {
			const $instance = $(this);

			if ($instance.hasClass('slick-initialized')) {
				return;
			}

			$instance.slick({
				dots: true,
				arrows: false,
				infinite: true,
				speed: 300,
				slidesToShow: 1,
				slidesToScroll: 1,
				adaptiveHeight: true
			});
		});
	});

	$(function () {
		const $doctorCarousels = $('.leading-doctors__grid');
		const breakpoint = 1199;
		const stagePaddingBreakpoint = breakpoint;

		if (!$doctorCarousels.length || typeof $.fn.slick !== 'function') {
			return;
		}

		function toggleLeadingDoctorsStagePadding($carousel) {
			if (window.innerWidth <= stagePaddingBreakpoint) {
				$carousel.addClass('stagePaddingRight itemMargin');
				return;
			}

			$carousel.removeClass('stagePaddingRight itemMargin');
		}

		function toggleLeadingDoctorsCarousel() {
			$doctorCarousels.each(function () {
				const $carousel = $(this);
				const $section = $carousel.closest('.leading-doctors');
				const isInitialized = $carousel.hasClass('slick-initialized');

				toggleLeadingDoctorsStagePadding($carousel);

				if (window.innerWidth <= breakpoint) {
					if (isInitialized) {
						return;
					}

					$carousel.slick({
						dots: false,
						arrows: true,
						infinite: true,
						speed: 300,
						slidesToShow: 3,
						slidesToScroll: 1,
						adaptiveHeight: false,
						appendArrows: $section.find('.leading-doctors__controls'),
						prevArrow: $section.find('.leading-doctors__arrow--prev'),
						nextArrow: $section.find('.leading-doctors__arrow--next'),
						responsive: [
							{
								breakpoint: 992,
								settings: {
									slidesToShow: 2
								}
							},
							{
								breakpoint: 768,
								settings: {
									slidesToShow: 1
								}
							}
						]
					});

					return;
				}

				if (isInitialized) {
					$carousel.slick('unslick');
				}

				$carousel.removeClass('stagePaddingRight itemMargin');
			});
		}

		toggleLeadingDoctorsCarousel();

		let doctorCarouselResizeTimer;
		$(window).on('resize', function () {
			clearTimeout(doctorCarouselResizeTimer);
			doctorCarouselResizeTimer = setTimeout(toggleLeadingDoctorsCarousel, 150);
		});
	});

	$(function () {
		const $similarArticleCarousels = $('.single-post-page .single-post-sidebar__cards');
		const breakpoint = 767;

		if (!$similarArticleCarousels.length || typeof $.fn.slick !== 'function') {
			return;
		}

		function resetSimilarArticlesHeights($carousel) {
			$carousel.find('.single-post-sidebar-card, .single-post-sidebar-card__link').css('height', '');
		}

		function equalizeSimilarArticlesHeights($carousel) {
			let maxHeight = 0;
			const $cards = $carousel.find('.slick-slide:not(.slick-cloned) .single-post-sidebar-card');

			resetSimilarArticlesHeights($carousel);

			$cards.each(function () {
				maxHeight = Math.max(maxHeight, $(this).outerHeight());
			});

			if (maxHeight > 0) {
				$carousel.find('.single-post-sidebar-card, .single-post-sidebar-card__link').css('height', maxHeight + 'px');
			}
		}

		function toggleSimilarArticlesCarousel() {
			$similarArticleCarousels.each(function () {
				const $carousel = $(this);
				const isInitialized = $carousel.hasClass('slick-initialized');

				if (window.innerWidth <= breakpoint) {
					$carousel.addClass('stagePaddingRight itemMargin');

					if (isInitialized) {
						equalizeSimilarArticlesHeights($carousel);
						return;
					}

					$carousel.off('setPosition.bnhSimilarArticles').on('setPosition.bnhSimilarArticles', function () {
						equalizeSimilarArticlesHeights($carousel);
					});
					$carousel.find('img').off('load.bnhSimilarArticles').on('load.bnhSimilarArticles', function () {
						equalizeSimilarArticlesHeights($carousel);
					});

					$carousel.slick({
						dots: false,
						arrows: false,
						infinite: true,
						speed: 300,
						slidesToShow: 1,
						slidesToScroll: 1,
						adaptiveHeight: false
					});

					setTimeout(function () {
						equalizeSimilarArticlesHeights($carousel);
					}, 100);

					return;
				}

				if (isInitialized) {
					$carousel.slick('unslick');
				}

				$carousel.off('setPosition.bnhSimilarArticles');
				$carousel.find('img').off('load.bnhSimilarArticles');
				resetSimilarArticlesHeights($carousel);
				$carousel.removeClass('stagePaddingRight itemMargin');
			});
		}

		toggleSimilarArticlesCarousel();

		let similarArticlesResizeTimer;
		$(window).on('resize', function () {
			clearTimeout(similarArticlesResizeTimer);
			similarArticlesResizeTimer = setTimeout(toggleSimilarArticlesCarousel, 150);
		});
	});
})(jQuery);
