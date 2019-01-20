<?php
namespace Yeti\Core\Model;

use \Able\Helpers\Arr;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Meta;
use \Yeti\Core\Model\External;

use \Yeti\Main\Building\Storages\Structures\SMeta;
use \Yeti\Main\Building\Storages\Structures\SExternal;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\TTemplatable;
use \Yeti\Main\Model\Abstracts\ITemplatable;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Layout extends AModel
	implements ITemplatable {

	use TProject;
	use TTemplatable;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_layouts';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name'];

	/**
	 * @var array
	 */
	protected $appends = ['content'];

	/**
	 * @return HasMany
	 */
	public final function pages() {
		return $this->hasMany(Page::class);
	}

	/**
	 * @return HasMany
	 */
	public final function metas(){
		return $this->hasMany(Meta::class);
	}

	/**
	 * @return SMeta[]
	 */
	public final function retrieveMetasList(): array {
		return array_map(function($Meta){
			return new SMeta($Meta['type'], $Meta['property'], $Meta['content']);
		}, $this->metas->toArray());
	}

	/**
	 * @return HasMany
	 */
	public final function externals(){
		return $this->hasMany(External::class);
	}

	/**
	 * @return SExternal[]
	 */
	public final function retrieveExternalsList(): array {
		return array_map(function($Meta){
			return new SExternal($Meta['type'], $Meta['link']);
		}, $this->externals->toArray());
	}

	/**
	 * @return string
	 */
	public function getContentAttribute() {
		return (string)$this->templates->first();
	}

	/**
	 * @param string $type
	 * @return array
	 * @throws \Exception
	 */
	public static final function collectTemplates(string $type): array {
		if (!in_array($type, ['html', 'js', 'css'])){
			throw new \Exception(sprintf('Invalid type: %s!', $type));
		}

		$Data = [];
		foreach (Template::where('owner_type', '=', Layout::class)
			->where('type', '=', $type)->get() as $Template){

				$Data[$Template->owner_id][$Template->id] = $Template->name;
		}

		return $Data;
	}
}
