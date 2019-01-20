@extends('yeti@core::main', ['scrollable' => true])

@param($filter)

@section('actions')
	@parent

	<a href="{{ route('yeti@core:pages.create') }}" title="Add Post">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">
		@include('filter', ['url' => route('yeti@core:pages.all'), 'filter' => $filter])

		<div class="table-responsive">
			<table class="table table-striped datagrid m-b-small">
				<thead>
					<tr>
						<th class="th-sortable" width="222px" data-toggle="class">Name</th>
						<th width="222px">Url</th>
						<th>Title</th>

						<th width="32" title="Allow for members?">
							<i class="fa fa-user"></i>
						</th>

						<th width="32" title="Allow for guests?">
							<i class="fa fa-users"></i>
						</th>

						<th width="32" title="In sitemap?">
							<i class="fa fa-file-text"></i>
						</th>

						<th width="32" title="Is visible?">
							<i class="fa fa-eye"></i>
						</th>

						<th width="112">
							<i class="fa fa-hand-paper-o fa-action"></i>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach($Pages as $Page)
						<tr>

							<td>{{ $Page->name }}</td>

							@if (!empty($Page->url))
								<td title="{{ $Page->url }}">
									{{ Url::trf($Page->url, 52) }}
								</td>
							@else
								<td>~</td>
							@endif

							<td title="{{ $Page->title }}">{{ Str::trf($Page->title, 72) }}</td>

							<td>
								@if ($Page->mode != 'guest')
									<i class="fa fa-unlock"></i>
								@else
									<i class="fa fa-lock"></i>
								@endif
							</td>

							<td>
								@if ($Page->mode != 'auth')
									<i class="fa fa-unlock"></i>
								@else
									<i class="fa fa-lock"></i>
								@endif
							</td>

							<td>
								@if ($Page->in_sitemap)
									<i class="fa fa-check"></i>
								@else
									<i class="fa fa-times"></i>
								@endif
							</td>

							<td>
								@if (!$Page->is_hidden)
									<i class="fa fa-check"></i>
								@else
									<i class="fa fa-times"></i>
								@endif
							</td>

							<td>
								<a href="{{ route('yeti@core:pages.edit', $Page->id) }}">
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
	{!! $Pages->render() !!}
@stop
