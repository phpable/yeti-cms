@extends('yeti@blog::main')

@object($Topic, 'url', 'title', 'description')

@section('actions')
	@parent

	<a href="#" id="button-entity-save" data-action="submit" data-target="post-edit-form" title="Save">
		<i class="fa fa-save fa-fw"></i>
	</a>

	@if (isset($Topic->id))
		<a href="{{ route('yeti@blog:topics.edit', $Topic->id) }}" title="Save">
			<i class="fa fa-file fa-fw"></i>
		</a>
	@endif

@stop

@section('workspace')
	<form id="post-edit-form" method="post" action="{{ isset($Topic->id) ? route('yeti@blog:topics.update', $Topic->id) : route('yeti@blog:topics.save') }}" class="form-horizontal panel">
		{!! csrf_field() !!}

			<div class="form-group">
				<label class="col-lg-1 control-label">Url</label>
				<div class="col-lg-11">
					<input type="text" class="bg-focus form-control parsley-validated" name="url" value="{{ $Topic->url }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-1 control-label">Title</label>
				<div class="col-lg-11">
					<input type="text" class="bg-focus form-control parsley-validated" name="title" value="{{ $Topic->title }}">
				</div>
			</div>

			<div class="form-group">
				<label class="col-lg-1 control-label">Description</label>
				<div class="col-lg-11">
					<textarea data-rangelength="[20,200]" data-trigger="keyup"
						class="form-control parsley-validated" rows="4" name="description">{{ $Topic->description }}</textarea>
				</div>
			</div>
	</form>
@stop
