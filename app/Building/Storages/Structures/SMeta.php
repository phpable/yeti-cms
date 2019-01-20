<?php
namespace Yeti\Main\Building\Storages\Structures;

use \Able\Struct\AStruct;

/**
 * @property string type
 * @property string property
 * @property string content
 */
class SMeta extends AStruct {

	/**
	 * @const string
	 */
	const MT_NAME = 'name';

	/**
	 * @const string
	 */
	const MT_HTTP_EQUIV = 'http-equiv';

	/**
	 * @var array
	 */
	protected static $Prototype = ['type', 'property', 'content'];

	/**
	 * @param string $value
	 * @return string
	 * @throws \Exception
	 */
	public final function setTypeProperty(string $value): string {
		if (!in_array($value = strtolower(trim($value)), [self::MT_HTTP_EQUIV, self::MT_NAME])){
			throw new \Exception(sprintf('Invalid metadata type: %s!', $value));
		}

		return $value;
	}

	/**
	 * @param string $value
	 * @return string
	 * @throws \Exception
	 */
	public final function setPropertyProperty(string $value): string {
		if (!preg_match('/^[A-Za-z0-9_-]+$/', $value)){
			throw new \Exception(sprintf('Invalid metadata name: %s!', $value));
		}

		return $value;
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public final function setContentProperty(string $value): string {
		return $value;
	}

	/**
	 * @return string
	 */
	public final function toHtml(): string {
		return '<meta ' . $this->type . '="' . htmlspecialchars($this->property)
			. '" content="' . $this->content . '" />';
	}
}


