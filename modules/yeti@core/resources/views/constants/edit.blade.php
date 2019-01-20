@extends('frame', ['action'
	=> route('yeti@core:constants.update', $Constant->id)])

@section('form')

	<div class="form-group">
		<label class="col-lg-2 control-label">Name</label>
		<div class="col-lg-10">
			<input type="text" data-type="name" data-required="true"
				class="bg-focus form-control parsley-validated" name="name" value="{{ $Constant->name }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Value</label>
		<div class="col-lg-10">
			<input type="text" data-type="value" data-required="true"
				class="bg-focus form-control parsley-validated" name="value" value="{{ $Constant->value }}">
		</div>
	</div>

@stop
