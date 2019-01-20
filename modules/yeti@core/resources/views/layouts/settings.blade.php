@extends('frame', ['action'
	=> route('yeti@core:layouts.update', $Layout->id)])

@section('actions')
	@parent

	<a href="{{ route('yeti@core:layouts.edit', $Layout->id) }}" title="Content">
		<i class="fa fa-file fa-fw"></i>
	</a>

	<a href="{{ route('yeti@core:layouts.delete', $Layout->id) }}" data-effect="waiting" title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('js')
	@parent

	<script type="text/javascript">
		(function(){
			$(function(){
				$('.table-editable').ctrlTED();
			});
		})(jQuery);
	</script>
@stop

@section('form')
	<div class="form-group">
		<label class="col-lg-2 control-label">Name</label>
		<div class="col-lg-10">
			<input type="text" data-type="name" data-required="true"
				class="bg-focus form-control parsley-validated" name="name" value="{{ $Layout->name }}">
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Metadata</label>
		<div class="col-sm-10">
			<section class="panel">
				<div class="table-responsive">
					<table class="table table-striped m-b-small table-editable">
						<thead>
							<tr>
								<th style="width: 15%">Type</th>
								<th style="width: 30%">Property</th>
								<th>Content</th>
								<th width="112">
									<a href="javascript:void(0)" title="Add New" data-role="create" data-template="tabrowtpl1">
										<i class="fa fa-plus fa-action"></i>
									</a>
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Layout->metas as $Meta)
								@include('yeti@core::layouts.settings.meta', ['Item' => $Meta, 'prefix' => 'meta',
									'action' => 'update', 'canEdit' => true, 'canDelete' => true])
							@endforeach

							@include('yeti@core::layouts.settings.meta', ['id' => 'tabrowtpl1', 'prefix' => 'meta',
								'action' => 'create', 'hidden' => true])
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>

	<div class="form-group">
		<label class="col-lg-2 control-label">Externals</label>
		<div class="col-sm-10">
			<section class="panel">
				<div class="table-responsive">
					<table class="table table-striped m-b-small table-editable">
						<thead>
							<tr>
								<th style="width: 15%">Type</th>
								<th>Link</th>
								<th width="112">
									<a href="javascript:void(0)" title="Add New" data-role="create" data-template="tabrowtpl2">
										<i class="fa fa-plus fa-action"></i>
									</a>
								</th>
							</tr>
						</thead>
						<tbody>
							@foreach($Layout->externals as $External)
								@include('yeti@core::layouts.settings.external', ['Item' => $External, 'prefix' => 'external',
									'action' => 'update', 'canEdit' => true, 'canDelete' => true])
							@endforeach

							@include('yeti@core::layouts.settings.external', ['id' => 'tabrowtpl2', 'prefix' => 'external',
								'action' => 'create', 'hidden' => true])
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
@stop
