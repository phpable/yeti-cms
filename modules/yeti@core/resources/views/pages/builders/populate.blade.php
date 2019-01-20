<div class="form-group">
	<label class="col-lg-2 control-label">Provider</label>
	<div class="col-lg-10">
		<select class="form-control" name="arguments[provider]">
			@foreach(providers() as $provider)
				<option value="{{ $provider }}" {{ isset($Arguments['provider']) &&  $Arguments['provider'] == $provider ? 'selected' : null  }}>{{ ucfirst($provider) }}</option>
			@endforeach
		</select>
	</div>
</div>
