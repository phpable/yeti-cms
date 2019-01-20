<?php
namespace Yeti\Blog\Model;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\HasManyThrough;
use \Illuminate\Database\Eloquent\Collection;

use \Yeti\Core\Model\Resource;
use \Yeti\Core\Model\Page;

use \Yeti\Blog\Model\Tag;
use \Yeti\Blog\Model\Topic;
use \Yeti\Blog\Model\Tag2Post;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\TUrlAttribute;

use \Able\Helpers\Arr;
use \Able\Helpers\Str;

class Post extends AModel {

	use TProject;
	use TUrlAttribute;

	/**
	 * @var string
	 */
	protected $table = 'yeti_blog_posts';

	/**
	 * @var array
	 */
	protected $fillable = ['url', 'title', 'description', 'preview',
		'body', 'topic_id'];

	/**
	 * @var array
	 */
	protected $appends = ['date', 'year', 'month',
		'cover', 'topic'];

	/**
	 * @return BelongsTo
	 */
	public function cover(){
		return $this->belongsTo(Resource::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function topic(){
		return $this->belongsTo(Topic::class);
	}

	/**
	 * @return HasManyThrough
	 */
	public function tags(){
		return Tag::whereIn('id', Tag2Post::where('post_id', '=',
			$this->id)->lists('tag_id')->toArray())->orderBy('title')->get();
	}

	/**
	 * @return string
	 */
	public function getDateAttribute(){
		return date('l, j F Y', strtotime($this
			->attributes['created_at']));
	}

	/**
	 * @param $value
	 */
	public final function setDescriptionAttribute($value){
		$this->attributes['description'] = substr($value, 255);
	}


	/**
	 * @return string
	 */
	public function getYearAttribute(){
		return date('Y', strtotime($this
			->attributes['created_at']));
	}

	/**
	 * @return string
	 */
	public function getMonthAttribute(){
		return date('m', strtotime($this
			->attributes['created_at']));
	}

	/**
	 * @return string
	 */
	public function getCoverAttribute(){
		return (string)$this->cover()->first();
	}

	/**
	 * @return Topic
	 */
	public function getTopicAttribute(){
		return $this->topic()->first();
	}

	/**
	 * @return string
	 */
	public final function getPreviewAttribute(){
		return !empty($this->attributes['preview'])
			? $this->attributes['preview'] : Str::tr($this->body, 1024);
	}

}

