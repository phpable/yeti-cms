<?php
namespace Yeti\Main\Building\Storages;

use \Yeti\Main\Building\Storages\Structures\SMeta;

class Metas {

	/**
	 * @var SMeta[]
	 */
	private $Metas = [];

	/**
	 * @param SMeta[]
	 * @throws \Exception
	 */
	public function __construct(array $Metas = []) {
		if (count($Metas) > 0) {
			$this->addMetas($Metas);
		}
	}


	/**
	 * @param array $Metas
	 * @throws \Exception
	 */
	public final function addMetas(array $Metas): void {
		foreach ($Metas as $Meta){

			if (!($Meta instanceof SMeta)){
				throw new \Exception('Invalid record type!');
			}

			$this->addMeta($Meta);
		}
	}

	/**
	 * @param SMeta $Meta
	 * @return void
	 * @throws \Exception
	 */
	public final function addMeta(SMeta $Meta): void {
		$this->Metas[] = $Meta;
	}

	/**
	 * @return string
	 */
	public final function toHtml(): string {
		return implode("\n", array_map(function(SMeta $Meta){
			return $Meta->toHtml();
		}, $this->Metas));
	}

}
