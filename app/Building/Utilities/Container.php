<?php
namespace Yeti\Main\Building\Utilities;

use Able\IO\Abstractions\ISource;
use \Able\IO\ReadingBuffer;
use \Able\IO\ReadingContainer;

use \Yeti\Core\Model\Template;

class Container extends ReadingBuffer {

	/**
	 * @var string
	 */
	private $name = null;

	/**
	 * Container constructor.
	 * @param ISource $Source
	 */
	public final function __construct(ISource $Source) {
		parent::__construct($Source);

		/**
		 * The default name will be used as a section name
		 * if none alternative name is assigned.
		 */
		$this->name = md5(microtime(true));
	}

	/**
	 * @param string $name
	 * @return Container
	 */
	public final function setAlternateName(string $name): Container {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public final function getName(): string {
		return $this->name;
	}

	/**
	 * @return \Generator
	 * @throws \Exception
	 */
	public final function read(): \Generator {
		yield '@section(' . $this->name . ')';
		yield from parent::read();
		yield '@end';
	}
}
