@extends('yeti@blog::main', ['scrollable' => true])

@param($filter)

@section('actions')
	@parent

	<a href="{{ route('yeti@blog:posts.add') }}" title="Create New Post">
		<i class="fa fa-plus"></i>
	</a>
@stop

@section('css')
	@parent

		<style type="text/css">
		.posts-list {
			display: block;
			background-color: #ffffff;
		}

		.posts-list .post {
			display: block;
			position: relative;
			overflow: hidden;
			background-color: transparent;
			padding: 12px 10px 0 10px;
			border-top: 1px solid #eaedef;
		}

		.posts-list .post:nth-child(2n+1) {
			background-color: #fcfdfe;
		}

		.posts-list .post > .post-field {
			font-family: "DejaVu Sans Mono", monospace;
			font-size: 10px;
			display: block;
			height: 22px;
			line-height: 22px;
		}

		.posts-list .post > .post-field > strong {
			display: block;
			float: left;
			width: 92px;
			font-weight: bold;
			font-size: 12px;
			text-transform: uppercase;
			text-align: left;
		}

		.posts-list .post > .post-field > strong::after {
			content: ":";
		}

		.posts-list .post > .post-title {
			display: block;
			margin: 22px 0 37px 0;
			height: 30px;
			word-wrap: break-word;
			overflow: hidden;
			text-overflow: ellipsis;
			font-size: 22px;
			font-weight: bold;
		}

		.posts-list .post > p {
			font-size: 12px;
			margin-bottom: 22px;
		}

	</style>
@stop

@section('js')
	<script type="text/javascript">
		(function($){
			$(function(){
				$('[data-action="follow"]').on('click', function(){
					var jThis = $(this);

					if (jThis.is('[data-target]')) {
						window.location.href = jThis.data('target');
					}
				});
			});
		})(jQuery);
	</script>
@stop

@section('workspace')
			@include('filter', ['url' => route('yeti@blog:posts.all'), 'filter' => $filter])

			<div class="posts-list">
				@foreach($Posts as $Post)
					<div class="post" data-action="follow" data-target="{{ route('yeti@blog:posts.edit', $Post->id) }}">
						<span class="post-field">
							<strong>URL</strong>

							@if (!empty($Post->url))
								/{{ ltrim($Post->url, '/') }}
							@else
								<span class="empty">~empty~</span>
							@endif
						</span>

						<span class="post-field">
							<strong>Created</strong>
							{{ date('M j, Y', strtotime($Post->created_at)) }}
						</span>

						<span class="post-field">
							<strong>Aurhor</strong>
							@if (!empty($Post->author))
								{{ $Post->author->name }}
							@else
								<span class="empty">~empty~</span>
							@endif
						</span>

						<span class="post-field">
							<strong>published</strong>

							@if (!empty($Post->is_published))
								Yes
							@else
								No
							@endif
						</span>

						<span class="post-title">{{ $Post->title }}</span>

						@if (!empty($Post->preview))
							<p>
								{{ Str::strip($Post->preview) }}
							</p>
						@else
							<span class="empty">~empty~</span>
						@endif
					</div>
				@endforeach
			</div>
		</div>
@stop



@section('footer')
	{!! $Posts->render() !!}
@stop
