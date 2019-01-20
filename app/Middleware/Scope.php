<?php
namespace Yeti\Main\Middleware;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\View;

use \Illuminate\Contracts\Auth\Guard;

use \Illuminate\Http\Request;

use \Yeti\Main\Exception\InvalidScopeException;

class Scope {

	/**
	 * @param  Request $Request
	 * @param  \Closure $Next
	 * @return mixed
	 * @throws InvalidScopeException
	 */
	public function handle(Request $Request, \Closure $Next) {
		if (!App::scopable()) {
			throw new InvalidScopeException();
		}

		return $Next($Request);
	}
}
