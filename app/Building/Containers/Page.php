<?php
namespace Yeti\Main\Building\Containers;

use \Yeti\Main\Building\Containers\Abstractions\AContainer;

class Page extends AContainer {

	public final function read(): \Generator {
		yield from parent::read();
	}
}
