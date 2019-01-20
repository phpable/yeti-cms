<?php
namespace Yeti\Main\Model\Abstracts;

use \Able\IO\ReadingBuffer;

trait TSourcable {

	/**
	 * @return ReadingBuffer
	 */
	public final function toReadingBuffer(): ReadingBuffer {
		return new ReadingBuffer($this);
	}
}
