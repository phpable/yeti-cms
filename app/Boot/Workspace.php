<?php
namespace Yeti\Main\Boot;

use \Able\IO\Path;
use \Able\IO\Directory;

class Workspace {

	/**
	 * @var Directory
	 */
	private $Directory = null;

	/**
	 * @param Path $Path
	 * @throws \Exception
	 */
	public function __construct(Path $Path) {
		if ($Path->isExists() && !$Path->isDirectory()){
			throw new \Exception('The workspace path is not a directory!');
		}

		$this->Directory = $Path->forceDirectory();
	}
}
