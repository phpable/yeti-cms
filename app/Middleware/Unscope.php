<?php
namespace Yeti\Main\Middleware;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Session;

use \Illuminate\Contracts\Auth\Guard;
use \Illuminate\Http\Request;

class Unscope {

	/**
	 * @param  Request $Request
	 * @param  \Closure $Next
	 * @return mixed
	 */
	public function handle(Request $Request, \Closure $Next) {
		if (App::scopable()) {
			Session::forget('__SCOPE__');
		}

		return $Next($Request);
	}
}
