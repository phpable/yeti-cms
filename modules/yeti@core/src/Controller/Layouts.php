<?php
namespace Yeti\Core\Controller;

use \Able\Helpers\Arr;

use \Yeti\Core\Model\Layout;
use \Yeti\Core\Model\Template;

use Yeti\Main\Macros\UpdateExternals;
use \Yeti\Main\Macros\UpdateMetas;
use \Yeti\Main\Macros\UpdateSources;

use \Yeti\Main\Controller\Abstracts\AController;

use \Illuminate\View\View;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Bus;
use \Illuminate\Support\Facades\Redirect;

class Layouts extends AController {

	/**
	 * @return View
	 */
	public function all() {
		return view('yeti@core::layouts.all')->with('Layouts',
			Layout::orderBy('name', 'ASC')->paginate(20));
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function edit($id){
		return view('yeti@core::layouts.edit')->with('Layout',
			Layout::findOrFail($id));
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function settings($id){
		return view('yeti@core::layouts.settings')
			->with('Layout', Layout::findOrFail($id));
	}

	/**
	 * @return Redirect
	 */
	public function create(){
		$Layout = Layout::create(['name' => 'layout' . time()]);

		(new Template(['name' => 'main',
			'owner' => $Layout]))->save();

		return redirect()->route('yeti@core:layouts.edit', $Layout->id)
			->withSuccess('Successful Created!');

	}

	/**
	 * @param  int $id
	 * @return Redirect
	 */
	public function update($id) {
		$Layout = Layout::findOrFail($id);

		Bus::dispatch(new UpdateSources($Layout,
			Input::get('sources', [])));

		return redirect()->route('yeti@core:layouts.edit', $Layout->id)
			->withSuccess('Successful Saved!');

	}

	/**
	 * @param  int $id
	 * @return Redirect
	 */
	public function updateSettings($id) {
		$Layout = Layout::findOrFail($id);

		$Layout->fill(Arr::only(Input::all(), 'name'));
		$Layout->save();

		Bus::dispatch(new UpdateMetas($Layout,
			Input::get('meta', [])));

		Bus::dispatch(new UpdateExternals($Layout,
			Input::get('external', [])));

		return redirect()->route('yeti@core:layouts.settings', $Layout->id)
			->withSuccess('Successful Saved!');

	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function delete($id){
		Layout::findOrFail($id)
			->delete();

		return redirect()->route('yeti@core:layouts.all');
	}
}
