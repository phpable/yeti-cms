<?php
namespace Yeti\Main\Middleware;

use \Illuminate\Support\Facades\View;
use \Illuminate\Contracts\Auth\Guard;
use \Illuminate\Http\Request;

class Auth {

	/**
	 * @var Guard
	 */
	protected $Auth = null;

	/**
	 * @param  Guard $Auth
	 */
	public function __construct(Guard $Auth) {
		$this->Auth = $Auth;
	}

	/**
	 * @param  Request $Request
	 * @param  \Closure $Next
	 * @return mixed
	 */
	public function handle(Request $Request, \Closure $Next) {
		if ($this->Auth->guest()) {
			return $Request->ajax() ? response('Access denied!', 401)
				: redirect()->guest('auth/login');
		}
		View::share('Viewer', $this->Auth->user());
		return $Next($Request);
	}

}
