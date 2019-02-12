@extends('yeti@blog::frame', [
	'action' => isset($Author->id)
		? route('yeti@blog:authors.update', $Author->id)
		: route('yeti@blog:authors.save'),

	'scrollable' => false])

@object($Author, 'id', 'url', 'meta_title', 'meta_description', 'name', 'photo', 'info')

@section('actions')
	@parent

	@if (!empty($Author->id))
		<a href="{{ route('yeti@blog:topics.delete', $Author->id) }}" data-effect="waiting"  title="Delete">
			<i class="fa fa-trash fa-fw"></i>
		</a>
	@endif
@stop

@section('js')
	@parent

	<script type="text/javascript">
		(function($){
			$(function(){
				var jPhoto = $('.leftphoto');

 				$(document).on('file-uploading-start', function(jEvent, Data) {
					jPhoto.addClass('uploading');
				});

				$(document).on('file-uploaded', function(jEvent, Data) {
					if (Data.error !== undefined && !Data.error){
						$('#author-photo').val(Data.name);
						$('#author-preview').attr('src', '/author/' + Data.name);
					}

					jPhoto.removeClass('uploading');
				});
			});
		})(jQuery)
	</script>
@stop

@section('form')
	<div class="col-lg-12 blog-author" data-effect="full-height">
		<div class="row" data-effect="full-height">

			<a class="leftphoto" href="javascript:void(0);">
				@if (!empty($Author->photo))
					<img id="author-preview" src="/author/{{ $Author->photo }}" alt="{{ $Author->name }}">
				@else
					<img id="author-preview" src="/images/default-user.png" alt="{{ $Author->name }}">
				@endif

				<input id="author-photo" type="hidden" name="photo" value="{{ $Author->photo }}">

				<div class="cover" id="fileinput-button">
					<i class="fa fa-photo"></i>
				</div>

				<div class="spinner">
					<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
				</div>
			</a>

			<div id="toppanel" class="topinfo">
				<div class="separated">
					<div class="form-group required">
						<label class="control-label">Url</label>
						<div class="control-body">
							<input type="text" class="bg-focus form-control" name="url" value="/{{ $Author->url }}">
						</div>
					</div>
					<div class="form-group required">
						<label class="control-label">Name</label>
						<div class="control-body">
							<input type="text" class="bg-focus form-control" name="name" value="{{ $Author->name }}">
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">[META] Title</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control" name="meta_title" value="{{ $Author->meta_title }}">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label double">[META] Desc.</label>
					<div class="control-body">
						<textarea data-rangelength="[20,200]" data-trigger="keyup"
							class="form-control" rows="4" name="meta_description">{{ $Author->meta_description }}</textarea>
					</div>
				</div>
			</div>

			<div class="bottominfo" data-effect="full-height" data-height-dec="toppanel">
					@include('yeti@blog[components]::summernote', [
						'text' => $Author->info,
						'name' => 'info',
						'type' => 'author',
						'url' => route('yeti@main:files.upload')])
			</div>
		</div>
	</div>

	@include('fileupload', ['target' => 'fileinput-button',
		'url' => route('yeti@main:files.upload'), 'type' => 'author'])
@stop
