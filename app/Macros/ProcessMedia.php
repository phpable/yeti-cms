<?php
namespace Yeti\Main\Macros;

use \Illuminate\Contracts\Bus\SelfHandling;

use \Intervention\Image\ImageManager;

use \Able\IO\File;
use \Able\Helpers\Src;

class ProcessMedia implements SelfHandling {

	/**
	 * @var null
	 */
	private $File = null;

	/**
	 * @var null
	 */
	private $type = null;

	/**
	 * UpdateExternals constructor.
	 * @param File $File
	 * @param string $type
	 */
	public function __construct(File $File, string $type) {
		$this->File = $File;
		$this->type = $type;
	}

	/**
	 * @throws \Exception
	 */
	public function handle() {
		if (method_exists($this, $method = 'process' . Src::tcm($this->type))){
			$this->{$method}();
		}
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public final function processBlog(): void {
		(new ImageManager(['driver' => 'imagick']))
			->make($this->File->toString())->resize(980, null, function ($Constraint) {
				$Constraint->aspectRatio();
				$Constraint->upsize();
			})->save($this->File->toString());
	}

	/**
	 * @return void
	 */
	public final function processAuthor(): void {
		_dumpe(__METHOD__);
	}
}
