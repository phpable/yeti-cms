@extends('yeti@blog::frame', [
	'action' => route('yeti@blog:posts.update', $Post->id),
	'footer' => true])

@section('css')
	@parent

	<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
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
			@include('yeti@blog[components]::summernote', ['text' => $Post->body,
				'name' => 'body', 'url' => route('yeti@main:files.upload')])
		</div>
	</div>
@stop
