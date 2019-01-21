@extends('yeti@core::main', ['footer' => false])

@param($scrollable, false)

@section('js')
	@parent

	<script type="text/javascript">
		$(function(){
			$('#button-entity-save').click(function(){
				var jThis = $(this);

				if (!jThis.is('.disabled')) {
					$('#yeti-entity-form').submit();
				}
				return false;
			});
		})
	</script>
@stop

@section('actions')
	@parent

	<a href="#" id="button-entity-save" title="Save">
		<i class="fa fa-check fa-fw"></i>
	</a>
@stop

@section('workspace')
	<form id="yeti-entity-form" action="{{ $action }}" method="post" class="form-horizontal">
		{!! csrf_field() !!}

		<div class="form-content" @if(!$scrollable) data-effect="full-height" data-master="content" @endif>
			@yield('form')
		</div>
	</form>
@stop



