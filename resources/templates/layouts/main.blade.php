<!DOCTYPE html>

<html class="no-touch" lang="en">

<head>

	<meta charset="utf-8">
	<link rel="shortcut icon" href="/images/yeti32y.png">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href='https://fonts.googleapis.com/css?family=Miriam+Libre:400,700' rel='stylesheet' type='text/css'>
	<link href="{{ asset('fonts/bbr.ttf') }}?family=Baloo+Bhaijaan" rel="stylesheet">

	<title>Yeti CMS</title>

	<link rel="stylesheet" href="{{ asset('css/main.css') }}">

	@yield('css')

	<script type="text/javascript" src="{{ asset('js/main.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/editor.js')}}"></script>

	@yield('js')
</head>

<body data-effect="full-height">
	@yield('main')

	<script type="text/javascript">
		(function($){
			$(function() {
				$('[data-action="submit"]').on('click', function(){
					var jThis = $(this);

					if (jThis.is('[data-target]')){
						$('form#' + jThis.data('target')).submit();
					}

					return false;
				});
			});
		})(jQuery);
	</script>
</body>

</html>
