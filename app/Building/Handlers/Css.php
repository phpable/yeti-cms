<?php
namespace Yeti\Main\Building\Handlers;

use \Yeti\Main\Building\Handlers\Abstractions\AHandler;

class Css extends AHandler {

	/**
	 * @return \Generator
	 * @throws \Exception
	 */
	public final function read(): \Generator {
		yield '@section(css)';
		yield from parent::read();
		yield '@end';
	}
}
