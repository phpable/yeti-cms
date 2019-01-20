<?php
$Items = \Yeti\Blog\Model\Tag::orderBy('title')
	->limit(30)->get();
?>
@foreach($Items as $Item)
	<a title="{{ $Item->weigth }} item{{ $Item->weigth > 1 ? 's' : null }} tagged with British Education" style="font-size:75%"
		href="{{ pagelink('blog-tag', [$Item->url]) }}">{{ $Item->title }}</a>
@endforeach
