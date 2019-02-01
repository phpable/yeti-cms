@extends('yeti@blog::frame', [
	'action' => !empty($Post->id)
		? route('yeti@blog:posts.update', $Post->id)
		: route('yeti@blog:posts.save'),
])

@object($Post, 'id', 'url', 'title',
	'description', 'preview', 'body', 'is_published', 'topic_id', 'author_id');

@section('actions')
	@parent

	@if(!empty($Post->id))
		<a href="{{ route('yeti@blog:posts.edit', $Post->id) }}" title="Content">
			<i class="fa fa-file fa-fw"></i>
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
	@endif
@stop

@section('form')
	<div class="col-lg-12">
		<div class="row">
			<div class="separated">
				<div class="form-group required">
					<label class="control-label">Url</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control" name="url" value="{{ $Post->url }}">
					</div>
				</div>
			</div>

			<div class="separated">
				<div class="form-group">
					<label class="control-label">Title</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control" name="title" value="{{ $Post->title }}">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label double">Description</label>
					<div class="control-body">
						<textarea data-rangelength="[20,200]" data-trigger="keyup"
							class="form-control parsley-validated" rows="4"  name="description">{{ $Post->description }}</textarea>
					</div>
				</div>
			</div>

			<div class="form-group required">
				<label class="control-label">Topic</label>
				<div class="control-body">
					<select class="form-control" name="topic_id">
						<option value="">~</option>

						@foreach($Topics as $Topic)
							<option value="{{ $Topic->id }}" {{ $Post->topic_id == $Topic->id ? 'selected' : null }}>{{ $Topic->title }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-group required">
				<label class="control-label">Author</label>
				<div class="control-body">
					<select class="form-control" name="author_id">
						<option value="">~</option>

						@foreach($Authors as $Author)
							<option value="{{ $Author->id }}" {{ $Post->author_id == $Author->id ? 'selected' : null }}>{{ $Author->name }}</option>
						@endforeach
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label double">Preview</label>
				<div class="control-body">
					<textarea data-rangelength="[20,200]" data-trigger="keyup"
						class="form-control parsley-validated" rows="4"  name="preview">{{ $Post->preview }}</textarea>
				</div>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
@stop
