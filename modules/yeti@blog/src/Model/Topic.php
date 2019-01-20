<?php
namespace Yeti\Blog\Model;

use \Yeti\Blog\Model\Post;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\TUrlAttribute;

use \Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends AModel {
	use TProject;
	use TUrlAttribute;

	/**
	 * @var string
	 */
	protected $table = 'yeti_blog_topics';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['url', 'title', 'title', 'description'];

	/**
	 * @var array
	 */
	protected $appends = ['posts_count'];

	/**
	 * @return HasMany
	 */
	public function posts(){
		return $this->hasMany(Post::class);
	}

	/**
	 * @return int
	 */
	public function getPostsCountAttribute(){
		return Post::where('topic_id', '=', $this->id)
			->count();
	}

	/**
	 * @param string $value
	 */
	public final function setDescriptionAttribute(string $value){
		$this->attributes['description'] = trim(substr($value, 0, 255));
	}

}
