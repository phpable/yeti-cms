<?php
namespace Yeti\Main\Model\Abstracts;

trait TUrlAttribute {

	/**
	 * @return string
	 */
	public function getUrlAttribute(){
		return preg_replace('/\s+/', '_', strtolower(trim($this->attributes['url'])));
	}

}
