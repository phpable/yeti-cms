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
			imageCaption: {
				edit: 'Modify Caption',
				captionLabel: 'Caption'
			}
		},
	});

	$.extend($.summernote.plugins, {
		'imageCaption': function (context) {
			var self = this;

			var ui = $.summernote.ui;
			var $note = context.layoutInfo.note;
			var $editor = context.layoutInfo.editor;
			var $editable = context.layoutInfo.editable;

			if (typeof context.options.imageCaption === 'undefined') {
				context.options.imageCaption = {};
			}

			if (typeof context.options.imageCaption.defaultCaption === 'undefined') {
				context.options.imageCaption.defaultCaption = "Default caption";
			}

			var options = context.options;
			var lang = options.langInfo;

			context.memo('button.imageCaption', function () {
				var button = ui.button({
					contents: '<i class="fa fa-info-circle"></i>',
					tooltip: lang.imageCaption.edit,
					container: false,
					click: function (e) {
						context.invoke('imageCaption.show');
					}
				});

				return button.render();
			});

			this.initialize = function () {
				var $container = options.dialogsInBody ? $(document.body) : $editor;

				var body = ['<div class="form-group">',
					'<label>' + lang.imageCaption.captionLabel + '</label>',
					'<input class="note-caption-caption-text form-control" type="text" />',
					'</div>'].join('');

				var footer = '<button href="#" class="btn btn-primary note-image-caption-btn">' + lang.imageCaption.edit + '</button>';

				this.$dialog = ui.dialog({
					caption: lang.imageCaption.edit,
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

			this.show = function () {
				var $img = $($editable.data('target'));
				var imgInfo = {
					imgDom: $img,
					caption: $img.attr('data-caption')
				};
				this.showLinkDialog(imgInfo).then(function (imgInfo) {
					ui.hideDialog(self.$dialog);
					var $img = imgInfo.imgDom;

					if (imgInfo.caption) {
						$img.attr('data-caption', imgInfo.caption);
					} else {
						$img.removeAttr('data-caption');
					}

					if ($img.parent().is('[data-cnt="wrapper"')) {
						$img.parent().find('[data-cnt="caption"]').text(imgInfo.caption);
					}

					$note.val(context.invoke('code'));
					$note.change();
				});
			};

			this.showLinkDialog = function (imgInfo) {
				return $.Deferred(function (deferred) {
					var $imageCaption = self.$dialog.find('.note-caption-caption-text'),
						$editBtn = self.$dialog.find('.note-image-caption-btn');

					ui.onDialogShown(self.$dialog, function () {
						context.triggerEvent('dialog.shown');

						$editBtn.click(function (event) {
							event.preventDefault();
							deferred.resolve({
								imgDom: imgInfo.imgDom,
								caption: $imageCaption.val(),
							});
						});

						$imageCaption.val(imgInfo.caption).trigger('focus');
						self.bindEnterKey($imageCaption, $editBtn);
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
		}
	});
}));
