<?php
define('LARAVEL_START', microtime(true));

use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\User;
use \Yeti\Main\Model\Project;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;

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

			$parameters = Arr::simplify(array_slice(func_get_args(), 1));
			if (!is_null($Page = Page::where('name', '=', ltrim($path, ':'))->first())){
					return preg_replace_callback('/\{\$([A-Za-z0-9-_]+):?([0-9A-Za-z_-]*)\}/',
						function(array $Matches) use (&$parameters, $path) {
							if (count($parameters) > 0) {
								return array_shift($parameters);
							}

							if (!empty($Matches[2])){
								return $Matches[2];
							}

							throw new \Exception(sprintf('Parameter % missed for %s!', $Matches[2], $path));
					}, '/' . ltrim($Page->url, '/'));
			}

			return '/';
		}

		return app(Illuminate\Contracts\Routing\UrlGenerator::class)->to($path, $parameters, $secure);
	}
}

if (!function_exists('is_page')) {
	function is_page(string $mask): bool {
		if (preg_match('/^:[\w-]+/', $mask)) {
			if (!is_null($Page = Page::where('name', '=', ltrim($mask, ':'))->first())) {
				return preg_match('/^\/' . Str::join('\\/', array_map(function(string $value){
					return !preg_match('/\{\$[^}]+\}/', $value, $Matches)
						? preg_quote($value, '/') : '[^\\/]+';
				}, preg_split('/\/+/',$Page->url, -1, PREG_SPLIT_NO_EMPTY))) . '(\\?.+)?$/', $_SERVER['REQUEST_URI']) > 0;
			}
		}

		return false;
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

if (! function_exists('user_path')) {

	/**
	 * Returns the path to the working directory of the given user.
	 * @param User $User
	 * @param string $path
	 * @return string
	 */
	function user_path(User $User, string $path = ''): string {
		return base_path(Str::join(DIRECTORY_SEPARATOR, config('app.paths.users'), $User->uid, $path));
	}
}

if (! function_exists('project_path')) {

	/**
	 * Returns the path to the working directory of the project.
	 * @param Project $Project
	 * @param string $path
	 * @return string
	 */
	function project_path(Project $Project, string $path = ''): string {
		return base_path(Str::join(DIRECTORY_SEPARATOR, config('app.paths.projects'), $Project->uid, $path));
	}
}

