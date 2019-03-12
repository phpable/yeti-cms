/* https://github.com/DiemenDesign/summernote-image-captionit */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory)
	} else if (typeof module === 'object' && module.exports) {
		module.exports = factory(require('jquery'));
	} else {
		factory(window.jQuery)
	}
}
(function ($) {
	$.extend(true, $.summernote.lang, {
		'en-US': {
			captionIt: {
				tooltip: 'Caption It'
			}
		}
	});

	$.extend($.summernote.plugins, {
		'captionIt': function (context) {
			var ui = $.summernote.ui,
				$editable = context.layoutInfo.editable,
				options = context.options,
				lang = options.langInfo,
				$note = context.layoutInfo.note;


			if (typeof context.options.captionIt.icon === 'undefined') {
				context.options.captionIt.icon = '<i class="fa fa-info"></i>';
			}

			if (typeof context.options.captionIt.captionText === 'undefined') {
				context.options.captionIt.captionText = 'Caption Goes Here.';
			}

			context.memo('button.captionIt', function () {
				var button = ui.button({
					contents: options.captionIt.icon,
					tooltip: lang.captionIt.tooltip,

					click: function () {
						var img = $($editable.data('target'));
						var jParent = img.parent();

						if (jParent.parent().find('span[data-cnt="caption"]').length > 0) {
							jParent.parent().find('span[data-cnt="caption"]').remove();
						} else {
							var captionText = context.options.captionIt.captionText;
							if (img.attr('title')) {
								captionText = img.attr('title');
							}

							if (!img.parent().is('span[data-cnt="wrapper"]')) {
								img.wrap($('<span data-cnt="wrapper"></span>'));
							}

							img.after('<span data-cnt="caption">'
								+ captionText + '</span>');
						}

						$note.val(context.invoke('code'));
						$note.change();
					}
				});

				return button.render();
			});
		}
	});
}));
