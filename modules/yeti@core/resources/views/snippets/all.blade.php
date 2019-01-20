@extends('yeti@core::main', ['scrollable' => true])

@param($filter)

@section('actions')
	@parent

	<a href="{{ route('yeti@core:snippets.create') }}" title="Add Snippet">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">
		@include('filter', ['url' => route('yeti@core:snippets.all'), 'filter' => $filter])

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
					@foreach($Snippets as $Snippet)
						<tr>

							<td>{{ $Snippet->name }}</td>

							<td>
								<a href="{{ route('yeti@core:snippets.edit', $Snippet->id) }}">
									<i class="fa fa-pencil fa-action"></i>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<footer class="panel-footer">
			<div class="row">
				<div class="col-sm-12 text-right text-center-sm">
				</div>
			</div>
		</footer>

	</section>
@stop

@section('footer')
	{!! $Snippets->render() !!}
@stop
