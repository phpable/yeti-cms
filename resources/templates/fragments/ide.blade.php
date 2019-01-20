@param($multytab, false)
@param($tabable, true)

@param($owner)
@param($container)

@if (isset($container, $owner))
	@section('js')
		@parent

		<script type="text/javascript">
			(function($) {
				$(function () {
					var jContainer = $('.editor-container');
					if (jContainer.length > 0) {
						var jSlider = $('.nav-slider', jContainer);

						var jTabs = $('li', jSlider);
						if (jTabs.length > 0) {
							jSlider.width(jTabs.length * 172);

							if (jTabs.filter('.active').length < 1) {
								var jQueue = jTabs.tabFind('tid');

								if (jQueue.length > 0) {
									$('a', jQueue).trigger('click');
								}else {
									$('a', jTabs.first()).trigger('click');
								}
							}
						}

						jContainer.on('click', '.nav-tabs a', function(){
							var jThis = $(this);
							$.tabSave(jThis.closest('li').data('tid'));

							var Editor = ace.edit("editor-control-"
								+ jThis.attr('href').replace(/^#editor-/, ""));

							Editor.focus();
						});

						$('#tab-add').bind('click', function(){
							$(this).toggleClass('open');
						});

						$('li', $('#addsource')).bind('click', function () {
							var jThis = $(this);

							if (jThis.is('[data-type]')) {
								jThis.parent().removeClass('open');

								$.post("{{ route('yeti@main:editor.create') }}", {
									pid: "{{ $container }}",
									owner: "{{ $owner }}",
									type: jThis.data('type')
								}).done(function (jResponse) {
									var jTabs = $('li', $('.nav-slider'));

									var jSlider = $('.nav-slider', jContainer);
									if (jResponse['__TAB__'] !== undefined) {
										jSlider.width((jTabs.length + 1) * 172).append($(jResponse['__TAB__']));
									}

									var jEditors = $('.tab-content', jContainer);
									if (jResponse['__EDITOR__'] !== undefined) {
										jEditors.append(jResponse['__EDITOR__']);
									}

									for (var i = Math.floor(((jTabs.filter(':visible').length + 1) * 172 - $('#tabh').width()) / 172); i > 0; i--) {
										jTabs.filter(':visible').first().hide();
									}

									$('a', $('#editor-tab-' + jResponse['id'])).click();
								});
							}
						});

						$('#tab-rename').bind('click', function() {
							var jTab = $('.nav-tabs li.active', jContainer);
							if (jTab.length > 0){

								jTab.addClass('editable');
								$('.rename > input', jTab).focus();
							}
						});

						$('.nav-tabs').each(function() {
							var jThis = $(this);

							jThis.on('keypress', 'input', function (jEvent) {
								var jInput = $(this);

								if (!/[A-Za-z0-9_-]/.test(jEvent.key)) {
									if (jEvent.keyCode !== 8 && jEvent.keyCode !== 13) {
										if (jEvent.keyCode !== 35 && jEvent.keyCode !== 36) {
											return false
										}
									}
								}
								if (/[0-9-]/.test(jEvent.key) && jInput.val().length < 1) {
									return false
								}
							});

							jThis.on('keyup focusout', 'input', function (jEvent) {
								var jInput = $(this);

								if (jEvent.keyCode === 13 || jEvent.keyCode === 27 || jEvent.type === 'focusout') {
									var jTab = $('.nav-tabs li.editable');
									jTab.removeClass('editable');

									if (jEvent.keyCode === 13){
										if (/[A-Za-z][A-Za-z0-9_-]+/.test(jInput.val())) {
											var jLabel = $('span', jTab);

											var original = jLabel.html();
											jLabel.html(jInput.val());

											if (jInput.is('[data-reference]')){
												$.post("{{ route('yeti@main:editor.rename') }}", {
													id: jInput.data('reference'),
													name: jInput.val()
												}).done(function(jResponse){
													if (jResponse["name"] === undefined){
														jLabel.html(original);
													}
												}).error(function(){
													jLabel.html(original);
												});
											}
										}
									}
								}
							});
						});

						$('#tab-delete').bind('click', function(){
							var jThis = $(this);

							if (!jThis.is('.waiting')){
								jThis.addClass('waiting');

								setTimeout(function(){
									jThis.removeClass('waiting');
								}, 500);
							} else {
								var jTabs = $('li', $('.nav-slider'));
								var jActive = jTabs.filter('.active');

								if (jActive.length === 1) {
									$.post("{{ route('yeti@main:editor.delete') }}", {
										id: $('input', jActive.first()).data('reference')
									}).done(function (jResponse) {
										if (jResponse['id'] !== undefined) {
											$('#editor-tab-' + jResponse['id']).remove();
											$('#editor-' + jResponse['id']).remove();
										}

										if (jTabs.length > 0) {
											$('a', jTabs.first()).trigger('click');
										}
									});
								}
							}
						});
					}

					$('#slide-right-btn').bind('click', function(){
						var jTabs = $('li', $('.nav-slider'));

						if (jTabs.filter(':visible').length * 172 - $('#tabh').width() >= 172){
							jTabs.filter(':visible').first().hide();
						}
					});

					$('#slide-left-btn').bind('click', function(){
						var jTabs = $('li', $('.nav-slider'));

						if (jTabs.not(':visible').length > 0){
							jTabs.not(':visible').last().show();
						}
					});
				});
			})(jQuery);
		</script>
	@stop

	<div class="editor-container" data-effect="full-height">
		<div class="tab-header" id="tabh">
			<div class="arrows slide-left" id="slide-left-btn">
				<i class="fa fa-caret-left"></i>
			</div>

			<ul class="nav nav-tabs nav-slider">
				@param($Editors, [])

				@foreach($Editors as $Editor)
					@object($Editor, 'name', 'source', 'test')

					@include('ide.tabbtn', ['name' => $Editor->name,
						'id' => $Editor->id, 'type' => $Editor->type])
				@endforeach
			</ul>

			<ul class="nav nav-tabs nav-tools">
				@if($multytab)
					<li class="tab-btn tab-dowpdown-btn" id="tab-add">
						<i class="fa fa-plus"></i>

						<ul class="tab-dropdown fbaloo" id="addsource">
							<li data-type="html">.html</li>
							<li data-type="css">.css</li>
							<li data-type="js">.js</li>
						</ul>
					</li>
				@endif

				<li class="tab-btn" id="tab-rename">
					<i class="fa fa-pencil"></i>
				</li>

				<li class="tab-btn" id="tab-delete">
					<i class="fa fa-trash"></i>
				</li>
			</ul>

			<div class="arrows slide-right" id="slide-right-btn">
				<i class="fa fa-caret-right"></i>
			</div>
		</div>

		<div class="tab-content" data-effect="full-height" data-height-dec="tabh">
			@foreach($Editors as $Editor)
				@object($Editor)

				@include('ide.editor', ['id' => $Editor->id, 'name' => 'sources['. $Editor->id . ']',
					'value' => $Editor->source, 'type' => $Editor->type])
			@endforeach
		</div>
	</div>
@endif
