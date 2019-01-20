<?php
namespace Yeti\Main\Command;

use \Illuminate\Foundation\Console\ConsoleMakeCommand;

class LaravelConsoleMake extends ConsoleMakeCommand {

	/**
	 * Get the default namespace for the class.
	 * @param  string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace) {
		return $rootNamespace . '\Command';
	}

}
