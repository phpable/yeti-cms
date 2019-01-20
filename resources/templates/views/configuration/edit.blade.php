@extends('yeti@core::main')

@section('workspace')
	<section class="panel">
		<section class="panel-body scrollbar scroll-y m-b">
			@foreach($Modules as $Module)
				<div class="row module-cnt">
					<div class="col-sm-8">

						<div class="pull-left module-logo">
							<span class="fa-stack fa-2x">
								<i class="fa fa-circle fa-stack-2x text-success"></i>
								<i class="fa fa-star fa-stack-1x text-white"></i>
							</span>
						</div>

						<div class="module-info">
							<a href="#" class="h4">{{ $Module->title }}</a>

							<div class="maintainer">
								Maintained&nbsp;by&nbsp;<span class="text-uppercase">{{ $Module->maintainer }}</span>
							</div>
						</div>

						<p>{{ $Module->description }}</p>
					</div>

					<div class="col-sm-2">
						<div class="rating">
							<span>Rated</span>
							<div class="stars">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star-half-empty"></i>
								<i class="fa fa-star-o"></i>
							</div>
						</div>
					</div>

					<div class="col-sm-2">
						<div class="status">
							<i class="fa fa-lock"></i>
						</div>
					</div>
				</div>
			@endforeach
		</section>
	</section>
@stop
