@extends('yeti@blog::main', [
	'scrollable' => true,
])

@param($filter)

@section('actions')
	@parent

	<a href="{{ route('yeti@blog:authors.add') }}" title="Add Topic">
		<i class="fa fa-plus"></i>
	</a>
@stop


@section('css')
	@parent

	<style type="text/css">
		.authors-list {
			display: block;
			background-color: #ffffff;
		}

		.authors-list .author{
			display: block;
			position: relative;
			overflow: hidden;
			background-color: transparent;
			padding: 12px 10px 0 10px;
			border-top: 1px solid #eaedef;
		}

		.authors-list .post:nth-child(2n+1) {
			background-color: #fcfdfe;
		}

		.authors-list .author > .author-field {
			font-family: "DejaVu Sans Mono", monospace;
			font-size: 10px;
			display: block;
			height: 22px;
			line-height: 22px;
			margin-left: 72px;
		}

		.authors-list .author > .author-photo {
			display: block;
			width: 44px;
			height: 44px;
			float: left;
			margin: 0;
			border: 1px solid #656565;
		}

		.authors-list .author > .author-field > strong {
			display: block;
			float: left;
			width: 92px;
			font-weight: bold;
			font-size: 12px;
			text-transform: uppercase;
			text-align: left;
		}

		.authors-list .author > .author-field > strong::after {
			content: ":";
		}

		.authors-list .author > .author-title {
			display: block;
			margin: 22px 0 37px 0;
			height: 30px;
			word-wrap: break-word;
			overflow: hidden;
			text-overflow: ellipsis;
			font-size: 22px;
			font-weight: bold;
		}

		.authors-list .author > p,
		.authors-list .author > .empty {
			display: block;
			font-size: 12px;
			margin-bottom: 22px;
		}

		.authors-list .author > .empty {
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
	<div class="panel">
		@include('filter', ['url' => route('yeti@blog:authors.all'), 'filter' => $filter])

		<div class="authors-list">
			@foreach($Authors as $Author)
				<div class="author" data-action="follow" data-target="{{ route('yeti@blog:authors.edit', $Author->id) }}">
					<img class="author-photo" src="/author/{{ $Author->photo }}" alt="{{ $Author->name }}" />

					<span class="author-field">
						<strong>url</strong>

						@if (!empty($Author->url))
							/{{ ltrim($Author->url, '/') }}
						@else
							<span class="empty">~empty~</span>
						@endif
					</span>

					<span class="author-field">
						<strong>Related</strong>
						{{ $Author->posts_count }}&nbsp;Post{{ $Author->posts_count > 1 ? 's' : ''  }}
					</span>

					<span class="author-title">{{ $Author->name }}</span>

					@if (!empty($Author->info))
						<p>
							{!! Str::strip($Author->info) !!}
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
	{!! $Authors->render() !!}
@stop
