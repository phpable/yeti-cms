@extends('frame', ['action'
	=> route('yeti@core:snippets.update', $Snippet->id)])

@section('actions')
	@parent

	<a href="{{ route('yeti@core:snippets.edit', $Snippet->id) }}" title="Content">
		<i class="fa fa-file fa-fw"></i>
	</a>

	<a href="{{ route('yeti@core:snippets.delete', $Snippet->id) }}" data-effect="waiting" title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('form')
	<div class="form-group">
		<label class="col-lg-2 control-label">Name</label>
		<div class="col-lg-10">
			<input type="text" data-type="name" data-required="true"
				class="bg-focus form-control parsley-validated" name="name" value="{{ $Snippet->name }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Params</label>
		<div class="col-lg-10">
			<input type="text" data-type="params" data-required="false"
				class="bg-focus form-control parsley-validated" name="params" value="{{ $Snippet->params }}">
		</div>
	</div>
@stop
