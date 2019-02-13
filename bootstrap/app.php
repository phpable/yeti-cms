<?php
use \Yeti\Main\Model\Module;
use \Yeti\Main\Model\Project;
use \Yeti\Main\Model\Scope\ProjectScope;

use \Yeti\Main\Exception\InvalidScopeException;

use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

use \Illuminate\Database\Eloquent\Collection;

if (!function_exists('resources_path')) {
	/**
	 * Get the path to the resources folder.
	 *
	 * @param  string $path
	 * @return string
	 */
	function resources_path($path = ''): string {
		return base_path('resources') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
	}
}

if (!function_exists('temporary_path')) {
	/**
	 * Get the path to the temporary folder.
	 *
	 * @param  string $path
	 * @return string
	 */
	function temporary_path($path = ''): string {
		return resources_path('temporary') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
	}
}

if (!function_exists('module_path')) {
	/**
	 * Get the path to the module folder.
	 *
	 * @param  Module $Module
	 * @param  string $path
	 * @return string
	 */
	function module_path(Module $Module, $path = ''): string {
		return  base_path('modules') . DIRECTORY_SEPARATOR
			. $Module->getMnemonic() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
	}
}

$App = new \Yeti\Main\Boot\Runtime(realpath(__DIR__ . '/../'));

$App->singleton(
	\Illuminate\Contracts\Http\Kernel::class,
	\Yeti\Main\Boot\Http::class
);

$App->singleton(
	\Illuminate\Contracts\Console\Kernel::class,
	\Yeti\Main\Boot\Console::class
);

$App->singleton(
	\Illuminate\Contracts\Debug\ExceptionHandler::class,
	\Yeti\Main\Exception\Interceptor::class
);

return $App;
