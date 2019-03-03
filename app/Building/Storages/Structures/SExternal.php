<?php
namespace Yeti\Main\Building\Storages\Structures;

use \Able\Struct\AStruct;
use \Exception;

/**
 * @property string type
 * @property string property
 * @property string content
 */
class SExternal extends AStruct {

	/**
	 * @const string
	 */
	const ET_STYLE = 'style';

	/**
	 * @const string
	 */
	const ET_SCRIPT = 'script';

	/**
	 * @const string
	 */
	const ET_CANONICAL = 'canonical';

	/**
	 * @var array
	 */
	protected static array $Prototype = ['type', 'link'];

	/**
	 * @param string $value
	 * @return string
	 * @throws Exception
	 */
	public final function setTypeProperty(string $value): string {
		if (!in_array($value = strtolower(trim($value)), [self::ET_CANONICAL,
			self::ET_SCRIPT, self::ET_STYLE])){
				throw new Exception(sprintf('Invalid metadata type: %s!', $value));
		}

		return $value;
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public final function setLinkProperty(string $value): string {
		return $value;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public final function toHtml(): string {
		switch ($this->type){
			case self::ET_CANONICAL:
				return '<link rel="canonical" href="' . $this->link . '" />';
			case self::ET_STYLE:
				return '<link rel="stylesheet" type="text/css" href="' . $this->link . '" />';
			case self::ET_SCRIPT:
				return '<script type="text/javascript" src="' . $this->link . '"></script>';
			default:
				throw new Exception(sprintf('Undefined exgternal resource type: %s!'), $this->type);
		}
	}
}


