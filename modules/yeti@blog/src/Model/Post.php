<?php
namespace Yeti\Blog\Model;

use \Illuminate\Support\Facades\DB;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\HasManyThrough;
use \Illuminate\Database\Eloquent\Collection;

use \Yeti\Core\Model\Resource;
use \Yeti\Core\Model\Page;

use \Yeti\Blog\Model\Topic;
use \Yeti\Blog\Model\Author;

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
	protected $fillable = ['url', 'title', 'meta_title', 'meta_description', 'preview',
		'body', 'markers', 'is_published'];

	/**
	 * @var array
	 */
	protected $appends = ['topic', 'author', 'lightweight'];

	/**
	 * @var array
	 */
	protected $casts = [
		'groups' => 'array',
	];

	/**
	 * @return BelongsTo
	 */
	public function topic(): BelongsTo {
		return $this->belongsTo(Topic::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function author(): BelongsTo {
		return $this->belongsTo(Author::class);
	}

	/**
	 * @return Topic
	 */
	public function getTopicAttribute(): Topic {
		return $this->topic()->first();
	}

	/**
	 * @return Author
	 */
	public function getAuthorAttribute(): Author {
		return $this->author()->first();
	}

	/**
	 * @return string
	 */
	public final function getBodyAttribute(): string {
		return (string)$this->attributes['body'];
	}

	/**
	 * @return string
	 */
	public final function getPreviewAttribute(): string {
		return !empty($this->attributes['preview'])
			? $this->attributes['preview'] : Str::tr(Str::strip($this->body), 1000);
	}

	/**
	 * @return string
	 */
	public final function getLightweightAttribute() {
		return Str::strip(Str::tr($this->body, 5500), 'p', 'a', 'ul', 'ol', 'li');
	}

	/**
	 * @return string
	 */
	public final function getDescriptionAttribute(): string {
		return !empty($this->attributes['description'])
			? $this->attributes['description'] : Str::tr($this->getPreviewAttribute(), 255);
	}

}

