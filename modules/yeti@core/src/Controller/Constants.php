<?php
namespace Yeti\Core\Controller;

use \Illuminate\Support\Facades\Input;
use \Illuminate\View\View;

use \Yeti\Core\Model\Constant;
use \Yeti\Main\Controller\Abstracts\AController;

use \Able\Helpers\Arr;

class Constants extends AController {

	/**
	 * @return View
	 */
	public function all(){
		return view('yeti@core::constants.all')->with('Constants',
			Constant::orderBy('name', 'ASC')->paginate(20));
	}

	/**
	 * @return View
	 */
	public function create(){
		return view('yeti@core::constants.create');
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function edit($id){
		return view('yeti@core::constants.edit')->with('Constant',
			Constant::findOrFail($id));
	}

	/**
	 * @return Response
	 */
	public function store() {
		$Constant = new Constant();

		$Constant->fill(Arr::only(Input::all(), 'name', 'value'));
		$Constant->save();

		return redirect()->route('yeti@core:constants.all')
			->withSuccess('Constant was successfully added!');
	}

	/**
	 * @param  int $id
	 * @return Response
	 */
	public function update($id) {
		$Constant = Constant::findOrFail($id);
		$Constant->fill(Arr::only(Input::all(), 'name', 'value'));
		$Constant->save();

		return redirect()->route('yeti@core:constants.all')
			->withSuccess('The constant\'s value was successfully changed!');

	}

	/**
	 * @param  int $id
	 * @return Response
	 */
	public function delete($id){
		Constant::destroy($id);

		return redirect()->route('yeti@core:constants.all')
			->withSuccess('Constant was successfully deleted!');

	}
}
