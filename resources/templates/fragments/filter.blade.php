@param($url)
@param($filter)


@if (isset($url))
	<ul class="filterbar" id="filterbar">
		<li>
			<a class="btn btn-sm @if(is_null($filter)) active @endif" href="{{ $url }}?filter=*">*</a>
		</li>

		@foreach(range('a', 'z') as $letter)
			<li>
				<a class="btn btn-sm @if ($filter == $letter) active @endif" href="{{ $url }}?filter={{ $letter }}">
					{{ $letter }}
				</a>
			</li>
		@endforeach
	</ul>
@endif

