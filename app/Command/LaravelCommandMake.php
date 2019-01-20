<?php
namespace Yeti\Main\Command;

use \Illuminate\Foundation\Console\CommandMakeCommand;

class LaravelCommandMake extends CommandMakeCommand {

	/**
	 * Get the default namespace for the class.
	 * @param  string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace) {
		return $rootNamespace . '\Macros';
	}

}
