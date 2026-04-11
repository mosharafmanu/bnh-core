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

	if (!childNav || !config.restUrl) {
		return;
	}

	const featuredContainerSelector = '[data-topic-featured]';
	const latestContainerSelector = '[data-topic-latest]';
	const loadingClass = 'is-loading';
	const updatingClass = 'is-updating';

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
