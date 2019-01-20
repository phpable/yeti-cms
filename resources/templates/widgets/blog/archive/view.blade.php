<?php
$Items = \Yeti\Blog\Model\Post::all();
?>

<ul class="level0">
	@foreach (array_unique($Items->lists('year')->toArray()) as $year)
		@foreach(array_unique($Items->where('year', $year)->lists('month')->toArray()) as $month)
			<li>
				<a href="{{ pagelink('blog-archive', [$year, $month]) }}">
					<span class="catTitle">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</span>
					<span class="catCounter"> ({{ $Items->where('year', $year)->where('month', $month)->count() }})</span>
				</a>
			</li>
		@endforeach
	@endforeach
</ul>
