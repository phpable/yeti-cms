<?php
namespace Yeti\Core\Model;

use Able\Helpers\Src;
use Able\Reglib\Regex;
use \Illuminate\Database\Eloquent\Relations\HasMany;
use \Illuminate\Database\Eloquent\Relations\MorphTo;

use \Illuminate\Support\Facades\Log;

use \Yeti\Core\Model\Page;
use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\ISourcable;
use \Yeti\Main\Model\Abstracts\TSourcable;

use \Able\Helpers\Arr;
use \Able\Reglib\Reglib;

use \Able\IO\Abstractions\ILocated;

class Template extends AModel
	implements ISourcable {

	use TProject;
	use TSourcable;

	/**
	 * @var array
	 */
	protected static $Allowed = ['html', 'js', 'css'];

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_templates';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'type', 'source',
		'owner', 'owner_type', 'owner_id'];

	/**
	 * @return MorphTo
	 */
	public final function owner(): MorphTo {
		return $this->morphTo();
	}

	/**
	 * @param AModel $Owner
	 */
	public function setOwnerAttribute(AModel $Owner){
		$this->attributes['owner_id'] = $Owner->id;
		$this->attributes['owner_type'] = $Owner->getType();
	}

	/**
	 * @param $value
	 * @throws \Exception
	 */
	public final function setTypeAttribute($value){
		if(!in_array($value = strtolower(trim($value)), self::$Allowed)){
			throw new \Exception('Invalid source type!');
		}

		$this->attributes['type'] = $value;
	}

	/**
	 * @param string $value
	 */
	public function setNameAttribute($value) {
		$this->attributes['name'] = strtolower(trim($value));
	}

	/**
	 * @param string $value
	 */
	public function setSourceAttribute($value) {
		$this->attributes['source'] = trim($value);
	}

	/**
	 * @param AModel $Owner
	 * @param string $type
	 * @param string $prefix
	 * @throws \Exception
	 * @return string
	*/
	public static final function generate(AModel $Owner, string $type, string $prefix = 'main'){
		if (!preg_match('/^' . Regex::RE_VARIABLE . '$/', $prefix)){
			throw new \Exception('Invalid name format');
		}

		$Names = $Owner->templates->where('type', $type)
			->pluck('name')->toArray();

		for($i = 0; in_array($out = preg_replace('/00$/', null,
			sprintf($prefix . "%'02s", $i)), $Names); $i++);

		return $out;
	}

	/**
	 * @return string
	 */
	public final function getContent(): string {
		return (string)$this->source;
	}

	/**
	 * @return mixed
	 */
	public final function __toString(){
		return $this->getContent();
	}

	/**
	 * @return string
	 */
	public final function getLocation(): string {
		return '{' . Src::fcm(Src::rns(get_class($this->owner))) . '}'
			. $this->owner->name . ':{template[' . $this->type . ']}' . $this->name;
	}
}

Template::saving(function(Template $Template){
	$Template->hash = md5($Template->source);
});
