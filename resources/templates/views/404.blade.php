<!doctype html>
<html>
	<title>Whoops, looks like something went wrong!</title>
		<style>
			* {
				box-sizing: border-box;
				font-family: Helvetica, Arial, sans-serif;
			}
			html, body {
				height: 100%;
				padding: 0;
				margin: 0;
			}
			body {
				background-color: rgb(116, 116, 116);
				position: relative;
			}
			.error {
				display: table;
				height: 300px;
				width: 100%;
				position: absolute;
				top: 0;
				left: 0;
			}
			.error > span {
				font-size: 212px;
				line-height: 300px;
				color: rgb(134, 151, 145);
				display: table-cell;
				text-align: center;
				text-shadow: 2px 2px #000000;
			}
			.message {
				display: block;
				padding-top: 300px;
				height: 100%;
				width: 100%;
			}
			.message > span {
				display: block;
				margin: 0 20%;
				padding: 22px;
				background-color: #303030;
				color: #a29d9d;
				font-size: 16px;
				text-shadow: 2px 2px #000000;
			}
		</style>
	<body>
		<div class="error">
			<span>404</span>
		</div>

		@if (isset($message))
			<div class="message">
				<span>
					{{ $message }}
				</span>
			</div>
		@endif
	</body>
</html>
