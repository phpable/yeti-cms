@extends('yeti@core::main')

@section('actions')
	@parent

	{{--
	<a href="#" class="btn btn-sm btn-success" title="Clear Log">
		<i class="fa fa-eraser"></i>
	</a>
	--}}

	<a href="{{ URL::previous() }}" class="btn btn-sm btn-danger" title="Go Back">
		<i class="fa fa-reply"></i>
	</a>
@stop

@section('workspace')
	<section class="panel">
		<div class="panel-body">
			{{ $messages }}
		</div>
	</section>
@stop
