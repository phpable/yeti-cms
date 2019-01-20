<?php
namespace Yeti\Main\Boot;

use \Illuminate\Foundation\Http\Kernel;
use \Illuminate\Session\Middleware\StartSession;
use \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use \Illuminate\View\Middleware\ShareErrorsFromSession;
use \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

use \Yeti\Main\Middleware\Auth;
use \Yeti\Main\Middleware\Ajax;
use \Yeti\Main\Middleware\Guest;
use \Yeti\Main\Middleware\Scope;
use \Yeti\Main\Middleware\Storage;
use \Yeti\Main\Middleware\Unscope;

class YetiKernel extends Kernel {

	/**
	 * The application's global HTTP middleware stack.
	 * @var array
	 */
	protected $middleware = [
		CheckForMaintenanceMode::class,
		AddQueuedCookiesToResponse::class,
		StartSession::class,
		ShareErrorsFromSession::class,
		Storage::class,
	];

	/**
	 * The application's route middleware.
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => Auth::class,
		'guest' => Guest::class,
		'scope' => Scope::class,
		'unscope' => Unscope::class,
		'ajax' => Ajax::class,
	];

}
