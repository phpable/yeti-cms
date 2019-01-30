@extends('yeti@blog::main', ['scrollable' => true])

@param($filter)

@section('actions')
	@parent

	<a href="{{ route('yeti@blog:topics.add') }}" title="Add Topic">
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
			padding: 0 10px;
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

		.posts-list .post > p,
		.posts-list .post > .empty {
			display: block;
			font-size: 12px;
			margin-bottom: 22px;
		}

		.posts-list .post > .empty {
			color: #cecece;
			font-style: italic;
			font-family: "DejaVu Sans Mono", monospace;
			font-size: 10px;
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
	@include('filter', ['url' => route('yeti@blog:topics.all'), 'filter' => $filter])

	<div class="posts-list">
		@foreach($Topics as $Topic)
			<div class="post" data-action="follow" data-target="{{ route('yeti@blog:topics.edit', $Topic->id) }}">
				<span class="post-field">
					<strong>url</strong>

					@if (!empty($Topic->url))
						/{{ ltrim($Topic->url, '/') }}
					@else
						<span class="empty">~empty~</span>
					@endif
				</span>

				<span class="post-field">
					<strong>Related</strong>
					{{ $Topic->posts_count }}&nbsp;Post{{ $Topic->posts_count > 1 ? 's' : ''  }}
				</span>

				<span class="post-title">{{ $Topic->title }}</span>

				@if (!empty($Topic->description))
					<p>
						{{ Str::strip($Topic->description) }}
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
	{!! $Topics->render() !!}
@stop
