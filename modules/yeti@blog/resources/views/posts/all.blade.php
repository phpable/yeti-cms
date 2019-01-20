@extends('yeti@blog::main')

@param($filter)

@section('actions')
	@parent

	<a href="{{ route('yeti@blog:posts.add') }}" title="Create New Post">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">
		<div class="table-responsive">
			@include('filter', ['url' => route('yeti@core:pages.all'), 'filter' => $filter])

			<table class="table table-striped datagrid m-b-small">
				<thead>
					<tr>
						<th>Preview</th>
						<th width="112"></th>
					</tr>
				</thead>

				<tbody>
					@foreach($Posts as $Post)
						<tr>
							<td>
								<div class="post-preview">
									<div class="header">

										@if (!empty($Post->url))
											<span>
												/{{ ltrim($Post->url, '/') }}
											</span>
										@else
											<span>~</span>
										@endif

										<h4>{{ Str::trf($Post->title, 72) }}</h4>
									</div>

									<p>
										{{ Str::strip($Post->preview) }}
									</p>
								</div>
							</td>

							<td>
								<a href="{{ route('yeti@blog:posts.edit', $Post->id) }}">
									<i class="fa fa-pencil"></i>
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
	{!! $Posts->render() !!}
@stop
