@extends('yeti@blog::frame', [
	'action' => isset($Topic->id)
		? route('yeti@blog:topics.update', $Topic->id)
		: route('yeti@blog:topics.save'),

	'scrollable' => true])

@object($Topic, 'id', 'url', 'title', 'meta_title', 'meta_description')

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
				<div class="form-group required">
					<label class="control-label">Url</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control parsley-validated" name="url" value="/{{ $Topic->url }}">
					</div>
				</div>
			</div>

			<div class="separated">
				<div class="form-group">
					<label class="control-label">Title</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control parsley-validated" name="title" value="{{ $Topic->title }}">
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="control-label">[META] Title</label>
				<div class="control-body">
					<input type="text" class="bg-focus form-control" name="meta_title" value="{{ $Topic->meta_title }}">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label double">[META] Desc.</label>
				<div class="control-body">
					<textarea data-rangelength="[20,200]" data-trigger="keyup"
						class="form-control" rows="4" name="meta_description">{{ $Topic->meta_description }}</textarea>
				</div>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
@stop
