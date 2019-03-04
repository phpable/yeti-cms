@param($id, md5(microtime(true)))
@param($index, 0)


<div class="form-group share-group" id="{{ $id }}">
	<div class="col-lg-3">
		<div class="row">
			<a class="btn btn-delete btn-prepend btn-flat" data-action="delete-object" data-target="{{ $id }}">
				<i class="fa fa-remove"></i>
			</a>

			<div class="short-label control-body">
				<input type="text" data-type="name" data-required="true"
					class="bg-focus form-control parsley-validated" name="objects[{{ $index }}][alias]" value="{{ !empty($Object['alias']) ? $Object['alias'] : null }}">
			</div>
		</div>
	</div>

	<div class="col-lg-2">
		<div class="row">
			<label class="control-label short">:</label>
			<div class="control-body">
				<select class="form-control" name="objects[{{ $index }}][type]">
					@foreach(entrances() as $value => $title)
						<option value="{{ $value }}" {{ !empty($Object['type']) &&  $Object['type'] == $value ? 'selected' : null  }}>{{ $title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>

	<div class="col-lg-2">
		<div class="row">
			<label class="control-label short">Of</label>
			<div class="control-body">
				<select class="form-control" name="objects[{{ $index }}][item]">
					<option value="" {{ empty($Object['item']) ? 'selected' : null}}>~</option>

					@foreach(share() as $value => $title)
						<option value="{{ $value }}" {{ !empty($Object['item']) &&  $Object['item'] == $value ? 'selected' : null  }}>{{ $title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>

	{{--
	<div class="col-lg-2">
		<div class="row">
			<label class="control-label short">By</label>
			<div class="control-body">
				<select class="form-control" name="objects[{{ $index }}][target]">
					<option value="" {{ empty($Object['target']) ? 'selected' : null}}>~</option>

					@foreach(targets() as $value => $title)
						<option value="{{ $value }}" {{ !empty($Object['target']) &&  $Object['target'] == $value ? 'selected' : null  }}>{{ $title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	--}}

	<div class="col-lg-5">
		<div class="row">
			<label class="control-label short">=</label>
			<div class="control-body">
				<input type="text" data-type="name" data-required="true"
					class="bg-focus form-control parsley-validated" name="objects[{{ $index }}][value]" value="{{ !empty($Object['value']) ? $Object['value'] : null }}">
			</div>
		</div>
	</div>
</div>
