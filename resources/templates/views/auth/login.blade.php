@extends('main')

@section('main')
	<div class="row">
		<div class="authorize col-lg-4 col-lg-offset-4 m-t-large">
			<section class="panel">
				<header class="panel-heading text-center"> Welcome to <strong>Yeti CMS!</strong></header>

				<form class="panel-body" action="/auth/login" method="post">

					@if (Session::get('errors'))
						<div class="alert alert-danger">
							Invalid login or password!
						</div>
					@endif

					{!! csrf_field() !!}

					<div class="block">
						<input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
					</div>

					<div class="block">
						<input type="password" name="password" class="form-control" placeholder="Password" id="inputPassword">
					</div>

					<div class="checkbox"><label>
						<input type="checkbox" name="remember">Remember me!</label>
					</div>

					{{--
					<a class="pull-right m-t-mini" href="#">
						<small>Forgot password?</small>
					</a>
					--}}

					<button class="btn btn-info" type="submit">Let me in!</button>

				</form>
			</section>
		</div>
	</div>
@stop
