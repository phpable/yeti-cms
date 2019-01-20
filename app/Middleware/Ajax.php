<?php
namespace Yeti\Main\Middleware;

use \Illuminate\Http\Request;

class Ajax {

	/**
	 * @param  Request $Request
	 * @param  \Closure $Next
	 * @throws \Exception
	 * @return mixed
	 */
	public function handle(Request $Request, \Closure $Next) {
		if (!$Request->ajax()) {
			throw new \Exception('Invalid request!');
		}

		return $Next($Request);
	}

}
