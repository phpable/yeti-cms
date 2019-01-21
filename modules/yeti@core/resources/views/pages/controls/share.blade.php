@param($id, md5(microtime(true)))
@param($index, 0)

@section('js')
	@parent

	<script type="text/javascript">
		(function ($) {
			$(function () {
				$(document).on('click', 'a[data-action="delete-share"]', function () {
					$('#' + $(this).data('target')).remove();
					return false;
				});
			});
		})(jQuery)
	</script>
@stop

<div class="form-group share-group" id="{{ $id }}">
	<div class="col-lg-3">
		<div class="row">
			<label class="control-label">Share</label>
			<div class="control-body">
				<select class="form-control" name="arguments[share][{{ $index }}][type]">
					<option value="" {{ empty($Share['type']) ? 'selected' : null}}>~</option>

					@foreach(entrances() as $value => $title)
						<option value="{{ $value }}" {{ !empty($Share['type']) &&  $Share['type'] == $value ? 'selected' : null  }}>{{ $title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>

	<div class="col-lg-3">
		<div class="row">
			<label class="control-label short">Of</label>
			<div class="control-body">
				<select class="form-control" name="arguments[share][{{ $index }}][item]">
					<option value="" {{ empty($Share['item']) ? 'selected' : null}}>~</option>

					@foreach(share() as $value => $title)
						<option value="{{ $value }}" {{ !empty($Share['item']) &&  $Share['item'] == $value ? 'selected' : null  }}>{{ $title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>

	<div class="col-lg-2">
		<div class="row">
			<label class="control-label short">As</label>
			<div class="control-body">
				<input type="text" data-type="name" data-required="true"
					class="bg-focus form-control parsley-validated" name="arguments[share][{{ $index }}][as]" value="{{ !empty($Share['as']) ? $Share['as'] : null }}">
			</div>
		</div>
	</div>

	<div class="col-lg-3">
		<div class="row">
			<label class="control-label short">By</label>
			<div class="control-body">
				<select class="form-control" name="arguments[share][{{ $index }}][by]">
					<option value="" {{ empty($Share['item']) ? 'selected' : null}}>~</option>

					@foreach(properties() as $value => $title)
						<option value="{{ $value }}" {{ !empty($Share['by']) &&  $Share['by'] == $value ? 'selected' : null  }}>{{ $title }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>

	<div class="col-lg-1">
		<div class="row">
			<a class="btn btn-delete btn-right btn-flat" data-action="delete-share" data-target="{{ $id }}">
				<i class="fa fa-remove"></i>
			</a>
		</div>
	</div>
</div>
