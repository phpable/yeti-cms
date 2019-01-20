<?php
namespace Yeti\Core\Model;

use \Able\Prototypes\IStringable;
use \Able\Prototypes\TStringable;

use \Yeti\Main\Model\Abstracts\AModel;
use \Yeti\Main\Model\Abstracts\TProject;

class Resource extends AModel implements IStringable {
	use TProject;
	use TStringable;

	/**
	 * @var string
	 */
	protected $table = 'yeti_main_resources';

	/**
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'type', 'category', 'path'];

	/**
	 * @return string
	 */
	public final function toString(): string {
		return $this->name;
	}
}
