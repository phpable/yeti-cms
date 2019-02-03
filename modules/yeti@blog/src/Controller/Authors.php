<?php
namespace Yeti\Blog\Controller;

use \Illuminate\View\View;
use \Illuminate\Http\Response;

use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Redirect;

use \Able\Helpers\Str;
use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Author;

use \Yeti\Main\Controller\Abstracts\AController;

class Authors extends AController {

	/**
	 * @return Response
	 */
	public function all() {
		if (Input::has('filter')
			&& in_array(strtolower(Input::get('filter')), range('a', 'z'))){

			return view('yeti@blog::authors.all')->with('Authors', Author::orderBy('name', 'ASC')
				->where('name', 'like', Input::get('filter'). '%')->paginate(20)
				->appends(Input::only('filter')))->with('filter', Input::get('filter'));
		}

		return view()->make('yeti@blog::authors.all')
			->with('Authors', Author::orderBy('name', 'ASC')->paginate(15));
	}

	/**
	 * @return Response
	 */
	public function add() {
		return view()->make('yeti@blog::authors.settings');
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function edit($id) {
		return view()->make('yeti@blog::authors.edit')
			->with('Author', Author::findOrFail($id));
	}

	/**
	 * @return Redirect
	 */
	public function save() {
		$Author = Author::create(Input::all());

		return redirect()->route('yeti@blog:authors.edit', $Author->id)
			->withSuccess('New author was successful created!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function update($id) {
		Author::findOrFail($id)->update(Input::all());

		return redirect()->route('yeti@blog:authors.edit', $id)
			->withSuccess('Author\'s data were successfully updated!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function delete($id) {
		Author::findOrFail($id)->delete();

		return redirect()->route('yeti@blog:authors.all')
			->withSuccess('Author\'s data were successfully deleted!');
	}
}
