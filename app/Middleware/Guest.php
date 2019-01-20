<?php
namespace Yeti\Main\Middleware;

use \Illuminate\Contracts\Auth\Guard;
use \Illuminate\Http\Request;

class Guest {

	protected $Ignorable = [
		'/auth/logout'];

	/**
	 * @var Guard
	 */
	protected $Auth;

	/**
	 * @param  Guard $auth
	 */
	public function __construct(Guard $auth) {
		$this->Auth = $auth;
	}

	/**
	 * @param  Request $Request
	 * @param  \Closure $next
	 * @return mixed
	 */
	public function handle(Request $Request, \Closure $next) {
		if ($this->Auth->check() && !in_array($Request->getPathInfo(), $this->Ignorable)) {
			return redirect('/');
		}

		return $next($Request);
	}
}
