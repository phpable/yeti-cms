<?php
namespace Yeti\Main\Building\Containers;

use \Yeti\Main\Building\Containers\Abstractions\AContainer;

use \Yeti\Main\Building\Storages\Metas;
use \Yeti\Main\Building\Storages\Externals;

class Layout extends AContainer {

	/**
	 * @return \Generator
	 * @throws \Exception
	 */
	public final function read(): \Generator {
		yield '@extends(base:project)';
		yield '@build(constants)';
		yield '@section(main)';
		yield from parent::read();
		yield '@end';
	}
}
