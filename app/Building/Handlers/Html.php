<?php
namespace Yeti\Main\Building\Handlers;

use \Yeti\Main\Building\Handlers\Abstractions\AHandler;

class Html extends AHandler {

	/**
	 * @return \Generator
	 * @throws \Exception
	 */
	public final function read(): \Generator {
		yield from parent::read();
	}
}
