@extends('yeti@blog::main')

@section('actions')
	@parent

	<a href="{{ route('yeti@blog:topics.add') }}" title="Create New Post">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">
		<div class="table-responsive">

			<table class="table table-striped datagrid m-b-small">
				<thead>
					<tr>
						<th class="th-sortable" width="30%" data-toggle="class">Url</th>
						<th>Title & Description</th>
						<th width="112"></th>
					</tr>
				</thead>
				<tbody>

					@foreach($Topics as $Topic)
						<tr>
							@if (!empty($Topic->url))
								<td>
									/{{ ltrim($Topic->url, '/') }}
								</td>
							@else
								<td>~</td>
							@endif

							<td>
								<h4>{{ Str::trf($Topic->title, 72) }}</h4>

								<p>
									{{ Str::strip($Topic->description) }}
								</p>
							</td>

							<td>
								<a class="toolbtn" href="{{ route('yeti@blog:topics.edit', $Topic->id) }}">
									<i class="fa fa-pencil"></i>
								</a>
								@if ($Topic->posts_count < 1)
									<a class="toolbtn" href="{{ route('yeti@blog:topics.delete', $Topic->id) }}">
										<i class="fa fa-trash"></i>
									</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</section>
@stop



@section('footer')
	{!! $Topics->render() !!}
@stop
