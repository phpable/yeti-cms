@extends('workspace', ['footer' => isset($footer) ? $footer : true])

@section('fonts')
	@parent

	<link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
@stop

@section('js')
	@parent

	<script type="text/javascript">
		(function($) {
			$(function () {
				$('[data-control="jselect"]').editableSelect();

				$('input[data-control="jtags"]').each(function () {
					var jThis = $(this);

					var jTagsLimit = parseInt(jThis.data('jtags-limit'));
					if (!isNaN(jTagsLimit) || jTagsLimit < 1 || jTagsLimit > 99) {
						jTagsLimit = 99;
					}

					var jTagsSuggestions = [];
					if (jThis.is('[data-jtags-suggestions]')){
						jTagsSuggestions = jThis.data('jtags-suggestions').split(/\s*,+\s*/);
					}

					jTagsSuggestions = ['aaaa', 'bbbbb'];
					jThis.amsifySuggestags({
						tagLimit: jTagsLimit,
						suggestions: jTagsSuggestions,
						defaultTagClass: 'amsify-suggestags-badge'
					});
				});
			});
		})(jQuery);
	</script>
@stop

@section('tools')
	<a href="{{ route('yeti@blog:authors.all') }}" title="Authors">
		<i class="fa fa-users"></i>
	</a>

	<a href="{{ route('yeti@blog:topics.all') }}" title="Topics">
		<i class="fa fa-list"></i>
	</a>

	<a href="{{ route('yeti@blog:posts.all') }}" title="Posts">
		<i class="fa fa-file"></i>
	</a>
@stop
