<?php
namespace Yeti\Main\Command;

use \Illuminate\Foundation\Console\CommandMakeCommand;

class LaravelMacrosMake extends CommandMakeCommand {

	/**
	 * The console command name.
	 * @var string
	 */
	protected $name = 'make:macros';

	/**
	 * Get the default namespace for the class.
	 * @param  string $rootNamespace
	 * @return string
	 */
	protected function getDefaultNamespace($rootNamespace) {
		return $rootNamespace . '\Macros';
	}

}
