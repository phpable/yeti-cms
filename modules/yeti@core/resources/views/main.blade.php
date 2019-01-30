@extends('workspace')

@section('options')
	@parent

	@if (App::scopable())
		<li>
			<a href="{{ route('yeti@core:constants.all') }}" title="Global Constants">
				<i class="fa fa-th-list fa-fw text-default"></i>
			</a>
		</li>

		<li>
			<a href="{{ route('yeti@core:robots.edit') }}" title="Robots Setting">
				<i class="fa fa-android fa-fw text-default"></i>
			</a>
		</li>

		<li>
			<a href="{{ route('yeti@core:settings.edit') }}" title="Project Settings">
				<i class="fa fa-cog fa-fw text-default"></i>
			</a>
		</li>
	@endif
@stop

@section('tools')
	@parent

	@if (App::scopable())
		<li>
			<a href="{{route('yeti@core:layouts.all') }}" title="Layouts">
				<i class="fa fa-clone"></i>
			</a>
		</li>

		<li>
			<a href="{{ route('yeti@core:pages.all') }}" title="Pages">
				<i class="fa fa-sticky-note fa-lg"></i>
			</a>
		</li>

		<li>
			<a href="{{ route('yeti@core:snippets.all') }}" title="Snippets">
				<i class="fa fa-code"></i>
			</a>
		</li>

		<li>
			<a href="{{ route('yeti@main:files') }}" title="Files">
				<i class="fa fa-folder-open fa-lg"></i>
			</a>
		</li>
	@endif
@stop
