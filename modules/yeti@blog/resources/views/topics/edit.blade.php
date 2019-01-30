@extends('yeti@blog::frame', [
	'action' => isset($Post) ? route('yeti@blog:topics.update', $Topic->id) : route('yeti@blog:topics.save'),
	'scrollable' => true])

@object($Topic, 'id', 'url', 'title', 'description')

@section('actions')
	@parent

	@if (!empty($Topic->id))
		<a href="{{ route('yeti@blog:topics.delete', $Topic->id) }}" data-effect="waiting"  title="Delete">
			<i class="fa fa-trash fa-fw"></i>
		</a>
	@endif
@stop

@section('form')
	<div class="col-lg-12">
		<div class="row">
			<div class="separated">
				<div class="form-group">
					<label class="control-label">Url</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control parsley-validated" name="url" value="{{ $Topic->url }}">
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label">Title</label>
				<div class="control-body">
					<input type="text" class="bg-focus form-control parsley-validated" name="title" value="{{ $Topic->title }}">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label double">Description</label>
				<div class="control-body">
					<textarea data-rangelength="[20,200]" data-trigger="keyup"
						class="form-control parsley-validated" rows="4" name="description">{{ $Topic->description }}</textarea>
				</div>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
@stop
