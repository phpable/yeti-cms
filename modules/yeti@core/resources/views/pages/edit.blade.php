@extends('frame', ['action'
	=> route('yeti@core:pages.update', $Page->id)])

@param($Preview, $Page)

@section('actions')
	@parent

	<a href="{{ route('yeti@core:pages.settings', $Page->id) }}" title="Setting">
		<i class="fa fa-pencil fa-fw"></i>
	</a>

	<a href="{{ route('yeti@core:pages.delete', $Page->id) }}" data-effect="waiting"  title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('form')
	@include('ide', ['Editors' => $Page->templates, 'container' => $Page->id,
		'owner' => $Page->getType(), 'multytab' => true])
@stop
