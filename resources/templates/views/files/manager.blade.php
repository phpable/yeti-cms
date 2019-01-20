@extends('yeti@core::main')

@section('js')
	@parent

	<script type="text/javascript">
		(function($){
			$(function(){
				$(document).on('files-loaded', function(){
					var jScrollable = $('.scrollable');

					if (jScrollable.data("plugin_tinyscrollbar") === undefined) {
						jScrollable.tinyscrollbar({alwaysShow: true});
					} else {
						jScrollable.data("plugin_tinyscrollbar").update();
					}
				});

				$(document).on('file-uploaded', function(jEvent, Data) {
					var jData = $('.data', $('.filemanager'));

					if (jData.length > 0) {
						var jItem = window.__FILEMANAGER__.file(Data);
						var jFiles = $('.files', jData);

						if (jFiles.length > 0) {
							jFiles.first().before(jItem);
						} else {
							jData.append(jItem);
						}

						jData.trigger('main-resize')
					}
				});

				$('#create-folder').on('click', function () {
					$.post("{{ route('yeti@main:files.create-folder') }}", {
						context:  window['__FILEMANAGER__CONTEXT__']
					}).done(function(Data){
						var jData = $('.data', $('.filemanager'));

						if (jData.length > 0) {
							var jItem = window.__FILEMANAGER__.folder($.extend(Data, {type: 'folder'}));
							var jFolders = $('.folders', jData);

							if (jFolders.length > 0) {
								jFolders.last().after(jItem);
							} else {
								jData.append(jItem);
							}

							jData.trigger('main-resize')
						}
					});

					return false;
				})
			});
		})(jQuery)
	</script>
@stop

@section('actions')
	@parent

	<a href="#" id="fileinput-button" title="Upload">
		<i class="fa fa-plus"></i>
	</a>

	<a href="#" id="create-folder" title="Create Folder">
		<i class="fa fa-folder"></i>
	</a>

	@include('fileupload', ['target' => 'fileinput-button',
		'url' => route('yeti@main:files.upload'), 'progress' => 'progressbar'])
@stop

@section('workspace')
	<section class="panel filemanager"
		data-load="{{ route('yeti@main:files.load') }}">

		<div class="breadcrumbs" id="breadcrumbs"></div>

		<div class="scrollable" data-effect="full-height" data-height-dec="breadcrumbs" id="scrollbar1">

			<div class="scrollbar">
				<div class="track">
					<div class="thumb">
						<div class="end"></div>
					</div>
				</div>
			</div>

			<div class="viewport" data-effect="full-height">
				<div class="overview">
					<ul class="data"></ul>
				</div>
			</div>

		</div>
	</section>
@stop
