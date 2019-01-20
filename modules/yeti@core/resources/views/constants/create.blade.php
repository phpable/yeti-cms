@extends('frame', ['action'
	=> route('yeti@core:constants.store')])

@section('form')

	<div class="form-group">
		<label class="col-lg-1 control-label">Name</label>
		<div class="col-lg-11">
			<input type="text" data-type="name" data-required="true"
				class="bg-focus form-control parsley-validated" name="name" value="">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-1 control-label">Value</label>
		<div class="col-lg-11">
			<input type="text" data-type="value" data-required="true"
				class="bg-focus form-control parsley-validated" name="value" value="">
		</div>
	</div>

@stop
