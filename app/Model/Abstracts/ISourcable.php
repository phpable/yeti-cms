<?php
namespace Yeti\Main\Model\Abstracts;

use \Able\IO\Abstractions\ISource;
use \Able\IO\Abstractions\ILocated;

use \Able\IO\ReadingBuffer;

interface ISourcable extends ISource, ILocated {

	/**
	 * @return ReadingBuffer
	 */
	public function toReadingBuffer(): ReadingBuffer;
}
