@extends('frame', ['action'
	=> route('yeti@core:snippets.update', $Snippet->id)])

@section('actions')
	@parent

	<a href="{{ route('yeti@core:snippets.settings', $Snippet->id) }}" title="Setting">
		<i class="fa fa-pencil fa-fw"></i>
	</a>

	<a href="{{ route('yeti@core:snippets.delete', $Snippet->id) }}" data-effect="waiting" title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('form')
	@include('ide', ['Editors' => $Snippet->templates, 'multytab' => true,
		'container' => $Snippet->id, 'owner' => $Snippet->getType()])
@stop
