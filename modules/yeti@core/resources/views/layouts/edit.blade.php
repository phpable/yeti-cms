@extends('frame', ['action'
	=> route('yeti@core:layouts.update', $Layout->id)])

@section('actions')
	@parent

	<a href="{{ route('yeti@core:layouts.settings', $Layout->id) }}" title="Setting">
		<i class="fa fa-pencil fa-fw"></i>
	</a>

	<a href="{{ route('yeti@core:layouts.delete', $Layout->id) }}" data-effect="waiting" title="Delete">
		<i class="fa fa-trash fa-fw"></i>
	</a>
@stop

@section('form')
	@include('ide', ['Editors' => $Layout->templates, 'container' => $Layout->id,
		'owner' => $Layout->getType(), 'multytab' => true])
@stop
