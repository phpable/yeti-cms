<?php
namespace Yeti\Main\Building\Handlers;

use \Yeti\Main\Building\Handlers\Abstractions\AHandler;

class Js extends AHandler {

	/**
	 * @return \Generator
	 * @throws \Exception
	 */
	public final function read(): \Generator {
		$this->process(function(string $content){
			if (!empty($content)) {
				return (new \MatthiasMullie\Minify\JS())->add($content)->minify() . ';';
			}
		});

		yield '@section(js)';
		yield from parent::read();
		yield '@end';
	}
}
