(function () {
	'use strict';

	tinymce.PluginManager.add('bnh_shortcodes', function (editor) {
		const registeredShortcodes = Array.isArray(window.bnhCoreEditorShortcodes) ? window.bnhCoreEditorShortcodes : [];

		if (!registeredShortcodes.length) {
			return;
		}

		const menuItems = registeredShortcodes.map(function (item) {
			const shortcodeTag = typeof item.tag === 'string' ? item.tag : '';
			const label = typeof item.label === 'string' ? item.label : shortcodeTag;

			return {
				text: label,
				onclick: function () {
					if (!shortcodeTag) {
						return;
					}

					editor.insertContent('[' + shortcodeTag + ']');
				}
			};
		});

		editor.addButton('bnh_shortcodes', {
			type: 'menubutton',
			text: 'BNH Shortcodes',
			icon: false,
			menu: menuItems
		});
	});
}());
