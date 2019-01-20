@extends('main')

@param($scrollable, false)
@param($footer, true)

@section('main')
	<header id="header" class="navbar">
		<div class="row">
			<div class="col-sm-4">
				<ul class="nav navbar-nav navbar-actions project-tools">
					<li>
						@yield('actions')
					</li>
				</ul>
			</div>

			<div class="col-sm-8">
				<ul class="nav navbar-nav navbar-avatar pull-right main-menu">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<div class="user-cnt">
								<div class="user-info">
									<span class="user-name">{{ $Viewer->name }}</span>
									<em class="user-role">owner</em>
								</div>

								<span class="thumb-small avatar inline">
									<img src="/images/yeti72.png" alt="{{ $Viewer->name }}" class="img-circle">
								</span>
							</div>
						</a>

						<ul class="dropdown-menu pull-right">
							@if (App::scopable())
								@if (count(App::modules()) > 1)
									@foreach(App::modules() as $Module)
										<li><a href="{{ route($Module->route) }}">{{  $Module->title }}</a></li>
									@endforeach

									<li class="divider"></li>
								@endif
							@endif

							<li><a href="/auth/logout">Logout</a></li>
						</ul>
					</li>
				</ul>

				@if (App::scopable())
					<ul class="nav navbar-nav project-tools pull-right">
						<li class="empty-after">
							@yield('commands')

							<a href="{{ preview(isset($Preview) ? $Preview : null) }}" target="_blank" title="Preview">
								<i class="fa fa-eye"></i>
							</a>

							<a href="javascript:void(0);" data-href="{{ route('yeti@main:deploy') }}" id="btn-deploy" title="Deploy">
								<i class="fa fa-heart"></i>
							</a>
						</li>

						<li>
							@yield('options')
						</li>
					</ul>
				@endif
			</div>
		</div>

		<div id="progressbar" class="progress progress-mini m-t-mini m-b-none">
			<div class="progress-bar progress-bar-deploy" style="display: none"></div>
			<div class="progress-bar progress-bar-build" style="display: none;"></div>
		</div>
	</header>

	<nav id="nav" class="nav-primary hidden-xs nav-vertical">
		<ul class="nav affix-top" data-spy="affix">

			<li>
				<a href="{{ route('yeti@main:dashboard') }}" title="Projects">
					<i class="fa fa-th"></i>
				</a>
			</li>

			@if (!App::scopable())
				<li>
					<a href="{{ route('yeti@main:configuration') }}" title="Configuration">
						<i class="fa fa-wrench"></i>
					</a>
				</li>
			@endif

			@if (App::scopable())
				@yield('tools')
			@endif
		</ul>
	</nav>

	<section id="content" data-effect="full-height full-width" data-height-dec="header footer" data-width-dec="nav" @if($scrollable) class="scroll-container" @endif>
		@yield('workspace')
	</section>

	@if ($footer)
		<footer id="footer" class="navbar-fixed-bottom">
			@yield('footer')
		</footer>
	@endif
@stop
