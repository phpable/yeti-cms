<?php
$Categories = \Yeti\Blog\Model\Category::all();
?>

<ul class="level0">
	@foreach($Categories as $Category)
		<li>
			<a href="{{ pagelink('blog-category', [$Category->url]) }}">
				<span class="catTitle">{{ $Category->title }}</span>
				<span class="catCounter"> ({{ $Category->posts_count }})</span>
			</a>
		</li>
	@endforeach
</ul>
