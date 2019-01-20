<?php
namespace Yeti\Core\Controller;

use \Able\Helpers\Arr;

use \Yeti\Core\Model\Page;
use \Yeti\Main\Controller\Abstracts\AController;

use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;

class Robots extends AController {

	public function edit() {
		return view('yeti@core::robots.edit')
			->with('text', App::scope()->option('robots'));
	}

	public function update(){
			App::scope()->store(['robots' => Input::get('text')]);

			return redirect()->route('yeti@core:robots.edit')
				->withSuccess('Project setting was successful updated!');
	}

}
