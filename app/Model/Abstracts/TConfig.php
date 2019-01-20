<?php
namespace Yeti\Main\Model\Abstracts;

trait TConfig {

	/**
	 * @param string $name
	 * @return string
	 */
	protected function fromConfig($name){
		return isset($this->attributes['config']) ? array_merge(array_fill_keys([$name], null),
			json_decode($this->attributes['config'], true))[$name] : null;
	}

	/**
	 * @param string $name
	 * @param string $value
	 */
	protected function toConfig($name, $value){
		$this->attributes['config'] = json_encode(array_merge(isset($this->attributes['config'])
			? json_decode($this->attributes['config'], true) : [], [$name => $value]));
	}

	/**
	 * @return array
	 */
	public final function getConfigAttribute() {
		return (array)json_decode($this->attributes['config'], true);
	}

	/**
	 * @param array $Values
	 */
	public final function setConfigAttribute(array $Values) {
		$this->attributes['config'] = json_encode($Values);
	}

	public function toArray(){
		return array_merge($this->getConfigAttribute(),
			parent::toArray());
	}

}
