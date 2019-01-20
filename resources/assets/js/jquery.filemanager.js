(function ($) {
	var __NORMAL_BLOCK_WIDTH__ = 300;

	$(function () {

		var jManager = $('.filemanager');

		if (jManager.length > 0) {
			var jData = jManager.find('.data');

			if (jData.length > 0) {
				var jCrumbs = $('.breadcrumbs');

				/**
				 * @TODO Don't forget to remove that hook later!.
				 */
				window.__FILEMANAGER__ = {


					/**
					 * This is a temporarily needed hook to have the ability
					 * to add new items in realtime after uploading.
					 *
					 * @param Options
					 * @returns {*|HTMLElement}
					 */
					file: function (Options) {
						var fileSize = bytesToSize(Options.size);
						var name = escapeHTML(Options.name);
						var icon = '<span class="icon file"></span>';

						var fileType = name.split('.');
						fileType = fileType.length > 1
							? fileType[fileType.length - 1] : '';

						icon = '<span class="icon file f-' + fileType + '">.' + fileType + '</span>';

						return $('<li class="files"><a href="javascript:void(0)" title="' + Options.path + '" class="files">' + icon + '<span class="name">' + name + '</span> <span class="details">' + fileSize + '</span></a></li>');
					},

					folder: function (Options) {
						var name = escapeHTML(Options.name);
						var icon = '<span class="icon folder"></span>';

						var itemsLength = Options.size;
						if (itemsLength == 1) {
							itemsLength += ' item';
						} else if (itemsLength > 1) {
							itemsLength += ' items';
						} else {
							itemsLength = 'Empty';
						}

						return $('<li class="folders" data-path="' + Options.path + '"><a href="javascript:void(0)" class="folders">' + icon + '<span class="name">' + name + '</span>'
							+ '<span class="details">' + itemsLength + '</span></a></li>');
					}
				};

				/**
				 * Navigates to the given path
				 * @param path
				 */
				function goto(path) {

					/**
					 * @TODO Don't forget to remove that hook later!.
					 *
					 * This is a temporarily needed hook to have the ability to upload files into the selected directory.
					 * Without the hook would be possible to upload files into the root folder only.
					 */
					window['__FILEMANAGER__CONTEXT__'] = path;

					$.get(jManager.data('load'), {
						'path': path
					}, function (Response) {
						if (!Response.error) {
							render(Response);
						}else{
							jData.empty().show(function(){
								$(document).trigger('files-loaded');
							});
						}
					});
				}

				/**
				 * Render the HTML for the file manager
				 * @param data
				 */
				function render(Data) {
					var Folders = [];
					var Files = [];

					if (Array.isArray(Data.items)) {
						Data.items.forEach(function (d) {
							if (d.type === 'folder') {
								Folders.push(d);
							} else if (d.type === 'file') {
								Files.push(d);
							}
						});
					}

					// Empty the old result and make the new one
					jData.empty().hide();
					if (!Data.is_root) {
						var back = $('<li class="folders"><a data-path="" href="javascript:void(0)" title="Back"><span class="icon folder"></span>'
							+ '<span class="back">...</span></a></li>');
						back.appendTo(jData);
					}

					if (Folders.length) {
						Folders.forEach(function (f) {
							jData.append(window.__FILEMANAGER__.folder(f))
						});

					}

					if (Files.length) {
						Files.forEach(function (f) {
							jData.append(window.__FILEMANAGER__.file(f));
						});
					}

					/**
					 * Generate the breadcrumbs
					 */
					jCrumbs.empty();
					jCrumbs.append('<span>...</span>');
					var Steps = Data.path.split(/\/+/);
					if (Steps.length > 0) {
						Steps.forEach(function (item, index) {
							var name = item.split('/');
							if (/[^\s]+/.test(name)) {
								jCrumbs.append('<span class="arrow">&nbsp;/&nbsp;</span><span class="folderName">' + name[name.length - 1] + '</span>');
							}
						});
					}

					jData.show(function () {
						$(window).trigger('main-resize');
						$(document).trigger('files-loaded');
					});

					$(window).on('resize', function () {
						$(window).trigger('main-resize');
					});

					$(window).on('main-resize', function () {
						var jItem = $('li', jData)
							.removeAttr('style').first();

						if (jItem.length > 0) {
							$('li', jData).width(calculateWidth());
						}
					});

				}

				/**
				 * @returns {number}
				 */
				function calculateWidth(){
					return Math.round(jData.innerWidth() / Math.round(jData.innerWidth() / __NORMAL_BLOCK_WIDTH__) * 100) / 100;
				}

				/**
				 * Convert file sizes from bytes to human readable units
				 * @param {number} bytes
				 * @returns {string}
				 */
				function bytesToSize(bytes) {
					var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
					if (bytes === undefined){
						return 'Undefined';
					}

					if (bytes === 0) {
						return '0 Bytes';
					}

					var i = Math.floor(Math.log(bytes) / Math.log(1024));
					return (Math.round(bytes / Math.pow(1024, i) * 100) / 100)+ ' ' + sizes[i];
				}

				/**
				 * This function escapes special html characters in names
				 * @param text
				 * @returns {*}
				 */
				function escapeHTML(text) {
					return text.replace(/\&/g, '&amp;').replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
				}

				/**
				 * Clicking on folders
				 */
				jData.on('dblclick', 'li.folders', function (jEvent) {
					jEvent.preventDefault();
					goto($(this).data('path'));
				});

				/**
				 * Clicking on breadcrumbs
				 */
				jCrumbs.on('click', 'a', function (e) {
					e.preventDefault();

					var index = jCrumbs.find('a').index($(this)),
						nextDir = Urls[index];

					Urls.length = Number(index);
					window.location.hash = encodeURIComponent(nextDir);
				});


				/**
				 * Immediately initialization of the filemanager component.
				 *
				 * @attention It's will never initialize if element with css class 'filemanager' isn't exists
				 * or it hasn't a child element with html class 'data' inside.
				 */
				goto('/');

			}
		}
	});
})(jQuery);
