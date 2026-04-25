(function (blocks, element, blockEditor, serverSideRender) {
	'use strict';

	if (!blocks || !element || !blockEditor || !serverSideRender) {
		return;
	}

	const el = element.createElement;
	const ServerSideRender = serverSideRender;
	const useBlockProps = blockEditor.useBlockProps;

	blocks.registerBlockType('bnh-core/topic-community', {
		apiVersion: 2,
		title: 'Topic Community',
		description: 'Insert the reusable topic community signup section.',
		icon: 'groups',
		category: 'widgets',
		supports: {
			html: false,
			reusable: false
		},
		edit: function () {
			const blockProps = useBlockProps({
				className: 'bnh-editor-topic-community-block'
			});

			return el(
				'div',
				blockProps,
				el(ServerSideRender, {
					block: 'bnh-core/topic-community'
				})
			);
		},
		save: function () {
			return null;
		}
	});

	blocks.registerBlockType('bnh-core/book-consultation', {
		apiVersion: 2,
		title: 'Book Consultation',
		description: 'Insert the reusable book consultation section.',
		icon: 'id-alt',
		category: 'widgets',
		supports: {
			html: false,
			reusable: false
		},
		edit: function () {
			const blockProps = useBlockProps({
				className: 'bnh-editor-book-consultation-block'
			});

			return el(
				'div',
				blockProps,
				el(ServerSideRender, {
					block: 'bnh-core/book-consultation'
				})
			);
		},
		save: function () {
			return null;
		}
	});
}(window.wp && window.wp.blocks, window.wp && window.wp.element, window.wp && window.wp.blockEditor, window.wp && window.wp.serverSideRender));
