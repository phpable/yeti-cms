@param($id, md5(time()))
@param($name, 'content')

@param($type, 'html')
@param($active, false)

@param($value)

<div id="editor-{{ $id }}" class="tab-pane @if ($active) active @endif">

	<script type="text/javascript">
		(function($) {
			var __MODE__ = "text";

			if ("{{ $type }}" === "js"){
				__MODE__ = "javascript";
			}

			if ("{{ $type }}" === "css"){
				__MODE__ = "css";
			}

			if ("{{ $type }}" === "html"){
				__MODE__ = "html";
			}

			$(function () {
				let Editor = ace.edit("editor-control-" + "{{ $id }}");

				Editor.setTheme("ace/theme/solarized_light");
				Editor.setShowPrintMargin(false);
				Editor.setShowInvisibles(true);

				Editor.setOptions({
					useWorker: false,
					enableBasicAutocompletion: true,
					enableSnippets: true,
					enableLiveAutocompletion: false
				});

				Editor.session.setMode("ace/mode/" + __MODE__);
				Editor.session.setUseSoftTabs(false);

				Editor.renderer.setScrollMargin(7);
				Editor.renderer.setVScrollBarAlwaysVisible(true);

				$(document).on('submit', $('#yeti-entity-form'), function () {
					$('input', $("#editor-{{ $id }}"))
						.val(Editor.getValue());
				});
			})
		})(jQuery);
	</script>

	<div id="editor-control-{{ $id }}" class="ace-editor">{{ $value }}</div>
	<input type="hidden" name="{{ $name }}" value="" />
</div>
