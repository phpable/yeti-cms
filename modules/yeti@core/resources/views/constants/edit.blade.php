@extends('frame', ['action'
	=> route('yeti@core:constants.update', $Constant->id)])

@section('form')
	<div class="col-lg-12">
		<div class="row">
			<div class="form-group">
				<label class="control-label">Name</label>
				<div class="control-body">
					<input type="text" data-type="name" data-required="true"
						class="bg-focus form-control parsley-validated" name="name" value="{{ $Constant->name }}">
				</div>
			</div>

			<div class="form-group">
				<label class="control-label">Value</label>
				<div class="control-body">
					<input type="text" data-type="value" data-required="true"
						class="bg-focus form-control parsley-validated" name="value" value="{{ $Constant->value }}">
				</div>
			</div>
		</div>
	</div>
@stop
