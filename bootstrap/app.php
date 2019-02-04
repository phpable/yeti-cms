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

$App = (new class(realpath(__DIR__ . '/../'))
	extends \Illuminate\Foundation\Application{

	/**
	 * @return Project
	 */
	public final function scope(): Project {
		return ProjectScope::detectActiveScope();
	}

	/**
	 * @return bool
	 */
	public final function scopable(): bool {
		return Auth::check() && Session::has('__SCOPE__');
	}

	/**
	 * @return Collection
	 */
	public final function modules(): Collection {
		return Module::getActive();
	}
});

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$App->singleton(
	\Illuminate\Contracts\Http\Kernel::class,
	\Yeti\Main\Boot\YetiKernel::class
);

$App->singleton(
	\Illuminate\Contracts\Console\Kernel::class,
	\Yeti\Main\Boot\Console::class
);

$App->singleton(
	\Illuminate\Contracts\Debug\ExceptionHandler::class,
	\Yeti\Main\Exception\Interceptor::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $App;
