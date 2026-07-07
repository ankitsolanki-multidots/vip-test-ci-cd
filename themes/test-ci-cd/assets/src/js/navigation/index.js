/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
(function ($) {
	'use strict';

	$('.menu-toggle').click(function () {
		$('header .main-navigation').toggleClass('toggled');
	});

	// responsive menu css
	$('.main-navigation ul.menu>li.menu-item-has-children').on(
		'click',
		function () {
			const windowWidth = $(window).width();
			if (767 > windowWidth) {
				$(this).find('.sub-menu,.children').slideToggle();
				$(this).toggleClass('active-sub');
			}
		}
	);

	// Sticky Header
	$(window).scroll(function () {
		if ($(window).scrollTop() >= 67) {
			$('.test-ci-cd-header').addClass('fixed-header');
		} else {
			$('.test-ci-cd-header').removeClass('fixed-header');
		}
	});
})(jQuery);
