<?php
namespace Yeti\Main\Macros;

use \Illuminate\Contracts\Bus\SelfHandling;

use \Intervention\Image\ImageManager;

use \Able\IO\File;
use \Able\Helpers\Src;

class ProcessMedia implements SelfHandling {

	/**
	 * @var File
	 */
	private $File = null;

	/**
	 * @var string
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
	 * @throws \Exception
	 */
	public final function processAuthor(): void {
		$Image = (new ImageManager(['driver' => 'imagick']))
			->make($this->File->toString());

		if ($Image->width() != 300 || $Image->height() != 300) {

			$Image = $Image->height() > $Image->width()
				? $Image->widen(300)
				: $Image->heighten(300);


			$Image->crop(300, 300,
				floor(($Image->width() - 300) / 2),
				floor(($Image->height() - 300) / 2));
		}

		$Image->save($this->File->toString());
	}
}
