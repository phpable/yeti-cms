<?php
namespace Yeti\Main\Controller\Abstracts;

use \Illuminate\Routing\Controller;

use \Illuminate\Foundation\Bus\DispatchesJobs;
use \Illuminate\Foundation\Validation\ValidatesRequests;

use \Illuminate\Routing\Redirector;
use \Illuminate\Http\RedirectResponse;

use \Illuminate\Support\Facades\Input;

use \Able\Helpers\Str;

abstract class AController extends Controller {
	use DispatchesJobs, ValidatesRequests;

	/**
	 * Execute an action on the controller.
	 * @param  string $method
	 * @param  array $Parameters
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function callAction($method, $Parameters) {
		if (Input::has('filter')) {
			session([get_class($this) => Input::get('filter')]);
		}

		return parent::callAction($method, $Parameters);
	}
}
