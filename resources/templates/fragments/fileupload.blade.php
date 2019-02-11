@param($target)
@param($id, 'fileupload' . md5(implode([time(), rand(1, 100)])))
@param($url, '/')
@param($progress)
@param($type)

@section('js')
	@parent

	<script type="text/javascript">
	(function($){

		$(function(){
			'use strict';
			var jTarget = $('#' + "{{ $target }}");

			if (jTarget.length > 0){
				if (!jTarget.is('.fileinput-button')){
					jTarget.addClass('fileinput-button');
				}

				jTarget.append('<input id="{{ $id }}" type="file" name="files[]">');
			}
		});


		/**
		 * Initialize the jQuery File Upload widget.
		 */
		$(function(){
			'use strict';

			var __ID__ = "#{{ $id }}";
			var __URL__ = "{{ $url }}";
			var __PROGRESS__ = "{{ $progress }}";
			var __TYPE__ = "{{ $type }}";

			$(__ID__).each(function () {
				var jThis = $(this);
				var jContainer = $(__ID__ + 'container');

				var jProgress = __PROGRESS__.length > 0
					? $('div', $('#' + __PROGRESS__)) : null;

				jThis.fileupload({
					url: __URL__,
					dataType: 'json',
					add: function (index, jEvent) {
						if (jProgress !== null) {
							jProgress.show();
						}

						$(document).trigger('file-uploading-start', jEvent);
						jEvent.submit();
					},
					progressall: function (index, jEvent) {
						if (jProgress !== null) {
							jProgress.css({ width:
								parseInt(jEvent.loaded / jEvent.total * 100, 10) + '%'});
						}
					},
					done: function (index, jEvent) {
						setTimeout(function(){
							$(document).trigger('file-uploaded', [$.extend(jEvent.result, {
								url: __URL__, id: __ID__.replace(/^#/, "")})]);
						}, 1000);
						if (jProgress !== null) {
							setTimeout(function() {
								jProgress.hide().css({
									width: 0}); }, 1000);
						}
					}
				}).bind('fileuploadsubmit', function (jEvent, Data) {
					Data.formData =  (function(){
						var data = new FormData();

						if (window['__FILEMANAGER__CONTEXT__'] !== undefined){
							data.append('context', window['__FILEMANAGER__CONTEXT__']);
						}

						if (__TYPE__.length > 0){
							data.append('type', __TYPE__);
						}

						return data;
					})();
				});
			});
		});
	})(jQuery);
	</script>
@stop
