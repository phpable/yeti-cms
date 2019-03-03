<?php
namespace Yeti\Main\Model;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Layout;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TConfig;

use \Illuminate\Support\Facades\Cookie;
use \Illuminate\Support\Facades\Session;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

use \Able\Helpers\Jsn;
use \Able\Helpers\Arr;
use \Able\Helpers\Str;

use \Able\IO\Path;

use Exception;

class Project
	extends AModel {

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_projects';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'domain', 'builded_at',
		'objects', 'deployed_at'];

	/**
	 * @var array
	 */
	protected $appends = ['uid',
		'path', 'hash'];

	/**
	 * @var array
	 */
	protected $hidden = ['storage'];

	/**
	 * @var array
	 */
	protected $casts = [
		'objects' => 'array'
	];

	/**
	 * @return array
	 * @throws Exception
	 */
	public final function options(): array {
		return Jsn::decode($this->storage);
	}

	/**
	 * @param string $name
	 * @return null|string
	 * @throws Exception
	 */
	public final function option(string $name): ?string {
		return Arr::get($this->options(), $name);
	}

	/**
	 * @param array $Options
	 * @throws Exception
	 */
	public final function store(array $Options){
		$this->storage = Jsn::merge($this->storage, $Options);
		$this->save();
	}

	/**
	 * @return string
	 */
	public final function getUidAttribute(): string {
		return sprintf('%1$08d', $this->id);
	}

	/**
	 * @return Path
	 * @throws Exception
	 */
	public final function getPathAttribute(): Path {
		return Path::create(base_path('projects'), $this->name);
	}

	/**
	 * @return string
	 */
	public final function getHashAttribute(): string {
		return md5(Str::join('|', Arr::collect($this->id, (string)$this->created_at)));
	}
}

