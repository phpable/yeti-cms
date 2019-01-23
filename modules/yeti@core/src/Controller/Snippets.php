<?php
namespace Yeti\Core\Controller;

use \Illuminate\View\View;
use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Bus;

use \Yeti\Core\Model\Snippet;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Macros\UpdateSources;
use \Yeti\Main\Controller\Abstracts\AController;

use \Able\Helpers\Arr;

class Snippets extends AController {

	/**
	 * @return View
	 */
	public function all(){
		if (Input::has('filter')
			&& in_array(strtolower(Input::get('filter')), range('a', 'z'))){

				return view('yeti@core::snippets.all')->with('Snippets', Snippet::orderBy('name', 'ASC')
						->where('name', 'like', Input::get('filter'). '%')->paginate(20)->appends(Input::only('filter')))
							->with('filter', Input::get('filter'));
		}

		return view('yeti@core::snippets.all')
			->with('Snippets', Snippet::orderBy('name', 'ASC')->paginate(20));
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function edit($id){
		return view('yeti@core::snippets.edit')->with('Snippet',
			Snippet::findOrFail($id));
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function settings($id){
		return view('yeti@core::snippets.settings')
			->with('Snippet', Snippet::findOrFail($id));
	}

	/**
	 * @return Redirect
	 */
	public function create(){
		$Snippet = Snippet::create(['name' => 'snippet' . time()]);

		(new Template(['name' => 'main',
			'owner' => $Snippet]))->save();

		return redirect()->route('yeti@core:snippets.edit', $Snippet->id)
			->withSuccess('Successful Created!');

	}

	/**
	 * @param  int $id
	 * @return Response
	 */
	public function update($id) {
		$Snippet = Snippet::findOrFail($id);

		Bus::dispatch(new UpdateSources($Snippet, Input::get('sources', [])));

		return redirect()->route('yeti@core:snippets.edit', $Snippet->id)
			->withSuccess('Successful Saved!');

	}

	/**
	 * @param  int $id
	 * @return Response
	 */
	public function updateSettings($id) {
		$Snippet = Snippet::findOrFail($id);
		$Snippet->fill(Arr::only(Input::all(), 'name', 'content', 'params'));
		$Snippet->save();

		return redirect()->route('yeti@core:snippets.settings', $Snippet->id)
			->withSuccess('Successful Saved!');

	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function delete($id){
		Snippet::findOrFail($id)->delete();

		return redirect()->route('yeti@core:snippets.all', ['filter'
			=> session(get_class($this))])->withSuccess('Successful Deleted!');
	}

}
