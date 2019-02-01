@extends('yeti@blog::main', ['footer' => isset($footer) ? $footer : false])

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

	<script type="text/javascript">
		function validate() {
			var jContainers = $('.required');

			for(var i = 0; i < jContainers.length; i++) {
				var jControls = $(jContainers[i]).find('input[type="text"], select');

				for (var j = 0; j < jControls.length; j++) {
					if (jControls.val().replace(/\s+/, '').length < 1){
						return false;
					}
				}
			}

			return true;
		}

		(function($){
			$(function(){
				var jButton = $('#button-entity-save');

				$('.required').find('input[type="text"],select').on('click change keypress', function(){
					if (validate()){
						jButton.removeClass('disabled');
					}else{
						jButton.addClass('disabled');
					}
				}).trigger('click');
			});
		})(jQuery);
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



