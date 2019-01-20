<?php
namespace Yeti\Core\Controller;

use \Illuminate\View\View;

use \Illuminate\Support\Facades\Bus;
use \Illuminate\Support\Facades\App;
use \Illuminate\Support\Facades\Input;

use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Project;

use \Yeti\Main\Controller\Abstracts\AController;

class Settings extends AController {

	public final function edit() {
		return view('yeti@core::settings.edit')->with('Project',
			App::scope())->with('Options', App::scope()->options())->with('Pages', Page::all());
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public final function update(){
		if (!Input::has('project_id') || App::scope()->id != Input::get('project_id')){
			throw new \Exception('Invalid request!');
		}

		App::scope()
			->update(Input::only('name', 'url', 'title', 'description'))
			->store(Input::only('deploy_path', 'index_page_id', 'login_page_id', 'error_page_id'));

		return redirect()->route('yeti@core:settings.edit')
			->withSuccess('Project settings was successful updated!');
	}

}
