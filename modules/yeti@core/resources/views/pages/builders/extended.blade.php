@param($id, md5(microtime(true)))
@param($index, 0)

@section('js')
	@parent

	<script type="text/javascript">
		(function(){
			$(function(){
				var jTpl = $('#share_tpl');

				if (jTpl.length > 0) {
					jTpl.remove();

					$('.btn-apply').on('click', function () {
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
@stop

<div class="shares">
	@if (isset($Arguments['share']))
		@foreach($Arguments['share'] as $index => $Info)
			@include('yeti@core::pages.controls.share', ['Share' => $Info, 'id' => 'share' . $index])
		@endforeach
	@endif
</div>

<div class="form-group btn-group">
	<a class="btn btn-apply btn-flat">
		<i class="fa fa-plus"></i>
		<span>Share Entity</span>
	</a>
</div>

<div id="share_tpl" style="display: none">
	@include('yeti@core::pages.controls.share', ['id' => '__ID__', 'index' => '__INDEX__'])
</div>
