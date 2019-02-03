@extends('yeti@blog::frame', [
	'action' => isset($Author->id)
		? route('yeti@blog:topics.update', $Author->id)
		: route('yeti@blog:topics.save'),

	'scrollable' => true])

@object($Author, 'id', 'url', 'title', 'description', 'name', 'photo', 'info')

@section('actions')
	@parent

	@if (!empty($Author->id))
		<a href="{{ route('yeti@blog:topics.delete', $Author->id) }}" data-effect="waiting"  title="Delete">
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
						<input type="text" class="bg-focus form-control" name="url" value="{{ $Author->url }}">
					</div>
				</div>
			</div>

			<div class="separated">
				<div class="form-group required">
					<label class="control-label">Name</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control" name="name" value="{{ $Author->name }}">
					</div>
				</div>
			</div>

			<div class="separated">
				<div class="form-group">
					<label class="control-label">Title</label>
					<div class="control-body">
						<input type="text" class="bg-focus form-control" name="title" value="{{ $Author->title }}">
					</div>
				</div>

				<div class="form-group">
					<label class="control-label double">Description</label>
					<div class="control-body">
						<textarea data-rangelength="[20,200]" data-trigger="keyup"
							class="form-control" rows="4" name="description">{{ $Author->description }}</textarea>
					</div>
				</div>
			</div>

			<div class="separated">
				<div class="form-group">
					<label class="control-label double">Info</label>
					<div class="control-body">
						<textarea data-rangelength="[20,200]" data-trigger="keyup"
							class="form-control" rows="4" name="info">{{ $Author->info }}</textarea>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div class="clearfix"></div>
@stop
