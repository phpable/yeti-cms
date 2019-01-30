@extends('yeti@blog::frame', [
	'action' => route('yeti@blog:posts.update', $Post->id),
	'footer' => true])

@section('css')
	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
@stop

@section('js')
	<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>

	<script type="text/javascript">
		(function(){
			$(function(){
				$('#wysiwyg').summernote({
					height:(function(){
						return $('#content').innerHeight(); })() - 40,

					focus: true,

					toolbar: [
						['style', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
						['fontsize', ['fontsize']],
						['insert', ['picture', 'video', 'link']],
						['meta', ['style', 'ul', 'ol', 'paragraph', 'height']],
						['misc', ['undo', 'redo', 'fullscreen', 'help']],
					],

					placeholder: 'Write here...',

					dialogsInBody: true,
					dialogsFade: true,
					blockquoteBreakingLevel: 2,

					codeviewFilter: false,
			  		codeviewIframeFilter: true,

				}).on('summernote.image.upload', function(Editor, Diles) {
					for(var i = 0; i < Files.length; i++) {
						$.ajax({
							url: '{{ route('yeti@main:files.upload') }}',
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
								Editor.insertImage(data.url, data.name);
							}
						});
					}
				});;
			});
		})(jQuery);
	</script>
@stop

@section('actions')
	@parent

	<a href="{{ route('yeti@blog:posts.settings', $Post->id) }}" title="Setting">
		<i class="fa fa-pencil fa-fw"></i>
	</a>

	@if (!$Post->is_published)
		<a href="{{ route('yeti@blog:posts.publish', $Post->id) }}" title="Publish">
			<i class="fa fa-eye"></i>
		</a>
	@else
		<a href="{{ route('yeti@blog:posts.unpublish', $Post->id) }}" title="Hide">
			<i class="fa fa-eye-slash"></i>
		</a>
	@endif

	<a href="{{ route('yeti@blog:posts.delete', $Post->id) }}" data-effect="waiting"  title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('form')
	<div class="editor-container">
		<div class="tab-content" data-effect="full-height" data-height-dec="tabh">
			<textarea id="wysiwyg" name="body">{{ $Post->body }}</textarea>
		</div>
	</div>
@stop
