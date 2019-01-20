<?php
namespace Yeti\Main\Middleware;

use \Able\Helpers\Arr;

use \Illuminate\Support\Facades\URL;
use \Illuminate\Support\Facades\Session;

class Storage {

	/**
	 * @const string
	 */
	const NAME = '__URLS__';

	/**
	 * Handle an incoming request.
	 * @param  \Illuminate\Http\Request $Request
	 * @param  \Closure $Next
	 * @param  string $guard
	 * @return mixed
	 */
	public function handle($Request, \Closure $Next, $guard = null) {
		$url = strtolower(trim(URL::full()));

		if (Session::has(self::NAME)){
			$Urls = array_values(Arr::cast(Session::get(self::NAME, [])));

			if (count($Urls) < 1 || $Urls[count($Urls) - 1] != $url) {
				Session::push(self::NAME, $url);
			}

			return $Next($Request);
		}

		Session::push(self::NAME, $url);
		return $Next($Request);
	}
}
