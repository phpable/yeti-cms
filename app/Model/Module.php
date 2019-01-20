<?php
namespace Yeti\Main\Model;

use \Illuminate\Database\Eloquent\Collection;

use \Yeti\Main\Model\Abstracts\AModel;

class Module extends AModel {

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_modules';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['maintainer', 'name', 'title', 'route', 'status'];

	/**
	 * @var array
	 */
	protected $appends = ['path'];

	/**
	 * @const string
	 */
	const MS_ACTIVE = 'active';

	/**
	 * @const string
	 */
	const MS_INACTIVE = 'inactive';

	/**
	 * @const string
	 */
	const MS_OUTDATED = 'outdated';

	/**
	 * @const string
	 */
	const MS_CORRUPTED = 'corrupted';

	/**
	 * @return Collection
	 */
	public final static function getActive(){
		return static::where('status', '=', Module::MS_ACTIVE)
			->orderBy('id')->get();
	}

	/**
	 * @return string
	 */
	public final function getPathAttribute(): string {
		return base_path('modules') . DIRECTORY_SEPARATOR
			. strtolower($this->maintainer . '@' . $this->name);
	}

	/**
	 * @return string
	 */
	public final function getRouteAttribute(): string {
		return !empty($this->attributes['route']) ? $this->attributes['route']
			: implode('.', [$this->maintainer, $this->name, 'main']);
	}

}

