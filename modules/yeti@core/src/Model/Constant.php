<?php
namespace Yeti\Core\Model;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;

use \Able\Reglib\Regex;

class Constant extends AModel {
	use TProject;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_constants';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'value'];

	/**
	 * @param string $value
	 * @throws \Exception
	 */
	public final function setNameAttribute(string $value): void {
		if (!preg_match('/^' . Regex::RE_VARIABLE . '$/', ($value = trim($value)))){
			throw new \Exception('Invalid variable name!');
		}

		$this->attributes['name'] = $value;
	}
}
