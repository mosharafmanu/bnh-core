/**
 * Topic navigation enhancement.
 *
 * Child topic clicks and latest-article pagination are upgraded with REST
 * requests. Parent topic links remain standard page transitions.
 *
 * @package BNH_Core
 */

(function () {
	'use strict';

	const config = window.bnhCoreTopicNavigation || {};
	const childNav = document.querySelector('.topic-child-nav');
	const featuredContainerSelector = '[data-topic-featured]';
	const latestContainerSelector = '[data-topic-latest]';
	const hasDynamicTopicSections = Boolean(
		document.querySelector(featuredContainerSelector) && document.querySelector(latestContainerSelector)
	);
	const loadingClass = 'is-loading';
	const updatingClass = 'is-updating';

	function initChildNavScrolling() {
		document.querySelectorAll('.topic-child-nav').forEach((navWrapper) => {
			const nav = navWrapper.querySelector('.topic-child-nav__list');
			const scrollbar = navWrapper.querySelector('.topic-child-nav__scrollbar');
			const thumb = navWrapper.querySelector('.topic-child-nav__scrollbar-thumb');

			if (!nav || nav.dataset.scrollableChildNavBound === 'true') {
				return;
			}

			let isDown = false;
			let isDragging = false;
			let startX = 0;
			let scrollLeft = 0;
			let hasMoved = false;
			const dragThreshold = 5;

			function updateScrollbar() {
				if (!scrollbar || !thumb) {
					return;
				}

				const maxScroll = nav.scrollWidth - nav.clientWidth;

				if (maxScroll <= 0) {
					scrollbar.classList.remove('is-visible');
					thumb.style.width = '0%';
					return;
				}

				const scrollProgress = nav.scrollLeft / maxScroll;
				const visiblePercent = (nav.clientWidth / nav.scrollWidth) * 100;
				const progressPercent = visiblePercent + (scrollProgress * (100 - visiblePercent));

				scrollbar.classList.add('is-visible');
				thumb.style.width = `${progressPercent}%`;
			}

			nav.querySelectorAll('a, button').forEach((item) => {
				item.setAttribute('draggable', 'false');
			});

			nav.addEventListener('pointerdown', (event) => {
				if (event.button !== 0) {
					return;
				}

				isDown = true;
				isDragging = false;
				hasMoved = false;
				startX = event.clientX;
				scrollLeft = nav.scrollLeft;
			});

			function endDrag(event) {
				if (!isDown) {
					return;
				}

				isDown = false;
				isDragging = false;
				nav.classList.remove('is-dragging');

				if (typeof event.pointerId !== 'undefined' && nav.hasPointerCapture(event.pointerId)) {
					nav.releasePointerCapture(event.pointerId);
				}

				window.setTimeout(() => {
					hasMoved = false;
				}, 50);
			}

			nav.addEventListener('pointermove', (event) => {
				if (!isDown) {
					return;
				}

				const distance = Math.abs(event.clientX - startX);

				if (distance > dragThreshold) {
					event.preventDefault();
					hasMoved = true;

					if (!isDragging) {
						isDragging = true;
						nav.classList.add('is-dragging');
						nav.setPointerCapture(event.pointerId);
					}

					nav.scrollLeft = scrollLeft - ((event.clientX - startX) * 1.5);
				}
			});

			nav.addEventListener('pointerup', endDrag);
			nav.addEventListener('pointercancel', endDrag);

			nav.addEventListener('click', (event) => {
				const link = event.target.closest('a, button');

				if (!link || !hasMoved) {
					return;
				}

				event.preventDefault();
				event.stopPropagation();
			});

			nav.addEventListener('scroll', updateScrollbar, { passive: true });
			window.addEventListener('resize', () => {
				window.clearTimeout(nav.scrollableChildNavResizeTimeout);
				nav.scrollableChildNavResizeTimeout = window.setTimeout(updateScrollbar, 100);
			});

			nav.dataset.scrollableChildNavBound = 'true';

			window.setTimeout(updateScrollbar, 100);
			window.setTimeout(updateScrollbar, 500);
		});
	}

	initChildNavScrolling();

	function initMobileParentNav() {
		document.querySelectorAll('.topic-parent-nav').forEach((nav) => {
			const toggle = nav.querySelector('.topic-parent-nav__mobile-toggle');

			if (!toggle || nav.dataset.mobileParentNavBound === 'true') {
				return;
			}

			function close() {
				nav.classList.remove('is-mobile-open');
				toggle.setAttribute('aria-expanded', 'false');
			}

			toggle.addEventListener('click', () => {
				const isOpen = nav.classList.toggle('is-mobile-open');
				toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			});

			document.addEventListener('click', (event) => {
				if (!nav.classList.contains('is-mobile-open') || nav.contains(event.target)) {
					return;
				}

				close();
			});

			window.addEventListener('resize', () => {
				if (window.innerWidth > 767) {
					close();
				}
			});

			nav.dataset.mobileParentNavBound = 'true';
		});
	}

	initMobileParentNav();

	function updateLatestScrollProgress(section) {
		if (!section) {
			return;
		}

		const scroller = section.querySelector('.topic-latest-articles__items');
		const progress = section.querySelector('.topic-latest-articles__scrollbar-progress');
		const scrollbar = section.querySelector('.topic-latest-articles__scrollbar');
		const scrollUi = section.querySelector('.topic-latest-articles__scroll-ui');
		const prevButton = section.querySelector('.topic-latest-articles__scroll-button--prev');
		const nextButton = section.querySelector('.topic-latest-articles__scroll-button--next');

		if (!scroller || !progress || !scrollbar) {
			return;
		}

		const maxScroll = scroller.scrollWidth - scroller.clientWidth;
		const isScrollable = maxScroll > 0;

		scrollbar.classList.toggle('is-visible', isScrollable);
		if (scrollUi) {
			scrollUi.classList.toggle('is-visible', isScrollable);
		}

		if (!isScrollable) {
			progress.style.width = '0px';
			progress.style.transform = 'translateX(0)';
			return;
		}

		const visibleRatio = Math.min(1, scroller.clientWidth / scroller.scrollWidth);
		const minThumbPercent = 24;
		const thumbPercent = Math.max(minThumbPercent, visibleRatio * 100);
		const scrollbarWidth = scrollbar.clientWidth;
		const thumbWidth = scrollbarWidth * (thumbPercent / 100);
		const maxThumbOffset = Math.max(0, scrollbarWidth - thumbWidth);
		const progressOffset = maxScroll > 0 ? (scroller.scrollLeft / maxScroll) * maxThumbOffset : 0;

		progress.style.width = `${thumbWidth}px`;
		progress.style.transform = `translateX(${progressOffset}px)`;
	}

	function bindLatestScrollProgress(section) {
		if (!section || section.dataset.scrollProgressBound === 'true') {
			return;
		}

		const scroller = section.querySelector('.topic-latest-articles__items');
		const progress = section.querySelector('.topic-latest-articles__scrollbar-progress');
		const scrollbar = section.querySelector('.topic-latest-articles__scrollbar');
		const prevButton = section.querySelector('.topic-latest-articles__scroll-button--prev');
		const nextButton = section.querySelector('.topic-latest-articles__scroll-button--next');

		if (!scroller || !progress || !scrollbar) {
			return;
		}

		const refresh = () => updateLatestScrollProgress(section);
		const pointerState = {
			active: false,
			dragOffsetX: 0
		};

		function moveScrollerFromClientX(clientX) {
			const rect = scrollbar.getBoundingClientRect();
			const maxScroll = scroller.scrollWidth - scroller.clientWidth;

			if (maxScroll <= 0 || rect.width <= 0) {
				return;
			}

			const thumbWidth = progress.getBoundingClientRect().width;
			const usableWidth = Math.max(1, rect.width - thumbWidth);
			const offsetX = Math.min(Math.max(clientX - rect.left - pointerState.dragOffsetX, 0), usableWidth);
			const scrollRatio = offsetX / usableWidth;

			scroller.scrollLeft = maxScroll * scrollRatio;
		}

		scroller.addEventListener('scroll', refresh, { passive: true });
		window.addEventListener('resize', refresh);

		function getScrollStep() {
			const firstItem = scroller.querySelector('.topic-latest-articles__item');

			if (!firstItem) {
				return Math.max(320, scroller.clientWidth * 0.8);
			}

			const itemRect = firstItem.getBoundingClientRect();
			const scrollerStyles = window.getComputedStyle(scroller);
			const gap = parseFloat(scrollerStyles.columnGap || scrollerStyles.gap || '0') || 0;

			return itemRect.width + gap;
		}

		if (prevButton) {
			prevButton.addEventListener('click', () => {
				scroller.scrollBy({
					left: -getScrollStep(),
					behavior: 'smooth'
				});
			});
		}

		if (nextButton) {
			nextButton.addEventListener('click', () => {
				scroller.scrollBy({
					left: getScrollStep(),
					behavior: 'smooth'
				});
			});
		}

		scrollbar.addEventListener('click', (event) => {
			if (event.target === progress || pointerState.active) {
				return;
			}

			pointerState.dragOffsetX = progress.getBoundingClientRect().width / 2;
			moveScrollerFromClientX(event.clientX);
		});

		progress.addEventListener('pointerdown', (event) => {
			const thumbRect = progress.getBoundingClientRect();

			pointerState.active = true;
			pointerState.dragOffsetX = Math.min(Math.max(event.clientX - thumbRect.left, 0), thumbRect.width);
			progress.classList.add('is-dragging');
			progress.setPointerCapture(event.pointerId);
			event.preventDefault();
		});

		progress.addEventListener('pointermove', (event) => {
			if (!pointerState.active) {
				return;
			}

			moveScrollerFromClientX(event.clientX);
		});

		const endDrag = (event) => {
			if (!pointerState.active) {
				return;
			}

			pointerState.active = false;
			progress.classList.remove('is-dragging');

			if (typeof event.pointerId !== 'undefined' && progress.hasPointerCapture(event.pointerId)) {
				progress.releasePointerCapture(event.pointerId);
			}
		};

		progress.addEventListener('pointerup', endDrag);
		progress.addEventListener('pointercancel', endDrag);

		section.dataset.scrollProgressBound = 'true';

		window.requestAnimationFrame(refresh);
	}

	function initLatestScrollProgress(root = document) {
		root.querySelectorAll('.topic-latest-articles').forEach((section) => {
			bindLatestScrollProgress(section);
			updateLatestScrollProgress(section);
		});
	}

	initLatestScrollProgress();

	if (!childNav || !config.restUrl) {
		return;
	}

	function updateActiveChildLinks(activeChildSlug) {
		const activeColor = childNav.dataset.activeColor || '';

		childNav.querySelectorAll('.topic-child-nav__link').forEach((link) => {
			const isActive = link.dataset.childSlug === activeChildSlug;
			link.classList.toggle('is-active', isActive);

			if (isActive) {
				link.setAttribute('aria-current', 'page');
				if (activeColor) {
					link.style.backgroundColor = activeColor;
					link.style.color = 'var(--bhn-white)';
				}
			} else {
				link.removeAttribute('aria-current');
				link.style.backgroundColor = '';
				link.style.color = '';
			}
		});
	}

	function replaceSection(selector, html) {
		if (!html) {
			return null;
		}

		const currentNode = document.querySelector(selector);

		if (!currentNode) {
			return null;
		}

		const wrapper = document.createElement('div');
		wrapper.innerHTML = html.trim();

		const nextNode = wrapper.firstElementChild;

		if (!nextNode) {
			return null;
		}

		currentNode.replaceWith(nextNode);
		return nextNode;
	}

	function setLoadingState(nodes) {
		nodes.filter(Boolean).forEach((node) => {
			node.classList.add(loadingClass);
		});
	}

	function clearLoadingState(nodes) {
		nodes.filter(Boolean).forEach((node) => {
			node.classList.remove(loadingClass);
			node.classList.add(updatingClass);
			initLatestScrollProgress(node);

			window.setTimeout(() => {
				node.classList.remove(updatingClass);
			}, 220);
		});
	}

	function buildRequestUrl(parentSlug, childSlug, paged, fragment) {
		const url = new URL(config.restUrl, window.location.origin);
		url.searchParams.set('parent', parentSlug);
		url.searchParams.set('child', childSlug);
		url.searchParams.set('paged', String(paged || 1));

		if (fragment) {
			url.searchParams.set('fragment', fragment);
		}

		return url.toString();
	}

	async function fetchTopicContent(parentSlug, childSlug, paged, fragment) {
		const response = await window.fetch(buildRequestUrl(parentSlug, childSlug, paged, fragment), {
			method: 'GET',
			credentials: 'same-origin',
			headers: {
				'Accept': 'application/json'
			}
		});

		if (!response.ok) {
			throw new Error('Failed to load topic content.');
		}

		return response.json();
	}

	childNav.addEventListener('click', async (event) => {
		const link = event.target.closest('.topic-child-nav__link');

		if (!link) {
			return;
		}

		if (!hasDynamicTopicSections) {
			return;
		}

		event.preventDefault();

		const parentSlug = link.dataset.parentSlug;
		const childSlug = link.dataset.childSlug;

		if (!parentSlug || !childSlug) {
			return;
		}

		try {
			const featuredContainer = document.querySelector(featuredContainerSelector);
			const latestContainer = document.querySelector(latestContainerSelector);

			setLoadingState([featuredContainer, latestContainer]);

			const data = await fetchTopicContent(parentSlug, childSlug, 1, 'all');

			const nextFeaturedContainer = replaceSection(featuredContainerSelector, data.featured_html || '');
			const nextLatestContainer = replaceSection(latestContainerSelector, data.latest_html || '');
			updateActiveChildLinks(childSlug);
			clearLoadingState([nextFeaturedContainer, nextLatestContainer]);
		} catch (error) {
			window.console.error(error);

			const featuredContainer = document.querySelector(featuredContainerSelector);
			const latestContainer = document.querySelector(latestContainerSelector);
			featuredContainer?.classList.remove(loadingClass);
			latestContainer?.classList.remove(loadingClass);
		}
	});

	document.addEventListener('click', async (event) => {
		const paginationLink = event.target.closest('.topic-latest-articles .pagination a');

		if (!paginationLink) {
			return;
		}

		event.preventDefault();

		const latestContainer = document.querySelector(latestContainerSelector);

		if (!latestContainer) {
			return;
		}

		const parentSlug = latestContainer.dataset.parentSlug;
		const childSlug = latestContainer.dataset.childSlug;
		const paginationUrl = new URL(paginationLink.href, window.location.origin);
		const paged = parseInt(paginationUrl.searchParams.get('topic-page') || '1', 10);

		if (!parentSlug || !childSlug) {
			return;
		}

		try {
			latestContainer.classList.add(loadingClass);
			const data = await fetchTopicContent(parentSlug, childSlug, paged, 'latest');
			const nextLatestContainer = replaceSection(latestContainerSelector, data.latest_html || '');
			clearLoadingState([nextLatestContainer]);
		} catch (error) {
			window.console.error(error);
			latestContainer.classList.remove(loadingClass);
		}
	});
})();
