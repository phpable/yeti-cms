@extends('yeti@core::main')

@section('js')
	@parent

	<script type="text/javascript">
		(function($){
			$(function(){
				$('.project-cnt').bind('click', function () {
					$('#project-hidden-field').val($(this).data('pid'));
					$('#project-form').submit();
				});
			});
		})(jQuery);
	</script>
@stop

@section('workspace')
	<div class="row dashboard">
		<div class="col-lg-12">
			<form action="{{ route('yeti@main:dashboard') }}" method="post" id="project-form">
				<input type="hidden" id="project-hidden-field" name="project" value="" />
				{!! csrf_field() !!}

				@foreach ($Projects as $Project)
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
						<div class="row">
							<section class="project-cnt" data-pid="{{ $Project->id }}">
								<div class="project-tile" >

									<div class="project-logo">
										<span class="btn btn-circle btn-facebook btn-lg">
											<i class="fa fa-glass"></i>
										</span>
									</div>

									<div class="project-info">
										<span class="project-name fbaloo"><span>@</span>{{ $Project->name }}</span>
										<span class="project-url">{{ $Project->domain }}</span>
									</div>

									<div class="project-owners">
										<span class="thumb avatar">
											<img class="img-circle" src="/images/yeti72.png" title="Crazy Yeti - Owner">
										</span>
									</div>
								</div>
							</section>
						</div>
					</div>
				@endforeach

			</form>
		</div>
	</div>
@stop

