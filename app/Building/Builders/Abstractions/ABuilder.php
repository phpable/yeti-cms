<?php
namespace Yeti\Main\Building\Builders\Abstractions;

use \Yeti\Core\Model\Page;

use \Able\IO\Path;
use \Able\IO\Directory;

abstract class ABuilder {

//	/**
//	 * @var Directory
//	 */
//	private $TargetDirectory = null;
//
//	/**
//	 * @return Directory
//	 */
//	protected final function getTargetDirectory(): Directory {
//		return $this->TargetDirectory;
//	}
//
//	/**
//	 * @param string ...$fragments
//	 * @return Directory
//	 * @throws \Exception
//	 */
//	protected final function forceTargetDirectory(string ...$fragments): Directory {
//		return (new Path($this->getTargetDirectory(), ...$fragments))->forceDirectory();
//	}

//	/**
//	 * @param Directory $Directory
//	 * @throws \Exception
//	 */
//	public function __construct(Directory $Directory){
//		$this->TargetDirectory = $Directory;
//	}

	/**
	 * @var Page
	 */
	protected $Page = null;

	/**
	 * @param Page $Page
	 * @throws \Exception
	 */
	public function __construct(Page $Page){
		if (!preg_match('/^[A-Za-z]+[A-Za-z_-]/', $Page->name)){
			throw new \Exception('Page name is invalid or empty!');
		}

		if ($Page->is_hidden) {
			throw new \Exception('Page is hidden!');
		}

		if (is_null($Page->layout)) {
			throw new \Exception('Undefined layout!');
		}

		$this->Page = $Page;
	}

	/**
	 * @param Directory $Directory
	 */
	abstract public function build(Directory $Directory): void;
}

