<?php
namespace Yeti\Core\Model;

use \Able\Helpers\Arr;

use \Yeti\Core\Model\Page;
use \Yeti\Core\Model\Template;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;
use \Yeti\Main\Model\Abstracts\ITemplatable;
use \Yeti\Main\Model\Abstracts\TTemplatable;

class Snippet extends AModel
	implements ITemplatable {

	use TProject;
	use TTemplatable;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_snippets';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'params'];

	/**
	 * @var array
	 */
	protected $appends = ['content'];

	/**
	 * @return string
	 */
	public function getContentAttribute() {
		return (string)$this->templates->where('type', 'html')
			->first();
	}

	/**
	 * @param string $value
	 */
	public function setParamsAttribute($value) {
		$this->attributes['params'] = implode(',', array_filter(preg_split('/\s*,+\s*/', $value, -1,
			PREG_SPLIT_NO_EMPTY), function ($value) { return preg_match('/^[A-Za-z][A-Za-z0-9_]{2,}$/', $value); }));
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public static function findByName($name) {
		return static::where('name', '=', strtolower(trim($name)))
			->first();
	}

	/**
	 * @param array $Params
	 * @return string
	 */
	public function parse(array $Params = []) {
		$Params = array_change_key_case(array_combine(preg_split('/\s*,+\s*/', $this->params,
			-1, PREG_SPLIT_NO_EMPTY), $Params), CASE_LOWER);
		return preg_replace_callback('/\{\{ *\%([A-Za-z][A-Za-z0-9_]*) *\}\}/m', function (array $Maches) use ($Params) {

			return array_key_exists($Maches[1] = strtolower(trim($Maches[1])), $Params)
				? $Params[$Maches[1]] : null;

		}, $this->content);
	}

}


