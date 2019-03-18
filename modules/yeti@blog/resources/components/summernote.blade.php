@param($id, md5(microtime(true)))
@param($name, $id)
@param($type)
@param($text)
@param($url)

@param($parent)
@param($externals, false)

@section('css')
	@parent

	<link href="/css/summernote.css" rel="stylesheet">

	<style type="text/css">
		.note-editor {
			font-family: "Ubuntu Mono", monospace;
			font-size: 16px;
		}

		.note-editor h1,
		.note-editor h2,
		.note-editor h3,
		.note-editor h4,
		.note-editor h5,
		.note-editor h6 {
			font-weight: bold;
			font-style: normal;
			display: block;
			color: #221f1f;
			text-decoration: underline;
		}

		.note-editor h1 *,
		.note-editor h2 *,
		.note-editor h3 *,
		.note-editor h4 *,
		.note-editor h5 *,
		.note-editor h6 * {
			font-weight: bold;
			font-style: normal;
		}

		.note-editor h1 {
			font-size: 34px;
			margin: 72px 0 52px 0;
			text-transform: uppercase;
		}

		.note-editor h2 {
			font-size: 30px;
			margin: 72px 0 52px 0;
		}

		.note-editor h3 {
			font-size: 26px;
			margin: 72px 0 32px 0;
		}

		.note-editor h4 {
			font-size: 22px;
			margin: 52px 0 22px 0;
		}

		.note-editor h5 {
			font-size: 18px;
			margin: 52px 0 22px 0;
		}

		.note-editor h6 {
			font-size: 16px;
			margin: 52px 0 22px 0;
		}

		.note-editor .dropdown-style h1,
		.note-editor .dropdown-style h2,
		.note-editor .dropdown-style h3,
		.note-editor .dropdown-style h4,
		.note-editor .dropdown-style h5,
		.note-editor .dropdown-style h6 {
			margin: 0 !important;
		}
	</style>
@stop

@section('js')
	@parent

	<script src="/js/summernote.js"></script>
	<script type="text/javascript">
		(function(){
			var __PARENT__ = "{{ $parent }}";
			var __EXTERNALS__ = !!"{{ $externals }}";

			$(function(){
				var jRecipient = $('#{{ $id }}');
				if (jRecipient.length > 0) {

					jRecipient.summernote({
						imageTitle: {
							specificAltField: false,
						},

						imageCaption: {
							defaultCaption: "Default Caption",
						},

						imageSource: {
							useImageSrc: true,
						},

						airMode: false,
						styleWithSpan: false,
						height: (function () {
							return (__PARENT__.length > 0 ? $("#{{ $parent }}")
								: jRecipient.parent()).innerHeight();
						})() - 40,

						focus: true,

						toolbar: [
							['style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
							['color', ['color']],
							['fontsize', ['fontsize']],

							__EXTERNALS__
								? ['insert', ['picture', 'video', 'link']]
								: ['insert', ['link']],

							['meta', ['style', 'ul', 'ol', 'paragraph', 'height']],
							['misc', ['undo', 'redo', 'fullscreen', 'help']]

							/*
							['table', ['table']],
							*/
						],

						popover: {
							image: [
								['float', ['floatLeft', 'floatRight', 'floatNone']],
								['image', ['imageSize100', 'imageSize50', 'imageSize25', 'imageSizeAuto', 'removeMedia']],
								['attrs', [ 'imageTitle', 'imageCaption', 'imageSource']]
							],
							link: [
								['link', ['linkDialogShow', 'unlink']]
							]

							/*
							table: [
								['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
								['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
							],
							*/
						},

						dialogsInBody: true,
						dialogsFade: true,
						tabDisable: false,

						blockquoteBreakingLevel: 2,

						popoverContainer: '#static-popover',

						callbacks: {
							onImageUpload: function (Files) {
								for (var i = 0; i < Files.length; i++) {
									$.ajax({
										url: '{{ $url  }}',
										type: 'POST',

										data: (function (File) {
											var data = new FormData();

											data.append('files[]', File);
											data.append('type', "{{ $type }}");

											return data;
										})(Files[i]),

										cache: false,
										contentType: false,
										processData: false,
										success: function (data) {
											$("#{{ $id }}").summernote('insertImage', data.url, function(jImage){
												jImage.attr('alt', data.name);
											});
										}
									});
								}
							},
							onPaste: function (jEvent) {
								var bufferText = ((jEvent.originalEvent || jEvent).clipboardData
									|| window.clipboardData).getData('Text');

								bufferText = bufferText.replace(/<((?:\/|!doctype\s*)?[a-z0-9_-]+)\s*(?:[^>\s]+(?:\s*=\s*(?:(?:'(?:\\'|[^'])*'|"(?:\\"|[^"])*")|[^>\s]+))?\s*)*\/?>/gi, '');

								jEvent.preventDefault();
								document.execCommand('insertText', false, bufferText);
							}
						}
					});
				}

				$(window).resize(function () {
					jRecipient.parent().find('.note-scrollable-container').height((function () {
						return (__PARENT__.length > 0 ? $("#{{ $parent }}")
							: jRecipient.parent()).innerHeight();
					})() - 40)
				})
			});
		})(jQuery);
	</script>
@stop

<textarea id="{{ $id }}" name="{{ $name }}" style="display: none">{{ $text }}</textarea>
