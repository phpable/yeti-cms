<?php
namespace Yeti\Blog\Model;

use \Yeti\Blog\Model\Post;
use \Yeti\Blog\Model\Tag2Post;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;

use \Illuminate\Database\Eloquent\Collection;

class Tag extends AModel {
	use TProject;

	/**
	 * @var string
	 */
	protected $table = 'yeti_blog_tags';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['title'];

	/**
	 * @var array
	 */
	protected $appends = ['url', 'weigth'];

	/**
	 * @return Collection
	 */
	public function getPostsAttribute(){
		return Post::whereIn('id', Tag2Post::where('tag_id',
			'=', $this->id)->lists('post_id')->toArray())->get();
	}

	/**
	 * @return string
	 */
	public function getUrlAttribute(){
		return strtolower(preg_replace('/\s+/', '_', strtolower(trim($this->title))));
	}

	/**
	 * @return int
	 */
	public function getWeigthAttribute(){
		return Post::whereIn('id', Tag2Post::where('tag_id',
					'=', $this->id)->lists('post_id')->toArray())->count();
	}

}
