<?php
namespace Yeti\Main\Building\Exports;

use \Able\Reglib\Regex;

use \Yeti\Main\Building\Exports\Utilities\Export;

class Exporter {

	/**
	 * @var array
	 */
	private $Exportable = [];

	/**
	 * @return array
	 */
	public final function getExportableItems(): array {
		return $this->Exportable;
	}

	/**
	 * Exporter constructor.
	 * @param array $Exportable
	 */
	public final function __construct(array $Exportable = []) {
		$this->Exportable = $Exportable;
	}

	/**
	 * @param string $name
	 * @throws \Exception
	 * @return Export
	 */
	public final function export(string $name) {
		if (!Regex::create('/^' . Regex::RE_VARIABLE . '@' . Regex::RE_VARIABLE . ':' . Regex::RE_VARIABLE .  '$/')->check($name)) {
			throw new \Exception(sprintf('Invalid entity name: %s!', $name));
		}

		if (!isset($this->Exportable[$name])) {
			throw new \Exception(sprintf('Undefined entity: %s!', $name));
		}

		if (!class_exists($this->Exportable[$name])) {
			throw new \Exception(sprintf('Undefined class: %s!', $this->Exportable[$name]));
		}

		return new Export((new $this->Exportable[$name]())->newQuery());
	}
}
