@extends('frame', ['action'
	=> route('yeti@core:settings.update')])

@section('form')
	<div class="col-lg-12">
		<div class="row">
			<div class="separated">

				<div class="form-group">
					<label class="control-label">Name</label>

					<div class="control-body">
						<input type="text" data-type="name" data-required="true"
							class="bg-focus form-control parsley-validated" name="name" value="{{ $Project->name }}">
					</div>
				</div>

				<div class="separated">
					<div class="form-group">
						<label class="control-label">Domain</label>

						<div class="control-body">
							<input type="text" data-type="name" data-required="true"
								class="bg-focus form-control parsley-validated" name="domain" value="{{ $Project->domain }}">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label">Deploy Path</label>

						<div class="control-body">
							<input type="text" data-type="text" data-required="true"
								class="bg-focus form-control parsley-validated" name="deploy_path" value="{{ $Options['deploy_path'] }}">
						</div>
					</div>
				</div>
			</div>

			<div class="separated">
				<div class="form-group">
					<label class="control-label">Index Page</label>

					<div class="control-body">
						<select class="form-control" name="index_page_id">
							<option value="" @if (empty($Options['index_page_id'])) selected @endif>~ not set ~</option>
							@foreach($Pages as $Page)
								<option value="{{ $Page->id }}" {{ $Page->id == $Options['index_page_id'] ? 'selected' : null }}>{{ $Page->name }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">Login Page</label>

					<div class="control-body">
						<select class="form-control" name="login_page_id">
							<option value="" @if (empty($Options['login_page_id'])) selected @endif>~ not set ~</option>
							@foreach($Pages as $Page)
								<option value="{{ $Page->id }}" {{ $Page->id == $Options['login_page_id'] ? 'selected' : null }}>{{ $Page->name }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="control-label">Error Page</label>

					<div class="control-body">
						<select class="form-control" name="error_page_id">
							<option value="" @if (empty($Options['error_page_id'])) selected @endif>~ not set ~</option>
							@foreach($Pages as $Page)
								<option value="{{ $Page->id }}" {{ $Page->id == $Options['error_page_id'] ? 'selected' : null }}>{{ $Page->name }}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="project_id" value="{{ $Project->id }}">
@stop
