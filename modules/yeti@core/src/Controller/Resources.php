<?php
namespace Yeti\Core\Controller;

use \Illuminate\View\View;

use \Illuminate\Support\Facades\Bus;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;

use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Project;

use \Yeti\Main\Controller\Abstracts\AController;

class Resources extends AController {

	public final function edit() {
		return view('yeti@core::resources.edit')->with('Project',
			App::scope());
	}
}
