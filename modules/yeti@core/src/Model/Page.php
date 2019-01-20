<?php
namespace Yeti\Core\Model;

use \Able\Helpers\Arr;
use \Able\Helpers\Arg;
use \Able\Helpers\Src;

use \Yeti\Core\Model\Layout;
use \Yeti\Main\Model\Project;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TConfig;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\ITemplatable;
use \Yeti\Main\Model\Abstracts\TTemplatable;
use \Yeti\Main\Model\Abstracts\TArgumentable;

use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends AModel
	implements ITemplatable {

	use TConfig;
	use TProject;
	use TTemplatable;
	use TArgumentable;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_pages';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'url', 'title', 'description',
		'builder', 'arguments', 'mode', 'in_sitemap'];

	/**
	 * @var array
	 */
	protected $appends = ['absolute_url'];

	/**
	 * @const string
	 */
	const M_REGULAR = 'regular';

	/**
	 * @const string
	 */
	const M_AUTH = 'auth';

	/**
	 * @const string
	 */
 	const M_GUEST = 'guest';

	/**
	 * @return BelongsTo
	 */
	public function layout() {
		return $this->belongsTo(Layout::class);
	}

	/**
	 * @return BelongsTo
	 */
	public function template() {
		return $this->belongsTo(Template::class);
	}

	/**
	 * @return string
	 */
	public final function getLayoutPartialName(){
		return !is_null($this->template) ? $this->template->name : 'main';
	}

	/**
	 * @param string $value
	 */
	public final function setNameAttribute($value){
		$this->attributes['name'] = strtolower(trim($value));
	}

	/**
	 * @param string $value
	 */
	public final function setUrlAttribute($value) {
		$this->attributes['url'] = strlen($value = trim(strtolower($value))) > 0
			? ('/' . preg_replace('/^\/+/', null, preg_replace('/\/+/', '/', $value))) : null;
	}

	/**
	 * @return string
	 */
	public final function getTitleAttribute(){
		return $this->fromConfig('title');
	}

	/**
	 * @param string $value
	 */
	public final function setTitleAttribute($value){
		$this->toConfig('title', $value);
	}

	/**
	 * @return string
	 */
	public final function getDescriptionAttribute(){
		return $this->fromConfig('description');
	}

	/**
	 * @param string $value
	 */
	public final function setDescriptionAttribute($value){
		$this->toConfig('description', $value);
	}

	/**
	 * @return string
	 */
	public final function getAbsoluteUrlAttribute(){
		return implode('/', array_filter([rtrim($this->project->domain, '/'), ltrim($this->url, '/')]));
	}

	/**
	 * @param array $Args
	 * @return string
	 */
	public final function route(array $Args = []){
		return preg_replace_callback('/\{\$[A-Za-z][A-Za-z0-9_-]+\}/', function($Maches) use (&$Args){
			return array_shift($Args); }, $this->url);
	}
}

Page::created(function(Page $Page){
	$Page->templates()->create(['name' => 'main']);
});
