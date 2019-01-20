@extends('frame', ['action'
	=> route('yeti@core:pages.update', $Page->id), 'scrollable' => true])

@param($Preview, $Page)

@section('actions')
	@parent

	<a href="{{ route('yeti@core:pages.edit', $Page->id) }}" title="Content">
		<i class="fa fa-file fa-fw"></i>
	</a>

	<a href="{{ route('yeti@core:pages.delete', $Page->id) }}" data-effect="waiting"  title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('js')
	@parent

	<script type="text/javascript">
		(function(){
			var __VALUES = eval("[" + base64.decode('{{ base64_encode(json_encode($Templates)) }}') + "]").pop();

			$(function(){
				var jRelative = $('select[name="template"]');

				if (jRelative.length > 0) {
					jRelative. attr('disabled', true);

					$('select[name="layout"]').on('change', function () {
						var jThis = $(this);

						jRelative.empty();
						if (__VALUES[jThis.val()] !== undefined){
							for(var i in __VALUES[jThis.val()]){
								jRelative.append($('<option value="' +  i + '">' + __VALUES[jThis.val()][i] + '</option>'));
							}

							if (jRelative.is('[data-selected]')
								&& __VALUES[jThis.val()][jRelative.data('selected')] !== undefined){
									jRelative.val(jRelative.data('selected'));
							}

							jRelative.removeAttr('disabled');
						}
					}).trigger('change');
				}
			});
		})(jQuery);
	</script>

	<script type="text/javascript">
		(function(){
			$(function() {
				$('[name="builder"]').on('change', function(){
					$('.builder-arguments').hide().filter('#builder-'
						+ $(this).val() + '-arguments').show();
				}).trigger('change');
			});
		})(jQuery);
	</script>
@stop

@section('form')
	<div class="form-group">
		<label class="col-lg-2 control-label">Name</label>
		<div class="col-lg-10">
			<input type="text" data-type="name" data-required="true"
				class="bg-focus form-control parsley-validated" name="name" value="{{ $Page->name }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Layout</label>
		<div class="col-lg-10">
			<select class="form-control" name="layout">
				<option value="">~</option>
				@foreach($Layouts as $Layout)
					<option value="{{ $Layout->id }}" {{ $Page->layout_id == $Layout->id ? 'selected' : null }}>{{ $Layout->name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Template</label>
		<div class="col-lg-10">
			<select class="form-control" name="template" @if (!empty($Page->template_id)) data-selected="{{ $Page->template_id }}" @endif>
			</select>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Url</label>
		<div class="col-lg-10">
			<input type="text" data-type="name" data-required="true"
				class="bg-focus form-control parsley-validated" name="url" value="{{ $Page->url }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Title</label>
		<div class="col-lg-10">
			<input type="text" data-type="title" data-required="true"
				class="bg-focus form-control parsley-validated" name="title" value="{{ $Page->title }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Description</label>
		<div class="col-lg-10">
			<textarea data-rangelength="[20,200]" data-trigger="keyup"
				class="form-control parsley-validated" rows="1" name="description">{{ $Page->description }}</textarea>
		</div>
	</div>

	<div class="form-group-separated">
		<div class="form-group">
			<label class="col-lg-2 control-label">Access</label>
			<div class="col-lg-5">
				<div class="radio">
					<label class="radio-custom">
						<input type="radio" {{ $Page->mode == 'auth' ? 'checked="checked"' : null }} name="mode" value="auth">
						<i class="fa fa-circle-o"></i>
						Only members
					</label>
					&nbsp;&nbsp;&nbsp;
					<label class="radio-custom">
						<input type="radio" {{ $Page->mode == 'guest' ? 'checked="checked"' : null }} name="mode" value="guest">
						<i class="fa fa-circle-o"></i>
						Only guests
					</label>
					&nbsp;&nbsp;&nbsp;
					<label class="radio-custom">
						<input type="radio" {{ $Page->mode == 'regular' ? 'checked="checked"' : null }} name="mode" value="regular">
						<i class="fa fa-circle-o"></i>
						Any user
					</label>
				</div>
			</div>
		</div>

		<div class="form-group">
			<label class="col-lg-2 control-label">Options</label>
			<div class="col-lg-10">
				<div class="checkbox">
					<label class="checkbox-custom">
						<input type="checkbox" {{ $Page->in_sitemap ? 'checked="checked"' : null }} name="in_sitemap" value="1">
						<i class="fa fa-check-square-o"></i>
						In sitemap?
					</label>
				</div>
			</div>
		</div>
	</div>

	<div class="form-group-separated">
		<div class="form-group">
			<label class="col-lg-2 control-label">Builder</label>
			<div class="col-lg-10">
				<select class="form-control" name="builder">
					@foreach(builders() as $builder)
						<option value="{{ $builder }}" {{ $Page->builder == $builder ? 'selected' : null }}>{{ ucfirst($builder) }}</option>
					@endforeach
				</select>
			</div>
		</div>

		@foreach(builders() as $builder)
			@if (!in_array($builder, ['standard', 'paginate', 'archive']))
				<div class="builder-arguments" id="builder-{{ $builder }}-arguments" style="display: none;">
					@include('yeti@core::pages.builders.' . $builder, ['Arguments' => $Page->arguments])
				</div>
			@endif
		@endforeach
	</div>
@stop
