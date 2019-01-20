<?php
namespace Yeti\Main\Model\Abstracts;

use \Able\Helpers\Arr;

trait TArgumentable {

	/**
	 * @return array
	 */
	public final function getArgumentsAttribute(): array {
		return (array)json_decode(Arr::get($this->attributes, 'arguments'), true);
	}

	/**
	 * @param array $Arguments
	 */
	public final function setArgumentsAttribute(array $Arguments = []): void {
		$this->attributes['arguments'] = json_encode(array_filter($Arguments));
	}
}
