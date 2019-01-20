@extends('frame', ['action'
	=> route('yeti@core:robots.update')])

@section('form')
	<div class="editor-container" data-effect="full-height">
		@include('ide.editor', ['name' => 'text',
			'value' => $text, 'type' => 'text'])
	</div>
@stop

