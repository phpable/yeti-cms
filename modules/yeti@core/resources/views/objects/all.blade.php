@extends('frame', [
	'action' => route('yeti@core:objects.update'),
	'scrollable' => true])

@section('actions')
	@parent

	<a id="btn-share" href="javascript:void(0)" title="Share">
		<i class="fa fa-plus-circle fa-fw"></i>
	</a>
@stop

@section('js')
	@parent

	<script type="text/javascript">
		(function(){
			$(function(){
				var jTpl = $('#share_tpl');

				if (jTpl.length > 0) {
					jTpl.remove();

					$('#btn-share').on('click', function () {
						var jThis = $(this);
						var count = $('.share-group').length + 1;

						$('.shares').append(jTpl.html()
							.replace(/__ID__/g, 'dynamic-' + count)
							.replace(/__INDEX__/g, count));
					});
				}
			});
		})(jQuery);
	</script>

	<script type="text/javascript">
		(function ($) {
			$(function () {
				$(document).on('click', 'a[data-action="delete-object"]', function () {
					$('#' + $(this).data('target')).remove();
					return false;
				});
			});
		})(jQuery)
	</script>
@stop

@section('form')
	<div class="col-lg-12">
		<div class="row">
			<div class="separated">

				<div id="share_tpl" style="display: none">
					@include('yeti@core::objects.controls.object', ['id' => '__ID__', 'index' => '__INDEX__'])
				</div>

				<div class="shares">
					@if (!empty($Objects))
						@foreach($Objects as $index => $Object)
							@include('yeti@core::objects.controls.object', ['Object' => $Object, 'id' => 'share' . $index])
						@endforeach
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>
@stop
