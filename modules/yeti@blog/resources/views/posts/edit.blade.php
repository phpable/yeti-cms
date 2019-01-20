@extends('yeti@blog::main')

@section('actions')
	@parent

	<a href="#" id="button-entity-save" data-action="submit" data-target="post-edit-form" title="Save">
		<i class="fa fa-save fa-fw"></i>
	</a>

	<a href="{{ route('yeti@blog:posts.settings', $Post->id) }}" title="Setting">
		<i class="fa fa-pencil fa-fw"></i>
	</a>
@stop

@section('workspace')
	<form id="post-edit-form" method="post" action="{{ route('yeti@blog:posts.update', $Post->id) }}" class="form-horizontal panel">
		{!! csrf_field() !!}

		<div class="editor-container">
			<div class="tab-content" data-effect="full-height" data-height-dec="tabh">
				@include('ide.editor', ['name' => 'body', 'value' => $Post->body,
						'mode' => 'html', 'action' => 'update', 'active' => 'true'])

			</div>
		</div>
	</form>
@stop
