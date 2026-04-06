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
})(jQuery);
