@extends('workspace')

@section('tools')
	<li>
		<a href="{{ route('yeti@blog:posts.all') }}" title="Posts">
			<i class="fa fa-file"></i>
		</a>
	</li>

	<li>
		<a href="{{ route('yeti@blog:topics.all') }}" title="Topics">
			<i class="fa fa-list"></i>
		</a>
	</li>
@stop
