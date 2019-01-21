<?php
define('LARAVEL_START', microtime(true));

if (! function_exists('url')) {

	/**
	 * Generate a url for the application.
	 * @param  string $path
	 * @param  mixed $parameters
	 * @param  bool $secure
	 * @return string
	 * @throws \Exception
	 */
	function url($path = null, $parameters = [], $secure = null) {
		if (preg_match('/^:[\w-]+/', $path)){

			if (file_exists($path = Config::get('building.destination')
				. '/'. App::scope()->name . '/pages/' . ltrim($path, ':') . '/page.json')){

					return preg_replace_callback('/\{\$([A-Za-z0-9-_]+):?([0-9A-Za-z_-]*)\}/',
						function(array $Matches) use (&$parameters, $path) {
							if (count($parameters) > 0) {
								return array_shift($parameters);
							}

							if (!empty($Matches[2])){
								return $Matches[2];
							}

							throw new \Exception(sprintf('Paranetr % missed for %s!', $Matches[2], $parameters));
					}, '/' . ltrim(json_decode(file_get_contents($path))->url, '/'));
			}

			return '/';
		}

		return app(Illuminate\Contracts\Routing\UrlGenerator::class)->to($path, $parameters, $secure);
	}
}

if (!function_exists('collect')) {

	/**
	 * @param string $tag
	 * @param string $key
	 * @param Closure $Handler
	 * @return mixed
	 * @throws \Exception
	 */
	function collect(string $tag, string $key = null, \Closure $Handler = null) {
		static $__CACHE = [];

		if (!in_array($tag, ['css', 'js'])){
			throw new \Exception('Undefined cache type!');
		}

		if (func_num_args() < 2 ){
			return implode("\n", $__CACHE[$tag] ?? []);
		}

		if (!isset($__CACHE[$tag])) {
			$__CACHE[$tag] = [];
		}

		$key = $key ?? md5(strval(time()
			* microtime(true) * count($__CACHE[$tag])));

		if (!isset($__CACHE[$tag][$key])) {
			ob_start();
			call_user_func($Handler, ...array_slice(func_get_args(), 3));

			$__CACHE[$tag][$key] = preg_replace('/<\/(?:script|style)>$/', '',
				preg_replace('/^<(?:script|style)[^>]*>/', '', trim(ob_get_clean())));
		}
	}
}

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/
$composerPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($composerPath)) {
	$Loader = include($composerPath);

	if (file_exists($file = __DIR__ . '/../resources/temporary/import.php')){
		foreach (include($file) as  $namespace => $map){
			$Loader->setPsr4($namespace, $map);
		}
	}
}

/*
|--------------------------------------------------------------------------
| Include The Compiled Class File
|--------------------------------------------------------------------------
|
| To dramatically increase your application's performance, you may use a
| compiled class file which contains all of the classes commonly used
| by a request. The Artisan "optimize" is used to create this file.
|
*/

$compiledPath = __DIR__ . '/cache/compiled.php';

if (file_exists($compiledPath)) {
	require $compiledPath;
}

