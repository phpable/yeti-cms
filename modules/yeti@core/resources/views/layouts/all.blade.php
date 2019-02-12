@extends('yeti@core::main', [
	'scrollable' => true,
	'footer' => false
])

@section('actions')
	@parent

	<a href="{{ route('yeti@core:layouts.create') }}" title="Add Layout">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">

		<div class="table-responsive">
			<table class="table table-striped m-b-small">
				<thead>
					<tr>
						<th class="th-sortable" data-toggle="class">Name</th>

						<th width="112">
							<i class="fa fa-hand-paper-o fa-action"></i>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($Layouts as $Layout)
						<tr>
							<td>{{ $Layout->name }}</td>

							<td>
								<a href="{{ route('yeti@core:layouts.edit', $Layout->id) }}" title="edit">
									<i class="fa fa-pencil fa-action"></i>
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
	{!! $Layouts->render() !!}
@stop
