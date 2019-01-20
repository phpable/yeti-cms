<?php
namespace Yeti\Blog\Controller;

use \Illuminate\View\View;
use \Illuminate\Http\Response;

use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Redirect;

use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Post;
use \Yeti\Blog\Model\Topic;

use \Yeti\Main\Controller\Abstracts\AController;

class Posts extends AController {

	/**
	 * @return Response
	 */
	public function all(){
		return view()->make('yeti@blog::posts.all')
			->with('Posts', Post::orderBy('updated_at', 'desc')->paginate(15));
	}

	/**
	 * @return Response
	 */
	public function add(){
		return view()->make('yeti@blog::posts.settings')
			->with('Topics', Topic::orderBy('title')->get());
	}

	/**
	 * @return Redirect
	 */
	public function save(){
		$Post = Post::create(array_merge(['url' => 'page'
			. (Post::count() + 1)], Input::all()));

		return redirect()->route('yeti@blog:posts.edit', $Post->id)
			->withSuccess('Blog post was successful saved!');
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function edit($id){
		return view()->make('yeti@blog::posts.edit')
			->with('Post', Post::findOrFail($id));
	}

	/**
	 * @param $id
	 * @return Response
	 */
	public function settings($id){
		return view()->make('yeti@blog::posts.settings')
			->with('Post', Post::findOrFail($id))->with('Topics', Topic::orderBy('title')->get());
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function update($id){
		$Post = Post::findOrFail($id)->update(Input::all());

		return redirect()->to(previous())
			->withSuccess('Blog post was successful saved!');
	}

}
