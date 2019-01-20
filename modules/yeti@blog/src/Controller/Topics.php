<?php
namespace Yeti\Blog\Controller;

use \Illuminate\View\View;
use \Illuminate\Http\Response;

use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Redirect;

use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Topic;

use \Yeti\Main\Controller\Abstracts\AController;

class Topics extends AController {

	/**
	 * @return Response
	 */
	public function all(){
		return view()->make('yeti@blog::topics.all')
			->with('Topics', Topic::orderBy('title', 'asc')->paginate(25));
	}

	/**
	 * @return Response
	 */
	public function add(){
		return view()->make('yeti@blog::topics.edit');
	}

	/**
	 * @return Redirect
	 */
	public function save(){
		$Topic = Topic::create(array_merge(['url' => 'topic'
			. (Topic::count() + 1)], Input::all()));

		return redirect()->route('yeti@blog:topics.edit', $Topic->id)
			->withSuccess('Blog topic was successful saved!');
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function edit($id){
		return view()->make('yeti@blog::topics.edit')
			->with('Topic', Topic::findOrFail($id));
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function update($id){
		$Topic = Topic::findOrFail($id)->update(Input::all());

		return redirect()->to(previous())
			->withSuccess('Blog topic was successful saved!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function delete($id){
		$Topic = Topic::findOrFail($id)->delete();

		return redirect()->route('yeti@blog:topics.all')
			->withSuccess('Blog topic was successful deleted!');
	}

}
