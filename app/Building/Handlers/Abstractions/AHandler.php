<?php
namespace Yeti\Main\Building\Handlers\Abstractions;

use \Able\Helpers\Src;

use \Able\IO\ReadingBuffer;
use \Able\IO\ReadingContainer;

use \Yeti\Core\Model\Template;

abstract class AHandler extends ReadingBuffer {

	/**
	 * @param Template $Template
	 * @return AHandler
	 * @throws \Exception
	 */
	public final static function produce(Template $Template): AHandler {
		if (!class_exists($class = Src::lns(static::class, 2)
			. '\\' . Src::tcm($Template->type))){

				throw new \Exception(sprintf('Undefined container class %s', $class));
		}

		return new $class($Template);
	}
}
