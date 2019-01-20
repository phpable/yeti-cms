@extends('yeti@blog::main')

@object($Post, 'topic_id', 'url', 'title', 'preview', 'description')

@section('actions')
	@parent

	<a href="#" id="button-entity-save" data-action="submit" data-target="post-edit-form" title="Save">
		<i class="fa fa-check fa-fw"></i>
	</a>

	@if (isset($Post->id))
		<a href="{{ route('yeti@blog:posts.edit', $Post->id) }}" title="Save">
			<i class="fa fa-save fa-fw"></i>
		</a>
	@endif

@stop

@section('workspace')
	<form id="post-edit-form" method="post" action="{{ isset($Post->id) ? route('yeti@blog:posts.update', $Post->id) : route('yeti@blog:posts.save') }}" class="form-horizontal panel">
		{!! csrf_field() !!}

		<div class="form-group">
			<label class="col-lg-1 control-label">Topic</label>
			<div class="col-lg-5">
				<select class="form-control" name="topic_id">
					<option value=""></option>
					@foreach($Topics as $Topic)
						<option value="{{ $Topic->id }}" {{ $Post->topic_id == $Topic->id ? 'selected' : null }}>{{ $Topic->title }}</option>
					@endforeach
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-1 control-label">Url</label>
			<div class="col-lg-11">
				<input type="text" class="bg-focus form-control parsley-validated" name="url" value="{{ $Post->url }}">
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-1 control-label">Title</label>
			<div class="col-lg-11">
				<input type="text" class="bg-focus form-control parsley-validated" name="title" value="{{ $Post->title }}">
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-1 control-label">Preview</label>
			<div class="col-lg-11">
				<textarea data-rangelength="[20,200]" data-trigger="keyup"
					class="form-control parsley-validated" rows="4"  name="preview">{{ $Post->preview }}</textarea>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-1 control-label">Description</label>
			<div class="col-lg-11">
				<textarea data-rangelength="[20,200]" data-trigger="keyup"
					class="form-control parsley-validated" rows="4"  name="description">{{ $Post->description }}</textarea>
			</div>
		</div>
	</form>
@stop
