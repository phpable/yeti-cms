@extends('frame', ['action'
	=> route('yeti@core:resources.update')])

@section('form')

	<input type="hidden" name="project_id" value="{{ $Project->id }}">
@stop
