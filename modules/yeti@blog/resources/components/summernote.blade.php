@param($id, md5(microtime(true)))
@param($name, $id)
@param($text)
@param($url)

@param($parent, 'content')

@section('js')
	@parent

	<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
	<script type="text/javascript">
		(function(){
			$(function(){
				$('#{{ $id }}').summernote({
					height: (function(){
						return $("#{{ $parent }}").innerHeight(); })() - 40,

					focus: true,

					toolbar: [
						['style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
						['fontsize', ['fontsize']],
						['insert', ['picture', 'video', 'link']],
						['meta', ['style', 'ul', 'ol', 'paragraph', 'height']],
						['misc', ['undo', 'redo', 'fullscreen', 'help']],
					],

					dialogsInBody: true,
					dialogsFade: true,
					blockquoteBreakingLevel: 2,

					codeviewFilter: false,
			  		codeviewIframeFilter: true,

					callbacks: {
						onImageUpload: function(Files){
							for(var i = 0; i < Files.length; i++) {
								$.ajax({
									url: '{{ $url  }}',
									type: 'POST',

									data: (function (File) {
										var data = new FormData();

										data.append('files[]', File);
										data.append('type', 'blog');

										return data;
									})(Files[i]),

									cache: false,
									contentType: false,
									processData: false,
									success: function (data) {
										var Image = document.createElement('img');
										Image.src = data.url;
										Image.alt = data.name;

										$("{{ $id }}").summernote('insertNode', Image);
									}
								});
							}
						}
					}
				});
			});
		})(jQuery);
	</script>
@stop

<textarea id="{{ $id }}" name="{{ $name }}">{{ $text }}</textarea>
