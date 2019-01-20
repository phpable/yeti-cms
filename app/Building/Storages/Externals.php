<?php
namespace Yeti\Main\Building\Storages;

use \Yeti\Main\Building\Storages\Structures\SExternal;

class Externals {

	/**
	 * @var SExternal[]
	 */
	private $Externals = [];

	/**
	 * @param SExternal[]
	 * @throws \Exception
	 */
	public function __construct(array $Externals = []) {
		if (count($Externals) > 0) {
			$this->addExternals($Externals);
		}
	}

	/**
	 * @param array $Externals
	 * @throws \Exception
	 */
	public final function addExternals(array $Externals): void {
		foreach ($Externals as $External){

			if (!($External instanceof SExternal)){
				throw new \Exception('Invalid record type!');
			}

			$this->addExternal($External);
		}
	}

	/**
	 * @param SExternal $External
	 * @return void
	 */
	public final function addExternal(SExternal $External): void {
		array_push($this->Externals, $External);
	}

	/**
	 * @return string
	 */
	public final function toHtml(): string {
		return implode("\n", array_map(function(SExternal $External){
			return $External->toHtml();
		}, $this->Externals));
	}

}
