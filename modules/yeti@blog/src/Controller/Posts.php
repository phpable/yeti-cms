<?php
namespace Yeti\Blog\Controller;

use \Illuminate\View\View;
use \Illuminate\Http\Response;

use \Illuminate\Support\Facades\Input;
use \Illuminate\Support\Facades\Redirect;

use \Able\Helpers\Str;
use \Able\Helpers\Arr;

use \Yeti\Blog\Model\Post;
use \Yeti\Blog\Model\Topic;
use \Yeti\Blog\Model\Author;

use \Yeti\Main\Controller\Abstracts\AController;

class Posts extends AController {

	/**
	 * @return Response
	 */
	public function all() {
		if (Input::has('filter')
			&& in_array(strtolower(Input::get('filter')), range('a', 'z'))){

			return view('yeti@blog::posts.all')->with('Posts', Post::orderBy('created_at', 'DESC')
				->where('title', 'like', Input::get('filter'). '%')->paginate(20)
				->appends(Input::only('filter')))->with('filter', Input::get('filter'));
		}

		return view()->make('yeti@blog::posts.all')
			->with('Posts', Post::orderBy('updated_at', 'DESC')->paginate(15));
	}

	/**
	 * @return Response
	 */
	public function add() {
		return view()->make('yeti@blog::posts.settings')
			->with('Topics', Topic::orderBy('title')->get())
			->with('Authors', Author::orderBy('name')->get());
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function edit($id) {
		return view()->make('yeti@blog::posts.edit')
			->with('Post', Post::findOrFail($id));
	}

	/**
	 * @param $id
	 * @return Response
	 */
	public function settings($id) {
		return view()->make('yeti@blog::posts.settings')
			->with('Post', Post::findOrFail($id))
			->with('Topics', Topic::orderBy('title')->get())
			->with('Authors', Author::orderBy('name')->get());
	}

	/**
	 * @return Redirect
	 */
	public function save() {
		$Post = Post::create(Input::except('topic_id', 'author_id'));

		if (Input::has('topic_id')) {
			$Post->topic()->associate(Topic::findOrFail(Input::get('topic_id')));
		}

		if (Input::has('author_id')) {
			$Post->author()->associate(Author::findOrFail(Input::get('author_id')));
		}

		$Post->save();

		return redirect()->route('yeti@blog:posts.edit', $Post->id)
			->withSuccess('New post was successful created!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function update($id) {
		$Post = Post::findOrFail($id)->update(Input::all());

		if (Input::has('topic_id')) {
			$Post->topic()->associate(Topic::findOrFail(Input::get('topic_id')));
		}

		if (Input::has('author_id')){
			$Post->author()->associate(Author::findOrFail(Input::get('author_id')));
		}

		$Post->groups = preg_split('/\s*,+\s*/',
			Input::get('groups'), -1, PREG_SPLIT_NO_EMPTY);

		$Post->save();

		return redirect()->route('yeti@blog:posts.settings', $id)
			->withSuccess('The post was successful updated!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function updateContent($id) {
		Post::findOrFail($id)->update(Input::only('body'));

		return redirect()->route('yeti@blog:posts.edit', $id)
			->withSuccess('The post content was successful updated!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function publish($id) {
		Post::findOrFail($id)->update(['is_published' => true]);

		return redirect()->route('yeti@blog:posts.all')
			->withSuccess('The blog post was successful published!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function unpublish($id) {
		Post::findOrFail($id)->update(['is_published' => false]);

		return redirect()->route('yeti@blog:posts.all')
			->withSuccess('The post was successful hide from publishing!');
	}

	/**
	 * @param $id
	 * @return Redirect
	 */
	public function delete($id) {
		Post::findOrFail($id)->delete();

		return redirect()->route('yeti@blog:posts.all')
			->withSuccess('The post was successfully deleted!');
	}
}
