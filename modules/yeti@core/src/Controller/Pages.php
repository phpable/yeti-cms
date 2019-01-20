<?php
namespace Yeti\Core\Controller;

use http\Env\Response;
use \Illuminate\View\View;
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

class Pages extends AController {

	/**
	 * @return View
	 */
	public function all(){
		if (Input::has('filter')
			&& in_array(strtolower(Input::get('filter')), range('a', 'z'))){

			return view('yeti@core::pages.all')->with('Pages', Page::orderBy('name', 'ASC')
					->where('name', 'like', Input::get('filter'). '%')->paginate(20)
						->appends(Input::only('filter')))->with('filter', Input::get('filter'));
		}

		return view('yeti@core::pages.all')
			->with('Pages', Page::orderBy('name', 'ASC')->paginate(20));
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function edit($id){
		return view('yeti@core::pages.edit')->with('Page', Page::findOrFail($id))
			->with('Layouts', Layout::all());
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws \Exception
	 */
	public function settings($id) {
		return view('yeti@core::pages.settings')->with('Page',Page::findOrFail($id))
			->with('Layouts', Layout::all())->with('Templates', Layout::collectTemplates('html'));
	}

	/**
	 * @return Redirect
	 */
	public function create(){
		return redirect()->route('yeti@core:pages.edit', Page::create(['name' => 'page' . time()])->id)
			->withSuccess('Successful Created!');
	}

	/**
	 * @param  int $id
	 * @return Response
	 */
	public function update($id) {
		$Page = Page::findOrFail($id);

		$Page->fill(array_merge(['in_sitemap'=> false],
			Input::except('arguments')));

		if (Input::has('layout')) {
			$Page->layout()->associate(Layout::findOrFail(Input::get('layout')));
		}

		if (Input::has('template')) {
			$Template = $Page->layout->templates->find(Input::get('template'));

			if (!is_null($Template)){
				$Page->template()->associate($Template);
			}
		}

		if (Input::has('arguments')) {
			$Page->arguments = Input::get('arguments');
		}

		$Page->save();
		Bus::dispatch(new UpdateSources($Page, Input::get('sources', [])));

		return redirect()->route('yeti@core:pages.edit', $id)
			->withSuccess('Successful Saved!');
	}

	/**
	 * @param int $id
	 * @return View
	 */
	public function delete($id){
		Page::findOrFail($id)->delete();

		return redirect()->route('yeti@core:pages.all', ['filter'
			=> session(get_class($this))])->withSuccess('Successful Deleted!');
	}

}
