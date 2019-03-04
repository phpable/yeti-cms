<?php
namespace Yeti\Core\Controller;

use \Illuminate\View\View;
use \Illuminate\Http\Response;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Bus;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Redirect;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Template;
use \Yeti\Main\Macros\UpdateSources;
use \Yeti\Main\Controller\Abstracts\AController;

use \Able\Helpers\Arr;
use \Able\Helpers\Arg;

class Objects extends AController {

	/**
	 * @return View
	 */
	public function all(){
		return view('yeti@core::objects.all')->with('Objects', App::scope()->objects);
	}

	/**
	 * @return Response
	 */
	public function update() {
		App::scope()->update(['objects' => array_filter(Input::get('objects', []), function($Info){
			return !empty($Info['item']) && !empty($Info['alias']);
		})]);

		return redirect()->route('yeti@core:objects.all')
			->withSuccess('Successful Saved!');
	}
}
