<?php
namespace Yeti\Main\Model\Abstracts;

trait TUrlAttribute {

	/**
	 * @param string $url
	 * @throws \Exception
	 */
	public final function setUrlAttribute(string $url): void {
		if (empty($url)){
			throw new \Exception('Topic URL cannot be empty!');
		}

		$this->attributes['url'] = $url;
	}

	/**
	 * @return string
	 */
	public function getUrlAttribute(){
		return preg_replace('/\s+/', '_', strtolower(trim($this->attributes['url'])));
	}

}
