<?php
namespace Yeti\Main\Building\Containers\Abstractions;

use \Able\IO\ReadingContainer;
use \Able\Helpers\Src;

use \Yeti\Main\Model\Abstracts\ITemplatable;

abstract class AContainer extends ReadingContainer {

	/**
	 * @param ITemplatable $Model
	 * @return AContainer
	 * @throws \Exception
	 */
	public final static function produce(ITemplatable $Model): AContainer {
		if (!class_exists($class = Src::lns(static::class, 2)
			. '\\' . Src::rns(get_class($Model)))){
				throw new \Exception(sprintf('Undefined container class %s', $class));
		}

		return new $class($Model);
	}

	/**
	 * @var ITemplatable
	 */
	protected $Source = null;

	/**
	 * @param ITemplatable $Source
	 */
	public final function __construct(ITemplatable $Source) {
		$this->Source = $Source;
	}

	/**
	 * @var array
	 */
	private $Params = [];

	/**
	 * @param array $Params
	 */
	public final function assignParams(array $Params = []){
		$this->Params = $Params;
	}

	/**
	 * @return \Generator
	 */
	public function read(): \Generator {
		$hash = 'e_' . md5(microtime(true));

		yield '@section(' . $hash . ')';

		foreach ($this->Params as $name => $value){
			yield '@declare($' . $name . ', ' . $value . ')';
		}


//		if (!empty($this->Params)){
//			yield '@e64("' . base64_encode(json_encode($this->Params)) . '")';
//		}

		yield from parent::read();

		yield '@end';

		yield '@yield(' . $hash . ')';
	}

}
