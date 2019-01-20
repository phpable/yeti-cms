@extends('yeti@core::main', ['scrollable' => true])

@section('actions')
	@parent

	<a href="{{ route('yeti@core:constants.create') }}" title="Add Constant">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">

		<div class="table-responsive">
			<table class="table table-striped m-b-small">
				<thead>
					<tr>
						<th class="th-sortable" width="222px" data-toggle="class">Name</th>
						<th>Value</th>

						<th width="112"></th>
					</tr>
				</thead>
				<tbody>
					@foreach($Constants as $Constant)
						<tr>

							<td>${{ $Constant->name }}</td>

							<td title="{{ $Constant->value }}">{{ $Constant->value }}</td>

							<td>
								<a href="{{ route('yeti@core:constants.edit', $Constant->id) }}" title="edit">
									<i class="fa fa-pencil"></i>
								</a>
								&nbsp;&nbsp;&nbsp;
								<a href="{{ route('yeti@core:constants.delete', $Constant->id) }}" title="delete">
									<i class="fa fa-trash"></i>
								</a>
							</td>

						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
@stop

@section('footer')
	{!! $Constants->render() !!}
@stop
