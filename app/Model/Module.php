<?php
namespace Yeti\Main\Model;

use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Eloquent\Builder;

use \Yeti\Main\Model\Abstracts\AModel;

use \Able\IO\Path;

use \Able\Helpers\Str;
use \Able\Helpers\Src;
use \Able\Helpers\Jsn;
use \Able\Helpers\Arr;

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
	protected $fillable = ['maintainer', 'name', 'title', 'status'];

	/**
	 * @var array
	 */
	protected $appends = ['route'];

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
	 * @param string $value
	 * @throws \Exception
	 */
	public final function setStatusAttribute(string $value): void {
		if (!in_array($value, [self::MS_ACTIVE,
			self::MS_CORRUPTED, self::MS_INACTIVE, self::MS_OUTDATED])) {
				throw new \Exception(sprintf('Invalid status: %s!', $value));
		}

		$this->attributes['status'] = $value;
	}

	/**
	 * @return Builder
	 */
	public final static function whereActive(){
		return self::where('status', '=', Module::MS_ACTIVE);
	}

	/**
	 * @return string
	 */
	public final function getMnemonic(): string {
		return Str::join('@', Src::fcm($this->maintainer, '-'), Src::fcm($this->name, '-'));
	}

	/**
	 * @return string
	 */
	public final function getNamespace(): string {
		return Str::join('\\', Src::tcm($this->maintainer), Src::tcm($this->name));
	}

	/**
	 * @var Path
	 */
	private $Path = null;

	/**
	 * @return Path
	 * @throws \Exception
	 */
	public final function getPath(): Path {
		if (is_null($this->Path)){
			$this->Path = Path::create(base_path('modules'), $this->getMnemonic());
		}

		return $this->Path->toPath();
	}

	/**
	 * @var array
	 */
	private $Manifest = null;

	/**
	 * @param string $name
	 * @param string $default
	 * @return string|null
	 * @throws \Exception
	 */
	public final function manifest(string $name, string $default = null): ?string {
		try {
			if (is_null($this->Manifest)) {
				$this->Manifest = Jsn::decode($this->getPath()
					->append('manifest.json')->toFile()->getContent());

			}

			return Arr::follow($this->Manifest, ...preg_split('/\.+/',
				$name, -1, PREG_SPLIT_NO_EMPTY)) ?? $default;

		}catch (\Exception $Exception){
			throw new \Exception(sprintf('Can\'t load the manifest file: %s!',
				$Exception->getMessage()));
		}
	}

	/**
	 * @const string
	 */
	protected const DEFAULT_ROUTE = 'main';

	/**
	 * @return string
	 * @throws \Exception
	 */
	public final function getRouteAttribute(): string {
		return Str::join(':', $this->getMnemonic(),
			$this->manifest('routes.default', self::DEFAULT_ROUTE));
	}
}

