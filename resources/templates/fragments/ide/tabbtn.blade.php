@param($name)
@param($id)

@param($type, 'html')

@if (isset($id, $name))
	<li id="editor-tab-{{ $id }}" data-tid="{{ $id }}">

		<a id="editor-tab-{{ $type }}-{{ $id }}-button" href="#editor-{{ $id }}"
			title="{{ ucfirst($name) }}" data-toggle="tab">

			<i class="fa fa-file"></i>
			<em class="f-ext fbaloo">{{ $type }}</em>
			<span>{{ $name }}</span>
		</a>

		<div class="rename" style="display: none">
			<i class="fa fa-file"></i>
			<em class="f-ext fbaloo">{{ $type }}</em>

			<input class="rename" type="text" name="rename[{{ $name }}]" @if (!is_null($id)) data-reference="{{ $id }}" @endif value="{{ $name }}">
		</div>
	</li>
@endif
