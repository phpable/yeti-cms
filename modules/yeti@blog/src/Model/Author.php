<?php
namespace Yeti\Blog\Model;

use \Yeti\Blog\Model\Post;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\TUrlAttribute;


use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends AModel {
	use TProject;
	use TUrlAttribute;

	/**
	 * @var string
	 */
	protected $table = 'yeti_blog_authors';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['url', 'meta_title', 'meta_description',
		'name', 'photo', 'info'];

	/**
	 * @var array
	 */
	protected $appends = ['posts_count'];

	/**
	 * @return int
	 */
	public function getPostsCountAttribute(){
		return Post::where('author_id', '=', $this->id)->count();
	}

	/**
	 * @return HasMany
	 */
	public final function posts(){
		return $this->hasMany(Post::class);
	}

}
