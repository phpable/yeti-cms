(function (factory) {
	/* global define */
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	} else if (typeof module === 'object' && module.exports) {
		// Node/CommonJS
		module.exports = factory(require('jquery'));
	} else {
		// Browser globals
		factory(window.jQuery);
	}
}(function ($) {
	$.extend(true, $.summernote.lang, {
		'en-US': {
			imageSource: {
				edit: 'Modify Source',
				sourceLink: 'Source',
				sourceText: 'Text'
			}
		},
	});

	$.extend($.summernote.plugins, {
		'imageSource': function (context) {
			var self = this;

			var ui = $.summernote.ui;
			var $note = context.layoutInfo.note;
			var $editor = context.layoutInfo.editor;
			var $editable = context.layoutInfo.editable;

			if (typeof context.options.imageSource === 'undefined') {
				context.options.imageSource = {};
			}

			if (typeof context.options.imageSource.useImageSrc === 'undefined') {
				context.options.imageSource.useImageSrc = true;
			}

			if (typeof context.options.imageSource.defaultText === 'undefined') {
				context.options.imageSource.defaultText = "Source";
			}

			var options = context.options;
			var lang = options.langInfo;

			context.memo('button.imageSource', function () {
				var button = ui.button({
					contents: '<i class="fa fa-external-link"></i>',
					tooltip: lang.imageSource.edit,
					container: false,
					click: function (e) {
						context.invoke('imageSource.update');
					}
				});

				return button.render();
			});

			this.initialize = function () {
				var $container = options.dialogsInBody ? $(document.body) : $editor;

				var body = [
					'<div class="form-group">',
					'<label>' + lang.imageSource.sourceText + '</label>',
					'<input class="note-source-source-text form-control" type="text" />',
					'</div>',
					'<div class="form-group">',
					'<label>' + lang.imageSource.sourceLink + '</label>',
					'<input class="note-source-source-link form-control" type="text" />',
					'</div>'
				].join('');

				var footer = '<button href="#" class="btn btn-primary note-image-source-btn">' + lang.imageSource.edit + '</button>';

				this.$dialog = ui.dialog({
					source: lang.imageSource.edit,
					body: body,
					footer: footer
				}).render().appendTo($container);
			};

			this.destroy = function () {
				ui.hideDialog(this.$dialog);
				this.$dialog.remove();
			};

			this.bindEnterKey = function ($input, $btn) {
				$input.on('keypress', function (event) {
					if (event.keyCode === 13) {
						$btn.trigger('click');
					}
				});
			};

			this.update = function () {
				var $img = $($editable.data('target'));

				var imgInfo = {
					imgDom: $img,
					text: $img.attr('data-source-text'),
					source: $img.attr('data-source-link')
				};

				if (!imgInfo.text || !imgInfo.text.length) {
					imgInfo.text = context.options.imageSource.defaultText;
				}

				if (!imgInfo.source || !imgInfo.source.length) {
					imgInfo.source = context.options.imageSource.useImageSrc ? $img.attr('src') : '';
				}

				this.showLinkDialog(imgInfo).then(function (imgInfo) {
					ui.hideDialog(self.$dialog);
					var $img = imgInfo.imgDom;

					if (imgInfo.source) {
						$img.attr('data-source-link', imgInfo.source);
					} else {
						$img.removeAttr('data-source-link');
					}

					if (imgInfo.text) {
						$img.attr('data-source-text', imgInfo.text);
					} else {
						$img.removeAttr('data-source-text');
					}

					$note.val(context.invoke('code'));
					$note.change();

					context.invoke('imageSource.wrap', $img);
				});
			};

			this.showLinkDialog = function (imgInfo) {
				return $.Deferred(function (deferred) {
					var $imageSource = self.$dialog.find('.note-source-source-link'),
						$imageText = self.$dialog.find('.note-source-source-text'),
						$editBtn = self.$dialog.find('.note-image-source-btn');

					ui.onDialogShown(self.$dialog, function () {
						context.triggerEvent('dialog.shown');

						$editBtn.click(function (event) {
							event.preventDefault();
							deferred.resolve({
								imgDom: imgInfo.imgDom,
								source: $imageSource.val(),
								text: $imageText.val()
							});
						});

						$imageSource.val(imgInfo.source).trigger('focus');
						$imageText.val(imgInfo.text).trigger('focus');

						self.bindEnterKey($imageSource, $editBtn);
					});

					ui.onDialogHidden(self.$dialog, function () {
						$editBtn.off('click');

						if (deferred.state() === 'pending') {
							deferred.reject();
						}
					});

					ui.showDialog(self.$dialog);
				});
			};

			this.wrap = function ($img) {
				var imgInfo = {
					imgDom: $img,
					source: $img.attr('data-source-link'),
					text: $img.attr('data-source-text')
				};

				if (!$img.parent().is('span[data-cnt="wrapper"]')) {
					context.invoke('editor.wrapImage', $img);
				}

				$img.parent().find('span[data-cnt="source"]').remove();
				if (imgInfo.source && imgInfo.text) {
					$img.after('<span data-cnt="source">Original: <a href="' + encodeURI(imgInfo.source) + '">' + imgInfo.text + '</a></span>');
				}

				$note.val(context.invoke('code'));
				$note.change();
			};
		}
	});
}));
